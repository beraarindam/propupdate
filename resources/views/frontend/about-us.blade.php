@extends('frontend.layouts.master')
@section('title', $page?->browserTitle() ?? 'About us')

@php
	use App\Models\Page;

	$introImg = Page::mediaPublicUrl($page?->aboutPage('intro.image_path'), $page?->aboutPage('intro.image_url'))
		?: 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&fit=crop&w=900&q=80';
	$introAlt = filled($page?->aboutPage('intro.image_alt'))
		? $page->aboutPage('intro.image_alt')
		: 'Modern home representing PropUpdate listings';

	$founderImg = Page::mediaPublicUrl($page?->aboutPage('founder.image_path'), $page?->aboutPage('founder.image_url'))
		?: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=900&q=80';
	$founderAlt = filled($page?->aboutPage('founder.image_alt'))
		? $page->aboutPage('founder.image_alt')
		: 'Professional portrait representing PropUpdate Realty leadership';

	$defaultValueItems = [
		['icon' => 'fa-solid fa-shield-halved', 'title' => 'Transparency', 'text' => 'Pricing history, loading factors, approvals, and realistic timelines — shared early, in plain language.'],
		['icon' => 'fa-solid fa-bullseye', 'title' => 'Fit over hype', 'text' => 'We match you to micro-markets and product types that suit your goals — not what is easiest to sell.'],
		['icon' => 'fa-solid fa-handshake', 'title' => 'End-to-end care', 'text' => 'From first site visit through documentation and handover — one accountable team.'],
	];
	$rawValueItems = $page?->aboutPage('values.items');
	if (! is_array($rawValueItems)) {
		$rawValueItems = [];
	}
	$rawValueItems = array_values(array_pad($rawValueItems, 3, []));
	$valueRows = [];
	for ($vi = 0; $vi < 3; $vi++) {
		$row = $rawValueItems[$vi] ?? [];
		$def = $defaultValueItems[$vi];
		$valueRows[] = [
			'icon' => filled($row['icon'] ?? '') ? trim((string) $row['icon']) : $def['icon'],
			'title' => filled($row['title'] ?? '') ? trim((string) $row['title']) : $def['title'],
			'text' => filled($row['text'] ?? '') ? trim((string) $row['text']) : $def['text'],
		];
	}

	$ctaTelRaw = $page?->aboutPage('stats.cta_tel') ?: '+917204362646';
	$ctaTelHref = 'tel:' . preg_replace('/\s+/', '', (string) $ctaTelRaw);
