@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<div class="title">
						<h2>Quesionaire List</h2>
					</div>
				</div>

				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_quesionaire"><i class="fas fa-plus"></i></a>
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
							<th class="text-left">Quesionaire ID.</th>
						    <th>Title</th>
							<th>Description</th>
							<th>Expired option</th>
							<th>Expired Value</th>
							<th>Restriction</th>
							<th>Quetions</th>
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
<div class="modal" id="add_quesionaire">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Quesionaire</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" id="add-quesionaire-form" action="#">
				@csrf
				<div class="add-quesionaire">
					<div class="row">

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title">Title<span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Title" id="title" name="title" required>
								 <span class="error" id="title_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title">Expired Date Option<span class="text-danger">*</span></h6>
								<select class="form-control" name="expired_option" id="expired_option">
							        <option>Select Expired Date Option</option>
							        <optgroup label="Day">
							        	@for ($i = 1; $i <= 31; $i++)
							            		<option value="{{ 'Day_'.$i }}">{{ $i }}</option>
							            @endfor
							        </optgroup>
							        <optgroup label="Month">
							          @for ($i = 1; $i <= 12; $i++)
							            		<option value="{{  'Month_'.$i }}">{{ $i }}</option>
							            @endfor
							        </optgroup>
							         <optgroup label="Week">
							          	@for ($i = 1; $i <= 52; $i++)
							            		<option value="{{  'Week_'.$i }}">{{ $i }}</option>
							            @endfor
							        </optgroup>
							    </select>
								 <span class="error" id="expired_option_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
						<div class="form-group">
							<h6 class="sub-title">Restriction</h6>
							 <select class="form-control" name="restriction" id="restriction">
							 	<option value="0" selected>No</option>
							 	<option value="1">Yes</option>
							 </select>
							 <span class="error" id="restriction_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title">Description<span class="text-danger">*</span></h6>
								 <textarea class="form-control" name="description" id="description" rows="6"></textarea>
								 <span class="error" id="description_error"></span>
							</div>
						</div>


						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button class="btn btn-info add_quesionaire_data" type="submit"> Add Quesionaire</button>
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


<div class="modal" id="edit_quesionaire">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Quesionaire</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="edit_quesionaire_info">

      </div>
    </div>
  </div>
</div>


@endsection
@push('css')

@endpush
@push('js')
    <script src="{{asset('admin_assets/')}}/js/jquery-ui.js"></script>
 <script type="text/javascript">

		 urls = base_url+'/admin/quesionaire/';

 		var laravel_datatable =$('#laravel_datatable').DataTable({
			processing: true,
			serverSide: true,
			"ordering": false,
			destroy: true,
			ajax:urls ,

			columns: [
				{ data: 'id', name: 'id' },
				{ data: 'title', name: 'title' },
				{ data: 'description', name: 'description' },
				{ data: 'expired_option', name: 'expired_option' },
				{ data: 'expired_value', name: 'expired_value' },
				{ data: 'restriction', name: 'restriction' },
				{ data: 'id', name: 'id',
					render: function (data, type, column, meta) {
						if(column.questions > 0 ){
							return '<a href="'+base_url+'/admin/question/'+column.id+'" target="_blank" class="button accept">'+column.questions+'</a>';
						} else{
						return '<a href="#"  class="button accept">'+column.questions+'</a>';
						}
					} },
				{ data: 'id', name: 'id' ,
					render: function (data, type, column, meta) {
						return '<a  href="#" data-id="'+column.id+'" class="button accept edit_quesionaire_request">Edit</a>'+
	 					'<button class="button reject btn-delete" data-url="'+base_url+'/admin/quesionaire/delete/'+column.id+'">Delete</button>';
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
		  text: "Once deleted, you will not be able to recover this Quesionaire data!",
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



$(document).on("click", ".add_quesionaire_data", function(e) {
	e.preventDefault();

	var data = jQuery(this).parents('form:first').serialize();

	$.ajax({
		url: base_url + '/admin/quesionaire/store',
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
				$("form#add-quesionaire-form")[0].reset();
				swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('.error').removeClass('text-danger');
				$('#add_quesionaire').modal('hide');
			}
		},
	});
});



$(document).on("click", ".edit_quesionaire", function(e) {
	e.preventDefault();

	var data = jQuery(this).parents('form:first').serialize();
	var id = $(this).data('id');
	$.ajax({
		url: base_url + '/admin/quesionaire/update/'+id,
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
				$("form#edit-quesionaire-form")[0].reset();
				swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('.error').removeClass('text-danger');
				$('#edit_quesionaire').modal('hide');
			}
		},
	});
});


$(document).on("click", ".edit_quesionaire_request", function(e) {
	e.preventDefault();
	var id = $(this).data('id');

	var aurls = base_url + "/admin/quesionaire/edit_quesionaire/" + id;
	jQuery.ajax({
		url: aurls,
		type: 'get',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(response) {

			if (response.success) {
				$('#edit_quesionaire_info').html(response.html);

				$('#edit_quesionaire').modal('show');

			}
		},
	});
});


 </script>
@endpush
