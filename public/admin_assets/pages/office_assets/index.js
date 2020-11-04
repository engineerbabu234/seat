$(document).ready(function(){
	
	var asset_datatable =$('#asset_datatable').DataTable({
		processing: true,
		serverSide: true,
		"ordering": false,
		destroy: true,
		ajax: base_url+'/admin/office/asset',

		columns: [
			{ data: 'id', name: 'id' },
			{ data: 'office_name', name: 'office_name' },
			{ data: 'building_name', name: 'building_name' },
			{ data: 'title', name: 'title' },
			{ data: 'total_seats', name: 'total_seats' },
			{ data: 'created_at', name: 'created_at' }, 
			{ data: 'building_id', name: 'building_id' , 
				render: function (data, type, column, meta) {
					return '<a href="#" data-id="'+column.id+'" class="button accept edit_office_assets_request">Edit</a>'+
 					'<button class="button reject btn-delete" data-url="'+base_url+'/admin/office/asset/delete/'+column.id+'">Delete</button>';
				}
			}
		]
	});



	$('body').on('click','.btn-delete',function(e){
	 	  var url = $(this).attr('data-url');
  	 	 swal({
		  title: "Are you sure you want to delete?",
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
					'type':'post',
					'url' : url,
				beforeSend: function() {
				},
				'success' : function(response){
					if(response.status == 'success'){
						swal("Success!",response.message, "success");
						
					}
					if(response.status == 'failed'){
						swal("Failed!",response.message, "error");
					}
				},
				'error' : function(error){
				},
				complete: function() {
					 var redrawtable = jQuery('#asset_datatable').dataTable();
                    	redrawtable.fnDraw();
					
				},
				});
		 });
  	 });
});



$(document).on("click", ".add-office-btn", function(e) {
	e.preventDefault();
 	 var photo = $("form#add-office-asset-form").find(".dropify-render").find("img").attr("src");
        var data = jQuery(this).parents('form:first').serialize();
        if(photo){
        	data += "&preview_image=" + photo;
        }
	$.ajax({
		url:base_url+'/admin/office/asset/add' ,
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
				$("form#add-office-asset-form")[0].reset();
				swal("Success!",response.message, "success"); 	 	
				var redrawtable = jQuery('#asset_datatable').dataTable();
                    redrawtable.fnDraw();
                     $('#add_asset').modal('hide');
			}
		},
	});
});



$(document).on("click", ".edit-office-btn", function(e) {
	e.preventDefault();
 	 var photo = $("form#add-office-asset-form").find(".dropify-render").find("img").attr("src");
        var data = jQuery(this).parents('form:first').serialize();
        if(photo){
        	data += "&preview_image=" + photo;
        }
       var id = $(this).data('id'); 
	$.ajax({
		url:base_url+'/admin/office/asset/update/'+id ,
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
				$("form#add-office-asset-form")[0].reset();
				swal("Success!",response.message, "success");  
				var redrawtable = jQuery('#asset_datatable').dataTable();
                    redrawtable.fnDraw();
                    $('.error').removeClass('text-danger'); 
                     $('#edit_modal').modal('hide');
			}
		},
	});
});



$(document).on("change", "#building_id", function(e) {
	var building_id = $('#building_id option:selected').val();
	 var urls = base_url + "/admin/office/asset/getoffice/"+building_id;
	 

	jQuery.ajax({
        url:urls,
        type: 'get',
        dataType: 'json', 
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
        	 
            if (response.success) { 
            	var output = [];

				$.each(response.data, function(key, value)
				{
				  output.push('<option value="'+ value.office_id +'">'+ value.office_name +'</option>');
				});

				$('#bindoffices').html(output.join(''));


              
            }
        },
    }); 

});
 

$(document).on("change", "#edit_building_id", function(e) {
	var building_id = $('#edit_building_id option:selected').val();
	 var urls = base_url + "/admin/office/asset/getoffice/"+building_id; 
	jQuery.ajax({
        url:urls,
        type: 'get',
        dataType: 'json', 
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
        	 
            if (response.success) { 
            	var output = [];

				$.each(response.data, function(key, value)
				{
				  output.push('<option value="'+ value.office_id +'">'+ value.office_name +'</option>');
				});

				$('#editbindoffices').html(output.join(''));


              
            }
        },
    }); 

});
 

$(document).on("click", ".edit_office_assets_request", function(e) {
	e.preventDefault();
var id = $(this).data('id');
 
 var aurls = base_url + "/admin/office/asset/edit/"+id;
jQuery.ajax({
        url:aurls,
        type: 'post',
        dataType: 'json', 
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
        	 
            if (response.success) { 
            	 $('#edit_assets').html(response.html);
            	var drEvent = $('.dropify-event').dropify();
 				 $('#edit_modal').modal('show');	
              
            }
        },
    });  
});