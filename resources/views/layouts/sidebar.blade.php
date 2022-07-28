<!-- <style>
	aside.main-sidebar.sidebar-dark-primary.elevation-4 {
		pointer-events: none;
	}
</style> -->

@php
$coustom_pages = array("Custom_Blade_Solution",
"Intern_Onboarding_Form",
"Intership_Application_Form",
"Scholarship_Contest_Submission",
"Scholarship_Winner","cannabis",
"contact_today");  
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(#2f2f2f, #2f2f2f); ">
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
				<img src="{{ auth()->user()->profile_image != null ? asset('storage/profile_image/'.auth()->user()->profile_image) : asset('default.jpg') }}" class="img-rounded elevation-2" alt="{{ auth()->user()->name }}">
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
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>
							Dashboard
						</p>
					</a>
				</li>

				{{-- <li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-file"></i>
						<p>
							Reports
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('admin/reports/sales') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Sales Report</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/reports/carts') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Abandoned Cart Report</p>
							</a>
						</li>
					</ul>
				</li> --}}
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-user"></i>
						<p>
							Users
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						@if(checkpermission('UserController@create'))
						<li class="nav-item">
							<a href="{{ url('admin/users/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add User</p>
							</a>
						</li>
						@endif
						@if(checkpermission('UserController@index'))
						<li class="nav-item">
							<a href="{{ url('admin/users') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>User List</p>
							</a>
						</li>
						@endif
						@if(checkpermission('UserAddressController@index'))
				        <li class="nav-item">
					      <a href="{{ url('admin/addresses') }}" class="nav-link">
						     <i class="far fa-circle nav-icon"></i>
						        <p>
							      User Addresses
						        </p>
				        	</a>
				       </li>
			        	@endif
						@if(auth()->user()->role_id == 1)
			        	@if(checkpermission('UserPermissionController@index'))
			        	<li class="nav-item has-treeview">
			  		       <a href="{{ url('admin/userpermissions') }}" class="nav-link">
						      <i class="fas fa-user-lock nav-icon"></i>
						         <p>
						    	     User Permissions
						        </p>
				          	</a>
			        	</li>
			        	@endif
			         	@endif
					</ul>
				</li>
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-file"></i>
						<p>
							Page
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						@if(checkpermission('PageController@create'))
						<li class="nav-item">
							<a href="{{ url('admin/pages/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Page</p>
							</a>
						</li>
						@endif
						@if(checkpermission('PageController@index'))
						<li class="nav-item">
							<a href="{{ url('admin/pages') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Page List</p>
							</a>
						</li>
						@endif
					</ul>
				</li>
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-blog"></i>
						<p>
							Blogs
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						@if(checkpermission('BlogController@create'))
						<li class="nav-item">
							<a href="{{ url('admin/blogs/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Blog</p>
							</a>
						</li>
						@endif
						@if(checkpermission('BlogController@index'))
						<li class="nav-item">
							<a href="{{ url('admin/blogs') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Blog List</p>
							</a>
						</li>
						@endif
						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-cubes"></i>
								<p>
									Blog Category
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								@if(checkpermission('BlogCategoryController@create'))
								<li class="nav-item">
									<a href="{{ url('admin/blogcategory/create') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Add Blog Category</p>
									</a>
								</li>
								@endif
								@if(checkpermission('BlogCategoryController@index'))
								<li class="nav-item">
									<a href="{{ url('admin/blogcategory') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Blog Category List</p>
									</a>
								</li>
								@endif
							</ul>
						</li>
					</ul>
				</li>
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-file"></i>
						<p>
							Reports
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ url('admin/reports/sales') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Sales Report</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ url('admin/reports/carts') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Abandoned Cart Report</p>
							</a>
						</li>
					</ul>
				</li>

				{{-- <li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-square"></i>
						<p>
							Category
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						@if(checkpermission('CategoryController@create'))
						<li class="nav-item">
							<a href="{{ url('admin/categories/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Category</p>
							</a>
						</li>
						@endif
						@if(checkpermission('CategoryController@index'))
						<li class="nav-item">
							<a href="{{ url('admin/categories') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Category List</p>
							</a>
						</li>
						@endif
					</ul>
				</li> --}}

				{{-- <li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-clone"></i>
						<p>
							Sub Category
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						@if(checkpermission('SubCategoryController@create'))
						<li class="nav-item">
							<a href="{{ url('admin/sub/categories/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Sub Category</p>
							</a>
						</li>
						@endif
						@if(checkpermission('SubCategoryController@index'))
						<li class="nav-item">
							<a href="{{ url('admin/sub/categories') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Sub Category List</p>
							</a>
						</li>
						@endif
					</ul>
				</li> --}}

				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-shopping-basket"></i>
						<p>
							Product
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						@if(checkpermission('ProductController@index'))
						<li class="nav-item">
							<a href="{{ url('admin/products/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Product</p>
							</a>
						</li>
						@endif
						@if(checkpermission('ProductController@index'))
						<li class="nav-item">
							<a href="{{ url('admin/products') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Product List</p>
							</a>
						</li>
						@endif
						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-square"></i>
								<p>
									Category
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								@if(checkpermission('CategoryController@create'))
								<li class="nav-item">
									<a href="{{ url('admin/categories/create') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Add Category</p>
									</a>
								</li>
								@endif
								@if(checkpermission('CategoryController@index'))
								<li class="nav-item">
									<a href="{{ url('admin/categories') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Category List</p>
									</a>
								</li>
								@endif
							</ul>
						</li>
						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-clone"></i>
								<p>
									Sub Category
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								@if(checkpermission('SubCategoryController@create'))
								<li class="nav-item">
									<a href="{{ url('admin/sub/categories/create') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Add Sub Category</p>
									</a>
								</li>
								@endif
								@if(checkpermission('SubCategoryController@index'))
								<li class="nav-item">
									<a href="{{ url('admin/sub/categories') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Sub Category List</p>
									</a>
								</li>
								@endif
							</ul>
						</li>
						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-map-pin"></i>
								<p>
									Dropship Locations
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								@if(checkpermission('ProductController@index'))
								<li class="nav-item">
									<a href="{{ url('admin/dropship/create') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Add Dropship Location</p>
									</a>
								</li>
								@endif
								@if(checkpermission('ProductController@index'))
								<li class="nav-item">
									<a href="{{ url('admin/dropship') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Dropship Location List</p>
									</a>
								</li>
								@endif
							</ul>
						</li>
					</ul>
				</li>
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="fab fa-opencart nav-icon"></i>
						<p>
							Orders
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						@if(checkpermission('OrderController@create'))
						<li class="nav-item">
							<a href="{{ url('admin/orders/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add Order</p>
							</a>
						</li>
						@endif
						@if(checkpermission('OrderController@index'))
						<li class="nav-item">
							<a href="{{ url('admin/orders') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Order List</p>
							</a>
						</li>
						@endif
						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="fas fa-tags nav-icon"></i>
								<p>
									Coupon
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								@if(checkpermission('CouponController@create'))
								<li class="nav-item">
									<a href="{{ url('admin/coupons/create') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Add Coupon</p>
									</a>
								</li>
								@endif
								@if(checkpermission('CouponController@index'))
								<li class="nav-item">
									<a href="{{ url('admin/coupons') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Coupon List</p>
									</a>
								</li>
								@endif
							</ul>
						</li>

						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="fas fa-solid fa-percent nav-icon"></i>
								<p>
									Tax Rate
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								@if(checkpermission('TaxRateController@create'))
								<li class="nav-item">
									<a href="{{ url('admin/taxrates/create') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Add Tax Rate</p>
									</a>
								</li>
								@endif
								@if(checkpermission('TaxRateController@index'))
								<li class="nav-item">
									<a href="{{ url('admin/taxrates') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Tax Rate List</p>
									</a>
								</li>
								@endif
							</ul>
						</li>
						<li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="fas fa-shopping-cart nav-icon"></i>
								<p>
									Carts
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								@if(checkpermission('CartController@create'))
								<li class="nav-item">
									<a href="{{ url('admin/carts/create') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Add Cart</p>
									</a>
								</li>
								@endif
								@if(checkpermission('CartController@index'))
								<li class="nav-item">
									<a href="{{ url('admin/carts') }}" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Cart List</p>
									</a>
								</li>
								@endif
							</ul>
						</li>
						@if(checkpermission('WishlistController@index'))
						<li class="nav-item has-treeview">
							<a href="{{ url('admin/wishlists') }}" class="nav-link">
								<i class="fas fa-solid fa-heart nav-icon"></i>
								<p>
									Wish List
								</p>
							</a>
						</li>
						@endif
					</ul>
					
				</li>
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="fas fa-solid fa-table nav-icon"></i>
						<p>
							Forms
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						@if(checkpermission('AskChemistController@index'))
						<li class="nav-item has-treeview">
							<a href="{{ url('admin/askchemist') }}" class="nav-link">
								<i class="fas fa-solid fa-comment nav-icon"></i>
								<p>
									Ask Chemist
								</p>
							</a>
						</li>
						@endif
		
						@if(checkpermission('RequestProductController@index'))
						<li class="nav-item has-treeview">
							<a href="{{ url('admin/requestproduct') }}" class="nav-link">
								<i class="fas fa-th-list nav-icon"></i>
								<p>
									Request Product
								</p>
							</a>
						</li>
						@endif
		
						@if(checkpermission('BulkPricingController@index'))
						<li class="nav-item has-treeview">
							<a href="{{ url('admin/bulkpricing') }}" class="nav-link">
								<i class="fas fa-money-bill nav-icon"></i>
								<p>
									Bulk Pricing
								</p>
							</a>
						</li>
						@endif
		
						@if(checkpermission('TechnicalSupportController@index'))
						<li class="nav-item has-treeview">
							<a href="{{ url('admin/technicalsupport') }}" class="nav-link">
								<i class="fas fa-tools nav-icon"></i>
								<p>
									Technical Support
								</p>
							</a>
						</li>
						@endif
		
						@if(checkpermission('ContactUsController@index'))
						<li class="nav-item has-treeview">
							<a href="{{ url('admin/contact') }}" class="nav-link">
								<i class="fas fa-solid fa-address-book nav-icon"></i>
								<p>
									Contact Us
								</p>
							</a>
						</li>
						@endif
						@foreach($coustom_pages as $coustom_page)
						<li class="nav-item has-treeview">
							<a href="{{ url('admin/forms/list',$coustom_page) }}" class="nav-link">
								<i class="fas fa-solid fa-address-book nav-icon"></i>
								<p>
									{{ ucwords(str_replace("_"," ",$coustom_page)) }}
								</p>
							</a>
						</li>						
						@endforeach

					</ul>
				</li>
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="fas fa-solid fa-envelope-open-text nav-icon"></i>
						<p>
							News Letter
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						@if(checkpermission('NewsLetterController@create'))
						<li class="nav-item">
							<a href="{{ url('admin/newsletters/create') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Add News Letter</p>
							</a>
						</li>
						@endif
						@if(checkpermission('NewsLetterController@index'))
						<li class="nav-item">
							<a href="{{ url('admin/newsletters') }}" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>News Letter List</p>
							</a>
						</li>
						@endif
					</ul>
				</li>
			
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="fas fa-solid fa-cog nav-icon"></i>
						<p>
							Settings
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item has-treeview">
							<a href="{{ url('admin/staticvalues') }}" class="nav-link">
								<i class="fas fa-solid fa-pen nav-icon"></i>
								<p>
									Static Values
								</p>
							</a>
						</li>
						@if(checkpermission('NoticeController@index'))
			         	<li class="nav-item has-treeview">
					       <a href="{{ url('admin/notices') }}" class="nav-link">
					        	<i class="fas fa-solid fa-pen nav-icon"></i>
					         	<p>
					        		Popups
					        	</p>
				         	</a>
			         	</li>				
						@endif
					</ul>
				</li>


				{{-- @if(checkpermission('UserAddressController@index'))
				<li class="nav-item has-treeview">
					<a href="{{ url('admin/addresses') }}" class="nav-link">
						<i class="fas fa-solid fa-address-book nav-icon"></i>
						<p>
							User Addresses
						</p>
					</a>
				</li>
				@endif --}}

				{{-- @if(checkpermission('ReturnController@index'))
				<li class="nav-item has-treeview">
					<a href="{{ url('admin/returns') }}" class="nav-link">
						<i class="fa fa-undo nav-icon"></i>
						<p>
							Order Returns
						</p>
					</a>
				</li>
				@endif --}}

				{{-- @if(checkpermission('AskChemistController@index'))
				<li class="nav-item has-treeview">
					<a href="{{ url('admin/askchemist') }}" class="nav-link">
						<i class="fas fa-solid fa-comment nav-icon"></i>
						<p>
							Ask Chemist
						</p>
					</a>
				</li>
				@endif

				@if(checkpermission('RequestProductController@index'))
				<li class="nav-item has-treeview">
					<a href="{{ url('admin/requestproduct') }}" class="nav-link">
						<i class="fas fa-th-list nav-icon"></i>
						<p>
							Request Product
						</p>
					</a>
				</li>
				@endif

				@if(checkpermission('BulkPricingController@index'))
				<li class="nav-item has-treeview">
					<a href="{{ url('admin/bulkpricing') }}" class="nav-link">
						<i class="fas fa-money-bill nav-icon"></i>
						<p>
							Bulk Pricing
						</p>
					</a>
				</li>
				@endif

				@if(checkpermission('TechnicalSupportController@index'))
				<li class="nav-item has-treeview">
					<a href="{{ url('admin/technicalsupport') }}" class="nav-link">
						<i class="fas fa-tools nav-icon"></i>
						<p>
							Technical Support
						</p>
					</a>
				</li>
				@endif

				@if(checkpermission('ContactUsController@index'))
				<li class="nav-item has-treeview">
					<a href="{{ url('admin/contact') }}" class="nav-link">
						<i class="fas fa-solid fa-address-book nav-icon"></i>
						<p>
							Contact Us
						</p>
					</a>
				</li>
				@endif --}}

				{{-- @if(checkpermission('NoticeController@index'))
				<li class="nav-item has-treeview">
					<a href="{{ url('admin/notices') }}" class="nav-link">
						<i class="fas fa-solid fa-pen nav-icon"></i>
						<p>
							Notice
						</p>
					</a>
				</li>
				@endif --}}

				{{-- @if(auth()->user()->role_id == 1)
				@if(checkpermission('UserPermissionController@index'))
				<li class="nav-item has-treeview">
					<a href="{{ url('admin/userpermissions') }}" class="nav-link">
						<i class="fas fa-user-lock nav-icon"></i>
						<p>
							User Permissions
						</p>
					</a>
				</li>
				@endif
				@endif --}}
				

				{{-- <li class="nav-item has-treeview">
					<a href="{{ url('admin/staticvalues') }}" class="nav-link">
						<i class="fas fa-solid fa-pen nav-icon"></i>
						<p>
							Static Values
						</p>
					</a>
				</li> --}}

			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>