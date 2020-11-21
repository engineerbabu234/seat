$(document).ready(function(){
	$('#laravel_datatable').DataTable({
		processing: true,
		serverSide: true,
		"ordering": false,
		destroy: true,
		ajax: base_url+'/admin/building/office_list/'+building_id,

		columns: [
			{ data: 'number_key', name: 'number_key' },
			{ data: 'office_number', name: 'office_number' },
			{ data: 'office_name', name: 'office_name' },
			{ data: 'seats_count', name: 'seats_count' },
			{ data: 'created_at', name: 'created_at' },
			{ data: 'office_id', name: 'office_id' , 
				render: function (data, type, column, meta) {
					return '<a href="'+base_url+'/admin/office/edit_office/'+column.office_id+'" class="button accept">Edit</a>'+
					'<a href="'+base_url+'/admin/office/office_details/'+column.office_id+'" class="button accept">Details</a>'+
					'<button class="button reject btn-delete" data-url="'+base_url+'/admin/office/delete/'+column.office_id+'">Delete</button>';
					
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
});