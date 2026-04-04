@extends('frontend.layouts.master')
@php
  $homePage = $page ?? null;
  $heroBgDefault = 'https://images.unsplash.com/photo-1486325212027-8081e485255e?auto=format&fit=crop&w=1920&q=80';
  $heroBg = $homePage?->hero('bg_url') ?: $heroBgDefault;
  $heroLine1 = $homePage?->hero('line1') ?: 'Update your property search with';
  $heroLine2 = $homePage?->hero('line2') ?: 'PropUpdate Realty';
  $heroSubtitle = $homePage?->hero('subtitle') ?: 'where decisions are informed, not influenced';
  $heroSearchPh = $homePage?->hero('search_placeholder') ?: 'Location | Project | Builder';
@endphp
@section('title', $homePage?->browserTitle() ?? 'Home')

@section('content')
<div class="pu-hero-stack">
  <section class="pu-hero">
    <div
      class="pu-hero__bg"
      role="img"
      aria-label="Modern residential high-rise buildings at dusk"
      style="background-image: url('{{ $heroBg }}');"
    ></div>
    <div class="pu-hero__gradient" aria-hidden="true"></div>
    <div class="pu-hero__inner">
      <div class="pu-hero__content">
        <form class="pu-hero-search" action="#" method="get" role="search">
          <div class="input-wrap">
            <i class="fa-solid fa-search" aria-hidden="true"></i>
            <input type="search" name="q" placeholder="{{ $heroSearchPh }}" autocomplete="off">
          </div>
        </form>
        <h1 class="pu-hero__title">
          <span class="pu-hero__line">{{ $heroLine1 }}</span>
          <span class="pu-hero__brand">{{ $heroLine2 }}</span>
        </h1>
        <p class="pu-hero__subtitle">{{ $heroSubtitle }}</p>
      </div>
    </div>
  </section>

  @php
    $puCatFallbacks = [
      'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=900&q=80',
      'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=900&q=80',
      'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80',
      'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=900&q=80',
    ];
  @endphp
  <section class="pu-categories-wrap" aria-label="Property categories">
    <div class="pu-categories">
      @if(isset($homeCategories) && $homeCategories->isNotEmpty())
        @foreach($homeCategories as $idx => $cat)
          @php($catBg = $cat->bannerImageUrl() ?? ($puCatFallbacks[$idx] ?? $puCatFallbacks[0]))
          <a href="{{ route('properties.index', ['category_id' => $cat->id]) }}" class="pu-cat-card" style="background-image: url('{{ e($catBg) }}');">
            <span class="pu-cat-label">{{ $cat->name }}</span>
          </a>
        @endforeach
      @else
        <a href="{{ route('properties.index') }}" class="pu-cat-card" style="background-image: url('{{ e($puCatFallbacks[0]) }}');">
          <span class="pu-cat-label">Apartments</span>
        </a>
        <a href="{{ route('properties.index') }}" class="pu-cat-card" style="background-image: url('{{ e($puCatFallbacks[1]) }}');">
          <span class="pu-cat-label">Villas</span>
        </a>
        <a href="{{ route('properties.index') }}" class="pu-cat-card" style="background-image: url('{{ e($puCatFallbacks[2]) }}');">
          <span class="pu-cat-label">Commercial</span>
        </a>
        <a href="{{ route('properties.index') }}" class="pu-cat-card" style="background-image: url('{{ e($puCatFallbacks[3]) }}');">
          <span class="pu-cat-label">Plots</span>
        </a>
      @endif
    </div>
  </section>
</div>


