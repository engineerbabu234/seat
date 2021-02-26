@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">

			<!--header-->
			<div class="header">
						<div class="title">
				<div class="row align-items-center">
					<div class="col-md-6 col-sm-6 col-xs-6">
							<h2>Contract Templates List</h2>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="btns">
							<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_contracttemplates"><i class="fas fa-plus"></i></a>
						</div>
						</div>
					</div>
				</div>
			</div>
			<!--END header-->

		<div class="custom-data-table">
			<div class="data-table">

		<div class="custom-table-height">
			<div class="table-responsive">
				<table class="table table-striped text-center" id="laravel_datatable">
					<thead>
						<tr>
							<th><span class="iconWrap iconSize_32" title="Contract Templates Id."  data-trigger="hover" data-content="Contract Templates Id" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="30" ></span></th>
						    <th><span class="iconWrap iconSize_32" title="Contract Provider"  data-content="Contract Provider" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/provider.png" class="icon bl-icon" width="30" ></span> </th>
						     <th><span class="iconWrap iconSize_32" title="Provider Contract Template"  data-content="Provider Contract Template" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/document.png" class="icon bl-icon" width="30" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Contract Title" data-content="Contract Title"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="30" ></span>  </th>
							<th><span class="iconWrap iconSize_32" title="Restrict Seat" data-content="Restrict Seat"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/restrict-seat.png" class="icon bl-icon" width="30" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Contract Description"  data-content="Contract Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="30" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Expire Contract After"  data-trigger="hover" data-content="Expire Contract After" data-placement="left"><img src="{{asset('admin_assets')}}/images/expire_after.png" class="icon bl-icon" width="25" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Update Date" data-content="Update Date"   data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="30" ></span> </th>
							<th nowrap><span class="iconWrap iconSize_32" title="Action" data-content="Action"   data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="30" ></span> </th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
		</div>
	</div>
</div>




<!-- The Modal -->
<div class="modal" id="add_contracttemplates">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Contract Templates</h4>
        <button type="button" class="close close_contract_template" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" id="add-contracttemplates-form" action="#">
				@csrf
				<div class="add-contracttemplates">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Provider" data-content="Provider"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-type.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
								<select class="form-control contract_id" name="contract_id" id="contract_id" required>
									<option value="">-- Select Provider --</option>
									 @foreach($api_provider as $key => $value)
					                <option value="{{$value->id}}">{{$value->api_title}}</option>
					                @endforeach
								</select>
							 	<span class="error" id="contract_id_error"></span>

							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Document" title="Document"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/document.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>

								<select class="form-control contract_document_id" name="contract_document_id" id="contract_document_id"  >
									<option value="">-- Select Document --</option>
					            </select>
							 <span class="error" id="contract_document_id_error"></span>
							<button type="button" data-toggle="modal" data-target="#add_document" class="btn btn-success btn-sm upload_document col-12 mt-1">Upload New</button>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Contract Title" title="Contract Title"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Contract title" name="contract_title" id="contract_title" required>
								 <span class="error" id="contract_title_error"></span>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Contract Restrict Seat" title="Contract Restrict Seat"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/restrict-seat.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
								<select name="contract_restrict_seat" id="contract_restrict_seat" class="form-control">
									<option value="1">Yes</option>
									<option value="0" selected>No</option>
								</select>
								 <span class="error" id="contract_restrict_seat_error"></span>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Expire After" data-content="Expire After" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/expire_after.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
								<div class="row">
									<div class="col-sm-6">
											<select class="form-control" name="expired_value" id="expired_value">
												 <optgroup id="option_day" label="days">
							        		 		 @for ($i = 1; $i <= 31; $i++)
								            		<option value="{{ 'Day_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>
							            	      <optgroup id="option_month" label="Month">
							        		 		   @for ($i = 1; $i <= 12; $i++)
								            		<option value="{{ 'Month_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>
							            	      <optgroup id="option_week" label="Week">
							        		 		 @for ($i = 1; $i <= 52; $i++)
								            		<option value="{{ 'Week_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>
							    		</select>


									</div>
									<div class="col-sm-6">
										<select class="form-control" name="expired_option" id="expired_option">
									        <option value="day">Day(s)</option>
									       <!--  <option  value="month">Month(s)</option>
									        <option value="week">Week(s)</option> -->
									    </select>
									</div>
								</div>


								 <span class="error" id="expired_option_error"></span>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Contract Description" title="Contract Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="30" ></span>  </h6>
								 <textarea class="form-control" name="contract_description" id="contract_description" placeholder="Contract Description"></textarea>
								 <span class="error" id="contract_description_error"></span>
							</div>
						</div>


						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button class="btn btn-info add_contracttemplates" type="submit"> Add Contract Templates</button>
						</div>
					 </div>
					 </div>

				</div>
			</form>
      </div>
      <!-- Modal footer -->


    </div>
  </div>
