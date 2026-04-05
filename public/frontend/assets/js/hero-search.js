/**
 * Home hero: typewriter placeholder + debounced AJAX suggestions.
 */
(function () {
  var root = document.getElementById('pu-hero-search');
  if (!root) {
    return;
  }

  var url = root.getAttribute('data-suggestions-url');
  var listUrl = root.getAttribute('data-list-url') || '';
  var phrases = [];
  try {
    phrases = JSON.parse(root.getAttribute('data-typing-phrases') || '[]');
  } catch (e) {
    phrases = [];
  }
  if (!phrases.length) {
    phrases = ['Search by area or project…'];
  }

  var input = document.getElementById('pu-hero-q');
  var typingEl = document.getElementById('pu-hero-typing');
  var panel = document.getElementById('pu-hero-search-panel');
  var listEl = document.getElementById('pu-hero-search-list');
  var loadingEl = document.getElementById('pu-hero-search-loading');
  var footEl = document.getElementById('pu-hero-search-foot');
  var allLink = document.getElementById('pu-hero-search-all');

  if (!input || !panel || !listEl) {
    return;
  }

  var debounceTimer;
  var abortCtrl;
  var activeIndex = -1;
  var phraseTimer;
  var ti = 0;
  var ci = 0;
  var deleting = false;

  function setIdle(on) {
    if (on) {
      root.classList.add('pu-hero-search--idle');
    } else {
      root.classList.remove('pu-hero-search--idle');
    }
  }

  function openPanel(open) {
    panel.hidden = !open;
    input.setAttribute('aria-expanded', open ? 'true' : 'false');
    root.classList.toggle('pu-hero-search--open', open);
  }

  function closePanel() {
    openPanel(false);
    activeIndex = -1;
    listEl.querySelectorAll('.pu-hero-search__item').forEach(function (el) {
      el.classList.remove('is-active');
    });
  }

  function debounce(fn, ms) {
    return function () {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(fn, ms);
    };
  }

  function escapeHtml(s) {
    var d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }

  function fetchSuggestions(q) {
    if (abortCtrl) {
      abortCtrl.abort();
    }
    if (q.length < 2) {
      listEl.innerHTML = '';
      if (loadingEl) {
        loadingEl.hidden = true;
      }
      if (footEl) {
        footEl.hidden = true;
      }
      closePanel();
      return;
    }

    abortCtrl = new AbortController();
    if (loadingEl) {
      loadingEl.hidden = false;
    }
    openPanel(true);

    fetch(url + '?q=' + encodeURIComponent(q), {
      signal: abortCtrl.signal,
      headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        if (loadingEl) {
          loadingEl.hidden = true;
        }
        var results = data.results || [];
        listEl.innerHTML = '';

        results.forEach(function (item, i) {
          var a = document.createElement('a');
          a.href = item.url;
          a.className = 'pu-hero-search__item';
          a.setAttribute('role', 'option');
          a.id = 'pu-hero-opt-' + i;
          a.addEventListener('mousedown', function (e) {
            e.preventDefault();
            window.location.href = item.url;
          });

          var row =
            '<span class="pu-hero-search__item-title">' +
            escapeHtml(item.title) +
            '</span>' +
            '<span class="pu-hero-search__item-sub">' +
            (item.deal ? '<span class="pu-hero-search__item-deal">' + escapeHtml(item.deal) + '</span>' : '') +
            (item.location
              ? '<span class="pu-hero-search__item-loc">' + escapeHtml(item.location) + '</span>'
              : '') +
            (item.price_label
              ? '<span class="pu-hero-search__item-price">' + escapeHtml(item.price_label) + '</span>'
              : '') +
            '</span>';

          a.innerHTML = row;
          listEl.appendChild(a);
        });

        if (results.length === 0) {
          var empty = document.createElement('div');
          empty.className = 'pu-hero-search__empty';
          empty.textContent =
            'No matching listings yet. Try another keyword or browse the full directory.';
          listEl.appendChild(empty);
        }

        if (footEl && allLink) {
          footEl.hidden = false;
          allLink.href = listUrl + (listUrl.indexOf('?') >= 0 ? '&' : '?') + 'q=' + encodeURIComponent(q);
        }

        activeIndex = -1;
      })
      .catch(function (e) {
        if (e.name === 'AbortError') {
          return;
        }
        if (loadingEl) {
          loadingEl.hidden = true;
        }
      });
  }

  var runFetch = debounce(function () {
    fetchSuggestions(input.value.trim());
  }, 260);

  input.addEventListener('input', function () {
    if (input.value.trim()) {
      setIdle(false);
    } else {
      setIdle(true);
    }
    runFetch();
  });

  input.addEventListener('focus', function () {
    setIdle(false);
    if (input.value.trim().length >= 2) {
      fetchSuggestions(input.value.trim());
    }
  });

  input.addEventListener('blur', function () {
    setTimeout(function () {
      if (!input.value.trim()) {
        setIdle(true);
      }
      closePanel();
    }, 200);
  });

  input.addEventListener('keydown', function (e) {
    var opts = listEl.querySelectorAll('a.pu-hero-search__item');
    if (!opts.length || panel.hidden) {
      return;
    }
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      activeIndex = Math.min(activeIndex + 1, opts.length - 1);
      opts.forEach(function (el, i) {
        el.classList.toggle('is-active', i === activeIndex);
      });
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeIndex = Math.max(activeIndex - 1, -1);
      opts.forEach(function (el, i) {
        el.classList.toggle('is-active', i === activeIndex);
      });
    } else if (e.key === 'Enter' && activeIndex >= 0) {
      e.preventDefault();
      window.location.href = opts[activeIndex].href;
    } else if (e.key === 'Escape') {
      closePanel();
    }
  });

  document.addEventListener('click', function (e) {
    if (!root.contains(e.target)) {
      closePanel();
    }
  });

  function tickTyping() {
    clearTimeout(phraseTimer);
    if (document.activeElement === input || (input.value && input.value.trim())) {
      if (typingEl) {
        typingEl.textContent = '';
      }
      phraseTimer = setTimeout(tickTyping, 420);
      return;
    }
    if (!root.classList.contains('pu-hero-search--idle')) {
      phraseTimer = setTimeout(tickTyping, 420);
      return;
    }
    if (!typingEl) {
      phraseTimer = setTimeout(tickTyping, 420);
      return;
    }

    var phrase = phrases[ti % phrases.length];
    if (!deleting) {
      ci += 1;
      typingEl.textContent = phrase.slice(0, ci);
      if (ci >= phrase.length) {
        deleting = true;
        phraseTimer = setTimeout(tickTyping, 2200);
        return;
      }
      phraseTimer = setTimeout(tickTyping, 68);
    } else {
      ci -= 1;
      typingEl.textContent = phrase.slice(0, Math.max(ci, 0));
      if (ci <= 0) {
        deleting = false;
        ti += 1;
        phraseTimer = setTimeout(tickTyping, 520);
        return;
      }
      phraseTimer = setTimeout(tickTyping, 36);
    }
  }

  setIdle(true);
  tickTyping();
})();
