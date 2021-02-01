$(document).ready(function(){
	
	$('#laravel_datatable').DataTable({
		processing: true,
		serverSide: true, 
		destroy: true, 
		ajax: base_url+'/admin/seat_label', 

		columns: [
			{ data: 'number_key', name: 'number_key' },
			{ data: 'order_date', name: 'order_date' },
			{ data: 'config', name: 'config' },
			{ data: 'no_labels', name: 'no_labels' },
			{ data: 'status', name: 'status' }, 
		]
	});
 
});
 
$('.addseatlabel').hide();
$(document).on("change", "#office_asset_id", function(e) {
    var office_asset_id = $('#office_asset_id :selected').val();
    if(office_asset_id != ''){
          $('.addseatlabel').show(); 
    } else {
        $('.addseatlabel').hide();
    }
});

$(document).on("click", ".addseatlabel", function(e) {
	e.preventDefault();
	 
	var data = jQuery(this).parents('form:first').serialize();
	 
	$.ajax({
		url: base_url + '/admin/seat_label/store',
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
				$("form#add-seatlabel-form")[0].reset();
				success_alert(response.message);
				//swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('#add_seatlabel').modal('hide');
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
				success_alert(response.message);
				//swal("Success!", response.message, "success");
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

		

$(document).on("click", ".view_deploy_label", function(e) {
	e.preventDefault();
	var id = $(this).data('id');

	var aurls = base_url + "/admin/seat_label/get_deploy_info/"+id;
	jQuery.ajax({
		url: aurls,
		type: 'get',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(response) {

			if (response.success) {			 
				$('#deploy_label_modal').modal('show');
				$('#deploy_label').html(response.html);
				get_deploy_label(id);

			}
		},
	});
});

 function get_deploy_label(label){ 

 	laravel_datatable_deploy = $('#laravel_datatable_deploy').dataTable({
                    "bProcessing": false,
                    "bServerSide": true,
                    "bStateSave": true,
                    "autoWidth": false,
                    "destroy": true,
                     "ordering": false,
                    "sAjaxSource": base_url+'/admin/seat_label/deploy_label', 
                    "fnServerParams": function(aoData) { 
                   	 aoData.push({
                        "name": "label_id",
                        "value": label
	                    } );
	                 },
                    "sPaginationType": "numbers",
                    "oLanguage": {
                    "sLengthMenu": "Display _MENU_ records"
                    },
                    "aoColumns": [
                    { "mData": "number_key",bSortable: true,sWidth: "5%"},
                    { "mData": "building_name",bSortable: true,sWidth: "15%"},
                    { "mData": "office_name",bSortable: true,sWidth: "15%"},
                    { "mData": "title",bSortable: true,sWidth: "15%"}, 
                    { "mData": "seat",bSortable: true,sWidth: "5%"},
                    { "mData": "status",bSortable: true,sWidth: "5%"},  
                     {
                        "mData": "action",
                        Width: "10%",
                        Class: "text-center",
                        mRender: function(v, t, o) {
                              
                            if (o.deploy_status == 1) {
                                action = '<a data-id="'+o.number_key+'" data-seat_label_id="'+o.seat_label_id+'" class="btn btn-link deploy_seat"><span class=" text-info">Deploy to seat</span></a>';
                            } else if(o.deploy_status == 2){
                                action = '<span class="text-muted ">Deployed to seat</span>';
                            }  else if(o.deploy_status == 3){
                                action = '<span class="text-muted ">Activated</span>';
                            }   
                           
                            return action;
                        },
                    }, 
                    ],
                    
                });

        

    }


        $('#building_id').on('change', function(event) {
            event.preventDefault();
            var building_id = $(this).val();
            if(building_id){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/filter_office_list/"+building_id,

                   success:function(res)
                   {
                        if(res.data)
                        {
                            $("#office_id").empty();
                             $("#office_id").append("<option value=''>All</option>");
                            $.each(res.data,function(key,value){
                                $("#office_id").append("<option value="+value.office_id+">"+value.office_name+"</option>");
                            });
                        }
                   }

                });
            } else{
            	 $("#office_asset_id").empty();
                 $("#office_asset_id").append("<option value=''>All</option>");
                 $("#office_id").empty();
            	$("#office_id").append("<option value=''>All</option>");
            	$("#seat_id").empty();
	            $("#seat_id").append("<option value=''>All</option>");
            }
        });

        $('#office_id').on('change', function(event) {
            event.preventDefault();
            var office_id = $(this).val();
            if(office_id){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/filter_office_assets_list/"+office_id,

                   success:function(res)
                   {
                        if(res.data)
                        {
                            $("#office_asset_id").empty();
                               $("#office_asset_id").append("<option value=''>All</option>");
                            $.each(res.data,function(key,value){
                                $("#office_asset_id").append("<option value="+value.id+">"+value.title+"</option>");
                            });
                        }
                   }

                });
            } else{
            	$("#office_asset_id").empty();
            	$("#office_asset_id").append("<option value=''>All</option>");
            	$("#seat_id").empty();
	            $("#seat_id").append("<option value=''>All</option>");
            }
        });


        $('#office_asset_id').on('change', function(event) {
            event.preventDefault();
            var office_assets_id = $(this).val();
            if(office_assets_id){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/admin/seat_label/filter_seat_list/"+office_assets_id, 
                   success:function(res)
                   {

                        if(res.data)
                        {
	                        $("#seat_id").empty();
	                          $("#seat_id").append("<option value=''>All</option>");
	                            $.each(res.data,function(key,value){
	                            $("#seat_id").append("<option value="+value.seat_id+">"+value.seat_no+"</option>");
	                        });
                        }
                   }

                });
            } 
        });

        $('body').on('click','.deploy_seat',function(e){ 
               e.preventDefault();
            var deploy_id = $(this).data('id');
            var label_id = $(this).data('seat_label_id');

             swal({
              title: "Are you sure you want to deploy this seat?", 
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
                        'url' :  base_url+"/admin/seat_label/change_deploy_seat/"+deploy_id,
                    beforeSend: function() {
                    },
                    'success' : function(response){
                        if(response.status == true){
                            success_alert(response.message); 
                            get_deploy_label(label_id); 
                            var redrawtable = jQuery('#laravel_datatable').dataTable();
                            redrawtable.fnDraw();
                        }
                        if(response.status == false){
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


 $('#orderid_pophover').hide();

$('#order_id').popover({
    content:  $('#orderid_pophover').html(),  
    placement: 'left',
    html: true
});

$('#orderdate_pophover').hide(); 
$('#order_date').popover({
    content:  $('#orderdate_pophover').html(),  
    placement: 'left',
    html: true
});

$('#orderstatus_pophover').hide(); 
$('#order_status').popover({
    content:  $('#orderstatus_pophover').html(),  
    placement: 'left',
    html: true
});



$('#orderconfig_pophover').hide(); 
$('#order_config').popover({
    content:  $('#orderconfig_pophover').html(),  
    placement: 'left',
    html: true
});