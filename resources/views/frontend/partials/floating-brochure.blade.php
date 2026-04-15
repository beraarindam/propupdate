<div id="pu-floating-brochure" class="pu-floating-brochure">
  <button
    type="button"
    class="pu-floating-brochure__trigger"
    data-pu-brochure-open
    aria-haspopup="dialog"
    aria-controls="pu-brochure-modal"
  >
    Get Free Brochure
  </button>
</div>

<div
  id="pu-brochure-modal"
  class="pu-brochure-modal"
  role="dialog"
  aria-modal="true"
  aria-labelledby="pu-brochure-modal-title"
  hidden
>
  <button type="button" class="pu-brochure-modal__backdrop" data-pu-brochure-close aria-label="Close brochure popup"></button>
  <div class="pu-brochure-modal__dialog">
    <button type="button" class="pu-brochure-modal__close" data-pu-brochure-close aria-label="Close">
      <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>
    <h2 id="pu-brochure-modal-title" class="pu-brochure-modal__title">Get Free Brochure</h2>
    <p class="pu-brochure-modal__subtitle">Share your details and our team will send the brochure.</p>

    @if(session('floating_brochure_status'))
      <div class="alert alert-success py-2 px-3 small mb-3" role="status">
        {{ session('floating_brochure_status') }}
      </div>
    @endif

    <form action="{{ route('lead.brochure') }}" method="post" class="pu-brochure-form" novalidate>
      @csrf
      <input type="hidden" name="page_url" value="{{ url()->current() }}" data-pu-brochure-page-url>

      <div class="pu-brochure-form__row">
        <label for="pu-brochure-name">Name</label>
        <input id="pu-brochure-name" type="text" name="brochure_name" value="{{ old('brochure_name') }}" autocomplete="name" required>
        @error('brochure_name', 'floatingBrochure')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="pu-brochure-form__row">
        <label for="pu-brochure-email">Email</label>
        <input id="pu-brochure-email" type="email" name="brochure_email" value="{{ old('brochure_email') }}" autocomplete="email" required>
        @error('brochure_email', 'floatingBrochure')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="pu-brochure-form__row">
        <label for="pu-brochure-phone">Phone no</label>
        <input id="pu-brochure-phone" type="text" name="brochure_phone" value="{{ old('brochure_phone') }}" autocomplete="tel" required>
        @error('brochure_phone', 'floatingBrochure')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="pu-brochure-form__row">
        <label for="pu-brochure-message">Message</label>
        <textarea id="pu-brochure-message" name="brochure_message" rows="3" placeholder="Location, property type, budget..." required>{{ old('brochure_message') }}</textarea>
        @error('brochure_message', 'floatingBrochure')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <button type="submit" class="pu-brochure-form__submit">Submit</button>
    </form>
  </div>
</div>

<script>
(function () {
  var modal = document.getElementById('pu-brochure-modal');
  if (!modal) return;
  var hasServerFeedback = {{ session()->has('floating_brochure_status') || $errors->floatingBrochure->any() ? 'true' : 'false' }};

  function openModal() {
    modal.removeAttribute('hidden');
    document.body.classList.add('pu-brochure-modal-open');
  }

  function closeModal() {
    modal.setAttribute('hidden', '');
    document.body.classList.remove('pu-brochure-modal-open');
  }

  document.querySelectorAll('[data-pu-brochure-open]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      openModal();
    });
  });

  modal.querySelectorAll('[data-pu-brochure-close]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      closeModal();
    });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hasAttribute('hidden')) {
      closeModal();
    }
  });

  var pageInput = modal.querySelector('[data-pu-brochure-page-url]');
  if (pageInput && window.location) {
    pageInput.value = window.location.href;
  }

  if (hasServerFeedback) {
    openModal();
  }
})();
</script>
