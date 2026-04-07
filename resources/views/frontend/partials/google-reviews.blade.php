@php
  /** @var array|null $googleReviews */
  $gr = $googleReviews ?? null;
  $grVisible = is_array($gr)
    && (count($gr['reviews']) > 0 || ($gr['rating'] ?? null) !== null);
  $grRating = is_array($gr) ? ($gr['rating'] ?? null) : null;
  $grCount = is_array($gr) ? ($gr['user_rating_count'] ?? null) : null;
@endphp
@if($grVisible)
<section class="pu-greviews" aria-labelledby="pu-greviews-heading" data-pu-greviews>
  <div class="pu-greviews__bg" aria-hidden="true"></div>
  <div class="container position-relative">
    <div class="pu-greviews__layout row g-4 g-xl-5 align-items-stretch">
      <div class="col-lg-4 col-xl-3">
        <div class="pu-greviews__summary">
          @if($siteSettings?->logoUrl())
            <div class="pu-greviews__logo-wrap">
              <img src="{{ $siteSettings->logoUrl() }}" alt="" class="pu-greviews__logo" width="56" height="56" loading="lazy" decoding="async">
            </div>
          @endif
          <h2 id="pu-greviews-heading" class="pu-greviews__biz">{{ $gr['display_name'] }}</h2>
          @if($grRating !== null)
            <div class="pu-greviews__stars-row" aria-label="Rated {{ number_format($grRating, 1) }} out of 5">
              @for($i = 1; $i <= 5; $i++)
                <span class="pu-greviews__star{{ $i <= round($grRating) ? ' is-on' : '' }}" aria-hidden="true">★</span>
              @endfor
            </div>
          @endif
          @if($grCount !== null && $grCount > 0)
            <p class="pu-greviews__count">{{ number_format($grCount) }} Google reviews</p>
          @endif
          <a href="{{ e($gr['write_review_url']) }}" class="pu-greviews__write-btn" target="_blank" rel="noopener noreferrer">Write a review</a>
          @if(filled($gr['maps_url'] ?? null))
            <p class="mb-0 mt-3">
              <a href="{{ e($gr['maps_url']) }}" class="pu-greviews__maps-link" target="_blank" rel="noopener noreferrer">View on Google Maps</a>
            </p>
          @endif
        </div>
      </div>
      <div class="col-lg-8 col-xl-9">
        <div class="pu-greviews__carousel-wrap">
          @if(count($gr['reviews']) > 0)
            <button type="button" class="pu-greviews__nav pu-greviews__nav--prev" data-pu-greviews-prev aria-label="Previous reviews">
              <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
            </button>
            <div class="pu-greviews__viewport">
              <div class="pu-greviews__track" data-pu-greviews-track>
                @foreach($gr['reviews'] as $idx => $rev)
                  @php
                    $rRating = (float) ($rev['rating'] ?? 0);
                    $long = mb_strlen((string) ($rev['text'] ?? '')) > 220;
                    $initial = mb_strtoupper(mb_substr((string) ($rev['author'] ?? '?'), 0, 1));
                  @endphp
                  <article class="pu-greviews__card">
                    <header class="pu-greviews__card-head">
                      @if(filled($rev['photo'] ?? null))
                        <img src="{{ e($rev['photo']) }}" alt="" class="pu-greviews__avatar" width="44" height="44" loading="lazy" decoding="async" referrerpolicy="no-referrer">
                      @else
                        <span class="pu-greviews__avatar pu-greviews__avatar--letter" aria-hidden="true">{{ $initial }}</span>
                      @endif
                      <div class="pu-greviews__card-meta">
                        @if(filled($rev['author_url'] ?? null))
                          <a href="{{ e($rev['author_url']) }}" class="pu-greviews__author" target="_blank" rel="noopener noreferrer">{{ $rev['author'] }}</a>
                        @else
                          <span class="pu-greviews__author">{{ $rev['author'] }}</span>
                        @endif
                        @if(filled($rev['relative_time'] ?? null))
                          <span class="pu-greviews__when">{{ $rev['relative_time'] }}</span>
                        @endif
                      </div>
                      <span class="pu-greviews__g-icon" aria-label="Google"><i class="fa-brands fa-google" aria-hidden="true"></i></span>
                    </header>
                    <div class="pu-greviews__card-rating">
                      @for($i = 1; $i <= 5; $i++)
                        <span class="pu-greviews__mini-star{{ $i <= round($rRating) ? ' is-on' : '' }}" aria-hidden="true">★</span>
                      @endfor
                      <span class="pu-greviews__verified" title="From Google Maps"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                    </div>
                    <div class="pu-greviews__text-wrap">
                      <p class="pu-greviews__text{{ $long ? ' is-clamped' : '' }}" data-pu-greviews-text="{{ $idx }}">{{ $rev['text'] }}</p>
                      @if($long)
                        <button type="button" class="pu-greviews__more" data-pu-greviews-more="{{ $idx }}">Read more</button>
                      @endif
                    </div>
                  </article>
                @endforeach
              </div>
            </div>
            <button type="button" class="pu-greviews__nav pu-greviews__nav--next" data-pu-greviews-next aria-label="Next reviews">
              <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            </button>
          @else
            <p class="pu-greviews__empty mb-0">See all reviews and the latest ratings on Google.</p>
            @if(filled($gr['maps_url'] ?? null))
              <a href="{{ e($gr['maps_url']) }}" class="btn btn-outline-dark btn-sm mt-2" target="_blank" rel="noopener noreferrer">Open Google Maps</a>
            @endif
          @endif
        </div>
        <p class="pu-greviews__legal small text-muted mb-0 mt-3">Reviews and star rating are provided by Google and update periodically.</p>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script src="{{ asset('frontend/assets/js/google-reviews-carousel.js') }}" defer></script>
@endpush
@endif
