<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyArea;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    private const GALLERY_MAX_FILES = 24;

    private const GALLERY_MAX_UPLOAD_BATCH = 12;

    private const FLOOR_PLAN_MAX_FILES = 24;

    private const FLOOR_PLAN_MAX_UPLOAD_BATCH = 12;

    private const MASTER_PLAN_MAX_FILES = 24;

    private const MASTER_PLAN_MAX_UPLOAD_BATCH = 12;

    public function index(): View
    {
        return view('backend.properties.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));

        $recordsTotal = Property::query()->count();

        $query = Property::query()->with(['category']);
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%')
                    ->orWhere('summary', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('locality', 'like', '%'.$search.'%')
                    ->orWhere('city', 'like', '%'.$search.'%')
                    ->orWhere('meta_title', 'like', '%'.$search.'%')
                    ->orWhere('developer_name', 'like', '%'.$search.'%');
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        // Must match <thead> column order (no "Type" column in the admin table).
        $orderColumns = [
            'sort_order',
            'title',
            'property_category_id',
            'listing_type',
            'price',
            'city',
            'is_published',
            'updated_at',
        ];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'updated_at';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'updated_at';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', 'desc');

        $rows = $query->skip($start)->take($length)->get();

        $listingLabels = Property::listingTypeOptions();

        $token = csrf_token();
        $data = $rows->map(function (Property $property) use ($token, $listingLabels) {
            $status = $property->is_published
                ? '<span class="badge bg-success">Live</span>'
                : '<span class="badge bg-secondary">Draft</span>';

            $feat = $property->is_featured
                ? '<span class="badge bg-warning text-dark">Featured</span>'
                : '<span class="text-muted small">—</span>';

            $newLaunch = $property->is_new_launch
                ? '<span class="badge bg-info text-dark">New launch</span>'
                : '<span class="text-muted small">—</span>';

            $cat = $property->category
                ? e(Str::limit($property->category->name, 28))
                : '<span class="text-muted">—</span>';

            $deal = $listingLabels[$property->listing_type] ?? $property->listing_type;
            $dealCell = '<span class="badge bg-light text-dark border">'.e($deal).'</span>';

            $priceCell = $property->price_on_request
                ? '<span class="text-muted">On request</span>'
                : ($property->price !== null
                    ? '<span class="text-nowrap">'.e($property->price_currency).' '.e(number_format((float) $property->price, 0, '.', ',')).'</span>'
                    : '<span class="text-muted">—</span>');

            $loc = collect([$property->locality, $property->city])->filter()->implode(', ');
            $locCell = $loc !== '' ? e(Str::limit($loc, 42)) : '<span class="text-muted">—</span>';

            $updated = $property->updated_at?->format('M j, Y') ?? '—';

            $liveUrl = $property->is_published ? route('properties.show', $property) : null;
            $titleCell = $liveUrl
                ? '<span class="fw-semibold"><a href="'.e($liveUrl).'" target="_blank" rel="noopener noreferrer">'.e(Str::limit($property->title, 52)).'</a></span>'
                : '<span class="fw-semibold">'.e(Str::limit($property->title, 52)).'</span>';

            $editUrl = route('admin.properties.edit', $property);
            $deleteUrl = route('admin.properties.destroy', $property);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this property listing?\');">'
                .'<input type="hidden" name="_token" value="'.e($token).'">'
                .'<input type="hidden" name="_method" value="DELETE">'
                .'<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>';

            return [
                (string) $property->sort_order,
                $titleCell,
                $cat,
                $dealCell,
                $priceCell,
                $locCell,
                '<div class="d-flex flex-wrap gap-1">'.$status.$feat.$newLaunch.'</div>',
                '<span class="text-muted small text-nowrap">'.e($updated).'</span>',
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

    public function tinymceUpload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $path = $request->file('file')->store('properties/editor', 'public');
        $url = SiteSetting::resolvePublicUrl($path);

        if ($url === null) {
            Storage::disk('public')->delete($path);

            return response()->json(['error' => 'Storage path could not be resolved.'], 422);
        }

        return response()->json(['location' => $url]);
    }

    public function create(): View
    {
        return view('backend.properties.create', [
            'property' => new Property([
                'is_published' => false,
                'is_featured' => false,
                'is_new_launch' => false,
                'sort_order' => 0,
                'listing_type' => Property::LISTING_SALE,
                'price_currency' => 'INR',
                'country' => 'India',
            ]),
            'categories' => PropertyCategory::optionsForPropertyAssign(),
            'areas' => PropertyArea::optionsForPropertyAssign(),
            'types' => PropertyType::query()->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedPropertyPayload($request);

        $slugBase = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($data['title']);
        $slug = $this->uniqueSlug($slugBase);

        $publishedAt = null;
        if ($request->boolean('is_published')) {
            $publishedAt = now();
        }

        $featuredPath = null;
        if ($request->hasFile('featured_image')) {
            $featuredPath = $request->file('featured_image')->store('properties/featured', 'public');
        }

        $galleryPaths = $this->storeNewGalleryFiles($request, []);

        $masterPlanPaths = $this->storeNewMasterPlanFiles($request, []);
        $floorPlanPaths = $this->storeNewFloorPlanFiles($request, []);

        $property = Property::create(array_merge($data, [
            'slug' => $slug,
            'published_at' => $publishedAt,
            'featured_image_path' => $featuredPath,
            'gallery_paths' => $galleryPaths,
            'master_plan_path' => null,
            'master_plan_paths' => $masterPlanPaths,
            'floor_plan_paths' => $floorPlanPaths,
        ]));

        return redirect()
            ->route('admin.properties.edit', $property)
            ->with('status', 'Property listing created.');
    }

    public function edit(Property $property): View
    {
        return view('backend.properties.edit', [
            'property' => $property,
            'categories' => PropertyCategory::optionsForPropertyAssign(),
            'areas' => PropertyArea::optionsForPropertyAssign(),
            'types' => PropertyType::query()->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Property $property): RedirectResponse
    {
        $data = $this->validatedPropertyPayload($request, $property);

        $slugInput = trim((string) $request->input('slug', ''));
        if ($slugInput !== '') {
            $slug = $this->uniqueSlug(Str::slug($slugInput), $property->id);
        } else {
            $slug = $this->uniqueSlug(Str::slug($data['title']), $property->id);
        }

        $publishedAt = $property->published_at;
        if ($request->boolean('is_published')) {
            if ($publishedAt === null) {
                $publishedAt = now();
            }
        } else {
            $publishedAt = null;
        }

        $property->fill(array_merge($data, [
            'slug' => $slug,
            'published_at' => $publishedAt,
        ]));

        if ($request->boolean('remove_featured_image')) {
            if ($property->featured_image_path) {
                Storage::disk('public')->delete($property->featured_image_path);
            }
            $property->featured_image_path = null;
        }

        if ($request->hasFile('featured_image')) {
            if ($property->featured_image_path) {
                Storage::disk('public')->delete($property->featured_image_path);
            }
            $property->featured_image_path = $request->file('featured_image')->store('properties/featured', 'public');
        }

        $existingGallery = is_array($property->gallery_paths) ? $property->gallery_paths : [];
        $remove = $request->input('remove_gallery_paths', []);
        if (! is_array($remove)) {
            $remove = [];
        }
        $remove = array_values(array_intersect($remove, $existingGallery));
        foreach ($remove as $path) {
            Storage::disk('public')->delete($path);
        }
        $kept = array_values(array_diff($existingGallery, $remove));
        $merged = $this->storeNewGalleryFiles($request, $kept);
        $property->gallery_paths = $merged;

        $existingMasterPlans = is_array($property->master_plan_paths) ? $property->master_plan_paths : [];
        if (is_string($property->master_plan_path) && $property->master_plan_path !== '') {
            array_unshift($existingMasterPlans, $property->master_plan_path);
        }
        $existingMasterPlans = array_values(array_unique(array_filter($existingMasterPlans, fn ($path) => is_string($path) && $path !== '')));
        $removeMasterPlans = $request->input('remove_master_plan_paths', []);
        if (! is_array($removeMasterPlans)) {
            $removeMasterPlans = [];
        }
        $removeMasterPlans = array_values(array_intersect($removeMasterPlans, $existingMasterPlans));
        foreach ($removeMasterPlans as $path) {
            Storage::disk('public')->delete($path);
        }
        $keptMasterPlans = array_values(array_diff($existingMasterPlans, $removeMasterPlans));
        $property->master_plan_paths = $this->storeNewMasterPlanFiles($request, $keptMasterPlans);
        $property->master_plan_path = null;

        $existingFloors = is_array($property->floor_plan_paths) ? $property->floor_plan_paths : [];
        $removeFloors = $request->input('remove_floor_plan_paths', []);
        if (! is_array($removeFloors)) {
            $removeFloors = [];
        }
        $removeFloors = array_values(array_intersect($removeFloors, $existingFloors));
        foreach ($removeFloors as $path) {
            Storage::disk('public')->delete($path);
        }
        $keptFloors = array_values(array_diff($existingFloors, $removeFloors));
        $property->floor_plan_paths = $this->storeNewFloorPlanFiles($request, $keptFloors);

        $property->save();

        return redirect()
            ->route('admin.properties.edit', $property)
            ->with('status', 'Property listing saved.');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $this->deletePropertyMedia($property);
        $property->delete();

        return redirect()
            ->route('admin.properties.index')
            ->with('status', 'Property deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedPropertyPayload(Request $request, ?Property $ignore = null): array
    {
        $rawArea = $request->input('property_area_id');
        if ($rawArea === '' || $rawArea === null || $rawArea === '0') {
            $request->merge(['property_area_id' => null]);
        }

        $priceRule = $request->boolean('price_on_request')
            ? 'nullable|numeric|min:0|max:99999999999999'
            : 'nullable|numeric|min:0|max:99999999999999';

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('properties', 'slug')->ignore($ignore?->id),
            ],
            'property_category_id' => 'nullable|exists:property_categories,id',
            'property_area_id' => 'nullable|exists:property_areas,id',
            'property_type_id' => 'nullable|exists:property_types,id',
            'listing_type' => 'required|in:sale,rent,both',
            'price' => $priceRule,
            'price_currency' => 'nullable|string|max:10',
            'price_on_request' => 'nullable|boolean',
            'maintenance_charges' => 'nullable|string|max:120',
            'bedrooms' => 'nullable|numeric|min:0|max:99',
            'bathrooms' => 'nullable|numeric|min:0|max:99',
            'balconies' => 'nullable|integer|min:0|max:99',
            'parking_covered' => 'nullable|integer|min:0|max:99',
            'built_up_area_sqft' => 'nullable|numeric|min:0',
            'carpet_area_sqft' => 'nullable|numeric|min:0',
            'plot_area_sqft' => 'nullable|numeric|min:0',
            'floor_number' => 'nullable|integer|min:-5|max:500',
            'total_floors' => 'nullable|integer|min:1|max:500',
            'facing' => 'nullable|string|max:60',
            'furnishing' => 'nullable|string|max:60',
            'age_of_property_years' => 'nullable|integer|min:0|max:200',
            'possession_status' => 'nullable|string|max:120',
            'developer_name' => 'nullable|string|max:255',
            'rera_number' => 'nullable|string|max:120',
            'developer_description' => 'nullable|string|max:20000',
            'project_land_area' => 'nullable|string|max:120',
            'total_units' => 'nullable|integer|min:0|max:99999999',
            'towers_blocks_summary' => 'nullable|string|max:500',
            'unit_variants_summary' => 'nullable|string|max:120',
            'maps_link_url' => 'nullable|string|max:2000',
            'price_disclaimer' => 'nullable|string|max:10000',
            'configuration_extra_text' => 'nullable|string|max:20000',
            'unit_mix_text' => 'nullable|string|max:30000',
            'specifications_text' => 'nullable|string|max:30000',
            'expert_pros_text' => 'nullable|string|max:10000',
            'expert_cons_text' => 'nullable|string|max:10000',
            'project_faqs_text' => 'nullable|string|max:50000',
            'master_plans' => 'nullable|array|max:'.self::MASTER_PLAN_MAX_UPLOAD_BATCH,
            'master_plans.*' => 'image|max:8192|mimes:jpeg,png,jpg,gif,webp',
            'remove_master_plan_paths' => 'nullable|array',
            'remove_master_plan_paths.*' => 'string|max:500',
            'floor_plans' => 'nullable|array|max:'.self::FLOOR_PLAN_MAX_UPLOAD_BATCH,
            'floor_plans.*' => 'image|max:8192|mimes:jpeg,png,jpg,gif,webp',
            'remove_floor_plan_paths' => 'nullable|array',
            'remove_floor_plan_paths.*' => 'string|max:500',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'locality' => 'nullable|string|max:120',
            'city' => 'nullable|string|max:120',
            'state' => 'nullable|string|max:120',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:120',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'summary' => 'nullable|string|max:8000',
            'description' => 'nullable|string|max:500000',
            'amenities_text' => 'nullable|string|max:20000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:5000',
            'meta_keywords' => 'nullable|string|max:255',
            'featured_image_url' => 'nullable|string|max:2000',
            'featured_image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_featured_image' => 'nullable|boolean',
            'gallery' => 'nullable|array|max:'.self::GALLERY_MAX_UPLOAD_BATCH,
            'gallery.*' => 'image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_gallery_paths' => 'nullable|array',
            'remove_gallery_paths.*' => 'string|max:500',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new_launch' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:999999',
        ]);

        $amenities = $this->parseAmenities((string) ($validated['amenities_text'] ?? ''));
        $configurationRows = $this->parsePipeKeyValueRows((string) ($validated['configuration_extra_text'] ?? ''));
        $unitMix = $this->parseUnitMixRows((string) ($validated['unit_mix_text'] ?? ''));
        $specifications = $this->parsePipeKeyValueRows((string) ($validated['specifications_text'] ?? ''), 60);
        $expertPros = $this->parseBulletLines((string) ($validated['expert_pros_text'] ?? ''), 40);
        $expertCons = $this->parseBulletLines((string) ($validated['expert_cons_text'] ?? ''), 40);
        $editedFaqs = $this->parseProjectFaqs((string) ($validated['project_faqs_text'] ?? ''));
        $projectFaqs = $this->mergeFaqRows([], $editedFaqs);

        foreach ([
            'amenities_text',
            'configuration_extra_text',
            'unit_mix_text',
            'specifications_text',
            'expert_pros_text',
            'expert_cons_text',
            'project_faqs_text',
            'slug',
            'gallery',
            'remove_gallery_paths',
            'featured_image',
            'remove_featured_image',
            'master_plans',
            'remove_master_plan_paths',
            'floor_plans',
            'remove_floor_plan_paths',
        ] as $strip) {
            unset($validated[$strip]);
        }

        return array_merge($validated, [
            'amenities' => $amenities,
            'configuration_rows' => $configurationRows,
            'unit_mix' => $unitMix,
            'specifications' => $specifications,
            'expert_pros' => $expertPros,
            'expert_cons' => $expertCons,
            'project_faqs' => $projectFaqs,
            'price_currency' => $validated['price_currency'] ?? 'INR',
            'price_on_request' => $request->boolean('price_on_request'),
            'is_published' => $request->boolean('is_published'),
            'is_featured' => $request->boolean('is_featured'),
            'is_new_launch' => $request->boolean('is_new_launch'),
            'price' => $request->boolean('price_on_request') ? null : ($validated['price'] ?? null),
            'featured_image_url' => $validated['featured_image_url'] ?? null,
            'property_category_id' => $validated['property_category_id'] ?? null,
            'property_area_id' => $validated['property_area_id'] ?? null,
            'property_type_id' => $validated['property_type_id'] ?? null,
        ]);
    }

    /**
     * @param  array<int, string>  $existingPaths
     * @return array<int, string>
     */
    private function storeNewMasterPlanFiles(Request $request, array $existingPaths): array
    {
        $existingPaths = array_values(array_filter($existingPaths, fn ($p) => is_string($p) && $p !== ''));
        $files = $request->file('master_plans', []) ?: [];

        foreach ($files as $file) {
            if (! $file || count($existingPaths) >= self::MASTER_PLAN_MAX_FILES) {
                break;
            }
            $existingPaths[] = $file->store('properties/master-plans', 'public');
        }

        return $existingPaths;
    }

    /**
     * @return array<int, string>
     */
    private function parseAmenities(string $raw): array
    {
        $parts = preg_split('/\r\n|\r|\n|,/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        if (! is_array($parts)) {
            return [];
        }
        $parts = array_map('trim', $parts);
        $parts = array_filter($parts, fn ($s) => $s !== '');

        return array_values(array_unique(array_slice($parts, 0, 80)));
    }

    /**
     * @param  array<int, string>  $existingPaths
     * @return array<int, string>
     */
    private function storeNewGalleryFiles(Request $request, array $existingPaths): array
    {
        $existingPaths = array_values(array_filter($existingPaths, fn ($p) => is_string($p) && $p !== ''));
        $files = $request->file('gallery', []) ?: [];

        foreach ($files as $file) {
            if (! $file || count($existingPaths) >= self::GALLERY_MAX_FILES) {
                break;
            }
            $existingPaths[] = $file->store('properties/gallery', 'public');
        }

        return $existingPaths;
    }

    private function deletePropertyMedia(Property $property): void
    {
        if ($property->featured_image_path) {
            Storage::disk('public')->delete($property->featured_image_path);
        }
        if ($property->master_plan_path) {
            Storage::disk('public')->delete($property->master_plan_path);
        }
        $masterPlans = is_array($property->master_plan_paths) ? $property->master_plan_paths : [];
        foreach ($masterPlans as $path) {
            if (is_string($path) && $path !== '') {
                Storage::disk('public')->delete($path);
            }
        }
        $paths = is_array($property->gallery_paths) ? $property->gallery_paths : [];
        foreach ($paths as $path) {
            if (is_string($path) && $path !== '') {
                Storage::disk('public')->delete($path);
            }
        }
        $floors = is_array($property->floor_plan_paths) ? $property->floor_plan_paths : [];
        foreach ($floors as $path) {
            if (is_string($path) && $path !== '') {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    private function parsePipeKeyValueRows(string $raw, int $max = 40): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        if (! is_array($lines)) {
            return [];
        }
        $out = [];
        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '' || ! str_contains($line, '|')) {
                continue;
            }
            [$k, $v] = array_map('trim', explode('|', $line, 2));
            if ($k === '') {
                continue;
            }
            $out[] = ['label' => $k, 'value' => $v];
            if (count($out) >= $max) {
                break;
            }
        }

        return $out;
    }

    /**
     * @return array<int, array{unit_type: string, size_sqft: string, price_label: string}>
     */
    private function parseUnitMixRows(string $raw, int $max = 40): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        if (! is_array($lines)) {
            return [];
        }
        $out = [];
        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '') {
                continue;
            }
            $parts = array_map('trim', explode('|', $line));
            if (count($parts) < 2) {
                continue;
            }
            $out[] = [
                'unit_type' => $parts[0] ?? '',
                'size_sqft' => $parts[1] ?? '',
                'price_label' => $parts[2] ?? '',
            ];
            if (count($out) >= $max) {
                break;
            }
        }

        return $out;
    }

    /**
     * @return array<int, string>
     */
    private function parseBulletLines(string $raw, int $max = 40): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        if (! is_array($lines)) {
            return [];
        }
        $out = [];
        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '') {
                continue;
            }
            $out[] = $line;
            if (count($out) >= $max) {
                break;
            }
        }

        return $out;
    }

    /**
     * @return array<int, array{question: string, answer: string}>
     */
    private function parseProjectFaqs(string $raw, int $max = 40): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }
        $blocks = preg_split('/\R\s*-{3,}\s*\R/', $raw);
        if (! is_array($blocks)) {
            return [];
        }
        $out = [];
        foreach ($blocks as $block) {
            $block = trim((string) $block);
            if ($block === '' || ! str_contains($block, ':::')) {
                continue;
            }
            [$q, $a] = explode(':::', $block, 2);
            $q = trim($q);
            $a = trim((string) $a);
            if ($q === '') {
                continue;
            }
            $out[] = ['question' => $q, 'answer' => $a];
            if (count($out) >= $max) {
                break;
            }
        }

        return $out;
    }

    /**
     * @param  array<int, string>  $existingPaths
     * @return array<int, string>
     */
    private function storeNewFloorPlanFiles(Request $request, array $existingPaths): array
    {
        $existingPaths = array_values(array_filter($existingPaths, fn ($p) => is_string($p) && $p !== ''));
        $files = $request->file('floor_plans', []) ?: [];

        foreach ($files as $file) {
            if (! $file || count($existingPaths) >= self::FLOOR_PLAN_MAX_FILES) {
                break;
            }
            $existingPaths[] = $file->store('properties/floor-plans', 'public');
        }

        return $existingPaths;
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base !== '' ? $base : 'property';
        $candidate = $slug;
        $i = 2;
        while (Property::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$i++;
        }

        return $candidate;
    }

    /**
     * @param  array<int, array{question:string,answer:string}>  $base
     * @param  array<int, array{question:string,answer:string}>  $append
     * @return array<int, array{question:string,answer:string}>
     */
    private function mergeFaqRows(array $base, array $append): array
    {
        $merged = [];
        $seen = [];
        foreach (array_merge($base, $append) as $row) {
            if (! is_array($row)) {
                continue;
            }
            $q = trim((string) ($row['question'] ?? ''));
            $a = trim((string) ($row['answer'] ?? ''));
            if ($q === '') {
                continue;
            }
            $key = mb_strtolower($q."\n".$a);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $merged[] = ['question' => $q, 'answer' => $a];
            if (count($merged) >= 80) {
                break;
            }
        }

        return $merged;
    }
}