</div>


<div class="modal" id="edit_contracttemplates">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Contract Templates</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="edit_contracttemplates_info">

      </div>
    </div>
  </div>
</div>


<!-- The Modal -->
<div class="modal" id="add_document">
  <div class="modal-dialog   modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Contract Document</h4>
        <button type="button" class="close_new close_add_document close"  >&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" id="add-document-form" name="add-document-form" enctype="multipart/form-data">
				@csrf
				<input type="hidden" class="api_connection_id" name="api_connection_id" id="">
				<div class="add-document">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Contract Document"  data-trigger="hover" data-content="Contract Document Title" data-placement="left"><img src="{{asset('admin_assets')}}/images/title.png" class="icon bl-icon" width="25" ></span><span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Contract Document Title" name="document_title" required>
								 <span class="error" id="document_title_error"></span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Contract Document"  data-trigger="hover" data-content="Contract Document File " data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="25" ></span><span class="text-danger">*</span></h6>
								<input type="file"  class="form-control"  name="document_name"  id="document_name"  >
								 <span class="error" id="document_name_error"></span>


							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Contract Document"  data-trigger="hover" data-content="Contract Document Description" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span><span class="text-danger">*</span></h6>
								 <textarea name="document_description" id="document_description" rows="8" class="form-control"></textarea>
								 <span class="error" id="document_description_error"></span>
							</div>
						</div>

						<div class="col-sm-12">
							<div class="add-product-btn text-center">
								<button class="btn btn-info add_document" type="submit"> Add Contract Document</button>
							</div>
						 </div>
					 </div>

				</div>
			</form>
      </div>
      <!-- Modal footer -->


    </div>
  </div>
</div>


