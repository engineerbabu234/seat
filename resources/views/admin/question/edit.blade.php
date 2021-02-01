 	<form method="POST" id="edit-question-form" action="#">
				@csrf

				<div class="add-question">
					<div class="row">
							<input type="hidden" name="quesionaire_id" id="quesionaire_id" value="{{$question->quesionaire_id}}">

						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Question"  data-trigger="hover" data-content="Question" data-placement="left"><img src="{{asset('admin_assets')}}/images/question.png" class="icon bl-icon" width="25" ></span><span class="text-danger">*</span></h6>
							<input type="text" class="form-control" value="{{ $question->question }}" placeholder="Question" name="question" required>
							 <span class="error" id="question_error"></span>
						</div>
						</div>
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Correct Answer"  data-trigger="hover" data-content="Correct Answer" data-placement="left"><img src="{{asset('admin_assets')}}/images/answer.png" class="icon bl-icon" width="25" ></span></h6>
							 <select class="form-control" name="correct_answer" id="correct_answer">
							 	<option @if($question->correct_answer == 0) {{ 'selected' }} @endif value="0"  >No</option>
							 	<option @if($question->correct_answer == 1) {{ 'selected' }} @endif value="1">Yes</option>
							 </select>
							 <span class="error" id="correct_answer_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button data-id="{{$question->id}}" class="btn btn-info edit_question" type="submit"> Update Question</button>
						</div>
					 </div>
					 </div>

				</div>


			</form>
