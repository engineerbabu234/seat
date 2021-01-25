{{-- <script type="text/javascript" src="{{asset('admin_assets')}}/js/jquery.min.js"></script> --}}

<script type="text/javascript" src="{{asset('front_end')}}/js/popper.min.js"></script>
<script type="text/javascript" src="{{asset('front_end')}}/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('admin_assets')}}/js/custom.js"></script>

<script type="text/javascript">
	window.setTimeout(function() {
		$(".alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();
		});
    }, 3000);

    $('#popover_content').hide();
	$(".iconSize_32").on("mouseover", function (e) {
			var content = $(this).data('content');
			$('#content_html').text(content);
	        $(this).popover({
	            content:  $('#popover_content').html(),
	            placement: 'left',
	            html: true,
	             trigger: 'hover'
	        });
	});

	function get_pophover() {
         $(".iconSize_32 ").on("mouseover", function (e) {
            var content = $(this).data('content');
            $('#content_html').text(content);
            $(this).popover({
                content:  $('#popover_content').html(),
                placement: 'right',
                html: true,
                trigger: 'hover'
            });
        });
    }

</script>

<script type="text/javascript">
	var base_url = "{{url('/')}}";
	$('[data-toggle="tooltip"]').tooltip();

  	 	setInterval(function () {  myTimer();  }, 60000);

		function myTimer() {
			 var urls = base_url + "/admin/get_new_time";
		   jQuery.ajax({
            url: urls,
            type: 'get',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
             $('#header_time').text(response.time);

            },
        });

		}
</script>

@if(!empty($data['js']))
	@foreach($data['js'] as $js)
		<script type="text/javascript" src="{{URL::asset('admin_assets/pages')}}/{{$js}}"></script>
	@endforeach
@endif
