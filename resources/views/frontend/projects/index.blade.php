@extends('frontend.layouts.master')

@php
  $bannerBg = $page?->bannerBackgroundUrl() ?? 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1920&q=80';
  $bannerTitle = $page?->banner_title ?: 'Projects';
  $crumbLabel = $page?->name ?: 'Projects';
  $f = $filters ?? [];
@endphp

@section('title', $page?->browserTitle() ?? 'Projects')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $bannerTitle,
  'crumbCurrent' => $crumbLabel,
  'lead' => $page?->banner_lead ?? 'New launches and developments — <strong>pricing</strong>, location, and story in one place.',
  'bgImage' => $bannerBg,
])

@if(filled($page?->body_html))
<section class="pu-page-intro-cms pt-0">
  <div class="container py-3 py-lg-4">
    <div class="pu-legal__inner pu-page-body-cms">
      {!! $page->body_html !!}
    </div>
  </div>
</section>
@endif

@php
  $listingCatFallbacks = [
    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=900&q=80',
  ];
@endphp
@if(($listingTopCategories ?? collect())->isNotEmpty())
<section class="pu-categories-wrap pu-categories-wrap--listing">
  <div class="pu-categories">
    @foreach($listingTopCategories as $idx => $cat)
      @php($catBg = $cat->bannerImageUrl() ?? ($listingCatFallbacks[$idx] ?? $listingCatFallbacks[0]))
      <a href="{{ route('projects.index', ['category_id' => $cat->id]) }}" class="pu-cat-card" style="background-image: url('{{ e($catBg) }}');">
        <span class="pu-cat-label">{{ $cat->name }}</span>
      </a>
    @endforeach
  </div>
</section>
@endif

