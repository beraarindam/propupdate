<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientReview;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientReviewController extends Controller
{
    public function index(): View
    {
        return view('backend.client_reviews.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));
        $recordsTotal = ClientReview::query()->count();

        $query = ClientReview::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('reviewer_name', 'like', '%'.$search.'%')
                    ->orWhere('content', 'like', '%'.$search.'%');
            });
        }
        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $orderColumns = ['sort_order', 'sort_order', 'reviewer_name', 'rating', 'is_published', 'id'];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'sort_order';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'sort_order';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', $orderDir);

        $rows = $query->skip($start)->take($length)->get();
        $token = csrf_token();
        $data = $rows->map(function (ClientReview $item) use ($token) {
            $status = $item->is_published
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $thumb = $item->avatarUrl();
            $thumbCell = $thumb
                ? '<img src="'.e($thumb).'" alt="" class="rounded-circle border" style="width:44px;height:44px;object-fit:cover;">'
                : '<span class="text-muted small">—</span>';

            $stars = str_repeat('★', $item->ratingStars()).str_repeat('☆', 5 - $item->ratingStars());

            $editUrl = route('admin.client_reviews.edit', $item);
            $deleteUrl = route('admin.client_reviews.destroy', $item);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this review?\');">'
                .'<input type="hidden" name="_token" value="'.e($token).'">'
                .'<input type="hidden" name="_method" value="DELETE">'
                .'<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>';

            return [
                (string) $item->sort_order,
                $thumbCell,
                '<span class="fw-semibold">'.e(Str::limit($item->reviewer_name, 40)).'</span>',
                '<span class="text-warning" title="'.$item->ratingStars().'/5">'.e($stars).'</span>',
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
        return view('backend.client_reviews.create', [
            'item' => new ClientReview(['is_published' => true, 'sort_order' => 0, 'rating' => 5]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('client-reviews', 'public');
        }

        ClientReview::create([
            'reviewer_name' => $data['reviewer_name'],
            'content' => $data['content'],
            'rating' => $data['rating'],
            'image_path' => $path,
            'image_url' => $data['image_url'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.client_reviews.index')->with('status', 'Review created.');
    }

    public function edit(ClientReview $client_review): View
    {
        return view('backend.client_reviews.edit', ['item' => $client_review]);
    }

    public function update(Request $request, ClientReview $client_review): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->boolean('remove_image') && $client_review->image_path) {
            Storage::disk('public')->delete($client_review->image_path);
            $client_review->image_path = null;
        }
        if ($request->hasFile('image')) {
            if ($client_review->image_path) {
                Storage::disk('public')->delete($client_review->image_path);
            }
            $client_review->image_path = $request->file('image')->store('client-reviews', 'public');
        }

        $client_review->fill([
            'reviewer_name' => $data['reviewer_name'],
            'content' => $data['content'],
            'rating' => $data['rating'],
            'image_url' => $data['image_url'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);
        $client_review->save();

        return redirect()->route('admin.client_reviews.index')->with('status', 'Review saved.');
    }

    public function destroy(ClientReview $client_review): RedirectResponse
    {
        if ($client_review->image_path) {
            Storage::disk('public')->delete($client_review->image_path);
        }
        $client_review->delete();

        return redirect()->route('admin.client_reviews.index')->with('status', 'Review deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'reviewer_name' => 'required|string|max:120',
            'content' => 'required|string|max:5000',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'image_url' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
            'remove_image' => 'nullable|boolean',
        ]);

        $urlTrim = isset($data['image_url']) ? trim((string) $data['image_url']) : '';
        if ($urlTrim !== '' && ! preg_match('/\Ahttps?:\/\//i', $urlTrim)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'image_url' => 'Image URL must start with http:// or https://',
            ]);
        }
        $data['image_url'] = $urlTrim !== '' ? $urlTrim : null;

        return $data;
    }
}
