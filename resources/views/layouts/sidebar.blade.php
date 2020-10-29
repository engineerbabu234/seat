{{-- <div class="left-wrapper">
	<div class="left-block">
		<button class="close-menu">
			<i class="fa fa-times"></i>
		</button>
		<div class="left-block-body">
			<nav>
				<div class="nav-logo">
					<a href="{{route('dashboard')}}">
						<img src="{{asset('admin_assets')}}/images/nav-logo.png" class="logo" width="70px">
						<img src="{{asset('admin_assets')}}/images/logo-icon.png" class="logo-icon">
					</a>
				</div>
				<div class="navlink">
					<ul>
						<li>
							<a class="@if((substr(strrchr(url()->current(),"/"),1)=='dashboard')){{'active'}}@endif" href="{{route('dashboard')}}"><i class="fa fa-home"></i> <span>Dashboard</span></a>
						</li>
						<li>
							<a class="@if((substr(strrchr(url()->current(),"/"),1)=='user')){{'active'}}@endif" href="{{route('admin/user/index')}}"><i class="fa fa-user"></i> <span>Users</span></a>
						</li>

						<li>
							<a class="@if((substr(strrchr(url()->current(),"/"),1)=='driver')){{'active'}}@endif" href="{{route('admin/driver/index')}}"><i class="fa fa-car"></i> <span>Drivers</span></a>
						</li>

						<li>
							<a class="@if((substr(strrchr(url()->current(),"/"),1)=='vehicle_type')){{'active'}}@endif" href="{{route('admin/vehicle_type/index')}}"><i class="fa fa-car"></i> <span>Vehicle Type</span></a>
						</li>
						<li>
							<a class="@if((substr(strrchr(url()->current(),"/"),1)=='trip')){{'active'}}@endif" href="{{route('admin/trip/index')}}"><i class="fa fa-car"></i> <span>Trips</span></a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
	</div>
</div> --}}
<div class="left-block">
	<button class="close-menu">
		<i class="fa fa-times"></i>
	</button>
	<div class="left-block-body">
		<nav>
			<div class="nav-logo">
				<a href="index.html">
					<img src="{{asset('admin_assets')}}/images/nav-logo2.png" class="logo">
					<img src="{{asset('admin_assets')}}/images/logo-icon.png" class="logo-icon">
				</a>
			</div>

			<div class="navlink">
				<ul>
					<li>
						<a href="index.html"><i class="fa fa-list-alt"></i> <span>System Managing</span></a>
					</li>
					<li>
						<a href="add_building.html"><i class="fas fa-building"></i> <span>Add Bulding</span></a>
					</li>
					<li>
						<a href="add_office.html"><i class="fas fa-plus"></i> <span>Add Office</span></a>
					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='building')){{'active'}}@endif" href="{{route('admin/building/index')}}"><i class="fas fa-building"></i> <span>My Buldings</span></a>
					</li>
					<li>
						<a href="my_offices_list.html"><i class="fas fa-building"></i> <span>My Offices</span></a>
					</li>
					<li>
						<a href="reservation_request.html"><i class="fa fa-list"></i> <span>reservation request</span></a>
					</li>
					<li>
						<a href="reservation_history.html"><i class="fa fa-list"></i> <span>reservation History</span></a>
					</li>
					<li>
						<a href="login.html"><i class="fas fa-sign-in-alt"></i> <span>log out</span></a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</div>
