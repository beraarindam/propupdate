<div class="card radius-10 border">
	<div class="card-body">
		<h5 class="mb-3 pb-2 border-bottom">Home — category strip</h5>
		<p class="text-muted small mb-3">Use <strong>Property categories</strong> (first four published root categories) or define up to four custom cards (label, link, background <strong>upload</strong>).</p>
		<div class="mb-3">
			<label class="form-label d-block">Source</label>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="home_cat_source" id="home_cat_src_db" value="property_categories" @checked($catSource === 'property_categories')>
				<label class="form-check-label" for="home_cat_src_db">Property categories (database)</label>
			</div>
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="home_cat_source" id="home_cat_src_custom" value="custom" @checked($catSource === 'custom')>
				<label class="form-check-label" for="home_cat_src_custom">Custom cards</label>
			</div>
		</div>
		@for($i = 0; $i < 4; $i++)
			@php
				$ci = $catItems[$i] ?? [];
				$catPrev = \App\Models\Page::mediaPublicUrl($ci['image_path'] ?? null, $ci['image_url'] ?? null);
			@endphp
			<div class="border rounded p-3 mb-3 bg-light">
				<div class="fw-semibold small text-muted mb-2">Card {{ $i + 1 }} <span class="fw-normal">(custom mode only)</span></div>
				<div class="row g-2">
					<div class="col-md-4">
						<label class="form-label small">Label</label>
						<input type="text" name="home_cat_{{ $i }}_label" class="form-control form-control-sm" value="{{ old('home_cat_'.$i.'_label', $ci['label'] ?? '') }}">
					</div>
					<div class="col-md-4">
						<label class="form-label small">Link (URL, path, or <code>properties</code>)</label>
						<input type="text" name="home_cat_{{ $i }}_href" class="form-control form-control-sm" value="{{ old('home_cat_'.$i.'_href', $ci['href'] ?? '') }}" placeholder="/properties or https://…">
					</div>
					<div class="col-md-4">
						<label class="form-label small">Background image</label>
						@if($catPrev)
							<div class="mb-1"><img src="{{ $catPrev }}" alt="" class="rounded border" style="max-height:56px;width:auto;"></div>
						@endif
						<input type="file" name="home_cat_{{ $i }}_image" class="form-control form-control-sm" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
						@if(filled($ci['image_path'] ?? null))
							<div class="form-check mt-1">
								<input class="form-check-input" type="checkbox" name="remove_home_cat_{{ $i }}_image" value="1" id="rmcat{{ $i }}">
								<label class="form-check-label small" for="rmcat{{ $i }}">Remove image</label>
							</div>
						@endif
					</div>
				</div>
			</div>
		@endfor
	</div>
</div>
