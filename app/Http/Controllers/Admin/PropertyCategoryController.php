<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PropertyCategoryController extends Controller
{
    public function index(): View
    {
        return view('backend.property_categories.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));

        $recordsTotal = PropertyCategory::query()->count();

        $query = PropertyCategory::query()->with('parent');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('meta_title', 'like', '%'.$search.'%')
                    ->orWhereHas('parent', fn ($p) => $p->where('name', 'like', '%'.$search.'%'));
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $orderColumns = ['sort_order', 'sort_order', 'name', 'parent_id', 'slug', 'is_published', 'id'];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'sort_order';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'sort_order';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', $orderDir);

        $rows = $query->skip($start)->take($length)->get();

        $token = csrf_token();
        $data = $rows->map(function (PropertyCategory $category) use ($token) {
            $status = $category->is_published
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $imgUrl = $category->bannerImageUrl();
            $imgCell = $imgUrl
                ? '<img src="'.e($imgUrl).'" alt="" class="rounded border" style="width:48px;height:48px;object-fit:cover;">'
                : '<span class="text-muted small">—</span>';

            $parentCell = $category->parent
                ? e(Str::limit($category->parent->name, 36))
                : '<span class="text-muted small">—</span>';

            $editUrl = route('admin.property_categories.edit', $category);
            $deleteUrl = route('admin.property_categories.destroy', $category);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this category? Child categories become top-level; listings may lose their category link.\');">'
                .'<input type="hidden" name="_token" value="'.e($token).'">'
                .'<input type="hidden" name="_method" value="DELETE">'
                .'<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>';

            return [
                (string) $category->sort_order,
                $imgCell,
                '<span class="fw-semibold">'.e(Str::limit($category->name, 72)).'</span>',
                $parentCell,
                '<code class="small">'.e(Str::limit($category->slug, 40)).'</code>',
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
        return view('backend.property_categories.create', [
            'category' => new PropertyCategory(['is_published' => true, 'sort_order' => 0]),
            'parentSelectOptions' => PropertyCategory::nestedSelectOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedCategoryFields($request);

        $slugBase = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($data['name']);
        $slug = $this->uniqueSlug($slugBase);

        $imagePath = null;
        if ($request->hasFile('category_image')) {
            $imagePath = $request->file('category_image')->store('property-categories', 'public');
        }

        PropertyCategory::create(array_merge($data, [
            'slug' => $slug,
            'image_path' => $imagePath,
        ]));

        return redirect()
            ->route('admin.property_categories.index')
            ->with('status', 'Category created.');
    }

    public function edit(PropertyCategory $property_category): View
    {
        $property_category->load('children');

        return view('backend.property_categories.edit', [
            'category' => $property_category,
            'parentSelectOptions' => PropertyCategory::nestedSelectOptions($property_category->branchIds()),
        ]);
    }

    public function update(Request $request, PropertyCategory $property_category): RedirectResponse
    {
        $excludeParentIds = $property_category->branchIds();

        $data = $this->validatedCategoryFields($request, $excludeParentIds, $property_category->id);

        $slugInput = trim((string) $request->input('slug', ''));
        if ($slugInput !== '') {
            $slug = $this->uniqueSlug(Str::slug($slugInput), $property_category->id);
        } else {
            $slug = $this->uniqueSlug(Str::slug($data['name']), $property_category->id);
        }

        $property_category->fill(array_merge($data, ['slug' => $slug]));

        if ($request->boolean('remove_category_image')) {
            if ($property_category->image_path) {
                Storage::disk('public')->delete($property_category->image_path);
            }
            $property_category->image_path = null;
        }

        if ($request->hasFile('category_image')) {
            if ($property_category->image_path) {
                Storage::disk('public')->delete($property_category->image_path);
            }
            $property_category->image_path = $request->file('category_image')->store('property-categories', 'public');
        }

        $property_category->save();

        return redirect()
            ->route('admin.property_categories.edit', $property_category)
            ->with('status', 'Category saved.');
    }

    public function destroy(PropertyCategory $property_category): RedirectResponse
    {
        if ($property_category->image_path) {
            Storage::disk('public')->delete($property_category->image_path);
        }
        $property_category->delete();

        return redirect()
            ->route('admin.property_categories.index')
            ->with('status', 'Category deleted.');
    }

    /**
     * @param  array<int, int>  $excludeParentIds
     * @return array<string, mixed>
     */
    private function validatedCategoryFields(Request $request, array $excludeParentIds = [], ?int $ignoreId = null): array
    {
        $rawParent = $request->input('parent_id');
        if ($rawParent === '' || $rawParent === null || $rawParent === '0') {
            $request->merge(['parent_id' => null]);
        }

        $validated = $request->validate([
            'parent_id' => [
                'nullable',
                'exists:property_categories,id',
                Rule::notIn($excludeParentIds),
            ],
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('property_categories', 'slug')->ignore($ignoreId),
            ],
            'description' => 'nullable|string|max:10000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:5000',
            'meta_keywords' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:2000',
            'category_image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_category_image' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
        ]);

        foreach (['slug', 'category_image', 'remove_category_image'] as $strip) {
            unset($validated[$strip]);
        }

        $parentId = $validated['parent_id'] ?? null;
        if ($parentId === null || $parentId === '' || $parentId === 0) {
            $parentId = null;
        } else {
            $parentId = (int) $parentId;
        }

        unset($validated['parent_id']);

        return array_merge($validated, [
            'parent_id' => $parentId,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base !== '' ? $base : 'category';
        $candidate = $slug;
        $i = 2;
        while (PropertyCategory::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$i++;
        }

        return $candidate;
    }
}
