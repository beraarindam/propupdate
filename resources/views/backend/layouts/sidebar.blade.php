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
		
		<li class="{{ request()->routeIs('admin.pages.*') ? 'mm-active' : '' }}">
			<a href="javascript:;" class="has-arrow">
				<div class="parent-icon"><i class='bx bx-book-content'></i></div>
				<div class="menu-title">Pages</div>
			</a>
			<ul>
				<li class="{{ request()->routeIs('admin.pages.index') ? 'mm-active' : '' }}">
					<a href="{{ route('admin.pages.index') }}"><i class='bx bx-radio-circle'></i>Manage pages</a>
				</li>
			</ul>
		</li>
		<li class="{{ request()->routeIs('admin.properties.*', 'admin.property_categories.*', 'admin.projects.*', 'admin.exclusive_resale_listings.*') ? 'mm-active' : '' }}">
			<a href="javascript:;" class="has-arrow">
				<div class="parent-icon"><i class='bx bx-buildings'></i></div>
				<div class="menu-title">Properties</div>
			</a>
			<ul>
				<li class="{{ request()->routeIs('admin.properties.*') ? 'mm-active' : '' }}">
					<a href="{{ route('admin.properties.index') }}"><i class='bx bx-radio-circle'></i>Listings</a>
				</li>
				<li class="{{ request()->routeIs('admin.property_categories.*') ? 'mm-active' : '' }}">
					<a href="{{ route('admin.property_categories.index') }}"><i class='bx bx-radio-circle'></i>Categories</a>
				</li>
				<li class="{{ request()->routeIs('admin.projects.*') ? 'mm-active' : '' }}">
					<a href="{{ route('admin.projects.index') }}"><i class='bx bx-radio-circle'></i>Projects</a>
				</li>
				<li class="{{ request()->routeIs('admin.exclusive_resale_listings.*') ? 'mm-active' : '' }}">
					<a href="{{ route('admin.exclusive_resale_listings.index') }}"><i class='bx bx-radio-circle'></i>Exclusive resale</a>
				</li>
			</ul>
		</li>
		<li class="{{ request()->routeIs('admin.services.*') ? 'mm-active' : '' }}">
			<a href="{{ route('admin.services.index') }}">
				<div class="parent-icon"><i class='bx bx-briefcase-alt-2'></i></div>
				<div class="menu-title">Services</div>
			</a>
		</li>
		<li class="{{ request()->routeIs('admin.blogs.*') ? 'mm-active' : '' }}">
			<a href="javascript:;" class="has-arrow">
				<div class="parent-icon"><i class='bx bx-news'></i></div>
				<div class="menu-title">Blog</div>
			</a>
			<ul>
				<li class="{{ request()->routeIs('admin.blogs.index') ? 'mm-active' : '' }}">
					<a href="{{ route('admin.blogs.index') }}"><i class='bx bx-radio-circle'></i>Manage posts</a>
				</li>
			</ul>
		</li>
		<li class="{{ request()->routeIs('admin.gallery_items.*') ? 'mm-active' : '' }}">
			<a href="{{ route('admin.gallery_items.index') }}">
				<div class="parent-icon"><i class='bx bx-images'></i></div>
				<div class="menu-title">Gallery</div>
			</a>
		</li>
		<li class="{{ request()->routeIs('admin.faqs.*') ? 'mm-active' : '' }}">
			<a href="{{ route('admin.faqs.index') }}">
				<div class="parent-icon"><i class='bx bx-help-circle'></i></div>
				<div class="menu-title">FAQs</div>
			</a>
		</li>
		<li class="{{ request()->routeIs('admin.enquiries.*') ? 'mm-active' : '' }}">
			<a href="{{ route('admin.enquiries.index') }}">
				<div class="parent-icon"><i class='bx bx-mail-send'></i></div>
				<div class="menu-title">Enquiries</div>
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
