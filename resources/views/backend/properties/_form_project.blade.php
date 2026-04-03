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
					<textarea name="project_faqs_text" class="form-control font-monospace small" rows="8" maxlength="50000" placeholder="Question:::&#10;Answer text&#10;---&#10;Next question:::&#10;Answer">{{ $faqs }}</textarea>
					<p class="text-muted small mb-0 mt-1">Each FAQ: question, then <code>:::</code>, then answer. Separate FAQs with a line containing only <code>---</code>.</p>
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
					<label class="form-label">Master plan image</label>
					@if($p->masterPlanUrl())
						<div class="mb-2">
							<img src="{{ $p->masterPlanUrl() }}" alt="" class="img-thumbnail rounded" style="max-height: 160px; object-fit: cover;">
						</div>
					@endif
					<input type="file" name="master_plan_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
					@if($p->master_plan_path)
						<div class="form-check mt-2">
							<input class="form-check-input" type="checkbox" name="remove_master_plan" value="1" id="rm_master_plan" @checked(old('remove_master_plan'))>
							<label class="form-check-label" for="rm_master_plan">Remove master plan file</label>
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
