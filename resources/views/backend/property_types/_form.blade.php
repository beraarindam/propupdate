<div class="row g-4">
	<div class="col-lg-7">
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Property type</h5>
				<div class="mb-3">
					<label class="form-label">Name</label>
					<input type="text" name="name" class="form-control" value="{{ old('name', $type->name) }}" required maxlength="255">
				</div>
				<div class="mb-3">
					<label class="form-label">URL slug</label>
					<input type="text" name="slug" class="form-control font-monospace" value="{{ old('slug', $type->slug) }}" maxlength="255" placeholder="auto from name if empty">
					<p class="text-muted small mb-0 mt-1">Examples: apartment, villa, plot, office.</p>
				</div>
				<div class="mb-0">
					<label class="form-label">Description <span class="text-muted small">(optional)</span></label>
					<textarea name="description" class="form-control" rows="5" maxlength="10000">{{ old('description', $type->description) }}</textarea>
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
					<input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $type->meta_title) }}" maxlength="255" placeholder="Defaults to name if empty">
				</div>
				<div class="mb-3">
					<label class="form-label">Meta description</label>
					<textarea name="meta_description" class="form-control" rows="3" maxlength="5000">{{ old('meta_description', $type->meta_description) }}</textarea>
				</div>
				<div class="mb-0">
					<label class="form-label">Meta keywords</label>
					<input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $type->meta_keywords) }}" maxlength="255" placeholder="comma, separated">
				</div>
			</div>
		</div>
		<div class="card radius-10 border">
			<div class="card-body">
				<h5 class="mb-3 pb-2 border-bottom">Publishing</h5>
				<div class="mb-3">
					<label class="form-label">Sort order</label>
					<input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $type->sort_order ?? 0) }}" min="0">
				</div>
				<div class="form-check form-switch mb-0">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="pt_pub" @checked(old('is_published', $type->is_published ?? true))>
					<label class="form-check-label" for="pt_pub">Published</label>
				</div>
			</div>
		</div>
	</div>
</div>
