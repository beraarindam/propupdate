@extends('backend.layouts.master')

@section('title', 'New property area')

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">New area</div>
			<a href="{{ route('admin.property_areas.index') }}" class="btn btn-outline-secondary btn-sm">← All areas</a>
		</div>

		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
			</div>
		@endif

		<form action="{{ route('admin.property_areas.store') }}" method="post" enctype="multipart/form-data">
			@csrf
			@include('backend.property_areas._form', ['area' => $area])
			<div class="mt-3">
				<button type="submit" class="btn btn-primary px-4">Create area</button>
			</div>
		</form>
	</div>
</div>
@endsection
