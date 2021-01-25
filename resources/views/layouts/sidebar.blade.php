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
					@if(Auth::User()->role == 2)
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='seat_reservation')){{'active'}}@endif" href="{{url('/')}}"><img src="{{asset('admin_assets')}}/images/users.png" class="menu_icons wh-img"> <span>Home</span></a>
					</li>

					 @if (!Auth::guest())
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='questionaries')){{'active'}}@endif" href="{{url('/questionaries')}}"><img src="{{asset('admin_assets')}}/images/system_messaging.png" class="menu_icons wh-img"> <span>Questionaries</span></a>
					</li>
				    @endif

				     @if (!Auth::guest())
					<li>
						<a class="@if((substr(strrchr(url()->current(),"/"),1)=='reservation')){{'active'}}@endif" href="{{url('/reservation')}}"><img src="{{asset('admin_assets')}}/images/building.png" class="menu_icons wh-img"> <span>Reservation</span></a>
					</li>
					 @endif

					 @endif


					<li>
						<a href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                       <img src="{{asset('admin_assets')}}/images/logout.png" class="menu_icons wh-img">
                        Log Out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                        </form>

					</li>
				</ul>
			</div>
		</nav>
	</div>
</div>
