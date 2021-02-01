<!DOCTYPE html>
<html>
 @include('layouts.head')
 @stack('css')
<body onload="loaderfun()">
	<div id="loader-wrapper">
		<div id="loader">
			<div class="svg-wrapper">
				<img src="{{asset('front_end')}}/images/loader1.gif">
			</div>
		</div>
	</div>
	<main class="clearfix">
		 @include('layouts.sidebar')
		<div class="right-block">
			<div class="Navoverlay"></div>
			<div class="right-block-body">
				@include('layouts.header')
				<!------right block body-->

			</div>
			<div class="dev">
				@section('content')@show
			</div>
			 @include('add_modals')
			<!---footer--->
			{{-- @include('layouts.footer') --}}
		</div>
	</main>

	<div id="popover_content">
	<div class="row">
	    <div class="col-sm-12">
	    	<p id="content_html"></p>
	    </div>
	</div>
	</div>

<!--script-->
@include('layouts.foot')
@stack('js')
</body>
</html>
