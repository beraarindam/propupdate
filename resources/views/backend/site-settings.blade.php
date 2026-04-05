@extends('backend.layouts.master')

@section('title', 'Site settings')

@push('styles')
<style>
	.admin-site-settings .page-content { padding-bottom: 5.5rem; }
	.admin-site-settings .settings-card {
		border: 1px solid rgba(0, 0, 0, 0.06);
		box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.04);
	}
	.admin-site-settings .settings-card .card-title {
		font-size: 1rem;
		font-weight: 600;
		color: #32393f;
		border-bottom: 1px solid rgba(0, 0, 0, 0.08);
		padding-bottom: 0.75rem;
		margin-bottom: 1.25rem;
	}
	.admin-site-settings .settings-actions { margin-top: 0.25rem; }
</style>
@endpush

@section('content')
<div class="page-wrapper admin-site-settings">
	<div class="page-content">
		<div class="page-breadcrumb d-sm-flex align-items-center mb-4">
			<div class="breadcrumb-title pe-3 mb-0">Site settings</div>
		</div>

		@if(session('site_settings_status'))
			<div class="alert alert-success border-0 bg-success alert-dismissible fade show mb-4">
				<div class="text-white">{{ session('site_settings_status') }}</div>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		@if($errors->any())
			<div class="alert alert-danger mb-4">
				<ul class="mb-0">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		@if($settings->logoUrl() || $settings->faviconUrl() || ($settings->promo_popup_image_path && $settings->promoPopupBannerUrl()))
			<div class="card radius-10 settings-card mb-4">
				<div class="card-body p-4">
					<h5 class="card-title mb-0 border-0 pb-0">Uploaded assets</h5>
					<div class="d-flex flex-wrap align-items-center gap-3 mt-3">
						@if($settings->logoUrl())
							<div class="d-flex align-items-center gap-2 border rounded px-3 py-2 bg-light bg-opacity-50">
								<img src="{{ $settings->logoUrl() }}" alt="Logo" style="max-height:40px; max-width:160px; object-fit:contain;">
								<form action="{{ route('admin.site-settings.logo.destroy') }}" method="post" class="d-inline mb-0" onsubmit="return confirm('Remove current logo?');">
									@csrf
									<button type="submit" class="btn btn-sm btn-outline-danger">Remove logo</button>
								</form>
							</div>
						@endif
						@if($settings->faviconUrl())
							<div class="d-flex align-items-center gap-2 border rounded px-3 py-2 bg-light bg-opacity-50">
								<img src="{{ $settings->faviconUrl() }}" alt="Favicon" style="height:28px;width:28px;object-fit:contain;">
								<form action="{{ route('admin.site-settings.favicon.destroy') }}" method="post" class="d-inline mb-0" onsubmit="return confirm('Remove favicon?');">
									@csrf
									<button type="submit" class="btn btn-sm btn-outline-danger">Remove favicon</button>
								</form>
							</div>
						@endif
						@if($settings->promo_popup_image_path && $settings->promoPopupBannerUrl())
							<div class="d-flex align-items-center gap-2 border rounded px-3 py-2 bg-light bg-opacity-50">
								<img src="{{ $settings->promoPopupBannerUrl() }}" alt="Promo popup" style="max-height:56px; max-width:200px; object-fit:contain;">
								<form action="{{ route('admin.site-settings.promo-popup.destroy') }}" method="post" class="d-inline mb-0" onsubmit="return confirm('Remove promo popup image?');">
									@csrf
									<button type="submit" class="btn btn-sm btn-outline-danger">Remove promo image</button>
								</form>
							</div>
						@endif
					</div>
				</div>
			</div>
		@endif

		<form action="{{ route('admin.site-settings.update') }}" method="post" enctype="multipart/form-data">
			@csrf

			{{-- Row 1: equal-height pair --}}
			<div class="row g-4 align-items-stretch mb-1">
				<div class="col-12 col-lg-6 d-flex">
					<div class="card radius-10 settings-card w-100">
						<div class="card-body p-4 d-flex flex-column">
							<h5 class="card-title">Branding &amp; assets</h5>
							<div class="mb-3">
								<label class="form-label fw-semibold">Site name</label>
								<input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings->site_name) }}" placeholder="PropUpdate">
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">Tagline <span class="fw-normal text-muted small">(header / footer)</span></label>
								<input type="text" name="tagline" class="form-control" value="{{ old('tagline', $settings->tagline) }}" placeholder="Update your property search">
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">Logo <span class="fw-normal text-muted small">(PNG, JPG, SVG, WebP — max 4&nbsp;MB)</span></label>
								<input type="file" name="logo" class="form-control" accept="image/*,.svg">
							</div>
							<div class="mb-0 mt-auto">
								<label class="form-label fw-semibold">Favicon <span class="fw-normal text-muted small">(PNG, ICO, SVG — max 1&nbsp;MB)</span></label>
								<input type="file" name="favicon" class="form-control" accept=".png,.jpg,.jpeg,.gif,.svg,.ico,image/x-icon,image/png">
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-lg-6 d-flex">
					<div class="card radius-10 settings-card w-100">
						<div class="card-body p-4 d-flex flex-column">
							<h5 class="card-title">Contact</h5>
							<div class="mb-3">
								<label class="form-label fw-semibold">Email</label>
								<input type="email" name="email" class="form-control" value="{{ old('email', $settings->email) }}">
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">Phone <span class="fw-normal text-muted small">(10 digits or +91…)</span></label>
								<input type="text" name="phone" class="form-control" value="{{ old('phone', $settings->phone) }}">
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">WhatsApp <span class="fw-normal text-muted small">(e.g. 91…)</span></label>
								<input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $settings->whatsapp) }}">
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">Website URL</label>
								<input type="url" name="website_url" class="form-control" value="{{ old('website_url', $settings->website_url) }}" placeholder="https://">
							</div>
							<div class="mb-0 flex-grow-1 d-flex flex-column">
								<label class="form-label fw-semibold">Address</label>
								<textarea name="address" class="form-control flex-grow-1" rows="5" style="min-height: 7.5rem;">{{ old('address', $settings->address) }}</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row g-4 align-items-stretch mb-4">
				<div class="col-12">
					<div class="card radius-10 settings-card">
						<div class="card-body p-4">
							<h5 class="card-title">Promo popup <span class="fw-normal text-muted small">(homepage &amp; site-wide on first visit)</span></h5>
							<p class="text-muted small mb-3">Shows a full-screen style overlay when visitors open the site. After they close it, it stays hidden until you change the image, URL, or link (or they clear browser data).</p>
							<div class="form-check form-switch mb-3">
								<input class="form-check-input" type="checkbox" name="promo_popup_enabled" value="1" id="promo_popup_enabled" @checked(old('promo_popup_enabled', $settings->promo_popup_enabled ?? false))>
								<label class="form-check-label" for="promo_popup_enabled">Enable promo popup</label>
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">Banner image <span class="fw-normal text-muted small">(upload — max 5&nbsp;MB)</span></label>
								<input type="file" name="promo_popup_image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">Or image URL <span class="fw-normal text-muted small">(if no upload)</span></label>
								<input type="text" name="promo_popup_image_url" class="form-control" value="{{ old('promo_popup_image_url', $settings->promo_popup_image_url) }}" placeholder="https://…">
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">Click-through link <span class="fw-normal text-muted small">(optional)</span></label>
								<input type="text" name="promo_popup_link_url" class="form-control" value="{{ old('promo_popup_link_url', $settings->promo_popup_link_url) }}" placeholder="https://… or /properties">
							</div>
							<div class="form-check mb-0">
								<input class="form-check-input" type="checkbox" name="remove_promo_popup_image" value="1" id="remove_promo_popup_image">
								<label class="form-check-label text-muted small" for="remove_promo_popup_image">Remove uploaded promo image on save (keeps URL field)</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			{{-- Row 2: equal-height pair --}}
			<div class="row g-4 align-items-stretch mb-4">
				<div class="col-12 col-lg-6 d-flex">
					<div class="card radius-10 settings-card w-100">
						<div class="card-body p-4 d-flex flex-column">
							<h5 class="card-title">Social profiles</h5>
							<div class="mb-3">
								<label class="form-label fw-semibold">Facebook</label>
								<input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings->facebook_url) }}" placeholder="https://">
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">Instagram</label>
								<input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $settings->instagram_url) }}" placeholder="https://">
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">YouTube</label>
								<input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $settings->youtube_url) }}" placeholder="https://">
							</div>
							<div class="mb-3">
								<label class="form-label fw-semibold">LinkedIn</label>
								<input type="url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $settings->linkedin_url) }}" placeholder="https://">
							</div>
							<div class="mb-0">
								<label class="form-label fw-semibold">X (Twitter)</label>
								<input type="url" name="twitter_url" class="form-control" value="{{ old('twitter_url', $settings->twitter_url) }}" placeholder="https://">
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-lg-6 d-flex">
					<div class="card radius-10 settings-card w-100">
						<div class="card-body p-4 d-flex flex-column">
							<h5 class="card-title">SEO &amp; footer</h5>
							<div class="mb-3">
								<label class="form-label fw-semibold">Meta title</label>
								<input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $settings->meta_title) }}">
							</div>
							<div class="mb-3 flex-grow-1 d-flex flex-column">
								<label class="form-label fw-semibold">Meta description</label>
								<textarea name="meta_description" class="form-control flex-grow-1" rows="4" style="min-height: 6rem;">{{ old('meta_description', $settings->meta_description) }}</textarea>
							</div>
							<div class="mb-0">
								<label class="form-label fw-semibold">Footer intro text</label>
								<textarea name="footer_text" class="form-control" rows="4" placeholder="Short paragraph under the logo in the footer.">{{ old('footer_text', $settings->footer_text) }}</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="w-100">
				<div class="card radius-10 settings-actions border-primary border-opacity-25 shadow-sm">
					<div class="card-body p-4">
						<div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-3">
							<div class="order-2 order-md-1">
								<button type="submit" class="btn btn-primary px-5 py-2">Save settings</button>
							</div>
							<p class="text-muted small mb-0 order-1 order-md-2 text-md-end" style="max-width: 32rem;">
								Uploads save to <code>storage/app/public/site</code>. Run <code>php artisan storage:link</code> if images do not load.
							</p>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
