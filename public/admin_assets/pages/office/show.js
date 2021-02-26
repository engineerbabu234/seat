$(document).ready(function(){

	
	
	$('#laravel_datatable').DataTable({
		processing: true,
		serverSide: true,
		"ordering": false,
		destroy: true,
		ajax: base_url+'/admin/office/office_details/'+office_id,

		columns: [
			{ data: 'number_key', name: 'number_key' },
			{ data: 'seat_no', name: 'seat_no' },
			{ data: 'seat_id', name: 'seat_id' , 
				render: function (data, type, column, meta) {
					if(column.seat_type=='2'){
						return '<label class="label rejected">Blocked</label>';
					}else{
						if(column.status=='1'){
							return '<label class="label blocked">Booked</label>';
						}else if(column.status=='0'){
							return '<label class="label accepted">Available</label>';
						}
					}
					
					
				}
			},
			{ data: 'updated_at', name: 'updated_at' },
			{ data: 'seat_id', name: 'seat_id' , 
				render: function (data, type, column, meta) {
					 //return '<a href="edit_office_building.html" class="button accept">Edit</a>'+
					 return '<button class="button reject btn-delete" data-url="'+base_url+'/admin/office/delete_seat/'+column.seat_id+'" >Delete</button>';
					
				}
			},


			
		]
	});

	$('body').on('click','.btn-delete',function(e){
	 	  var url = $(this).attr('data-url');
  	 	 swal({
		  title: "Are you sure you want to delete office and related office assets?",
		  //text: "Once deleted, you will not be able to recover this office data!",
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
						//swal("Success!",response.message, "success");
						location.reload();
					    //getOffices();
					}
					if(response.status == 'failed'){
						error_alert(response.message);
						//swal("Failed!",response.message, "error");
					}
				},
				'error' : function(error){
				},
				complete: function() {
				},
				});
		 });
  	 });
});