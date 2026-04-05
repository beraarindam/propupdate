<div class="card radius-10 border">
	<div class="card-body">
		<h5 class="mb-3 pb-2 border-bottom">Listing page copy</h5>
		<p class="text-muted small mb-3">Shown beside or below the breadcrumb banner. Leave blank to use the built-in defaults. Use <strong>Main content</strong> for an optional HTML intro above the grid.</p>
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label">Sidebar title</label>
				<input type="text" name="listing_sidebar_title" class="form-control" value="{{ old('listing_sidebar_title', $page->listingIndex('sidebar_title')) }}" placeholder="e.g. Find your space">
			</div>
			<div class="col-12">
				<label class="form-label">Sidebar lead</label>
				<textarea name="listing_sidebar_lead" class="form-control" rows="2" placeholder="Short line under the sidebar title">{{ old('listing_sidebar_lead', $page->listingIndex('sidebar_lead')) }}</textarea>
			</div>
			<div class="col-md-6">
				<label class="form-label">Empty state title</label>
				<input type="text" name="listing_empty_title" class="form-control" value="{{ old('listing_empty_title', $page->listingIndex('empty_title')) }}" placeholder="When no results / no items">
			</div>
			<div class="col-12">
				<label class="form-label">Empty state message <span class="text-muted small">(HTML allowed)</span></label>
				<textarea name="listing_empty_message" class="form-control font-monospace small" rows="3" placeholder="e.g. Try different filters or a link to reset">{{ old('listing_empty_message', $page->listingIndex('empty_message')) }}</textarea>
			</div>
		</div>
	</div>
</div>