@endsection
@push('css')
@endpush
@push('js')
  <script type="text/javascript" src="{{asset('public')}}/js/sweetalert.min.js"></script>
 <script type="text/javascript">

			urls = base_url+'/admin/contract_templates/';


 		var asset_datatable =$('#laravel_datatable').DataTable({
			processing: true,
			serverSide: true,
			"ordering": false,
			destroy: true,
			ajax:urls ,

			columns: [
				{ data: 'number_key', name: 'number_key' },
				{ data: 'contract_provider', name: 'contract_provider' },
				{ data: 'contract_title', name: 'contract_title' },
				{ data: 'document_title', name: 'document_title' },
				{ data: 'contract_restrict_seat', name: 'contract_restrict_seat' },
				{ data: 'contract_description', name: 'contract_description'},
				 { data: 'expired_option', name: 'expired_option' },
				{ data: 'updated_at', name: 'updated_at' },
				{ data: 'id', name: 'id' ,
					render: function (data, type, column, meta) {
						return '<a  href="#" data-id="'+column.id+'" class="button btn-wh   edit_contracttemplates_request"><img src="'+base_url+'/admin_assets/images/edit.png" title="edit" class="white-img"></a>'+
	 					'<button class="button btn-wh   btn-delete" data-url="'+base_url+'/admin/contract_templates/delete/'+column.id+'"><img src="'+base_url+'/admin_assets/images/delete.png" title="delete" class="white-img"></button>';
					}
				}
			]
		});

 	$(function(e){



  	    	 // Departmetn remove confirmation modal
  	 $('body').on('click','.btn-delete',function(e){
	 	  var url = $(this).attr('data-url');
  	 	 swal({
		  title: "Are you sure?",
		  text: "Are you sure you want to delete Contract Templates?",
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
					    var redrawtable = jQuery('#laravel_datatable').dataTable();
						redrawtable.fnDraw();
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

 	})



$(document).on("click", ".add_contracttemplates", function(e) {
	e.preventDefault();
	 $('.error').removeClass('text-danger');
     $('.error').text('');
	var data = jQuery(this).parents('form:first').serialize();
	$('.error').text('');;
	$('.error').removeClass('text-danger');
	$.ajax({
		url: base_url + '/admin/contract_templates/store',
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
				$("form#add-contracttemplates-form")[0].reset();
				success_alert(response.message);
				//swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('.error').removeClass('text-danger');
				$('#add_contracttemplates').modal('hide');
			}
		},
	});
});



$(document).on("click", ".edit_contracttemplate", function(e) {
	e.preventDefault();

	var data = jQuery(this).parents('form:first').serialize();
	var id = $(this).data('id');
	$.ajax({
		url: base_url + '/admin/contract_templates/update/'+id,
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
				$("form#edit-contracttemplates-form")[0].reset();
				success_alert(response.message);
				//swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('.error').removeClass('text-danger');
				$('#edit_contracttemplates').modal('hide');
			}
		},
	});
});


$(document).on("click", ".edit_contracttemplates_request", function(e) {
	e.preventDefault();
	var id = $(this).data('id');

	var aurls = base_url + "/admin/contract_templates/edit_contracttemplates/" + id;
	jQuery.ajax({
		url: aurls,
		type: 'get',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(response) {

			if (response.success) {
				$('#edit_contracttemplates_info').html(response.html);
				$('#edit_contracttemplates').modal('show');

				$('.upload_document').hide();

				$('.upload_document').on('click',function(event) {
					event.preventDefault();
					 $("form#add-document-form")[0].reset();
					var contract_id = $('.edit_contract_id option:selected').val();
					if(contract_id){
						$('.api_connection_id').val(contract_id);
						$('.upload_document').show();
					} else{
						$('.upload_document').hide();
					}

				});

				$('.edit_contract_id').on('change',function(event) {
					event.preventDefault();
					var contract_id = $(this).val();
					get_document_list(contract_id);
					if(contract_id ){
						$('.upload_document').show();
					} else{
						$('.upload_document').hide();
					}

				});
			}
		},
	});
});



$(document).on("click", ".add_document", function(e) {
    e.preventDefault();
    showPageSpinner();
     $('.error').removeClass('text-danger');
     $('.error').text('');
     var form = $('form#add-document-form')[0];
       var data = new FormData(form);

    $.ajax({
        url: base_url + '/admin/contract_templates/add_document',
        type: 'post',
        dataType: 'json',
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(response) {
        	hidePageSpinner();
            if (response.status == 400) {
                $.each(response.responseJSON.errors, function(k, v) {
                    $('#' + k + '_error').text(v);
                    $('#' + k + '_error').addClass('text-danger');
                });
            }
        },
        success: function(response) {
            if (response.success) {
                $("form#add-document-form")[0].reset();
                success_alert(response.message);

                get_document_list(response.api_connection_id,response.document_id)
                $('#add_document').modal('hide');
                $('.error').removeClass('text-danger');
            }
            hidePageSpinner();
        },
    });
});



