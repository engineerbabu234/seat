$(document).ready(function(){
	
	let request_table = $('#laravel_datatable').DataTable({
		processing: true,
		serverSide: true,
		"ordering": false,
		destroy: true,
		ajax: base_url+'/admin/reservation/reservation_request',

		columns: [
			{ data: 'reservation_id', name: 'reservation_id' },
			{ data: 'building_name', name: 'building_name' },
			{ data: 'office_name', name: 'office_name' },
			{ data: 'user_name', name: 'user_name' },
            { data: 'email', name: 'email' },
            { data: 'profile_image', name: 'profile_image' , 
                render: function (data, type, column, meta) {
                    return '<div class="img">'+
                    '<img src="'+column.profile_image+'" width="70" >'+
                    '</div>' ;
                }
            },
            //{ data: 'profile_image', name: 'profile_image' },
			{ data: 'seat_no', name: 'seat_no' },
			{ data: 'reserve_date', name: 'reserve_date' },
			{ data: 'building_id', name: 'building_id' , 
				render: function (data, type, column, meta) {
				return '<button class="button btn-wh accept-status" reserve_seat_id="'+column.reserve_seat_id+'" title="Accept"><img src="'+base_url+'/admin_assets/images/booking_mode.png" class="white-img"></button>'+
				'<button class="button btn-wh reject-status" reserve_seat_id="'+column.reserve_seat_id+'"  title="Reject" ><img src="'+base_url+'/admin_assets/images/delete.png"class="white-img"></button>';
				}
			}
		]
	});


$('body').on('click','.btn-delete',function(e){
          var url = $(this).attr('data-url');
         swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this office data!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
         })
         .then((willDelete) => {
            if(!willDelete){
                return false;
            }
                $.ajax({
                    "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                    'type':'get',
                    'url' : url,
                beforeSend: function() {
                },
                'success' : function(response){
                    if(response.status == 'success'){
                        success_alert(response.message);
                       
                       var redrawtable = jQuery('#laravel_datatable').dataTable();
                        redrawtable.fnDraw();
                    }
                    if(response.status == 'failed'){
                        error_alert(response.message);
                      
                    }
                },
                'error' : function(error){
                },
                complete: function() {
                },
                });
         });
     });
	$('body').on('click','.accept-status', function() {
        var reserve_seat_id = $(this).attr("reserve_seat_id");
        var success_status='Accepted';            
        path='admin/reservation/accpted';
        swal({
            title: "Are you sure?",
            text: "Once accpted, you will not be able to recover this seat reservation request data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if(!willDelete){
                return false;
            }
            $.ajax({
                "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                'type':'get',
                'url' :  base_url + '/' +path,
                'data' : {  reserve_seat_id : reserve_seat_id},
                'beforeSend': function() {
                    showPageSpinner();
                },
                'success' : function(response){
                    if(response.status){ 
                        success_alert(response.message);
                        //swal(success_status ,response.message, 'success');
                       var redrawtable = jQuery('#laravel_datatable').dataTable();
                        redrawtable.fnDraw();
                         
                    }
                },
                'error' :  function(errors){
                    console.log(errors);
                },
                'complete': function() {
                    hidePageSpinner();
                }
            });
        });
    });

    $('body').on('click','.reject-status', function() {
        var reserve_seat_id = $(this).attr("reserve_seat_id");
        var success_status='Rejected';            
        path='admin/reservation/rejected';
        swal({
            title: "Are you sure?",
            text: "Once rejected, you will not be able to recover this seat reservation request data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if(!willDelete){
                return false;
            }
            $.ajax({
                "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                'type':'get',
                'url' :  base_url + '/' +path,
                'data' : {  reserve_seat_id : reserve_seat_id},
                'beforeSend': function() {
                    showPageSpinner();
                },
                'success' : function(response){
                    if(response.status){
                        success_alert(response.message);
                       // swal(success_status ,response.message, 'success'); 
                         var redrawtable = jQuery('#laravel_datatable').dataTable();
                        redrawtable.fnDraw();
                    }
                },
                'error' :  function(errors){
                    console.log(errors);
                    hidePageSpinner();
                },
                'complete': function() {
                    hidePageSpinner();
                }
            });
        });
    });
});