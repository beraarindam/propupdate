@extends('frontend.layouts.master')

@php
  $bannerBg = $page?->bannerBackgroundUrl() ?? 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1920&q=80';
  $bannerTitle = $page?->banner_title ?: 'Projects';
  $crumbLabel = $page?->name ?: 'Projects';
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

<section class="pu-blog-list pu-project-list @if(filled($page?->body_html)) pt-2 @endif">
  <div class="container">
    @if($projects->isEmpty())
      <div class="pu-pl-empty py-5">
        <div class="pu-pl-empty__icon" aria-hidden="true"><i class="fa-solid fa-building"></i></div>
        <h2 class="h5 pu-pl-empty__title">{{ $page?->listingIndex('empty_title') ?: 'No projects yet' }}</h2>
        <p class="text-muted mb-0 text-center">
          @if(filled($page?->listingIndex('empty_message')))
            {!! $page->listingIndex('empty_message') !!}
          @else
            No project pages yet. Check back soon.
          @endif
        </p>
      </div>
    @else
      <div class="row g-4">
        @foreach($projects as $item)
          @php($hero = $item->featuredBannerUrl())
          <div class="col-md-6 col-lg-4">
            <article class="pu-blog-card">
              @if($hero)
                <a href="{{ route('projects.show', $item) }}" class="pu-blog-card__img-link">
                  <div class="pu-blog-card__img" style="background-image: url('{{ e($hero) }}');"></div>
                </a>
              @endif
              <div class="pu-blog-card__body">
                @if($item->is_featured)
                  <span class="badge bg-warning text-dark mb-2">Featured</span>
                @endif
                <p class="pu-blog-card__date mb-1">
                  @if($item->location)
                    <i class="fa-solid fa-location-dot me-1" aria-hidden="true"></i>{{ $item->location }}
                  @endif
                  @if($item->location && $item->developer_name)
                    <span class="text-muted"> · </span>
                  @endif
                  @if($item->developer_name)
                    {{ $item->developer_name }}
                  @endif
                </p>
                <h2 class="pu-blog-card__title">
                  <a href="{{ route('projects.show', $item) }}">{{ $item->title }}</a>
                </h2>
                @if($item->summary)
                  <p class="pu-blog-card__excerpt">{{ $item->summary }}</p>
                @else
                  <p class="pu-blog-card__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($item->body), 140) }}</p>
                @endif
                <a href="{{ route('projects.show', $item) }}" class="pu-blog-card__more">View project</a>
              </div>
            </article>
          </div>
        @endforeach
      </div>
      <div class="pu-blog-pagination mt-4 d-flex justify-content-center">
        {{ $projects->withQueryString()->links() }}
      </div>
    @endif
  </div>
</section>
@endsection
