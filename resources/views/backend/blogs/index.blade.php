@extends('backend.layouts.master')

@section('title', 'Blog')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">Blog management</div>
			<a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">New post</a>
		</div>

		@if(session('status'))
			<div class="alert alert-success border-0 alert-dismissible fade show">
				{{ session('status') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		<div class="card radius-10">
			<div class="card-body">
				<p class="text-secondary small mb-3">Create and edit articles. Each post uses a unique <strong>slug</strong> for the public URL (<code>/blog/your-slug</code>). Configure SEO and the hero image in the editor.</p>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead class="table-light">
							<tr>
								<th>Post</th>
								<th>Slug</th>
								<th>Status</th>
								<th>Updated</th>
								<th class="text-end">Action</th>
							</tr>
						</thead>
						<tbody>
							@forelse($posts as $post)
								<tr>
									<td class="fw-semibold">{{ $post->title }}</td>
									<td>
										@if($post->is_published && $post->published_at)
											<code class="small"><a href="{{ route('blog.show', $post) }}" target="_blank" rel="noopener noreferrer">{{ $post->slug }}</a></code>
										@else
											<code class="small">{{ $post->slug }}</code>
										@endif
									</td>
									<td>
										@if($post->is_published)
											<span class="badge bg-success">Published</span>
										@else
											<span class="badge bg-secondary">Draft</span>
										@endif
									</td>
									<td class="text-muted small">{{ $post->updated_at?->diffForHumans() ?? '—' }}</td>
									<td class="text-end text-nowrap">
										<a href="{{ route('admin.blogs.edit', $post) }}" class="btn btn-sm btn-primary">Edit</a>
										<form action="{{ route('admin.blogs.destroy', $post) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this post?');">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
										</form>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="5" class="text-muted text-center py-4">No posts yet. <a href="{{ route('admin.blogs.create') }}">Create the first post</a>.</td>
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