<section class="pu-about" id="about" aria-labelledby="pu-about-heading">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="pu-about-visual">
          <div class="pu-about-blob" aria-hidden="true"></div>
          <div class="pu-about-photos">
            <div class="pu-about-photo pu-about-photo--top">
              <img
                src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&amp;fit=crop&amp;w=640&amp;q=80"
                alt="Waterfront luxury homes"
                width="320"
                height="240"
                loading="lazy"
              >
            </div>
            <div class="pu-about-photo pu-about-photo--main">
              <img
                src="https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&amp;fit=crop&amp;w=800&amp;q=80"
                alt="Modern suburban family home"
                width="480"
                height="384"
                loading="lazy"
              >
            </div>
          </div>
          <div class="pu-about-proof">
            <p class="pu-about-proof__title">Our Happy Customer</p>
            <div class="pu-about-proof__row">
              <div class="pu-about-proof__avatars">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&amp;fit=crop&amp;w=96&amp;h=96&amp;q=80" alt="">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&amp;fit=crop&amp;w=96&amp;h=96&amp;q=80" alt="">
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&amp;fit=crop&amp;w=96&amp;h=96&amp;q=80" alt="">
                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&amp;fit=crop&amp;w=96&amp;h=96&amp;q=80" alt="">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&amp;fit=crop&amp;w=96&amp;h=96&amp;q=80" alt="">
              </div>
              <span class="pu-about-proof__badge">2K+</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="pu-about-copy">
          <p class="pu-about-kicker">About PropUpdate</p>
          <h2 id="pu-about-heading">Embrace the Elegance of Our Exclusive Properties</h2>
          <p>
            At PropUpdate Realty, we help you discover homes and investments with clarity and confidence.
            Our team combines deep local market knowledge with a transparent process—so every decision
            you make is informed, not influenced.
          </p>
          <div class="pu-about-stats">
            <div class="pu-about-stat">
              <strong>10K+</strong>
              <span>Homes sold</span>
            </div>
            <div class="pu-about-stat">
              <strong>9K+</strong>
              <span>Happy clients</span>
            </div>
            <div class="pu-about-stat">
              <strong>98%</strong>
              <span>Satisfaction</span>
            </div>
          </div>
          <a href="#" class="pu-about-btn">
            See all properties
            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="pu-why" id="why-propupdate" aria-labelledby="pu-why-heading">
  <div class="pu-why-bg-pattern" aria-hidden="true"></div>
  <div class="container position-relative">
    <div class="row align-items-stretch g-4 g-xl-5">
      <div class="col-lg-5 col-xl-5">
        <div class="pu-why-showcase">
          <div class="pu-why-showcase__shape" aria-hidden="true"></div>
          <div class="pu-why-showcase__card">
            <span class="pu-why-showcase__label">
              <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
              Clarity over hype
            </span>
            <div class="pu-why-mark">
              <svg class="pu-why-mark__svg" viewBox="0 0 280 260" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <defs>
                  <linearGradient id="puWhyIconGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#001f3f"/>
                    <stop offset="100%" style="stop-color:#0a3a6e"/>
                  </linearGradient>
                </defs>
                <path fill="url(#puWhyIconGrad)" d="M140 12L272 102v18H8V102L140 12z"/>
                <rect x="44" y="118" width="42" height="132" rx="6" fill="#001f3f"/>
                <rect x="194" y="118" width="42" height="132" rx="6" fill="#001f3f"/>
                <rect x="44" y="168" width="192" height="38" rx="6" fill="#001f3f"/>
              </svg>
            </div>
            <div class="pu-why-showcase__glow" aria-hidden="true"></div>
          </div>
          <div class="pu-why-chip">
            <span class="pu-why-chip__stars" aria-hidden="true">★★★★★</span>
            <span class="pu-why-chip__text"><strong>4.9</strong> avg. client rating</span>
          </div>
        </div>
      </div>
      <div class="col-lg-7 col-xl-7">
        <div class="pu-why-copy">
          <p class="pu-why-eyebrow">Why we're different</p>
          <h2 id="pu-why-heading" class="pu-why-title">
            Why Choose <span class="pu-why-title__accent">PropUpdate?</span>
          </h2>
          <div class="pu-why-features" role="list">
            <div class="pu-why-feature" role="listitem">
              <span class="pu-why-feature__icon" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
              <p><strong>Curated listings only</strong> – no clutter, no noise.</p>
            </div>
            <div class="pu-why-feature" role="listitem">
              <span class="pu-why-feature__icon" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
              <p><strong>Transparent pricing</strong> &amp; real market insights.</p>
            </div>
            <div class="pu-why-feature" role="listitem">
              <span class="pu-why-feature__icon" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
              <p><strong>Investor-focused</strong> recommendations.</p>
            </div>
            <div class="pu-why-feature" role="listitem">
              <span class="pu-why-feature__icon" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
              <p><strong>End-to-end support</strong> from site visits to closure.</p>
            </div>
            <div class="pu-why-feature" role="listitem">
              <span class="pu-why-feature__icon" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
              <p><strong>Trusted by repeat clients</strong> &amp; referrals.</p>
            </div>
          </div>
          <blockquote class="pu-why-quote">
            <span class="pu-why-quote__mark" aria-hidden="true">"</span>
            We work closely with serious end-users and long-term investors who value clarity over hype.
          </blockquote>
          <div class="pu-why-cta-wrap">
            <a href="tel:+917204362646" class="pu-why-btn">
              Free<br>consultation
            </a>
            <a href="https://www.propupdate.com" class="pu-why-link" target="_blank" rel="noopener noreferrer">
              <span class="pu-why-link__icon" aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span>
              <span class="pu-why-link__text">www.propupdate.com</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="pu-resale" id="exclusive-resale" aria-labelledby="pu-resale-heading">
  <div class="pu-resale__corner pu-resale__corner--diamond" aria-hidden="true"></div>
  <div class="pu-resale__corner pu-resale__corner--waves" aria-hidden="true"></div>

  <div class="container position-relative">
    <div class="pu-resale-hero row align-items-stretch g-0">
      <div class="col-lg-6 pu-resale-hero__copy order-2 order-lg-1">
        <div class="pu-resale-hero__pattern" aria-hidden="true"></div>
        <h2 id="pu-resale-heading" class="pu-resale-hero__title">
          Exclusive resale deals in North Bangalore
        </h2>
      </div>
      <div class="col-lg-6 pu-resale-hero__visual order-1 order-lg-2">
        <div class="pu-resale-hero__frame">
          <img
            src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&amp;fit=crop&amp;w=900&amp;q=85"
            alt="Modern high-rise residences in Bangalore"
            width="640"
            height="800"
            loading="lazy"
            class="pu-resale-hero__img"
          >
          <div class="pu-resale-hero__accent-bar" aria-hidden="true"></div>
          <span class="pu-resale-hero__spark pu-resale-hero__spark--1" aria-hidden="true">✦</span>
          <span class="pu-resale-hero__spark pu-resale-hero__spark--2" aria-hidden="true">✦</span>
        </div>
      </div>
    </div>

    <div class="row pu-resale-grid g-4 g-lg-5">
      <div class="col-6 col-lg-3">
        <div class="pu-resale-card">
          <div class="pu-resale-card__icon" aria-hidden="true">
            <i class="fa-solid fa-tags"></i>
          </div>
          <h3 class="pu-resale-card__title">Below-Market Pricing</h3>
          <p class="pu-resale-card__text">Select resale homes available at prices lower than current market rates.</p>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="pu-resale-card">
          <div class="pu-resale-card__icon" aria-hidden="true">
            <i class="fa-solid fa-bolt"></i>
          </div>
          <h3 class="pu-resale-card__title">Immediate Availability</h3>
          <p class="pu-resale-card__text">Ready or near-ready properties with faster possession and quick closure.</p>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="pu-resale-card">
          <div class="pu-resale-card__icon" aria-hidden="true">
            <i class="fa-solid fa-clipboard-check"></i>
          </div>
          <h3 class="pu-resale-card__title">Verified &amp; Clear Titles</h3>
          <p class="pu-resale-card__text">Every resale deal is legally checked for ownership, approvals, and documentation.</p>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="pu-resale-card">
          <div class="pu-resale-card__icon" aria-hidden="true">
            <i class="fa-solid fa-percent"></i>
          </div>
          <h3 class="pu-resale-card__title">Off-Market Access</h3>
          <p class="pu-resale-card__text">Exclusive listings not publicly advertised, shared only with serious buyers.</p>
        </div>
      </div>
    </div>
  </div>
