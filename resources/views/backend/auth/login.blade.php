<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{{ asset('backend/assets/images/favicon-32x32.png') }}" type="image/png" onerror="this.removeAttribute('href')">
	<link href="{{ asset('backend/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet">
	<link href="{{ asset('backend/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
	<link href="{{ asset('backend/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/pace.min.css') }}" rel="stylesheet">
	<script src="{{ asset('backend/assets/js/pace.min.js') }}"></script>
	<link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/icons.css') }}" rel="stylesheet">
	<link href="{{ asset('backend/assets/css/app.css') }}" rel="stylesheet">
	<title>{{ $title ?? 'Admin sign in' }} — PropUpdate</title>
</head>

<body class="">
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="card mb-0">
							<div class="card-body">
								<div class="p-4">
									<div class="mb-3 text-center">
										<h4 class="mb-0 fw-bold text-primary">PropUpdate</h4>
										<p class="text-secondary mb-0 small">Admin panel</p>
									</div>
									<div class="text-center mb-4">
										<h5 class="">Sign in</h5>
										<p class="mb-0">Enter your email and password</p>
									</div>

									@if ($errors->any())
										<div class="alert alert-danger" role="alert">
											{{ $errors->first() }}
										</div>
									@endif

									<div class="form-body">
										<form class="row g-3" method="post" action="{{ route('admin.login.store') }}" autocomplete="off">
											@csrf
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email</label>
												<input
													type="email"
													class="form-control @error('email') is-invalid @enderror"
													id="inputEmailAddress"
													name="email"
													value="{{ old('email') }}"
													placeholder="you@example.com"
													required
													autocomplete="username"
												>
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Password</label>
												<div class="input-group" id="show_hide_password">
													<input
														type="password"
														class="form-control border-end-0 @error('password') is-invalid @enderror"
														id="inputChoosePassword"
														name="password"
														placeholder="Enter password"
														required
														autocomplete="current-password"
													>
													<a href="javascript:;" class="input-group-text bg-transparent" role="button" aria-label="Show or hide password"><i class='bx bx-hide'></i></a>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="remember" value="1" @checked(old('remember'))>
													<label class="form-check-label" for="flexSwitchCheckChecked">Remember me</label>
												</div>
											</div>
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary">Sign in</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<script src="{{ asset('backend/assets/js/app.js') }}"></script>
</body>
</html>
