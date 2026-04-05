<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExclusiveResaleListing;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExclusiveResaleListingController extends Controller
{
    public function index(): View
    {
        return view('backend.exclusive_resale_listings.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));

        $recordsTotal = ExclusiveResaleListing::query()->count();

        $query = ExclusiveResaleListing::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('property_code', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%');
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $orderColumns = ['sort_order', 'sort_order', 'property_code', 'title', 'is_published', 'id'];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'sort_order';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'sort_order';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', $orderDir);

        $rows = $query->skip($start)->take($length)->get();

        $token = csrf_token();
        $data = $rows->map(function (ExclusiveResaleListing $row) use ($token) {
            $status = $row->is_published
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $thumb = $row->imagePublicUrl();
            $thumbCell = $thumb
                ? '<img src="'.e($thumb).'" alt="" class="rounded-circle border" style="width:48px;height:48px;object-fit:cover;">'
                : '<span class="text-muted small">—</span>';

            $code = e($row->displayCode());
            $editUrl = route('admin.exclusive_resale_listings.edit', $row);
            $deleteUrl = route('admin.exclusive_resale_listings.destroy', $row);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this listing?\');">'
                .'<input type="hidden" name="_token" value="'.e($token).'">'
                .'<input type="hidden" name="_method" value="DELETE">'
                .'<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>';

            return [
                (string) $row->sort_order,
                $thumbCell,
                '<span class="fw-semibold">'.$code.'</span>',
                e(Str::limit($row->title, 70)),
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
        return view('backend.exclusive_resale_listings.create', [
            'listing' => new ExclusiveResaleListing([
                'is_published' => true,
                'sort_order' => 0,
                'status_badge' => 'Ready To Move',
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'property_code' => 'nullable|string|max:64',
            'title' => 'required|string|max:255',
            'status_badge' => 'nullable|string|max:120',
            'location' => 'nullable|string|max:500',
            'property_type' => 'nullable|string|max:120',
            'configuration' => 'nullable|string|max:120',
            'area_display' => 'nullable|string|max:120',
            'market_price' => 'nullable|string|max:120',
            'asking_price' => 'nullable|string|max:120',
            'rate_per_sqft' => 'nullable|string|max:120',
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
                ->withErrors(['image' => 'Upload a photo or enter an external image URL.']);
        }
        if (! $request->hasFile('image') && $urlTrim !== '' && ! preg_match('/\Ahttps?:\/\//i', $urlTrim)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['image_url' => 'Image URL must start with http:// or https://']);
        }

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('exclusive-resale/listings', 'public');
        }

        ExclusiveResaleListing::create([
            'property_code' => $data['property_code'] ?? null,
            'title' => $data['title'],
            'status_badge' => $data['status_badge'] ?? null,
            'location' => $data['location'] ?? null,
            'property_type' => $data['property_type'] ?? null,
            'configuration' => $data['configuration'] ?? null,
            'area_display' => $data['area_display'] ?? null,
            'market_price' => $data['market_price'] ?? null,
            'asking_price' => $data['asking_price'] ?? null,
            'rate_per_sqft' => $data['rate_per_sqft'] ?? null,
            'image_path' => $path,
            'image_url' => $urlTrim !== '' ? $urlTrim : null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? now() : null,
        ]);

        return redirect()
            ->route('admin.exclusive_resale_listings.index')
            ->with('status', 'Exclusive resale listing created.');
    }

    public function edit(ExclusiveResaleListing $exclusive_resale_listing): View
    {
        return view('backend.exclusive_resale_listings.edit', [
            'listing' => $exclusive_resale_listing,
        ]);
    }

    public function update(Request $request, ExclusiveResaleListing $exclusive_resale_listing): RedirectResponse
    {
        $data = $request->validate([
            'property_code' => 'nullable|string|max:64',
            'title' => 'required|string|max:255',
            'status_badge' => 'nullable|string|max:120',
            'location' => 'nullable|string|max:500',
            'property_type' => 'nullable|string|max:120',
            'configuration' => 'nullable|string|max:120',
            'area_display' => 'nullable|string|max:120',
            'market_price' => 'nullable|string|max:120',
            'asking_price' => 'nullable|string|max:120',
            'rate_per_sqft' => 'nullable|string|max:120',
            'image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'image_url' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->boolean('remove_image') && $exclusive_resale_listing->image_path) {
            Storage::disk('public')->delete($exclusive_resale_listing->image_path);
            $exclusive_resale_listing->image_path = null;
        }

        if ($request->hasFile('image')) {
            if ($exclusive_resale_listing->image_path) {
                Storage::disk('public')->delete($exclusive_resale_listing->image_path);
            }
            $exclusive_resale_listing->image_path = $request->file('image')->store('exclusive-resale/listings', 'public');
        }

        $urlTrim = isset($data['image_url']) ? trim((string) $data['image_url']) : '';

        $exclusive_resale_listing->fill([
            'property_code' => $data['property_code'] ?? null,
            'title' => $data['title'],
            'status_badge' => $data['status_badge'] ?? null,
            'location' => $data['location'] ?? null,
            'property_type' => $data['property_type'] ?? null,
            'configuration' => $data['configuration'] ?? null,
            'area_display' => $data['area_display'] ?? null,
            'market_price' => $data['market_price'] ?? null,
            'asking_price' => $data['asking_price'] ?? null,
            'rate_per_sqft' => $data['rate_per_sqft'] ?? null,
            'image_url' => $urlTrim !== '' ? $urlTrim : null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->boolean('is_published') && $exclusive_resale_listing->published_at === null) {
            $exclusive_resale_listing->published_at = now();
        }

        if ($exclusive_resale_listing->image_path === null && ($exclusive_resale_listing->image_url === null || trim((string) $exclusive_resale_listing->image_url) === '')) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['image' => 'Upload a photo or enter an external image URL.']);
        }
        $finalUrl = trim((string) ($exclusive_resale_listing->image_url ?? ''));
        if ($exclusive_resale_listing->image_path === null && $finalUrl !== '' && ! preg_match('/\Ahttps?:\/\//i', $finalUrl)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['image_url' => 'Image URL must start with http:// or https://']);
        }

        $exclusive_resale_listing->save();

        return redirect()
            ->route('admin.exclusive_resale_listings.edit', $exclusive_resale_listing)
            ->with('status', 'Listing saved.');
    }

    public function destroy(ExclusiveResaleListing $exclusive_resale_listing): RedirectResponse
    {
        if ($exclusive_resale_listing->image_path) {
            Storage::disk('public')->delete($exclusive_resale_listing->image_path);
        }
        $exclusive_resale_listing->delete();

        return redirect()
            ->route('admin.exclusive_resale_listings.index')
            ->with('status', 'Listing deleted.');
    }
}
