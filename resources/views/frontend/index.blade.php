@extends('frontend.layouts.master')
@php
  $homePage = $page ?? null;
  $heroBgDefault = 'https://images.unsplash.com/photo-1486325212027-8081e485255e?auto=format&fit=crop&w=1920&q=80';
  $heroBg = $homePage?->bannerBackgroundUrl() ?: $heroBgDefault;
  $heroBgAlt = $homePage?->hero('bg_alt') ?: 'Modern residential high-rise buildings at dusk';
  $heroLine1 = $homePage?->hero('line1') ?: 'Update your property search with';
  $heroLine2 = $homePage?->hero('line2') ?: 'PropUpdate Realty';
  $heroSubtitle = $homePage?->hero('subtitle') ?: 'where decisions are informed, not influenced';
  $heroSearchPh = $homePage?->hero('search_placeholder') ?: 'Location | Project | Builder';
  $heroTypingPhrases = array_values(array_unique(array_filter([
      $heroSearchPh,
      'Whitefield · Indiranagar · Koramangala…',
      'Search by builder, project, or area…',
  ], fn ($s) => is_string($s) && $s !== '')));
@endphp
@section('title', $homePage?->browserTitle() ?? 'Home')

@section('content')
<div class="pu-hero-stack">
  <section class="pu-hero">
    <div
      class="pu-hero__bg"
      role="img"
      aria-label="{{ e($heroBgAlt) }}"
      style="background-image: url('{{ $heroBg }}');"
    ></div>
    <div class="pu-hero__gradient" aria-hidden="true"></div>
    <div class="pu-hero__inner">
      <div class="pu-hero__content">
        <div
          class="pu-hero-search pu-hero-search--live pu-hero-search--idle"
          id="pu-hero-search"
          data-suggestions-url="{{ route('properties.suggestions') }}"
          data-list-url="{{ route('properties.index') }}"
          data-typing-phrases='@json($heroTypingPhrases)'
        >
          <form class="pu-hero-search__form" id="pu-hero-search-form" action="{{ route('properties.index') }}" method="get" role="search" autocomplete="off">
            <div class="input-wrap">
              <i class="fa-solid fa-search" aria-hidden="true"></i>
              <div class="pu-hero-search__field">
                <label for="pu-hero-q" class="visually-hidden">Search properties</label>
                <input
                  type="search"
                  name="q"
                  id="pu-hero-q"
                  value=""
                  autocomplete="off"
                  aria-autocomplete="list"
                  aria-controls="pu-hero-search-panel"
                  aria-expanded="false"
                  inputmode="search"
                >
                <span class="pu-hero-search__typing" id="pu-hero-typing" aria-hidden="true"></span>
              </div>
              <button type="submit" class="pu-hero-search__submit">
                <span class="pu-hero-search__submit-label">Search</span>
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
              </button>
            </div>
            <div class="pu-hero-search__panel" id="pu-hero-search-panel" role="listbox" aria-label="Matching listings" hidden>
              <div class="pu-hero-search__loading" id="pu-hero-search-loading" hidden>
                <span class="pu-hero-search__spinner" aria-hidden="true"></span>
                <span>Searching…</span>
              </div>
              <div class="pu-hero-search__list" id="pu-hero-search-list"></div>
              <div class="pu-hero-search__foot" id="pu-hero-search-foot" hidden>
                <a href="{{ route('properties.index') }}" class="pu-hero-search__all" id="pu-hero-search-all">View all results</a>
              </div>
            </div>
          </form>
        </div>
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
    $customCatItemsRaw = (array) ($homePage?->section('categories.items') ?? []);
    $customCatItems = array_values(array_filter($customCatItemsRaw, fn ($row) => filled($row['label'] ?? '')));
    $useCustomCats = ($homePage?->section('categories.source') === 'custom') && count($customCatItems) > 0;
  @endphp
  <section class="pu-categories-wrap" aria-label="Property categories">
    <div class="pu-categories">
      @if($useCustomCats)
        @foreach($customCatItems as $idx => $item)
          @php
            $href = \App\Models\Page::resolveHref($item['href'] ?? '');
            $catBg = \App\Models\Page::mediaPublicUrl($item['image_path'] ?? null, $item['image_url'] ?? null)
              ?? ($puCatFallbacks[$idx] ?? $puCatFallbacks[0]);
          @endphp
          <a href="{{ $href }}" class="pu-cat-card" style="background-image: url('{{ e($catBg) }}');">
            <span class="pu-cat-label">{{ $item['label'] }}</span>
          </a>
        @endforeach
      @elseif(isset($homeCategories) && $homeCategories->isNotEmpty())
        @foreach($homeCategories as $idx => $cat)
          @php
            $catBg = $cat->bannerImageUrl() ?? ($puCatFallbacks[$idx] ?? $puCatFallbacks[0]);
          @endphp
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

