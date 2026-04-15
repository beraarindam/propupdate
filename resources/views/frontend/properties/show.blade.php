@extends('frontend.layouts.master')

@php
  use App\Models\Property;
  $page = null;
  $bannerLead = $property->summary
    ? \Illuminate\Support\Str::limit(strip_tags($property->summary), 200)
    : collect([$property->locality, $property->city])->filter()->implode(', ');
  $dealLabel = Property::listingTypeOptions()[$property->listing_type] ?? $property->listing_type;
  $gallery = $property->galleryPublicUrls();
  $locLine = collect([$property->locality, $property->city, $property->state])->filter()->implode(', ');
@endphp

@section('title', $property->browserTitle())

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $property->title,
  'crumbCurrent' => $property->title,
  'showBreadcrumb' => false,
  'bgImage' => $property->featuredBannerUrl() ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80',
  'bannerClass' => 'pu-page-banner--property',
])

<section class="pu-pd-strip">
  <div class="container">
    <div class="pu-pd-strip__inner">
      <div class="pu-pd-chips">
        @if($property->category)
          <span class="pu-pd-chip">{{ $property->category->name }}</span>
        @endif
        <span class="pu-pd-chip pu-pd-chip--deal">{{ $dealLabel }}</span>
        @if($property->is_featured)
          <span class="pu-pd-chip pu-pd-chip--feat"><i class="fa-solid fa-star me-1" aria-hidden="true"></i>Featured</span>
        @endif
        @if($locLine !== '')
          <span class="pu-pd-chip pu-pd-chip--muted"><i class="fa-solid fa-location-dot me-1" aria-hidden="true"></i>{{ $locLine }}</span>
        @endif
      </div>
      <div class="pu-pd-strip__price">
        @if($property->price_on_request)
          <span class="pu-pd-price-tag">Price on request</span>
        @elseif($property->price !== null)
          <span class="pu-pd-price-tag">{{ $property->price_currency }} {{ number_format((float) $property->price, 0) }}</span>
          @if($property->listing_type === Property::LISTING_RENT)
            <span class="pu-pd-price-note">/ month</span>
          @endif
        @else
          <span class="pu-pd-price-tag pu-pd-price-tag--muted">Ask for price</span>
        @endif
      </div>
    </div>
  </div>
</section>

