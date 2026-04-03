@extends('backend.layouts.master')

@section('title', 'Edit: '.$page->name)

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

		<form action="{{ route('admin.pages.update', $page) }}" method="post">
			@csrf
			@method('PUT')

			<div class="row g-4">
				<div class="col-lg-4">
					<div class="card radius-10 border h-100">
						<div class="card-body">
							<h5 class="mb-3 pb-2 border-bottom">General</h5>
							<div class="mb-3">
								<label class="form-label">Admin label</label>
								<input type="text" name="name" class="form-control" value="{{ old('name', $page->name) }}" required>
							</div>
							<div class="mb-3">
								<label class="form-label text-muted small">Slug (read only)</label>
								<input type="text" class="form-control bg-light" value="{{ $page->slug }}" disabled>
							</div>
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" name="is_published" value="1" id="pub" @checked(old('is_published', $page->is_published))>
								<label class="form-check-label" for="pub">Published</label>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-8">
					<div class="card radius-10 border h-100">
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
					<div class="col-12">
						<div class="card radius-10 border">
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
									<div class="col-12">
										<label class="form-label">Hero background image URL</label>
										<input type="text" name="hero_bg_url" class="form-control" value="{{ old('hero_bg_url', $page->hero('bg_url')) }}" placeholder="https://…">
									</div>
								</div>
							</div>
						</div>
					</div>
				@else
					<div class="col-12">
						<div class="card radius-10 border">
							<div class="card-body">
								<h5 class="mb-3 pb-2 border-bottom">Page banner</h5>
								<div class="row g-3">
									<div class="col-md-6">
										<label class="form-label">Banner title (H1)</label>
										<input type="text" name="banner_title" class="form-control" value="{{ old('banner_title', $page->banner_title) }}">
									</div>
									<div class="col-md-6">
										<label class="form-label">Banner background image URL</label>
										<input type="text" name="banner_image_url" class="form-control" value="{{ old('banner_image_url', $page->banner_image_url) }}" placeholder="https://…">
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

				<div class="col-12">
					<div class="card radius-10 border">
						<div class="card-body">
							<h5 class="mb-3 pb-2 border-bottom">
								Main content
								@if(in_array($page->slug, ['privacy-policy', 'terms-and-conditions'], true))
									<span class="text-muted small fw-normal"> — leave empty to use the built‑in legal template; or paste full HTML to replace it</span>
								@endif
							</h5>
							<textarea name="body_html" class="form-control font-monospace small" rows="16" placeholder="Optional HTML (trusted admin only).">{{ old('body_html', $page->body_html) }}</textarea>
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
