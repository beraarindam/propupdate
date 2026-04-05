@extends('backend.layouts.master')

@section('title', 'Edit: '.$page->name)

@php
	$bannerPreview = $page->bannerBackgroundUrl();
	if ($page->slug === 'home') {
		$catSource = old('home_cat_source', $page->section('categories.source') ?? 'property_categories');
		$catItems = array_pad((array) ($page->section('categories.items') ?? []), 4, []);
	}
@endphp

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">Edit page: {{ $page->name }}</div>
			<a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary btn-sm">← All pages</a>
		</div>

		@if(session('page_status'))
			<div class="alert alert-success border-0 alert-dismissible fade show">
				{{ session('page_status') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
			</div>
		@endif

		<form action="{{ route('admin.pages.update', $page) }}" method="post" enctype="multipart/form-data">
			@csrf
			@method('PUT')

			<div class="row g-4">
				<div class="col-12">
					<div class="card radius-10 border">
						<div class="card-body pb-2">
							<ul class="nav nav-tabs flex-nowrap gap-1 border-bottom-0" id="pageEditTabs" role="tablist" style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
								<li class="nav-item" role="presentation">
									<button class="nav-link active text-nowrap" id="tab-general" data-bs-toggle="tab" data-bs-target="#pane-general" type="button" role="tab" aria-controls="pane-general" aria-selected="true">General</button>
								</li>
								<li class="nav-item" role="presentation">
									<button class="nav-link text-nowrap" id="tab-seo" data-bs-toggle="tab" data-bs-target="#pane-seo" type="button" role="tab" aria-controls="pane-seo" aria-selected="false">SEO</button>
								</li>
								@if($page->slug === 'home')
									<li class="nav-item" role="presentation">
										<button class="nav-link text-nowrap" id="tab-hero" data-bs-toggle="tab" data-bs-target="#pane-hero" type="button" role="tab">Hero &amp; banner</button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link text-nowrap" id="tab-categories" data-bs-toggle="tab" data-bs-target="#pane-categories" type="button" role="tab">Categories</button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link text-nowrap" id="tab-about" data-bs-toggle="tab" data-bs-target="#pane-about" type="button" role="tab">About</button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link text-nowrap" id="tab-why" data-bs-toggle="tab" data-bs-target="#pane-why" type="button" role="tab">Why us</button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link text-nowrap" id="tab-resale" data-bs-toggle="tab" data-bs-target="#pane-resale" type="button" role="tab">Resale</button>
									</li>
									<li class="nav-item" role="presentation">
										<button class="nav-link text-nowrap" id="tab-services-launches" data-bs-toggle="tab" data-bs-target="#pane-services-launches" type="button" role="tab">Services &amp; launches</button>
									</li>
								@else
									@if($page->slug === 'about-us')
										<li class="nav-item" role="presentation">
											<button class="nav-link text-nowrap" id="tab-about-page" data-bs-toggle="tab" data-bs-target="#pane-about-page" type="button" role="tab">About page</button>
										</li>
									@endif
									@if(in_array($page->slug, ['properties', 'projects', 'new-launches'], true))
										<li class="nav-item" role="presentation">
											<button class="nav-link text-nowrap" id="tab-listing" data-bs-toggle="tab" data-bs-target="#pane-listing" type="button" role="tab">Listing layout</button>
										</li>
									@endif
									<li class="nav-item" role="presentation">
										<button class="nav-link text-nowrap" id="tab-banner" data-bs-toggle="tab" data-bs-target="#pane-banner" type="button" role="tab">Breadcrumb &amp; banner</button>
									</li>
								@endif
								<li class="nav-item" role="presentation">
									<button class="nav-link text-nowrap" id="tab-content" data-bs-toggle="tab" data-bs-target="#pane-content" type="button" role="tab">Main content</button>
								</li>
							</ul>
						</div>
						<div class="card-body border-top pt-4">
							<div class="tab-content" id="pageEditTabContent">
								<div class="tab-pane fade show active" id="pane-general" role="tabpanel" aria-labelledby="tab-general" tabindex="0">
									<div class="card radius-10 border bg-light">
										<div class="card-body">
											<h5 class="mb-3 pb-2 border-bottom">General</h5>
											<div class="row g-3">
												<div class="col-md-6 col-lg-4">
													<label class="form-label">Admin label</label>
													<input type="text" name="name" class="form-control" value="{{ old('name', $page->name) }}" required>
												</div>
												<div class="col-md-6 col-lg-4">
													<label class="form-label text-muted small">Slug (read only)</label>
													<input type="text" class="form-control bg-light" value="{{ $page->slug }}" disabled>
												</div>
												<div class="col-12 col-lg-4 d-flex align-items-end">
													<div class="form-check form-switch">
														<input class="form-check-input" type="checkbox" name="is_published" value="1" id="pub" @checked(old('is_published', $page->is_published))>
														<label class="form-check-label" for="pub">Published</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="tab-pane fade" id="pane-seo" role="tabpanel" aria-labelledby="tab-seo" tabindex="0">
									<div class="card radius-10 border bg-light">
										<div class="card-body">
											<h5 class="mb-3 pb-2 border-bottom">SEO</h5>
											<div class="mb-3">
												<label class="form-label">Meta title</label>
												<input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}" placeholder="Shown in search results &amp; browser tab">
											</div>
											<div class="mb-3">
												<label class="form-label">Meta description</label>
												<textarea name="meta_description" class="form-control" rows="3" placeholder="Short summary for Google/snippets">{{ old('meta_description', $page->meta_description) }}</textarea>
											</div>
											<div class="mb-0">
												<label class="form-label">Meta keywords <span class="text-muted small">(optional)</span></label>
												<input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $page->meta_keywords) }}" placeholder="comma, separated, keywords">
											</div>
										</div>
									</div>
								</div>

								@if($page->slug === 'home')
									<div class="tab-pane fade" id="pane-hero" role="tabpanel" aria-labelledby="tab-hero" tabindex="0">
										<div class="card radius-10 border mb-4">
											<div class="card-body">
												<h5 class="mb-3 pb-2 border-bottom">Home hero</h5>
												<div class="row g-3">
													<div class="col-md-6">
														<label class="form-label">Hero line 1</label>
														<input type="text" name="hero_line1" class="form-control" value="{{ old('hero_line1', $page->hero('line1')) }}">
													</div>
													<div class="col-md-6">
														<label class="form-label">Hero line 2 (brand)</label>
														<input type="text" name="hero_line2" class="form-control" value="{{ old('hero_line2', $page->hero('line2')) }}">
													</div>
													<div class="col-md-6">
														<label class="form-label">Hero subtitle</label>
														<input type="text" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle', $page->hero('subtitle')) }}">
													</div>
													<div class="col-md-6">
														<label class="form-label">Search placeholder</label>
														<input type="text" name="hero_search_placeholder" class="form-control" value="{{ old('hero_search_placeholder', $page->hero('search_placeholder')) }}">
													</div>
													<div class="col-md-12">
														<label class="form-label">Hero background alt text <span class="text-muted small">(accessibility)</span></label>
														<input type="text" name="hero_bg_alt" class="form-control" value="{{ old('hero_bg_alt', $page->hero('bg_alt')) }}">
														<p class="text-muted small mt-2 mb-0">Hero background image: upload under <strong>Banner / hero background</strong> below on this tab.</p>
													</div>
												</div>
											</div>
										</div>
										<div class="card radius-10 border">
											<div class="card-body">
												<h5 class="mb-3 pb-2 border-bottom">Banner / hero background image</h5>
												<p class="text-muted small mb-3">Upload an image for inner-page banners and the home hero. Legacy URL-only banners from older data still show until you upload a replacement.</p>
												@if($bannerPreview)
													<div class="mb-3">
														<div class="text-muted small mb-1">Current image</div>
														<img src="{{ $bannerPreview }}" alt="" class="img-fluid rounded border" style="max-height: 140px; width: auto;">
													</div>
												@endif
												<div class="mb-3">
													<label class="form-label">Upload image</label>
													<input type="file" name="banner_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
												</div>
												@if($page->banner_image_path || filled($page->banner_image_url))
													<div class="form-check mb-0">
														<input class="form-check-input" type="checkbox" name="remove_banner_image" value="1" id="remove_banner_image" @checked(old('remove_banner_image'))>
														<label class="form-check-label" for="remove_banner_image">Remove banner image (uploaded or legacy URL)</label>
													</div>
												@endif
											</div>
										</div>
									</div>

									<div class="tab-pane fade" id="pane-categories" role="tabpanel" aria-labelledby="tab-categories" tabindex="0">
										@include('backend.pages._home_categories_card')
									</div>

									<div class="tab-pane fade" id="pane-about" role="tabpanel" aria-labelledby="tab-about" tabindex="0">
										@include('backend.pages._home_about_card')
									</div>

									<div class="tab-pane fade" id="pane-why" role="tabpanel" aria-labelledby="tab-why" tabindex="0">
										@include('backend.pages._home_why_card')
									</div>

									<div class="tab-pane fade" id="pane-resale" role="tabpanel" aria-labelledby="tab-resale" tabindex="0">
										@include('backend.pages._home_resale_card')
									</div>

									<div class="tab-pane fade" id="pane-services-launches" role="tabpanel" aria-labelledby="tab-services-launches" tabindex="0">
										<div class="d-flex flex-column gap-4">
											@include('backend.pages._home_services_card')
											@include('backend.pages._home_launches_card')
										</div>
									</div>
								@else
									@if($page->slug === 'about-us')
										<div class="tab-pane fade" id="pane-about-page" role="tabpanel" aria-labelledby="tab-about-page" tabindex="0">
											@include('backend.pages._about_us_edit')
										</div>
									@endif
									@if(in_array($page->slug, ['properties', 'projects', 'new-launches'], true))
										<div class="tab-pane fade" id="pane-listing" role="tabpanel" aria-labelledby="tab-listing" tabindex="0">
											@include('backend.pages._listing_index_edit')
										</div>
									@endif
									<div class="tab-pane fade" id="pane-banner" role="tabpanel" aria-labelledby="tab-banner" tabindex="0">
										<div class="card radius-10 border mb-4">
											<div class="card-body">
												<h5 class="mb-3 pb-2 border-bottom">Breadcrumb / banner background image</h5>
												<p class="text-muted small mb-3">This image appears behind the page title strip at the top (breadcrumb area). Upload a file here; legacy URL-only banners still work until you replace them.</p>
												@if($bannerPreview)
													<div class="mb-3">
														<div class="text-muted small mb-1">Current image</div>
														<img src="{{ $bannerPreview }}" alt="" class="img-fluid rounded border" style="max-height: 140px; width: auto;">
													</div>
												@endif
												<div class="mb-3">
													<label class="form-label">Upload image</label>
													<input type="file" name="banner_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
												</div>
												@if($page->banner_image_path || filled($page->banner_image_url))
													<div class="form-check mb-0">
														<input class="form-check-input" type="checkbox" name="remove_banner_image" value="1" id="remove_banner_image" @checked(old('remove_banner_image'))>
														<label class="form-check-label" for="remove_banner_image">Remove banner image (uploaded or legacy URL)</label>
													</div>
												@endif
											</div>
										</div>
										<div class="card radius-10 border">
											<div class="card-body">
												<h5 class="mb-3 pb-2 border-bottom">Page banner text</h5>
												<div class="row g-3">
													<div class="col-md-6">
														<label class="form-label">Banner title (H1)</label>
														<input type="text" name="banner_title" class="form-control" value="{{ old('banner_title', $page->banner_title) }}">
													</div>
													<div class="col-12">
														<label class="form-label">Banner lead <span class="text-muted small">(HTML allowed, e.g. &lt;strong&gt;)</span></label>
														<textarea name="banner_lead" class="form-control font-monospace small" rows="3">{{ old('banner_lead', $page->banner_lead) }}</textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								@endif

								<div class="tab-pane fade" id="pane-content" role="tabpanel" aria-labelledby="tab-content" tabindex="0">
									<div class="card radius-10 border bg-light">
										<div class="card-body">
											<h5 class="mb-3 pb-2 border-bottom">
												Main content
												@if($page->slug === 'home')
													<span class="text-muted small fw-normal"> — optional HTML below the hero/category strip</span>
												@elseif(in_array($page->slug, ['privacy-policy', 'terms-and-conditions'], true))
													<span class="text-muted small fw-normal"> — leave empty for built‑in legal template, or paste full HTML</span>
												@elseif(in_array($page->slug, ['properties', 'projects', 'new-launches'], true))
													<span class="text-muted small fw-normal"> — optional intro HTML above the listing grid</span>
												@endif
											</h5>
											<textarea name="body_html" class="form-control font-monospace small" rows="16" placeholder="Optional HTML (trusted admin only).">{{ old('body_html', $page->body_html) }}</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12">
					<button type="submit" class="btn btn-primary px-5">Save page</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