</section>

@if(isset($services) && $services->isNotEmpty())
<section class="pu-services" id="services" aria-labelledby="pu-services-heading">
  <div class="container">
    <p class="pu-services__kicker">What we do</p>
    <h2 id="pu-services-heading" class="pu-services__title">Our services</h2>
    <p class="pu-services__lead">Practical support across the property journey — from first brief to documentation.</p>
    <div class="row g-4 pu-services__grid">
      @foreach($services as $svc)
        <div class="col-sm-6 col-lg-3">
          <div class="pu-service-card">
            <div class="pu-service-card__icon" aria-hidden="true">
              @if($svc->icon_class)
                <i class="{{ $svc->icon_class }}"></i>
              @else
                <i class="fa-solid fa-circle-check"></i>
              @endif
            </div>
            <h3 class="pu-service-card__title">{{ $svc->name }}</h3>
            <p class="pu-service-card__text">{{ $svc->summary }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="pu-launches" id="pre-register" aria-labelledby="pu-launches-heading">
  <div class="pu-launches__bg" aria-hidden="true"></div>
  <div class="container position-relative">
    @if (session('pre_register_status'))
      <div class="alert alert-success pu-launches__alert" role="status">
        {{ session('pre_register_status') }}
      </div>
    @endif
    @if ($errors->has('name') || $errors->has('email') || $errors->has('message'))
      <div class="alert alert-danger pu-launches__alert" role="alert">
        @foreach ($errors->all() as $err)
          <div>{{ $err }}</div>
        @endforeach
      </div>
    @endif

    <div class="row g-5 align-items-start">
      <div class="col-lg-6">
        <div class="pu-launches-copy">
          <h2 id="pu-launches-heading" class="pu-launches__title">
            Explore new property launches across Bangalore
          </h2>
          <p class="pu-launches__lead">
            Get early visibility on upcoming projects—pricing bands, floor plans, and launch offers—before they go wide to the market.
          </p>
          <h3 class="pu-launches__sub">Benefits of booking in the pre-launch stage</h3>
          <ul class="pu-launches-benefits">
            <li>
              <span class="pu-launches-benefits__icon" aria-hidden="true"><i class="fa-solid fa-chart-line"></i></span>
              <div>
                <strong>Lowest entry price</strong>
                <span>Early-bird tiers typically offer the sharpest pricing before escalations at launch.</span>
              </div>
            </li>
            <li>
              <span class="pu-launches-benefits__icon" aria-hidden="true"><i class="fa-solid fa-layer-group"></i></span>
              <div>
                <strong>Best choice of inventory</strong>
                <span>Pick preferred floors, views, and unit types while the full stack is still available.</span>
              </div>
            </li>
            <li>
              <span class="pu-launches-benefits__icon" aria-hidden="true"><i class="fa-solid fa-rocket"></i></span>
              <div>
                <strong>Higher appreciation potential</strong>
                <span>Buy closer to developer pricing and benefit as the project matures and the micro-market grows.</span>
              </div>
            </li>
            <li>
              <span class="pu-launches-benefits__icon" aria-hidden="true"><i class="fa-solid fa-shield-halved"></i></span>
              <div>
                <strong>RERA, launch &amp; construction clarity</strong>
                <span>We help you track approvals, launch milestones, construction progress, and realistic possession timelines.</span>
              </div>
            </li>
          </ul>
          <p class="pu-launches__cta-line">
            Pre-launch is where informed buyers create maximum value:
            <strong>right pricing, right unit, right timing.</strong>
          </p>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="pu-launches-aside">
          <p class="pu-launches-aside__intro">
            Pre-register to get early access to <strong>pricing, floor plans</strong> and <strong>exclusive launch offers</strong>.
          </p>
          <div class="pu-launches-form-card">
            <h3 class="pu-launches-form-card__title">Contact form</h3>
            <form class="pu-launches-form" action="{{ route('lead.pre-register') }}" method="post" novalidate>
              @csrf
              <div class="pu-launches-field">
                <label for="launch-name">Name</label>
                <input type="text" id="launch-name" name="name" value="{{ old('name') }}" placeholder="Your name" autocomplete="name" required>
              </div>
              <div class="pu-launches-field">
                <label for="launch-email">Email address</label>
                <input type="email" id="launch-email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
              </div>
              <div class="pu-launches-field">
                <label for="launch-message">Leave a message</label>
                <textarea id="launch-message" name="message" rows="4" placeholder="Project preferences, budget range, timeline…">{{ old('message') }}</textarea>
              </div>
              <button type="submit" class="pu-launches-submit">Submit</button>
              <p class="pu-launches-form__note">
                We respect your privacy. Your details are used only to respond to this enquiry—never for spam.
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
