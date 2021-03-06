 	<form method="POST" id="edit-question-form" action="#">
				@csrf

				<div class="add-question">
					<div class="row">
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title">Quesionaire<span class="text-danger">*</span></h6>
							<select id="quesionaire_id" name="quesionaire_id"    class="form-control"  >
								@if($questionarie->isEmpty())
								<option value="">Record Not Found</option>
							@else
								@foreach($questionarie as $key => $value)
								    @if($key == 0)
								     <option value="">-- Select Quesionaire --</option>
									@endif
									<option @if($question->id == $value->id) {{'selected'}} @endif value="{{$value->id}}"  >{{$value->title}}</option>
								@endforeach
							@endif
							</select>
							 <span class="error" id="edit_quesionaire_id_error"></span>
						</div>
						</div>
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title">Quetion<span class="text-danger">*</span></h6>
							<input type="text" class="form-control" value="{{ $question->question }}" placeholder="Quetion" name="question" required>
							 <span class="error" id="question_error"></span>
						</div>
						</div>
						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title">Correct Answer</h6>
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
