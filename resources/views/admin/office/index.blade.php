@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<div class="title">
						<h2>Office List</h2>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_office"><i class="fas fa-plus"></i></a>

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
							<th>Office ID.</th>
						    <th>Building</th>
							<th>Office No.</th>
							<th>Office Name</th>
							<th>Total Office assets</th>
							<th>Date/Time </th>
							<th nowrap>Action</th>
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
							<h6 class="sub-title">Building <span class="text-danger">*</span></h6>
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
							<h6 class="sub-title">Office Name <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Office Name" name="office_name" required>
							 <span class="error" id="office_name_error"></span>
						</div>
						</div>
						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title">Office Number <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Office Number" name="office_number" required>
							 <span class="error" id="office_number_error"></span>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title">Description </h6>
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
				{ data: 'office_id', name: 'office_id' },
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
				{ data: 'created_at', name: 'created_at' },
				{ data: 'office_id', name: 'office_id' ,
					render: function (data, type, column, meta) {
						return '<a  href="#" data-id="'+column.office_id+'" class="button accept edit_office_request">Edit</a>'+
	 					'<button class="button reject btn-delete" data-url="'+base_url+'/admin/office/delete/'+column.office_id+'">Delete</button>';
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
					    var redrawtable = jQuery('#laravel_datatable').dataTable();
						redrawtable.fnDraw();
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
				swal("Success!", response.message, "success");
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
				swal("Success!", response.message, "success");
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
