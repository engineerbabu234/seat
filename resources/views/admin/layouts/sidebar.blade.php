@php
namespace App\Helpers;
Use Auth;
$product_place_holder_image=ImageHelper::getProductPlaceholderImage();
use Illuminate\Support\Facades\Session;
@endphp
<div class="left-block">
	<button class="close-menu">
		<i class="fa fa-times"></i>
	</button>
	<div class="left-block-body">
		<nav>
			<div class="nav-logo">
				<a href="{{route('dashboard')}}">
					@php
					$logo=env('Logo');
					if($logo){
						$Admin = \App\Models\User::where('role','1')->first();

						@endphp
						<img src="{{ImageHelper::getProfileImage($Admin->logo_image)}}" class="logo">
						<img src="{{ImageHelper::getProfileImage($Admin->logo_image)}}" class="logo-icon">
						@php
					}else{
						@endphp
						<img src="{{asset('admin_assets')}}/images/nav-logo2.png" class="logo">
						<img src="{{asset('admin_assets')}}/images/logo-icon.png" class="logo-icon">
						@php
					}
					@endphp
				</a>
			</div>

			<div class="navlink">
				<ul>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='dashboard')){{'active'}}@endif" href="{{route('dashboard')}}"><i class="fa fa-list-alt"></i> <span>System Managing</span></a>
					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='user')){{'active'}}@endif" href="{{url('admin/user')}}"><i class="fas fa-users"></i> <span>Users</span></a>
					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='add_building')){{'active'}}@endif" href="{{url('admin/building/add_building')}}"><i class="fas fa-building"></i> <span>Add Bulding</span></a>
					</li>

					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='add_office')){{'active'}}@endif" href="{{url('admin/office/add_office')}}"><i class="fas fa-plus"></i> <span>Add Office</span></a>
					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='building')){{'active'}}@endif" href="{{url('admin/building')}}"><i class="fas fa-building"></i> <span>My Buldings</span></a>
					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='office')){{'active'}}@endif" href="{{url('admin/office')}}"><i class="fas fa-building"></i> <span>My Offices</span></a>
					</li>
					<li>

						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='assets')){{'active'}}@endif" href="{{url('admin/assets/index')}}"><i class="fas fa-list"></i> <span>My Offices Assets</span></a>
					</li>
					<li>


						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='reservation_request')){{'active'}}@endif" href="{{url('admin/reservation/reservation_request')}}"><i class="fa fa-list"></i> <span>Reservation Request</span></a>

					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='reservation_history')){{'active'}}@endif" href="{{url('admin/reservation/reservation_history')}}"><i class="fa fa-list"></i> <span>Reservation History</span></a>
					</li>
					<li>
						<a href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-in-alt"></i>
                                Log Out</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                                </form>
						{{-- <a href="login.html"><i class="fas fa-sign-in-alt"></i> <span>log out</span></a> --}}
					</li>
				</ul>
			</div>
		</nav>
	</div>
</div>
