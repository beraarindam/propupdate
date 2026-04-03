@extends('frontend.layouts.master')

@php
  $page = null;
@endphp

@section('title', $property->browserTitle())

@section('content')
@php
  $bannerLead = $property->summary
    ? \Illuminate\Support\Str::limit(strip_tags($property->summary), 180)
    : collect([$property->locality, $property->city])->filter()->implode(', ');
@endphp
@include('frontend.partials.page-banner', [
  'title' => $property->title,
  'crumbCurrent' => $property->title,
  'lead' => $bannerLead,
  'bgImage' => $property->featuredBannerUrl() ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80',
])

<article class="pu-blog-article pu-property-detail">
  <div class="container py-5">
    <div class="row g-4">
      <div class="col-lg-8">
        @php($gallery = $property->galleryPublicUrls())
        @if(count($gallery))
          <section class="mb-5">
            <h2 class="h4 pu-proj-heading">Gallery</h2>
            <div class="pu-property-gallery row g-2">
              @foreach($gallery as $url)
                <div class="col-6 col-md-4">
                  <div class="ratio ratio-4x3 rounded overflow-hidden border">
                    <img src="{{ $url }}" alt="" class="w-100 h-100" style="object-fit: cover;">
                  </div>
                </div>
              @endforeach
            </div>
          </section>
        @endif

        @include('frontend.properties._microsite', ['property' => $property])
      </div>

      <div class="col-lg-4">
        <div class="card border shadow-sm position-sticky pu-proj-sidebar" style="top: 1rem;">
          <div class="card-body">
            <h2 class="h6 text-uppercase text-muted mb-3">Quick facts</h2>
            <dl class="row small mb-0">
              <dt class="col-5 text-muted">Deal</dt>
              <dd class="col-7">{{ \App\Models\Property::listingTypeOptions()[$property->listing_type] ?? $property->listing_type }}</dd>
              <dt class="col-5 text-muted">Price</dt>
              <dd class="col-7">
                @if($property->price_on_request)
                  On request
                @elseif($property->price !== null)
                  {{ $property->price_currency }} {{ number_format((float) $property->price, 0) }}
                @else
                  —
                @endif
              </dd>
              @if($property->maintenance_charges)
                <dt class="col-5 text-muted">Maintenance</dt>
                <dd class="col-7">{{ $property->maintenance_charges }}</dd>
              @endif
              @if($property->category)
                <dt class="col-5 text-muted">Category</dt>
                <dd class="col-7">{{ $property->category->name }}</dd>
              @endif
              @if($property->type)
                <dt class="col-5 text-muted">Type</dt>
                <dd class="col-7">{{ $property->type->name }}</dd>
              @endif
              @if($property->developer_name)
                <dt class="col-5 text-muted">Developer</dt>
                <dd class="col-7">{{ $property->developer_name }}</dd>
              @endif
              @if($property->bedrooms !== null)
                <dt class="col-5 text-muted">Bedrooms</dt>
                <dd class="col-7">{{ $property->bedrooms }}</dd>
              @endif
              @if($property->bathrooms !== null)
                <dt class="col-5 text-muted">Bathrooms</dt>
                <dd class="col-7">{{ $property->bathrooms }}</dd>
              @endif
              @if($property->built_up_area_sqft)
                <dt class="col-5 text-muted">Built-up</dt>
                <dd class="col-7">{{ $property->built_up_area_sqft }} sq ft</dd>
              @endif
              @if($property->possession_status)
                <dt class="col-5 text-muted">Possession</dt>
                <dd class="col-7">{{ $property->possession_status }}</dd>
              @endif
            </dl>
            @php($addr = collect([$property->address_line1, $property->address_line2, $property->locality, $property->city, $property->state, $property->postal_code, $property->country])->filter()->implode(', '))
            @if($addr !== '')
              <hr>
              <p class="small mb-0"><strong>Address</strong><br>{{ $addr }}</p>
            @endif
          </div>
        </div>
        <p class="mt-3 mb-0">
          <a href="{{ route('properties.index') }}" class="pu-blog-back">← Back to properties</a>
        </p>
      </div>
    </div>
  </div>
</article>
@endsection
