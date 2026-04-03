@extends('backend.layouts.master')

@section('title', 'New blog post')

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">New post</div>
			<a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-sm">← All posts</a>
		</div>

		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
			</div>
		@endif

		<form id="blog_post_form" action="{{ route('admin.blogs.store') }}" method="post" enctype="multipart/form-data">
			@csrf
			@include('backend.blogs._form', ['post' => $post])
			<div class="mt-3">
				<button type="submit" class="btn btn-primary px-4">Create post</button>
			</div>
		</form>
		@include('backend.blogs._tinymce')
	</div>
</div>
@endsection
