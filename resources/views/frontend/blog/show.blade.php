@extends('frontend.layouts.master')

@php
  $page = null;
@endphp

@section('title', $post->browserTitle())

@section('content')
@php
  $bannerLead = $post->excerpt
    ? \Illuminate\Support\Str::limit(strip_tags($post->excerpt), 180)
    : \Illuminate\Support\Str::limit(strip_tags($post->body), 180);
@endphp
@include('frontend.partials.page-banner', [
  'title' => $post->title,
  'crumbCurrent' => $post->title,
  'lead' => $bannerLead,
  'bgImage' => $post->featuredBannerUrl() ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80',
])

<article class="pu-blog-article">
  <div class="container">
    <div class="pu-blog-article__inner">
      <p class="pu-blog-article__meta">
        <time datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->format('F j, Y') }}</time>
      </p>
      <div class="pu-blog-article__content pu-page-body-cms">
        {!! $post->body !!}
      </div>
      <p class="mt-4 mb-0">
        <a href="{{ route('blog.index') }}" class="pu-blog-back">← Back to blog</a>
      </p>
    </div>
  </div>
</article>
@endsection
