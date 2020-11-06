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
					if(column.office_count > 0 ){
					return ' <a target="_blank" href="'+base_url+'/admin/office/'+column.building_id+'" class="button accept">'+column.office_count+'</a>';
					 } else{
					 	return ' <a   href="#" class="button accept">'+column.office_count+'</a>';
					
					 }
				} 
			},
			{ data: 'created_at', name: 'created_at' }, 
			{ data: 'building_id', name: 'building_id' , 
				render: function (data, type, column, meta) {
					return '<a  data-id="'+column.building_id+'" href="#" class="button accept edit_building_request">Edit</a>'+
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




$(document).on("click", ".add_building", function(e) {
	e.preventDefault();
	 
	var data = jQuery(this).parents('form:first').serialize();
	 
	$.ajax({
		url: base_url + '/admin/building/store',
		type: 'post',
		dataType: 'json',
		data: data,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		error: function(response) {
			if (response.status == 400) {
				$.each(response.responseJSON.errors, function(k, v) {
					$('#' + k + '_error').text(v);
					$('#' + k + '_error').addClass('text-danger');
				});
			}
		},
		success: function(response) {
			if (response.success) {
				$("form#add-building-form")[0].reset();
				swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('#add_building').modal('hide');
			}
		},
	});
});



$(document).on("click", ".edit_building", function(e) {
	e.preventDefault();
 
	var data = jQuery(this).parents('form:first').serialize();
	var id = $(this).data('id'); 
	$.ajax({
		url: base_url + '/admin/building/update/'+id,
		type: 'post',
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
				$("form#edit-building-form")[0].reset();
				swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('.error').removeClass('text-danger');
				$('#edit_building').modal('hide');
			}
		},
	});
});


$(document).on("click", ".edit_building_request", function(e) {
	e.preventDefault();
	var id = $(this).data('id');

	var aurls = base_url + "/admin/building/edit_building/" + id;
	jQuery.ajax({
		url: aurls,
		type: 'get',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(response) {

			if (response.success) {
				$('#edit_building_info').html(response.html);
			 
				$('#edit_building').modal('show');

			}
		},
	});
});