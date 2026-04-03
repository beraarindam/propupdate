@extends('backend.layouts.master')

@section('title', 'Edit: '.$post->title)

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">Edit post</div>
			<div class="d-flex flex-wrap gap-2">
				@if($post->is_published && $post->published_at)
					<a href="{{ route('blog.show', $post) }}" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener noreferrer">View live</a>
				@endif
				<a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-sm">← All posts</a>
			</div>
		</div>

		@if(session('status'))
			<div class="alert alert-success border-0 alert-dismissible fade show">
				{{ session('status') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
			</div>
		@endif

		<form id="blog_post_form" action="{{ route('admin.blogs.update', $post) }}" method="post" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			@include('backend.blogs._form', ['post' => $post])
			<div class="mt-3">
				<button type="submit" class="btn btn-primary px-4">Save changes</button>
			</div>
		</form>
		@include('backend.blogs._tinymce')
	</div>
</div>
@endsection