@if(filled($homePage?->body_html))
<section class="pu-home-cms-intro py-4 border-bottom bg-light-subtle">
  <div class="container pu-page-body-cms">
    {!! $homePage->body_html !!}
  </div>
</section>
@endif

@php
  $abKicker = $homePage?->section('about.kicker') ?: 'About PropUpdate';
  $abHeading = $homePage?->section('about.heading') ?: 'Embrace the Elegance of Our Exclusive Properties';
  $abBody = $homePage?->section('about.body');
  $abPhotoTop = \App\Models\Page::mediaPublicUrl($homePage?->section('about.photo_top_path'), $homePage?->section('about.photo_top_url'))
    ?: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=640&q=80';
  $abPhotoMain = \App\Models\Page::mediaPublicUrl($homePage?->section('about.photo_main_path'), $homePage?->section('about.photo_main_url'))
    ?: 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=800&q=80';
  $abPhotoTopAlt = $homePage?->section('about.photo_top_alt') ?: 'Waterfront luxury homes';
  $abPhotoMainAlt = $homePage?->section('about.photo_main_alt') ?: 'Modern suburban family home';
  $abProofTitle = $homePage?->section('about.proof_title') ?: 'Our Happy Customer';
  $abProofBadge = $homePage?->section('about.proof_badge') ?: '2K+';
  $proofPaths = (array) ($homePage?->section('about.proof_avatar_paths') ?? []);
  $proofUrls = (array) ($homePage?->section('about.proof_avatar_urls') ?? []);
  $abAvatars = [];
  for ($pi = 0; $pi < 5; $pi++) {
    $u = \App\Models\Page::mediaPublicUrl($proofPaths[$pi] ?? null, $proofUrls[$pi] ?? null);
    if ($u) {
      $abAvatars[] = $u;
    }
  }
  $abDefaultAvatars = [
    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=96&h=96&q=80',
    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=96&h=96&q=80',
    'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=96&h=96&q=80',
    'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=96&h=96&q=80',
    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=96&h=96&q=80',
  ];
  $abStat1v = $homePage?->section('about.stat1_val') ?: '10K+';
  $abStat1l = $homePage?->section('about.stat1_label') ?: 'Homes sold';
  $abStat2v = $homePage?->section('about.stat2_val') ?: '9K+';
  $abStat2l = $homePage?->section('about.stat2_label') ?: 'Happy clients';
  $abStat3v = $homePage?->section('about.stat3_val') ?: '98%';
  $abStat3l = $homePage?->section('about.stat3_label') ?: 'Satisfaction';
  $abBtnText = $homePage?->section('about.btn_text') ?: 'See all properties';
  $abBtnUrl = \App\Models\Page::resolveHref($homePage?->section('about.btn_url') ?: '');
