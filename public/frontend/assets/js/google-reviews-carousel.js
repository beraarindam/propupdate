(function () {
  'use strict';

  var root = document.querySelector('[data-pu-greviews]');
  if (!root) return;

  var track = root.querySelector('[data-pu-greviews-track]');
  var prev = root.querySelector('[data-pu-greviews-prev]');
  var next = root.querySelector('[data-pu-greviews-next]');

  function stepSize() {
    var card = track ? track.querySelector('.pu-greviews__card') : null;
    if (!card) return 320;
    var rect = card.getBoundingClientRect();
    var gap = 16;
    return Math.round(rect.width + gap);
  }

  if (prev && track) {
    prev.addEventListener('click', function () {
      track.scrollBy({ left: -stepSize(), behavior: 'smooth' });
    });
  }
  if (next && track) {
    next.addEventListener('click', function () {
      track.scrollBy({ left: stepSize(), behavior: 'smooth' });
    });
  }

  root.querySelectorAll('[data-pu-greviews-more]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var id = btn.getAttribute('data-pu-greviews-more');
      var p = root.querySelector('[data-pu-greviews-text="' + id + '"]');
      if (!p) return;
      p.classList.remove('is-clamped');
      btn.hidden = true;
    });
  });
})();
