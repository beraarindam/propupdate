@extends('frontend.layouts.master')

@php
  $page = null;
@endphp

@section('title', 'Blog')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => 'Blog',
  'crumbCurrent' => 'Blog',
  'lead' => 'Insights on <strong>Bangalore real estate</strong>, launches, resale, and buying with clarity.',
  'bgImage' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80',
])

<section class="pu-blog-list">
  <div class="container">
    @if($posts->isEmpty())
      <p class="text-center text-muted py-5 mb-0">No articles yet. Check back soon.</p>
    @else
      <div class="row g-4">
        @foreach($posts as $item)
          @php($featured = $item->featuredBannerUrl())
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
                @if($item->excerpt)
                  <p class="pu-blog-card__excerpt">{{ $item->excerpt }}</p>
                @else
                  <p class="pu-blog-card__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($item->body), 140) }}</p>
                @endif
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
