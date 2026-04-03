@extends('backend.layouts.master')

@section('title', 'New property')

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">New property</div>
			<a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary btn-sm">← All properties</a>
		</div>

		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
			</div>
		@endif

		<form id="property_form" action="{{ route('admin.properties.store') }}" method="post" enctype="multipart/form-data">
			@csrf
			@include('backend.properties._form', ['property' => $property, 'categories' => $categories, 'types' => $types])
			<div class="mt-3">
				<button type="submit" class="btn btn-primary px-4">Create listing</button>
			</div>
		</form>
		@include('backend.properties._tinymce')
	</div>
</div>
@endsection
