<div class="card radius-10 border">
	<div class="card-body">
		<h5 class="mb-3 pb-2 border-bottom">Home — why choose us</h5>
		<div class="row g-3">
			<div class="col-md-4">
				<label class="form-label">Eyebrow</label>
				<input type="text" name="home_why_eyebrow" class="form-control" value="{{ old('home_why_eyebrow', $page->section('why.eyebrow')) }}">
			</div>
			<div class="col-md-4">
				<label class="form-label">Title</label>
				<input type="text" name="home_why_title" class="form-control" value="{{ old('home_why_title', $page->section('why.title')) }}">
			</div>
			<div class="col-md-4">
				<label class="form-label">Title accent (coloured part)</label>
				<input type="text" name="home_why_title_accent" class="form-control" value="{{ old('home_why_title_accent', $page->section('why.title_accent')) }}">
			</div>
			@for($i = 1; $i <= 5; $i++)
				@php
					$feat = $page->section('why.features.'.($i - 1));
				@endphp
				<div class="col-12">
					<label class="form-label">Bullet {{ $i }} <span class="text-muted small">(HTML allowed, e.g. &lt;strong&gt;)</span></label>
					<input type="text" name="home_why_feature_{{ $i }}" class="form-control" value="{{ old('home_why_feature_'.$i, $feat) }}">
				</div>
			@endfor
			<div class="col-12">
				<label class="form-label">Quote</label>
				<textarea name="home_why_quote" class="form-control" rows="2">{{ old('home_why_quote', $page->section('why.quote')) }}</textarea>
			</div>
			<div class="col-md-6">
				<label class="form-label">Showcase label <span class="text-muted small">(HTML allowed)</span></label>
				<input type="text" name="home_why_showcase_label" class="form-control" value="{{ old('home_why_showcase_label', $page->section('why.showcase_label')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Chip text <span class="text-muted small">(HTML allowed)</span></label>
				<input type="text" name="home_why_chip_text" class="form-control" value="{{ old('home_why_chip_text', $page->section('why.chip_text')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Consultation button lines <span class="text-muted small">(use line break between lines)</span></label>
				<textarea name="home_why_consult_text" class="form-control" rows="2">{{ old('home_why_consult_text', $page->section('why.consult_text')) }}</textarea>
			</div>
			<div class="col-md-6">
				<label class="form-label">Consultation tel <span class="text-muted small">(+91… or tel:…)</span></label>
				<input type="text" name="home_why_cta_tel" class="form-control" value="{{ old('home_why_cta_tel', $page->section('why.cta_tel')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Website URL</label>
				<input type="text" name="home_why_website_url" class="form-control" value="{{ old('home_why_website_url', $page->section('why.website_url')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Website label</label>
				<input type="text" name="home_why_website_label" class="form-control" value="{{ old('home_why_website_label', $page->section('why.website_label')) }}">
			</div>
		</div>
	</div>
</div>
