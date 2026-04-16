@extends('frontend.layouts.master')

@php
  $bannerBg = $page?->bannerBackgroundUrl() ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80';
@endphp

@section('title', $page?->browserTitle() ?? 'Blog')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $page?->banner_title ?? $page?->name ?? 'Blog',
  'crumbCurrent' => $page?->name ?? 'Blog',
  'lead' => $page?->banner_lead ?? 'Insights on <strong>Bangalore real estate</strong>, launches, resale, and buying with clarity.',
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

<section class="pu-blog-list @if(filled($page?->body_html)) pt-0 @endif">
  <div class="container">
    @if($posts->isEmpty())
      <p class="text-center text-muted py-5 mb-0">No articles yet. Check back soon.</p>
    @else
      <div class="row g-4">
        @foreach($posts as $item)
          @php($featured = $item->featuredBannerUrl())
          @php($blogExcerpt = $item->excerpt ? strip_tags($item->excerpt) : strip_tags($item->body))
          <div class="col-md-6 col-lg-4">
            <article class="pu-blog-card">
              @if($featured)
                <a href="{{ route('blog.show', $item) }}" class="pu-blog-card__img-link">
                  <div class="pu-blog-card__img" style="background-image: url('{{ e($featured) }}');"></div>
                </a>
              @endif
              <div class="pu-blog-card__body">
                <time class="pu-blog-card__date" datetime="{{ $item->published_at?->toIso8601String() }}">{{ $item->published_at?->format('M j, Y') }}</time>
                <h2 class="pu-blog-card__title">
                  <a href="{{ route('blog.show', $item) }}">{{ $item->title }}</a>
                </h2>
                <p class="pu-blog-card__excerpt">{{ \Illuminate\Support\Str::limit($blogExcerpt, 140) }}</p>
                <a href="{{ route('blog.show', $item) }}" class="pu-blog-card__more">Read more</a>
              </div>
            </article>
          </div>
        @endforeach
      </div>
      <div class="pu-blog-pagination mt-4 d-flex justify-content-center">
        {{ $posts->withQueryString()->links() }}
      </div>
    @endif
  </div>
</section>
@endsection
