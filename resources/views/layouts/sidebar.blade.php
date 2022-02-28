<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(#e66465, #9198e5);">
	<!-- Brand Logo -->
	<div class="brand-link">
		<img src="{{ asset('New_Ecolink_Logo-33.png') }}" alt="Ecolink logo" class="brand-image  elevation-3" style="opacity: .8">
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
				<a href="{{ url('admin/profile',auth()->user()->id) }}" class="d-block">{{ auth()->user()->name }}</a>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
			with font-awesome or any other icon font library -->

				<li class="nav-item">
					<a href="{{ url('admin/home') }}" class="nav-link">
						<p>
							Dashboard
						</p>
					</a>
				</li>

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<p>
							Users
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('admin/users/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add User</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/users') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>User List</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<p>
							Blogs
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('admin/blogs/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Blog</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/blogs') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Blog List</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<p>
							Category
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('admin/categories/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Category</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/categories') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Category List</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<p>
							Sub Category
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('admin/sub/categories/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Sub Category</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/sub/categories') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Sub Category List</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<p>
							Product
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('admin/products/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Product</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/products') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Product List</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<p>
							News Letter
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('admin/newsletters/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add News Letter</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/newsletters') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>News Letter List</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<p>
							Page
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('admin/pages/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Page</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/pages') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Page List</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<p>
							Coupon
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('admin/coupons/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Coupon</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/coupons') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Coupon List</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-item has-treeview">
					<a href="{{ url('admin/carts') }}" class="nav-link">
						<p>
							Carts
						</p>
					</a>
				</li>

				<li class="nav-item has-treeview">
					<a href="{{ url('admin/orders') }}" class="nav-link">
						<p>
							Orders
						</p>
					</a>
				</li>

				<li class="nav-item has-treeview">
					<a href="{{ url('admin/returns') }}" class="nav-link">
						<p>
							Order Returns
						</p>
					</a>
				</li>

				<li class="nav-item has-treeview">
					<a href="{{ url('admin/askchemist') }}" class="nav-link">
						<p>
							Ask Chemist
						</p>
					</a>
				</li>

				<li class="nav-item has-treeview">
					<a href="{{ url('admin/requestproduct') }}" class="nav-link">
						<p>
							Request Product
						</p>
					</a>
				</li>

				<li class="nav-item has-treeview">
					<a href="{{ url('admin/contact') }}" class="nav-link">
						<p>
							Contact
						</p>
					</a>
				</li>
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>