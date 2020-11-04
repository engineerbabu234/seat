$(document).ready(function(){
	
	$('#laravel_datatable').DataTable({
		processing: true,
		serverSide: true,
		"ordering": false,
		destroy: true,
		ajax: base_url+'/admin/building',

		columns: [
			{ data: 'number_key', name: 'number_key' },
			{ data: 'building_name', name: 'building_name' },
			{ data: 'building_id', name: 'building_id',
				render: function (data, type, column, meta) {
					return ' <a target="_blank" href="'+base_url+'/admin/building/office_list/'+column.building_id+'" class="button accept">'+column.office_count+'</a>';
					 } },
			{ data: 'created_at', name: 'created_at' }, 
			{ data: 'building_id', name: 'building_id' , 
				render: function (data, type, column, meta) {
					return '<a href="'+base_url+'/admin/building/edit_building/'+column.building_id+'" class="button accept">Edit</a>'+
					 '<button class="button reject btn-delete" data-url="'+base_url+'/admin/building/delete/'+column.building_id+'">Delete</button>';
				}
			}
		]
	});

	$('body').on('click','.btn-delete',function(e){
	 	  var url = $(this).attr('data-url');
  	 	 swal({
		  title: "Are you sure you want to delete?",
		  text: "Once deleted, you will not be able to recover this building and all ossociated offices data!",
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
					    //getOffices();
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