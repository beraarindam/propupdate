<footer class="pu-footer">
  <div class="pu-footer__accent-bar" aria-hidden="true"></div>

  <div class="pu-footer__main">
    <div class="container">
      <div class="row gy-5 pt-5 pb-4">
        <div class="col-lg-4 col-md-6">
          <div class="pu-footer-brand">
            <a href="{{ url('/') }}" class="pu-footer-brand__link">
              <span class="pu-footer-brand__icon" aria-hidden="true">
                @if($siteSettings?->logoUrl())
                  <img src="{{ $siteSettings->logoUrl() }}" alt="" width="44" height="44" style="object-fit:contain;">
                @else
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" width="44" height="44">
                  <path d="M8 20L24 8L40 20V40H30V28H18V40H8V20Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M14 22L24 14L34 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.85"/>
                  <path d="M20 40V30H28V40" stroke="currentColor" stroke-width="1.5" opacity="0.85"/>
                </svg>
                @endif
              </span>
              <span class="pu-footer-brand__text">
                <span class="pu-footer-brand__name">{{ $siteSettings?->site_name ?: 'PropUpdate' }}</span>
                <span class="pu-footer-brand__tag">{{ $siteSettings?->tagline ?: 'Update your property search' }}</span>
              </span>
            </a>
            <p class="pu-footer-brand__desc">
              {{ $siteSettings?->footer_text ?? 'Decisions informed, not influenced. Curated resale and new launches across Bangalore with transparent guidance for homeowners and investors.' }}
            </p>
            <ul class="pu-footer-social">
              <li><a href="{{ $siteSettings?->facebook_url ?: '#' }}" class="pu-footer-social__link" aria-label="Facebook" rel="noopener noreferrer" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
              <li><a href="{{ $siteSettings?->instagram_url ?: '#' }}" class="pu-footer-social__link" aria-label="Instagram" rel="noopener noreferrer" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
              <li><a href="{{ $siteSettings?->youtube_url ?: '#' }}" class="pu-footer-social__link" aria-label="YouTube" rel="noopener noreferrer" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
              <li><a href="{{ $siteSettings?->linkedin_url ?: '#' }}" class="pu-footer-social__link" aria-label="LinkedIn" rel="noopener noreferrer" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
            </ul>
          </div>
        </div>

        <div class="col-lg-2 col-md-6">
          <h3 class="pu-footer-heading">Quick links</h3>
          <ul class="pu-footer-links">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('pages.about') }}">About us</a></li>
            <li><a href="{{ route('home') }}#services">Services</a></li>
            <li><a href="{{ route('blog.index') }}">Blog</a></li>
            <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
            <li><a href="{{ route('pages.contact') }}">Contact us</a></li>
            <li><a href="{{ route('exclusive-resale.index') }}">Exclusive resale</a></li>
            <li><a href="{{ route('properties.index') }}">Properties</a></li>
            <li><a href="{{ route('projects.index') }}">Projects</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6">
          <h3 class="pu-footer-heading">Contact</h3>
          <ul class="pu-footer-contact">
            <li>
              <span class="pu-footer-contact__icon" aria-hidden="true"><i class="fa-solid fa-phone"></i></span>
              <a href="{{ $siteSettings?->telHref() ?? 'tel:+917204362646' }}">{{ $siteSettings?->phone ?? '7204362646' }}</a>
            </li>
            <li>
              <span class="pu-footer-contact__icon" aria-hidden="true"><i class="fa-solid fa-envelope"></i></span>
              <a href="{{ $siteSettings?->mailtoHref() ?? 'mailto:info@propupdate.com' }}">{{ $siteSettings?->email ?? 'info@propupdate.com' }}</a>
            </li>
            <li>
              <span class="pu-footer-contact__icon" aria-hidden="true"><i class="fa-solid fa-globe"></i></span>
              @php
                $footerWeb = $siteSettings?->website_url ?? 'https://www.propupdate.com';
                $footerWebLabel = parse_url($footerWeb, PHP_URL_HOST) ?? preg_replace('#^https?://#i', '', $footerWeb);
              @endphp
              <a href="{{ $footerWeb }}" target="_blank" rel="noopener noreferrer">{{ $footerWebLabel }}</a>
            </li>
            <li>
              <span class="pu-footer-contact__icon" aria-hidden="true"><i class="fa-solid fa-location-dot"></i></span>
              <span class="pu-footer-contact__text">{{ $siteSettings?->address ?? 'North Bangalore, Karnataka, India' }}</span>
            </li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6">
          <h3 class="pu-footer-heading">Award</h3>
          <p class="pu-footer-gallery__hint">Latest from our awards</p>
          <div class="pu-footer-gallery">
            @foreach(($footerAwardItems ?? []) as $fg)
              <a href="{{ $fg['href'] }}" class="pu-footer-gallery__item" aria-label="{{ e($fg['label']) }}" style="background-image: url('{{ e($fg['image_url']) }}');"></a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="pu-footer__bottom">
    <div class="container">
      <div class="pu-footer__bottom-row">
        <p class="pu-footer__copy">
          &copy; {{ date('Y') }} PropUpdate Realty. All rights reserved.
        </p>
        <nav class="pu-footer__legal-nav" aria-label="Legal">
          <a href="{{ route('pages.contact') }}">Contact</a>
          <span class="pu-footer__legal-sep" aria-hidden="true">·</span>
          <a href="{{ route('pages.privacy') }}">Privacy</a>
          <span class="pu-footer__legal-sep" aria-hidden="true">·</span>
          <a href="{{ route('pages.terms') }}">Terms</a>
        </nav>
      </div>
    </div>
  </div>
</footer>

<!--===== JS SCRIPT LINK =======-->
  <script src="{{asset('frontend/assets/js/plugins/bootstrap.min.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/fontawesome.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/aos.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/counter.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/gsap.min.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/ScrollTrigger.min.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/Splitetext.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/sidebar.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/swiper-slider.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/magnific-popup.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/mobilemenu.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/owlcarousel.min.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/nice-select.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/waypoints.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/slick-slider.js')}}"></script>
  <script src="{{asset('frontend/assets/js/plugins/circle-progress.js')}}"></script>
  <script src="{{asset('frontend/assets/js/main.js')}}"></script>
