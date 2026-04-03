<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PropertyTypeController extends Controller
{
    public function index(): View
    {
        return view('backend.property_types.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));

        $recordsTotal = PropertyType::query()->count();

        $query = PropertyType::query();
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
        $orderColumns = ['sort_order', 'name', 'slug', 'is_published', 'id'];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'sort_order';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'sort_order';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', $orderDir);

        $rows = $query->skip($start)->take($length)->get();

        $token = csrf_token();
        $data = $rows->map(function (PropertyType $type) use ($token) {
            $status = $type->is_published
                ? '<span class="badge bg-success">Published</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $editUrl = route('admin.property_types.edit', $type);
            $deleteUrl = route('admin.property_types.destroy', $type);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this type? Listings may lose their type link.\');">'
                .'<input type="hidden" name="_token" value="'.e($token).'">'
                .'<input type="hidden" name="_method" value="DELETE">'
                .'<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>';

            return [
                (string) $type->sort_order,
                '<span class="fw-semibold">'.e(Str::limit($type->name, 80)).'</span>',
                '<code class="small">'.e(Str::limit($type->slug, 40)).'</code>',
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
        return view('backend.property_types.create', [
            'type' => new PropertyType(['is_published' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:10000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:5000',
            'meta_keywords' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
        ]);

        $slugBase = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($data['name']);
        $slug = $this->uniqueSlug($slugBase);

        PropertyType::create([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()
            ->route('admin.property_types.index')
            ->with('status', 'Property type created.');
    }

    public function edit(PropertyType $property_type): View
    {
        return view('backend.property_types.edit', ['type' => $property_type]);
    }

    public function update(Request $request, PropertyType $property_type): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('property_types', 'slug')->ignore($property_type->id),
            ],
            'description' => 'nullable|string|max:10000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:5000',
            'meta_keywords' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:999999',
            'is_published' => 'nullable|boolean',
        ]);

        $slugInput = trim((string) $request->input('slug', ''));
        if ($slugInput !== '') {
            $slug = $this->uniqueSlug(Str::slug($slugInput), $property_type->id);
        } else {
            $slug = $this->uniqueSlug(Str::slug($data['name']), $property_type->id);
        }

        $property_type->fill([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_published' => $request->boolean('is_published'),
        ]);
        $property_type->save();

        return redirect()
            ->route('admin.property_types.edit', $property_type)
            ->with('status', 'Property type saved.');
    }

    public function destroy(PropertyType $property_type): RedirectResponse
    {
        $property_type->delete();

        return redirect()
            ->route('admin.property_types.index')
            ->with('status', 'Property type deleted.');
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base !== '' ? $base : 'type';
        $candidate = $slug;
        $i = 2;
        while (PropertyType::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$i++;
        }

        return $candidate;
    }
}
