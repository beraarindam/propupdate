@extends('frontend.layouts.master')

@php
  $page = null;
@endphp

@section('title', 'Properties')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => 'Properties',
  'crumbCurrent' => 'Properties',
  'lead' => 'Browse <strong>verified listings</strong> across Bangalore — filter by category and type in the admin.',
  'bgImage' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80',
])

<section class="pu-blog-list pu-properties-list">
  <div class="container py-5">
    @if($properties->isEmpty())
      <p class="text-center text-muted py-5 mb-0">No published listings yet.</p>
    @else
      <div class="row g-4">
        @foreach($properties as $item)
          @php($featured = $item->featuredBannerUrl())
          <div class="col-md-6 col-lg-4">
            <article class="pu-blog-card">
              @if($featured)
                <a href="{{ route('properties.show', $item) }}" class="pu-blog-card__img-link">
                  <div class="pu-blog-card__img" style="background-image: url('{{ e($featured) }}');"></div>
                </a>
              @endif
              <div class="pu-blog-card__body">
                @if($item->is_featured)
                  <span class="badge bg-warning text-dark mb-2">Featured</span>
                @endif
                <p class="small text-muted mb-1">
                  @if($item->category)
                    {{ $item->category->name }}
                  @endif
                  @if($item->category && $item->type)
                    ·
                  @endif
                  @if($item->type)
                    {{ $item->type->name }}
                  @endif
                </p>
                <h2 class="pu-blog-card__title">
                  <a href="{{ route('properties.show', $item) }}">{{ $item->title }}</a>
                </h2>
                <p class="pu-blog-card__excerpt mb-2">
                  @if($item->price_on_request)
                    <strong>Price on request</strong>
                  @elseif($item->price !== null)
                    <strong>{{ $item->price_currency }} {{ number_format((float) $item->price, 0) }}</strong>
                    · {{ \App\Models\Property::listingTypeOptions()[$item->listing_type] ?? $item->listing_type }}
                  @else
                    {{ \App\Models\Property::listingTypeOptions()[$item->listing_type] ?? $item->listing_type }}
                  @endif
                </p>
                @if($item->summary)
                  <p class="pu-blog-card__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($item->summary), 120) }}</p>
                @elseif($item->locality || $item->city)
                  <p class="pu-blog-card__excerpt text-muted">{{ collect([$item->locality, $item->city])->filter()->implode(', ') }}</p>
                @endif
                <a href="{{ route('properties.show', $item) }}" class="pu-blog-card__more">View details</a>
              </div>
            </article>
          </div>
        @endforeach
      </div>
      <div class="mt-4 d-flex justify-content-center">
        {{ $properties->withQueryString()->links() }}
      </div>
    @endif
  </div>
</section>
@endsection