@endphp

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $page?->banner_title ?? 'About PropUpdate',
  'crumbCurrent' => $page?->name ?? 'About us',
  'lead' => $page?->banner_lead ?? 'Where every property decision is <strong>informed</strong>, not influenced — serving serious buyers and investors across Bangalore.',
  'bgImage' => $page?->bannerBackgroundUrl() ?? 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1920&q=80',
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
    <div class="row align-items-center g-4 g-lg-5">
      <div class="col-lg-5">
        <div class="pu-about-page-visual">
          <div class="pu-about-page-visual__blob" aria-hidden="true"></div>
          <div class="pu-about-page-visual__frame">
            <img
              src="{{ $introImg }}"
              alt="{{ $introAlt }}"
              width="560"
              height="420"
              loading="lazy"
            >
          </div>
          <div class="pu-about-page-badge">
            <span class="pu-about-page-badge__num">{{ $page?->aboutPage('intro.badge_num') ?: '10+' }}</span>
            <span class="pu-about-page-badge__text">{{ $page?->aboutPage('intro.badge_text') ?: 'Years collective experience' }}</span>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <p class="pu-about-page-kicker">{{ $page?->aboutPage('intro.kicker') ?: 'Who we are' }}</p>
        <h2 class="pu-about-page-h2">{{ $page?->aboutPage('intro.h2') ?: 'Built for buyers who read the fine print' }}</h2>
        <p class="pu-about-page-text">
          {!! $page?->aboutPage('intro.paragraph_1') ?: 'PropUpdate Realty is a Bangalore-focused practice for <strong>resale</strong>, <strong>new launches</strong>, and <strong>investment-grade inventory</strong>. We combine market data, legal diligence, and straight answers — so you never discover surprises after you commit.' !!}
        </p>
        <p class="pu-about-page-text">
          {!! $page?->aboutPage('intro.paragraph_2') ?: 'Whether you are upgrading your home or building a portfolio, we act as your research partner, not a billboard.' !!}
        </p>
        @php
          $introCtaText = $page?->aboutPage('intro.cta_text') ?: 'Get launch access';
          $introCtaHref = filled($page?->aboutPage('intro.cta_href'))
            ? Page::resolveHref($page->aboutPage('intro.cta_href'))
            : route('home') . '#pre-register';
        @endphp
        <a href="{{ $introCtaHref }}" class="pu-about-page-cta">{{ $introCtaText }}</a>
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
          <p class="pu-founder__eyebrow" id="founder-heading">{{ $page?->aboutPage('founder.eyebrow') ?: "Founder's note" }}</p>
          <h2 class="pu-founder__name">{{ $page?->aboutPage('founder.name') ?: 'Roshan Kumar' }}</h2>
          <p class="pu-founder__role">{{ $page?->aboutPage('founder.role') ?: 'Founder – PropUpdate Realty' }}</p>
          <blockquote class="pu-founder__quote">
            <span class="pu-founder__quote-mark" aria-hidden="true">“</span>
            {!! $page?->aboutPage('founder.quote') ?: 'Real estate decisions should be driven by insight, not urgency.' !!}
          </blockquote>
          <p class="pu-founder__body">
            {!! $page?->aboutPage('founder.body_1') ?: 'At <strong>PropUpdate Realty</strong>, my goal is to help clients buy the right property, at the right time, for the right reason.' !!}
          </p>
          <p class="pu-founder__body">
            {!! $page?->aboutPage('founder.body_2') ?: 'With hands-on market experience in Bangalore real estate, <strong>Roshan</strong> works personally with clients to identify properties that deliver lifestyle value, appreciation, and long-term security.' !!}
          </p>
        </div>
      </div>
      <div class="col-lg-6 order-1 order-lg-2">
        <div class="pu-founder__visual">
          <div class="pu-founder__visual-glow" aria-hidden="true"></div>
          <img
            class="pu-founder__photo pu-founder__photo--photo"
            src="{{ $founderImg }}"
            alt="{{ $founderAlt }}"
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
      <p class="pu-about-page-kicker">{{ $page?->aboutPage('values.kicker') ?: 'What we stand for' }}</p>
      <h2 class="pu-about-page-h2 pu-about-page-h2--center">{{ $page?->aboutPage('values.h2') ?: 'Three pillars' }}</h2>
    </div>
    <div class="row g-4">
      @foreach($valueRows as $vr)
        <div class="col-md-4">
          <div class="pu-value-card">
            <span class="pu-value-card__icon" aria-hidden="true"><i class="{{ e($vr['icon']) }}"></i></span>
            <h3>{{ $vr['title'] }}</h3>
            <p>{{ $vr['text'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="pu-about-page-stats">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-6 col-lg-3">
        <div class="pu-stat-block">
          <strong class="pu-stat-block__num">{{ $page?->aboutPage('stats.stat1_num') ?: '10K+' }}</strong>
          <span class="pu-stat-block__label">{!! $page?->aboutPage('stats.stat1_label') ?: 'Homes sold <br><em>(ecosystem)</em>' !!}</span>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="pu-stat-block">
          <strong class="pu-stat-block__num">{{ $page?->aboutPage('stats.stat2_num') ?: '9K+' }}</strong>
          <span class="pu-stat-block__label">{!! $page?->aboutPage('stats.stat2_label') ?: 'Happy clients' !!}</span>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="pu-stat-block">
          <strong class="pu-stat-block__num">{{ $page?->aboutPage('stats.stat3_num') ?: '98%' }}</strong>
          <span class="pu-stat-block__label">{!! $page?->aboutPage('stats.stat3_label') ?: 'Satisfaction focus' !!}</span>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="pu-stat-block pu-stat-block--accent">
          <span class="pu-stat-block__cta-label">{{ $page?->aboutPage('stats.cta_label') ?: 'Talk to us' }}</span>
          <a href="{{ $ctaTelHref }}" class="pu-stat-block__tel">{{ $ctaTelRaw }}</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
