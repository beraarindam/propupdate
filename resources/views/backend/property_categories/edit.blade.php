@extends('backend.layouts.master')

@section('title', 'Edit: '.$category->name)

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">Edit category</div>
			<a href="{{ route('admin.property_categories.index') }}" class="btn btn-outline-secondary btn-sm">← All categories</a>
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

		<form action="{{ route('admin.property_categories.update', $category) }}" method="post" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			@include('backend.property_categories._form', ['category' => $category, 'parentSelectOptions' => $parentSelectOptions])
			<div class="mt-3">
				<button type="submit" class="btn btn-primary px-4">Save changes</button>
			</div>
		</form>
	</div>
</div>
@endsection
