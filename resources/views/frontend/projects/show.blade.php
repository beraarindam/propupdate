@extends('frontend.layouts.master')

@php
  $page = null;
  $gallery = $project->galleryPublicUrls();
  $bannerLead = $project->summary
    ? \Illuminate\Support\Str::limit(strip_tags($project->summary), 200)
    : \Illuminate\Support\Str::limit(strip_tags($project->body), 200);
  $metaBits = collect([$project->location, $project->developer_name])->filter()->implode(' · ');
  $locLine = $project->locationAddressLine() ?: $project->location;
  $qfSidebar = $project->quickFactsRows();
  $ctaTitle = $project->ctaHeadline() ?: 'Request information';
  $ctaSub = $project->ctaSubtext() ?: 'Ask for floor plans, availability, and best units — we respond quickly.';
@endphp

@section('title', $project->browserTitle())

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $project->title,
  'crumbCurrent' => $project->title,
  'lead' => $metaBits !== '' ? e($metaBits).' — '.$bannerLead : $bannerLead,
  'bgImage' => $project->featuredBannerUrl() ?? 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1920&q=80',
  'bannerClass' => 'pu-page-banner--property',
])

<section class="pu-pd-strip pu-prj-strip">
  <div class="container">
    <div class="pu-pd-strip__inner">
      <div class="pu-pd-chips">
        @if($project->is_featured)
          <span class="pu-pd-chip pu-pd-chip--feat"><i class="fa-solid fa-star me-1" aria-hidden="true"></i>Featured project</span>
        @endif
        @if($locLine)
          <span class="pu-pd-chip pu-pd-chip--muted"><i class="fa-solid fa-location-dot me-1" aria-hidden="true"></i>{{ $locLine }}</span>
        @endif
        @if($project->developer_name)
          <span class="pu-pd-chip pu-pd-chip--muted"><i class="fa-solid fa-helmet-safety me-1" aria-hidden="true"></i>{{ $project->developer_name }}</span>
        @endif
      </div>
      <div class="pu-pd-strip__price pu-prj-strip__tag">
        <span class="pu-pd-price-tag pu-prj-strip__pill">Project</span>
        <span class="pu-pd-price-note d-none d-md-inline">Site visit · floor plans · advisory</span>
      </div>
    </div>
  </div>
</section>

