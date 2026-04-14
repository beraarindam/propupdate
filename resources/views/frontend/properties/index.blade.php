@extends('frontend.layouts.master')

@php
  use App\Models\Property;
  use App\Models\Project;
  $f = $filters ?? [];
  $listingRouteName = $listingRoute ?? 'properties.index';
  $listingLabels = Property::listingTypeOptions();
  $resultsItems = ($listingRouteName === 'new-launches.index') ? ($launchItems ?? $properties->getCollection()) : $properties;
  $resultsTotal = ($listingRouteName === 'new-launches.index') ? ($launchTotal ?? $resultsItems->count()) : $properties->total();
  $bannerBg = $page?->bannerBackgroundUrl() ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80';
  $bannerTitle = $page?->banner_title ?: ($listingRouteName === 'new-launches.index' ? 'New launches' : 'Properties');
  $crumbLabel = $page?->name ?: ($listingRouteName === 'new-launches.index' ? 'New launches' : 'Properties');
@endphp

@section('title', $page?->browserTitle() ?? 'Properties')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $bannerTitle,
  'crumbCurrent' => $crumbLabel,
  'lead' => $page?->banner_lead ?? 'Refine by <strong>deal type</strong>, location, and size — then explore listings tailored to you.',
  'bgImage' => $bannerBg,
])

@php
  $listingCatFallbacks = [
    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=900&q=80',
  ];
@endphp
@if(($listingTopCategories ?? collect())->isNotEmpty() && $listingRouteName === 'properties.index')
<section class="pu-categories-wrap pu-categories-wrap--listing">
  <div class="pu-categories">
    @foreach($listingTopCategories as $idx => $cat)
      @php($catBg = $cat->bannerImageUrl() ?? ($listingCatFallbacks[$idx] ?? $listingCatFallbacks[0]))
      <a href="{{ route($listingRouteName, ['category_id' => $cat->id]) }}" class="pu-cat-card" style="background-image: url('{{ e($catBg) }}');">
        <span class="pu-cat-label">{{ $cat->name }}</span>
      </a>
    @endforeach
  </div>
</section>
@endif

@if(filled($page?->body_html))
<section class="pu-page-intro-cms pt-0">
  <div class="container py-3 py-lg-4">
    <div class="pu-legal__inner pu-page-body-cms">
      {!! $page->body_html !!}
    </div>
  </div>
</section>
@endif

