<div class="row g-4">
	<div class="col-12">
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Content</h5>
				<div class="mb-3">
					<label class="form-label">Title</label>
					<input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required maxlength="255">
				</div>
				<div class="mb-3">
					<label class="form-label">URL slug</label>
					<input type="text" name="slug" class="form-control font-monospace" value="{{ old('slug', $post->slug) }}" maxlength="255" placeholder="auto from title if empty">
					<p class="text-muted small mb-0 mt-1">Public URL: <code>/blog/your-slug</code> — lowercase, hyphens. Leave blank to generate from title.</p>
				</div>
				<div class="mb-3">
					<label class="form-label">Excerpt <span class="text-muted small">(optional)</span></label>
					<textarea name="excerpt" class="form-control" rows="3" maxlength="5000" placeholder="Short teaser for listings">{{ old('excerpt', $post->excerpt) }}</textarea>
				</div>
				<div class="mb-3">
					<label class="form-label">Featured image</label>
					@if($post->featuredBannerUrl())
						<div class="mb-2">
							<img src="{{ $post->featuredBannerUrl() }}" alt="" class="img-thumbnail rounded" style="max-height: 180px; object-fit: cover;">
						</div>
					@endif
					<input type="file" name="featured_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
					<p class="text-muted small mt-1 mb-2">Upload JPG, PNG, GIF or WebP (max 5&nbsp;MB). Replaces the current featured image when saved.</p>
					@if($post->featured_image_path)
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="remove_featured_image" value="1" id="remove_featured_image" @checked(old('remove_featured_image'))>
							<label class="form-check-label" for="remove_featured_image">Remove uploaded featured image</label>
						</div>
					@endif
					<hr class="my-3 text-muted opacity-25">
					<label class="form-label">Or external image URL <span class="text-muted small">(optional)</span></label>
					<input type="text" name="featured_image_url" class="form-control" value="{{ old('featured_image_url', $post->featured_image_url) }}" maxlength="2000" placeholder="https://… (used if no upload)">
					<p class="text-muted small mb-0 mt-1">Upload takes priority over URL when both are set.</p>
				</div>
				<div class="mb-0">
					<label class="form-label">Body</label>
					<textarea name="body" id="blog_body" class="form-control" rows="16">{{ old('body', $post->body) }}</textarea>
					<p class="text-muted small mt-1 mb-0">Use the editor toolbar for formatting. Images in the article can be uploaded via the image button.</p>
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
					<input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title) }}" maxlength="255" placeholder="Defaults to post title if empty">
				</div>
				<div class="mb-3">
					<label class="form-label">Meta description</label>
					<textarea name="meta_description" class="form-control" rows="3" maxlength="5000">{{ old('meta_description', $post->meta_description) }}</textarea>
				</div>
				<div class="mb-0">
					<label class="form-label">Meta keywords <span class="text-muted small">(optional)</span></label>
					<input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $post->meta_keywords) }}" maxlength="255" placeholder="comma, separated">
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="card radius-10 border h-100">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Publishing</h5>
				<div class="form-check form-switch mb-3">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="blog_pub" @checked(old('is_published', $post->is_published))>
					<label class="form-check-label" for="blog_pub">Published (visible on /blog)</label>
				</div>
				@if($post->published_at)
					<p class="text-muted small mb-0">First published: {{ $post->published_at->format('M j, Y H:i') }}</p>
				@endif
			</div>
		</div>
	</div>
</div>
