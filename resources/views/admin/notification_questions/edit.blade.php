 	<form method="POST" id="edit-notification_questions-form" action="#">
				@csrf
				<div class="modal-header">
		        <h4 class="modal-title">Edit Notification Question</h4>

		        <button type="button" class="close_new close_edit_notification_questions"  >&times;</button>
		      </div>
		 	<div class="modal-body" >
				<div class="edit-notification_question">
					<div class="row">

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Notification Category"  data-trigger="hover" data-content="Notification Category" data-placement="left"><img src="{{asset('admin_assets')}}/images/category.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<select class="form-control" name="category_id" id="category_id">
									@if($notification_category->isEmpty())
										<option value="">Record Not Found</option>
										@else
											@foreach($notification_category as $key => $value)
												@if($key == 0)
												<option value="">-- Select Notification Category --</option>
												@endif
												<option @if($notification_questions->category_id == $value->id) {{'selected'}} @endif value="{{$value->id}}">{{ ucfirst($value->title) }}</option>
											@endforeach
										@endif
								</select>
								 <span class="error" id="edit_category_id_error"></span>
							</div>
						</div>

						<div class="col-sm-8">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Notification Question"  data-trigger="hover"  data-content="Notification Question" data-placement="left"><img src="{{asset('admin_assets')}}/images/question.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Add Notification Question" id="question" value="{{$notification_questions->question}}"  name="question" required>
								 <span class="error" id="edit_question_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Notification User Type"  data-trigger="hover"  data-content="Notification User Type" data-placement="left"><img src="{{asset('admin_assets')}}/images/users.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								 <select class="form-control" name="user_type" id="user_type">
								 	<option value="">-- Select User Type --</option>
								 	<option @if($notification_questions->user_type == 2) {{'selected'}} @endif  value="2">User</option>
								 	<option @if($notification_questions->user_type == 3) {{'selected'}} @endif value="3">Cleaner</option>
								</select>
								 <span class="error" id="edit_user_type_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Notification Question Answer Type"  data-trigger="hover"  data-content="Notification Question Answer Type" data-placement="left"><img src="{{asset('admin_assets')}}/images/answer.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<select class="form-control ans_type" name="ans_type" id="ans_type">
									<option value="">-- Select Question Answer Type --</option>
									@foreach($anstype as $key => $value)
										<option  @if($notification_questions->ans_type ==$key) {{'selected'}} @endif  value="{{$key}}"  >{{$value}}</option>
									@endforeach
								</select>
								 <span class="error" id="edit_ans_type_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Repeat Reminder"  data-trigger="hover"  data-content="Repeat Reminder" data-placement="left"><img src="{{asset('admin_assets')}}/images/repeat.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								 <select class="form-control" name="repeat" id="repeat">
								 	<option value="">-- Select Repeat Reminder --</option>
								 	<option value="1" @if($notification_questions->repeat ==1) {{'selected'}} @endif>Yes</option>
								 	<option value="0"  @if($notification_questions->repeat ==0) {{'selected'}} @endif>No</option>
								</select>
								 <span class="error" id="edit_reminder_error"></span>
							</div>
						</div>

						<div class="col-sm-12 view_answare">
							  <div class="admore-fields">
				               @if (!empty($answers))
				                   @foreach ($answers as $key => $row)
				                        <div class="fieldsaddmore-row rowId-{{$key}}">
				                            <input type="text" class="form-control" name="answer[{{$key}}]" value="{{$row->answer}}" />
				                            <a href="#" data-rowid="{{$key}}" class="fieldsaddmore-removebtn  text-danger">Remove</a>
				                        </div>
				                   @endforeach
				               @endif
				            </div>
							<a href="#" class="fieldsaddmore-addbtn btn btn-sm btn-info  ">Add more</a>
							<span class="error" id="edit_answar_error"></span>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Notification Start Date"  data-trigger="hover"  data-content="Notification Start Date" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<input type="date" class="form-control"  id="start_date" name="start_date" value="{{  $notification_questions->start_date  }}" required>
								 <span class="error" id="edit_start_date_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group repeat_options" @if($notification_questions->repeat ==1) {{ 'style="display:block"'}} @else {{ 'style=display:none'}} @endif >
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Notification End Date"  data-trigger="hover"  data-content="Notification End Date" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<input type="date" class="form-control" id="end_date" name="end_date" value="{{  $notification_questions->end_date  }}" required>
								<span class="error" id="edit_end_date_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group repeat_options" @if($notification_questions->repeat ==1) {{'style="display:block"'}} @else {{'style=display:none'}} @endif>
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Repeat Options" data-content="Repeat Options" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/expire_after.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>

										<select class="form-control" name="repeat_value" id="repeat_value">
									        <option @if($notification_questions->repeat_value == 'day') {{'selected'}} @endif value="day">Daily</option>
									        <option  @if($notification_questions->repeat_value == 'month') {{'selected'}} @endif value="month">Monthly</option>
									        <option @if($notification_questions->repeat_value == 'week') {{'selected'}} @endif value="week">Weekly</option>
									    </select>

								 <span class="error" id="edit_repeat_value_error"></span>
							</div>
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Description" data-content="Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span> <span class="text-danger">*</span></h6>
								 <textarea class="form-control " name="description" id="description" rows="2">{{$notification_questions->description}}</textarea>
								 <span class="error" id="description_error"></span>
							</div>
						</div>


						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button   data-id="{{$notification_questions->id}}" class="btn btn-info edit_notification_question" type="submit">Save</button>
						</div>
					 </div>
					 </div>

					</div>
				</div>
			</form>
