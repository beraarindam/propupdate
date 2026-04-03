<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
	<div class="sidebar-header">
		<div>
			<img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="" onerror="this.style.display='none'">
		</div>
		<div>
			<h4 class="logo-text">PropUpdate</h4>
		</div>
		<div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
	</div>
	<!--navigation-->
	<ul class="metismenu" id="menu">
		<li class="{{ request()->routeIs('admin.dashboard') ? 'mm-active' : '' }}">
			<a href="{{ route('admin.dashboard') }}">
				<div class="parent-icon"><i class='bx bx-home-alt'></i></div>
				<div class="menu-title">Dashboard</div>
			</a>
		</li>
		<li class="{{ request()->routeIs('admin.site-settings') ? 'mm-active' : '' }}">
			<a href="{{ route('admin.site-settings') }}">
				<div class="parent-icon"><i class='bx bx-cog'></i></div>
				<div class="menu-title">Site settings</div>
			</a>
		</li>
	</ul>
	<!--end navigation-->
</div>
<!--end sidebar wrapper -->
