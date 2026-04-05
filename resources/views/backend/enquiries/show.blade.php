@extends('backend.layouts.master')

@section('title', 'Enquiry #'.$enquiry->id)

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">Enquiry #{{ $enquiry->id }}</div>
			<a href="{{ route('admin.enquiries.index') }}" class="btn btn-outline-secondary btn-sm">← All enquiries</a>
		</div>

		<div class="card radius-10 mb-3">
			<div class="card-body">
				<div class="row g-3">
					<div class="col-md-6">
						<p class="text-muted small mb-1">Source</p>
						<p class="mb-0 fw-semibold">{{ $enquiry->sourceLabel() }}</p>
					</div>
					<div class="col-md-6">
						<p class="text-muted small mb-1">Received</p>
						<p class="mb-0">{{ $enquiry->created_at->format('l, F j, Y \a\t g:i A') }}</p>
					</div>
					@if($enquiry->property)
						<div class="col-12">
							<p class="text-muted small mb-1">Related listing</p>
							<p class="mb-0">
								<a href="{{ route('admin.properties.edit', $enquiry->property) }}">{{ $enquiry->property->title }}</a>
								<span class="text-muted small ms-2">·</span>
								<a href="{{ route('properties.show', $enquiry->property) }}" target="_blank" rel="noopener" class="small">View on site</a>
							</p>
						</div>
					@endif
					@if($enquiry->exclusiveResaleListing)
						<div class="col-12">
							<p class="text-muted small mb-1">Exclusive resale card</p>
							<p class="mb-0">
								<a href="{{ route('admin.exclusive_resale_listings.edit', $enquiry->exclusiveResaleListing) }}">{{ $enquiry->exclusiveResaleListing->displayCode() }} — {{ $enquiry->exclusiveResaleListing->title }}</a>
								<span class="text-muted small ms-2">·</span>
								<a href="{{ route('exclusive-resale.index') }}#er-{{ $enquiry->exclusiveResaleListing->id }}" target="_blank" rel="noopener" class="small">View on site</a>
							</p>
						</div>
					@endif
					@if($enquiry->project)
						<div class="col-12">
							<p class="text-muted small mb-1">Related project</p>
							<p class="mb-0">
								<a href="{{ route('admin.projects.edit', $enquiry->project) }}">{{ $enquiry->project->title }}</a>
								<span class="text-muted small ms-2">·</span>
								<a href="{{ route('projects.show', $enquiry->project) }}" target="_blank" rel="noopener" class="small">View on site</a>
							</p>
						</div>
					@endif
					<div class="col-md-6">
						<p class="text-muted small mb-1">Name</p>
						<p class="mb-0">{{ $enquiry->name }}</p>
					</div>
					<div class="col-md-6">
						<p class="text-muted small mb-1">Email</p>
						<p class="mb-0"><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></p>
					</div>
					@if($enquiry->phone)
						<div class="col-md-6">
							<p class="text-muted small mb-1">Phone</p>
							<p class="mb-0"><a href="tel:{{ preg_replace('/\s+/', '', $enquiry->phone) }}">{{ $enquiry->phone }}</a></p>
						</div>
					@endif
					@if($enquiry->subject)
						<div class="col-12">
							<p class="text-muted small mb-1">Subject</p>
							<p class="mb-0">{{ $enquiry->subject }}</p>
						</div>
					@endif
					@if($enquiry->message)
						<div class="col-12">
							<p class="text-muted small mb-1">Message</p>
							<div class="border rounded p-3 bg-light" style="white-space: pre-wrap;">{{ $enquiry->message }}</div>
						</div>
					@endif
					@if($enquiry->ip_address)
						<div class="col-12">
							<p class="text-muted small mb-1">IP address</p>
							<p class="mb-0 font-monospace small">{{ $enquiry->ip_address }}</p>
						</div>
					@endif
				</div>
			</div>
		</div>

		<form action="{{ route('admin.enquiries.destroy', $enquiry) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this enquiry permanently?');">
			@csrf
			@method('DELETE')
			<button type="submit" class="btn btn-outline-danger">Delete enquiry</button>
		</form>
	</div>
</div>
@endsection
