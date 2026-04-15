{{-- Public /projects/{slug} layout — each block maps to a section on the live detail page --}}
<div class="col-12" id="proj-detail-sections">
	<div class="alert alert-primary border-0 shadow-sm mb-4">
		<div class="d-flex flex-wrap align-items-start gap-3">
			<div class="flex-grow-1">
				<h6 class="alert-heading fw-bold mb-1">Detail page content</h6>
				<p class="mb-0 small">These fields build the <strong>public project page</strong> (quick facts table, pricing grid, amenities, location, plans, FAQs, developer, enquiry sidebar). Use the <strong>Content</strong> card above for <strong>Summary</strong> (overview teaser) and <strong>Body</strong> (long “more about this project” article).</p>
			</div>
			<a href="#proj-section-content-top" class="btn btn-sm btn-outline-primary text-nowrap">↑ Back to content</a>
		</div>
	</div>
</div>

<div class="col-12 mb-4">
	<div class="card radius-10 border shadow-none border-primary border-opacity-25">
		<div class="card-header bg-primary bg-opacity-10 py-3 border-bottom">
			<span class="badge bg-primary me-2">A</span>
			<strong class="align-middle">Location, map &amp; RERA</strong>
			<span class="text-muted small fw-normal ms-2 d-none d-md-inline">→ “Location” block + sidebar + developer strip</span>
		</div>
		<div class="card-body">
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label">Google Maps link <span class="text-muted small fw-normal">(recommended for “Open in Maps”)</span></label>
					<input type="text" name="maps_link_url" class="form-control" value="{{ old('maps_link_url', $project->maps_link_url) }}" maxlength="2000" placeholder="https://maps.google.com/...">
					<p class="text-muted small mb-0 mt-1">Powers the <strong>Open in Google Maps</strong> button on the detail page.</p>
				</div>
				<div class="col-md-6">
					<label class="form-label">RERA registration</label>
					<input type="text" name="rera_number" class="form-control" value="{{ old('rera_number', $project->rera_number) }}" maxlength="120" placeholder="e.g. PRM/KA/...">
					<p class="text-muted small mb-0 mt-1">Shown under developer name and in the sidebar when quick facts are empty.</p>
				</div>
				<div class="col-12">
					<label class="form-label">Full address / location line</label>
					<input type="text" name="location_address" class="form-control" value="{{ old('location_address', $project->extra('location_address') ? (string) $project->extra('location_address') : '') }}" maxlength="500" placeholder="Devanahalli, North Bangalore, Karnataka">
					<p class="text-muted small mb-0 mt-1">Long line under <strong>Location</strong> and in the sidebar (if filled).</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-12 mb-4">
	<div class="card radius-10 border shadow-none">
		<div class="card-header bg-dark bg-opacity-10 py-3 border-bottom">
			<span class="badge bg-dark me-2">B</span>
			<strong class="align-middle">Quick facts &amp; pricing table</strong>
			<span class="text-muted small fw-normal ms-2 d-none d-md-inline">→ main column + sidebar quick facts</span>
		</div>
		<div class="card-body">
			<div class="mb-4">
				<label class="form-label">Quick facts <span class="text-muted small fw-normal">(one per line)</span></label>
				<div class="font-monospace small text-muted mb-1">Format: <code>Label | Value</code></div>
				<textarea name="quick_facts_text" class="form-control font-monospace small" rows="7" maxlength="20000" placeholder="Total land area | 80 acres&#10;Total units | 1200&#10;Possession | Dec 2028&#10;Towers | 4">{{ old('quick_facts_text', $project->quickFactsAsPlainText()) }}</textarea>
			</div>
			<div class="mb-4">
				<label class="form-label">Unit mix &amp; indicative pricing</label>
				<div class="font-monospace small text-muted mb-1">Format: <code>Unit type | Carpet / size | Price</code></div>
				<textarea name="unit_pricing_text" class="form-control font-monospace small" rows="7" maxlength="20000" placeholder="3 BHK | 1650 sq.ft | ₹ 1.25 Cr onwards&#10;4 BHK | 2200 sq.ft | On request">{{ old('unit_pricing_text', $project->unitPricingAsPlainText()) }}</textarea>
			</div>
			<div class="mb-0">
				<label class="form-label">Pricing disclaimer</label>
				<textarea name="price_disclaimer" class="form-control" rows="3" maxlength="8000" placeholder="Prices indicative; subject to availability and taxes.">{{ old('price_disclaimer', $project->priceDisclaimer() ?? '') }}</textarea>
				<p class="text-muted small mb-0 mt-1">Shown below the pricing table.</p>
			</div>
		</div>
	</div>
</div>

