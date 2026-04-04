@extends('frontend.layouts.master')

@php
  use App\Models\Property;
  $page = null;
  $f = $filters ?? [];
  $listingLabels = Property::listingTypeOptions();
@endphp

@section('title', 'Properties')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => 'Properties',
  'crumbCurrent' => 'Properties',
  'lead' => 'Refine by <strong>deal type</strong>, location, and size — then explore listings tailored to you.',
  'bgImage' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80',
])

<section class="pu-pl-page">
  <div class="container py-4 py-lg-5">
    <form method="get" action="{{ route('properties.index') }}" class="pu-pl-form" id="pu-property-filters">
      <input type="hidden" name="view" value="{{ $f['view'] ?? 'grid' }}" id="pu-pl-view-input">

      <div class="row g-4 pu-pl-layout">
        {{-- Left: filters --}}
        <div class="col-lg-4 col-xl-3">
          <aside class="pu-pl-sidebar">
            <div class="pu-pl-sidebar__head">
              <h2 class="pu-pl-sidebar__title">Find your space</h2>
              <p class="pu-pl-sidebar__lead text-muted small mb-0">Filters apply instantly when you search.</p>
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
                <label class="pu-pl-label" for="pu-city">City</label>
                <select name="city" id="pu-city" class="pu-pl-select">
                  <option value="">All cities</option>
                  @foreach($filterCities as $c)
                    <option value="{{ $c }}" @selected(($f['city'] ?? '') === $c)>{{ $c }}</option>
                  @endforeach
                </select>
              </div>
              <div class="pu-pl-field">
                <label class="pu-pl-label" for="pu-cat">Category</label>
                <select name="category_id" id="pu-cat" class="pu-pl-select">
                  <option value="">All categories</option>
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
              <a href="{{ route('properties.index') }}" class="pu-pl-btn pu-pl-btn--ghost w-100 text-center">Reset all</a>
            </div>
          </aside>
        </div>

        {{-- Right: toolbar + grid --}}
        <div class="col-lg-8 col-xl-9">
          <div class="pu-pl-toolbar">
            <div class="pu-pl-toolbar__left">
              <h1 class="pu-pl-count">Properties <span class="pu-pl-count__n">({{ $properties->total() }})</span></h1>
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

          @if($properties->isEmpty())
            <div class="pu-pl-empty">
              <div class="pu-pl-empty__icon" aria-hidden="true"><i class="fa-solid fa-magnifying-glass"></i></div>
              <h2 class="h5 pu-pl-empty__title">No listings match</h2>
              <p class="text-muted mb-3">Try widening your filters or <a href="{{ route('properties.index') }}">clear them</a> to see all published properties.</p>
            </div>
          @else
            <div class="row g-4 pu-pl-grid @if(($f['view'] ?? 'grid') === 'list') pu-pl-grid--list @endif">
              @foreach($properties as $item)
                @php($featured = $item->featuredBannerUrl())
                @php($dealLabel = $listingLabels[$item->listing_type] ?? $item->listing_type)
                <div class="@if(($f['view'] ?? 'grid') === 'list') col-12 @else col-md-6 @endif">
                  <article class="pu-pl-card @if(($f['view'] ?? 'grid') === 'list') pu-pl-card--list @endif">
                    <div class="pu-pl-card__media">
                      @if($featured)
                        <a href="{{ route('properties.show', $item) }}" class="pu-pl-card__img-link">
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
                        <span class="pu-pl-badge pu-pl-badge--deal">{{ $dealLabel }}</span>
                      </div>
                    </div>
                    <div class="pu-pl-card__body">
                      @if($item->category)
                        <p class="pu-pl-card__cat">{{ $item->category->name }}</p>
                      @endif
                      <h2 class="pu-pl-card__title">
                        <a href="{{ route('properties.show', $item) }}">{{ $item->title }}</a>
                      </h2>
                      @if($item->locality || $item->city)
                        <p class="pu-pl-card__loc"><i class="fa-solid fa-location-dot me-1" aria-hidden="true"></i>{{ collect([$item->locality, $item->city])->filter()->implode(', ') }}</p>
                      @endif
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
                      @if($item->summary)
                        <p class="pu-pl-card__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($item->summary), ($f['view'] ?? 'grid') === 'list' ? 220 : 120) }}</p>
                      @endif
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
                      <a href="{{ route('properties.show', $item) }}" class="pu-pl-card__cta">View details <i class="fa-solid fa-arrow-right-long ms-1" aria-hidden="true"></i></a>
                    </div>
                  </article>
                </div>
              @endforeach
            </div>

            <div class="mt-4 pt-2 d-flex justify-content-center pu-blog-pagination">
              {{ $properties->withQueryString()->links() }}
            </div>
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
