@extends('backend.layouts.master')

@section('title', 'Add FAQ')

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">New FAQ</div>
			<a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary btn-sm">← All FAQs</a>
		</div>

		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
			</div>
		@endif

		<form action="{{ route('admin.faqs.store') }}" method="post">
			@csrf
			@include('backend.faqs._form', ['faq' => $faq])
			<div class="mt-3">
				<button type="submit" class="btn btn-primary px-4">Save FAQ</button>
			</div>
		</form>
	</div>
</div>
@endsection
