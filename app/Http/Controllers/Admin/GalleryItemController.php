<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryItemController extends Controller
{
    public function index(): View
    {
        return view('backend.gallery_items.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));

        $recordsTotal = GalleryItem::query()->count();

        $query = GalleryItem::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('caption', 'like', '%'.$search.'%');
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $orderColumns = ['sort_order', 'sort_order', 'title', 'is_published', 'id'];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'sort_order';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'sort_order';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', $orderDir);

        $rows = $query->skip($start)->take($length)->get();

        $token = csrf_token();
        $data = $rows->map(function (GalleryItem $item) use ($token) {
            $status = $item->is_published
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $thumb = $item->imagePublicUrl();
            $thumbCell = $thumb
                ? '<img src="'.e($thumb).'" alt="" class="rounded border" style="width:56px;height:56px;object-fit:cover;">'
                : '<span class="text-muted small">—</span>';

            $editUrl = route('admin.gallery_items.edit', $item);
            $deleteUrl = route('admin.gallery_items.destroy', $item);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this gallery image?\');">'
                .'<input type="hidden" name="_token" value="'.e($token).'">'
                .'<input type="hidden" name="_method" value="DELETE">'
                .'<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>';

            return [
                (string) $item->sort_order,
                $thumbCell,
                '<span class="fw-semibold">'.e(Str::limit($item->title ?: '—', 60)).'</span>',
                $status,
                $actions,
            ];
        })->values()->all();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function create(): View
    {
        return view('backend.gallery_items.create', [
            'item' => new GalleryItem(['is_published' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:2000',
            'image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'image_url' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
        ]);

        $urlTrim = isset($data['image_url']) ? trim((string) $data['image_url']) : '';
        if (! $request->hasFile('image') && $urlTrim === '') {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['image' => 'Upload an image or enter an external image URL.']);
        }
        if (! $request->hasFile('image') && $urlTrim !== '' && ! preg_match('/\Ahttps?:\/\//i', $urlTrim)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['image_url' => 'Image URL must start with http:// or https://']);
        }

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('gallery/items', 'public');
        }

        GalleryItem::create([
            'title' => $data['title'] ?? null,
            'caption' => $data['caption'] ?? null,
            'image_path' => $path,
            'image_url' => $urlTrim !== '' ? $urlTrim : null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.gallery_items.index')
            ->with('status', 'Gallery item created.');
    }

    public function edit(GalleryItem $gallery_item): View
    {
        return view('backend.gallery_items.edit', [
            'item' => $gallery_item,
        ]);
    }

    public function update(Request $request, GalleryItem $gallery_item): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:2000',
            'image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'image_url' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->boolean('remove_image') && $gallery_item->image_path) {
            Storage::disk('public')->delete($gallery_item->image_path);
            $gallery_item->image_path = null;
        }

        if ($request->hasFile('image')) {
            if ($gallery_item->image_path) {
                Storage::disk('public')->delete($gallery_item->image_path);
            }
            $gallery_item->image_path = $request->file('image')->store('gallery/items', 'public');
        }

        $urlTrim = isset($data['image_url']) ? trim((string) $data['image_url']) : '';

        $gallery_item->fill([
            'title' => $data['title'] ?? null,
            'caption' => $data['caption'] ?? null,
            'image_url' => $urlTrim !== '' ? $urlTrim : null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($gallery_item->image_path === null && ($gallery_item->image_url === null || trim((string) $gallery_item->image_url) === '')) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['image' => 'Upload an image or enter an external image URL.']);
        }
        $finalUrl = trim((string) ($gallery_item->image_url ?? ''));
        if ($gallery_item->image_path === null && $finalUrl !== '' && ! preg_match('/\Ahttps?:\/\//i', $finalUrl)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['image_url' => 'Image URL must start with http:// or https://']);
        }

        $gallery_item->save();

        return redirect()
            ->route('admin.gallery_items.index')
            ->with('status', 'Gallery item saved.');
    }

    public function destroy(GalleryItem $gallery_item): RedirectResponse
    {
        if ($gallery_item->image_path) {
            Storage::disk('public')->delete($gallery_item->image_path);
        }
        $gallery_item->delete();

        return redirect()
            ->route('admin.gallery_items.index')
            ->with('status', 'Gallery item deleted.');
    }
}
