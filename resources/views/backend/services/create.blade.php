@extends('backend.layouts.master')

@section('title', 'Add service')

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">New service</div>
			<a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary btn-sm">← All services</a>
		</div>

		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
			</div>
		@endif

		<form action="{{ route('admin.services.store') }}" method="post">
			@csrf
			@include('backend.services._form', ['service' => $service])
			<div class="mt-3">
				<button type="submit" class="btn btn-primary px-4">Save service</button>
			</div>
		</form>
	</div>
</div>
@endsection
