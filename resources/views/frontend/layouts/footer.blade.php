<footer class="pu-footer">
  <div class="pu-footer__accent-bar" aria-hidden="true"></div>

  <div class="pu-footer__main">
    <div class="container">
      <div class="row gy-5 pt-5 pb-4">
        <div class="col-lg-4 col-md-6">
          <div class="pu-footer-brand">
            <a href="{{ url('/') }}" class="pu-footer-brand__link">
              <span class="pu-footer-brand__icon" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" width="44" height="44">
                  <path d="M8 20L24 8L40 20V40H30V28H18V40H8V20Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                  <path d="M14 22L24 14L34 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.85"/>
                  <path d="M20 40V30H28V40" stroke="currentColor" stroke-width="1.5" opacity="0.85"/>
                </svg>
              </span>
              <span class="pu-footer-brand__text">
                <span class="pu-footer-brand__name">PROPUPDATE</span>
                <span class="pu-footer-brand__tag">Update your property search</span>
              </span>
            </a>
            <p class="pu-footer-brand__desc">
              Decisions informed, not influenced. Curated resale and new launches across Bangalore with transparent guidance for homeowners and investors.
            </p>
            <ul class="pu-footer-social">
              <li><a href="#" class="pu-footer-social__link" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
              <li><a href="#" class="pu-footer-social__link" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a></li>
              <li><a href="#" class="pu-footer-social__link" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a></li>
              <li><a href="#" class="pu-footer-social__link" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a></li>
            </ul>
          </div>
        </div>

        <div class="col-lg-2 col-md-6">
          <h3 class="pu-footer-heading">Quick links</h3>
          <ul class="pu-footer-links">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ url('/#about') }}">About us</a></li>
            <li><a href="{{ url('/#exclusive-resale') }}">Exclusive resale</a></li>
            <li><a href="{{ url('/#why-propupdate') }}">Why PropUpdate</a></li>
            <li><a href="#">Properties</a></li>
            <li><a href="#">New launches</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6">
          <h3 class="pu-footer-heading">Contact</h3>
          <ul class="pu-footer-contact">
            <li>
              <span class="pu-footer-contact__icon" aria-hidden="true"><i class="fa-solid fa-phone"></i></span>
              <a href="tel:+917204362646">7204362646</a>
            </li>
            <li>
              <span class="pu-footer-contact__icon" aria-hidden="true"><i class="fa-solid fa-envelope"></i></span>
              <a href="mailto:info@propupdate.com">info@propupdate.com</a>
            </li>
            <li>
              <span class="pu-footer-contact__icon" aria-hidden="true"><i class="fa-solid fa-globe"></i></span>
              <a href="https://www.propupdate.com" target="_blank" rel="noopener noreferrer">www.propupdate.com</a>
            </li>
            <li>
              <span class="pu-footer-contact__icon" aria-hidden="true"><i class="fa-solid fa-location-dot"></i></span>
              <span class="pu-footer-contact__text">North Bangalore, Karnataka, India</span>
            </li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6">
          <h3 class="pu-footer-heading">Gallery</h3>
          <p class="pu-footer-gallery__hint">Follow us for new listings</p>
          <div class="pu-footer-gallery">
            <a href="#" class="pu-footer-gallery__item" aria-label="Property 1" style="background-image: url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&amp;fit=crop&amp;w=200&amp;q=80');"></a>
            <a href="#" class="pu-footer-gallery__item" aria-label="Property 2" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&amp;fit=crop&amp;w=200&amp;q=80');"></a>
            <a href="#" class="pu-footer-gallery__item" aria-label="Property 3" style="background-image: url('https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&amp;fit=crop&amp;w=200&amp;q=80');"></a>
            <a href="#" class="pu-footer-gallery__item" aria-label="Property 4" style="background-image: url('https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&amp;fit=crop&amp;w=200&amp;q=80');"></a>
            <a href="#" class="pu-footer-gallery__item" aria-label="Property 5" style="background-image: url('https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&amp;fit=crop&amp;w=200&amp;q=80');"></a>
            <a href="#" class="pu-footer-gallery__item" aria-label="Property 6" style="background-image: url('https://images.unsplash.com/photo-1568605114967-8130f3a36994?auto=format&amp;fit=crop&amp;w=200&amp;q=80');"></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="pu-footer__bottom">
    <div class="container">
      <p class="pu-footer__copy">
        &copy; {{ date('Y') }} PropUpdate Realty. All rights reserved.
      </p>
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
