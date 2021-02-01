@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<div class="title">
						<h2>Contract Templates List</h2>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_contracttemplates"><i class="fas fa-plus"></i></a>

					</div>
				</div>
			</div>
		</div><!--END header-->
		<!--my tenders-->
		<div class="custom-data-table">
			<div class="data-table">

		<div class="custom-table-height">
			<div class="table-responsive">
				<table class="table table-striped text-center" id="laravel_datatable">
					<thead>
						<tr>
							<th><span class="iconWrap iconSize_32" title="Contract Templates Id."  data-trigger="hover" data-content="Contract Templates Id" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="30" ></span></th>
						    <th><span class="iconWrap iconSize_32" title="Contract Provider"  data-content="Contract Provider" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-type.png" class="icon bl-icon" width="30" ></span> </th>
						     <th><span class="iconWrap iconSize_32" title="Provider Contract Template"  data-content="Provider Contract Template" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/provider.png" class="icon bl-icon" width="30" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="title" data-content="Contract Title"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="30" ></span>  </th>
							<th><span class="iconWrap iconSize_32" title="Restrict Seat" data-content="Restrict Seat"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/restrict-seat.png" class="icon bl-icon" width="30" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Contract Description"  data-content="Contract Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/secrect_key.png" class="icon bl-icon" width="30" ></span> </th>
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
		</div><!--END my tenders-->
	</div>
</div>




<!-- The Modal -->
<div class="modal" id="add_contracttemplates">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Contract Templates</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
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
					                	<option value="{{$key}}">{{$value}}</option>
					                @endforeach
							</select>
							 <span class="error" id="contract_id_error"></span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Document" title="Document"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-type.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>

								<select class="form-control contract_document_id" name="contract_document_id" id="contract_document_id"  >
									<option value="">-- Select Document --</option>
					            </select>
							 <span class="error" id="contract_document_id_error"></span>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Api Description" title="Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="30" ></span>  </h6>
								 <textarea class="form-control" name="contract_document_id" id="contract_document_id"></textarea>
								 <span class="error" id="contract_document_id_error"></span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Contract Title" title="Contract Title"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Contract title" name="api_title" required>
								 <span class="error" id="contract_title_error"></span>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Contract Restrict Seat" title="Contract Restrict Seat"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-access.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
								<select name="contract_restrict_seat" id="contract_restrict_seat" class=""></select>
								 <span class="error" id="contract_restrict_seat_error"></span>
							</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Api Secret"  data-trigger="hover" data-content="Api Secret" data-placement="left"><img src="{{asset('admin_assets')}}/images/secrect_key.png" class="icon bl-icon" width="25" ></span>  </h6>
							<input type="text" class="form-control" placeholder="Api Secret" name="api_secret" required>
							 <span class="error" id="api_secret_error"></span>
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




@endsection
@push('css')
@endpush
@push('js')
  <script type="text/javascript" src="{{asset('public')}}/js/sweetalert.min.js"></script>
 <script type="text/javascript">

			urls = base_url+'/admin/contracttemplates/';


 		var asset_datatable =$('#laravel_datatable').DataTable({
			processing: true,
			serverSide: true,
			"ordering": false,
			destroy: true,
			ajax:urls ,

			columns: [
				{ data: 'number_key', name: 'number_key' },
				{ data: 'contract_id', name: 'contract_id' },
				{ data: 'contract_document_id', name: 'contract_document_id' },
				{ data: 'contract_title', name: 'contract_title' },
				{ data: 'api_key', name: 'api_key' },
				{ data: 'api_secret', name: 'api_secret'},
				{ data: 'updated_at', name: 'updated_at' },
				{ data: 'id', name: 'id' ,
					render: function (data, type, column, meta) {
						return '<a  href="#" data-id="'+column.id+'" class="button btn-wh   edit_contracttemplates_request"><img src="'+base_url+'/admin_assets/images/edit.png" title="edit" class="white-img"></a>'+
	 					'<button class="button btn-wh   btn-delete" data-url="'+base_url+'/admin/contracttemplates/delete/'+column.id+'"><img src="'+base_url+'/admin_assets/images/delete.png" title="delete" class="white-img"></button>';
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
		  text: "Are you sure you want to delete office and related office assets?",
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

	var data = jQuery(this).parents('form:first').serialize();
	$('.error').text('');;
	$('.error').removeClass('text-danger');
	$.ajax({
		url: base_url + '/admin/contracttemplates/store',
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



$(document).on("click", ".edit_acontracttemplates", function(e) {
	e.preventDefault();

	var data = jQuery(this).parents('form:first').serialize();
	var id = $(this).data('id');
	$.ajax({
		url: base_url + '/admin/contracttemplates/update/'+id,
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

	var aurls = base_url + "/admin/contracttemplates/edit_contracttemplates/" + id;
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
				get_api_provider();

			}
		},
	});
});


get_api_provider();
function get_api_provider(){
$('.api_type').on('change', function(event) {

	event.preventDefault();
	var api_type = jQuery(this).val();

            if(api_type){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url: base_url + "/admin/contracttemplates/get_api_provider_list/"+api_type,
                   success:function(res)
                   {

                        if(res.data)
                        {
                            $(".api_provider").empty();
                            $(".api_provider").append("<option value=''>-- Select Provider -- </option>");

                            $.each(res.data,function(key,value){
                                $(".api_provider").append("<option value="+key+">"+value+"</option>");
                            });
                        }
                   }

                });
            } else {
                $(".api_provider").empty();
                $(".api_provider").append("<option value=''>Select Provider</option>");
            }
});
}

 </script>
@endpush
