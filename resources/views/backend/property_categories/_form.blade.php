@php
  /** @var \Illuminate\Support\Collection|array $parentSelectOptions */
  $parentSelectOptions = $parentSelectOptions ?? [];
@endphp
<div class="row g-4">
	<div class="col-lg-7">
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Category</h5>
				<div class="mb-3">
					<label class="form-label">Parent category</label>
					<select name="parent_id" class="form-select">
						@foreach($parentSelectOptions as $pid => $plabel)
							<option value="{{ $pid }}" @selected((string) old('parent_id', $category->parent_id ?? '') === (string) $pid)>{{ $plabel }}</option>
						@endforeach
					</select>
					<p class="text-muted small mb-0 mt-1">Choose <strong>top level</strong> for a root category, or nest under another (any depth).</p>
				</div>
				<div class="mb-3">
					<label class="form-label">Name</label>
					<input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required maxlength="255">
				</div>
				<div class="mb-3">
					<label class="form-label">URL slug</label>
					<input type="text" name="slug" class="form-control font-monospace" value="{{ old('slug', $category->slug) }}" maxlength="255" placeholder="auto from name if empty">
					<p class="text-muted small mb-0 mt-1">Lowercase, hyphens. Used in URLs and filters.</p>
				</div>
				<div class="mb-3">
					<label class="form-label">Category image</label>
					@if($category->bannerImageUrl())
						<div class="mb-2">
							<img src="{{ $category->bannerImageUrl() }}" alt="" class="img-thumbnail rounded" style="max-height: 140px; object-fit: cover;">
						</div>
					@endif
					<input type="file" name="category_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
					<p class="text-muted small mt-1 mb-2">JPG, PNG, GIF or WebP (max 5&nbsp;MB). Upload applies to this category (parent or child).</p>
					@if($category->image_path)
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="remove_category_image" value="1" id="pc_rem_img" @checked(old('remove_category_image'))>
							<label class="form-check-label" for="pc_rem_img">Remove uploaded image</label>
						</div>
					@endif
					<hr class="my-3 text-muted opacity-25">
					<label class="form-label">Or external image URL <span class="text-muted small">(optional)</span></label>
					<input type="text" name="image_url" class="form-control" value="{{ old('image_url', $category->image_url) }}" maxlength="2000" placeholder="https://…">
					<p class="text-muted small mb-0 mt-1">Upload takes priority over URL when both are set.</p>
				</div>
				<div class="mb-0">
					<label class="form-label">Description <span class="text-muted small">(optional)</span></label>
					<textarea name="description" class="form-control" rows="5" maxlength="10000">{{ old('description', $category->description) }}</textarea>
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
					<input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $category->meta_title) }}" maxlength="255" placeholder="Defaults to name if empty">
				</div>
				<div class="mb-3">
					<label class="form-label">Meta description</label>
					<textarea name="meta_description" class="form-control" rows="3" maxlength="5000">{{ old('meta_description', $category->meta_description) }}</textarea>
				</div>
				<div class="mb-0">
					<label class="form-label">Meta keywords</label>
					<input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $category->meta_keywords) }}" maxlength="255" placeholder="comma, separated">
				</div>
			</div>
		</div>
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Publishing</h5>
				<div class="mb-3">
					<label class="form-label">Sort order</label>
					<input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
				</div>
				<div class="form-check form-switch mb-0">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="pc_pub" @checked(old('is_published', $category->is_published ?? true))>
					<label class="form-check-label" for="pc_pub">Published</label>
				</div>
			</div>
		</div>
	</div>
</div>
