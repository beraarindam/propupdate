@extends('backend.layouts.master')

@section('title', 'New project page')

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">New project page</div>
			<a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary btn-sm">← All projects</a>
		</div>

		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
			</div>
		@endif

		<form id="project_page_form" action="{{ route('admin.projects.store') }}" method="post" enctype="multipart/form-data">
			@csrf
			@include('backend.projects._form', ['project' => $project])
			<div class="mt-3">
				<button type="submit" class="btn btn-primary px-4">Create</button>
			</div>
		</form>
		@include('backend.projects._tinymce')
	</div>
</div>
@endsection
