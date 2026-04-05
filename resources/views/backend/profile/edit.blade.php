@extends('backend.layouts.master')

@section('title', 'My profile')

@section('content')
<div class="page-wrapper">
	<div class="page-content pb-5">
		<div class="page-breadcrumb d-sm-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div class="breadcrumb-title pe-3 mb-0">My profile</div>
			<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
		</div>

		@if(session('status'))
			<div class="alert alert-success border-0 alert-dismissible fade show">
				{{ session('status') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		@if($errors->any())
			<div class="alert alert-danger">
				<ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
			</div>
		@endif

		<div class="row g-4">
			<div class="col-lg-6">
				<div class="card border-0 shadow-sm">
					<div class="card-body">
						<h5 class="card-title mb-3">Account details</h5>
						<form action="{{ route('admin.profile.update') }}" method="post" autocomplete="off">
							@csrf
							@method('PUT')
							<div class="mb-3">
								<label for="name" class="form-label">Name</label>
								<input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
								@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>
							<div class="mb-3">
								<label for="email" class="form-label">Email</label>
								<input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
								@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>
							<button type="submit" class="btn btn-primary px-4">Save profile</button>
						</form>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card border-0 shadow-sm">
					<div class="card-body">
						<h5 class="card-title mb-3">Change password</h5>
						<form action="{{ route('admin.profile.password') }}" method="post" autocomplete="off">
							@csrf
							@method('PUT')
							<div class="mb-3">
								<label for="current_password" class="form-label">Current password</label>
								<input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
								@error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>
							<div class="mb-3">
								<label for="password" class="form-label">New password</label>
								<input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
								@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
							</div>
							<div class="mb-3">
								<label for="password_confirmation" class="form-label">Confirm new password</label>
								<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
							</div>
							<button type="submit" class="btn btn-primary px-4">Update password</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
