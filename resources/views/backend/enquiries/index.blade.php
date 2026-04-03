@extends('backend.layouts.master')

@section('title', 'Enquiries')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" crossorigin="anonymous">
@endpush

@section('content')
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb d-sm-flex align-items-center mb-4">
			<div class="breadcrumb-title pe-3 mb-0">Enquiries</div>
		</div>

		@if(session('status'))
			<div class="alert alert-success border-0 alert-dismissible fade show">
				{{ session('status') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		<div class="card radius-10">
			<div class="card-body">
				<p class="text-secondary small mb-3">Messages from the <strong>contact</strong> page and <strong>pre-register</strong> form. Table uses server-side paging and search.</p>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0 w-100" id="enquiryTable" style="width:100%">
						<thead class="table-light">
							<tr>
								<th>Received</th>
								<th>Source</th>
								<th>Name</th>
								<th>Email</th>
								<th class="text-center">Read</th>
								<th class="text-end">Action</th>
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
	$('#enquiryTable').DataTable({
		processing: true,
		serverSide: true,
		ajax: @json(route('admin.enquiries.data')),
		order: [[0, 'desc']],
		pageLength: 25,
		lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
		columnDefs: [
			{ orderable: false, targets: 5 },
			{ className: 'text-center', targets: 4 },
			{ className: 'text-end', targets: 5 }
		],
		autoWidth: false,
		createdRow: function (row, data) {
			if (typeof data[4] === 'string' && data[4].indexOf('bx-envelope') !== -1) {
				$(row).addClass('table-light fw-semibold');
			}
		}
	});
});
</script>
@endpush
