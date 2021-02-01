
<div class="main-body question_main" >
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
				<table class="table table-striped text-center" id="laravel_datatable_question">
					<thead>
						<tr>
							<th><span class="iconWrap iconSize_32" title="ID." data-content="Question id" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="25" ></span></th>
						    <th><span class="iconWrap iconSize_32" title="Question"  data-content="Question"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/question.png" class="icon bl-icon" width="25" ></span></th>
							<th><span class="iconWrap iconSize_32" title="Correct Answer"  data-content="Correct Answer" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/answer.png" class="icon bl-icon" width="25" ></span> </th>
							<th nowrap><span class="iconWrap iconSize_32" title="Action" data-content="Action" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="25" ></span> </th>
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
  <div class="modal-dialog   ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Question</h4>
        <button type="button" class="close_new close_add_questions"  >&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" id="add-question-form">
				@csrf
				<div class="add-question">
					<div class="row">
						<input type="hidden" name="quesionaire_id" id="quesionaire_id" value="{{$id}}">
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Question"  data-trigger="hover" data-content="Question" data-placement="left"><img src="{{asset('admin_assets')}}/images/question.png" class="icon bl-icon" width="25" ></span><span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Question" name="question" required>
							 <span class="error" id="question_error"></span>
						</div>
						</div>
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Correct Answer"  data-trigger="hover" data-content="Correct Answer" data-placement="left"><img src="{{asset('admin_assets')}}/images/answer.png" class="icon bl-icon" width="25" ></span></h6>
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
  <div class="modal-dialog  ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Question</h4>
        <button type="button" class="close_new close_edit_questions" >&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="edit_question_info">

      </div>
    </div>
  </div>
</div>
