<!DOCTYPE html>
<html>
 @include('layouts.auth_head')
 @stack('css')
<body>
	<!--header start-->
	<section class="login-page login clearfix">
		<div class="back-home">
			<a href="#" class="back-page"><i class="fas fa-arrow-left"></i> Back to Home</a>
		</div>
		<!-- <div class="left-bg" style="background-image: url('images/left-bg.jpg');"></div>
		<div class="right-bg" style="background-image: url('images/right-bg.jpg');"></div> -->

		<div class="lg-wrapper">
			<div class="login-box clearfix">
				<div class="bg-img" style="background-image: url('{{asset('front_end')}}/images/01.jpg');"></div>

					@section('content')@show

			</div>
		</div>
	</section><!--end header-->
@include('layouts.auth_foot')
@stack('js')
</body>
</html>