<section class="pu-pl-page @if(filled($page?->body_html)) pt-0 @endif">
  <div class="container py-4 py-lg-5">
    <form method="get" action="{{ route($listingRouteName) }}" class="pu-pl-form" id="pu-property-filters">
      <input type="hidden" name="view" value="{{ $f['view'] ?? 'grid' }}" id="pu-pl-view-input">

      <div class="row g-4 pu-pl-layout">
        {{-- Left: filters --}}
        <div class="col-lg-4 col-xl-3">
          <aside class="pu-pl-sidebar">
            <div class="pu-pl-sidebar__head">
              <h2 class="pu-pl-sidebar__title">{{ $page?->listingIndex('sidebar_title') ?: 'Find your space' }}</h2>
              <p class="pu-pl-sidebar__lead text-muted small mb-0">{{ $page?->listingIndex('sidebar_lead') ?: 'Filters apply instantly when you search.' }}</p>
            </div>

            <div class="pu-pl-deal" role="group" aria-label="Deal type">
              <label class="pu-pl-deal__opt">
                <input type="radio" name="deal" value="" @checked(($f['deal'] ?? '') === '')>
                <span>All</span>
              </label>
              <label class="pu-pl-deal__opt">
                <input type="radio" name="deal" value="sale" @checked(($f['deal'] ?? '') === 'sale')>
                <span>For sale</span>
              </label>
              <label class="pu-pl-deal__opt">
                <input type="radio" name="deal" value="rent" @checked(($f['deal'] ?? '') === 'rent')>
                <span>For rent</span>
              </label>
            </div>

            <div class="pu-pl-fields">
              <div class="pu-pl-field">
                <label class="pu-pl-label" for="pu-q">Keyword</label>
                <input type="text" name="q" id="pu-q" class="pu-pl-input" value="{{ $f['q'] ?? '' }}" placeholder="Title, area, address…" autocomplete="off">
              </div>
              <div class="pu-pl-field">
                <label class="pu-pl-label" for="pu-area">Area</label>
                <select name="area_id" id="pu-area" class="pu-pl-select">
                  <option value="">All areas</option>
                  @foreach(($filterAreas ?? collect()) as $area)
                    <option value="{{ $area->id }}" @selected((string)($f['area_id'] ?? '') === (string) $area->id)>{{ $area->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="pu-pl-field">
                <label class="pu-pl-label" for="pu-cat">Category</label>
                @php($catParent = $categoryDropdownParent ?? null)
                <select name="category_id" id="pu-cat" class="pu-pl-select">
                  <option value="" @selected(($f['category_id'] ?? '') === '')>All categories</option>
                  @if($catParent)
                    <option value="{{ $catParent->id }}" @selected((string)($f['category_id'] ?? '') === (string) $catParent->id)>
                      All in {{ $catParent->name }}
                    </option>
                  @endif
                  @foreach($filterCategories as $cat)
                    <option value="{{ $cat->id }}" @selected((string)($f['category_id'] ?? '') === (string) $cat->id)>{{ $cat->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="pu-pl-field">
                <label class="pu-pl-label" for="pu-bed">Bedrooms (min.)</label>
                <select name="bedrooms" id="pu-bed" class="pu-pl-select">
                  <option value="">Any</option>
                  @foreach([1, 2, 3, 4, 5] as $n)
                    <option value="{{ $n }}" @selected((string)($f['bedrooms'] ?? '') === (string) $n)>{{ $n }}{{ $n === 5 ? '+' : '' }}</option>
                  @endforeach
                </select>
              </div>
              <div class="pu-pl-field">
                <label class="pu-pl-label" for="pu-bath">Bathrooms (min.)</label>
                <select name="bathrooms" id="pu-bath" class="pu-pl-select">
                  <option value="">Any</option>
                  @foreach([1, 2, 3, 4] as $n)
                    <option value="{{ $n }}" @selected((string)($f['bathrooms'] ?? '') === (string) $n)>{{ $n }}{{ $n === 4 ? '+' : '' }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="pu-pl-sidebar__actions">
              <button type="submit" class="pu-pl-btn pu-pl-btn--primary w-100">Search properties</button>
              <a href="{{ route($listingRouteName) }}" class="pu-pl-btn pu-pl-btn--ghost w-100 text-center">Reset all</a>
            </div>
          </aside>
        </div>

        {{-- Right: toolbar + grid --}}
        <div class="col-lg-8 col-xl-9">
          <div class="pu-pl-toolbar">
            <div class="pu-pl-toolbar__left">
              <h1 class="pu-pl-count">{{ $bannerTitle }} <span class="pu-pl-count__n">({{ $resultsTotal }})</span></h1>
            </div>
            <div class="pu-pl-toolbar__right">
              <div class="pu-pl-view-toggle" role="group" aria-label="Layout">
                <button type="button" class="pu-pl-view-btn @if(($f['view'] ?? 'grid') === 'grid') is-active @endif" data-pu-view="grid" title="Grid view" aria-pressed="{{ ($f['view'] ?? 'grid') === 'grid' ? 'true' : 'false' }}">
                  <i class="fa-solid fa-grip" aria-hidden="true"></i>
                </button>
                <button type="button" class="pu-pl-view-btn @if(($f['view'] ?? '') === 'list') is-active @endif" data-pu-view="list" title="List view" aria-pressed="{{ ($f['view'] ?? '') === 'list' ? 'true' : 'false' }}">
                  <i class="fa-solid fa-list" aria-hidden="true"></i>
                </button>
              </div>
              <div class="pu-pl-toolbar__selects">
                <label class="visually-hidden" for="pu-per">Per page</label>
                <select name="per_page" id="pu-per" class="pu-pl-select pu-pl-select--sm" onchange="this.form.submit()">
                  @foreach([6, 12, 24] as $n)
                    <option value="{{ $n }}" @selected((int)($f['per_page'] ?? 12) === $n)>Show {{ $n }}</option>
                  @endforeach
                </select>
                <label class="visually-hidden" for="pu-sort">Sort</label>
                <select name="sort" id="pu-sort" class="pu-pl-select pu-pl-select--sm" onchange="this.form.submit()">
                  <option value="default" @selected(($f['sort'] ?? '') === 'default')>Sort: Featured</option>
                  <option value="newest" @selected(($f['sort'] ?? '') === 'newest')>Sort: Newest</option>
                  <option value="price_asc" @selected(($f['sort'] ?? '') === 'price_asc')>Sort: Price ↑</option>
                  <option value="price_desc" @selected(($f['sort'] ?? '') === 'price_desc')>Sort: Price ↓</option>
                </select>
              </div>
            </div>
          </div>

          @if($resultsItems->isEmpty())
            <div class="pu-pl-empty">
              <div class="pu-pl-empty__icon" aria-hidden="true"><i class="fa-solid fa-magnifying-glass"></i></div>
              <h2 class="h5 pu-pl-empty__title">{{ $page?->listingIndex('empty_title') ?: 'No listings match' }}</h2>
              <p class="text-muted mb-3">
                @if(filled($page?->listingIndex('empty_message')))
                  {!! $page->listingIndex('empty_message') !!}
                @else
                  @if($listingRouteName === 'new-launches.index')
                    Try widening your filters or <a href="{{ route('properties.index') }}">browse all properties</a>.
                  @else
                    Try widening your filters or <a href="{{ route($listingRouteName) }}">clear them</a> to see all published properties.
                  @endif
                @endif
              </p>
            </div>
          @else
            <div class="row g-4 pu-pl-grid @if(($f['view'] ?? 'grid') === 'list') pu-pl-grid--list @endif">
              @foreach($resultsItems as $item)
                @php($isProjectCard = $item instanceof Project)
                @php($featured = $item->featuredBannerUrl())
                @php($dealLabel = $isProjectCard ? 'Project' : ($listingLabels[$item->listing_type] ?? $item->listing_type))
                @php($detailsUrl = $isProjectCard ? route('projects.show', $item) : route('properties.show', $item))
                <div class="@if(($f['view'] ?? 'grid') === 'list') col-12 @else col-md-6 @endif">
                  <article class="pu-pl-card @if(($f['view'] ?? 'grid') === 'list') pu-pl-card--list @endif">
                    <div class="pu-pl-card__media">
                      @if($featured)
                        <a href="{{ $detailsUrl }}" class="pu-pl-card__img-link">
                          <figure class="pu-pl-card__figure">
                            <img src="{{ e($featured) }}" alt="{{ e(\Illuminate\Support\Str::limit($item->title, 120)) }}" class="pu-pl-card__photo" loading="lazy" decoding="async" width="640" height="400">
                          </figure>
                        </a>
                      @else
                        <div class="pu-pl-card__figure pu-pl-card__figure--placeholder" role="img" aria-hidden="true"></div>
                      @endif
                      <div class="pu-pl-card__badges">
                        @if($item->is_featured)
                          <span class="pu-pl-badge pu-pl-badge--feat">Featured</span>
                        @endif
                        @if((bool) ($item->is_new_launch ?? false))
                          <span class="pu-pl-badge pu-pl-badge--launch">New launch</span>
                        @endif
                        <span class="pu-pl-badge pu-pl-badge--deal">{{ $dealLabel }}</span>
                      </div>
                    </div>
                    <div class="pu-pl-card__body">
                      @if(!$isProjectCard && $item->category)
                        <p class="pu-pl-card__cat">{{ $item->category->name }}</p>
                      @endif
                      <h2 class="pu-pl-card__title">
                        <a href="{{ $detailsUrl }}">{{ $item->title }}</a>
                      </h2>
                      @if($isProjectCard ? ($item->location || $item->developer_name) : ($item->locality || $item->city))
                        <p class="pu-pl-card__loc"><i class="fa-solid fa-location-dot me-1" aria-hidden="true"></i>{{ $isProjectCard ? collect([$item->location, $item->developer_name])->filter()->implode(' · ') : collect([$item->locality, $item->city])->filter()->implode(', ') }}</p>
                      @endif
                      @if(!$isProjectCard)
                        <p class="pu-pl-card__price-row mb-2">
                          @if($item->price_on_request)
                            <strong class="pu-pl-card__price">Price on request</strong>
                          @elseif($item->price !== null)
                            <strong class="pu-pl-card__price">{{ $item->price_currency }} {{ number_format((float) $item->price, 0) }}</strong>
                            @if($item->listing_type === Property::LISTING_RENT)
                              <span class="pu-pl-card__price-note">/ month</span>
                            @endif
                          @else
                            <strong class="pu-pl-card__price text-muted">Ask for price</strong>
                          @endif
                        </p>
                      @endif
                      @if($item->summary)
                        <p class="pu-pl-card__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($item->summary), ($f['view'] ?? 'grid') === 'list' ? 220 : 120) }}</p>
                      @endif
                      @if(!$isProjectCard)
                        <div class="pu-pl-card__facts">
                          @if($item->bedrooms !== null)
                            <span class="pu-pl-fact" title="Bedrooms"><i class="fa-solid fa-bed" aria-hidden="true"></i> {{ $item->bedrooms }}</span>
                          @endif
                          @if($item->bathrooms !== null)
                            <span class="pu-pl-fact" title="Bathrooms"><i class="fa-solid fa-bath" aria-hidden="true"></i> {{ $item->bathrooms }}</span>
                          @endif
                          @if($item->built_up_area_sqft)
                            <span class="pu-pl-fact" title="Built-up area"><i class="fa-solid fa-ruler-combined" aria-hidden="true"></i> {{ number_format((float) $item->built_up_area_sqft, 0) }} sq ft</span>
                          @elseif($item->plot_area_sqft)
                            <span class="pu-pl-fact" title="Plot area"><i class="fa-solid fa-ruler-combined" aria-hidden="true"></i> {{ number_format((float) $item->plot_area_sqft, 0) }} sq ft plot</span>
                          @endif
                        </div>
                      @endif
                      <a href="{{ $detailsUrl }}" class="pu-pl-card__cta">{{ $isProjectCard ? 'View project' : 'View details' }} <i class="fa-solid fa-arrow-right-long ms-1" aria-hidden="true"></i></a>
                    </div>
                  </article>
                </div>
              @endforeach
            </div>

            @if($listingRouteName !== 'new-launches.index')
              <div class="mt-4 pt-2 d-flex justify-content-center pu-blog-pagination">
                {{ $properties->withQueryString()->links() }}
              </div>
            @endif
          @endif
        </div>
      </div>
    </form>
  </div>
</section>

@push('scripts')
<script>
(function () {
  var form = document.getElementById('pu-property-filters');
  var viewInput = document.getElementById('pu-pl-view-input');
  if (!form || !viewInput) return;
  document.querySelectorAll('[data-pu-view]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var v = btn.getAttribute('data-pu-view');
      if (v !== 'grid' && v !== 'list') return;
      viewInput.value = v;
      form.submit();
    });
  });
})();
</script>
@endpush
@endsection