<article class="pu-pd-main pu-prj-page">
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
                  <a href="{{ $url }}" class="pu-pd-gallery__item pu-pd-mfp" title="{{ e(\Illuminate\Support\Str::limit($project->title, 80)) }}">
                    <div class="ratio ratio-4x3">
                      <img src="{{ $url }}" alt="{{ e($project->title.' — photo '.($idx + 1)) }}" class="pu-pd-gallery__img" loading="{{ $idx < 3 ? 'eager' : 'lazy' }}" width="400" height="300">
                    </div>
                    <span class="pu-pd-gallery__zoom" aria-hidden="true"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
                  </a>
                </div>
              @endforeach
            </div>
          </section>
        @endif

        @include('frontend.projects._microsite', ['project' => $project])
      </div>

      <div class="col-lg-4">
        <aside class="pu-pd-sidebar pu-prj-sidebar">
          <div class="pu-pd-facts card border-0 shadow-lg pu-prj-facts-card">
            <div class="pu-pd-facts__head">
              <h2 class="pu-pd-facts__title">Quick facts</h2>
              <p class="pu-pd-facts__sub text-white-50 small mb-0">Key details at a glance</p>
            </div>
            <div class="card-body pu-pd-facts__body">
              <ul class="pu-pd-facts__list list-unstyled mb-0">
                @forelse($qfSidebar as $row)
                  <li class="pu-pd-fact">
                    <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-circle-check"></i></span>
                    <div>
                      <span class="pu-pd-fact__label">{{ $row['label'] }}</span>
                      <span class="pu-pd-fact__value">{{ $row['value'] }}</span>
                    </div>
                  </li>
                @empty
                  @if($project->location)
                    <li class="pu-pd-fact">
                      <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-location-dot"></i></span>
                      <div>
                        <span class="pu-pd-fact__label">Location</span>
                        <span class="pu-pd-fact__value">{{ $project->location }}</span>
                      </div>
                    </li>
                  @endif
                  @if($project->developer_name)
                    <li class="pu-pd-fact">
                      <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-helmet-safety"></i></span>
                      <div>
                        <span class="pu-pd-fact__label">Developer</span>
                        <span class="pu-pd-fact__value">{{ $project->developer_name }}</span>
                      </div>
                    </li>
                  @endif
                  @if($project->rera_number)
                    <li class="pu-pd-fact">
                      <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-shield-halved"></i></span>
                      <div>
                        <span class="pu-pd-fact__label">RERA</span>
                        <span class="pu-pd-fact__value">{{ $project->rera_number }}</span>
                      </div>
                    </li>
                  @endif
                  @if(!$project->location && !$project->developer_name && !$project->rera_number)
                    <li class="pu-pd-fact">
                      <span class="pu-pd-fact__icon" aria-hidden="true"><i class="fa-solid fa-layer-group"></i></span>
                      <div>
                        <span class="pu-pd-fact__label">Project</span>
                        <span class="pu-pd-fact__value text-white-50">Full details below</span>
                      </div>
                    </li>
                  @endif
                @endforelse
              </ul>
              @php($addrOnly = is_string($project->extra('location_address')) ? trim($project->extra('location_address')) : '')
              @if($addrOnly !== '')
                <div class="pu-pd-address mt-3 pt-3 border-top border-light border-opacity-25">
                  <span class="pu-pd-address__label"><i class="fa-solid fa-map-pin me-2" aria-hidden="true"></i>Address</span>
                  <p class="pu-pd-address__text mb-0">{{ $addrOnly }}</p>
                </div>
              @endif
            </div>
          </div>

          <div class="pu-pd-request card border-0 shadow-lg mt-4 pu-prj-cta-card" id="pu-project-request">
            <div class="pu-pd-request__head">
              <h2 class="pu-pd-request__title mb-0">{{ $ctaTitle }}</h2>
              <p class="pu-pd-request__sub text-white-50 small mb-0 mt-1">{{ $ctaSub }}</p>
            </div>
            <div class="card-body pu-pd-request__body">
              @if(session('project_enquiry_status'))
                <div class="alert alert-success py-2 px-3 small mb-3" role="status">{{ session('project_enquiry_status') }}</div>
              @endif
              <div class="d-grid gap-2 mb-3">
                <a href="{{ $siteSettings?->whatsappHref() ?? 'https://wa.me/917204362646' }}" class="btn btn-success fw-semibold" target="_blank" rel="noopener noreferrer">
                  <i class="fa-brands fa-whatsapp me-2" aria-hidden="true"></i>Chat on WhatsApp
                </a>
              </div>
              <p class="small text-muted text-center mb-3">or send a message below</p>
              <form method="post" action="{{ route('projects.enquiry', $project) }}" novalidate>
                @csrf
                <div class="mb-3">
                  <label for="pu-prj-enq-name" class="form-label small fw-semibold text-muted mb-1">Name</label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="pu-prj-enq-name" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name">
                  @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                  <label for="pu-prj-enq-email" class="form-label small fw-semibold text-muted mb-1">Email</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="pu-prj-enq-email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email">
                  @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                  <label for="pu-prj-enq-phone" class="form-label small fw-semibold text-muted mb-1">Phone <span class="fw-normal opacity-75">(optional)</span></label>
                  <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="pu-prj-enq-phone" name="phone" value="{{ old('phone') }}" maxlength="32" autocomplete="tel">
                  @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                  <label for="pu-prj-enq-message" class="form-label small fw-semibold text-muted mb-1">Message</label>
                  <textarea class="form-control @error('message') is-invalid @enderror" id="pu-prj-enq-message" name="message" rows="4" required maxlength="4000" placeholder="I would like to know more about this project…">{{ old('message') }}</textarea>
                  @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn pu-pd-request__submit w-100">Send request</button>
              </form>
            </div>
          </div>

          <p class="mt-4 mb-0">
            <a href="{{ route('projects.index') }}" class="pu-pd-back"><i class="fa-solid fa-arrow-left-long me-2" aria-hidden="true"></i>All projects</a>
          </p>
        </aside>
      </div>
    </div>
  </div>
</article>

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
});
</script>
@endpush
@endsection
