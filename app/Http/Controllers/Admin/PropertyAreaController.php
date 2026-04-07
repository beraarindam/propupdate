<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyArea;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PropertyAreaController extends Controller
{
    public function index(): View
    {
        return view('backend.property_areas.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));

        $recordsTotal = PropertyArea::query()->count();

        $query = PropertyArea::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('meta_title', 'like', '%'.$search.'%');
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $orderColumns = ['sort_order', 'sort_order', 'name', 'slug', 'is_published', 'id'];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'sort_order';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'sort_order';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', $orderDir);

        $rows = $query->skip($start)->take($length)->get();

        $token = csrf_token();
        $data = $rows->map(function (PropertyArea $area) use ($token) {
            $status = $area->is_published
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $imgUrl = $area->bannerImageUrl();
            $imgCell = $imgUrl
                ? '<img src="'.e($imgUrl).'" alt="" class="rounded border" style="width:48px;height:48px;object-fit:cover;">'
                : '<span class="text-muted small">—</span>';

            $editUrl = route('admin.property_areas.edit', $area);
            $deleteUrl = route('admin.property_areas.destroy', $area);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this area? Listings using it will have the area cleared.\');">'
                .'<input type="hidden" name="_token" value="'.e($token).'">'
                .'<input type="hidden" name="_method" value="DELETE">'
                .'<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>';

            return [
                (string) $area->sort_order,
                $imgCell,
                '<span class="fw-semibold">'.e(Str::limit($area->name, 72)).'</span>',
                '<code class="small">'.e(Str::limit($area->slug, 40)).'</code>',
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
        return view('backend.property_areas.create', [
            'area' => new PropertyArea(['is_published' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedAreaFields($request);

        $slugBase = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($data['name']);
        $slug = $this->uniqueSlug($slugBase);

        $imagePath = null;
        if ($request->hasFile('area_image')) {
            $imagePath = $request->file('area_image')->store('property-areas', 'public');
        }

        PropertyArea::create(array_merge($data, [
            'slug' => $slug,
            'image_path' => $imagePath,
        ]));

        return redirect()
            ->route('admin.property_areas.index')
            ->with('status', 'Area created.');
    }

    public function edit(PropertyArea $property_area): View
    {
        return view('backend.property_areas.edit', [
            'area' => $property_area,
        ]);
    }

    public function update(Request $request, PropertyArea $property_area): RedirectResponse
    {
        $data = $this->validatedAreaFields($request, $property_area->id);

        $slugInput = trim((string) $request->input('slug', ''));
        if ($slugInput !== '') {
            $slug = $this->uniqueSlug(Str::slug($slugInput), $property_area->id);
        } else {
            $slug = $this->uniqueSlug(Str::slug($data['name']), $property_area->id);
        }

        $property_area->fill(array_merge($data, ['slug' => $slug]));

        if ($request->boolean('remove_area_image')) {
            if ($property_area->image_path) {
                Storage::disk('public')->delete($property_area->image_path);
            }
            $property_area->image_path = null;
        }

        if ($request->hasFile('area_image')) {
            if ($property_area->image_path) {
                Storage::disk('public')->delete($property_area->image_path);
            }
            $property_area->image_path = $request->file('area_image')->store('property-areas', 'public');
        }

        $property_area->save();

        return redirect()
            ->route('admin.property_areas.edit', $property_area)
            ->with('status', 'Area saved.');
    }

    public function destroy(PropertyArea $property_area): RedirectResponse
    {
        if ($property_area->image_path) {
            Storage::disk('public')->delete($property_area->image_path);
        }
        $property_area->delete();

        return redirect()
            ->route('admin.property_areas.index')
            ->with('status', 'Area deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedAreaFields(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('property_areas', 'slug')->ignore($ignoreId),
            ],
            'description' => 'nullable|string|max:10000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:5000',
            'meta_keywords' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:2000',
            'area_image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_area_image' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
        ]);

        foreach (['slug', 'area_image', 'remove_area_image'] as $strip) {
            unset($validated[$strip]);
        }

        return array_merge($validated, [
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base !== '' ? $base : 'area';
        $candidate = $slug;
        $i = 2;
        while (PropertyArea::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$i++;
        }

        return $candidate;
    }
}
