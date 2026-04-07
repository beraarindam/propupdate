<div class="row g-4">
	<div class="col-lg-7">
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Award</h5>
				<div class="mb-3">
					<label class="form-label">Title</label>
					<input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" maxlength="255">
				</div>
				<div class="mb-3">
					<label class="form-label">Subtitle <span class="text-muted small">(optional)</span></label>
					<textarea name="subtitle" class="form-control" rows="3" maxlength="2000">{{ old('subtitle', $item->subtitle) }}</textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">Image</label>
					@if($item->imagePublicUrl())
						<div class="mb-2">
							<img src="{{ $item->imagePublicUrl() }}" alt="" class="img-thumbnail rounded" style="max-height: 150px; object-fit: cover;">
						</div>
					@endif
					<input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
					<p class="text-muted small mt-1 mb-2">JPG, PNG, GIF or WebP (max 5 MB).</p>
					@if($item->image_path)
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="remove_image" value="1" id="award_rem_img" @checked(old('remove_image'))>
							<label class="form-check-label" for="award_rem_img">Remove uploaded image</label>
						</div>
					@endif
					<hr class="my-3 text-muted opacity-25">
					<label class="form-label">Or external image URL</label>
					<input type="text" name="image_url" class="form-control" value="{{ old('image_url', $item->image_url) }}" maxlength="2000" placeholder="https://...">
				</div>
				<div class="mb-0">
					<label class="form-label">Link URL <span class="text-muted small">(optional click target)</span></label>
					<input type="text" name="link_url" class="form-control" value="{{ old('link_url', $item->link_url) }}" maxlength="2000" placeholder="https://...">
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Publishing</h5>
				<div class="mb-3">
					<label class="form-label">Sort order</label>
					<input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0">
				</div>
				<div class="form-check form-switch mb-0">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="award_pub" @checked(old('is_published', $item->is_published ?? true))>
					<label class="form-check-label" for="award_pub">Published</label>
				</div>
			</div>
		</div>
	</div>
</div>

