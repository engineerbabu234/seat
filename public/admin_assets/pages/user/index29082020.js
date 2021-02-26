$(document).ready(function(){
	
	$('#laravel_datatable').DataTable({
		processing: true,
		serverSide: true,
		//retrieve: true,
		"ordering": false,
		destroy: true,
		ajax: base_url+'/admin/user',
		//dom: 'lBfrtip',
		//"scrollX": false,
		/*buttons:[
		'copy', 'csv', 'pdf', 'print'
		],*/
		//"lengthMenu": [ 10, 50, 100, 500],
		columns: [
			{ data: 'number_key', name: 'number_key' },

			

			{ data: 'user_name', name: 'user_name' },

			{ data: 'email', name: 'email' , 
				render: function (data, type, column, meta) {
				    if(column.email_verify_status == '1'){
				      return '<p>'+column.email+'(Email is verified)<p>';
				    }else{
				      return '<p>'+column.email+'(Email is not verify yet)<p>';
				    }
				}
			},
			{ data: 'created_at', name: 'created_at' },

			{ data: 'profile_image', name: 'profile_image' , 
				render: function (data, type, column, meta) {
					return '<div class="img">'+
					'<img src="'+column.profile_image+'" width="70">'+
					'</div>' ;
				}
			},

			{ data: 'approve_status', name: 'approve_status',
				render: function (data, type, column, meta) {
					if(column.approve_status=='0'){
						return '<button class="button accept approve-status-change"  user_id="'+column.id+'" status="1">Approve</button>'+
						'<button class="button reject reject-status-change" user_id="'+column.id+'" status="2">Reject</button>';
					}else if(column.approve_status=='1'){
						return '<button class="button accept" >Approved</button>';
					}else if(column.approve_status=='2'){
						return '<button class="button reject">Rejected</button>';
					}
				}
			},
		]
	});

	$('body').on('click','.approve-status-change', function() {
		var user_id = $(this).attr("user_id");
		var status = $(this).attr("status");
		$(this).prop('disabled', true);
		//status=$(this).closest('tr').find('.approve-status-change').val();
		console.log(status);
		path='admin/user/approve_status_change/';
		swal({
            title: "Are you sure?",
            text: "Once approved, you will not be able to recover this user request data!",
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

				'type':'PUT',
				'url' :  base_url + '/' +path,
				'data' : {  id : user_id, status:status },
				'beforeSend': function() {

				},
				'success' : function(response){
					if(response.status == 'success'){
						if(status=='1'){
							swal('Approved' ,response.message, 'success');
						}else{
							swal('Rejected' ,response.message, 'success');
						}
						
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

	$('body').on('click','.reject-status-change', function() {
		var user_id = $(this).attr("user_id");
		var status = $(this).attr("status");
		$(this).prop('disabled', true);
		//status=$(this).closest('tr').find('.approve-status-change').val();
		console.log(status);
		path='admin/user/approve_status_change/';
		swal({
            title: "Are you sure?",
            text: "Once rejected, you will not be able to recover this user request data!",
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

				'type':'PUT',
				'url' :  base_url + '/' +path,
				'data' : {  id : user_id, status:status },
				'beforeSend': function() {

				},
				'success' : function(response){
					if(response.status == 'success'){
						if(status=='1'){
							swal('Approved' ,response.message, 'success');
						}else{
							swal('Rejected' ,response.message, 'success');
						}
						
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