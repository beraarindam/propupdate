<div class="row g-4">
	<div class="col-lg-7">
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Area</h5>
				<div class="mb-3">
					<label class="form-label">Name</label>
					<input type="text" name="name" class="form-control" value="{{ old('name', $area->name) }}" required maxlength="255">
				</div>
				<div class="mb-3">
					<label class="form-label">URL slug</label>
					<input type="text" name="slug" class="form-control font-monospace" value="{{ old('slug', $area->slug) }}" maxlength="255" placeholder="auto from name if empty">
					<p class="text-muted small mb-0 mt-1">Lowercase, hyphens. Used in URLs and filters if you expose areas on the site.</p>
				</div>
				<div class="mb-3">
					<label class="form-label">Area image</label>
					@if($area->bannerImageUrl())
						<div class="mb-2">
							<img src="{{ $area->bannerImageUrl() }}" alt="" class="img-thumbnail rounded" style="max-height: 140px; object-fit: cover;">
						</div>
					@endif
					<input type="file" name="area_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
					<p class="text-muted small mt-1 mb-2">JPG, PNG, GIF or WebP (max 5&nbsp;MB).</p>
					@if($area->image_path)
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="remove_area_image" value="1" id="pa_rem_img" @checked(old('remove_area_image'))>
							<label class="form-check-label" for="pa_rem_img">Remove uploaded image</label>
						</div>
					@endif
					<hr class="my-3 text-muted opacity-25">
					<label class="form-label">Or external image URL <span class="text-muted small">(optional)</span></label>
					<input type="text" name="image_url" class="form-control" value="{{ old('image_url', $area->image_url) }}" maxlength="2000" placeholder="https://…">
					<p class="text-muted small mb-0 mt-1">Upload takes priority over URL when both are set.</p>
				</div>
				<div class="mb-0">
					<label class="form-label">Description <span class="text-muted small">(optional)</span></label>
					<textarea name="description" class="form-control" rows="5" maxlength="10000">{{ old('description', $area->description) }}</textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<div class="card radius-10 border mb-4">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">SEO</h5>
				<div class="mb-3">
					<label class="form-label">Meta title</label>
					<input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $area->meta_title) }}" maxlength="255" placeholder="Defaults to name if empty">
				</div>
				<div class="mb-3">
					<label class="form-label">Meta description</label>
					<textarea name="meta_description" class="form-control" rows="3" maxlength="5000">{{ old('meta_description', $area->meta_description) }}</textarea>
				</div>
				<div class="mb-0">
					<label class="form-label">Meta keywords</label>
					<input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $area->meta_keywords) }}" maxlength="255" placeholder="comma, separated">
				</div>
			</div>
		</div>
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Publishing</h5>
				<div class="mb-3">
					<label class="form-label">Sort order</label>
					<input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $area->sort_order ?? 0) }}" min="0">
				</div>
				<div class="form-check form-switch mb-0">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="pa_pub" @checked(old('is_published', $area->is_published ?? true))>
					<label class="form-check-label" for="pa_pub">Published</label>
				</div>
			</div>
		</div>
	</div>
</div>
