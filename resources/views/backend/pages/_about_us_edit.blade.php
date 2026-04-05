@php
	$introPreview = \App\Models\Page::mediaPublicUrl($page->aboutPage('intro.image_path'), $page->aboutPage('intro.image_url'));
	$founderPreview = \App\Models\Page::mediaPublicUrl($page->aboutPage('founder.image_path'), $page->aboutPage('founder.image_url'));
	$valueItems = array_values(array_pad((array) ($page->aboutPage('values.items') ?? []), 3, []));
@endphp

<div class="d-flex flex-column gap-4">
	<div class="card radius-10 border">
		<div class="card-body">
			<h5 class="mb-3 pb-2 border-bottom">Intro (image + copy)</h5>
			<p class="text-muted small mb-3">Left-side visual and right-side text on the About page. Leave fields empty to keep the theme defaults on the live site.</p>
			@if($introPreview)
				<div class="mb-3">
					<div class="text-muted small mb-1">Current intro image</div>
					<img src="{{ $introPreview }}" alt="" class="img-fluid rounded border" style="max-height: 160px; width: auto;">
				</div>
			@endif
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label">Intro image upload</label>
					<input type="file" name="about_intro_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
				</div>
				<div class="col-md-6 d-flex align-items-end">
					@if($page->aboutPage('intro.image_path') || filled($page->aboutPage('intro.image_url')))
						<div class="form-check mb-0">
							<input class="form-check-input" type="checkbox" name="remove_about_intro_image" value="1" id="remove_about_intro_image" @checked(old('remove_about_intro_image'))>
							<label class="form-check-label" for="remove_about_intro_image">Remove intro image</label>
						</div>
					@endif
				</div>
				<div class="col-12">
					<label class="form-label">Intro image alt text</label>
					<input type="text" name="about_intro_image_alt" class="form-control" value="{{ old('about_intro_image_alt', $page->aboutPage('intro.image_alt')) }}">
				</div>
				<div class="col-md-4">
					<label class="form-label">Badge number</label>
					<input type="text" name="about_intro_badge_num" class="form-control" value="{{ old('about_intro_badge_num', $page->aboutPage('intro.badge_num')) }}" placeholder="10+">
				</div>
				<div class="col-md-8">
					<label class="form-label">Badge text</label>
					<input type="text" name="about_intro_badge_text" class="form-control" value="{{ old('about_intro_badge_text', $page->aboutPage('intro.badge_text')) }}">
				</div>
				<div class="col-md-6">
					<label class="form-label">Kicker (small line above heading)</label>
					<input type="text" name="about_intro_kicker" class="form-control" value="{{ old('about_intro_kicker', $page->aboutPage('intro.kicker')) }}">
				</div>
				<div class="col-md-6">
					<label class="form-label">Heading (H2)</label>
					<input type="text" name="about_intro_h2" class="form-control" value="{{ old('about_intro_h2', $page->aboutPage('intro.h2')) }}">
				</div>
				<div class="col-12">
					<label class="form-label">Paragraph 1</label>
					<textarea name="about_intro_paragraph_1" class="form-control" rows="3">{{ old('about_intro_paragraph_1', $page->aboutPage('intro.paragraph_1')) }}</textarea>
					<p class="text-muted small mt-1 mb-0">HTML allowed (e.g. &lt;strong&gt;).</p>
				</div>
				<div class="col-12">
					<label class="form-label">Paragraph 2</label>
					<textarea name="about_intro_paragraph_2" class="form-control" rows="3">{{ old('about_intro_paragraph_2', $page->aboutPage('intro.paragraph_2')) }}</textarea>
				</div>
				<div class="col-md-6">
					<label class="form-label">CTA button text</label>
					<input type="text" name="about_intro_cta_text" class="form-control" value="{{ old('about_intro_cta_text', $page->aboutPage('intro.cta_text')) }}">
				</div>
				<div class="col-md-6">
					<label class="form-label">CTA link</label>
					<input type="text" name="about_intro_cta_href" class="form-control" value="{{ old('about_intro_cta_href', $page->aboutPage('intro.cta_href')) }}" placeholder="properties, /contact, https://…">
				</div>
			</div>
		</div>
	</div>

	<div class="card radius-10 border">
		<div class="card-body">
			<h5 class="mb-3 pb-2 border-bottom">Founder block</h5>
			@if($founderPreview)
				<div class="mb-3">
					<div class="text-muted small mb-1">Current photo</div>
					<img src="{{ $founderPreview }}" alt="" class="img-fluid rounded border" style="max-height: 200px; width: auto;">
				</div>
			@endif
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label">Founder photo upload</label>
					<input type="file" name="about_founder_photo" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
				</div>
				<div class="col-md-6 d-flex align-items-end">
					@if($page->aboutPage('founder.image_path') || filled($page->aboutPage('founder.image_url')))
						<div class="form-check mb-0">
							<input class="form-check-input" type="checkbox" name="remove_about_founder_photo" value="1" id="remove_about_founder_photo" @checked(old('remove_about_founder_photo'))>
							<label class="form-check-label" for="remove_about_founder_photo">Remove founder photo</label>
						</div>
					@endif
				</div>
				<div class="col-12">
					<label class="form-label">Photo alt text</label>
					<input type="text" name="about_founder_image_alt" class="form-control" value="{{ old('about_founder_image_alt', $page->aboutPage('founder.image_alt')) }}">
				</div>
				<div class="col-md-6">
					<label class="form-label">Eyebrow (e.g. Founder’s note)</label>
					<input type="text" name="about_founder_eyebrow" class="form-control" value="{{ old('about_founder_eyebrow', $page->aboutPage('founder.eyebrow')) }}">
				</div>
				<div class="col-md-6">
					<label class="form-label">Name</label>
					<input type="text" name="about_founder_name" class="form-control" value="{{ old('about_founder_name', $page->aboutPage('founder.name')) }}">
				</div>
				<div class="col-12">
					<label class="form-label">Role / title</label>
					<input type="text" name="about_founder_role" class="form-control" value="{{ old('about_founder_role', $page->aboutPage('founder.role')) }}">
				</div>
				<div class="col-12">
					<label class="form-label">Quote</label>
					<textarea name="about_founder_quote" class="form-control" rows="2">{{ old('about_founder_quote', $page->aboutPage('founder.quote')) }}</textarea>
				</div>
				<div class="col-12">
					<label class="form-label">Body paragraph 1</label>
					<textarea name="about_founder_body_1" class="form-control" rows="3">{{ old('about_founder_body_1', $page->aboutPage('founder.body_1')) }}</textarea>
				</div>
				<div class="col-12">
					<label class="form-label">Body paragraph 2</label>
					<textarea name="about_founder_body_2" class="form-control" rows="3">{{ old('about_founder_body_2', $page->aboutPage('founder.body_2')) }}</textarea>
				</div>
			</div>
		</div>
	</div>

	<div class="card radius-10 border">
		<div class="card-body">
			<h5 class="mb-3 pb-2 border-bottom">Values (three pillars)</h5>
			<div class="row g-3 mb-3">
				<div class="col-md-6">
					<label class="form-label">Section kicker</label>
					<input type="text" name="about_values_kicker" class="form-control" value="{{ old('about_values_kicker', $page->aboutPage('values.kicker')) }}">
				</div>
				<div class="col-md-6">
					<label class="form-label">Section heading (H2)</label>
					<input type="text" name="about_values_h2" class="form-control" value="{{ old('about_values_h2', $page->aboutPage('values.h2')) }}">
				</div>
			</div>
			@foreach([1, 2, 3] as $idx)
				@php $i = $idx - 1; $item = $valueItems[$i] ?? []; @endphp
				<div class="border rounded p-3 mb-3 bg-light">
					<div class="fw-semibold mb-2">Pillar {{ $idx }}</div>
					<div class="row g-2">
						<div class="col-12">
							<label class="form-label small">Font Awesome classes <span class="text-muted">(e.g. fa-solid fa-shield-halved)</span></label>
							<input type="text" name="about_value_{{ $idx }}_icon" class="form-control form-control-sm" value="{{ old("about_value_{$idx}_icon", $item['icon'] ?? '') }}">
						</div>
						<div class="col-12">
							<label class="form-label small">Title</label>
							<input type="text" name="about_value_{{ $idx }}_title" class="form-control form-control-sm" value="{{ old("about_value_{$idx}_title", $item['title'] ?? '') }}">
						</div>
						<div class="col-12">
							<label class="form-label small">Text</label>
							<textarea name="about_value_{{ $idx }}_text" class="form-control form-control-sm" rows="2">{{ old("about_value_{$idx}_text", $item['text'] ?? '') }}</textarea>
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>

	<div class="card radius-10 border">
		<div class="card-body">
			<h5 class="mb-3 pb-2 border-bottom">Stats row</h5>
			<div class="row g-3">
				@foreach([1, 2, 3] as $n)
					<div class="col-md-6 col-lg-4">
						<label class="form-label small">Stat {{ $n }} — number</label>
						<input type="text" name="about_stat_{{ $n }}_num" class="form-control form-control-sm" value="{{ old("about_stat_{$n}_num", $page->aboutPage("stats.stat{$n}_num")) }}">
						<label class="form-label small mt-2">Stat {{ $n }} — label <span class="text-muted">(HTML ok)</span></label>
						<textarea name="about_stat_{{ $n }}_label" class="form-control form-control-sm" rows="2">{{ old("about_stat_{$n}_label", $page->aboutPage("stats.stat{$n}_label")) }}</textarea>
					</div>
				@endforeach
				<div class="col-md-6">
					<label class="form-label">CTA box label</label>
					<input type="text" name="about_stats_cta_label" class="form-control" value="{{ old('about_stats_cta_label', $page->aboutPage('stats.cta_label')) }}">
				</div>
				<div class="col-md-6">
					<label class="form-label">Phone / tel display</label>
					<input type="text" name="about_stats_cta_tel" class="form-control" value="{{ old('about_stats_cta_tel', $page->aboutPage('stats.cta_tel')) }}" placeholder="+91 7204362646">
				</div>
			</div>
		</div>
	</div>
</div>
