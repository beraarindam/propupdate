@php
  use App\Models\ClientReview;
  use App\Models\SiteSetting;

  $sectionTitle = $title ?? 'What clients say on Google';
  $sectionLead = $lead ?? 'Real reviews from buyers, sellers, and investors who worked with PropUpdate across Bangalore.';
  $reviews = ClientReview::publishedForDisplay();
  $viewAllUrl = ($siteSettings ?? SiteSetting::current())->googleReviewsAllUrl()
    ?: 'https://share.google/rZWI91ux1RD6R9t87';
@endphp

@if($reviews->isNotEmpty())
<section class="pu-ti-reviews" aria-labelledby="pu-ti-reviews-heading" data-pu-greviews>
  <div class="container">
    <div class="pu-ti-reviews__shell">
      <div class="pu-ti-reviews__head">
        <span class="pu-ti-reviews__eyebrow">Google Reviews</span>
        <h2 id="pu-ti-reviews-heading" class="pu-ti-reviews__title">{{ $sectionTitle }}</h2>
        <p class="pu-ti-reviews__lead mb-0">{{ $sectionLead }}</p>
      </div>

      <div class="pu-ti-reviews__widget">
        <div class="pu-greviews__carousel-wrap">
          <button type="button" class="pu-greviews__nav pu-greviews__nav--prev" data-pu-greviews-prev aria-label="Previous reviews">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
          </button>
          <div class="pu-greviews__viewport">
            <div class="pu-greviews__track" data-pu-greviews-track>
              @foreach($reviews as $idx => $review)
                @php
                  $rRating = $review->ratingStars();
                  $long = mb_strlen((string) $review->content) > 220;
                  $initial = mb_strtoupper(mb_substr((string) $review->reviewer_name, 0, 1));
                @endphp
                <article class="pu-greviews__card">
                  <header class="pu-greviews__card-head">
                    @if($review->avatarUrl())
                      <img src="{{ $review->avatarUrl() }}" alt="" class="pu-greviews__avatar" width="44" height="44" loading="lazy" decoding="async">
                    @else
                      <span class="pu-greviews__avatar pu-greviews__avatar--letter" aria-hidden="true">{{ $initial }}</span>
                    @endif
                    <div class="pu-greviews__card-meta">
                      <span class="pu-greviews__author">{{ $review->reviewer_name }}</span>
                    </div>
                    <span class="pu-greviews__g-icon" aria-label="Google"><i class="fa-brands fa-google" aria-hidden="true"></i></span>
                  </header>
                  <div class="pu-greviews__card-rating" aria-label="Rated {{ $rRating }} out of 5">
                    @for($i = 1; $i <= 5; $i++)
                      <span class="pu-greviews__mini-star{{ $i <= $rRating ? ' is-on' : '' }}" aria-hidden="true">★</span>
                    @endfor
                    <span class="pu-greviews__verified" title="Google review"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></span>
                  </div>
                  <div class="pu-greviews__text-wrap">
                    <p class="pu-greviews__text{{ $long ? ' is-clamped' : '' }}" data-pu-greviews-text="{{ $idx }}">{{ $review->content }}</p>
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
        </div>
      </div>

      <div class="pu-ti-reviews__foot text-center">
        <a href="{{ $viewAllUrl }}" class="pu-ti-reviews__all-btn" target="_blank" rel="noopener noreferrer">
          See All Review <i class="fa-solid fa-arrow-right ms-2" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script src="{{ asset('frontend/assets/js/google-reviews-carousel.js') }}" defer></script>
@endpush
@endif