<article class="pu-pd-main">
  <div class="container py-4 py-lg-5">
    <div class="row g-4 g-xl-5">
      <div class="col-lg-8">
        @if(count($gallery))
          <section class="pu-pd-gallery-block mb-5">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-3">
              <h2 class="pu-pd-section-title mb-0">Gallery</h2>
              <span class="pu-pd-gallery-hint text-muted small"><i class="fa-regular fa-images me-1" aria-hidden="true"></i>Click an image to enlarge</span>
            </div>
            <div class="pu-pd-gallery row g-3">
              @foreach($gallery as $idx => $url)
                <div class="col-6 col-md-4">
                  <a href="{{ $url }}" class="pu-pd-gallery__item pu-pd-mfp" title="{{ e(\Illuminate\Support\Str::limit($property->title, 80)) }}">
                    <div class="ratio ratio-4x3">
                      <img src="{{ $url }}" alt="{{ e($property->title.' — photo '.($idx + 1)) }}" class="pu-pd-gallery__img" loading="{{ $idx < 3 ? 'eager' : 'lazy' }}" width="400" height="300">
                    </div>
                    <span class="pu-pd-gallery__zoom" aria-hidden="true"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
                  </a>
                </div>
              @endforeach
            </div>
          </section>
        @endif

        @include('frontend.properties._microsite', ['property' => $property])
      </div>

      <div class="col-lg-4">
        <aside class="pu-pd-sidebar">
          <div class="pu-pd-facts card border-0 shadow-lg">
            <div class="pu-pd-facts__head">
              <h2 class="pu-pd-facts__title">Quick facts</h2>
              <p class="pu-pd-facts__sub text-white-50 small mb-0">Key numbers at a glance</p>
            </div>
            <div class="card-body pu-pd-facts__body">
              <ul class="pu-pd-facts__list list-unstyled mb-0">
                <li class="pu-pd-fact">
                  <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-handshake"></i></span>
                  <div>
                    <span class="pu-pd-fact__label">Deal</span>
                    <span class="pu-pd-fact__value">{{ $dealLabel }}</span>
                  </div>
                </li>
                <li class="pu-pd-fact">
                  <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-indian-rupee-sign"></i></span>
                  <div>
                    <span class="pu-pd-fact__label">Price</span>
                    <span class="pu-pd-fact__value">
                      @if($property->price_on_request)
                        On request
                      @elseif($property->price !== null)
                        {{ $property->price_currency }} {{ number_format((float) $property->price, 0) }}
                        @if($property->listing_type === Property::LISTING_RENT)
                          <span class="pu-pd-fact__muted">/ mo</span>
                        @endif
                      @else
                        —
                      @endif
                    </span>
                  </div>
                </li>
                @if($property->maintenance_charges)
                  <li class="pu-pd-fact">
                    <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-building"></i></span>
                    <div>
                      <span class="pu-pd-fact__label">Maintenance</span>
                      <span class="pu-pd-fact__value">{{ $property->maintenance_charges }}</span>
                    </div>
                  </li>
                @endif
                @if($property->category)
                  <li class="pu-pd-fact">
                    <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-layer-group"></i></span>
                    <div>
                      <span class="pu-pd-fact__label">Category</span>
                      <span class="pu-pd-fact__value">{{ $property->category->name }}</span>
                    </div>
                  </li>
                @endif
                @if($property->developer_name)
                  <li class="pu-pd-fact">
                    <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-helmet-safety"></i></span>
                    <div>
                      <span class="pu-pd-fact__label">Developer</span>
                      <span class="pu-pd-fact__value">{{ $property->developer_name }}</span>
                    </div>
                  </li>
                @endif
                @if($property->bedrooms !== null)
                  <li class="pu-pd-fact">
                    <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-bed"></i></span>
                    <div>
                      <span class="pu-pd-fact__label">Bedrooms</span>
                      <span class="pu-pd-fact__value">{{ $property->bedrooms }}</span>
                    </div>
                  </li>
                @endif
                @if($property->bathrooms !== null)
                  <li class="pu-pd-fact">
                    <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-bath"></i></span>
                    <div>
                      <span class="pu-pd-fact__label">Bathrooms</span>
                      <span class="pu-pd-fact__value">{{ $property->bathrooms }}</span>
                    </div>
                  </li>
                @endif
                @if($property->built_up_area_sqft)
                  <li class="pu-pd-fact">
                    <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-ruler-combined"></i></span>
                    <div>
                      <span class="pu-pd-fact__label">Built-up</span>
                      <span class="pu-pd-fact__value">{{ $property->built_up_area_sqft }} sq ft</span>
                    </div>
                  </li>
                @endif
                @if($property->possession_status)
                  <li class="pu-pd-fact">
                    <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-key"></i></span>
                    <div>
                      <span class="pu-pd-fact__label">Possession</span>
                      <span class="pu-pd-fact__value">{{ $property->possession_status }}</span>
                    </div>
                  </li>
                @endif
              </ul>
              @php($addr = collect([$property->address_line1, $property->address_line2, $property->locality, $property->city, $property->state, $property->postal_code, $property->country])->filter()->implode(', '))
              @if($addr !== '')
                <div class="pu-pd-address">
                  <span class="pu-pd-address__label"><i class="fa-solid fa-map-pin me-2" aria-hidden="true"></i>Address</span>
                  <p class="pu-pd-address__text mb-0">{{ $addr }}</p>
                </div>
              @endif
            </div>
          </div>

          <div class="pu-pd-request card border-0 shadow-lg mt-4" id="pu-property-request">
            <div class="pu-pd-request__head">
              <h2 class="pu-pd-request__title mb-0">Request information</h2>
              <p class="pu-pd-request__sub text-white-50 small mb-0 mt-1">Ask about this listing — we will reply soon.</p>
            </div>
            <div class="card-body pu-pd-request__body">
              @if(session('property_enquiry_status'))
                <div class="alert alert-success py-2 px-3 small mb-3" role="status">{{ session('property_enquiry_status') }}</div>
              @endif
              <form method="post" action="{{ route('properties.enquiry', $property) }}" novalidate>
                @csrf
                <div class="mb-3">
                  <label for="pu-enq-name" class="form-label small fw-semibold text-muted mb-1">Name</label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="pu-enq-name" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name">
                  @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                  <label for="pu-enq-email" class="form-label small fw-semibold text-muted mb-1">Email</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="pu-enq-email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email">
                  @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                  <label for="pu-enq-phone" class="form-label small fw-semibold text-muted mb-1">Phone</label>
                  <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="pu-enq-phone" name="phone" value="{{ old('phone') }}" required maxlength="32" autocomplete="tel">
                  @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                  <label for="pu-enq-message" class="form-label small fw-semibold text-muted mb-1">Message</label>
                  <textarea class="form-control @error('message') is-invalid @enderror" id="pu-enq-message" name="message" rows="4" required maxlength="4000" placeholder="Tell us what you would like to know…">{{ old('message') }}</textarea>
                  @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn pu-pd-request__submit w-100">Send request</button>
              </form>
            </div>
          </div>

          <p class="mt-4 mb-0">
            <a href="{{ route('properties.index') }}" class="pu-pd-back"><i class="fa-solid fa-arrow-left-long me-2" aria-hidden="true"></i>Back to all properties</a>
          </p>
        </aside>
      </div>
    </div>
  </div>
