@php
namespace App\Helpers;
Use Auth;
$product_place_holder_image=ImageHelper::getProductPlaceholderImage();
use Illuminate\Support\Facades\Session;
@endphp
{{-- <div class="top-nav">
	<div class="nav-item clearfix">
		<div class="left-item">
			<button class="toggle-btn"><i class="fa fa-bars"></i></button>
		</div>
		<div class="right-item">
			<div class="user-profile">
				<a href="{{ route('logout') }}" onclick="event.preventDefault();
				document.getElementById('logout-form').submit();">
				<button><i class="fa fa-sign-out"></i></button>
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				@csrf
				</form>
			</div>

			<div class="user-profile">
				<a href="{{route('profile')}}">
					<button><i class="fa fa-user"></i></button>
				</a>
			</div>

		</div>
	</div>
	<div class="title-btn clearfix">
	</div>
</div> --}}
<div class="top-nav">
<div class="nav-item clearfix">
	<div class="row">
		<div class="col-md-2 col-sm-4 col-xs-3">

		</div>
		<div class="col-md-8 col-sm-4 d-none-m">
			<div class="title">Workspace Management System </div>
		</div>
		<div class="col-md-2 col-sm-4 col-xs-9 text-right">
			<div class="right-item">
				{{-- <a href="notification.html" class="notification"><i class="fa fa-bell"></i> <span>&nbsp;</span></a> --}}
				<div class="user-profile">

					<a href="{{url('/admin/profile/')}}">
						@if(Auth::User()->profile_image)
							<img src="{{ Auth::User()->profile_image }}" alt="profile" id="user-profile" class="img-responsive">
						@else
						<img src="{{ImageHelper::getProfileImage('') }}" alt="profile" class="img-responsive">

						@endif
					</a>
					<span>
						<h2>{{Auth::User()->user_name}}</h2>
						<span class="" id="header_time">{{ date('d/m/Y h:i A')}}</span>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
