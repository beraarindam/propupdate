<!--===== PROPUPDATE HEADER =======-->
<div class="pu-header-wrap">
  <div class="pu-preheader">
    <div class="pu-preheader-inner">
      <span class="pu-preheader-call">Call us at <a href="{{ $siteSettings?->telHref() ?? 'tel:+917204362646' }}">{{ $siteSettings?->phone ?? '7204362646' }}</a></span>
      <ul class="pu-preheader-social">
        <li><a href="{{ $siteSettings?->facebook_url ?: '#' }}" class="pi-fb" aria-label="Facebook" @if(!($siteSettings?->facebook_url)) aria-disabled="true" @endif rel="noopener noreferrer" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
        <li><a href="{{ $siteSettings?->instagram_url ?: '#' }}" class="pi-ig" aria-label="Instagram" @if(!($siteSettings?->instagram_url)) aria-disabled="true" @endif rel="noopener noreferrer" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
        <li><a href="{{ $siteSettings?->youtube_url ?: '#' }}" class="pi-yt" aria-label="YouTube" @if(!($siteSettings?->youtube_url)) aria-disabled="true" @endif rel="noopener noreferrer" target="_blank"><i class="fa-brands fa-youtube"></i></a></li>
        <li><a href="{{ $siteSettings?->linkedin_url ?: '#' }}" class="pi-li" aria-label="LinkedIn" @if(!($siteSettings?->linkedin_url)) aria-disabled="true" @endif rel="noopener noreferrer" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
        <li><a href="{{ $siteSettings?->twitter_url ?: '#' }}" class="pi-x" aria-label="X" @if(!($siteSettings?->twitter_url)) aria-disabled="true" @endif rel="noopener noreferrer" target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
      </ul>
    </div>
  </div>

  <header class="pu-main-header">
    <div class="pu-main-header-inner">
      <a href="{{ url('/') }}" class="pu-brand">
        <span class="pu-brand-icon" aria-hidden="true">
          @if($siteSettings?->logoUrl())
            <img src="{{ $siteSettings->logoUrl() }}" alt="" width="48" height="48" style="object-fit:contain;max-height:48px;">
          @else
          <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 20L24 8L40 20V40H30V28H18V40H8V20Z" stroke="#2563eb" stroke-width="2" stroke-linejoin="round"/>
            <path d="M14 22L24 14L34 22" stroke="#1d4ed8" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M20 40V30H28V40" stroke="#3b82f6" stroke-width="1.5"/>
          </svg>
          @endif
        </span>
        <span class="pu-brand-text">
          <span class="pu-brand-name">{{ strtoupper($siteSettings?->site_name ?? 'PROPUPDATE') }}</span>
          <span class="pu-brand-tag">{{ strtoupper($siteSettings?->tagline ?? 'UPDATE YOUR PROPERTY SEARCH') }}</span>
        </span>
      </a>

      <nav class="pu-main-nav d-none d-lg-flex" aria-label="Primary">
        <a href="{{ url('/') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
        <a href="{{ route('pages.about') }}" class="{{ request()->routeIs('pages.about') ? 'is-active' : '' }}">About Us</a>
        <a href="{{ route('new-launches.index') }}" class="{{ request()->routeIs('new-launches.index') ? 'is-active' : '' }}">New Launches</a>
       
          
        <a href="{{ route('properties.index') }}" class="{{ request()->routeIs('properties.*') ? 'is-active' : '' }}">Properties</a>
      
        <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'is-active' : '' }}">Projects</a>
        <a href="{{ route('pages.contact') }}" class="{{ request()->routeIs('pages.contact') ? 'is-active' : '' }}">Contact Us</a>
      </nav>

      <div class="pu-header-cta d-none d-lg-flex">
        <a href="{{ route('exclusive-resale.index') }}" class="pu-btn-outline {{ request()->routeIs('exclusive-resale.*') ? 'is-active' : '' }}">Exclusive Resale</a>
        <a href="{{ $siteSettings?->whatsappHref() ?? 'https://wa.me/917204362646' }}" class="pu-icon-circle pu-icon-wa" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        <a href="{{ $siteSettings?->mailtoHref() ?? 'mailto:info@propupdate.com' }}" class="pu-icon-circle pu-icon-mail" aria-label="Email"><i class="fa-solid fa-envelope"></i></a>
      </div>

      <button type="button" class="pu-burger d-lg-none mobile-nav-icon" aria-label="Open menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>
</div>

<div class="mobile-sidebar mobile-sidebar1">
  <div class="logosicon-area">
    <div class="logos">
      <span class="pu-mobile-brand-name">PROPUPDATE</span>
    </div>
    <div class="menu-close">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
        <path d="M10.5859 12L2.79297 4.20706L4.20718 2.79285L12.0001 10.5857L19.793 2.79285L21.2072 4.20706L13.4143 12L21.2072 19.7928L19.793 21.2071L12.0001 13.4142L4.20718 21.2071L2.79297 19.7928L10.5859 12Z"></path>
      </svg>
    </div>
  </div>
  <div class="mobile-nav mobile-nav1">
    <ul class="mobile-nav-list nav-list1">
      <li><a href="{{ url('/') }}">Home</a></li>
      <li><a href="{{ route('new-launches.index') }}">New Launches</a></li>
      <li><a href="{{ route('pages.about') }}">About Us</a></li>
      <li><a href="{{ route('pages.contact') }}">Contact</a></li>
      <li><a href="{{ route('properties.index') }}">Properties</a></li>
      <li><a href="{{ route('projects.index') }}">Projects</a></li>
      <li><a href="{{ route('exclusive-resale.index') }}">Exclusive resale</a></li>
    </ul>
    <div class="allmobilesection" style="padding: 1rem 1.25rem;">
      <a href="{{ route('exclusive-resale.index') }}" class="pu-btn-outline d-inline-block w-100 mb-3 text-center">Exclusive Resale</a>
      <div class="d-flex gap-2 justify-content-center">
        <a href="{{ $siteSettings?->whatsappHref() ?? 'https://wa.me/917204362646' }}" class="pu-icon-circle pu-icon-wa" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        <a href="{{ $siteSettings?->mailtoHref() ?? 'mailto:info@propupdate.com' }}" class="pu-icon-circle pu-icon-mail" aria-label="Email"><i class="fa-solid fa-envelope"></i></a>
      </div>
    </div>
  </div>
</div>
