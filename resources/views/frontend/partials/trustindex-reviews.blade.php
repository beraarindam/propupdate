@php
  $trustindexWidgetId = '51d81de69bc9767673768ee6470';
  $trustindexTitle = $title ?? 'What clients say on Google';
  $trustindexLead = $lead ?? 'Real reviews from buyers, sellers, and investors who worked with PropUpdate across Bangalore.';
@endphp

<section class="pu-ti-reviews" aria-labelledby="pu-ti-reviews-heading">
  <div class="container">
    <div class="pu-ti-reviews__shell">
      <div class="pu-ti-reviews__head">
        <span class="pu-ti-reviews__eyebrow">Google Reviews</span>
        <h2 id="pu-ti-reviews-heading" class="pu-ti-reviews__title">{{ $trustindexTitle }}</h2>
        <p class="pu-ti-reviews__lead mb-0">{{ $trustindexLead }}</p>
      </div>

      <div class="pu-ti-reviews__widget">
        <div src="https://cdn.trustindex.io/loader.js?{{ $trustindexWidgetId }}">[trustindex data-widget-id={{ $trustindexWidgetId }}]</div>
      </div>
    </div>
  </div>
</section>

@once
  @push('scripts')
    <script defer async src="https://cdn.trustindex.io/loader.js?51d81de69bc9767673768ee6470"></script>
  @endpush
@endonce