</article>

<div class="modal fade" id="puBrochureModal" tabindex="-1" aria-labelledby="puBrochureModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header">
        <h2 class="modal-title h5 mb-0" id="puBrochureModalLabel">Download free brochure</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{ route('properties.brochure-request', $property) }}" novalidate>
        @csrf
        <div class="modal-body">
          <p class="small text-muted mb-3">Share your details and our team will send you the brochure.</p>
          <div class="mb-3">
            <label for="pu-bro-name" class="form-label small fw-semibold text-muted mb-1">Name</label>
            <input type="text" class="form-control @error('brochure_name', 'brochure') is-invalid @enderror" id="pu-bro-name" name="brochure_name" value="{{ old('brochure_name') }}" required maxlength="120" autocomplete="name">
            @error('brochure_name', 'brochure')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="pu-bro-email" class="form-label small fw-semibold text-muted mb-1">Email</label>
            <input type="email" class="form-control @error('brochure_email', 'brochure') is-invalid @enderror" id="pu-bro-email" name="brochure_email" value="{{ old('brochure_email') }}" required maxlength="255" autocomplete="email">
            @error('brochure_email', 'brochure')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-3">
            <label for="pu-bro-phone" class="form-label small fw-semibold text-muted mb-1">Phone</label>
            <input type="tel" class="form-control @error('brochure_phone', 'brochure') is-invalid @enderror" id="pu-bro-phone" name="brochure_phone" value="{{ old('brochure_phone') }}" required maxlength="32" autocomplete="tel">
            @error('brochure_phone', 'brochure')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="mb-0">
            <label for="pu-bro-message" class="form-label small fw-semibold text-muted mb-1">Message</label>
            <textarea class="form-control @error('brochure_message', 'brochure') is-invalid @enderror" id="pu-bro-message" name="brochure_message" rows="4" required maxlength="4000" placeholder="Please share brochure and payment plan.">{{ old('brochure_message') }}</textarea>
            @error('brochure_message', 'brochure')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn pu-pd-request__submit">Submit & request brochure</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof jQuery === 'undefined' || !jQuery.fn.magnificPopup) return;
  var $g = jQuery('.pu-pd-gallery');
  if (!$g.length) return;
  $g.magnificPopup({
    delegate: 'a.pu-pd-mfp',
    type: 'image',
    gallery: { enabled: true, navigateByImgClick: true, preload: [0, 1] },
    image: { titleSrc: function (item) { return item.el.attr('title') || ''; } },
    mainClass: 'mfp-img-mobile',
    removalDelay: 200
  });

  @if($errors->brochure->any())
    var modalEl = document.getElementById('puBrochureModal');
    if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
      bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
  @endif
});
</script>
@endpush
@endsection
