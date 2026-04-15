@php
  $cfgRows = $property->allConfigurationRows();
  $unitMix = is_array($property->unit_mix) ? $property->unit_mix : [];
  $specRows = is_array($property->specifications) ? $property->specifications : [];
  $pros = is_array($property->expert_pros) ? $property->expert_pros : [];
  $cons = is_array($property->expert_cons) ? $property->expert_cons : [];
  $faqs = is_array($property->project_faqs) ? $property->project_faqs : [];
  $floorUrls = $property->floorPlanPublicUrls();
  $masterUrls = $property->masterPlanPublicUrls();
@endphp

<div class="pu-proj-microsite pu-proj-microsite--detail">
  @if($property->published_at)
    <p class="pu-proj-meta mb-4">
      <time class="pu-proj-meta__time" datetime="{{ $property->published_at->toIso8601String() }}">
        <i class="fa-regular fa-calendar-check me-2" aria-hidden="true"></i>Updated {{ $property->published_at->format('F j, Y') }}
      </time>
    </p>
  @endif

  @if($property->summary)
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Overview</h2>
      <div class="pu-proj-lead">{!! nl2br(e($property->summary)) !!}</div>
    </section>
  @endif

  @if(count($cfgRows))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Configuration</h2>
      <div class="pu-proj-table-wrap">
        <table class="table table-sm align-middle pu-proj-table pu-proj-table--kv mb-0">
          <tbody>
            @foreach($cfgRows as $row)
              <tr>
                <th scope="row">{{ $row['label'] }}</th>
                <td>{{ $row['value'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>
  @endif

  @if(count($unitMix))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Unit mix &amp; indicative pricing</h2>
      <div class="pu-proj-table-wrap">
        <table class="table table-sm align-middle pu-proj-table pu-proj-table--data mb-0">
          <thead>
            <tr>
              <th>Unit type</th>
              <th>Size (sq.ft)</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach($unitMix as $u)
              <tr>
                <td>{{ $u['unit_type'] ?? '' }}</td>
                <td>{{ $u['size_sqft'] ?? '' }}</td>
                <td>{{ !empty($u['price_label']) ? $u['price_label'] : '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($property->price_disclaimer)
        <div class="pu-proj-callout mt-3 mb-0">{!! nl2br(e($property->price_disclaimer)) !!}</div>
      @endif
      <div class="mt-3" id="pu-proj-brochure">
        @if(session('property_brochure_status'))
          <div class="alert alert-success py-2 px-3 small mb-3" role="status">{{ session('property_brochure_status') }}</div>
        @endif
        <button type="button" class="pu-proj-maps-btn" data-bs-toggle="modal" data-bs-target="#puBrochureModal">
          <i class="fa-solid fa-file-arrow-down me-2" aria-hidden="true"></i>Download free brochure
        </button>
      </div>
    </section>
  @elseif($property->price_disclaimer)
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Pricing notes</h2>
      <div class="pu-proj-callout mb-0">{!! nl2br(e($property->price_disclaimer)) !!}</div>
      <div class="mt-3" id="pu-proj-brochure">
        @if(session('property_brochure_status'))
          <div class="alert alert-success py-2 px-3 small mb-3" role="status">{{ session('property_brochure_status') }}</div>
        @endif
        <button type="button" class="pu-proj-maps-btn" data-bs-toggle="modal" data-bs-target="#puBrochureModal">
          <i class="fa-solid fa-file-arrow-down me-2" aria-hidden="true"></i>Download free brochure
        </button>
      </div>
    </section>
  @else
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5" id="pu-proj-brochure">
      @if(session('property_brochure_status'))
        <div class="alert alert-success py-2 px-3 small mb-3" role="status">{{ session('property_brochure_status') }}</div>
      @endif
      <button type="button" class="pu-proj-maps-btn" data-bs-toggle="modal" data-bs-target="#puBrochureModal">
        <i class="fa-solid fa-file-arrow-down me-2" aria-hidden="true"></i>Download free brochure
      </button>
    </section>
  @endif

  @if($property->maps_link_url)
    <section class="pu-proj-section pu-proj-card pu-proj-card--maps mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Location</h2>
      <p class="mb-0">
        <a href="{{ $property->maps_link_url }}" class="pu-proj-maps-btn" target="_blank" rel="noopener noreferrer">
          <i class="fa-solid fa-map-location-dot me-2" aria-hidden="true"></i>Open in Google Maps
        </a>
      </p>
    </section>
  @endif

  @if(count($masterUrls) || count($floorUrls))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5" id="pu-property-plans">
      <h2 class="pu-proj-heading h4 mb-3">Plans</h2>
      @if(session('property_plan_status'))
        <div class="alert alert-success py-2 px-3 small mb-3" role="status">{{ session('property_plan_status') }}</div>
      @endif
      @if(count($masterUrls))
        <h3 class="pu-proj-subhead">Master plans</h3>
        <div class="row g-3 mb-4">
          @foreach($masterUrls as $mu)
            <div class="col-6 col-md-4 col-lg-3">
              <a
                href="{{ $mu }}"
                class="pu-proj-plan-thumb pu-proj-zoom d-block"
                data-pu-property-plan-open
                data-plan-type="Master Plan"
                data-plan-url="{{ $mu }}"
              >
                <img src="{{ $mu }}" alt="Master plan" class="w-100 pu-proj-plan-thumb__img">
              </a>
            </div>
          @endforeach
        </div>
      @endif
      @if(count($floorUrls))
        <h3 class="pu-proj-subhead">Floor plans</h3>
        <div class="row g-3">
          @foreach($floorUrls as $fu)
            <div class="col-6 col-md-4 col-lg-3">
              <a
                href="{{ $fu }}"
                class="pu-proj-plan-thumb pu-proj-zoom d-block"
                data-pu-property-plan-open
                data-plan-type="Floor Plan"
                data-plan-url="{{ $fu }}"
              >
                <img src="{{ $fu }}" alt="Floor plan" class="w-100 pu-proj-plan-thumb__img">
              </a>
            </div>
          @endforeach
        </div>
      @endif
    </section>
  @endif

  <div class="modal fade" id="puPropertyPlanModal" tabindex="-1" aria-labelledby="puPropertyPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header">
          <h2 class="modal-title h5 mb-0" id="puPropertyPlanModalLabel">Request Plan Access</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('properties.plan-request', $property) }}" novalidate>
          @csrf
          <input type="hidden" name="plan_type" id="pu-property-plan-type" value="{{ old('plan_type') }}">
          <input type="hidden" name="plan_url" id="pu-property-plan-url" value="{{ old('plan_url') }}">
          <div class="modal-body">
            <p class="small text-muted mb-3">Share your details and our team will send this plan.</p>
            <div class="mb-3">
              <label for="pu-property-plan-name" class="form-label small fw-semibold text-muted mb-1">Name</label>
              <input type="text" class="form-control @error('plan_name', 'propertyPlanAsset') is-invalid @enderror" id="pu-property-plan-name" name="plan_name" value="{{ old('plan_name') }}" required maxlength="120" autocomplete="name">
              @error('plan_name', 'propertyPlanAsset')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="pu-property-plan-email" class="form-label small fw-semibold text-muted mb-1">Email</label>
              <input type="email" class="form-control @error('plan_email', 'propertyPlanAsset') is-invalid @enderror" id="pu-property-plan-email" name="plan_email" value="{{ old('plan_email') }}" required maxlength="255" autocomplete="email">
              @error('plan_email', 'propertyPlanAsset')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="pu-property-plan-phone" class="form-label small fw-semibold text-muted mb-1">Phone</label>
              <input type="tel" class="form-control @error('plan_phone', 'propertyPlanAsset') is-invalid @enderror" id="pu-property-plan-phone" name="plan_phone" value="{{ old('plan_phone') }}" required maxlength="32" autocomplete="tel">
              @error('plan_phone', 'propertyPlanAsset')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-0">
              <label for="pu-property-plan-message" class="form-label small fw-semibold text-muted mb-1">Message</label>
              <textarea class="form-control @error('plan_message', 'propertyPlanAsset') is-invalid @enderror" id="pu-property-plan-message" name="plan_message" rows="4" required maxlength="4000" placeholder="Please share this plan and details.">{{ old('plan_message') }}</textarea>
              @error('plan_message', 'propertyPlanAsset')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn pu-pd-request__submit">Submit request</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('puPropertyPlanModal');
    if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap.Modal) return;
    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    var typeInput = document.getElementById('pu-property-plan-type');
    var urlInput = document.getElementById('pu-property-plan-url');
    document.querySelectorAll('[data-pu-property-plan-open]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        if (typeInput) typeInput.value = el.getAttribute('data-plan-type') || 'Plan';
        if (urlInput) urlInput.value = el.getAttribute('data-plan-url') || '';
        modal.show();
      });
    });
    @if($errors->propertyPlanAsset->any())
      modal.show();
    @endif
  });
  </script>
  @endpush

  @if($property->description)
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">More about this project</h2>
      <div class="pu-blog-article__content pu-page-body-cms pu-proj-body">{!! $property->description !!}</div>
    </section>
  @endif

  @if(count($specRows))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Specifications</h2>
      <div class="pu-proj-table-wrap">
        <table class="table table-sm align-middle pu-proj-table pu-proj-table--kv mb-0">
          <tbody>
            @foreach($specRows as $row)
              @if(!empty($row['label']))
                <tr>
                  <th scope="row">{{ $row['label'] }}</th>
                  <td>{{ $row['value'] ?? '' }}</td>
                </tr>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
    </section>
  @endif

  @if(is_array($property->amenities) && count($property->amenities))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Amenities</h2>
      <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-3 list-unstyled pu-proj-amenities mb-0">
        @foreach($property->amenities as $am)
          <li class="col mb-2">
            <span class="pu-proj-amenity">
              <span class="pu-proj-amenity__icon" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
              <span class="pu-proj-amenity__text">{{ $am }}</span>
            </span>
          </li>
        @endforeach
      </ul>
    </section>
  @endif

  @if(count($pros) || count($cons))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Expert notes</h2>
      <div class="row g-3 g-lg-4">
        @if(count($pros))
          <div class="col-md-6">
            <div class="pu-proj-insight pu-proj-insight--pro h-100">
              <h3 class="pu-proj-insight__title"><i class="fa-solid fa-circle-check me-2" aria-hidden="true"></i>Highlights</h3>
              <ul class="pu-proj-insight__list mb-0">
                @foreach($pros as $li)
                  <li>{{ $li }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif
        @if(count($cons))
          <div class="col-md-6">
            <div class="pu-proj-insight pu-proj-insight--con h-100">
              <h3 class="pu-proj-insight__title"><i class="fa-solid fa-circle-exclamation me-2" aria-hidden="true"></i>Points to consider</h3>
              <ul class="pu-proj-insight__list mb-0">
                @foreach($cons as $li)
                  <li>{{ $li }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif
      </div>
    </section>
  @endif

  @if($property->developer_name || $property->developer_description)
    <section class="pu-proj-section pu-proj-card pu-proj-developer mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">About the developer</h2>
      @if($property->developer_name)
        <p class="pu-proj-developer__name mb-2">{{ $property->developer_name }}</p>
      @endif
      @if($property->rera_number)
        <p class="pu-proj-developer__rera small mb-3"><i class="fa-solid fa-shield-halved me-2" aria-hidden="true"></i>RERA: {{ $property->rera_number }}</p>
      @endif
      @if($property->developer_description)
        <div class="pu-proj-developer__bio">{!! nl2br(e($property->developer_description)) !!}</div>
      @endif
    </section>
  @endif

  @if(count($faqs))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Frequently asked questions</h2>
      <div class="accordion accordion-flush pu-proj-faq" id="propertyFaqAccordion">
        @foreach($faqs as $idx => $faq)
          @php($fid = 'faq-'.$property->id.'-'.$idx)
          <div class="accordion-item pu-proj-faq__item">
            <h3 class="accordion-header m-0" id="heading-{{ $fid }}">
              <button class="accordion-button collapsed pu-proj-faq__btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $fid }}" aria-expanded="false" aria-controls="collapse-{{ $fid }}">
                {{ $faq['question'] ?? 'Question' }}
              </button>
            </h3>
            <div id="collapse-{{ $fid }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $fid }}" data-bs-parent="#propertyFaqAccordion">
              <div class="accordion-body pu-proj-faq__body">{!! nl2br(e($faq['answer'] ?? '')) !!}</div>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  @endif
</div>
