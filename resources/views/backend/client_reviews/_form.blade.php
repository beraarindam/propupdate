<div class="row g-4">
	<div class="col-lg-7">
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Review</h5>
				<div class="mb-3">
					<label class="form-label">Reviewer name</label>
					<input type="text" name="reviewer_name" class="form-control" value="{{ old('reviewer_name', $item->reviewer_name) }}" required maxlength="120">
				</div>
				<div class="mb-3">
					<label class="form-label">Rating</label>
					<select name="rating" class="form-select" required>
						@for($r = 5; $r >= 1; $r--)
							<option value="{{ $r }}" @selected((int) old('rating', $item->rating ?? 5) === $r)>{{ $r }} star{{ $r === 1 ? '' : 's' }}</option>
						@endfor
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label">Review content</label>
					<textarea name="content" class="form-control" rows="6" required maxlength="5000">{{ old('content', $item->content) }}</textarea>
				</div>
				<div class="mb-0">
					<label class="form-label">Reviewer photo</label>
					@if($item->avatarUrl())
						<div class="mb-2">
							<img src="{{ $item->avatarUrl() }}" alt="" class="img-thumbnail rounded-circle" style="width:72px;height:72px;object-fit:cover;">
						</div>
					@endif
					<input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
					<p class="text-muted small mt-1 mb-2">JPG, PNG, GIF or WebP (max 5 MB).</p>
					@if($item->image_path)
						<div class="form-check mb-2">
							<input class="form-check-input" type="checkbox" name="remove_image" value="1" id="review_rem_img" @checked(old('remove_image'))>
							<label class="form-check-label" for="review_rem_img">Remove uploaded photo</label>
						</div>
					@endif
					<label class="form-label">Or external photo URL</label>
					<input type="text" name="image_url" class="form-control" value="{{ old('image_url', $item->image_url) }}" maxlength="2000" placeholder="https://...">
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
					<p class="text-muted small mt-1 mb-0">Lower numbers appear first in the carousel.</p>
				</div>
				<div class="form-check form-switch mb-0">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="review_pub" @checked(old('is_published', $item->is_published ?? true))>
					<label class="form-check-label" for="review_pub">Published</label>
				</div>
			</div>
		</div>
	</div>
</div>
