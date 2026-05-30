@extends('backend.layouts.master')

@section('title', 'Add review')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb d-sm-flex align-items-center mb-4">
			<div class="breadcrumb-title pe-3 mb-0">Add review</div>
		</div>

		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
			</div>
		@endif

		<form method="post" action="{{ route('admin.client_reviews.store') }}" enctype="multipart/form-data">
			@csrf
			@include('backend.client_reviews._form')
			<div class="mt-4">
				<button type="submit" class="btn btn-primary px-4">Create review</button>
				<a href="{{ route('admin.client_reviews.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
			</div>
		</form>
	</div>
</div>
@endsection
