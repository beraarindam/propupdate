@extends('frontend.layouts.master')

@php
  $bannerBg = $page?->bannerBackgroundUrl() ?? 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1920&q=80';
@endphp

@section('title', $page?->browserTitle() ?? 'Exclusive resale')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $page?->banner_title ?? 'Exclusive resale',
  'crumbCurrent' => $page?->name ?? 'Exclusive resale',
  'lead' => $page?->banner_lead ?? 'Curated <strong>resale</strong> opportunities — verified inventory, clear numbers, and straight answers.',
  'bgImage' => $bannerBg,
])

@if(session('exclusive_resale_enquiry_status'))
  <div class="container pt-3">
    <div class="alert alert-success border-0 pu-er-alert" role="status">
      {{ session('exclusive_resale_enquiry_status') }}
    </div>
  </div>
@endif

@if(filled($page?->body_html))
<section class="pu-page-intro-cms pt-0">
  <div class="container py-3 py-lg-4">
    <div class="pu-legal__inner pu-page-body-cms">
      {!! $page->body_html !!}
    </div>
  </div>
</section>
@endif

<section class="pu-er-page @if(filled($page?->body_html)) pt-0 @endif">
  <div class="container py-4 py-lg-5">
    @if($listings->isEmpty())
      <div class="pu-pl-empty">
        <div class="pu-pl-empty__icon" aria-hidden="true"><i class="fa-solid fa-building-columns"></i></div>
        <h2 class="h5 pu-pl-empty__title">Listings coming soon</h2>
        <p class="text-muted mb-0">We are updating exclusive inventory — please check back or <a href="{{ route('pages.contact') }}">contact us</a>.</p>
      </div>
    @else
      <div class="row g-4 justify-content-center">
        @foreach($listings as $item)
          @php
            $img = $item->imagePublicUrl();
            $shareUrl = url()->current().'#er-'.$item->id;
          @endphp
          <div class="col-md-6 col-xl-4">
            <article class="pu-er-card" id="er-{{ $item->id }}">
              @if(filled($item->status_badge))
                <div class="pu-er-card__ribbon" aria-hidden="true">
                  <span class="pu-er-card__ribbon-text">{{ $item->status_badge }}</span>
                </div>
              @endif
              <div class="pu-er-card__inner">
                <p class="pu-er-card__code">{{ $item->displayCode() }}</p>
                <h2 class="pu-er-card__title">{{ $item->title }}</h2>
                <div class="pu-er-card__visual">
                  @if($img)
                    <img src="{{ e($img) }}" alt="" class="pu-er-card__photo" width="640" height="640" loading="lazy" decoding="async">
                  @else
                    <div class="pu-er-card__photo pu-er-card__photo--placeholder" aria-hidden="true"></div>
                  @endif
                </div>
                @if(filled($item->location))
                  <p class="pu-er-card__loc"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> {{ $item->location }}</p>
                @endif
                <div class="pu-er-card__pills">
                  @if(filled($item->property_type))
                    <span class="pu-er-pill">{{ $item->property_type }}</span>
                  @endif
                  @if(filled($item->configuration))
                    <span class="pu-er-pill">{{ $item->configuration }}</span>
                  @endif
                  @if(filled($item->area_display))
                    <span class="pu-er-pill">{{ $item->area_display }}</span>
                  @endif
                </div>
                <dl class="pu-er-card__prices">
                  @if(filled($item->market_price))
                    <div class="pu-er-card__price-row">
                      <dt>Market price</dt>
                      <dd>{{ $item->market_price }}</dd>
                    </div>
                  @endif
                  @if(filled($item->asking_price))
                    <div class="pu-er-card__price-row">
                      <dt>Asking price</dt>
                      <dd>{{ $item->asking_price }}</dd>
                    </div>
                  @endif
                  @if(filled($item->rate_per_sqft))
                    <div class="pu-er-card__price-row">
                      <dt>Rate / sq.ft</dt>
                      <dd>{{ $item->rate_per_sqft }}</dd>
                    </div>
                  @endif
                </dl>
                <div class="pu-er-card__actions">
                  <div class="pu-er-card__cta-row">
                    <button type="button" class="pu-er-btn pu-er-btn--ghost pu-er-card__enquiry-btn" data-pu-er-open="{{ $item->id }}" aria-haspopup="dialog" aria-controls="pu-er-modal-{{ $item->id }}">
                      <i class="fa-regular fa-hand-pointer me-1" aria-hidden="true"></i> Enquiry now
                    </button>
                    <button type="button" class="pu-er-btn pu-er-btn--primary pu-er-card__share-btn" data-pu-share="{{ e($shareUrl) }}">
                      <i class="fa-solid fa-share-nodes me-1" aria-hidden="true"></i> <span class="pu-er-share-label">Share</span>
                    </button>
                  </div>
                </div>
              </div>
            </article>
            <div
              class="pu-er-modal @if($errors->any() && (int) old('er_listing_id') === $item->id) is-open @endif"
              id="pu-er-modal-{{ $item->id }}"
              role="dialog"
              aria-modal="true"
              aria-labelledby="pu-er-modal-title-{{ $item->id }}"
              aria-hidden="{{ $errors->any() && (int) old('er_listing_id') === $item->id ? 'false' : 'true' }}"
              data-pu-er-modal
            >
              <button type="button" class="pu-er-modal__backdrop" data-pu-er-close tabindex="-1" aria-label="Close dialog"></button>
              <div class="pu-er-modal__box">
                <button type="button" class="pu-er-modal__x" data-pu-er-close aria-label="Close">
                  <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
                <h3 class="pu-er-modal__title" id="pu-er-modal-title-{{ $item->id }}">Enquiry — {{ $item->displayCode() }}</h3>
                <p class="pu-er-modal__subtitle">{{ $item->title }}</p>
                @if($errors->any() && (int) old('er_listing_id') === $item->id)
                  <div class="alert alert-danger py-2 px-3 small mb-3" role="alert">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                  </div>
                @endif
                <form action="{{ route('exclusive-resale.enquiry', $item) }}" method="post" class="pu-er-enquiry-form">
                  @csrf
                  <input type="hidden" name="er_listing_id" value="{{ $item->id }}">
                  <div class="mb-2">
                    <label class="pu-er-enquiry-form__label" for="er-name-{{ $item->id }}">Name</label>
                    <input type="text" class="pu-er-enquiry-form__input" id="er-name-{{ $item->id }}" name="name" value="{{ (int) old('er_listing_id') === $item->id ? old('name') : '' }}" required maxlength="120" autocomplete="name">
                  </div>
                  <div class="mb-2">
                    <label class="pu-er-enquiry-form__label" for="er-email-{{ $item->id }}">Email</label>
                    <input type="email" class="pu-er-enquiry-form__input" id="er-email-{{ $item->id }}" name="email" value="{{ (int) old('er_listing_id') === $item->id ? old('email') : '' }}" required maxlength="255" autocomplete="email">
                  </div>
                  <div class="mb-2">
                    <label class="pu-er-enquiry-form__label" for="er-phone-{{ $item->id }}">Phone <span class="pu-er-muted">(optional)</span></label>
                    <input type="text" class="pu-er-enquiry-form__input" id="er-phone-{{ $item->id }}" name="phone" value="{{ (int) old('er_listing_id') === $item->id ? old('phone') : '' }}" maxlength="32" autocomplete="tel">
                  </div>
                  <div class="mb-3">
                    <label class="pu-er-enquiry-form__label" for="er-msg-{{ $item->id }}">Message</label>
                    <textarea class="pu-er-enquiry-form__input" id="er-msg-{{ $item->id }}" name="message" rows="3" required maxlength="4000" placeholder="Tell us your timeline and budget…">{{ (int) old('er_listing_id') === $item->id ? old('message') : '' }}</textarea>
                  </div>
                  <button type="submit" class="pu-er-btn pu-er-btn--submit w-100">Send enquiry</button>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
  function setBodyScroll(lock) {
    document.body.style.overflow = lock ? 'hidden' : '';
  }

  function openModal(modal) {
    if (!modal) return;
    document.querySelectorAll('[data-pu-er-modal].is-open').forEach(function (m) {
      m.classList.remove('is-open');
      m.setAttribute('aria-hidden', 'true');
    });
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    setBodyScroll(true);
    var first = modal.querySelector('input:not([type="hidden"]), textarea');
    if (first) setTimeout(function () { first.focus(); }, 100);
  }

  function closeModal(modal) {
    if (!modal) return;
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    setBodyScroll(document.querySelector('[data-pu-er-modal].is-open') !== null);
  }

  document.querySelectorAll('[data-pu-er-open]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var id = btn.getAttribute('data-pu-er-open');
      var modal = document.getElementById('pu-er-modal-' + id);
      openModal(modal);
    });
  });

  document.querySelectorAll('[data-pu-er-modal] [data-pu-er-close]').forEach(function (el) {
    el.addEventListener('click', function () {
      closeModal(el.closest('[data-pu-er-modal]'));
    });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    document.querySelectorAll('[data-pu-er-modal].is-open').forEach(function (m) {
      closeModal(m);
    });
  });

  if (document.querySelector('[data-pu-er-modal].is-open')) {
    setBodyScroll(true);
  }

  document.querySelectorAll('[data-pu-share]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var url = btn.getAttribute('data-pu-share');
      if (!url) return;
      if (navigator.share) {
        navigator.share({ title: document.title, url: url }).catch(function () {});
        return;
      }
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(function () {
          var label = btn.querySelector('.pu-er-share-label');
          if (!label) return;
          var t = label.textContent;
          label.textContent = 'Copied!';
          setTimeout(function () { label.textContent = t; }, 1800);
        });
        return;
      }
      window.prompt('Copy link:', url);
    });
  });
})();
</script>
@endpush
