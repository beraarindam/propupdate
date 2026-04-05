@extends('frontend.layouts.master')

@section('title', $page?->browserTitle() ?? 'Gallery')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $page?->banner_title ?? $page?->name ?? 'Gallery',
  'crumbCurrent' => $page?->name ?? 'Gallery',
  'lead' => $page?->banner_lead ?? ($page?->meta_description ? strip_tags($page->meta_description) : 'A curated look at projects, spaces, and places we work with.'),
  'bgImage' => $page?->bannerBackgroundUrl() ?? 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1920&q=80',
])

<section class="pu-site-gallery py-4 py-lg-5">
  <div class="container">
    @if($items->isEmpty())
      <p class="text-center text-muted py-5 mb-0">Gallery coming soon.</p>
    @else
      <div class="row g-4 g-lg-4 pu-site-gallery__grid">
        @foreach($items as $item)
          @php($img = $item->imagePublicUrl())
          @continue($img === null)
          <div class="col-6 col-md-4 col-lg-3">
            <article class="pu-site-gallery__card">
              <a href="{{ $img }}" class="pu-site-gallery__link pu-site-gallery-mfp" title="{{ e($item->title ?: 'Gallery image') }}">
                <div class="ratio ratio-4x3 pu-site-gallery__ratio">
                  <img src="{{ $img }}" alt="{{ e($item->title ?: 'Gallery image') }}" class="pu-site-gallery__img" loading="lazy" width="400" height="300">
                </div>
                <span class="pu-site-gallery__zoom" aria-hidden="true"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
              </a>
              @if($item->title || $item->caption)
                <div class="pu-site-gallery__meta">
                  @if($item->title)
                    <h2 class="pu-site-gallery__title">{{ $item->title }}</h2>
                  @endif
                  @if($item->caption)
                    <p class="pu-site-gallery__caption mb-0">{{ $item->caption }}</p>
                  @endif
                </div>
              @endif
            </article>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof jQuery === 'undefined' || !jQuery.fn.magnificPopup) return;
  var $g = jQuery('.pu-site-gallery__grid');
  if (!$g.length) return;
  $g.magnificPopup({
    delegate: 'a.pu-site-gallery-mfp',
    type: 'image',
    gallery: { enabled: true, navigateByImgClick: true, preload: [0, 1] },
    image: { titleSrc: function (item) { return item.el.attr('title') || ''; } },
    mainClass: 'mfp-img-mobile',
    removalDelay: 200
  });
});
</script>
@endpush
