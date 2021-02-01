@include('add_modals')
<script type="text/javascript">
					var base_url = "{{url('/')}}";
				</script>
				<script type="text/javascript" src="{{asset('front_end')}}/js/jquery.min.js"></script>
				<script type="text/javascript" src="{{asset('front_end')}}/js/owl.carousel.min.js"></script>
				<script type="text/javascript" src="{{asset('front_end')}}/js/popper.min.js"></script>
				<script type="text/javascript" src="{{asset('front_end')}}/js/bootstrap.min.js"></script>
				<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
				<script type="text/javascript" src="{{asset('front_end')}}/js/custom.js"></script>
				<script type="text/javascript" src="{{asset('front_end')}}/js/seat_request/index.js"></script>
				<script src="{{asset('front_end')}}/js/alert.js" type="text/javascript"></script>
				<script type="text/javascript">
					window.setTimeout(function() {
						$(".alert").fadeTo(500, 0).slideUp(500, function(){
							$(this).remove();
						});
				}, 3000);
				</script>
			</body>
		</html>
