<style>
	p,
	a,
	i {
		color: white !important;
	}
</style>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(#e66465, #9198e5);">
	<!-- Brand Logo -->
	<div class="brand-link">
		<img src="{{ asset('Trevers.png') }}" alt="Ecolink logo" class="brand-image img-circle elevation-3" style="opacity: .8">
		<span class="brand-text font-weight-strong text-white" style="font-size: 1rem !important;">ECOLINK </span>
	</div>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="{{ auth()->user()->profile_image != null ? asset('public/storage/profile/'.auth()->user()->id.'/'.auth()->user()->profile_image) : asset('default.jpg') }}" class="img-rounded elevation-2" alt="{{ auth()->user()->name }}">
			</div>
			<div class="info">
				<a href="{{ url('profile',auth()->user()->id) }}" class="d-block">{{ auth()->user()->name }}</a>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
			with font-awesome or any other icon font library -->


				<li class="nav-item">
					<a href="{{ url('dashboard') }}" class="nav-link">
						<i class="nav-icon fas fa-chart-pie"></i>
						<p>
							Dashboard
						</p>
					</a>
				</li>

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-user"></i>
						<p>
							Users
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('/users/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add User</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('/users') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>User List</p>
							</a>
						</li>
					</ul>
				</li>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>