<section class="pu-pl-page @if(filled($page?->body_html)) pt-0 @endif">
  <div class="container py-4 py-lg-5">
    <form method="get" action="{{ route('projects.index') }}" class="pu-pl-form" id="pu-project-filters">
      <input type="hidden" name="view" value="{{ $f['view'] ?? 'grid' }}" id="pu-project-view-input">

      <div class="row g-4 pu-pl-layout">
        <div class="col-lg-4 col-xl-3">
          <aside class="pu-pl-sidebar">
            <div class="pu-pl-sidebar__head">
              <h2 class="pu-pl-sidebar__title">{{ $page?->listingIndex('sidebar_title') ?: 'Find your project' }}</h2>
              <p class="pu-pl-sidebar__lead text-muted small mb-0">{{ $page?->listingIndex('sidebar_lead') ?: 'Filter by keyword, location, and developer.' }}</p>
            </div>

            <div class="pu-pl-deal" role="group" aria-label="Featured filter">
              <label class="pu-pl-deal__opt">
                <input type="radio" name="featured" value="" @checked(($f['featured'] ?? '') === '')>
                <span>All</span>
              </label>
              <label class="pu-pl-deal__opt">
                <input type="radio" name="featured" value="1" @checked(($f['featured'] ?? '') === '1')>
                <span>Featured only</span>
              </label>
            </div>

            <div class="pu-pl-fields">
              <div class="pu-pl-field">
                <label class="pu-pl-label" for="proj-q">Keyword</label>
                <input type="text" name="q" id="proj-q" class="pu-pl-input" value="{{ $f['q'] ?? '' }}" placeholder="Project, summary, developer..." autocomplete="off">
              </div>
              <div class="pu-pl-field">
                <label class="pu-pl-label" for="proj-location">Location</label>
                <select name="location" id="proj-location" class="pu-pl-select">
                  <option value="">All locations</option>
                  @foreach(($filterLocations ?? collect()) as $loc)
                    <option value="{{ $loc }}" @selected(($f['location'] ?? '') === $loc)>{{ $loc }}</option>
                  @endforeach
                </select>
              </div>
              <div class="pu-pl-field">
                <label class="pu-pl-label" for="proj-developer">Developer</label>
                <select name="developer" id="proj-developer" class="pu-pl-select">
                  <option value="">All developers</option>
                  @foreach(($filterDevelopers ?? collect()) as $dev)
                    <option value="{{ $dev }}" @selected(($f['developer'] ?? '') === $dev)>{{ $dev }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="pu-pl-sidebar__actions">
              <button type="submit" class="pu-pl-btn pu-pl-btn--primary w-100">Search projects</button>
              <a href="{{ route('projects.index') }}" class="pu-pl-btn pu-pl-btn--ghost w-100 text-center">Reset all</a>
            </div>
          </aside>
        </div>

        <div class="col-lg-8 col-xl-9">
          <div class="pu-pl-toolbar">
            <div class="pu-pl-toolbar__left">
              <h1 class="pu-pl-count">{{ $bannerTitle }} <span class="pu-pl-count__n">({{ $projects->total() }})</span></h1>
            </div>
            <div class="pu-pl-toolbar__right">
              <div class="pu-pl-view-toggle" role="group" aria-label="Layout">
                <button type="button" class="pu-pl-view-btn @if(($f['view'] ?? 'grid') === 'grid') is-active @endif" data-pu-project-view="grid" title="Grid view" aria-pressed="{{ ($f['view'] ?? 'grid') === 'grid' ? 'true' : 'false' }}">
                  <i class="fa-solid fa-grip" aria-hidden="true"></i>
                </button>
                <button type="button" class="pu-pl-view-btn @if(($f['view'] ?? '') === 'list') is-active @endif" data-pu-project-view="list" title="List view" aria-pressed="{{ ($f['view'] ?? '') === 'list' ? 'true' : 'false' }}">
                  <i class="fa-solid fa-list" aria-hidden="true"></i>
                </button>
              </div>
              <div class="pu-pl-toolbar__selects">
                <label class="visually-hidden" for="proj-per">Per page</label>
                <select name="per_page" id="proj-per" class="pu-pl-select pu-pl-select--sm" onchange="this.form.submit()">
                  @foreach([6, 12, 24] as $n)
                    <option value="{{ $n }}" @selected((int)($f['per_page'] ?? 12) === $n)>Show {{ $n }}</option>
                  @endforeach
                </select>
                <label class="visually-hidden" for="proj-sort">Sort</label>
                <select name="sort" id="proj-sort" class="pu-pl-select pu-pl-select--sm" onchange="this.form.submit()">
                  <option value="default" @selected(($f['sort'] ?? '') === 'default')>Sort: Featured</option>
                  <option value="newest" @selected(($f['sort'] ?? '') === 'newest')>Sort: Newest</option>
                  <option value="title_asc" @selected(($f['sort'] ?? '') === 'title_asc')>Sort: Title A-Z</option>
                  <option value="title_desc" @selected(($f['sort'] ?? '') === 'title_desc')>Sort: Title Z-A</option>
                </select>
              </div>
            </div>
          </div>

          @if($projects->isEmpty())
            <div class="pu-pl-empty">
              <div class="pu-pl-empty__icon" aria-hidden="true"><i class="fa-solid fa-building"></i></div>
              <h2 class="h5 pu-pl-empty__title">{{ $page?->listingIndex('empty_title') ?: 'No projects yet' }}</h2>
              <p class="text-muted mb-0 text-center">
                @if(filled($page?->listingIndex('empty_message')))
                  {!! $page->listingIndex('empty_message') !!}
                @else
                  No project pages yet. Try widening your filters.
                @endif
              </p>
            </div>
          @else
            <div class="row g-4 pu-pl-grid @if(($f['view'] ?? 'grid') === 'list') pu-pl-grid--list @endif">
              @foreach($projects as $item)
                @php($hero = $item->featuredBannerUrl())
                <div class="@if(($f['view'] ?? 'grid') === 'list') col-12 @else col-md-6 @endif">
                  <article class="pu-pl-card @if(($f['view'] ?? 'grid') === 'list') pu-pl-card--list @endif">
                    <div class="pu-pl-card__media">
                      @if($hero)
                        <a href="{{ route('projects.show', $item) }}" class="pu-pl-card__img-link">
                          <figure class="pu-pl-card__figure">
                            <img src="{{ e($hero) }}" alt="{{ e(\Illuminate\Support\Str::limit($item->title, 120)) }}" class="pu-pl-card__photo" loading="lazy" decoding="async" width="640" height="400">
                          </figure>
                        </a>
                      @else
                        <div class="pu-pl-card__figure pu-pl-card__figure--placeholder" role="img" aria-hidden="true"></div>
                      @endif
                      <div class="pu-pl-card__badges">
                        @if($item->is_featured)
                          <span class="pu-pl-badge pu-pl-badge--feat">Featured</span>
                        @endif
                        <span class="pu-pl-badge pu-pl-badge--deal">Project</span>
                      </div>
                    </div>
                    <div class="pu-pl-card__body">
                      <h2 class="pu-pl-card__title">
                        <a href="{{ route('projects.show', $item) }}">{{ $item->title }}</a>
                      </h2>
                      @if($item->location || $item->developer_name)
                        <p class="pu-pl-card__loc"><i class="fa-solid fa-location-dot me-1" aria-hidden="true"></i>{{ collect([$item->location, $item->developer_name])->filter()->implode(' · ') }}</p>
                      @endif
                      @if(filled($item->summary))
                        <p class="pu-pl-card__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($item->summary), ($f['view'] ?? 'grid') === 'list' ? 220 : 120) }}</p>
                      @elseif(filled($item->body))
                        <p class="pu-pl-card__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($item->body), ($f['view'] ?? 'grid') === 'list' ? 220 : 120) }}</p>
                      @endif
                      <a href="{{ route('projects.show', $item) }}" class="pu-pl-card__cta">View project <i class="fa-solid fa-arrow-right-long ms-1" aria-hidden="true"></i></a>
                    </div>
                  </article>
                </div>
              @endforeach
            </div>
            <div class="mt-4 pt-2 d-flex justify-content-center pu-blog-pagination">
              {{ $projects->withQueryString()->links() }}
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
  var form = document.getElementById('pu-project-filters');
  var viewInput = document.getElementById('pu-project-view-input');
  if (!form || !viewInput) return;
  document.querySelectorAll('[data-pu-project-view]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var v = btn.getAttribute('data-pu-project-view');
      if (v !== 'grid' && v !== 'list') return;
      viewInput.value = v;
      form.submit();
    });
  });
})();
</script>
@endpush
@endsection
