@extends('backend.layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb mb-4">
			<div class="breadcrumb-title pe-3 mb-0">PropUpdate overview</div>
			<p class="text-secondary small mb-0">Live counts from your listings, enquiries, and content.</p>
		</div>

		<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
			<div class="col">
				<div class="card radius-10 border-start border-0 border-4 border-info h-100">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary">Live listings</p>
								<h4 class="my-1 text-info">{{ number_format($propertiesLive) }}</h4>
								<p class="mb-0 font-13 text-muted">Published on the website</p>
							</div>
							<div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bxs-buildings'></i></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card radius-10 border-start border-0 border-4 border-primary h-100">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary">All properties</p>
								<h4 class="my-1 text-primary">{{ number_format($propertiesTotal) }}</h4>
								<p class="mb-0 font-13 text-muted">{{ $propertiesDraft > 0 ? number_format($propertiesDraft).' draft(s) not live' : 'No drafts' }}</p>
							</div>
							<div class="widgets-icons-2 rounded-circle bg-gradient-deepblue text-white ms-auto"><i class='bx bxs-folder'></i></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card radius-10 border-start border-0 border-4 border-danger h-100">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary">Enquiries</p>
								<h4 class="my-1 text-danger">{{ number_format($enquiriesTotal) }}</h4>
								<p class="mb-0 font-13 text-muted">{{ $enquiriesUnread > 0 ? number_format($enquiriesUnread).' unread' : 'All read' }}</p>
							</div>
							<div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bxs-envelope'></i></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card radius-10 border-start border-0 border-4 border-success h-100">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary">Content</p>
								<h4 class="my-1 text-success">{{ number_format($categoriesPublished) }}</h4>
								<p class="mb-0 font-13 text-muted">Published categories · {{ number_format($galleryPublished) }} gallery · {{ number_format($blogPublished) }} blog</p>
							</div>
							<div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-grid-alt'></i></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row g-3 mb-4">
			<div class="col-12 col-lg-8 d-flex">
				<div class="card radius-10 w-100">
					<div class="card-header border-0">
						<h6 class="mb-0">Enquiries by month</h6>
						<small class="text-muted">Last 6 months (contact, pre-register, property)</small>
					</div>
					<div class="card-body">
						<div class="chart-container-1" style="position:relative;height:280px;">
							<canvas id="puDashChartEnquiries"></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-4 d-flex">
				<div class="card radius-10 w-100">
					<div class="card-header border-0">
						<h6 class="mb-0">Listings by category</h6>
						<small class="text-muted">Top {{ count($categoryLabels) }} groups</small>
					</div>
					<div class="card-body">
						<div class="chart-container-2" style="position:relative;height:220px;">
							<canvas id="puDashChartCategories"></canvas>
						</div>
					</div>
					<ul class="list-group list-group-flush">
						@foreach($categoryLabels as $idx => $label)
							<li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
								<span class="text-truncate me-2" title="{{ $label }}">{{ $label }}</span>
								<span class="badge bg-primary rounded-pill">{{ $categoryData[$idx] ?? 0 }}</span>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>

		<div class="row g-3 mb-4">
			<div class="col-12">
				<div class="d-flex flex-wrap gap-2">
					<a href="{{ route('admin.properties.create') }}" class="btn btn-primary btn-sm"><i class='bx bx-plus me-1'></i>New property</a>
					<a href="{{ route('admin.properties.index') }}" class="btn btn-outline-primary btn-sm">All listings</a>
					<a href="{{ route('admin.enquiries.index') }}" class="btn btn-outline-danger btn-sm">Enquiries</a>
					<a href="{{ route('admin.gallery_items.index') }}" class="btn btn-outline-secondary btn-sm">Gallery</a>
					<a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary btn-sm">Projects</a>
					<a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-sm">Blog</a>
				</div>
			</div>
		</div>

		<div class="card radius-10">
			<div class="card-header border-0 d-flex align-items-center flex-wrap gap-2">
				<h6 class="mb-0">Recent enquiries</h6>
				<a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-outline-primary ms-auto">View all</a>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table align-middle mb-0">
						<thead class="table-light">
							<tr>
								<th>Received</th>
								<th>Source</th>
								<th>Name</th>
								<th>Email</th>
								<th>Status</th>
								<th class="text-end">Action</th>
							</tr>
						</thead>
						<tbody>
							@forelse($recentEnquiries as $enq)
								<tr>
									<td class="text-nowrap small text-muted">{{ $enq->created_at?->format('M j, Y g:i A') }}</td>
									<td><span class="badge bg-light text-dark border">{{ $enq->sourceLabel() }}</span></td>
									<td class="fw-semibold">{{ $enq->name }}</td>
									<td class="small"><a href="mailto:{{ $enq->email }}">{{ $enq->email }}</a></td>
									<td>
										@if($enq->read_at)
											<span class="badge bg-success">Read</span>
										@else
											<span class="badge bg-warning text-dark">Unread</span>
										@endif
									</td>
									<td class="text-end">
										<a href="{{ route('admin.enquiries.show', $enq) }}" class="btn btn-sm btn-primary">Open</a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="6" class="text-center text-muted py-4">No enquiries yet.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
	var labels = @json($enquiryChartLabels);
	var dataEnq = @json($enquiryChartData);
	var catLabels = @json($categoryLabels);
	var catData = @json($categoryData);

	function run() {
		if (typeof Chart === 'undefined') return;
		var el1 = document.getElementById('puDashChartEnquiries');
		if (el1) {
			var g1 = el1.getContext('2d');
			var grad1 = g1.createLinearGradient(0, 0, 0, 280);
			grad1.addColorStop(0, '#6078ea');
			grad1.addColorStop(1, '#17c5ea');
			new Chart(g1, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
						label: 'Enquiries',
						data: dataEnq,
						backgroundColor: grad1,
						borderRadius: 8,
						borderWidth: 0
					}]
				},
				options: {
					maintainAspectRatio: false,
					plugins: { legend: { display: false } },
					scales: {
						y: { beginAtZero: true, ticks: { precision: 0 } }
					}
				}
			});
		}
		var el2 = document.getElementById('puDashChartCategories');
		if (el2 && catLabels.length) {
			var g2 = el2.getContext('2d');
			var colors = ['#4776e6', '#fc4a1a', '#ee0979', '#42e695', '#f54ea2', '#00b09b'];
			var bg = catLabels.map(function (_, i) { return colors[i % colors.length]; });
			new Chart(g2, {
				type: 'doughnut',
				data: {
					labels: catLabels,
					datasets: [{
						data: catData,
						backgroundColor: bg,
						borderWidth: 1
					}]
				},
				options: {
					maintainAspectRatio: false,
					cutout: '68%',
					plugins: {
						legend: { display: false }
					}
				}
			});
		}
	}
	if (window.jQuery) {
		jQuery(run);
	} else {
		document.addEventListener('DOMContentLoaded', run);
	}
})();
</script>
@endpush
