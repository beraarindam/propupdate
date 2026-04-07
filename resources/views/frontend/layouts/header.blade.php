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

  @php
    $megaCards = $newLaunchesMegaCards ?? [];
    $propertiesMegaCards = $propertiesMegaCards ?? [];
    $projectMegaCards = $projectsMegaCards ?? [];
    $projectMegaEnabled = count($projectMegaCards) > 0;
    $citiesMega = $citiesMega ?? ['areas' => [], 'cardsByCity' => []];
    $citiesMegaCardsByCity = $citiesMega['cardsByCity'] ?? [];
    $citiesMegaAreas = $citiesMega['areas'] ?? [];
    $citiesMegaAllKey = \App\Support\CitiesMegaMenu::ALL_KEY;
    $citiesMegaInitialCards = $citiesMegaCardsByCity[$citiesMegaAllKey] ?? [];
    $citiesMegaHasListings = count($citiesMegaInitialCards) > 0;
  @endphp
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
          <span class="pu-brand-name">{{ $siteSettings?->site_name ?: 'PropUpdate' }}</span>
          <span class="pu-brand-tag">{{ $siteSettings?->tagline ?: 'Update your property search' }}</span>
        </span>
      </a>

      <nav class="pu-main-nav d-none d-lg-flex" aria-label="Primary">
        <a href="{{ url('/') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
        <a href="{{ route('pages.about') }}" class="{{ request()->routeIs('pages.about') ? 'is-active' : '' }}">About Us</a>
        <div class="pu-nav-mega-wrap">
          <a href="{{ route('new-launches.index') }}" class="pu-nav-mega__trigger {{ request()->routeIs('new-launches.index') ? 'is-active' : '' }}">
            New Launches
            <i class="fa-solid fa-chevron-down pu-nav-mega__chev" aria-hidden="true"></i>
          </a>
          <div class="pu-nav-mega" role="region" aria-label="New launch highlights">
            <div class="pu-nav-mega__inner">
              <div class="pu-nav-mega__head">
                <span class="pu-nav-mega__kicker"><i class="fa-solid fa-star me-2" aria-hidden="true"></i>New &amp; featured</span>
                <a href="{{ route('new-launches.index') }}" class="pu-nav-mega__see-all">View all <span class="text-nowrap">new launches <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span></a>
              </div>
              @if(count($megaCards) > 0)
                <div class="pu-nav-mega__body">
                  <button type="button" class="pu-nav-mega__arrow pu-nav-mega__arrow--prev" data-mega-prev aria-label="Show previous items">
                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                  </button>
                  <div class="pu-nav-mega__viewport">
                    <div class="pu-nav-mega__track" data-mega-track>
                      @foreach($megaCards as $card)
                        <a href="{{ $card['url'] }}" class="pu-nav-mega__card">
                          <div class="pu-nav-mega__media-wrap">
                            @if(!empty($card['image']))
                              <img src="{{ $card['image'] }}" alt="" class="pu-nav-mega__img" loading="lazy" width="320" height="192">
                            @else
                              <div class="pu-nav-mega__media-fallback" aria-hidden="true"></div>
                            @endif
                            <span class="pu-nav-mega__badge">{{ $card['badge'] }}</span>
                          </div>
                          <div class="pu-nav-mega__text">
                            <span class="pu-nav-mega__title">{{ \Illuminate\Support\Str::limit($card['title'], 54) }}</span>
                            <span class="pu-nav-mega__loc">{{ $card['location'] }}</span>
                            <span class="pu-nav-mega__cta">Details <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span>
                          </div>
                        </a>
                      @endforeach
                    </div>
                  </div>
                  <button type="button" class="pu-nav-mega__arrow pu-nav-mega__arrow--next" data-mega-next aria-label="Show more items">
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                  </button>
                </div>
              @else
                <div class="pu-nav-mega__empty">
                  <p class="pu-nav-mega__empty-text mb-2 mb-md-3">Mark listings as <strong>New launch</strong> in Admin → Properties (published) to show them here.</p>
                  <a href="{{ route('new-launches.index') }}" class="pu-nav-mega__empty-cta">Browse the full new launches page <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
                </div>
              @endif
            </div>
          </div>
        </div>
        <div class="pu-nav-mega-wrap">
          <a href="{{ route('properties.index') }}" class="pu-nav-mega__trigger {{ request()->routeIs('properties.*') ? 'is-active' : '' }}">
            Properties
            <i class="fa-solid fa-chevron-down pu-nav-mega__chev" aria-hidden="true"></i>
          </a>
          <div class="pu-nav-mega" role="region" aria-label="Property categories">
            <div class="pu-nav-mega__inner">
              <div class="pu-nav-mega__head">
                <span class="pu-nav-mega__kicker"><i class="fa-solid fa-house me-2" aria-hidden="true"></i>Top categories</span>
                <a href="{{ route('properties.index') }}" class="pu-nav-mega__see-all">View all <span class="text-nowrap">categories <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span></a>
              </div>
              @if(count($propertiesMegaCards) > 0)
                <div class="pu-nav-mega__body">
                  <button type="button" class="pu-nav-mega__arrow pu-nav-mega__arrow--prev" data-mega-prev aria-label="Show previous items">
                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                  </button>
                  <div class="pu-nav-mega__viewport">
                    <div class="pu-nav-mega__track" data-mega-track>
                      @foreach($propertiesMegaCards as $card)
                        <a href="{{ $card['url'] }}" class="pu-nav-mega__card">
                          <div class="pu-nav-mega__media-wrap">
                            @if(!empty($card['image']))
                              <img src="{{ $card['image'] }}" alt="" class="pu-nav-mega__img" loading="lazy" width="320" height="192">
                            @else
                              <div class="pu-nav-mega__media-fallback" aria-hidden="true"></div>
                            @endif
                            <span class="pu-nav-mega__badge">{{ $card['badge'] }}</span>
                          </div>
                          <div class="pu-nav-mega__text">
                            <span class="pu-nav-mega__title">{{ \Illuminate\Support\Str::limit($card['title'], 54) }}</span>
                            <span class="pu-nav-mega__loc">{{ $card['location'] }}</span>
                            <span class="pu-nav-mega__cta">Browse <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span>
                          </div>
                        </a>
                      @endforeach
                    </div>
                  </div>
                  <button type="button" class="pu-nav-mega__arrow pu-nav-mega__arrow--next" data-mega-next aria-label="Show more items">
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                  </button>
                </div>
              @else
                <div class="pu-nav-mega__empty">
                  <p class="pu-nav-mega__empty-text mb-2 mb-md-3">Publish categories in <strong>Admin → Property categories</strong> to show them here.</p>
                  <a href="{{ route('properties.index') }}" class="pu-nav-mega__empty-cta">Browse all properties <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="pu-nav-mega-wrap">
          <a href="{{ route('properties.index') }}" class="pu-nav-mega__trigger">
            Areas
            <i class="fa-solid fa-chevron-down pu-nav-mega__chev" aria-hidden="true"></i>
          </a>
          <div
            class="pu-nav-mega pu-nav-mega--cities"
            role="region"
            aria-label="Browse properties by area"
            data-cities-mega-root
            data-properties-index="{{ route('properties.index') }}"
          >
            <div class="pu-nav-mega__inner">
              <div class="pu-nav-mega__head">
                <span class="pu-nav-mega__kicker"><i class="fa-solid fa-location-dot me-2" aria-hidden="true"></i>By area</span>
                <a href="{{ route('properties.index') }}" class="pu-nav-mega__see-all" data-cities-see-all>View all <span class="text-nowrap">properties <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span></a>
              </div>
              @if(! $citiesMegaHasListings)
                <div class="pu-nav-mega__empty">
                  <p class="pu-nav-mega__empty-text mb-2 mb-md-3">Publish listings with an <strong>area</strong> in Admin → Properties to browse them here.</p>
                  <a href="{{ route('properties.index') }}" class="pu-nav-mega__empty-cta">Browse properties <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
                </div>
              @else
                <div class="pu-cities-mega__split">
                  <div class="pu-cities-mega__sidebar" data-cities-mega-sidebar>
                    <div class="pu-cities-mega__sidebar-label">Area</div>
                    <ul class="pu-cities-mega__city-list list-unstyled mb-0">
                      <li>
                        <button type="button" class="pu-cities-mega__city is-active" data-cities-mega-city="{{ $citiesMegaAllKey }}">
                          All
                        </button>
                      </li>
                      @foreach($citiesMegaAreas as $area)
                        <li>
                          <button type="button" class="pu-cities-mega__city" data-cities-mega-city="{{ (string) ($area['id'] ?? '') }}">
                            {{ $area['name'] ?? '' }}
                          </button>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                  <div class="pu-cities-mega__main">
                    <div class="pu-nav-mega__body" data-cities-body-row>
                      <button type="button" class="pu-nav-mega__arrow pu-nav-mega__arrow--prev" data-mega-prev aria-label="Show previous items">
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                      </button>
                      <div class="pu-nav-mega__viewport">
                        <div class="pu-nav-mega__track" data-mega-track data-cities-track>
                          @foreach($citiesMegaInitialCards as $card)
                            <a href="{{ $card['url'] }}" class="pu-nav-mega__card">
                              <div class="pu-nav-mega__media-wrap">
                                @if(!empty($card['image']))
                                  <img src="{{ $card['image'] }}" alt="" class="pu-nav-mega__img" loading="lazy" width="320" height="192">
                                @else
                                  <div class="pu-nav-mega__media-fallback" aria-hidden="true"></div>
                                @endif
                                <span class="pu-nav-mega__badge">{{ $card['badge'] }}</span>
                              </div>
                              <div class="pu-nav-mega__text">
                                <span class="pu-nav-mega__title">{{ $card['title'] }}</span>
                                <span class="pu-nav-mega__loc">{{ $card['location'] }}</span>
                                <span class="pu-nav-mega__cta">Details <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span>
                              </div>
                            </a>
                          @endforeach
                        </div>
                      </div>
                      <button type="button" class="pu-nav-mega__arrow pu-nav-mega__arrow--next" data-mega-next aria-label="Show more items">
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                      </button>
                    </div>
                    <div class="pu-cities-mega__empty-main" data-cities-empty-panel hidden>
                      <p class="pu-cities-mega__empty-main-text mb-0">No published listings in this area yet.</p>
                      <a href="{{ route('properties.index') }}" class="pu-nav-mega__empty-cta d-inline-flex mt-2">View all properties <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></a>
                    </div>
                  </div>
                </div>
                <script type="application/json" id="pu-cities-mega-json">@json($citiesMegaCardsByCity)</script>
              @endif
            </div>
          </div>
        </div>

        @if($projectMegaEnabled)
          <div class="pu-nav-mega-wrap">
            <a href="{{ route('projects.index') }}" class="pu-nav-mega__trigger {{ request()->routeIs('projects.*') ? 'is-active' : '' }}">
              Projects
              <i class="fa-solid fa-chevron-down pu-nav-mega__chev" aria-hidden="true"></i>
            </a>
            <div class="pu-nav-mega" role="region" aria-label="Project highlights">
              <div class="pu-nav-mega__inner">
                <div class="pu-nav-mega__head">
                  <span class="pu-nav-mega__kicker"><i class="fa-solid fa-building me-2" aria-hidden="true"></i>Developments</span>
                  <a href="{{ route('projects.index') }}" class="pu-nav-mega__see-all">View all <span class="text-nowrap">projects <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span></a>
                </div>
                <div class="pu-nav-mega__body">
                  <button type="button" class="pu-nav-mega__arrow pu-nav-mega__arrow--prev" data-mega-prev aria-label="Show previous items">
                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                  </button>
                  <div class="pu-nav-mega__viewport">
                    <div class="pu-nav-mega__track" data-mega-track>
                      @foreach($projectMegaCards as $card)
                        <a href="{{ $card['url'] }}" class="pu-nav-mega__card">
                          <div class="pu-nav-mega__media-wrap">
                            @if(!empty($card['image']))
                              <img src="{{ $card['image'] }}" alt="" class="pu-nav-mega__img" loading="lazy" width="320" height="192">
                            @else
                              <div class="pu-nav-mega__media-fallback" aria-hidden="true"></div>
                            @endif
                            <span class="pu-nav-mega__badge">{{ $card['badge'] }}</span>
                          </div>
                          <div class="pu-nav-mega__text">
                            <span class="pu-nav-mega__title">{{ \Illuminate\Support\Str::limit($card['title'], 54) }}</span>
                            <span class="pu-nav-mega__loc">{{ $card['location'] }}</span>
                            <span class="pu-nav-mega__cta">Details <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span>
                          </div>
                        </a>
                      @endforeach
                    </div>
                  </div>
                  <button type="button" class="pu-nav-mega__arrow pu-nav-mega__arrow--next" data-mega-next aria-label="Show more items">
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        @else
          <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'is-active' : '' }}">Projects</a>
        @endif
        
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
      <span class="pu-mobile-brand-name">{{ $siteSettings?->site_name ?: 'PropUpdate' }}</span>
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
      <li><a href="{{ route('properties.index') }}">Areas</a></li>
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