@endphp
<section class="pu-about" id="about" aria-labelledby="pu-about-heading">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="pu-about-visual">
          <div class="pu-about-blob" aria-hidden="true"></div>
          <div class="pu-about-photos">
            <div class="pu-about-photo pu-about-photo--top">
              <img
                src="{{ e($abPhotoTop) }}"
                alt="{{ e($abPhotoTopAlt) }}"
                width="320"
                height="240"
                loading="lazy"
              >
            </div>
            <div class="pu-about-photo pu-about-photo--main">
              <img
                src="{{ e($abPhotoMain) }}"
                alt="{{ e($abPhotoMainAlt) }}"
                width="480"
                height="384"
                loading="lazy"
              >
            </div>
          </div>
          <div class="pu-about-proof">
            <p class="pu-about-proof__title">{{ $abProofTitle }}</p>
            <div class="pu-about-proof__row">
              <div class="pu-about-proof__avatars">
                @foreach(count($abAvatars) ? $abAvatars : $abDefaultAvatars as $av)
                  <img src="{{ e($av) }}" alt="">
                @endforeach
              </div>
              <span class="pu-about-proof__badge">{{ $abProofBadge }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="pu-about-copy">
          <p class="pu-about-kicker">{{ $abKicker }}</p>
          <h2 id="pu-about-heading">{{ $abHeading }}</h2>
          @if(filled($abBody))
            <p>{!! nl2br(e($abBody)) !!}</p>
          @else
            <p>
              At PropUpdate Realty, we help you discover homes and investments with clarity and confidence.
              Our team combines deep local market knowledge with a transparent process—so every decision
              you make is informed, not influenced.
            </p>
          @endif
          <div class="pu-about-stats">
            <div class="pu-about-stat">
              <strong>{{ $abStat1v }}</strong>
              <span>{{ $abStat1l }}</span>
            </div>
            <div class="pu-about-stat">
              <strong>{{ $abStat2v }}</strong>
              <span>{{ $abStat2l }}</span>
            </div>
            <div class="pu-about-stat">
              <strong>{{ $abStat3v }}</strong>
              <span>{{ $abStat3l }}</span>
            </div>
          </div>
          <a href="{{ $abBtnUrl }}" class="pu-about-btn">
            {{ $abBtnText }}
            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

@php
  $whyEyebrow = $homePage?->section('why.eyebrow') ?: "Why we're different";
  $whyTitle = $homePage?->section('why.title') ?: 'Why Choose ';
  $whyAccent = $homePage?->section('why.title_accent') ?: 'PropUpdate?';
  $whyFeatures = $homePage?->section('why.features') ?? [];
  if (! is_array($whyFeatures) || count(array_filter($whyFeatures)) === 0) {
    $whyFeatures = [
      '<strong>Curated listings only</strong> – no clutter, no noise.',
      '<strong>Transparent pricing</strong> & real market insights.',
      '<strong>Investor-focused</strong> recommendations.',
      '<strong>End-to-end support</strong> from site visits to closure.',
      '<strong>Trusted by repeat clients</strong> & referrals.',
    ];
  }
  $whyQuote = $homePage?->section('why.quote') ?: 'We work closely with serious end-users and long-term investors who value clarity over hype.';
  $whyShowcaseLabel = $homePage?->section('why.showcase_label');
  if (! filled($whyShowcaseLabel)) {
    $whyShowcaseLabel = '<i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Clarity over hype';
  }
  $whyChip = $homePage?->section('why.chip_text');
  if (! filled($whyChip)) {
    $whyChip = '<strong>4.9</strong> avg. client rating';
  }
  $whyConsult = $homePage?->section('why.consult_text') ?: "Free\nconsultation";
  $whyTelRaw = trim((string) ($homePage?->section('why.cta_tel') ?: '+917204362646'));
  $whyTelHref = preg_match('/^tel:/i', $whyTelRaw) ? $whyTelRaw : 'tel:'.preg_replace('/\s+/', '', $whyTelRaw);
  $whyWebUrl = $homePage?->section('why.website_url') ?: 'https://www.propupdate.com';
  $whyWebLabel = $homePage?->section('why.website_label') ?: 'www.propupdate.com';
@endphp
<section class="pu-why" id="why-propupdate" aria-labelledby="pu-why-heading">
  <div class="pu-why-bg-pattern" aria-hidden="true"></div>
  <div class="container position-relative">
    <div class="row align-items-stretch g-4 g-xl-5">
      <div class="col-lg-5 col-xl-5">
        <div class="pu-why-showcase">
          <div class="pu-why-showcase__shape" aria-hidden="true"></div>
          <div class="pu-why-showcase__card">
            <span class="pu-why-showcase__label">
              {!! $whyShowcaseLabel !!}
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
            <span class="pu-why-chip__text">{!! $whyChip !!}</span>
          </div>
        </div>
      </div>
      <div class="col-lg-7 col-xl-7">
        <div class="pu-why-copy">
          <p class="pu-why-eyebrow">{{ $whyEyebrow }}</p>
          <h2 id="pu-why-heading" class="pu-why-title">
            {{ $whyTitle }}<span class="pu-why-title__accent">{{ $whyAccent }}</span>
          </h2>
          <div class="pu-why-features" role="list">
            @foreach($whyFeatures as $featLine)
              @if(filled($featLine))
            <div class="pu-why-feature" role="listitem">
              <span class="pu-why-feature__icon" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
              <p>{!! $featLine !!}</p>
            </div>
              @endif
            @endforeach
          </div>
          <blockquote class="pu-why-quote">
            <span class="pu-why-quote__mark" aria-hidden="true">"</span>
            {{ $whyQuote }}
          </blockquote>
          <div class="pu-why-cta-wrap">
            @if(session('consultation_status'))
              <div class="alert alert-success py-2 px-3 mb-0 w-100" role="status">
                {{ session('consultation_status') }}
              </div>
            @endif
            <form action="{{ route('lead.consultation') }}" method="post" class="pu-why-consult-form" novalidate>
              @csrf
              <p class="pu-why-consult-form__title mb-0">{!! nl2br(e($whyConsult)) !!}</p>
              <div class="pu-why-consult-form__grid">
                <div class="pu-why-consult-field">
                  <label for="consult-name">Name</label>
                  <input id="consult-name" type="text" name="consult_name" value="{{ old('consult_name') }}" autocomplete="name" required>
                  @error('consult_name', 'consultation')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="pu-why-consult-field">
                  <label for="consult-email">Email</label>
                  <input id="consult-email" type="email" name="consult_email" value="{{ old('consult_email') }}" autocomplete="email" required>
                  @error('consult_email', 'consultation')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="pu-why-consult-field pu-why-consult-field--full">
                  <label for="consult-phone">Phone no</label>
                  <input id="consult-phone" type="text" name="consult_phone" value="{{ old('consult_phone') }}" autocomplete="tel" required>
                  @error('consult_phone', 'consultation')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="pu-why-consult-field pu-why-consult-field--full">
                  <label for="consult-message">Message</label>
                  <textarea id="consult-message" name="consult_message" rows="2" placeholder="Budget, area, timeline..." required>{{ old('consult_message') }}</textarea>
                  @error('consult_message', 'consultation')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
              </div>
              <button type="submit" class="pu-why-btn">Submit consultation</button>
            </form>
            <a href="{{ e($whyWebUrl) }}" class="pu-why-link" target="_blank" rel="noopener noreferrer">
              <span class="pu-why-link__icon" aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span>
              <span class="pu-why-link__text">{{ $whyWebLabel }}</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@include('frontend.partials.google-reviews')

@php
  $rsHeading = $homePage?->section('resale.heading') ?: 'Exclusive resale deals in North Bangalore';
  $rsImg = \App\Models\Page::mediaPublicUrl($homePage?->section('resale.hero_image_path'), $homePage?->section('resale.hero_image_url'))
    ?: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=900&q=85';
  $rsAlt = $homePage?->section('resale.hero_alt') ?: 'Modern high-rise residences in Bangalore';
  $rsDefaultCards = [
    ['icon' => 'fa-solid fa-tags', 'title' => 'Below-Market Pricing', 'text' => 'Select resale homes available at prices lower than current market rates.'],
    ['icon' => 'fa-solid fa-bolt', 'title' => 'Immediate Availability', 'text' => 'Ready or near-ready properties with faster possession and quick closure.'],
    ['icon' => 'fa-solid fa-clipboard-check', 'title' => 'Verified & Clear Titles', 'text' => 'Every resale deal is legally checked for ownership, approvals, and documentation.'],
    ['icon' => 'fa-solid fa-percent', 'title' => 'Off-Market Access', 'text' => 'Exclusive listings not publicly advertised, shared only with serious buyers.'],
  ];
  $rsCardsRaw = $homePage?->section('resale.cards') ?? [];
  $rsCards = [];
  if (is_array($rsCardsRaw)) {
    foreach ($rsCardsRaw as $c) {
      if (filled($c['title'] ?? null) || filled($c['text'] ?? null)) {
        $rsCards[] = $c;
      }
    }
  }
  if (count($rsCards) === 0) {
    $rsCards = $rsDefaultCards;
  }
@endphp
<section class="pu-resale" id="exclusive-resale" aria-labelledby="pu-resale-heading">
  <div class="pu-resale__corner pu-resale__corner--diamond" aria-hidden="true"></div>
  <div class="pu-resale__corner pu-resale__corner--waves" aria-hidden="true"></div>

  <div class="container position-relative">
    <div class="pu-resale-hero row align-items-stretch g-0">
      <div class="col-lg-6 pu-resale-hero__copy order-2 order-lg-1">
        <div class="pu-resale-hero__pattern" aria-hidden="true"></div>
        <h2 id="pu-resale-heading" class="pu-resale-hero__title">
          {{ $rsHeading }}
        </h2>
      </div>
      <div class="col-lg-6 pu-resale-hero__visual order-1 order-lg-2">
        <div class="pu-resale-hero__frame">
          <img
            src="{{ e($rsImg) }}"
            alt="{{ e($rsAlt) }}"
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
      @foreach($rsCards as $card)
        @php
          $cIcon = filled($card['icon'] ?? null) ? $card['icon'] : 'fa-solid fa-circle-check';
          $cTitle = $card['title'] ?? '';
          $cText = $card['text'] ?? '';
        @endphp
        @if(filled($cTitle) || filled($cText))
        <div class="col-6 col-lg-3">
          <div class="pu-resale-card">
            <div class="pu-resale-card__icon" aria-hidden="true">
              <i class="{{ e($cIcon) }}"></i>
            </div>
            @if(filled($cTitle))
              <h3 class="pu-resale-card__title">{{ $cTitle }}</h3>
            @endif
            @if(filled($cText))
              <p class="pu-resale-card__text">{{ $cText }}</p>
            @endif
          </div>
        </div>
        @endif
      @endforeach
    </div>
    <p class="text-center mt-4 pt-1 mb-0">
      <a href="{{ route('exclusive-resale.index') }}" class="pu-blog-card__more">Exclusive resale listing cards →</a>
    </p>
  </div>
</section>

@if(isset($services) && $services->isNotEmpty())
<section class="pu-services" id="services" aria-labelledby="pu-services-heading">
  <div class="container">
    <p class="pu-services__kicker">{{ $homePage?->section('services.kicker') ?: 'What we do' }}</p>
    <h2 id="pu-services-heading" class="pu-services__title">{{ $homePage?->section('services.title') ?: 'Our services' }}</h2>
    <p class="pu-services__lead">{{ $homePage?->section('services.lead') ?: 'Practical support across the property journey — from first brief to documentation.' }}</p>
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

@php
  $lnTitle = $homePage?->section('launches.title') ?: 'Explore new property launches across Bangalore';
  $lnLead = $homePage?->section('launches.lead') ?: 'Get early visibility on upcoming projects—pricing bands, floor plans, and launch offers—before they go wide to the market.';
  $lnSub = $homePage?->section('launches.sub') ?: 'Benefits of booking in the pre-launch stage';
  $lnBenefitsRaw = $homePage?->section('launches.benefits') ?? [];
  $lnDefaultBenefits = [
    ['icon' => 'fa-solid fa-chart-line', 'title' => 'Lowest entry price', 'text' => 'Early-bird tiers typically offer the sharpest pricing before escalations at launch.'],
    ['icon' => 'fa-solid fa-layer-group', 'title' => 'Best choice of inventory', 'text' => 'Pick preferred floors, views, and unit types while the full stack is still available.'],
    ['icon' => 'fa-solid fa-rocket', 'title' => 'Higher appreciation potential', 'text' => 'Buy closer to developer pricing and benefit as the project matures and the micro-market grows.'],
    ['icon' => 'fa-solid fa-shield-halved', 'title' => 'RERA, launch & construction clarity', 'text' => 'We help you track approvals, launch milestones, construction progress, and realistic possession timelines.'],
  ];
  $lnBenefits = [];
  if (is_array($lnBenefitsRaw)) {
    foreach ($lnBenefitsRaw as $b) {
      if (filled($b['title'] ?? null) || filled($b['text'] ?? null)) {
        $lnBenefits[] = $b;
      }
    }
  }
  if (count($lnBenefits) === 0) {
    $lnBenefits = $lnDefaultBenefits;
  }
  $lnCtaLine = $homePage?->section('launches.cta_line') ?: 'Pre-launch is where informed buyers create maximum value: <strong>right pricing, right unit, right timing.</strong>';
  $lnAside = $homePage?->section('launches.aside_intro') ?: 'Pre-register to get early access to <strong>pricing, floor plans</strong> and <strong>exclusive launch offers</strong>.';
  $lnFormTitle = $homePage?->section('launches.form_title') ?: 'Contact form';
  $lnFormNote = $homePage?->section('launches.form_note') ?: 'We respect your privacy. Your details are used only to respond to this enquiry—never for spam.';
@endphp
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
            {{ $lnTitle }}
          </h2>
          <p class="pu-launches__lead">
            {{ $lnLead }}
          </p>
          <h3 class="pu-launches__sub">{{ $lnSub }}</h3>
          <ul class="pu-launches-benefits">
            @foreach($lnBenefits as $ben)
              @php
                $bIcon = filled($ben['icon'] ?? null) ? $ben['icon'] : 'fa-solid fa-circle-check';
                $bTitle = $ben['title'] ?? '';
                $bText = $ben['text'] ?? '';
              @endphp
              @if(filled($bTitle) || filled($bText))
            <li>
              <span class="pu-launches-benefits__icon" aria-hidden="true"><i class="{{ e($bIcon) }}"></i></span>
              <div>
                @if(filled($bTitle))
                  <strong>{{ $bTitle }}</strong>
                @endif
                @if(filled($bText))
                  <span>{{ $bText }}</span>
                @endif
              </div>
            </li>
              @endif
            @endforeach
          </ul>
          <p class="pu-launches__cta-line">
            {!! $lnCtaLine !!}
          </p>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="pu-launches-aside">
          <p class="pu-launches-aside__intro">
            {!! $lnAside !!}
          </p>
          <div class="pu-launches-form-card">
            <h3 class="pu-launches-form-card__title">{{ $lnFormTitle }}</h3>
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
                <label for="launch-phone">Phone no</label>
                <input type="tel" id="launch-phone" name="phone" value="{{ old('phone') }}" placeholder="+91 ..." autocomplete="tel" required>
              </div>
              <div class="pu-launches-field">
                <label for="launch-message">Leave a message</label>
                <textarea id="launch-message" name="message" rows="4" placeholder="Project preferences, budget range, timeline…" required>{{ old('message') }}</textarea>
              </div>
              <button type="submit" class="pu-launches-submit">Submit</button>
              <p class="pu-launches-form__note">
                {!! $lnFormNote !!}
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('frontend/assets/js/hero-search.js') }}" defer></script>
@endpush
