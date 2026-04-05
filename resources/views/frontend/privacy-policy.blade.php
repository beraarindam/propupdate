@extends('frontend.layouts.master')
@section('title', $page?->browserTitle() ?? 'Privacy policy')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $page?->banner_title ?? 'Privacy policy',
  'crumbCurrent' => $page?->name ?? 'Privacy policy',
  'lead' => $page?->banner_lead ?? 'How PropUpdate Realty collects, uses, and protects your <strong>personal information</strong> on this website.',
  'bgImage' => $page?->bannerBackgroundUrl() ?? 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1920&q=80',
])

<article class="pu-legal">
  <div class="container">
    @if(filled($page?->body_html))
    <div class="pu-legal__inner pu-page-body-cms">
      {!! $page->body_html !!}
    </div>
    @else
    <div class="pu-legal__inner">
      <p class="pu-legal__meta">Last updated: {{ date('F j, Y') }}</p>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">1</span> Introduction</h2>
        <p>
          PropUpdate Realty (“we”, “us”, “our”) respects your privacy. This policy describes how we handle information when you use our website, forms, and related digital channels. It is meant as a general notice; for site-specific or contractual commitments, we may provide additional terms.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">2</span> Information we may collect</h2>
        <p>Depending on how you interact with us, we may process:</p>
        <ul>
          <li><strong>Contact details</strong> — name, email address, phone number, and messages you send via forms or email.</li>
          <li><strong>Usage data</strong> — approximate location, device type, browser, pages viewed, and referral sources (often via logs or analytics).</li>
          <li><strong>Preferences</strong> — property interests or areas you tell us about when you enquire.</li>
        </ul>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">3</span> How we use information</h2>
        <p>We use this information to:</p>
        <ul>
          <li>Respond to enquiries and provide property-related guidance;</li>
          <li>Improve our website and understand which content is useful;</li>
          <li>Comply with law and protect the security of our services.</li>
        </ul>
        <p>We do not sell your personal data to third parties for their independent marketing.</p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">4</span> Cookies &amp; analytics</h2>
        <p>
          Our site may use cookies or similar technologies to remember preferences and measure traffic. You can control cookies through your browser settings. Disabling some cookies may affect how certain features work.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">5</span> Sharing &amp; retention</h2>
        <p>
          We may share information with trusted service providers who assist us (e.g. hosting, email delivery) under appropriate safeguards, or when required by law. We retain enquiry and contact data only as long as needed for the purposes above or as required for records.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">6</span> Security</h2>
        <p>
          We apply reasonable technical and organisational measures to protect information. No method of transmission over the internet is completely secure; please use discretion when sharing sensitive details by email or forms.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">7</span> Your rights</h2>
        <p>
          Depending on applicable law, you may have the right to access, correct, or request deletion of your personal data, or to object to certain processing. To exercise these rights, contact us using the details on our <a href="{{ route('pages.contact') }}">Contact</a> page.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">8</span> Changes</h2>
        <p>
          We may update this policy from time to time. The “Last updated” date at the top will change when we do. Continued use of the site after changes constitutes acceptance of the revised policy where permitted by law.
        </p>
      </section>

      <p class="pu-legal__footnote">
        For questions about privacy, email <a href="mailto:info@propupdate.com">info@propupdate.com</a> or call <a href="tel:+917204362646">7204362646</a>.
      </p>
    </div>
    @endif
  </div>
</article>
@endsection
