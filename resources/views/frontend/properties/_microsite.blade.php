@php
  $cfgRows = $property->allConfigurationRows();
  $unitMix = is_array($property->unit_mix) ? $property->unit_mix : [];
  $specRows = is_array($property->specifications) ? $property->specifications : [];
  $pros = is_array($property->expert_pros) ? $property->expert_pros : [];
  $cons = is_array($property->expert_cons) ? $property->expert_cons : [];
  $faqs = is_array($property->project_faqs) ? $property->project_faqs : [];
  $floorUrls = $property->floorPlanPublicUrls();
  $masterUrl = $property->masterPlanUrl();
@endphp

<div class="pu-proj-microsite">
  @if($property->published_at)
    <p class="pu-proj-meta text-muted small mb-4">
      <time datetime="{{ $property->published_at->toIso8601String() }}">Updated {{ $property->published_at->format('F j, Y') }}</time>
    </p>
  @endif

  @if($property->summary)
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">Overview</h2>
      <div class="pu-proj-lead text-secondary">{!! nl2br(e($property->summary)) !!}</div>
    </section>
  @endif

  @if(count($cfgRows))
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">Configuration</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle pu-proj-table">
          <tbody>
            @foreach($cfgRows as $row)
              <tr>
                <th scope="row" class="bg-light text-dark w-25">{{ $row['label'] }}</th>
                <td>{{ $row['value'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>
  @endif

  @if(count($unitMix))
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">Unit mix &amp; indicative pricing</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm align-middle pu-proj-table">
          <thead class="table-light">
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
        <div class="alert alert-light border small mb-0 mt-3">{!! nl2br(e($property->price_disclaimer)) !!}</div>
      @endif
    </section>
  @elseif($property->price_disclaimer)
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">Pricing notes</h2>
      <div class="alert alert-light border small mb-0">{!! nl2br(e($property->price_disclaimer)) !!}</div>
    </section>
  @endif

  @if($property->maps_link_url)
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">Location</h2>
      <p class="mb-2">
        <a href="{{ $property->maps_link_url }}" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener noreferrer">Open in Google Maps</a>
      </p>
    </section>
  @endif

  @if($masterUrl || count($floorUrls))
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">Plans</h2>
      @if($masterUrl)
        <h3 class="h6 text-uppercase text-muted mt-3 mb-2">Master plan</h3>
        <div class="border rounded overflow-hidden mb-4">
          <img src="{{ $masterUrl }}" alt="Master plan" class="img-fluid w-100" style="max-height: 520px; object-fit: contain; background: #f8fafc;">
        </div>
      @endif
      @if(count($floorUrls))
        <h3 class="h6 text-uppercase text-muted mb-2">Floor plans</h3>
        <div class="row g-3">
          @foreach($floorUrls as $fu)
            <div class="col-6 col-md-4 col-lg-3">
              <a href="{{ $fu }}" class="d-block border rounded overflow-hidden pu-proj-zoom" target="_blank" rel="noopener noreferrer">
                <img src="{{ $fu }}" alt="Floor plan" class="w-100" style="max-height: 200px; object-fit: cover;">
              </a>
            </div>
          @endforeach
        </div>
      @endif
    </section>
  @endif

  @if($property->description)
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">More about this project</h2>
      <div class="pu-blog-article__content pu-page-body-cms">{!! $property->description !!}</div>
    </section>
  @endif

  @if(count($specRows))
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">Specifications</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle pu-proj-table">
          <tbody>
            @foreach($specRows as $row)
              @if(!empty($row['label']))
                <tr>
                  <th scope="row" class="bg-light w-25">{{ $row['label'] }}</th>
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
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">Amenities</h2>
      <ul class="row row-cols-1 row-cols-md-2 row-cols-lg-3 small list-unstyled pu-proj-amenities">
        @foreach($property->amenities as $am)
          <li class="col mb-2 px-2"><span class="text-success me-1">✓</span>{{ $am }}</li>
        @endforeach
      </ul>
    </section>
  @endif

  @if(count($pros) || count($cons))
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">Expert notes</h2>
      <div class="row g-4">
        @if(count($pros))
          <div class="col-md-6">
            <h3 class="h6 text-success">Highlights</h3>
            <ul class="small mb-0">
              @foreach($pros as $li)
                <li class="mb-2">{{ $li }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        @if(count($cons))
          <div class="col-md-6">
            <h3 class="h6 text-warning">Points to consider</h3>
            <ul class="small mb-0">
              @foreach($cons as $li)
                <li class="mb-2">{{ $li }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    </section>
  @endif

  @if($property->developer_name || $property->developer_description)
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">About the developer</h2>
      @if($property->developer_name)
        <p class="fw-semibold mb-2">{{ $property->developer_name }}</p>
      @endif
      @if($property->rera_number)
        <p class="small text-muted mb-2">RERA: {{ $property->rera_number }}</p>
      @endif
      @if($property->developer_description)
        <div class="small text-secondary">{!! nl2br(e($property->developer_description)) !!}</div>
      @endif
    </section>
  @endif

  @if(count($faqs))
    <section class="pu-proj-section mb-5">
      <h2 class="h4 pu-proj-heading">Frequently asked questions</h2>
      <div class="accordion accordion-flush pu-proj-faq" id="propertyFaqAccordion">
        @foreach($faqs as $idx => $faq)
          @php($fid = 'faq-'.$property->id.'-'.$idx)
          <div class="accordion-item border">
            <h3 class="accordion-header m-0" id="heading-{{ $fid }}">
              <button class="accordion-button collapsed py-3 small" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $fid }}" aria-expanded="false" aria-controls="collapse-{{ $fid }}">
                {{ $faq['question'] ?? 'Question' }}
              </button>
            </h3>
            <div id="collapse-{{ $fid }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $fid }}" data-bs-parent="#propertyFaqAccordion">
              <div class="accordion-body small">{!! nl2br(e($faq['answer'] ?? '')) !!}</div>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  @endif
</div>
