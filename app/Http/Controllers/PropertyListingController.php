<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Page;
use App\Models\Property;
use App\Models\PropertyArea;
use App\Models\PropertyCategory;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PropertyListingController extends Controller
{
    public function index(Request $request): View
    {
        return $this->renderPropertyIndex($request, false);
    }

    /**
     * Published properties flagged as new launch (subset of the main directory).
     */
    public function newLaunches(Request $request): View
    {
        return $this->renderPropertyIndex($request, true);
    }

    private function renderPropertyIndex(Request $request, bool $newLaunchesOnly): View
    {
        $deal = (string) $request->query('deal', '');
        if (! in_array($deal, ['', 'sale', 'rent'], true)) {
            $deal = '';
        }

        $q = trim((string) $request->query('q', ''));
        $city = trim((string) $request->query('city', ''));
        $areaIdRaw = $request->query('area_id');
        $categoryId = $request->query('category_id');
        $selectedAreaId = ($areaIdRaw !== null && $areaIdRaw !== '' && ctype_digit((string) $areaIdRaw))
            ? (int) $areaIdRaw
            : null;

        $bedrooms = $request->query('bedrooms');
        $bathrooms = $request->query('bathrooms');

        $perPage = (int) $request->query('per_page', 12);
        if (! in_array($perPage, [6, 12, 24], true)) {
            $perPage = 12;
        }

        $sort = (string) $request->query('sort', 'default');
        if (! in_array($sort, ['default', 'price_asc', 'price_desc', 'newest'], true)) {
            $sort = 'default';
        }

        $view = (string) $request->query('view', 'grid');
        if (! in_array($view, ['grid', 'list'], true)) {
            $view = 'grid';
        }

        $query = Property::query()
            ->published()
            ->with(['category']);

        if ($newLaunchesOnly) {
            $query->newLaunch();
        }

        if ($deal === 'sale') {
            $query->whereIn('listing_type', [Property::LISTING_SALE, Property::LISTING_BOTH]);
        } elseif ($deal === 'rent') {
            $query->whereIn('listing_type', [Property::LISTING_RENT, Property::LISTING_BOTH]);
        }

        if ($q !== '') {
            $term = '%'.addcslashes($q, '%_\\').'%';
            $query->where(function ($sub) use ($term) {
                $sub->where('title', 'like', $term)
                    ->orWhere('summary', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('locality', 'like', $term)
                    ->orWhere('city', 'like', $term)
                    ->orWhere('address_line1', 'like', $term);
            });
        }

        if ($selectedAreaId !== null) {
            $query->where('property_area_id', $selectedAreaId);
        } elseif ($city !== '') {
            $query->where('city', $city);
        }

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

        if ($bedrooms !== null && $bedrooms !== '' && ctype_digit((string) $bedrooms)) {
            $minB = (int) $bedrooms;
            $query->whereNotNull('bedrooms')->where('bedrooms', '>=', $minB);
        }

        if ($bathrooms !== null && $bathrooms !== '' && ctype_digit((string) $bathrooms)) {
            $minBa = (int) $bathrooms;
            $query->whereNotNull('bathrooms')->where('bathrooms', '>=', $minBa);
        }

        match ($sort) {
            'price_asc' => $query
                ->orderByRaw('CASE WHEN price_on_request = 1 OR price IS NULL THEN 1 ELSE 0 END ASC')
                ->orderBy('price', 'asc')
                ->orderByDesc('is_featured')
                ->orderByDesc('published_at'),
            'price_desc' => $query
                ->orderByRaw('CASE WHEN price_on_request = 1 OR price IS NULL THEN 1 ELSE 0 END ASC')
                ->orderBy('price', 'desc')
                ->orderByDesc('is_featured')
                ->orderByDesc('published_at'),
            'newest' => $query
                ->orderByDesc('published_at')
                ->orderByDesc('id'),
            default => $query
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->orderByDesc('id'),
        };

        $properties = $query->paginate($perPage)->withQueryString();

        $areas = PropertyArea::query()
            ->where('is_published', true)
            ->when($newLaunchesOnly, function ($q) {
                $q->whereHas('properties', function ($pq) {
                    $pq->published()->newLaunch();
                });
            }, function ($q) {
                $q->whereHas('properties', function ($pq) {
                    $pq->published();
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        $categoryDropdownParent = null;

        // Match homepage category strip logic: 4 published parent categories,
        // fallback to any published categories when parent set is empty.
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

        $filterCategoriesQuery = PropertyCategory::query()
            ->where('is_published', true)
            ->when($newLaunchesOnly, function ($q) {
                $q->whereHas('properties', function ($pq) {
                    $pq->published()->newLaunch();
                });
            });

        $categories = (clone $filterCategoriesQuery)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        if ($selectedCategoryId !== null) {
            $sel = PropertyCategory::query()
                ->where('is_published', true)
                ->whereKey($selectedCategoryId)
                ->first();
            if ($sel) {
                if (PropertyCategory::query()
                    ->where('parent_id', $sel->id)
                    ->where('is_published', true)
                    ->exists()) {
                    $categoryDropdownParent = $sel;
                } elseif ($sel->parent_id) {
                    $par = PropertyCategory::query()
                        ->where('is_published', true)
                        ->whereKey($sel->parent_id)
                        ->first();
                    if ($par && PropertyCategory::query()
                        ->where('parent_id', $par->id)
                        ->where('is_published', true)
                        ->exists()) {
                        $categoryDropdownParent = $par;
                    }
                }
            }
        }

        if ($categoryDropdownParent !== null) {
            $categories = $categoryDropdownParent->children()
                ->where('is_published', true)
                ->when($newLaunchesOnly, function ($q) {
                    $q->whereHas('properties', function ($pq) {
                        $pq->published()->newLaunch();
                    });
                })
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        $projectsNewLaunches = collect();
        if ($newLaunchesOnly) {
            $projectsQuery = Project::query()->published()->newLaunch();
            if ($q !== '') {
                $term = '%'.addcslashes($q, '%_\\').'%';
                $projectsQuery->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', $term)
                        ->orWhere('summary', 'like', $term)
                        ->orWhere('body', 'like', $term)
                        ->orWhere('location', 'like', $term)
                        ->orWhere('developer_name', 'like', $term);
                });
            }
            if ($selectedAreaId !== null) {
                $projectsQuery->where('property_area_id', $selectedAreaId);
            }
            if ($selectedCategoryId !== null) {
                $cat = PropertyCategory::query()->where('is_published', true)->whereKey($selectedCategoryId)->first();
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
                        $projectsQuery->whereIn('property_category_id', $branchIds !== [] ? $branchIds : [$cat->id]);
                    } else {
                        $projectsQuery->where('property_category_id', $selectedCategoryId);
                    }
                }
            }

            $projectsNewLaunches = $projectsQuery
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->limit(12)
                ->get();
        }

        $launchItems = $properties->getCollection();
        $launchTotal = $properties->total();
        if ($newLaunchesOnly) {
            $launchItems = $launchItems->concat($projectsNewLaunches)->values();
            $launchTotal = $launchItems->count();
        }

        $pageSlug = $newLaunchesOnly ? 'new-launches' : 'properties';
        $listingRoute = $newLaunchesOnly ? 'new-launches.index' : 'properties.index';

        return view('frontend.properties.index', [
            'page' => Page::bySlug($pageSlug),
            'properties' => $properties,
            'filterAreas' => $areas,
            'filterCategories' => $categories,
            'categoryDropdownParent' => $categoryDropdownParent,
            'listingTopCategories' => $topCategories,
            'listingRoute' => $listingRoute,
            'projectsNewLaunches' => $projectsNewLaunches,
            'launchItems' => $launchItems,
            'launchTotal' => $launchTotal,
            'filters' => [
                'deal' => $deal,
                'q' => $q,
                'city' => $city,
                'area_id' => $selectedAreaId ? (string) $selectedAreaId : '',
                'category_id' => $categoryId !== null && $categoryId !== '' ? (string) $categoryId : '',
                'bedrooms' => $bedrooms !== null && $bedrooms !== '' ? (string) $bedrooms : '',
                'bathrooms' => $bathrooms !== null && $bathrooms !== '' ? (string) $bathrooms : '',
                'per_page' => $perPage,
                'sort' => $sort,
                'view' => $view,
            ],
        ]);
    }

    /**
     * JSON typeahead for the home hero search (and future widgets).
     */
    public function suggestions(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $term = '%'.addcslashes($q, '%_\\').'%';

        $properties = Property::query()
            ->published()
            ->where(function ($sub) use ($term) {
                $sub->where('title', 'like', $term)
                    ->orWhere('summary', 'like', $term)
                    ->orWhere('locality', 'like', $term)
                    ->orWhere('city', 'like', $term)
                    ->orWhere('developer_name', 'like', $term);
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->limit(8)
            ->get([
                'id',
                'slug',
                'title',
                'locality',
                'city',
                'listing_type',
                'price_currency',
                'price',
                'price_on_request',
            ]);

        $labels = Property::listingTypeOptions();

        $results = $properties->map(function (Property $p) use ($labels) {
            $loc = collect([$p->locality, $p->city])->filter()->implode(', ');
            $priceLabel = $p->price_on_request
                ? 'Price on request'
                : ($p->price !== null
                    ? trim($p->price_currency.' '.number_format((float) $p->price, 0))
                    : null);

            return [
                'title' => $p->title,
                'url' => route('properties.show', $p),
                'location' => $loc !== '' ? $loc : null,
                'price_label' => $priceLabel,
                'deal' => $labels[$p->listing_type] ?? $p->listing_type,
            ];
        })->values();

        return response()->json(['results' => $results]);
    }

    public function show(Property $property): View
    {
        if (! $property->is_published || $property->published_at === null) {
            throw new NotFoundHttpException;
        }

        $property->load(['category']);

        return view('frontend.properties.show', [
            'page' => null,
            'property' => $property,
        ]);
    }

    public function submitEnquiry(Request $request, Property $property): RedirectResponse
    {
        if (! $property->is_published || $property->published_at === null) {
            throw new NotFoundHttpException;
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:32',
            'message' => 'required|string|max:4000',
        ]);

        Enquiry::create([
            'source' => Enquiry::SOURCE_PROPERTY,
            'property_id' => $property->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => Str::limit('Listing: '.$property->title, 200),
            'message' => $data['message'],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('properties.show', $property)
            ->withFragment('pu-property-request')
            ->with('property_enquiry_status', 'Thanks — your request was sent. We will get back to you shortly.');
    }

    public function submitBrochureRequest(Request $request, Property $property): RedirectResponse
    {
        if (! $property->is_published || $property->published_at === null) {
            throw new NotFoundHttpException;
        }

        $data = $request->validateWithBag('brochure', [
            'brochure_name' => 'required|string|max:120',
            'brochure_email' => 'required|email|max:255',
            'brochure_phone' => 'required|string|max:32',
            'brochure_message' => 'required|string|max:4000',
        ]);

        Enquiry::create([
            'source' => Enquiry::SOURCE_PROPERTY,
            'property_id' => $property->id,
            'name' => $data['brochure_name'],
            'email' => $data['brochure_email'],
            'phone' => $data['brochure_phone'],
            'subject' => Str::limit('Brochure request: '.$property->title, 200),
            'message' => $data['brochure_message'],
            'ip_address' => $request->ip(),
        ]);

        return redirect()
            ->route('properties.show', $property)
            ->withFragment('pu-proj-brochure')
            ->with('property_brochure_status', 'Thanks — brochure request received. Our team will contact you shortly.');
    }
}
