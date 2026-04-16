@php
  $p = $property;
  $configExtra = old('configuration_extra_text', $p->exists ? $p->configurationExtraAsPlainText() : '');
  $unitMix = old('unit_mix_text', $p->exists ? $p->unitMixAsPlainText() : '');
  $specs = old('specifications_text', $p->exists ? $p->specificationsAsPlainText() : '');
  $pros = old('expert_pros_text', $p->exists ? $p->expertProsAsPlainText() : '');
  $cons = old('expert_cons_text', $p->exists ? $p->expertConsAsPlainText() : '');
  $faqs = old('project_faqs_text', $p->exists ? $p->projectFaqsAsPlainText() : '');
@endphp
<div class="col-12">
	<div class="card radius-10 border border-primary border-opacity-25">
		<div class="card-body">
			<h5 class="mb-2">Project micro-site sections</h5>
			<p class="text-secondary small mb-4">Use these fields to build long-form project pages similar to typical real-estate listing sites: configuration table, unit mix &amp; price grid, specifications, plans, expert notes, FAQs.</p>

			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label">Developer / builder name</label>
					<input type="text" name="developer_name" class="form-control" value="{{ old('developer_name', $p->developer_name) }}" maxlength="255" placeholder="e.g. Prestige Estates">
				</div>
				<div class="col-md-6">
					<label class="form-label">RERA / registration # <span class="text-muted small">(optional)</span></label>
					<input type="text" name="rera_number" class="form-control" value="{{ old('rera_number', $p->rera_number) }}" maxlength="120">
				</div>
				<div class="col-12">
					<label class="form-label">About the developer</label>
					<textarea name="developer_description" class="form-control" rows="4" maxlength="20000" placeholder="Short builder profile for the detail page">{{ old('developer_description', $p->developer_description) }}</textarea>
				</div>
				<div class="col-md-4">
					<label class="form-label">Total land area <span class="text-muted small">(display)</span></label>
					<input type="text" name="project_land_area" class="form-control" value="{{ old('project_land_area', $p->project_land_area) }}" maxlength="120" placeholder="e.g. 5 Acres">
				</div>
				<div class="col-md-4">
					<label class="form-label">Total units</label>
					<input type="number" name="total_units" class="form-control" value="{{ old('total_units', $p->total_units) }}" min="0">
				</div>
				<div class="col-md-4">
					<label class="form-label">Unit variants summary</label>
					<input type="text" name="unit_variants_summary" class="form-control" value="{{ old('unit_variants_summary', $p->unit_variants_summary) }}" maxlength="120" placeholder="e.g. 2, 3 BHK">
				</div>
				<div class="col-12">
					<label class="form-label">Towers / blocks summary</label>
					<input type="text" name="towers_blocks_summary" class="form-control" value="{{ old('towers_blocks_summary', $p->towers_blocks_summary) }}" maxlength="500" placeholder="e.g. 2 towers, 2B+G+27 floors">
				</div>
				<div class="col-12">
					<label class="form-label">Extra configuration rows</label>
					<textarea name="configuration_extra_text" class="form-control font-monospace small" rows="4" maxlength="20000" placeholder="Label | Value (one per line)">{{ $configExtra }}</textarea>
					<p class="text-muted small mb-0 mt-1">Merged with auto rows from location, land area, units, towers, variants &amp; possession above.</p>
				</div>
				<div class="col-12">
					<label class="form-label">Unit mix &amp; pricing table</label>
					<textarea name="unit_mix_text" class="form-control font-monospace small" rows="5" maxlength="30000" placeholder="Unit type | Size (sq ft) | Price (optional)">{{ $unitMix }}</textarea>
					<p class="text-muted small mb-0 mt-1">Each line: three columns separated by <code>|</code> (price column optional).</p>
				</div>
				<div class="col-12">
					<label class="form-label">Construction specifications</label>
					<textarea name="specifications_text" class="form-control font-monospace small" rows="6" maxlength="30000" placeholder="Structure | RCC framed…">{{ $specs }}</textarea>
				</div>
				<div class="col-md-6">
					<label class="form-label">Expert positives <span class="text-muted small">(one per line)</span></label>
					<textarea name="expert_pros_text" class="form-control" rows="5" maxlength="10000" placeholder="USP bullets">{{ $pros }}</textarea>
				</div>
				<div class="col-md-6">
					<label class="form-label">Expert concerns <span class="text-muted small">(one per line)</span></label>
					<textarea name="expert_cons_text" class="form-control" rows="5" maxlength="10000">{{ $cons }}</textarea>
				</div>
				<div class="col-12">
					<label class="form-label">FAQs</label>
					@php
						$propertyFaqRows = [];
						$propertyFaqSource = trim((string) old('project_faqs_text', $faqs));
						if ($propertyFaqSource !== '') {
							$propertyFaqBlocks = preg_split('/\R---\R/', $propertyFaqSource) ?: [];
							foreach ($propertyFaqBlocks as $b) {
								$b = trim((string) $b);
								if ($b === '' || !str_contains($b, ':::')) continue;
								[$q, $a] = explode(':::', $b, 2);
								$q = trim((string) $q);
								$a = trim((string) $a);
								if ($q !== '') $propertyFaqRows[] = ['q' => $q, 'a' => $a];
							}
						}
						if (count($propertyFaqRows) === 0) {
							$propertyFaqRows[] = ['q' => '', 'a' => ''];
						}
					@endphp
					<div id="pu-property-faq-builder" class="border rounded p-2 bg-light-subtle">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<small class="text-muted">Click + to add FAQ item</small>
							<button type="button" class="btn btn-sm btn-primary" id="pu-property-faq-add">
								<i class="fa-solid fa-plus me-1"></i>Add FAQ
							</button>
						</div>
						<div id="pu-property-faq-rows">
							@foreach($propertyFaqRows as $idx => $row)
								<div class="border rounded p-2 mb-2 bg-white pu-faq-row">
									<div class="mb-2">
										<label class="form-label mb-1">Question</label>
										<input type="text" class="form-control pu-faq-question" value="{{ $row['q'] }}" placeholder="What is the possession timeline?">
									</div>
									<div class="mb-0">
										<label class="form-label mb-1">Answer</label>
										<textarea class="form-control pu-faq-answer" rows="3" placeholder="Expected handover timeline is...">{{ $row['a'] }}</textarea>
									</div>
									<div class="text-end mt-2">
										<button type="button" class="btn btn-sm btn-outline-danger pu-faq-remove">
											<i class="fa-solid fa-minus me-1"></i>Remove
										</button>
									</div>
								</div>
							@endforeach
						</div>
					</div>
					<textarea name="project_faqs_text" id="pu-property-faq-hidden" class="d-none">{{ $propertyFaqSource }}</textarea>
					<p class="text-muted small mb-0 mt-1">You can add/remove FAQs using buttons. Format is generated automatically.</p>
				</div>
				<div class="col-12">
					<label class="form-label">Price / costing disclaimer</label>
					<textarea name="price_disclaimer" class="form-control" rows="3" maxlength="10000" placeholder="Shown below unit price table">{{ old('price_disclaimer', $p->price_disclaimer) }}</textarea>
				</div>
				<div class="col-12">
					<label class="form-label">Google Maps / location link</label>
					<input type="text" name="maps_link_url" class="form-control" value="{{ old('maps_link_url', $p->maps_link_url) }}" maxlength="2000" placeholder="https://maps.google.com/...">
				</div>
				<div class="col-md-6">
					<label class="form-label">Master plan images</label>
					<input type="file" name="master_plans[]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple>
					<p class="text-muted small mt-1 mb-2">Up to {{ 12 }} new files per save; max {{ 24 }} stored.</p>
					@php
						$masterPaths = is_array($p->master_plan_paths ?? null) ? $p->master_plan_paths : [];
						if (is_string($p->master_plan_path ?? null) && $p->master_plan_path !== '') {
							array_unshift($masterPaths, $p->master_plan_path);
						}
						$masterPaths = array_values(array_unique(array_filter($masterPaths, fn ($path) => is_string($path) && $path !== '')));
					@endphp
					@if(count($masterPaths))
						<p class="small text-muted mb-1">Existing — check to remove:</p>
						<div class="row g-2">
							@foreach($masterPaths as $path)
								@php($mu = \App\Models\SiteSetting::resolvePublicUrl($path))
								@if($mu)
									<div class="col-6">
										<div class="border rounded p-1">
											<img src="{{ $mu }}" alt="" class="img-fluid rounded" style="max-height: 72px; width:100%; object-fit:cover;">
											<div class="form-check small mt-1">
												<input class="form-check-input" type="checkbox" name="remove_master_plan_paths[]" value="{{ $path }}" id="rm_mp_{{ md5($path) }}">
												<label class="form-check-label" for="rm_mp_{{ md5($path) }}">Remove</label>
											</div>
										</div>
									</div>
								@endif
							@endforeach
						</div>
					@endif
				</div>
				<div class="col-md-6">
					<label class="form-label">Floor plan images</label>
					<input type="file" name="floor_plans[]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple>
					<p class="text-muted small mt-1 mb-2">Up to {{ 12 }} new files per save; max {{ 24 }} stored.</p>
					@if(is_array($p->floor_plan_paths ?? null) && count($p->floor_plan_paths))
						<p class="small text-muted mb-1">Existing — check to remove:</p>
						<div class="row g-2">
							@foreach($p->floor_plan_paths as $path)
								@php($fu = \App\Models\SiteSetting::resolvePublicUrl($path))
								@if($fu)
									<div class="col-6">
										<div class="border rounded p-1">
											<img src="{{ $fu }}" alt="" class="img-fluid rounded" style="max-height: 72px; width:100%; object-fit:cover;">
											<div class="form-check small mt-1">
												<input class="form-check-input" type="checkbox" name="remove_floor_plan_paths[]" value="{{ $path }}" id="rm_fp_{{ md5($path) }}">
												<label class="form-check-label" for="rm_fp_{{ md5($path) }}">Remove</label>
											</div>
										</div>
									</div>
								@endif
							@endforeach
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var wrap = document.getElementById('pu-property-faq-builder');
  var rows = document.getElementById('pu-property-faq-rows');
  var addBtn = document.getElementById('pu-property-faq-add');
  var hidden = document.getElementById('pu-property-faq-hidden');
  if (!wrap || !rows || !addBtn || !hidden) return;

  function bindRemove(btn) {
    btn.addEventListener('click', function () {
      var row = btn.closest('.pu-faq-row');
      if (!row) return;
      if (rows.querySelectorAll('.pu-faq-row').length <= 1) {
        row.querySelector('.pu-faq-question').value = '';
        row.querySelector('.pu-faq-answer').value = '';
        return;
      }
      row.remove();
      serialize();
    });
  }

  function serialize() {
    var blocks = [];
    rows.querySelectorAll('.pu-faq-row').forEach(function (row) {
      var q = (row.querySelector('.pu-faq-question')?.value || '').trim();
      var a = (row.querySelector('.pu-faq-answer')?.value || '').trim();
      if (!q) return;
      blocks.push(q + ':::\n' + a);
    });
    hidden.value = blocks.join('\n---\n');
  }

  addBtn.addEventListener('click', function () {
    var div = document.createElement('div');
    div.className = 'border rounded p-2 mb-2 bg-white pu-faq-row';
    div.innerHTML =
      '<div class="mb-2"><label class="form-label mb-1">Question</label><input type="text" class="form-control pu-faq-question" placeholder="Enter question"></div>' +
      '<div class="mb-0"><label class="form-label mb-1">Answer</label><textarea class="form-control pu-faq-answer" rows="3" placeholder="Enter answer"></textarea></div>' +
      '<div class="text-end mt-2"><button type="button" class="btn btn-sm btn-outline-danger pu-faq-remove"><i class="fa-solid fa-minus me-1"></i>Remove</button></div>';
    rows.appendChild(div);
    bindRemove(div.querySelector('.pu-faq-remove'));
    serialize();
  });

  rows.querySelectorAll('.pu-faq-remove').forEach(bindRemove);
  rows.addEventListener('input', serialize);

  var form = wrap.closest('form');
  if (form) {
    form.addEventListener('submit', serialize);
  }
  serialize();
});
</script>
@endpush
