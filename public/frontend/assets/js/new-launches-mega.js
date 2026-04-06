(function () {
  'use strict';

  var wraps = document.querySelectorAll('.pu-nav-mega-wrap');
  if (!wraps.length) return;

  var closeDelayMs = 200;

  wraps.forEach(function (wrap) {
    var hideTimer = null;

    function openMega() {
      if (hideTimer) {
        clearTimeout(hideTimer);
        hideTimer = null;
      }
      wrap.classList.add('pu-mega--open');
    }

    function scheduleClose() {
      if (hideTimer) clearTimeout(hideTimer);
      hideTimer = setTimeout(function () {
        wrap.classList.remove('pu-mega--open');
        hideTimer = null;
      }, closeDelayMs);
    }

    wrap.addEventListener('mouseenter', openMega);
    wrap.addEventListener('mouseleave', scheduleClose);
    wrap.addEventListener('focusin', openMega);
    wrap.addEventListener('focusout', function (ev) {
      if (!wrap.contains(ev.relatedTarget)) {
        scheduleClose();
      }
    });

    var track = wrap.querySelector('[data-mega-track]');
    if (!track) return;

    var step = function () {
      var card = track.querySelector('.pu-nav-mega__card');
      if (!card) return 280;
      var w = card.getBoundingClientRect().width;
      var gap = 16;
      return Math.round(w + gap);
    };

    var prev = wrap.querySelector('[data-mega-prev]');
    var next = wrap.querySelector('[data-mega-next]');

    if (prev) {
      prev.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        track.scrollBy({ left: -step(), behavior: 'smooth' });
      });
    }
    if (next) {
      next.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        track.scrollBy({ left: step(), behavior: 'smooth' });
      });
    }
  });
})();
