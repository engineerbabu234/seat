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
			{ data: 'id', name: 'id' },
			{ data: 'user_name', name: 'approve_status',
				render: function (data, type, column, meta) { 
			 	return '<button class="btn btn-link edit_users_request" data-id="'+column.id+'" >'+column.user_name+'</button>';
						 					
				}
			},  
			{ data: 'job_profile', name: 'job_profile' }, 
			{ data: 'role', name: 'role' }, 
			{ data: 'email', name: 'email' },
			{ data: 'profile_image', name: 'profile_image' , 
				render: function (data, type, column, meta) {
					return '<div class="img">'+
					'<img src="'+column.profile_image+'" width="70">'+
					'</div>' ;
				}
			},
			{ data: 'updated_at', name: 'updated_at' }, 
			{ data: 'approve_status', name: 'approve_status',
				render: function (data, type, column, meta) {
					if(column.email_verify_status!='1'){
						return '<button class="button reject">Pending Email Verification</button>';
					}else{
						if(column.approve_status=='0'){
							return '<button class="button btn-wh approve-status-change"  user_id="'+column.id+'" status="1"><span class="iconWrap iconSize_32"  title="Approve"  data-content="Approve Profile" data-trigger="hover"><img src="'+base_url+'/admin_assets/images/question.png"  class="white-img"></span></button>'+
							'<button class="button btn-wh   btn-delete  reject-status-change" user_id="'+column.id+'" status="2"><span class="iconWrap iconSize_32"  title="Delete"    data-content="Delete Record"  data-trigger="hover"><img src="'+base_url+'/admin_assets/images/delete.png"  class="white-img"></span></button>';
						}else if(column.approve_status=='1'){
							return '<button class="button  btn-wh" ><span class="iconWrap iconSize_32"  title="Approved"  data-content="Approved Profile" data-trigger="hover"><img src="'+base_url+'/admin_assets/images/status.png"  class="white-img"></span></button>'+
							'<button class="button btn-wh   btn-delete  reject-status-change" user_id="'+column.id+'" status="2"><span class="iconWrap iconSize_32"  title="Delete"    data-content="Delete Record"  data-trigger="hover"><img src="'+base_url+'/admin_assets/images/delete.png"  class="white-img"></span></button>';
						}else if(column.approve_status=='2'){
							return '<button class="button btn-wh   btn-delete "><span class="iconWrap iconSize_32"  title="Delete"   data-content="Delete Record"  data-trigger="hover"><img src="'+base_url+'/admin_assets/images/delete.png" class="white-img"></span></button>';
						}
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
            	$(this).prop('disabled', false);
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
							success_alert(response.message);
							//swal('Approved' ,response.message, 'success');
						}else{
							success_alert(response.message);
							//swal('Rejected' ,response.message, 'success');
						}
						var redrawtable = jQuery('#laravel_datatable').dataTable();
						redrawtable.fnDraw();
						 
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
            text: "Once deleted, you will not be able to recover this user request data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if(!willDelete){
            	$(this).prop('disabled', false);
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
							success_alert(response.message);
							//swal('Approved' ,response.message, 'success');
						}else{
							success_alert(response.message);
							//swal('Rejected' ,response.message, 'success');
						} 
						var redrawtable = jQuery('#laravel_datatable').dataTable();
						redrawtable.fnDraw();
						 
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


	$(document).on("click", ".edit_users", function(e) {
	e.preventDefault();

			var data = jQuery(this).parents('form:first').serialize();
			var id = $(this).data('id');
			$.ajax({
				url: base_url + '/admin/user/update/'+id,
				type: 'put',
				dataType: 'json',
				data: data,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				error: function(response) {
					if (response.status == 400) {
						$.each(response.responseJSON.errors, function(k, v) {
							$('#edit_' + k + '_error').text(v);
							$('#edit_' + k + '_error').addClass('text-danger');
						});
					}
				},
				success: function(response) {
					if (response.success) {
						$("form#edit-users-form")[0].reset();
						success_alert(response.message);
					 
						var redrawtable = jQuery('#laravel_datatable').dataTable();
						redrawtable.fnDraw();
						$('.error').removeClass('text-danger');
						$('#edit_users').modal('hide');
					}
				},
			});
		});


		$(document).on("click", ".edit_users_request", function(e) {
			e.preventDefault();
			var id = $(this).data('id');

			var aurls = base_url + "/admin/user/edit/" + id;
			jQuery.ajax({
				url: aurls,
				type: 'get',
				dataType: 'json',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(response) {

					if (response.success) {
						$('#edit_users_info').html(response.html);

						$('#edit_users').modal('show');

					}
				},
			});
		});

		 $('#usertype_popup').hide();

        $('#usertype_info').popover({
            content:  $('#usertype_popup').html(),  
            placement: 'left',
            html: true
        });
 
});
 
