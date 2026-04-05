<div class="card radius-10 border">
	<div class="card-body">
		<h5 class="mb-3 pb-2 border-bottom">Home — about block</h5>
		<div class="row g-3">
			<div class="col-md-6">
				<label class="form-label">Kicker</label>
				<input type="text" name="home_about_kicker" class="form-control" value="{{ old('home_about_kicker', $page->section('about.kicker')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Heading (H2)</label>
				<input type="text" name="home_about_heading" class="form-control" value="{{ old('home_about_heading', $page->section('about.heading')) }}">
			</div>
			<div class="col-12">
				<label class="form-label">Body <span class="text-muted small">(plain text; line breaks kept)</span></label>
				<textarea name="home_about_body" class="form-control" rows="4">{{ old('home_about_body', $page->section('about.body')) }}</textarea>
			</div>
			<div class="col-md-6">
				<label class="form-label">Top photo</label>
				@php
					$abTopPrev = \App\Models\Page::mediaPublicUrl($page->section('about.photo_top_path'), $page->section('about.photo_top_url'));
				@endphp
				@if($abTopPrev)
					<div class="mb-1"><img src="{{ $abTopPrev }}" alt="" class="rounded border" style="max-height:72px;width:auto;"></div>
				@endif
				<input type="file" name="home_about_photo_top" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
				@if(filled($page->section('about.photo_top_path')))
					<div class="form-check mt-1">
						<input class="form-check-input" type="checkbox" name="remove_home_about_photo_top" value="1" id="rm_ab_top">
						<label class="form-check-label small" for="rm_ab_top">Remove</label>
					</div>
				@endif
			</div>
			<div class="col-md-6">
				<label class="form-label">Main photo</label>
				@php
					$abMainPrev = \App\Models\Page::mediaPublicUrl($page->section('about.photo_main_path'), $page->section('about.photo_main_url'));
				@endphp
				@if($abMainPrev)
					<div class="mb-1"><img src="{{ $abMainPrev }}" alt="" class="rounded border" style="max-height:72px;width:auto;"></div>
				@endif
				<input type="file" name="home_about_photo_main" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
				@if(filled($page->section('about.photo_main_path')))
					<div class="form-check mt-1">
						<input class="form-check-input" type="checkbox" name="remove_home_about_photo_main" value="1" id="rm_ab_main">
						<label class="form-check-label small" for="rm_ab_main">Remove</label>
					</div>
				@endif
			</div>
			<div class="col-md-6">
				<label class="form-label">Top photo alt</label>
				<input type="text" name="home_about_photo_top_alt" class="form-control" value="{{ old('home_about_photo_top_alt', $page->section('about.photo_top_alt')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Main photo alt</label>
				<input type="text" name="home_about_photo_main_alt" class="form-control" value="{{ old('home_about_photo_main_alt', $page->section('about.photo_main_alt')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Proof strip title</label>
				<input type="text" name="home_about_proof_title" class="form-control" value="{{ old('home_about_proof_title', $page->section('about.proof_title')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Proof badge (e.g. 2K+)</label>
				<input type="text" name="home_about_proof_badge" class="form-control" value="{{ old('home_about_proof_badge', $page->section('about.proof_badge')) }}">
			</div>
			<div class="col-12">
				<label class="form-label">Proof avatars <span class="text-muted small">(up to 5 images)</span></label>
				@php
					$proofP = array_pad((array) ($page->section('about.proof_avatar_paths') ?? []), 5, null);
					$proofU = array_pad((array) ($page->section('about.proof_avatar_urls') ?? []), 5, null);
				@endphp
				<div class="row g-2">
					@for($pi = 0; $pi < 5; $pi++)
						@php
							$pPrev = \App\Models\Page::mediaPublicUrl($proofP[$pi] ?? null, $proofU[$pi] ?? null);
						@endphp
						<div class="col-6 col-md-4">
							<div class="small text-muted mb-1">Avatar {{ $pi + 1 }}</div>
							@if($pPrev)
								<div class="mb-1"><img src="{{ $pPrev }}" alt="" class="rounded-circle border" style="width:40px;height:40px;object-fit:cover;"></div>
							@endif
							<input type="file" name="home_about_proof_{{ $pi }}" class="form-control form-control-sm" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
							@if(filled($proofP[$pi] ?? null))
								<div class="form-check mt-1">
									<input class="form-check-input" type="checkbox" name="remove_home_about_proof_{{ $pi }}" value="1" id="rmpr{{ $pi }}">
									<label class="form-check-label small" for="rmpr{{ $pi }}">Remove</label>
								</div>
							@endif
						</div>
					@endfor
				</div>
			</div>
			<div class="col-md-4">
				<label class="form-label">Stat 1 value</label>
				<input type="text" name="home_about_stat1_val" class="form-control" value="{{ old('home_about_stat1_val', $page->section('about.stat1_val')) }}">
			</div>
			<div class="col-md-8">
				<label class="form-label">Stat 1 label</label>
				<input type="text" name="home_about_stat1_label" class="form-control" value="{{ old('home_about_stat1_label', $page->section('about.stat1_label')) }}">
			</div>
			<div class="col-md-4">
				<label class="form-label">Stat 2 value</label>
				<input type="text" name="home_about_stat2_val" class="form-control" value="{{ old('home_about_stat2_val', $page->section('about.stat2_val')) }}">
			</div>
			<div class="col-md-8">
				<label class="form-label">Stat 2 label</label>
				<input type="text" name="home_about_stat2_label" class="form-control" value="{{ old('home_about_stat2_label', $page->section('about.stat2_label')) }}">
			</div>
			<div class="col-md-4">
				<label class="form-label">Stat 3 value</label>
				<input type="text" name="home_about_stat3_val" class="form-control" value="{{ old('home_about_stat3_val', $page->section('about.stat3_val')) }}">
			</div>
			<div class="col-md-8">
				<label class="form-label">Stat 3 label</label>
				<input type="text" name="home_about_stat3_label" class="form-control" value="{{ old('home_about_stat3_label', $page->section('about.stat3_label')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Button text</label>
				<input type="text" name="home_about_btn_text" class="form-control" value="{{ old('home_about_btn_text', $page->section('about.btn_text')) }}">
			</div>
			<div class="col-md-6">
				<label class="form-label">Button link</label>
				<input type="text" name="home_about_btn_url" class="form-control" value="{{ old('home_about_btn_url', $page->section('about.btn_url')) }}" placeholder="/properties or https://…">
			</div>
		</div>
	</div>
</div>
