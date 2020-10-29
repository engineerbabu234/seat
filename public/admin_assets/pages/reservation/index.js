$(document).ready(function(){
	
	$('#laravel_datatable').DataTable({
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
				return '<button class="button accept accept-status" reserve_seat_id="'+column.reserve_seat_id+'" >Accept</button>'+
				'<button class="button reject reject-status" reserve_seat_id="'+column.reserve_seat_id+'" >Reject</button>';
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
                        swal("Success!",response.message, "success");
                        location.reload();
                    }
                    if(response.status == 'failed'){
                        swal("Failed!",response.message, "error");
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

                },
                'success' : function(response){
                    if(response.status){
                        swal(success_status ,response.message, 'success');
                        location.reload();
                    }
                },
                'error' :  function(errors){
                    console.log(errors);
                },
                'complete': function() {

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

                },
                'success' : function(response){
                    if(response.status){
                        swal(success_status ,response.message, 'success');
                        location.reload();
                    }
                },
                'error' :  function(errors){
                    console.log(errors);
                },
                'complete': function() {

                }
            });
        });
    });
});