<div class="col-12 mb-4">
	<div class="card radius-10 border shadow-none">
		<div class="card-header bg-success bg-opacity-10 py-3 border-bottom">
			<span class="badge bg-success me-2">C</span>
			<strong class="align-middle">Amenities &amp; specifications</strong>
			<span class="text-muted small fw-normal ms-2 d-none d-md-inline">→ grid + specs table on detail page</span>
		</div>
		<div class="card-body">
			<div class="mb-4">
				<label class="form-label">Amenities</label>
				<textarea name="amenities_text" class="form-control" rows="5" maxlength="20000" placeholder="Clubhouse&#10;Swimming pool&#10;Jogging track">{{ old('amenities_text', implode("\n", $project->amenitiesList())) }}</textarea>
				<p class="text-muted small mb-0 mt-1">One per line or comma-separated.</p>
			</div>
			<div class="mb-0">
				<label class="form-label">Specifications</label>
				<div class="font-monospace small text-muted mb-1">Format: <code>Heading | Detail</code> per line</div>
				<textarea name="specifications_text" class="form-control font-monospace small" rows="7" maxlength="20000" placeholder="Structure | RCC framed structure&#10;Flooring | Vitrified tiles in living areas">{{ old('specifications_text', $project->specificationsAsPlainText()) }}</textarea>
			</div>
		</div>
	</div>
</div>

<div class="col-12 mb-4">
	<div class="card radius-10 border shadow-none">
		<div class="card-header bg-warning bg-opacity-25 py-3 border-bottom">
			<span class="badge bg-warning text-dark me-2">D</span>
			<strong class="align-middle">Expert notes &amp; FAQs</strong>
			<span class="text-muted small fw-normal ms-2 d-none d-md-inline">→ highlights / watch-outs + accordion</span>
		</div>
		<div class="card-body">
			<div class="row g-3 mb-4">
				<div class="col-md-6">
					<label class="form-label">Expert highlights (pros)</label>
					<textarea name="expert_pros_text" class="form-control" rows="6" maxlength="12000" placeholder="Strong connectivity to airport&#10;Reputed developer track record">{{ old('expert_pros_text', $project->expertProsAsPlainText()) }}</textarea>
					<p class="text-muted small mb-0 mt-1">One bullet per line.</p>
				</div>
				<div class="col-md-6">
					<label class="form-label">Points to consider (cons)</label>
					<textarea name="expert_cons_text" class="form-control" rows="6" maxlength="12000" placeholder="Distance from city core&#10;Ongoing infrastructure work nearby">{{ old('expert_cons_text', $project->expertConsAsPlainText()) }}</textarea>
					<p class="text-muted small mb-0 mt-1">One bullet per line.</p>
				</div>
			</div>
			<div class="mb-0">
				<label class="form-label">FAQs</label>
				<div class="font-monospace small text-muted mb-1">Each FAQ: question line ending with <code>:::</code> then answer (multiple lines). Separate FAQs with a line of <code>---</code>.</div>
				<textarea name="project_faqs_text" class="form-control font-monospace small" rows="10" maxlength="50000" placeholder="What is the possession timeline?:::&#10;Target handover is …&#10;---&#10;Is this RERA registered?:::&#10;Yes, registration no. …">{{ old('project_faqs_text', $project->projectFaqsAsPlainText()) }}</textarea>
			</div>
		</div>
	</div>
</div>

<div class="col-12 mb-4">
	<div class="card radius-10 border shadow-none">
		<div class="card-header bg-info bg-opacity-10 py-3 border-bottom">
			<span class="badge bg-info text-dark me-2">E</span>
			<strong class="align-middle">Developer / builder story</strong>
			<span class="text-muted small fw-normal ms-2 d-none d-md-inline">→ “About the developer” (name &amp; RERA also come from Content card + field A)</span>
		</div>
		<div class="card-body">
			<label class="form-label">About the developer <span class="text-muted small fw-normal">(rich text)</span></label>
			<textarea name="developer_about_html" id="project_developer_about" class="form-control" rows="10">{{ old('developer_about_html', $project->developerAboutHtml() ?? '') }}</textarea>
			<p class="text-muted small mb-0 mt-1">Use the <strong>Developer</strong> name from the Content card above; this block adds the long bio.</p>
		</div>
	</div>
</div>

