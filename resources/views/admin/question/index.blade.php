@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<div class="title">
						<h2>Question List</h2>
					</div>
				</div>

				<div class="col-md-6 col-sm-6 col-xs-6">
					<div class="btns">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_question"><i class="fas fa-plus"></i></a>
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
							<th>Quetion ID.</th>
						    <th>Quesionaire</th>
						    <th>Question</th>
							<th>Correct Answer</th>
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
<div class="modal" id="add_question">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Quetion</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" id="add-question-form" action="#">
				@csrf
				<div class="add-question">
					<div class="row">
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title">Quesionaire<span class="text-danger">*</span></h6>
							<select id="quesionaire_id" name="quesionaire_id"  class="form-control" >
								@if($questionarie->isEmpty())
								<option value="">Record Not Found</option>
							@else
								@foreach($questionarie as $key => $value)
								    @if($key == 0)
								     <option value="">-- Select Quesionaire --</option>
									@endif
									<option value="{{$value->id}}"  >{{$value->title}}</option>
								@endforeach
							@endif
							</select>
							 <span class="error" id="quesionaire_id_error"></span>
						</div>
						</div>
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title">Quetion<span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Quetion" name="question" required>
							 <span class="error" id="question_error"></span>
						</div>
						</div>
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title">Correct Answer</h6>
							 <select class="form-control" name="correct_answer" id="correct_answer">
							 	<option value="0" selected>No</option>
							 	<option value="1">Yes</option>
							 </select>
							 <span class="error" id="correct_answer_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button class="btn btn-info add_question" type="submit"> Add Question</button>
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


<div class="modal" id="edit_question">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Question</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="edit_question_info">

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
			urls = base_url+'/admin/question/'+id;
		} else {
			urls = base_url+'/admin/question/';
		}



 		var laravel_datatable =$('#laravel_datatable').DataTable({
			processing: true,
			serverSide: true,
			"ordering": false,
			destroy: true,
			ajax:urls ,

			columns: [
				{ data: 'number_key', name: 'number_key' },
				{ data: 'quesionaire', name: 'quesionaire' },
				{ data: 'question', name: 'question' },
				{ data: 'correct_answer', name: 'correct_answer' },
				{ data: 'created_at', name: 'created_at' },
				{ data: 'id', name: 'id' ,
					render: function (data, type, column, meta) {
						return '<a  href="#" data-id="'+column.id+'" class="button accept edit_question_request">Edit</a>'+
	 					'<button class="button reject btn-delete" data-url="'+base_url+'/admin/question/delete/'+column.id+'">Delete</button>';
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
		  text: "Once deleted, you will not be able to recover this Question data!",
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



$(document).on("click", ".add_question", function(e) {
	e.preventDefault();

	var data = jQuery(this).parents('form:first').serialize();

	$.ajax({
		url: base_url + '/admin/question/store',
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
				$("form#add-question-form")[0].reset();
				swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('.error').removeClass('text-danger');
				$('#add_question').modal('hide');
			}
		},
	});
});



$(document).on("click", ".edit_question", function(e) {
	e.preventDefault();

	var data = jQuery(this).parents('form:first').serialize();
	var id = $(this).data('id');
	$.ajax({
		url: base_url + '/admin/question/update/'+id,
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
				$("form#edit-question-form")[0].reset();
				swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('.error').removeClass('text-danger');
				$('#edit_question').modal('hide');
			}
		},
	});
});


$(document).on("click", ".edit_question_request", function(e) {
	e.preventDefault();
	var id = $(this).data('id');

	var aurls = base_url + "/admin/question/edit_question/" + id;
	jQuery.ajax({
		url: aurls,
		type: 'get',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(response) {

			if (response.success) {
				$('#edit_question_info').html(response.html);

				$('#edit_question').modal('show');

			}
		},
	});
});



 </script>
@endpush
