{{-- <script type="text/javascript" src="{{asset('admin_assets')}}/js/jquery.min.js"></script> --}}
<script type="text/javascript" src="{{asset('admin_assets')}}/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('admin_assets')}}/js/custom.js"></script>

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
		<script type="text/javascript" src="{{URL::asset('admin_assets/pages')}}/{{$js}}"></script>
	@endforeach
@endif
