@extends('frontend.layouts.master')
@section('title', $page?->browserTitle() ?? 'About us')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $page?->banner_title ?? 'About PropUpdate',
  'crumbCurrent' => $page?->name ?? 'About us',
  'lead' => $page?->banner_lead ?? 'Where every property decision is <strong>informed</strong>, not influenced — serving serious buyers and investors across Bangalore.',
  'bgImage' => $page?->banner_image_url ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1920&q=80',
])

@if(filled($page?->body_html))
<section class="pu-about-page-intro pt-0">
  <div class="container">
    <div class="pu-legal__inner pu-page-body-cms">
      {!! $page->body_html !!}
    </div>
  </div>
</section>
@endif

<section class="pu-about-page-intro @if(filled($page?->body_html)) pt-4 pt-lg-5 @endif">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="pu-about-page-visual">
          <div class="pu-about-page-visual__blob" aria-hidden="true"></div>
          <div class="pu-about-page-visual__frame">
            <img
              src="https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&amp;fit=crop&amp;w=900&amp;q=80"
              alt="Modern home representing PropUpdate listings"
              width="560"
              height="420"
              loading="lazy"
            >
          </div>
          <div class="pu-about-page-badge">
            <span class="pu-about-page-badge__num">10+</span>
            <span class="pu-about-page-badge__text">Years collective experience</span>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <p class="pu-about-page-kicker">Who we are</p>
        <h2 class="pu-about-page-h2">Built for buyers who read the fine print</h2>
        <p class="pu-about-page-text">
          PropUpdate Realty is a Bangalore-focused practice for <strong>resale</strong>, <strong>new launches</strong>, and <strong>investment-grade inventory</strong>.
          We combine market data, legal diligence, and straight answers — so you never discover surprises after you commit.
        </p>
        <p class="pu-about-page-text">
          Whether you are upgrading your home or building a portfolio, we act as your research partner, not a billboard.
        </p>
        <a href="{{ route('home') }}#pre-register" class="pu-about-page-cta">Get launch access</a>
      </div>
    </div>
  </div>
</section>

<section class="pu-founder" aria-labelledby="founder-heading">
  <div class="container-fluid px-0">
    <div class="row g-0 align-items-lg-stretch pu-founder__row">
      <div class="col-lg-6 order-2 order-lg-1">
        <div class="pu-founder__content">
          <span class="pu-founder__ribbon" aria-hidden="true"></span>
          <p class="pu-founder__eyebrow" id="founder-heading">Founder's note</p>
          <h2 class="pu-founder__name">Roshan Kumar</h2>
          <p class="pu-founder__role">Founder – PropUpdate Realty</p>
          <blockquote class="pu-founder__quote">
            <span class="pu-founder__quote-mark" aria-hidden="true">“</span>
            Real estate decisions should be driven by insight, not urgency.
          </blockquote>
          <p class="pu-founder__body">
            At <strong>PropUpdate Realty</strong>, my goal is to help clients buy the right property, at the right time, for the right reason.
          </p>
          <p class="pu-founder__body">
            With hands-on market experience in Bangalore real estate, <strong>Roshan</strong> works personally with clients to identify properties that deliver lifestyle value, appreciation, and long-term security.
          </p>
        </div>
      </div>
      <div class="col-lg-6 order-1 order-lg-2">
        <div class="pu-founder__visual">
          <div class="pu-founder__visual-glow" aria-hidden="true"></div>
          {{-- Professional portrait via Unsplash (replace with your own photo anytime) --}}
          <img
            class="pu-founder__photo pu-founder__photo--photo"
            src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&amp;fit=crop&amp;w=900&amp;q=80"
            alt="Professional portrait representing PropUpdate Realty leadership"
            width="600"
            height="750"
            loading="lazy"
            decoding="async"
            referrerpolicy="no-referrer-when-downgrade"
          >
        </div>
      </div>
    </div>
  </div>
</section>

<section class="pu-about-page-values">
  <div class="container">
    <div class="pu-about-page-values__head">
      <p class="pu-about-page-kicker">What we stand for</p>
      <h2 class="pu-about-page-h2 pu-about-page-h2--center">Three pillars</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="pu-value-card">
          <span class="pu-value-card__icon" aria-hidden="true"><i class="fa-solid fa-shield-halved"></i></span>
          <h3>Transparency</h3>
          <p>Pricing history, loading factors, approvals, and realistic timelines — shared early, in plain language.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="pu-value-card">
          <span class="pu-value-card__icon" aria-hidden="true"><i class="fa-solid fa-bullseye"></i></span>
          <h3>Fit over hype</h3>
          <p>We match you to micro-markets and product types that suit your goals — not what is easiest to sell.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="pu-value-card">
          <span class="pu-value-card__icon" aria-hidden="true"><i class="fa-solid fa-handshake"></i></span>
          <h3>End-to-end care</h3>
          <p>From first site visit through documentation and handover — one accountable team.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="pu-about-page-stats">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-6 col-lg-3">
        <div class="pu-stat-block">
          <strong class="pu-stat-block__num">10K+</strong>
          <span class="pu-stat-block__label">Homes sold <br><em>(ecosystem)</em></span>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="pu-stat-block">
          <strong class="pu-stat-block__num">9K+</strong>
          <span class="pu-stat-block__label">Happy clients</span>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="pu-stat-block">
          <strong class="pu-stat-block__num">98%</strong>
          <span class="pu-stat-block__label">Satisfaction focus</span>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="pu-stat-block pu-stat-block--accent">
          <span class="pu-stat-block__cta-label">Talk to us</span>
          <a href="tel:+917204362646" class="pu-stat-block__tel">7204362646</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
