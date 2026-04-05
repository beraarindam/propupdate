<div class="row g-4">
	<div class="col-lg-8">
		<div class="card radius-10 border">
			<div class="card-body">
				<h6 class="text-muted text-uppercase small mb-3">Listing details</h6>
				<div class="row g-3">
					<div class="col-md-4">
						<label class="form-label">Property code</label>
						<input type="text" name="property_code" class="form-control" value="{{ old('property_code', $listing->property_code) }}" maxlength="64" placeholder="e.g. PU-01">
					</div>
					<div class="col-md-8">
						<label class="form-label">Title <span class="text-danger">*</span></label>
						<input type="text" name="title" class="form-control" value="{{ old('title', $listing->title) }}" required maxlength="255">
					</div>
					<div class="col-md-6">
						<label class="form-label">Status ribbon</label>
						<input type="text" name="status_badge" class="form-control" value="{{ old('status_badge', $listing->status_badge) }}" maxlength="120" placeholder="Ready To Move">
					</div>
					<div class="col-12">
						<label class="form-label">Location</label>
						<input type="text" name="location" class="form-control" value="{{ old('location', $listing->location) }}" maxlength="500" placeholder="Area, city">
					</div>
					<div class="col-md-4">
						<label class="form-label">Type</label>
						<input type="text" name="property_type" class="form-control" value="{{ old('property_type', $listing->property_type) }}" maxlength="120" placeholder="Apartment">
					</div>
					<div class="col-md-4">
						<label class="form-label">Configuration</label>
						<input type="text" name="configuration" class="form-control" value="{{ old('configuration', $listing->configuration) }}" maxlength="120" placeholder="4 BHK">
					</div>
					<div class="col-md-4">
						<label class="form-label">Area</label>
						<input type="text" name="area_display" class="form-control" value="{{ old('area_display', $listing->area_display) }}" maxlength="120" placeholder="2400 Sqft">
					</div>
					<div class="col-md-4">
						<label class="form-label">Market price</label>
						<input type="text" name="market_price" class="form-control" value="{{ old('market_price', $listing->market_price) }}" maxlength="120" placeholder="4.1 Cr">
					</div>
					<div class="col-md-4">
						<label class="form-label">Asking price</label>
						<input type="text" name="asking_price" class="form-control" value="{{ old('asking_price', $listing->asking_price) }}" maxlength="120" placeholder="3.95 Cr">
					</div>
					<div class="col-md-4">
						<label class="form-label">Rate / sq.ft</label>
						<input type="text" name="rate_per_sqft" class="form-control" value="{{ old('rate_per_sqft', $listing->rate_per_sqft) }}" maxlength="120" placeholder="16458">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card radius-10 border mb-4">
			<div class="card-body">
				<h6 class="text-muted text-uppercase small mb-3">Photo</h6>
				@php $imgPrev = $listing->imagePublicUrl(); @endphp
				@if($imgPrev)
					<div class="mb-3 text-center">
						<img src="{{ $imgPrev }}" alt="" class="rounded-circle border" style="width:120px;height:120px;object-fit:cover;">
					</div>
				@endif
				<div class="mb-3">
					<label class="form-label">Upload image</label>
					<input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
				</div>
				<div class="mb-3">
					<label class="form-label">Or image URL</label>
					<input type="url" name="image_url" class="form-control" value="{{ old('image_url', $listing->image_url) }}" placeholder="https://…">
				</div>
				@if($listing->exists && ($listing->image_path || filled($listing->image_url)))
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="remove_image" value="1" id="er_rm_img" @checked(old('remove_image'))>
						<label class="form-check-label" for="er_rm_img">Remove uploaded file</label>
					</div>
				@endif
			</div>
		</div>
		<div class="card radius-10 border">
			<div class="card-body">
				<div class="mb-3">
					<label class="form-label">Sort order</label>
					<input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $listing->sort_order ?? 0) }}" min="0">
				</div>
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="er_pub" @checked(old('is_published', $listing->is_published ?? true))>
					<label class="form-check-label" for="er_pub">Published on website</label>
				</div>
			</div>
		</div>
	</div>
</div>
