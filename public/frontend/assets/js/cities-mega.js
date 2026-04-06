(function () {
  'use strict';

  var ALL = '__all__';

  function esc(s) {
    if (s == null) {
      return '';
    }
    return String(s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function cardHtml(c) {
    var img = c.image
      ? '<img src="' + esc(c.image) + '" alt="" class="pu-nav-mega__img" loading="lazy" width="320" height="192">'
      : '<div class="pu-nav-mega__media-fallback" aria-hidden="true"></div>';
    return (
      '<a href="' + esc(c.url) + '" class="pu-nav-mega__card">' +
      '<div class="pu-nav-mega__media-wrap">' +
      img +
      '<span class="pu-nav-mega__badge">' + esc(c.badge) + '</span>' +
      '</div>' +
      '<div class="pu-nav-mega__text">' +
      '<span class="pu-nav-mega__title">' + esc(c.title) + '</span>' +
      '<span class="pu-nav-mega__loc">' + esc(c.location) + '</span>' +
      '<span class="pu-nav-mega__cta">Details <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i></span>' +
      '</div>' +
      '</a>'
    );
  }

  var jsonEl = document.getElementById('pu-cities-mega-json');
  var root = document.querySelector('[data-cities-mega-root]');
  if (!jsonEl || !root) {
    return;
  }

  var cardsByCity;
  try {
    cardsByCity = JSON.parse(jsonEl.textContent.trim());
  } catch (e) {
    return;
  }

  var track = root.querySelector('[data-cities-track]');
  var sidebar = root.querySelector('[data-cities-mega-sidebar]');
  var seeAll = root.querySelector('[data-cities-see-all]');
  var emptyPanel = root.querySelector('[data-cities-empty-panel]');
  var bodyRow = root.querySelector('[data-cities-body-row]');
  var indexUrl = root.getAttribute('data-properties-index') || '/properties';

  function setSeeAll(cityKey) {
    if (!seeAll) {
      return;
    }
    if (cityKey === ALL) {
      seeAll.setAttribute('href', indexUrl);
      return;
    }
    var join = indexUrl.indexOf('?') === -1 ? '?' : '&';
    seeAll.setAttribute('href', indexUrl + join + 'city=' + encodeURIComponent(cityKey));
  }

  function setCity(cityKey) {
    var key = cityKey || ALL;
    var cards = cardsByCity[key];
    if (!Array.isArray(cards)) {
      cards = [];
    }

    if (sidebar) {
      sidebar.querySelectorAll('[data-cities-mega-city]').forEach(function (btn) {
        var k = btn.getAttribute('data-cities-mega-city') || ALL;
        btn.classList.toggle('is-active', k === key);
      });
    }

    setSeeAll(key);

    if (cards.length === 0) {
      if (track) {
        track.innerHTML = '';
      }
      if (bodyRow) {
        bodyRow.hidden = true;
      }
      if (emptyPanel) {
        emptyPanel.hidden = false;
      }
      return;
    }

    if (emptyPanel) {
      emptyPanel.hidden = true;
    }
    if (bodyRow) {
      bodyRow.hidden = false;
    }
    if (track) {
      track.innerHTML = cards.map(cardHtml).join('');
      track.scrollLeft = 0;
    }
  }

  if (!sidebar) {
    return;
  }

  sidebar.querySelectorAll('[data-cities-mega-city]').forEach(function (btn) {
    var pick = function () {
      setCity(btn.getAttribute('data-cities-mega-city') || ALL);
    };
    btn.addEventListener('mouseenter', pick);
    btn.addEventListener('focusin', pick);
  });
})();
