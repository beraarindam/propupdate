<div class="row g-3">
	<div class="col-md-8">
		<div class="card radius-10 border">
			<div class="card-body">
				<div class="mb-3">
					<label class="form-label">Title <span class="text-muted small">(optional)</span></label>
					<input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" maxlength="255" placeholder="e.g. Skyline view">
				</div>
				<div class="mb-3">
					<label class="form-label">Caption <span class="text-muted small">(optional)</span></label>
					<textarea name="caption" class="form-control" rows="3" maxlength="2000" placeholder="Short description shown under the image">{{ old('caption', $item->caption) }}</textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">Image file</label>
					@if($item->exists && $item->imagePublicUrl())
						<div class="mb-2">
							<img src="{{ $item->imagePublicUrl() }}" alt="" class="img-thumbnail rounded" style="max-height: 160px; object-fit: cover;">
						</div>
					@endif
					<input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
					<p class="text-muted small mt-1 mb-0">JPG, PNG, GIF or WebP (max 5&nbsp;MB). Leave empty to keep current upload.</p>
					@if($item->exists && $item->image_path)
						<div class="form-check mt-2">
							<input class="form-check-input" type="checkbox" name="remove_image" value="1" id="gi_rem_img" @checked(old('remove_image'))>
							<label class="form-check-label" for="gi_rem_img">Remove uploaded file</label>
						</div>
					@endif
				</div>
				<hr class="text-muted opacity-25">
				<div class="mb-0">
					<label class="form-label">Or external image URL</label>
					<input type="text" name="image_url" class="form-control" value="{{ old('image_url', $item->image_url) }}" maxlength="2000" placeholder="https://…">
					<p class="text-muted small mt-1 mb-0">Use either an upload or a URL (or both — upload takes preview priority when present).</p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card radius-10 border h-100">
			<div class="card-body">
				<div class="mb-3">
					<label class="form-label">Sort order</label>
					<input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0" max="999999">
				</div>
				<div class="form-check form-switch mb-0">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="gi_pub" @checked(old('is_published', $item->is_published ?? true))>
					<label class="form-check-label" for="gi_pub">Published on site</label>
				</div>
			</div>
		</div>
	</div>
</div>
