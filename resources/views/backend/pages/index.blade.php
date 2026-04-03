@extends('backend.layouts.master')

@section('title', 'Pages')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb d-sm-flex align-items-center mb-4">
			<div class="breadcrumb-title pe-3 mb-0">Page management</div>
		</div>

		<div class="card radius-10">
			<div class="card-body">
				<p class="text-secondary small mb-3">Edit content and SEO for each frontend page. Slug is fixed; URLs stay the same.</p>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead class="table-light">
							<tr>
								<th>Page</th>
								<th>Slug</th>
								<th>Updated</th>
								<th class="text-end">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach($pages as $page)
								<tr>
									<td class="fw-semibold">{{ $page->name }}</td>
									<td><code class="small">{{ $page->slug }}</code></td>
									<td class="text-muted small">{{ $page->updated_at?->diffForHumans() }}</td>
									<td class="text-end">
										<a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-primary">Edit</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
