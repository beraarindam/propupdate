<div class="card radius-10 border">
	<div class="card-body">
		<h5 class="mb-3 pb-2 border-bottom">Home — new launches / pre-register</h5>
		<div class="row g-3">
			<div class="col-12">
				<label class="form-label">Title (H2)</label>
				<input type="text" name="home_launches_title" class="form-control" value="{{ old('home_launches_title', $page->section('launches.title')) }}">
			</div>
			<div class="col-12">
				<label class="form-label">Lead paragraph</label>
				<textarea name="home_launches_lead" class="form-control" rows="3">{{ old('home_launches_lead', $page->section('launches.lead')) }}</textarea>
			</div>
			<div class="col-12">
				<label class="form-label">Benefits subheading (H3)</label>
				<input type="text" name="home_launches_sub" class="form-control" value="{{ old('home_launches_sub', $page->section('launches.sub')) }}">
			</div>
			@for($i = 1; $i <= 4; $i++)
				@php
					$b = $page->section('launches.benefits.'.($i - 1)) ?? [];
				@endphp
				<div class="col-12 border rounded p-3 bg-light">
					<div class="fw-semibold small text-muted mb-2">Benefit {{ $i }}</div>
					<div class="row g-2">
						<div class="col-md-3">
							<label class="form-label small">Icon class</label>
							<input type="text" name="home_launches_benefit_{{ $i }}_icon" class="form-control form-control-sm" value="{{ old('home_launches_benefit_'.$i.'_icon', $b['icon'] ?? '') }}">
						</div>
						<div class="col-md-4">
							<label class="form-label small">Title</label>
							<input type="text" name="home_launches_benefit_{{ $i }}_title" class="form-control form-control-sm" value="{{ old('home_launches_benefit_'.$i.'_title', $b['title'] ?? '') }}">
						</div>
						<div class="col-md-5">
							<label class="form-label small">Text</label>
							<input type="text" name="home_launches_benefit_{{ $i }}_text" class="form-control form-control-sm" value="{{ old('home_launches_benefit_'.$i.'_text', $b['text'] ?? '') }}">
						</div>
					</div>
				</div>
			@endfor
			<div class="col-12">
				<label class="form-label">CTA line <span class="text-muted small">(HTML allowed)</span></label>
				<textarea name="home_launches_cta_line" class="form-control font-monospace small" rows="2">{{ old('home_launches_cta_line', $page->section('launches.cta_line')) }}</textarea>
			</div>
			<div class="col-12">
				<label class="form-label">Aside intro <span class="text-muted small">(HTML allowed)</span></label>
				<textarea name="home_launches_aside_intro" class="form-control font-monospace small" rows="2">{{ old('home_launches_aside_intro', $page->section('launches.aside_intro')) }}</textarea>
			</div>
			<div class="col-md-6">
				<label class="form-label">Form card title</label>
				<input type="text" name="home_launches_form_title" class="form-control" value="{{ old('home_launches_form_title', $page->section('launches.form_title')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Form privacy note <span class="text-muted small">(HTML allowed)</span></label>
				<textarea name="home_launches_form_note" class="form-control font-monospace small" rows="2">{{ old('home_launches_form_note', $page->section('launches.form_note')) }}</textarea>
			</div>
		</div>
	</div>
</div>