<div class="col-12 mb-4">
	<div class="card radius-10 border shadow-none">
		<div class="card-header bg-secondary bg-opacity-10 py-3 border-bottom">
			<span class="badge bg-secondary me-2">F</span>
			<strong class="align-middle">Sidebar enquiry card &amp; freshness</strong>
			<span class="text-muted small fw-normal ms-2 d-none d-md-inline">→ right column CTA + optional date note</span>
		</div>
		<div class="card-body">
			<div class="row g-3 mb-0">
				<div class="col-md-6">
					<label class="form-label">Sidebar CTA headline</label>
					<input type="text" name="cta_headline" class="form-control" value="{{ old('cta_headline', $project->ctaHeadline() ?? '') }}" maxlength="255" placeholder="Book a site visit">
				</div>
				<div class="col-md-6">
					<label class="form-label">Sidebar CTA subtext</label>
					<input type="text" name="cta_subtext" class="form-control" value="{{ old('cta_subtext', $project->ctaSubtext() ?? '') }}" maxlength="500" placeholder="Our advisor will share floor plans &amp; best units.">
				</div>
				<div class="col-12">
					<label class="form-label">Last updated note</label>
					<input type="text" name="last_updated_note" class="form-control" value="{{ old('last_updated_note', $project->lastUpdatedNote() ?? '') }}" maxlength="500" placeholder="e.g. Pricing updated 4 Apr 2026">
					<p class="text-muted small mb-0 mt-1">If set, shown at the top of the detail page instead of the publish date.</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-12 mb-4">
	<div class="card radius-10 border shadow-none border-warning border-opacity-50">
		<div class="card-header bg-warning bg-opacity-10 py-3 border-bottom">
			<span class="badge bg-warning text-dark me-2">G</span>
			<strong class="align-middle">Gallery, master plan &amp; floor plans</strong>
			<span class="text-muted small fw-normal ms-2 d-none d-md-inline">→ top gallery + “Plans” section</span>
		</div>
		<div class="card-body">
			<div class="mb-4">
				<label class="form-label">Gallery images</label>
				<input type="file" name="gallery[]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple>
				<p class="text-muted small mb-0 mt-1">Up to 12 new images per save; max 24 total. Appears above the text sections on the live page.</p>
				@if(is_array($project->gallery_paths ?? null) && count($project->gallery_paths))
					<p class="small text-muted mt-3 mb-1 fw-semibold">Existing — check to remove:</p>
					<div class="row g-2">
						@foreach($project->gallery_paths as $path)
							@php
								$gUrl = \App\Models\SiteSetting::resolvePublicUrl($path);
							@endphp
							@if($gUrl)
								<div class="col-6 col-md-3 col-lg-2">
									<div class="border rounded p-1">
										<img src="{{ $gUrl }}" alt="" class="img-fluid rounded" style="max-height: 88px; width:100%; object-fit:cover;">
										<div class="form-check small mt-1">
											<input class="form-check-input" type="checkbox" name="remove_gallery_paths[]" value="{{ $path }}" id="proj_rm_g_{{ md5($path) }}">
											<label class="form-check-label" for="proj_rm_g_{{ md5($path) }}">Remove</label>
										</div>
									</div>
								</div>
							@endif
						@endforeach
					</div>
				@endif
			</div>
			<hr class="text-muted opacity-25">
			<div class="row g-3 mb-0">
				<div class="col-md-6">
					<label class="form-label">Master plan images</label>
					<input type="file" name="master_plans[]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple>
					<p class="text-muted small mt-1 mb-2">Up to 12 new files per save; max 24 stored.</p>
					@php
						$projMasterPaths = is_array($project->master_plan_paths ?? null) ? $project->master_plan_paths : [];
						if (is_string($project->master_plan_path ?? null) && $project->master_plan_path !== '') {
							array_unshift($projMasterPaths, $project->master_plan_path);
						}
						$projMasterPaths = array_values(array_unique(array_filter($projMasterPaths, fn ($p) => is_string($p) && $p !== '')));
					@endphp
					@if(count($projMasterPaths))
						<p class="small text-muted mt-2 mb-1 fw-semibold">Existing — check to remove:</p>
						<div class="row g-2">
							@foreach($projMasterPaths as $path)
								@php
									$mu = \App\Models\SiteSetting::resolvePublicUrl($path);
								@endphp
								@if($mu)
									<div class="col-6">
										<div class="border rounded p-1">
											<img src="{{ $mu }}" alt="" class="img-fluid rounded" style="max-height: 72px; width:100%; object-fit:cover;">
											<div class="form-check small mt-1">
												<input class="form-check-input" type="checkbox" name="remove_master_plan_paths[]" value="{{ $path }}" id="proj_rm_mp_{{ md5($path) }}">
												<label class="form-check-label" for="proj_rm_mp_{{ md5($path) }}">Remove</label>
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
					@if(is_array($project->floor_plan_paths ?? null) && count($project->floor_plan_paths))
						<p class="small text-muted mt-2 mb-1 fw-semibold">Existing — check to remove:</p>
						<div class="row g-2">
							@foreach($project->floor_plan_paths as $path)
								@php
									$fu = is_string($path) ? \App\Models\SiteSetting::resolvePublicUrl($path) : null;
								@endphp
								@if($fu)
									<div class="col-6">
										<div class="border rounded p-1">
											<img src="{{ $fu }}" alt="" class="img-fluid rounded" style="max-height: 72px; width:100%; object-fit:cover;">
											<div class="form-check small mt-1">
												<input class="form-check-input" type="checkbox" name="remove_floor_plan_paths[]" value="{{ $path }}" id="proj_rm_fp_{{ md5($path) }}">
												<label class="form-check-label" for="proj_rm_fp_{{ md5($path) }}">Remove</label>
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

<div class="col-12">
	<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center p-3 rounded border bg-light">
		<span class="small text-muted mb-0">Preview: save, publish, then use <strong>View live</strong> at the top of this screen.</span>
		<a href="#proj-detail-sections" class="btn btn-sm btn-outline-secondary">↑ Detail sections</a>
	</div>
</div>
