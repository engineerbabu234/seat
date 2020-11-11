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
				<div class="col-md-2 col-sm-2 col-xs-12">

					<div class="btns">
						<a href="#" class="add-asset btn btn-info question_logic_modal" title="Add Question Logic"  >Add Logic</a>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12">
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




<div class="modal" id="question_logic_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Question Logic</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="question_logic_info">

      </div>
    </div>
  </div>
</div>



@endsection
@push('css')
<style type="text/css">
	#choices {
    min-width: 200px;
    min-height: 60px;
}
.choice {
    float: left;
    border: 2px solid gray;
    margin: 5px;
    padding: 5px;
    cursor: pointer;
}
.questionContainer, .answerContainer {
    border: 2px solid gray;
    float: left;
    margin: 5px;
    width: 400px;
    height: 80px;
    padding: 10px;
}
.answerContainer {
    border-style: dashed;
}
.clearfix {
    clear: both;
}
</style>
@endpush
@push('js')
  <script type="text/javascript" src="{{asset('public')}}/js/sweetalert.min.js"></script>
   <script src="{{asset('admin_assets/')}}/js/jquery-ui.js"></script>
 <script type="text/javascript">

		 urls = base_url+'/admin/question/';

 		var laravel_datatable =$('#laravel_datatable').DataTable({
			processing: true,
			serverSide: true,
			"ordering": false,
			destroy: true,
			ajax:urls ,

			columns: [
				{ data: 'id', name: 'id' },
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



$(document).on("click", ".question_logic_modal", function(e) {
	e.preventDefault();
	var id = $(this).data('id');

	var aurls = base_url + "/admin/question/question_logic/";
	jQuery.ajax({
		url: aurls,
		type: 'get',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(response) {

			if (response.success) {
				$('#question_logic_info').html(response.html);
				draganddrop();
				$('#question_logic_modal').modal('show');

			}
		},
	});
});

// add logic for questins
function draganddrop(){
 $(function() {

  var draggableOptions = {
    appendTo: "body",
    helper: "clone",
    cursor: 'move'
  };

  var draggableOptions1 = {
    appendTo: "body",
    helper: "clone",
    cursor: 'copy'

  };

  $('.choice').draggable(draggableOptions);
  $('.choicelogic').draggable(draggableOptions1);

  $('#choices')
    .sortable()
    .droppable({
      activeClass: 'ui-state-default',
      hoverClass: 'ui-state-hover',
       accept: '.choice, .choicelogic',
      drop: function(evt, ui) {
        $('<div></div>')
          .addClass('choice')
          .text(ui.draggable.text())
          .draggable(draggableOptions)
          .appendTo(this);
        //$(ui.draggable).hide();
      }
    });


  $('.answerContainer').droppable({
    activeClass: 'ui-state-default',
    hoverClass: 'ui-state-hover',
    //accept: ":not(.ui-sortable-helper)",
    drop: function(event, ui) {
      //$(this).find(".placeholder").remove();


      $('<div></div>')
        .addClass('choice')
        .text(ui.draggable.text())
        .attr('data-id',ui.draggable.attr("id"))
        .draggable(draggableOptions)
        .appendTo(this);
      //$(ui.draggable).hide();

    }
  });


});
}



$(document).on("click", ".save_question_logic", function(e) {
	e.preventDefault();
	var logic_data = [];
	$('.choice').each(function(n) {
		if($(this).attr('data-id')){
	  		logic_data[n] = $(this).attr('data-id');
		}
	});

	console.log(logic_data);

	$.ajax({
		url: base_url + '/admin/question/save_question_logic',
		type: 'post',
		dataType: 'json',
		data: {'logic':logic_data},
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
				$("form#question-logic")[0].reset();
				swal("Success!", response.message, "success");
				$('.error').removeClass('text-danger');
				$('#question_logic_modal').modal('hide');
			}
		},
	});
});

 </script>
@endpush
