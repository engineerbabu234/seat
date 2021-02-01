@php
namespace App\Helpers;
Use Auth;
$product_place_holder_image=ImageHelper::getProductPlaceholderImage();
use Illuminate\Support\Facades\Session;
$Admin = \App\Models\User::where('role','1')->first();
@endphp

<style type="text/css">

    .left-block{
        background:{{$Admin->color}};
    }
</style>
<div class="left-block">
	<button class="close-menu">
		<i class="fa fa-times"></i>
	</button>
	<div class="left-block-body">
		<nav>
			<div class="nav-logo">
				<a href="{{route('dashboard')}}">
					@php
					$Admin = \App\Models\User::where('role','1')->first();

					if($Admin->logo_status == 1){

						@endphp
						<img src="{{ImageHelper::getLogoImage($Admin->logo_image)}}" class="logo">
						<img src="{{ImageHelper::getLogoImage($Admin->logo_image)}}" class="logo-icon">
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
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='dashboard')){{'active'}}@endif" href="{{route('dashboard')}}"><img src="{{asset('admin_assets')}}/images/system_messaging.png" class="menu_icons wh-img "> <span>System Managing</span></a>
					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='user')){{'active'}}@endif" href="{{url('admin/user')}}"><img src="{{asset('admin_assets')}}/images/users.png" class="menu_icons wh-img "> <span>Users</span></a>
					</li>
					<!-- <li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='add_building')){{'active'}}@endif" href="{{url('admin/building/add_building')}}"><i class="fas fa-building"></i> <span>Add Bulding</span></a>
					</li>

					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='add_office')){{'active'}}@endif" href="{{url('admin/office/add_office')}}"><i class="fas fa-plus"></i> <span>Add Office</span></a>
					</li> -->
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='building')){{'active'}}@endif" href="{{url('admin/building')}}"><img src="{{asset('admin_assets')}}/images/building.png" class="menu_icons wh-img "> <span>Buildings</span></a>
					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='office')){{'active'}}@endif" href="{{url('admin/office')}}"><img src="{{asset('admin_assets')}}/images/offices.png" class="menu_icons wh-img "> <span>Offices</span></a>
					</li>
					<li>

					<a class="@if((substr(strrchr(url()->current(),"/"),1)=='asset')){{'active'}}@endif" href="{{url('admin/office/asset')}}"><img src="{{asset('admin_assets')}}/images/assets.png" class="menu_icons wh-img "> <span>Offices Assets</span></a>
					</li>
					<li>
						<a href="{{route('admin.team.index')}}"><i class="fa fa-users"></i> <span>Teams</span></a>
					</li>

					<li>
					<a class="@if((substr(strrchr(url()->current(),"/"),1)=='quesionaire')){{'active'}}@endif" href="{{url('admin/quesionaire')}}"><img src="{{asset('admin_assets')}}/images/questionarie.png" class="menu_icons wh-img "> <span>Quesionaire</span></a>
					</li>
<!--
					<li>
					<a class="@if((substr(strrchr(url()->current(),"/"),1)=='question')){{'active'}}@endif" href="{{url('admin/question')}}"><i class="fas fa-list"></i> <span>Quesionaire Question</span></a>
					</li> -->


					<li>

						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='reservation_request')){{'active'}}@endif" href="{{url('admin/reservation/reservation_request')}}"><img src="{{asset('admin_assets')}}/images/reservation_request.png" class="menu_icons wh-img "> <span>Reservation Request</span></a>

					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='reservation_history')){{'active'}}@endif" href="{{url('admin/reservation/reservation_history')}}"><img src="{{asset('admin_assets')}}/images/reservation_history.png" class="menu_icons "> <span>Reservation History</span></a>
					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='seat_label')){{'active'}}@endif" href="{{url('admin/seat_label')}}"><img src="{{asset('admin_assets')}}/images/scan.png" class="menu_icons  wh-img"> <span>Contactless Requests</span></a>
					</li>

					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='apiconnections')){{'active'}}@endif" href="{{url('admin/apiconnections')}}"><img src="{{asset('admin_assets')}}/images/api.png" class="menu_icons  wh-img"> <span>Api Connections</span></a>
					</li>
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='settings')){{'active'}}@endif" href="{{url('admin/settings')}}"><img src="{{asset('admin_assets')}}/images/settings.png" class="menu_icons  wh-img"> <span>Settings</span></a>
					</li>
					<li>
						<a href="{{route('invite.users')}}"><i class="fa fa-users"></i> <span>Invite User</span></a>
					</li>
					<li>
						<a href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                               <img src="{{asset('admin_assets')}}/images/logout.png" class="menu_icons wh-img">
                                Log Out</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                                </form>
						{{-- <a href="login.html"><img src="{{asset('admin_assets')}}/images/logout.png" class="menu_icons wh-img"> <span>log out</span></a> --}}
					</li>
				</ul>
			</div>
		</nav>
	</div>
</div>
