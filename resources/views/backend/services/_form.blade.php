<div class="row g-4">
	<div class="col-lg-8">
		<div class="card radius-10 border">
			<div class="card-body">
				<div class="mb-3">
					<label class="form-label">Service name</label>
					<input type="text" name="name" class="form-control" value="{{ old('name', $service->name) }}" required maxlength="255">
				</div>
				<div class="mb-3">
					<label class="form-label">Short summary</label>
					<textarea name="summary" class="form-control" rows="4" required maxlength="5000" placeholder="Shown on the homepage service cards">{{ old('summary', $service->summary) }}</textarea>
				</div>
				<div class="mb-0">
					<label class="form-label">Full description <span class="text-muted small">(optional)</span></label>
					<textarea name="description" class="form-control" rows="5" maxlength="20000" placeholder="Extra detail for internal reference or future detail pages">{{ old('description', $service->description) }}</textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card radius-10 border h-100">
			<div class="card-body">
				<div class="mb-3">
					<label class="form-label">Icon class</label>
					<input type="text" name="icon_class" class="form-control font-monospace" value="{{ old('icon_class', $service->icon_class) }}" maxlength="120" placeholder="fa-solid fa-building">
					<p class="text-muted small mt-1 mb-0">Font Awesome 6 class, e.g. <code>fa-solid fa-chart-line</code></p>
				</div>
				<div class="mb-3">
					<label class="form-label">Sort order</label>
					<input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $service->sort_order ?? 0) }}" min="0">
				</div>
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" name="is_published" value="1" id="svc_pub" @checked(old('is_published', $service->is_published ?? true))>
					<label class="form-check-label" for="svc_pub">Published on website</label>
				</div>
			</div>
		</div>
	</div>
</div>
