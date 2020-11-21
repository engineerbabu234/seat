<!DOCTYPE html>
<html>
 @include('admin.layouts.head')
 @stack('css')
<body onload="loaderfun()">
	<div id="loader-wrapper">
		<div id="loader">
			<div class="svg-wrapper">
				<img src="{{asset('admin_assets')}}/images/loader1.gif">
			</div>
		</div>
	</div>
	<main class="clearfix">
		 @include('admin.layouts.sidebar')
		<div class="right-block">
			<div class="Navoverlay"></div>
			<div class="right-block-body">
				@include('admin.layouts.header')
				<!------right block body-->

			</div>
			<div class="dev">
				@section('content')@show
			</div>

			<!---footer--->
			{{-- @include('admin.layouts.footer') --}}
		</div>
	</main>
<!--script-->
@include('admin.layouts.foot')
@stack('js')
</body>
</html>
