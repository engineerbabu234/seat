@php
namespace App\Helpers;
Use Auth;
$product_place_holder_image=ImageHelper::getProductPlaceholderImage();
use Illuminate\Support\Facades\Session;
@endphp
<!DOCTYPE html>
<html>
 @include('admin.layouts.auth_head')
<body>
	{{-- <section class="login-page clearfix">
		<div class="left-bg">
			<div class="overlay"></div>
		</div>
		<div class="right-bg">
		</div>
		<div class="input-box">
			<div class="logo">
				<a>
					<img src="{{asset('admin_assets')}}/images/nav-logo.png">
				</a>
			</div>
			<div class="dev">
				@section('content')@show
			</div>
		</div>
	</section> --}}
	<section class="login-register clearfix">
		<div class="container-fluid">
			<div class="body">
				<div class="clearfix">
					<div class="custom-col-left">
						<div class="bg-wrapper">
						@php
						$Admin = \App\Models\User::where('role','1')->first();
					 	if($Admin->logo_status == 1){
							@endphp
							<img src="{{ImageHelper::getlogoImage($Admin->logo_image)}}" class="logo">
							@php
						}else{
							@endphp
							<img src="{{asset('admin_assets')}}/images/nav-logo2.png">
							@php
						}
						@endphp

						</div>
					</div>

					<div class="custom-col-right">
						<div class="lr-wrapper">
							<div class="lr-wrapper-inner">
								<div class="input-field">
									<div class="heading">
										<h2>Login</h2>
										<p>Welcome Back..! Enter all the Login Details</p>
									</div>
									<div class="dev">
										@section('content')@show

									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@include('admin.layouts.auth_foot')
</body>
</html>
