@php
  use App\Models\Property;
  $listingOpts = Property::listingTypeOptions();
  $amenitiesText = old('amenities_text', is_array($property->amenities ?? null) ? implode("\n", $property->amenities) : '');
@endphp

<div class="row g-4">
	<div class="col-12">
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Listing basics</h5>
				<div class="row g-3">
					<div class="col-lg-8">
						<label class="form-label">Title</label>
						<input type="text" name="title" class="form-control" value="{{ old('title', $property->title) }}" required maxlength="255">
					</div>
					<div class="col-lg-4">
						<label class="form-label">URL slug</label>
						<input type="text" name="slug" class="form-control font-monospace" value="{{ old('slug', $property->slug) }}" maxlength="255" placeholder="auto from title">
					</div>
					<div class="col-md-6 col-lg-4">
						<label class="form-label">Category</label>
						<select name="property_category_id" class="form-select">
							@foreach($categories as $cid => $clabel)
								<option value="{{ $cid }}" @selected((string) old('property_category_id', $property->property_category_id ?? '') === (string) $cid)>{{ $clabel }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-6 col-lg-4">
						<label class="form-label">Listing</label>
						<select name="listing_type" class="form-select" required>
							@foreach($listingOpts as $val => $label)
								<option value="{{ $val }}" @selected(old('listing_type', $property->listing_type ?? Property::LISTING_SALE) === $val)>{{ $label }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label">Price</label>
						<input type="number" name="price" class="form-control" value="{{ old('price', $property->price) }}" step="0.01" min="0" placeholder="0">
					</div>
					<div class="col-md-4">
						<label class="form-label">Currency</label>
						<input type="text" name="price_currency" class="form-control" value="{{ old('price_currency', $property->price_currency ?? 'INR') }}" maxlength="10">
					</div>
					<div class="col-md-4 d-flex align-items-end">
						<div class="form-check mb-2">
							<input class="form-check-input" type="checkbox" name="price_on_request" value="1" id="price_or" @checked(old('price_on_request', $property->price_on_request))>
							<label class="form-check-label" for="price_or">Price on request</label>
						</div>
					</div>
					<div class="col-12">
						<label class="form-label">Maintenance / society charges <span class="text-muted small">(optional)</span></label>
						<input type="text" name="maintenance_charges" class="form-control" value="{{ old('maintenance_charges', $property->maintenance_charges) }}" maxlength="120" placeholder="e.g. ₹12,000 / month">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="card radius-10 border h-100">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Specifications</h5>
				<div class="row g-3">
					<div class="col-6">
						<label class="form-label">Bedrooms</label>
						<input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $property->bedrooms) }}" step="0.5" min="0">
					</div>
					<div class="col-6">
						<label class="form-label">Bathrooms</label>
						<input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $property->bathrooms) }}" step="0.5" min="0">
					</div>
					<div class="col-6">
						<label class="form-label">Balconies</label>
						<input type="number" name="balconies" class="form-control" value="{{ old('balconies', $property->balconies) }}" min="0">
					</div>
					<div class="col-6">
						<label class="form-label">Covered parking</label>
						<input type="number" name="parking_covered" class="form-control" value="{{ old('parking_covered', $property->parking_covered) }}" min="0">
					</div>
					<div class="col-12">
						<label class="form-label">Built-up area (sq ft)</label>
						<input type="number" name="built_up_area_sqft" class="form-control" value="{{ old('built_up_area_sqft', $property->built_up_area_sqft) }}" step="0.01" min="0">
					</div>
					<div class="col-12">
						<label class="form-label">Carpet area (sq ft)</label>
						<input type="number" name="carpet_area_sqft" class="form-control" value="{{ old('carpet_area_sqft', $property->carpet_area_sqft) }}" step="0.01" min="0">
					</div>
					<div class="col-12">
						<label class="form-label">Plot area (sq ft)</label>
						<input type="number" name="plot_area_sqft" class="form-control" value="{{ old('plot_area_sqft', $property->plot_area_sqft) }}" step="0.01" min="0">
					</div>
					<div class="col-6">
						<label class="form-label">Floor #</label>
						<input type="number" name="floor_number" class="form-control" value="{{ old('floor_number', $property->floor_number) }}">
					</div>
					<div class="col-6">
						<label class="form-label">Total floors</label>
						<input type="number" name="total_floors" class="form-control" value="{{ old('total_floors', $property->total_floors) }}" min="1">
					</div>
					<div class="col-6">
						<label class="form-label">Facing</label>
						<input type="text" name="facing" class="form-control" value="{{ old('facing', $property->facing) }}" maxlength="60" placeholder="East, North-East…">
					</div>
					<div class="col-6">
						<label class="form-label">Furnishing</label>
						<input type="text" name="furnishing" class="form-control" value="{{ old('furnishing', $property->furnishing) }}" maxlength="60" placeholder="Unfurnished / Semi / Full">
					</div>
					<div class="col-6">
						<label class="form-label">Age (years)</label>
						<input type="number" name="age_of_property_years" class="form-control" value="{{ old('age_of_property_years', $property->age_of_property_years) }}" min="0">
					</div>
					<div class="col-12">
						<label class="form-label">Possession</label>
						<input type="text" name="possession_status" class="form-control" value="{{ old('possession_status', $property->possession_status) }}" maxlength="120" placeholder="Ready to move, Dec 2026…">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="card radius-10 border h-100">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Location</h5>
				<div class="mb-3">
					<label class="form-label">Address line 1</label>
					<input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', $property->address_line1) }}" maxlength="255">
				</div>
				<div class="mb-3">
					<label class="form-label">Address line 2</label>
					<input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', $property->address_line2) }}" maxlength="255">
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label">Locality</label>
						<input type="text" name="locality" class="form-control" value="{{ old('locality', $property->locality) }}" maxlength="120">
					</div>
					<div class="col-md-6">
						<label class="form-label">City</label>
						<input type="text" name="city" class="form-control" value="{{ old('city', $property->city) }}" maxlength="120">
					</div>
					<div class="col-md-6">
						<label class="form-label">State</label>
						<input type="text" name="state" class="form-control" value="{{ old('state', $property->state) }}" maxlength="120">
					</div>
					<div class="col-md-6">
						<label class="form-label">PIN</label>
						<input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $property->postal_code) }}" maxlength="20">
					</div>
					<div class="col-12">
						<label class="form-label">Country</label>
						<input type="text" name="country" class="form-control" value="{{ old('country', $property->country) }}" maxlength="120">
					</div>
					<div class="col-6">
						<label class="form-label">Latitude</label>
						<input type="number" name="latitude" class="form-control" value="{{ old('latitude', $property->latitude) }}" step="0.0000001" min="-90" max="90">
					</div>
					<div class="col-6">
						<label class="form-label">Longitude</label>
						<input type="number" name="longitude" class="form-control" value="{{ old('longitude', $property->longitude) }}" step="0.0000001" min="-180" max="180">
					</div>
				</div>
			</div>
		</div>
	</div>

	@include('backend.properties._form_project')

	<div class="col-12">
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Description & media</h5>
				<div class="mb-3">
					<label class="form-label">Short summary</label>
					<textarea name="summary" class="form-control" rows="3" maxlength="8000" placeholder="Card / search snippet">{{ old('summary', $property->summary) }}</textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">Full description</label>
					<textarea name="description" id="property_description" class="form-control" rows="12">{{ old('description', $property->description) }}</textarea>
					<p class="text-muted small mt-1 mb-0">Rich text; images via toolbar upload.</p>
				</div>
				<div class="mb-3">
					<label class="form-label">Amenities <span class="text-muted small">(one per line or comma-separated)</span></label>
					<textarea name="amenities_text" class="form-control" rows="4" maxlength="20000" placeholder="Lift&#10;24×7 security&#10;Clubhouse">{{ $amenitiesText }}</textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">Featured image</label>
					@if($property->featuredBannerUrl())
						<div class="mb-2">
							<img src="{{ $property->featuredBannerUrl() }}" alt="" class="img-thumbnail rounded" style="max-height: 160px; object-fit: cover;">
						</div>
					@endif
					<input type="file" name="featured_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
					@if($property->featured_image_path)
						<div class="form-check mt-2">
							<input class="form-check-input" type="checkbox" name="remove_featured_image" value="1" id="prop_rem_feat" @checked(old('remove_featured_image'))>
							<label class="form-check-label" for="prop_rem_feat">Remove uploaded featured image</label>
						</div>
					@endif
					<hr class="my-3 text-muted opacity-25">
					<label class="form-label">Or external image URL</label>
					<input type="text" name="featured_image_url" class="form-control" value="{{ old('featured_image_url', $property->featured_image_url) }}" maxlength="2000" placeholder="https://…">
				</div>
				<div class="mb-0">
					<label class="form-label">Gallery <span class="text-muted small">(add up to {{ 12 }} new images per save; max {{ 24 }} total)</span></label>
					<input type="file" name="gallery[]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" multiple>
					@if(is_array($property->gallery_paths ?? null) && count($property->gallery_paths))
						<p class="small text-muted mt-2 mb-1">Existing — check to remove:</p>
						<div class="row g-2">
							@foreach($property->gallery_paths as $path)
								@php($gUrl = \App\Models\SiteSetting::resolvePublicUrl($path))
								@if($gUrl)
									<div class="col-6 col-md-3 col-lg-2">
										<div class="border rounded p-1">
											<img src="{{ $gUrl }}" alt="" class="img-fluid rounded" style="max-height: 88px; width:100%; object-fit:cover;">
											<div class="form-check small mt-1">
												<input class="form-check-input" type="checkbox" name="remove_gallery_paths[]" value="{{ $path }}" id="rm_g_{{ md5($path) }}">
												<label class="form-check-label" for="rm_g_{{ md5($path) }}">Remove</label>
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

	<div class="col-lg-6">
		<div class="card radius-10 border h-100">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">SEO</h5>
				<div class="mb-3">
					<label class="form-label">Meta title</label>
					<input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $property->meta_title) }}" maxlength="255">
				</div>
				<div class="mb-3">
					<label class="form-label">Meta description</label>
					<textarea name="meta_description" class="form-control" rows="3" maxlength="5000">{{ old('meta_description', $property->meta_description) }}</textarea>
				</div>
				<div class="mb-0">
					<label class="form-label">Meta keywords</label>
					<input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $property->meta_keywords) }}" maxlength="255" placeholder="comma, separated">
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="card radius-10 border h-100">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Publishing</h5>
				<div class="form-check form-switch mb-3">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="prop_pub" @checked(old('is_published', $property->is_published))>
					<label class="form-check-label" for="prop_pub">Published (visible on site)</label>
				</div>
				<div class="form-check form-switch mb-3">
					<input class="form-check-input" type="checkbox" name="is_featured" value="1" id="prop_feat" @checked(old('is_featured', $property->is_featured))>
					<label class="form-check-label" for="prop_feat">Featured listing</label>
				</div>
				<div class="mb-0">
					<label class="form-label">Manual sort order</label>
					<input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $property->sort_order ?? 0) }}" min="0">
					@if($property->published_at)
						<p class="text-muted small mt-2 mb-0">Published at: {{ $property->published_at->format('M j, Y H:i') }}</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
