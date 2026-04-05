@extends('frontend.layouts.master')
@section('title', $page?->browserTitle() ?? 'Terms & conditions')

@section('content')
@include('frontend.partials.page-banner', [
  'title' => $page?->banner_title ?? 'Terms & conditions',
  'crumbCurrent' => $page?->name ?? 'Terms & conditions',
  'lead' => $page?->banner_lead ?? 'Rules for using this website and our <strong>information services</strong>. Please read before you submit enquiries or rely on published content.',
  'bgImage' => $page?->bannerBackgroundUrl() ?? 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1920&q=80',
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
        <h2><span class="pu-legal__num">1</span> Agreement</h2>
        <p>
          By accessing or using the PropUpdate Realty website (the “Site”), you agree to these terms. If you do not agree, please do not use the Site. We may update these terms; the “Last updated” date reflects the latest version.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">2</span> Information, not advice</h2>
        <p>
          Content on the Site (including listings, articles, maps, and estimates) is for <strong>general information</strong> only. It is not legal, tax, or investment advice. Property availability, pricing, approvals, and specifications must be verified independently before you transact.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">3</span> Enquiries &amp; communications</h2>
        <p>
          When you submit forms or contact us, you represent that your information is accurate. We may use your contact details to respond and, where appropriate, follow up about services. Automated or abusive submissions may be blocked.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">4</span> Intellectual property</h2>
        <p>
          Branding, layout, text, graphics, and compilations on the Site are protected by intellectual property laws. You may not copy, scrape, or redistribute our content for commercial use without written permission, except as allowed by law or for personal, non-commercial reference.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">5</span> Third-party links</h2>
        <p>
          The Site may link to third-party websites or tools. We are not responsible for their content, policies, or practices. Your use of third-party sites is at your own risk.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">6</span> Disclaimers</h2>
        <p>
          The Site is provided “as is” to the extent permitted by law. We do not warrant uninterrupted or error-free operation. To the fullest extent permitted, we disclaim implied warranties arising from use of the Site or reliance on its content.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">7</span> Limitation of liability</h2>
        <p>
          To the extent permitted by law, PropUpdate Realty and its team shall not be liable for indirect, incidental, or consequential damages arising from use of the Site, or from decisions made based on Site content. Your sole remedy for dissatisfaction with the Site is to stop using it.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">8</span> Governing law</h2>
        <p>
          These terms are governed by the laws of India, subject to applicable jurisdiction rules. Disputes shall preferably be resolved through good-faith discussion; where required, courts at Bangalore, Karnataka, shall have exclusive jurisdiction, unless mandatory consumer law provides otherwise.
        </p>
      </section>

      <section class="pu-legal__block">
        <h2><span class="pu-legal__num">9</span> Contact</h2>
        <p>
          For questions about these terms, visit <a href="{{ route('pages.contact') }}">Contact us</a> or write to <a href="mailto:info@propupdate.com">info@propupdate.com</a>.
        </p>
      </section>

      <p class="pu-legal__footnote">
        Our <a href="{{ route('pages.privacy') }}">Privacy policy</a> explains how we handle personal data.
      </p>
    </div>
    @endif
  </div>
</article>
@endsection
