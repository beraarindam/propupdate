@php
  $qf = $project->quickFactsRows();
  $unitPricing = $project->unitPricingRows();
  $specRows = $project->specificationsRows();
  $pros = $project->expertProsList();
  $cons = $project->expertConsList();
  $faqs = $project->faqsList();
  $floorUrls = $project->floorPlanPublicUrls();
  $masterUrls = $project->masterPlanPublicUrls();
@endphp

<div class="pu-proj-microsite pu-proj-microsite--detail">
  @if($project->lastUpdatedNote())
    <p class="pu-proj-meta mb-4">
      <span class="pu-proj-meta__time pu-proj-meta__time--note">
        <i class="fa-regular fa-clock me-2" aria-hidden="true"></i>{{ $project->lastUpdatedNote() }}
      </span>
    </p>
  @elseif($project->published_at)
    <p class="pu-proj-meta mb-4">
      <time class="pu-proj-meta__time" datetime="{{ $project->published_at->toIso8601String() }}">
        <i class="fa-regular fa-calendar-check me-2" aria-hidden="true"></i>Updated {{ $project->published_at->format('F j, Y') }}
      </time>
    </p>
  @endif

  @if($project->summary)
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Overview</h2>
      <div class="pu-proj-lead">{!! nl2br(e($project->summary)) !!}</div>
      <div class="mt-3" id="pu-project-brochure">
        <button
          type="button"
          class="btn pu-proj-maps-btn"
          data-bs-toggle="modal"
          data-bs-target="#puProjectBrochureModal"
        >
          <i class="fa-regular fa-file-lines me-2" aria-hidden="true"></i>Free Download Brochure
        </button>
      </div>
      @if(session('project_brochure_status'))
        <div class="alert alert-success py-2 px-3 small mt-3 mb-0" role="status">{{ session('project_brochure_status') }}</div>
      @endif
    </section>
  @endif

  <div class="modal fade" id="puProjectBrochureModal" tabindex="-1" aria-labelledby="puProjectBrochureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title h5 mb-0" id="puProjectBrochureModalLabel">Free Download Brochure</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted small mb-3">Fill your details and our team will send brochure details.</p>
          <form method="post" action="{{ route('projects.brochure-request', $project) }}" novalidate>
            @csrf
            <div class="mb-3">
              <label for="pu-prj-brochure-name" class="form-label small fw-semibold text-muted mb-1">Name</label>
              <input type="text" class="form-control @error('brochure_name', 'projectBrochure') is-invalid @enderror" id="pu-prj-brochure-name" name="brochure_name" value="{{ old('brochure_name') }}" required maxlength="120" autocomplete="name">
              @error('brochure_name', 'projectBrochure')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="pu-prj-brochure-email" class="form-label small fw-semibold text-muted mb-1">Email</label>
              <input type="email" class="form-control @error('brochure_email', 'projectBrochure') is-invalid @enderror" id="pu-prj-brochure-email" name="brochure_email" value="{{ old('brochure_email') }}" required maxlength="255" autocomplete="email">
              @error('brochure_email', 'projectBrochure')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="pu-prj-brochure-phone" class="form-label small fw-semibold text-muted mb-1">Phone</label>
              <input type="tel" class="form-control @error('brochure_phone', 'projectBrochure') is-invalid @enderror" id="pu-prj-brochure-phone" name="brochure_phone" value="{{ old('brochure_phone') }}" required maxlength="32" autocomplete="tel">
              @error('brochure_phone', 'projectBrochure')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-0">
              <label for="pu-prj-brochure-message" class="form-label small fw-semibold text-muted mb-1">Message</label>
              <textarea class="form-control @error('brochure_message', 'projectBrochure') is-invalid @enderror" id="pu-prj-brochure-message" name="brochure_message" rows="4" required maxlength="4000" placeholder="Please share brochure and project details.">{{ old('brochure_message') }}</textarea>
              @error('brochure_message', 'projectBrochure')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn pu-pd-request__submit w-100 mt-3">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  @if($errors->projectBrochure->any() || session('project_brochure_status'))
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      var modalEl = document.getElementById('puProjectBrochureModal');
      if (!modalEl || typeof bootstrap === 'undefined') return;
      bootstrap.Modal.getOrCreateInstance(modalEl).show();
    });
    </script>
    @endpush
  @endif

  @if(count($qf))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Quick facts</h2>
      <div class="pu-proj-table-wrap">
        <table class="table table-sm align-middle pu-proj-table pu-proj-table--kv mb-0">
          <tbody>
            @foreach($qf as $row)
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

  @if(count($unitPricing))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Unit mix &amp; indicative pricing</h2>
      <div class="pu-proj-table-wrap">
        <table class="table table-sm align-middle pu-proj-table pu-proj-table--data mb-0">
          <thead>
            <tr>
              <th>Unit type</th>
              <th>Carpet / size</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach($unitPricing as $u)
              <tr>
                <td>{{ $u['unit_type'] ?? '' }}</td>
                <td>{{ $u['size_sqft'] ?? '' }}</td>
                <td>{{ !empty($u['price_label']) ? $u['price_label'] : '—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @if($project->priceDisclaimer())
        <div class="pu-proj-callout mt-3 mb-0">{!! nl2br(e($project->priceDisclaimer())) !!}</div>
      @endif
    </section>
  @elseif($project->priceDisclaimer())
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Pricing notes</h2>
      <div class="pu-proj-callout mb-0">{!! nl2br(e($project->priceDisclaimer())) !!}</div>
    </section>
  @endif

  @if($project->maps_link_url)
    <section class="pu-proj-section pu-proj-card pu-proj-card--maps mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Location</h2>
      @if($project->locationAddressLine())
        <p class="text-muted mb-3 mb-md-4">{{ $project->locationAddressLine() }}</p>
      @endif
      <p class="mb-0">
        <a href="{{ $project->maps_link_url }}" class="pu-proj-maps-btn" target="_blank" rel="noopener noreferrer">
          <i class="fa-solid fa-map-location-dot me-2" aria-hidden="true"></i>Open in Google Maps
        </a>
      </p>
    </section>
  @elseif($project->locationAddressLine())
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Location</h2>
      <p class="mb-0 text-muted">{{ $project->locationAddressLine() }}</p>
    </section>
  @endif

  @if(count($masterUrls) || count($floorUrls))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5" id="pu-project-plans">
      <h2 class="pu-proj-heading h4 mb-3">Plans</h2>
      @if(session('project_plan_status'))
        <div class="alert alert-success py-2 px-3 small mb-3" role="status">{{ session('project_plan_status') }}</div>
      @endif
      @if(count($masterUrls))
        <h3 class="pu-proj-subhead">Master plans</h3>
        <div class="mb-4">
          <a
            href="{{ $masterUrls[0] }}"
            class="pu-proj-plan-thumb pu-proj-zoom d-block"
            data-pu-plan-open
            data-plan-type="Master Plan"
            data-plan-url="{{ $masterUrls[0] }}"
          >
            <img src="{{ $masterUrls[0] }}" alt="Master plan — {{ e(\Illuminate\Support\Str::limit($project->title, 60)) }}" class="w-100 pu-proj-plan-thumb__img">
          </a>
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
                data-pu-plan-open
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

  <div class="modal fade" id="puProjectPlanModal" tabindex="-1" aria-labelledby="puProjectPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title h5 mb-0" id="puProjectPlanModalLabel">Request Master Plan Access</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted small mb-3">Fill your details and our team will share this master plan with you.</p>
          <form method="post" action="{{ route('projects.plan-request', $project) }}" novalidate>
            @csrf
            <input type="hidden" name="plan_type" id="pu-project-plan-type" value="{{ old('plan_type') }}">
            <input type="hidden" name="plan_url" id="pu-project-plan-url" value="{{ old('plan_url') }}">
            <div class="mb-3">
              <label for="pu-project-plan-name" class="form-label small fw-semibold text-muted mb-1">Name</label>
              <input type="text" class="form-control @error('plan_name', 'projectPlanAsset') is-invalid @enderror" id="pu-project-plan-name" name="plan_name" value="{{ old('plan_name') }}" required maxlength="120" autocomplete="name">
              @error('plan_name', 'projectPlanAsset')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="pu-project-plan-email" class="form-label small fw-semibold text-muted mb-1">Email</label>
              <input type="email" class="form-control @error('plan_email', 'projectPlanAsset') is-invalid @enderror" id="pu-project-plan-email" name="plan_email" value="{{ old('plan_email') }}" required maxlength="255" autocomplete="email">
              @error('plan_email', 'projectPlanAsset')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="pu-project-plan-phone" class="form-label small fw-semibold text-muted mb-1">Phone</label>
              <input type="tel" class="form-control @error('plan_phone', 'projectPlanAsset') is-invalid @enderror" id="pu-project-plan-phone" name="plan_phone" value="{{ old('plan_phone') }}" required maxlength="32" autocomplete="tel">
              @error('plan_phone', 'projectPlanAsset')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-0">
              <label for="pu-project-plan-message" class="form-label small fw-semibold text-muted mb-1">Message</label>
              <textarea class="form-control @error('plan_message', 'projectPlanAsset') is-invalid @enderror" id="pu-project-plan-message" name="plan_message" rows="4" required maxlength="4000" placeholder="Please share this master plan and details.">{{ old('plan_message') }}</textarea>
              @error('plan_message', 'projectPlanAsset')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn pu-pd-request__submit w-100 mt-3">Submit request</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('puProjectPlanModal');
    if (!modalEl || typeof bootstrap === 'undefined' || !bootstrap.Modal) return;
    var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    var typeInput = document.getElementById('pu-project-plan-type');
    var urlInput = document.getElementById('pu-project-plan-url');
    document.querySelectorAll('[data-pu-plan-open]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        if (typeInput) typeInput.value = el.getAttribute('data-plan-type') || 'Master Plan';
        if (urlInput) urlInput.value = el.getAttribute('data-plan-url') || '';
        modal.show();
      });
    });
    @if($errors->projectPlanAsset->any())
      modal.show();
    @endif
  });
  </script>
  @endpush

  @if($project->body)
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">More about this project</h2>
      <div class="pu-blog-article__content pu-page-body-cms pu-proj-body">{!! $project->body !!}</div>
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

  @if(count($project->amenitiesList()))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Amenities</h2>
      <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-3 list-unstyled pu-proj-amenities mb-0">
        @foreach($project->amenitiesList() as $am)
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

  @if($project->developer_name || $project->developerAboutHtml() || $project->rera_number)
    <section class="pu-proj-section pu-proj-card pu-proj-developer mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">About the developer</h2>
      @if($project->developer_name)
        <p class="pu-proj-developer__name mb-2">{{ $project->developer_name }}</p>
      @endif
      @if($project->rera_number)
        <p class="pu-proj-developer__rera small mb-3"><i class="fa-solid fa-shield-halved me-2" aria-hidden="true"></i>RERA: {{ $project->rera_number }}</p>
      @endif
      @if($project->developerAboutHtml())
        <div class="pu-proj-developer__bio pu-page-body-cms">{!! $project->developerAboutHtml() !!}</div>
      @endif
    </section>
  @endif

  @if(count($faqs))
    <section class="pu-proj-section pu-proj-card mb-4 mb-lg-5">
      <h2 class="pu-proj-heading h4 mb-3">Frequently asked questions</h2>
      <div class="accordion accordion-flush pu-proj-faq" id="projectFaqAccordion">
        @foreach($faqs as $idx => $faq)
          @php($fid = 'pfaq-'.$project->id.'-'.$idx)
          <div class="accordion-item pu-proj-faq__item">
            <h3 class="accordion-header m-0" id="heading-{{ $fid }}">
              <button class="accordion-button collapsed pu-proj-faq__btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $fid }}" aria-expanded="false" aria-controls="collapse-{{ $fid }}">
                {{ $faq['question'] ?? 'Question' }}
              </button>
            </h3>
            <div id="collapse-{{ $fid }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $fid }}" data-bs-parent="#projectFaqAccordion">
              <div class="accordion-body pu-proj-faq__body">{!! nl2br(e($faq['answer'] ?? '')) !!}</div>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  @endif
</div>
