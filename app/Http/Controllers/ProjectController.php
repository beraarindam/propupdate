<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Page;
use App\Models\Project;
use App\Models\PropertyCategory;
use App\Support\LeadRatClient;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $location = trim((string) $request->query('location', ''));
        $developer = trim((string) $request->query('developer', ''));
        $categoryId = $request->query('category_id');
        $featured = (string) $request->query('featured', '');
        if (! in_array($featured, ['', '1'], true)) {
            $featured = '';
        }

        $perPage = (int) $request->query('per_page', 12);
        if (! in_array($perPage, [6, 12, 24], true)) {
            $perPage = 12;
        }

        $sort = (string) $request->query('sort', 'default');
        if (! in_array($sort, ['default', 'newest', 'title_asc', 'title_desc'], true)) {
            $sort = 'default';
        }

        $view = (string) $request->query('view', 'grid');
        if (! in_array($view, ['grid', 'list'], true)) {
            $view = 'grid';
        }

        $query = Project::query()->published();

        $selectedCategoryId = ($categoryId !== null && $categoryId !== '' && ctype_digit((string) $categoryId))
            ? (int) $categoryId
            : null;

        if ($selectedCategoryId !== null) {
            $cat = PropertyCategory::query()
                ->where('is_published', true)
                ->whereKey($selectedCategoryId)
                ->first();
            if ($cat) {
                $hasPublishedChildren = PropertyCategory::query()
                    ->where('parent_id', $cat->id)
                    ->where('is_published', true)
                    ->exists();
                if ($hasPublishedChildren) {
                    $branchIds = PropertyCategory::query()
                        ->whereIn('id', $cat->branchIds())
                        ->where('is_published', true)
                        ->pluck('id')
                        ->all();
                    $query->whereIn('property_category_id', $branchIds !== [] ? $branchIds : [$cat->id]);
                } else {
                    $query->where('property_category_id', $selectedCategoryId);
                }
            }
        }

        if ($q !== '') {
            $term = '%'.addcslashes($q, '%_\\').'%';
            $query->where(function ($sub) use ($term) {
                $sub->where('title', 'like', $term)
                    ->orWhere('summary', 'like', $term)
                    ->orWhere('body', 'like', $term)
                    ->orWhere('location', 'like', $term)
                    ->orWhere('developer_name', 'like', $term);
            });
        }

        if ($location !== '') {
            $query->where('location', $location);
        }

        if ($developer !== '') {
            $query->where('developer_name', $developer);
        }

        if ($featured === '1') {
            $query->where('is_featured', true);
        }

        match ($sort) {
            'newest' => $query->orderByDesc('published_at')->orderByDesc('id'),
            'title_asc' => $query->orderBy('title')->orderByDesc('published_at'),
            'title_desc' => $query->orderByDesc('title')->orderByDesc('published_at'),
            default => $query->orderByDesc('is_featured')->orderBy('sort_order')->orderByDesc('published_at')->orderByDesc('id'),
        };

        $projects = $query->paginate($perPage)->withQueryString();

        $filterLocations = Project::query()
            ->published()
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location')
            ->values();

        $filterDevelopers = Project::query()
            ->published()
            ->whereNotNull('developer_name')
            ->where('developer_name', '!=', '')
            ->distinct()
            ->orderBy('developer_name')
            ->pluck('developer_name')
            ->values();

        $topCategories = PropertyCategory::query()
            ->where('is_published', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(4)
            ->get();

        if ($topCategories->isEmpty()) {
            $topCategories = PropertyCategory::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->limit(4)
                ->get();
        }

        return view('frontend.projects.index', [
            'page' => Page::bySlug('projects'),
            'projects' => $projects,
            'listingTopCategories' => $topCategories,
            'filters' => [
                'q' => $q,
                'location' => $location,
                'developer' => $developer,
                'category_id' => $categoryId !== null && $categoryId !== '' ? (string) $categoryId : '',
                'featured' => $featured,
                'per_page' => $perPage,
                'sort' => $sort,
                'view' => $view,
            ],
            'filterLocations' => $filterLocations,
            'filterDevelopers' => $filterDevelopers,
        ]);
    }

    public function show(Project $project): View
    {
        if (! $project->is_published || $project->published_at === null) {
            throw new NotFoundHttpException;
        }

        return view('frontend.projects.show', [
            'page' => null,
            'project' => $project,
        ]);
    }

    public function submitEnquiry(Request $request, Project $project): RedirectResponse
    {
        if (! $project->is_published || $project->published_at === null) {
            throw new NotFoundHttpException;
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:32',
            'message' => 'required|string|max:4000',
        ]);

        $enquiry = Enquiry::create([
            'source' => Enquiry::SOURCE_PROJECT,
            'project_id' => $project->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subject' => Str::limit('Project: '.$project->title, 200),
            'message' => $data['message'],
            'ip_address' => $request->ip(),
        ]);
        app(LeadRatClient::class)->pushFromEnquiry($enquiry, [
            'project_model' => $project,
            'project_name' => $project->title,
            'propertyType' => (string) ($project->category?->name ?? ''),
            'page_url' => $request->fullUrl(),
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->withFragment('pu-project-request')
            ->with('project_enquiry_status', 'Thanks — your request was sent. We will get back to you shortly.');
    }
}
