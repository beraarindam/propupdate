<div class="card radius-10 border">
	<div class="card-body">
		<h5 class="mb-3 pb-2 border-bottom">Home — exclusive resale</h5>
		<div class="row g-3">
			<div class="col-12">
				<label class="form-label">Section heading (H2)</label>
				<input type="text" name="home_resale_heading" class="form-control" value="{{ old('home_resale_heading', $page->section('resale.heading')) }}">
			</div>
			<div class="col-md-8">
				<label class="form-label">Hero image</label>
				@php
					$rsPrev = \App\Models\Page::mediaPublicUrl($page->section('resale.hero_image_path'), $page->section('resale.hero_image_url'));
				@endphp
				@if($rsPrev)
					<div class="mb-1"><img src="{{ $rsPrev }}" alt="" class="rounded border" style="max-height:100px;width:auto;"></div>
				@endif
				<input type="file" name="home_resale_hero_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
				@if(filled($page->section('resale.hero_image_path')))
					<div class="form-check mt-1">
						<input class="form-check-input" type="checkbox" name="remove_home_resale_hero_image" value="1" id="rm_rs_hero">
						<label class="form-check-label small" for="rm_rs_hero">Remove</label>
					</div>
				@endif
			</div>
			<div class="col-md-4">
				<label class="form-label">Hero image alt</label>
				<input type="text" name="home_resale_hero_alt" class="form-control" value="{{ old('home_resale_hero_alt', $page->section('resale.hero_alt')) }}">
			</div>
			@for($i = 1; $i <= 4; $i++)
				@php
					$card = $page->section('resale.cards.'.($i - 1)) ?? [];
				@endphp
				<div class="col-12 border-top pt-3 mt-1">
					<div class="fw-semibold small text-muted mb-2">Card {{ $i }}</div>
					<div class="row g-2">
						<div class="col-md-3">
							<label class="form-label small">Icon class</label>
							<input type="text" name="home_resale_card_{{ $i }}_icon" class="form-control form-control-sm" value="{{ old('home_resale_card_'.$i.'_icon', $card['icon'] ?? '') }}" placeholder="fa-solid fa-tags">
						</div>
						<div class="col-md-4">
							<label class="form-label small">Title</label>
							<input type="text" name="home_resale_card_{{ $i }}_title" class="form-control form-control-sm" value="{{ old('home_resale_card_'.$i.'_title', $card['title'] ?? '') }}">
						</div>
						<div class="col-md-5">
							<label class="form-label small">Text</label>
							<input type="text" name="home_resale_card_{{ $i }}_text" class="form-control form-control-sm" value="{{ old('home_resale_card_'.$i.'_text', $card['text'] ?? '') }}">
						</div>
					</div>
				</div>
			@endfor
		</div>
	</div>
</div>
