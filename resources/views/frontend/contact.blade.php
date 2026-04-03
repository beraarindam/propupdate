@extends('frontend.layouts.master')
@section('title', 'Contact Us')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => 'Contact us',
  'crumbCurrent' => 'Contact us',
  'lead' => 'Questions about <strong>resale</strong>, <strong>launches</strong>, or <strong>investments</strong>? We reply within one business day.',
  'bgImage' => 'https://images.unsplash.com/photo-1423666639041-f56000c27a9a?auto=format&fit=crop&w=1920&q=80',
])

<section class="pu-contact">
  <div class="container">
    @if(session('contact_status'))
      <div class="pu-contact__alert" role="status">
        <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
        {{ session('contact_status') }}
      </div>
    @endif

    @if($errors->any())
      <div class="pu-contact__alert pu-contact__alert--error" role="alert">
        <strong>Please fix the following:</strong>
        <ul class="mb-0 mt-2 ps-3">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row g-4 g-lg-5 align-items-stretch">
      <div class="col-lg-5">
        <p class="pu-contact__kicker">Talk to PropUpdate</p>
        <h2 class="pu-contact__h2">We’re here in Bangalore</h2>
        <p class="pu-contact__intro">
          Share your brief — budget, locality, timeline — and we’ll route you to the right specialist.
        </p>
        <ul class="pu-contact__channels">
          <li class="pu-contact__channel">
            <span class="pu-contact__channel-icon" aria-hidden="true"><i class="fa-solid fa-phone"></i></span>
            <div>
              <span class="pu-contact__channel-label">Phone</span>
              <a href="tel:+917204362646">7204362646</a>
            </div>
          </li>
          <li class="pu-contact__channel">
            <span class="pu-contact__channel-icon" aria-hidden="true"><i class="fa-brands fa-whatsapp"></i></span>
            <div>
              <span class="pu-contact__channel-label">WhatsApp</span>
              <a href="https://wa.me/917204362646" target="_blank" rel="noopener noreferrer">Message us</a>
            </div>
          </li>
          <li class="pu-contact__channel">
            <span class="pu-contact__channel-icon" aria-hidden="true"><i class="fa-solid fa-envelope"></i></span>
            <div>
              <span class="pu-contact__channel-label">Email</span>
              <a href="mailto:info@propupdate.com">info@propupdate.com</a>
            </div>
          </li>
          <li class="pu-contact__channel">
            <span class="pu-contact__channel-icon" aria-hidden="true"><i class="fa-solid fa-location-dot"></i></span>
            <div>
              <span class="pu-contact__channel-label">Office</span>
              <span class="pu-contact__channel-text">North Bangalore, Karnataka, India</span>
            </div>
          </li>
        </ul>
        <div class="pu-contact__hours">
          <strong>Hours</strong> Mon–Sat · 10:00 – 19:00 IST
        </div>
      </div>
      <div class="col-lg-7">
        <div class="pu-contact__form-card">
          <h3 class="pu-contact__form-title">Send a message</h3>
          <p class="pu-contact__form-sub">Fields marked with <span class="text-danger">*</span> are required.</p>
          <form class="pu-contact__form" action="{{ route('pages.contact.submit') }}" method="post" novalidate>
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="pu-contact__label" for="contact-name">Full name <span class="text-danger">*</span></label>
                <input class="pu-contact__input" type="text" id="contact-name" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Your name">
              </div>
              <div class="col-md-6">
                <label class="pu-contact__label" for="contact-email">Email <span class="text-danger">*</span></label>
                <input class="pu-contact__input" type="email" id="contact-email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com">
              </div>
              <div class="col-md-6">
                <label class="pu-contact__label" for="contact-phone">Phone</label>
                <input class="pu-contact__input" type="tel" id="contact-phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="+91 …">
              </div>
              <div class="col-md-6">
                <label class="pu-contact__label" for="contact-subject">Subject</label>
                <input class="pu-contact__input" type="text" id="contact-subject" name="subject" value="{{ old('subject') }}" placeholder="e.g. Whitefield 3BHK resale">
              </div>
              <div class="col-12">
                <label class="pu-contact__label" for="contact-message">Message <span class="text-danger">*</span></label>
                <textarea class="pu-contact__textarea" id="contact-message" name="message" rows="5" required placeholder="Tell us what you’re looking for…">{{ old('message') }}</textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="pu-contact__submit">Send message</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
