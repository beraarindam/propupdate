@php
	$categories = $categories ?? ['' => '— None —'];
	$areas = $areas ?? ['' => '— None —'];
@endphp
<div class="row g-4">
	<div class="col-12" id="proj-section-content-top">
		<div class="card radius-10 border">
			<div class="card-body">
				<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3 pb-2 border-bottom">
					<h5 class="mb-0">Content — title, hero &amp; listing teaser</h5>
					<a href="#proj-detail-sections" class="btn btn-sm btn-primary">Jump to detail page fields (A–G) ↓</a>
				</div>
				<div class="mb-3">
					<label class="form-label">Title</label>
					<input type="text" name="title" class="form-control" value="{{ old('title', $project->title) }}" required maxlength="255">
				</div>
				<div class="mb-3">
					<label class="form-label">URL slug</label>
					<input type="text" name="slug" class="form-control font-monospace" value="{{ old('slug', $project->slug) }}" maxlength="255" placeholder="auto from title if empty">
					<p class="text-muted small mb-0 mt-1">Public URL: <code>/projects/your-slug</code></p>
				</div>
				<div class="row g-3 mb-3">
					<div class="col-md-6">
						<label class="form-label">Category</label>
						<select name="property_category_id" class="form-select">
							@foreach($categories as $cid => $clabel)
								<option value="{{ $cid }}" @selected((string) old('property_category_id', $project->property_category_id ?? '') === (string) $cid)>{{ $clabel }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-6">
						<label class="form-label">Location <span class="text-muted small">(short — chips / banner)</span></label>
						<input type="text" name="location" class="form-control" value="{{ old('location', $project->location) }}" maxlength="255" placeholder="e.g. North Bangalore">
					</div>
					<div class="col-md-12">
						<label class="form-label">Developer name <span class="text-muted small">(builder)</span></label>
						<input type="text" name="developer_name" class="form-control" value="{{ old('developer_name', $project->developer_name) }}" maxlength="255" placeholder="e.g. Prestige Estates">
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">Summary — overview on detail page</label>
					<textarea name="summary" class="form-control" rows="4" maxlength="8000" placeholder="2–4 sentences: what the project is, USP, location edge. Shows as the “Overview” block on the live page.">{{ old('summary', $project->summary) }}</textarea>
					<p class="text-muted small mb-0 mt-1">Also used on project cards and meta fallback when meta description is empty.</p>
				</div>
				<div class="mb-3">
					<label class="form-label">Featured / hero image</label>
					@if($project->featuredBannerUrl())
						<div class="mb-2">
							<img src="{{ $project->featuredBannerUrl() }}" alt="" class="img-thumbnail rounded" style="max-height: 180px; object-fit: cover;">
						</div>
					@endif
					<input type="file" name="featured_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
					@if($project->featured_image_path)
						<div class="form-check mt-2">
							<input class="form-check-input" type="checkbox" name="remove_featured_image" value="1" id="proj_rem_feat" @checked(old('remove_featured_image'))>
							<label class="form-check-label" for="proj_rem_feat">Remove uploaded featured image</label>
						</div>
					@endif
					<hr class="my-3 text-muted opacity-25">
					<label class="form-label">Or external image URL</label>
					<input type="text" name="featured_image_url" class="form-control" value="{{ old('featured_image_url', $project->featured_image_url) }}" maxlength="2000" placeholder="https://…">
					<p class="text-muted small mb-0 mt-1">Top banner on the public project page.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="col-12">
		<div class="card radius-10 border border-2 border-primary border-opacity-25">
			<div class="card-header bg-primary bg-opacity-10 py-3 border-bottom">
				<strong>Main article — “More about this project”</strong>
				<span class="text-muted small fw-normal ms-2">(rich text, large section on live page)</span>
			</div>
			<div class="card-body">
				<label class="form-label">Body</label>
				<textarea name="body" id="project_body" class="form-control" rows="18">{{ old('body', $project->body) }}</textarea>
				<p class="text-muted small mt-2 mb-0">Use headings, lists, and images for a full project story. This appears after pricing / location / plans on the detail page.</p>
			</div>
		</div>
	</div>

	@include('backend.projects._form_detail', ['project' => $project])

	<div class="col-12">
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Location</h5>
				<div class="mb-3">
						<label class="form-label">Area</label>
						<select name="property_area_id" class="form-select">
							@foreach($areas as $aid => $alabel)
								<option value="{{ $aid }}" @selected((string) old('property_area_id', $project->property_area_id ?? '') === (string) $aid)>{{ $alabel }}</option>
							@endforeach
						</select>
					<p class="text-muted small mb-0 mt-1">Managed under <strong>Properties → Areas</strong>. Free-text locality below stays optional.</p>
				</div>
				<div class="mb-3">
					<label class="form-label">Address line 1</label>
					<input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', $project->address_line1) }}" maxlength="255">
				</div>
				<div class="mb-3">
					<label class="form-label">Address line 2</label>
					<input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', $project->address_line2) }}" maxlength="255">
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label">Locality</label>
						<input type="text" name="locality" class="form-control" value="{{ old('locality', $project->locality) }}" maxlength="120">
					</div>
					<div class="col-md-6">
						<label class="form-label">City</label>
						<input type="text" name="city" class="form-control" value="{{ old('city', $project->city) }}" maxlength="120">
					</div>
					<div class="col-md-6">
						<label class="form-label">State</label>
						<input type="text" name="state" class="form-control" value="{{ old('state', $project->state) }}" maxlength="120">
					</div>
					<div class="col-md-6">
						<label class="form-label">PIN</label>
						<input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $project->postal_code) }}" maxlength="20">
					</div>
					<div class="col-12">
						<label class="form-label">Country</label>
						<input type="text" name="country" class="form-control" value="{{ old('country', $project->country ?? 'India') }}" maxlength="120">
					</div>
					<div class="col-6">
						<label class="form-label">Latitude</label>
						<input type="number" name="latitude" class="form-control" value="{{ old('latitude', $project->latitude) }}" step="0.0000001" min="-90" max="90">
					</div>
					<div class="col-6">
						<label class="form-label">Longitude</label>
						<input type="number" name="longitude" class="form-control" value="{{ old('longitude', $project->longitude) }}" step="0.0000001" min="-180" max="180">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="card radius-10 border h-100">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">SEO</h5>
				<div class="mb-3">
					<label class="form-label">Meta title</label>
					<input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $project->meta_title) }}" maxlength="255" placeholder="Browser title; defaults to project title">
				</div>
				<div class="mb-3">
					<label class="form-label">Meta description</label>
					<textarea name="meta_description" class="form-control" rows="3" maxlength="5000" placeholder="Shown in search snippets">{{ old('meta_description', $project->meta_description) }}</textarea>
				</div>
				<div class="mb-0">
					<label class="form-label">Meta keywords <span class="text-muted small">(optional)</span></label>
					<input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $project->meta_keywords) }}" maxlength="255" placeholder="comma, separated">
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="card radius-10 border h-100">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Publishing</h5>
				<div class="form-check form-switch mb-3">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="proj_pub" @checked(old('is_published', $project->is_published))>
					<label class="form-check-label" for="proj_pub">Published (visible on /projects)</label>
				</div>
				<div class="form-check form-switch mb-3">
					<input class="form-check-input" type="checkbox" name="is_featured" value="1" id="proj_feat" @checked(old('is_featured', $project->is_featured))>
					<label class="form-check-label" for="proj_feat">Featured (sort to top on listing)</label>
				</div>
				<div class="form-check form-switch mb-3">
					<input class="form-check-input" type="checkbox" name="is_new_launch" value="1" id="proj_new_launch" @checked(old('is_new_launch', $project->is_new_launch))>
					<label class="form-check-label" for="proj_new_launch">New launch (show on /new-launches)</label>
				</div>
				<div class="mb-3">
					<label class="form-label">Sort order</label>
					<input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $project->sort_order ?? 0) }}" min="0" max="999999">
				</div>
				@if($project->published_at)
					<p class="text-muted small mb-0">First published: {{ $project->published_at->format('M j, Y H:i') }}</p>
				@endif
			</div>
		</div>
	</div>
</div>
