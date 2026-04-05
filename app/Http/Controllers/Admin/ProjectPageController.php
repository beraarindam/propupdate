<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Support\ProjectDetailTextParsers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectPageController extends Controller
{
    private const GALLERY_MAX_FILES = 24;

    private const FLOOR_PLAN_MAX_FILES = 24;

    public function index(): View
    {
        return view('backend.projects.index');
    }

    public function data(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $lengthInput = (int) $request->input('length', 25);
        $length = $lengthInput === -1 ? 100 : max(1, min(100, $lengthInput));

        $search = trim((string) $request->input('search.value', ''));

        $recordsTotal = Project::query()->count();

        $query = Project::query();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%')
                    ->orWhere('summary', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%')
                    ->orWhere('developer_name', 'like', '%'.$search.'%')
                    ->orWhere('meta_title', 'like', '%'.$search.'%')
                    ->orWhere('meta_description', 'like', '%'.$search.'%');
            });
        }

        $recordsFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $orderColumns = ['sort_order', 'title', 'slug', 'is_published', 'updated_at', 'id'];
        $orderBy = $orderColumns[$orderColumnIndex] ?? 'sort_order';
        if (! in_array($orderBy, $orderColumns, true)) {
            $orderBy = 'sort_order';
        }
        $query->orderBy($orderBy, $orderDir)->orderBy('id', 'desc');

        $rows = $query->skip($start)->take($length)->get();

        $token = csrf_token();
        $data = $rows->map(function (Project $project) use ($token) {
            $status = $project->is_published
                ? '<span class="badge bg-success">Live</span>'
                : '<span class="badge bg-secondary">Draft</span>';
            $feat = $project->is_featured
                ? ' <span class="badge bg-warning text-dark">Featured</span>'
                : '';

            $updated = $project->updated_at?->format('M j, Y H:i') ?? '—';

            $publicUrl = $project->is_published && $project->published_at
                ? route('projects.show', $project)
                : null;
            $slugCell = $publicUrl
                ? '<code class="small"><a href="'.e($publicUrl).'" target="_blank" rel="noopener noreferrer">'.e($project->slug).'</a></code>'
                : '<code class="small">'.e($project->slug).'</code>';

            $editUrl = route('admin.projects.edit', $project);
            $deleteUrl = route('admin.projects.destroy', $project);
            $actions = '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary">Edit</a> '
                .'<form action="'.e($deleteUrl).'" method="post" class="d-inline" onsubmit="return confirm(\'Delete this project page?\');">'
                .'<input type="hidden" name="_token" value="'.e($token).'">'
                .'<input type="hidden" name="_method" value="DELETE">'
                .'<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>';

            return [
                (string) $project->sort_order,
                '<span class="fw-semibold">'.e(Str::limit($project->title, 64)).$feat.'</span>',
                $slugCell,
                '<div class="d-flex flex-wrap gap-1">'.$status.'</div>',
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

        $path = $request->file('file')->store('projects/editor', 'public');
        $url = SiteSetting::resolvePublicUrl($path);

        if ($url === null) {
            Storage::disk('public')->delete($path);

            return response()->json(['error' => 'Storage path could not be resolved.'], 422);
        }

        return response()->json(['location' => $url]);
    }

    public function create(): View
    {
        return view('backend.projects.create', [
            'project' => new Project([
                'is_published' => false,
                'is_featured' => false,
                'sort_order' => 0,
                'body' => '',
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedPayload($request);

        $slugBase = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($data['title']);
        $slug = $this->uniqueSlug($slugBase);

        $publishedAt = null;
        if ($request->boolean('is_published')) {
            $publishedAt = now();
        }

        $path = null;
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('projects/featured', 'public');
        }

        $galleryPaths = $this->storeNewGalleryFiles($request, []);
        $masterPlanPath = null;
        if ($request->hasFile('master_plan_image')) {
            $masterPlanPath = $request->file('master_plan_image')->store('projects/master-plans', 'public');
        }
        $floorPlanPaths = $this->storeNewFloorPlanFiles($request, []);

        $project = Project::create(array_merge($data, [
            'slug' => $slug,
            'published_at' => $publishedAt,
            'featured_image_path' => $path,
            'gallery_paths' => $galleryPaths,
            'master_plan_path' => $masterPlanPath,
            'floor_plan_paths' => $floorPlanPaths,
        ]));

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('status', 'Project page created.');
    }

    public function edit(Project $project): View
    {
        return view('backend.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validatedPayload($request, $project);

        $slugInput = trim((string) $request->input('slug', ''));
        if ($slugInput !== '') {
            $slug = $this->uniqueSlug(Str::slug($slugInput), $project->id);
        } else {
            $slug = $this->uniqueSlug(Str::slug($data['title']), $project->id);
        }

        $publishedAt = $project->published_at;
        if ($request->boolean('is_published')) {
            if ($publishedAt === null) {
                $publishedAt = now();
            }
        } else {
            $publishedAt = null;
        }

        $project->fill(array_merge($data, [
            'slug' => $slug,
            'published_at' => $publishedAt,
        ]));

        if ($request->boolean('remove_featured_image')) {
            if ($project->featured_image_path) {
                Storage::disk('public')->delete($project->featured_image_path);
            }
            $project->featured_image_path = null;
        }

        if ($request->hasFile('featured_image')) {
            if ($project->featured_image_path) {
                Storage::disk('public')->delete($project->featured_image_path);
            }
            $project->featured_image_path = $request->file('featured_image')->store('projects/featured', 'public');
        }

        $existingGallery = is_array($project->gallery_paths) ? $project->gallery_paths : [];
        $remove = $request->input('remove_gallery_paths', []);
        if (! is_array($remove)) {
            $remove = [];
        }
        $remove = array_values(array_intersect($remove, $existingGallery));
        foreach ($remove as $p) {
            Storage::disk('public')->delete($p);
        }
        $kept = array_values(array_diff($existingGallery, $remove));
        $project->gallery_paths = $this->storeNewGalleryFiles($request, $kept);

        if ($request->boolean('remove_master_plan')) {
            if ($project->master_plan_path) {
                Storage::disk('public')->delete($project->master_plan_path);
            }
            $project->master_plan_path = null;
        }
        if ($request->hasFile('master_plan_image')) {
            if ($project->master_plan_path) {
                Storage::disk('public')->delete($project->master_plan_path);
            }
            $project->master_plan_path = $request->file('master_plan_image')->store('projects/master-plans', 'public');
        }

        $existingFloors = is_array($project->floor_plan_paths) ? $project->floor_plan_paths : [];
        $removeFloors = $request->input('remove_floor_plan_paths', []);
        if (! is_array($removeFloors)) {
            $removeFloors = [];
        }
        $removeFloors = array_values(array_intersect($removeFloors, $existingFloors));
        foreach ($removeFloors as $p) {
            Storage::disk('public')->delete($p);
        }
        $keptFloors = array_values(array_diff($existingFloors, $removeFloors));
        $project->floor_plan_paths = $this->storeNewFloorPlanFiles($request, $keptFloors);

        $project->save();

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('status', 'Project page saved.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->deleteProjectMedia($project);
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('status', 'Project page deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedPayload(Request $request, ?Project $ignore = null): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('projects', 'slug')->ignore($ignore?->id),
            ],
            'summary' => 'nullable|string|max:8000',
            'body' => 'required|string|max:500000',
            'location' => 'nullable|string|max:255',
            'developer_name' => 'nullable|string|max:255',
            'maps_link_url' => 'nullable|string|max:2000',
            'rera_number' => 'nullable|string|max:120',
            'quick_facts_text' => 'nullable|string|max:20000',
            'unit_pricing_text' => 'nullable|string|max:20000',
            'price_disclaimer' => 'nullable|string|max:8000',
            'amenities_text' => 'nullable|string|max:20000',
            'specifications_text' => 'nullable|string|max:20000',
            'expert_pros_text' => 'nullable|string|max:12000',
            'expert_cons_text' => 'nullable|string|max:12000',
            'project_faqs_text' => 'nullable|string|max:50000',
            'developer_about_html' => 'nullable|string|max:500000',
            'location_address' => 'nullable|string|max:500',
            'cta_headline' => 'nullable|string|max:255',
            'cta_subtext' => 'nullable|string|max:500',
            'last_updated_note' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:5000',
            'meta_keywords' => 'nullable|string|max:255',
            'featured_image_url' => 'nullable|string|max:2000',
            'featured_image' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,gif,webp',
            'remove_featured_image' => 'nullable|boolean',
            'gallery' => 'nullable|array|max:12',
            'gallery.*' => 'image|max:8192|mimes:jpeg,png,jpg,gif,webp',
            'remove_gallery_paths' => 'nullable|array',
            'remove_gallery_paths.*' => 'string|max:500',
            'master_plan_image' => 'nullable|image|max:8192|mimes:jpeg,png,jpg,gif,webp',
            'remove_master_plan' => 'nullable|boolean',
            'floor_plans' => 'nullable|array|max:12',
            'floor_plans.*' => 'image|max:8192|mimes:jpeg,png,jpg,gif,webp',
            'remove_floor_plan_paths' => 'nullable|array',
            'remove_floor_plan_paths.*' => 'string|max:500',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:999999',
        ]);

        $mapsUrl = isset($validated['maps_link_url']) ? trim((string) $validated['maps_link_url']) : '';
        $rera = isset($validated['rera_number']) ? trim((string) $validated['rera_number']) : '';

        $extras = [
            'quick_facts' => ProjectDetailTextParsers::parsePipeKeyValueRows((string) ($validated['quick_facts_text'] ?? '')),
            'unit_pricing' => ProjectDetailTextParsers::parseUnitPricingRows((string) ($validated['unit_pricing_text'] ?? '')),
            'price_disclaimer' => isset($validated['price_disclaimer']) ? trim((string) $validated['price_disclaimer']) : '',
            'amenities' => $this->parseAmenities((string) ($validated['amenities_text'] ?? '')),
            'specifications' => ProjectDetailTextParsers::parsePipeKeyValueRows((string) ($validated['specifications_text'] ?? ''), 80),
            'expert_pros' => ProjectDetailTextParsers::parseBulletLines((string) ($validated['expert_pros_text'] ?? '')),
            'expert_cons' => ProjectDetailTextParsers::parseBulletLines((string) ($validated['expert_cons_text'] ?? '')),
            'faqs' => ProjectDetailTextParsers::parseFaqs((string) ($validated['project_faqs_text'] ?? '')),
            'developer_about_html' => (string) ($validated['developer_about_html'] ?? ''),
            'location_address' => isset($validated['location_address']) ? trim((string) $validated['location_address']) : '',
            'cta_headline' => isset($validated['cta_headline']) ? trim((string) $validated['cta_headline']) : '',
            'cta_subtext' => isset($validated['cta_subtext']) ? trim((string) $validated['cta_subtext']) : '',
            'last_updated_note' => isset($validated['last_updated_note']) ? trim((string) $validated['last_updated_note']) : '',
        ];

        return [
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'body' => $validated['body'],
            'extras' => $extras,
            'location' => $validated['location'] ?? null,
            'developer_name' => $validated['developer_name'] ?? null,
            'maps_link_url' => $mapsUrl !== '' ? $mapsUrl : null,
            'rera_number' => $rera !== '' ? $rera : null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'featured_image_url' => $validated['featured_image_url'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];
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
            $existingPaths[] = $file->store('projects/gallery', 'public');
        }

        return $existingPaths;
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
            $existingPaths[] = $file->store('projects/floor-plans', 'public');
        }

        return $existingPaths;
    }

    private function deleteProjectMedia(Project $project): void
    {
        if ($project->featured_image_path) {
            Storage::disk('public')->delete($project->featured_image_path);
        }
        if ($project->master_plan_path) {
            Storage::disk('public')->delete($project->master_plan_path);
        }
        foreach (is_array($project->gallery_paths) ? $project->gallery_paths : [] as $path) {
            if (is_string($path) && $path !== '') {
                Storage::disk('public')->delete($path);
            }
        }
        foreach (is_array($project->floor_plan_paths) ? $project->floor_plan_paths : [] as $path) {
            if (is_string($path) && $path !== '') {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base !== '' ? $base : 'project';
        $candidate = $slug;
        $i = 2;
        while (Project::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$i++;
        }

        return $candidate;
    }
}
