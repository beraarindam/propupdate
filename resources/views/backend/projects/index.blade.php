@extends('backend.layouts.master')

@section('title', 'Projects')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" crossorigin="anonymous">
@endpush

@section('content')
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">Project pages</div>
			<div class="d-flex flex-wrap gap-2">
				<a href="{{ route('projects.index') }}" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener noreferrer">View on site</a>
				<a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">New project</a>
			</div>
		</div>

		@if(session('status'))
			<div class="alert alert-success border-0 alert-dismissible fade show">
				{{ session('status') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		<div class="card radius-10">
			<div class="card-body">
				<p class="text-secondary small mb-3">Marketing pages for new launches and developments. Public URL: <code>/projects/your-slug</code>. Set <strong>SEO</strong> on each record for search engines.</p>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0 w-100" id="projectsTable" style="width:100%">
						<thead class="table-light">
							<tr>
								<th>Order</th>
								<th>Title</th>
								<th>Slug</th>
								<th>Status</th>
								<th>Updated</th>
								<th class="text-end">Actions</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
<script>
$(function () {
	$('#projectsTable').DataTable({
		processing: true,
		serverSide: true,
		ajax: @json(route('admin.projects.data')),
		order: [[0, 'asc']],
		pageLength: 25,
		lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
		columnDefs: [
			{ orderable: false, targets: [2, 5] },
			{ className: 'text-end', targets: 5 }
		],
		autoWidth: false
	});
});
</script>
@endpush