$('.upload_document').hide();
$('.contract_id').on('change',function(event) {
	event.preventDefault();
	var contract_id = $(this).val();
	get_document_list(contract_id);
	if(contract_id ){
		$('.upload_document').show();
	} else{
		$('.upload_document').hide();
	}

});

$('.upload_document').on('click',function(event) {
	event.preventDefault();
	 $("form#add-document-form")[0].reset();
	var contract_id = $('.contract_id option:selected').val();
	if(contract_id){
		$('.api_connection_id').val(contract_id);
		$('.upload_document').show();
	} else{
		$('.upload_document').hide();
	}

});


	function get_document_list(api_connection_id,document_id=''){

		if(api_connection_id){

			$.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/admin/contract_templates/get_document_list/"+api_connection_id,

                   success:function(res)
                   {
                        if(res.data)
                        {
                            $(".contract_document_id").empty();
                             $(".contract_document_id").append("<option value=''>-- Select Contract Document --</option>");
                            $.each(res.data,function(key,value){

                            	if(document_id !=''){
                            		if(document_id == value.id){
                            			$(".contract_document_id").append("<option selected value="+value.id+">"+value.document_title+"</option>");
                            		} else{
                                		$(".contract_document_id").append("<option  value="+value.id+">"+value.document_title+"</option>");
                            		}
                            	} else{
                            		$(".contract_document_id").append("<option  value="+value.id+">"+value.document_title+"</option>");
                            	}
                            });
                        }
                   }

                });
		} else{
            $("#contract_document_id").empty();
            $("#contract_document_id").append("<option value=''>-- Select Contract Document --</option>");
        }

	}


 $(document).on('click','.close_add_document',function(){
	$('.error').removeClass('text-danger');
    $('.error').text('');
    $("form#add-document-form")[0].reset();
    $('#add_document').modal('hide');
});

$(document).on('click','.close_contract_template',function(){
	$('.error').removeClass('text-danger');
     $('.error').text('');
     $("form#add-contracttemplates-form")[0].reset();
    $('#add_contracttemplates').modal('hide');
});



$(document).on("click", ".test_apiconnections", function(e) {
	e.preventDefault();
	showPageSpinner();
	var contract_id = $('#contract_id option:selected').val();

	$('.error').text('');;
	$('.error').removeClass('text-danger');
	$.ajax({
		url: base_url + '/admin/contract_templates/check_api',
		type: 'post',
		dataType: 'json',
		data:  {'contract_id':contract_id},
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
			hidePageSpinner();
		},
		success: function(response) {
			if (response.success) {
				success_alert(response.message);
				$('.test_apiconnections').hide();
        		$('.upload_document').show();
        		hidePageSpinner();
			}
		},
	});
});

$('#option_day').show();
	$('#option_week').hide();
	$('#option_month').hide();

$(document).on('change',"#expired_option",function(){

	 $(this).find('option').removeAttr("selected");
	 $("#expired_value").find('option').removeAttr("selected");

	var options = $(this).find("option:selected");

	if(options.val() == 'month'){
		$('#eoption_day').css('display', 'none');
		$('#eoption_week').css('display', 'none');
		$('#eoption_month').css('display', 'block');
		$('#expired_option option[value=1]').attr('selected','selected');

		$('#option_day').hide();
		$('#option_week').hide();
		$('#option_month').show();
	}

	if(options.val() == 'day'){
		$('#eoption_day').css('display', 'block');
		$('#eoption_week').css('display', 'none');
		$('#eoption_month').css('display', 'none');
		$('#expired_option option[value=1]').attr('selected','selected');

		$('#option_day').show();
		$('#option_week').hide();
		$('#option_month').hide();
	}

	if(options.val() == 'week'){

		$('#eoption_day').css('display', 'none');
		$('#eoption_week').css('display', 'block');
		$('#eoption_month').css('display', 'none');
		$('#expired_option option[value=1]').attr('selected','selected');

		$('#option_day').hide();
		$('#option_week').show();
		$('#option_month').hide();
	}

});

 </script>
@endpush