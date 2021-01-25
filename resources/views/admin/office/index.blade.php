@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
					<div class="title">
			<div class="row align-items-center">
				<div class="col-md-6 col-sm-6 col-xs-6">
						<h2>Office List</h2>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_office"><i class="fas fa-plus"></i></a>

					</div>
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
							<th><span class="iconWrap iconSize_32" title=" Office ID." data-content="Office ID"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="30" ></span></th>
						    <th><span class="iconWrap iconSize_32" data-content="Building" title="Building"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="icon bl-icon" width="30" ></span> </th>
							<th><span class="iconWrap iconSize_32" data-content="Office No." title="Office No."  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/no-label.png" class="icon bl-icon" width="30" ></span>  </th>
							<th><span class="iconWrap iconSize_32" data-content="Office Name" title="Office Name"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/offices.png" class="icon bl-icon" width="30" ></span> </th>
							<th><span class="iconWrap iconSize_32" data-content="Total Office Assets" title="Total Office assets"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/total-office.png" class="icon bl-icon" width="30" ></span> </th>
							<th><span class="iconWrap iconSize_32" data-content="Update Date" title="Update Date"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="30" ></span> </th>
							<th nowrap><span class="iconWrap iconSize_32" data-content="Action" title="Action"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="30" ></span> </th>
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
<div class="modal" id="add_office">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Office</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" id="add-office-form" action="#">
				@csrf
				<div class="add-office">
					<div class="row">
						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Building"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
							<select class="form-control" name="building_id" required>
							@if($buildings->isEmpty())
								<option value="">Record Not Found</option>
							@else
								@foreach($buildings as $key => $value)
								    @if($key == 0)
								     <option value="">-- Select building --</option>
									@endif
									<option value="{{$value->building_id}}" @if(old('building')==$value->building_id) {{'selected'}} @endif>{{$value->building_name}}</option>
								@endforeach
							@endif
						</select>
						 <span class="error" id="building_id_error"></span>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Office Name"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/offices.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Office Name" name="office_name" required>
							 <span class="error" id="office_name_error"></span>
						</div>
						</div>
						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Office Number"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/no-label.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Office Number" name="office_number" required>
							 <span class="error" id="office_number_error"></span>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span>  </h6>
							<textarea rows="4" class="form-control" placeholder="Write here..." name="description">{{old('description')}}</textarea>
							 <span class="error" id="description_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button class="btn btn-info add_office" type="submit"> Add Office</button>
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


<div class="modal" id="edit_office">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Office</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="edit_office_info">

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
 		var url = window.location.pathname;
		var id = url.substring(url.lastIndexOf('/') + 1);
		if($.isNumeric(id)){
			urls = base_url+'/admin/office/'+id;
		} else {
			urls = base_url+'/admin/office/';
		}


 		var asset_datatable =$('#laravel_datatable').DataTable({
			processing: true,
			serverSide: true,
			"ordering": false,
			destroy: true,
			ajax:urls ,

			columns: [
				{ data: 'number_key', name: 'number_key' },
				{ data: 'building_name', name: 'building_name' },
				{ data: 'office_number', name: 'office_number' },
				{ data: 'office_name', name: 'office_name' },
				{ data: 'office_id', name: 'office_id',
					render: function (data, type, column, meta) {
						if(column.seats > 0 ){
							return '<a href="'+base_url+'/admin/office/asset/'+column.office_id+'" target="_blank" class="button accept">'+column.seats+'</a>';
						} else{
						return '<a href="#"  class="button accept">'+column.seats+'</a>';
						}
					} },
				{ data: 'updated_at', name: 'updated_at' },
				{ data: 'office_id', name: 'office_id' ,
					render: function (data, type, column, meta) {
						return '<a  href="#" data-id="'+column.office_id+'" class="button btn-wh   edit_office_request"><img src="'+base_url+'/admin_assets/images/edit.png"   title="edit" class="white-img"></a>'+
	 					'<button class="button btn-wh   btn-delete" data-url="'+base_url+'/admin/office/delete/'+column.office_id+'"><img src="'+base_url+'/admin_assets/images/delete.png"  title="delete" class="white-img"></button>';
					}
				}
			]
		});

 	$(function(e){



          // Get Offices
  	    var getOffices = function(){
					$.ajax(
					{
						"headers":{
						'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
					},
						'type':'get',
						'url' : "{{url('admin/office/index')}}",
					beforeSend: function() {

					},
					'success' : function(response){
              	        $('.custom-data-table').html(response);
					},
  					'error' : function(error){
					},
					complete: function() {
					},
					});
  	    }

  	  //  getOffices();


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



$(document).on("click", ".add_office", function(e) {
	e.preventDefault();

	var data = jQuery(this).parents('form:first').serialize();

	$.ajax({
		url: base_url + '/admin/office/store',
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
				$("form#add-office-form")[0].reset();
				success_alert(response.message);
				//swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('.error').removeClass('text-danger');
				$('#add_office').modal('hide');
			}
		},
	});
});



$(document).on("click", ".edit_office", function(e) {
	e.preventDefault();

	var data = jQuery(this).parents('form:first').serialize();
	var id = $(this).data('id');
	$.ajax({
		url: base_url + '/admin/office/update/'+id,
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
				$("form#edit-office-form")[0].reset();
				success_alert(response.message);
				//swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('.error').removeClass('text-danger');
				$('#edit_office').modal('hide');
			}
		},
	});
});


$(document).on("click", ".edit_office_request", function(e) {
	e.preventDefault();
	var id = $(this).data('id');

	var aurls = base_url + "/admin/office/edit_office/" + id;
	jQuery.ajax({
		url: aurls,
		type: 'get',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(response) {

			if (response.success) {
				$('#edit_office_info').html(response.html);

				$('#edit_office').modal('show');

			}
		},
	});
});
 </script>
@endpush
