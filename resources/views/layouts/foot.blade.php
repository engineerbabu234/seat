<script type="text/javascript" src="{{asset('front_end')}}/js/jquery.min.js"></script>
<script type="text/javascript" src="{{asset('front_end')}}/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="{{asset('front_end')}}/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('front_end')}}/js/custom.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset('front_end')}}/js/jBox.js"></script>
<script src="{{asset('front_end')}}/js/alert.js" type="text/javascript"></script>
<script type="text/javascript">
	window.setTimeout(function() {
		$(".alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();
		});
    }, 3000);
</script>

<script type="text/javascript">
	var base_url = "{{url('/')}}";
</script>
@if(!empty($data['js']))
	@foreach($data['js'] as $js)
		<script type="text/javascript" src="{{URL::asset('front_end/pages')}}/{{$js}}"></script>
	@endforeach
@endif
