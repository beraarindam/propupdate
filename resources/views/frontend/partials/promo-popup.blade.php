@php
  $promoImg = $siteSettings?->promoPopupBannerUrl();
  $promoShow = $siteSettings && $siteSettings->promo_popup_enabled && $promoImg;
  $promoSig = $promoShow ? $siteSettings->promoPopupDismissSignature() : '';
  $promoLinkRaw = $promoShow ? trim((string) ($siteSettings->promo_popup_link_url ?? '')) : '';
  $promoLinkHref = ($promoShow && $promoLinkRaw !== '') ? \App\Models\Page::resolveHref($promoLinkRaw) : '';
  $promoLinkExternal = $promoLinkRaw !== '' && preg_match('#\Ahttps?://#i', $promoLinkRaw);
@endphp
@if($promoShow)
<div
  id="pu-promo-modal"
  class="pu-promo-modal"
  role="dialog"
  aria-modal="true"
  aria-labelledby="pu-promo-title"
  hidden
  data-sig="{{ $promoSig }}"
>
  <div class="pu-promo-modal__backdrop" data-pu-promo-close type="button" aria-label="Close promotion"></div>
  <div class="pu-promo-modal__dialog">
    <button type="button" class="pu-promo-modal__close" data-pu-promo-close aria-label="Close">
      <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>
    <h2 id="pu-promo-title" class="visually-hidden">Promotion</h2>
    <div class="pu-promo-modal__body">
      @if($promoLinkHref !== '')
        <a href="{{ $promoLinkHref }}" class="pu-promo-modal__link"@if($promoLinkExternal) target="_blank" rel="noopener noreferrer"@endif>
          <img src="{{ $promoImg }}" alt="" class="pu-promo-modal__img" width="800" height="600" loading="eager" decoding="async">
        </a>
      @else
        <img src="{{ $promoImg }}" alt="" class="pu-promo-modal__img" width="800" height="600" loading="eager" decoding="async">
      @endif
    </div>
  </div>
</div>
<script>
(function () {
  var el = document.getElementById('pu-promo-modal');
  if (!el) return;
  var sig = el.getAttribute('data-sig') || '';
  var pageKey = (window.location && window.location.pathname) ? window.location.pathname : 'home';
  var key = 'pu_promo_closed:' + sig + ':' + pageKey;
  function getStore() {
    try {
      window.localStorage.setItem('__pu_t', '1');
      window.localStorage.removeItem('__pu_t');
      return window.localStorage;
    } catch (e) {}
    try {
      window.sessionStorage.setItem('__pu_t', '1');
      window.sessionStorage.removeItem('__pu_t');
      return window.sessionStorage;
    } catch (e2) {}
    return null;
  }
  var store = getStore();
  if (!sig) return;
  if (store && store.getItem(key) === '1') return;

  function close() {
    el.setAttribute('hidden', '');
    document.body.classList.remove('pu-promo-modal-open');
    if (store) {
      try {
        store.setItem(key, '1');
      } catch (e) {}
    }
  }

  function open() {
    el.removeAttribute('hidden');
    document.body.classList.add('pu-promo-modal-open');
  }

  function openWithDelay() {
    window.setTimeout(open, 3000);
  }

  el.querySelectorAll('[data-pu-promo-close]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      close();
    });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !el.hasAttribute('hidden')) close();
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', openWithDelay);
  } else {
    openWithDelay();
  }
})();
</script>
@endif
