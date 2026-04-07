<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AwardController extends Controller
{
    public function index(): View
    {
        return view('backend.awards.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));
        $recordsTotal = Award::query()->count();

        $query = Award::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('subtitle', 'like', '%'.$search.'%');
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
        $data = $rows->map(function (Award $item) use ($token) {
            $status = $item->is_published
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $thumb = $item->imagePublicUrl();
            $thumbCell = $thumb
                ? '<img src="'.e($thumb).'" alt="" class="rounded border" style="width:56px;height:56px;object-fit:cover;">'
                : '<span class="text-muted small">—</span>';

            $editUrl = route('admin.awards.edit', $item);
            $deleteUrl = route('admin.awards.destroy', $item);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this award image?\');">'
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
        return view('backend.awards.create', [
            'item' => new Award(['is_published' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:2000',
            'image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'image_url' => 'nullable|string|max:2000',
            'link_url' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
        ]);

        $urlTrim = isset($data['image_url']) ? trim((string) $data['image_url']) : '';
        if (! $request->hasFile('image') && $urlTrim === '') {
            return redirect()->back()->withInput()->withErrors(['image' => 'Upload an image or enter an external image URL.']);
        }
        if (! $request->hasFile('image') && $urlTrim !== '' && ! preg_match('/\Ahttps?:\/\//i', $urlTrim)) {
            return redirect()->back()->withInput()->withErrors(['image_url' => 'Image URL must start with http:// or https://']);
        }

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('awards/items', 'public');
        }

        Award::create([
            'title' => $data['title'] ?? null,
            'subtitle' => $data['subtitle'] ?? null,
            'image_path' => $path,
            'image_url' => $urlTrim !== '' ? $urlTrim : null,
            'link_url' => ! empty($data['link_url']) ? trim((string) $data['link_url']) : null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.awards.index')->with('status', 'Award item created.');
    }

    public function edit(Award $award): View
    {
        return view('backend.awards.edit', ['item' => $award]);
    }

    public function update(Request $request, Award $award): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:2000',
            'image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'image_url' => 'nullable|string|max:2000',
            'link_url' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->boolean('remove_image') && $award->image_path) {
            Storage::disk('public')->delete($award->image_path);
            $award->image_path = null;
        }
        if ($request->hasFile('image')) {
            if ($award->image_path) {
                Storage::disk('public')->delete($award->image_path);
            }
            $award->image_path = $request->file('image')->store('awards/items', 'public');
        }

        $urlTrim = isset($data['image_url']) ? trim((string) $data['image_url']) : '';
        $award->fill([
            'title' => $data['title'] ?? null,
            'subtitle' => $data['subtitle'] ?? null,
            'image_url' => $urlTrim !== '' ? $urlTrim : null,
            'link_url' => ! empty($data['link_url']) ? trim((string) $data['link_url']) : null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($award->image_path === null && ($award->image_url === null || trim((string) $award->image_url) === '')) {
            return redirect()->back()->withInput()->withErrors(['image' => 'Upload an image or enter an external image URL.']);
        }
        $finalUrl = trim((string) ($award->image_url ?? ''));
        if ($award->image_path === null && $finalUrl !== '' && ! preg_match('/\Ahttps?:\/\//i', $finalUrl)) {
            return redirect()->back()->withInput()->withErrors(['image_url' => 'Image URL must start with http:// or https://']);
        }

        $award->save();

        return redirect()->route('admin.awards.index')->with('status', 'Award item saved.');
    }

    public function destroy(Award $award): RedirectResponse
    {
        if ($award->image_path) {
            Storage::disk('public')->delete($award->image_path);
        }
        $award->delete();

        return redirect()->route('admin.awards.index')->with('status', 'Award item deleted.');
    }
}

