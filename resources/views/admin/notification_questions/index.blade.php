@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->

		<div class="header">
					<div class="title">
			<div class="row align-items-center">
				<div class="col-md-6 col-sm-6 col-xs-6">
						<h2>Notification Questions List</h2>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_notification_question"><i class="fas fa-plus"></i></a>
					</div>
					</div>
				</div>
			</div>
		</div><!--END header-->

		<div class="custom-data-table">
			<div class="data-table">

		<div class="custom-table-height">
			<div class="table-responsive">
				<table class="table table-striped text-center" id="laravel_datatable">
					<thead>
						<tr>
							<th class="text-left"><span class="iconWrap iconSize_32" title="ID."  data-trigger="hover" data-content="Id" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="25" ></span> </th>
						    <th><span class="iconWrap iconSize_32" title="Notification Question" data-content="Notification Question" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/question.png" class="icon bl-icon" width="30" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Description" data-content="Description" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="User Type" data-content="User Type" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/users.png" class="icon bl-icon" width="25" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Start Date" data-content="Start Date" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="25" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="End Date" data-content="End Date" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="25" ></span> </th>

							<th><span class="iconWrap iconSize_32" title="Repeat" data-content="Repeat" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/expire_after.png" class="icon bl-icon" width="25" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Answer Type" data-content="Answer Type" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/answer.png" class="icon bl-icon" width="25" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Update Date" data-content="Update Date"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="25" ></span>  </th>

							<th nowrap><span class="iconWrap iconSize_32" title="Action" data-content="Action" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="25" ></span> </th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
		</div>
	</div>
</div>




<!-- The Modal -->
<div class="modal" id="add_notification_question">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Notification Questions</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" id="add-notification_question-form" action="#">
				@csrf
				<div class="add-notification_question">
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
												<option value="{{$value->id}}">{{ ucfirst($value->title) }}</option>
											@endforeach
										@endif
								</select>
								 <span class="error" id="category_id_error"></span>
							</div>
						</div>

						<div class="col-sm-8">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Notification Question"  data-trigger="hover"  data-content="Notification Question" data-placement="left"><img src="{{asset('admin_assets')}}/images/question.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Add Notification Question" id="question" name="question" required>
								 <span class="error" id="question_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Notification User Type"  data-trigger="hover"  data-content="Notification User Type" data-placement="left"><img src="{{asset('admin_assets')}}/images/users.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								 <select class="form-control" name="user_type" id="user_type">
								 	<option value="">-- Select User Type --</option>
								 	<option value="2">User</option>
								 	<option value="3">Cleaner</option>
								</select>
								 <span class="error" id="user_type_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Notification Question Answer Type"  data-trigger="hover"  data-content="Notification Question Answer Type" data-placement="left"><img src="{{asset('admin_assets')}}/images/answer.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<select class="form-control ans_type" name="ans_type" id="ans_type">

								 	@if(isset($anstype))

								 				<option value="">-- Select Question Answer Type --</option>
											@foreach($anstype as $key => $value)
												<option value="{{$key}}"  >{{$value}}</option>
											@endforeach

									@endif

								</select>
								 <span class="error" id="ans_type_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Repeat Reminder"  data-trigger="hover"  data-content="Repeat Reminder" data-placement="left"><img src="{{asset('admin_assets')}}/images/repeat.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								 <select class="form-control" name="repeat" id="repeat">
								 	<option value="">-- Select Repeat Reminder --</option>
								 	<option value="1">Yes</option>
								 	<option value="0">No</option>
								</select>
								 <span class="error" id="reminder_error"></span>
							</div>
						</div>

						<div class="col-sm-12 view_answare">
							<div class="admore-fields">
							</div>
							<a href="#" class="fieldsaddmore-addbtn btn btn-sm btn-info">Add more</a>

						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Notification Start Date"  data-trigger="hover"  data-content="Notification Start Date" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<input type="date" class="form-control" placeholder="Notification Question" id="start_date" name="start_date" required>
								 <span class="error" id="start_date_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group repeat_options">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Notification End Date"  data-trigger="hover"  data-content="Notification End Date" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<input type="date" class="form-control" placeholder="Notification End Date" id="end_date" name="end_date" required>
								<span class="error" id="end_date_error"></span>
							</div>
						</div>

						<div class="col-sm-4 ">
							<div class="form-group repeat_options">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Repeat Options" data-content="Repeat Options" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/expire_after.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>

										<select class="form-control" name="repeat_value" id="repeat_value">
									        <option value="day">Daily</option>
									        <option value="week">Weekly</option>
									        <option value="month">Monthly</option>
									    </select>

								 <span class="error" id="expired_option_error"></span>
							</div>
						</div>



						<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Description" data-content="Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span> </h6>
								 <textarea class="form-control " name="description" id="description" rows="2"></textarea>
								 <span class="error" id="description_error"></span>
							</div>
						</div>

						<div class="col-sm-12">
							<div class="add-product-btn text-center">
								<button class="btn btn-info add_notification_question_data" type="submit"> Add Notification Question</button>
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


<div class="modal" id="edit_notification_question">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"  id="edit_notification_question_info">

    </div>
  </div>
</div>

<!-- Addmore template -->
<script id="fieldsaddmore-template" type="text/template">
	<div class="fieldsaddmore-row rowId">
	    <input type="text" class="form-control" name="answer[key]" />
	    <a href="#" data-rowid="key" class="fieldsaddmore-removebtn text-danger">Remove</a>
	</div>
	<span class="error" id="answer.key_error"></span>
</script>

@endsection
@push('css')
<style type="text/css">
	.close_new{
	    background-color: transparent;
	    border: 0;
	    font-size: 26px;
	}
</style>

@endpush
@push('js')

  <script src="{{asset('admin_assets/')}}/js/jqery.fieldsaddmore.min.js"></script>
 <script type="text/javascript" src="{{URL::asset('admin_assets/pages')}}/notification_questions/index.js"></script>

@endpush
