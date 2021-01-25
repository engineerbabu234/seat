 	<form method="POST" id="edit-quesionaire-form" action="#">
 	   <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Quesionaire</h4>
							<div class="add-product-btn text-center">
								<button data-id="{{$quesionaire->id}}" class="btn btn-success edit_quesionaire" type="submit"> Save</button>

						 </div>
        <button type="button" class="close_new close_edit_quesionaire"  >&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" >



				@csrf

					<div class="edit-quesionaire">
					<div class="row">
						<input type="hidden" id="quesionaire_id" value="{{$id}}">
						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title"  ><span class="iconWrap iconSize_32" title="Title"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/title.png" class="icon bl-icon" width="40" ></span><span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Title" id="title" name="title" value="{{$quesionaire->title}}" required>
								 <span class="error" id="edit_title_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Expire After"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/expire_after.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
								<div class="row">

									<div class="col-sm-6">
											<select class="form-control" name="expired_value" id="expired_value">

												 <optgroup  id="eoption_day" @php if($quesionaire->expired_option ==  'Day'){ echo "style='display:block'"; } else { echo "style='display:none'";  } @endphp  id="option_day" label="days">

							        		 		 @for ($i = 1; $i <= 31; $i++)
								            		<option @if($quesionaire->expired_value == $i) {{'selected'}} @endif value="{{ 'Day_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>

							            	      <optgroup  id="eoption_month" @php if($quesionaire->expired_option ==  'Month'){ echo "style='display:block'"; } else { echo "style='display:none'";  } @endphp    id="option_month" label="Month">
							        		 		   @for ($i = 1; $i <= 12; $i++)
								            		<option @if($quesionaire->expired_value == $i) {{'selected'}} @endif value="{{ 'Month_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>

							            	      <optgroup id="eoption_week"  @php if($quesionaire->expired_option ==  'Week'){ echo "style='display:block'"; } else { echo "style='display:none'";  } @endphp  id="option_week" label="Week">
							        		 		 @for ($i = 1; $i <= 52; $i++)
								            		<option @if($quesionaire->expired_value == $i) {{'selected'}} @endif value="{{ 'Week_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>
							            	     <span class="error" id="edit_expired_value_error"></span>

							    		</select>
									</div>

									<div class="col-sm-6">
										<select class="form-control" name="expired_option" id="expired_option">
									        <option @if($quesionaire->expired_option ==  'Day') {{'selected'}} @endif  value="day">Day(s)</option>
									        <option  @if($quesionaire->expired_option ==  'Month') {{'selected'}} @endif value="month">Month(s)</option>
									        <option @if($quesionaire->expired_option ==  'Week') {{'selected'}} @endif value="week">Week(s)</option>
									    </select>
									</div>
								</div>
								 <span class="error" id="edit_expired_option_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Restric Seat"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/restrict-seat.png" class="icon bl-icon" width="22" ></span></h6>
							 <select class="form-control" name="restriction" id="restriction">
							 	<option @if($quesionaire->restriction == 0) {{'selected'}} @endif value="0" selected>No</option>
							 	<option @if($quesionaire->restriction == 1) {{'selected'}} @endif value="1">Yes</option>
							 </select>
							 <span class="error" id="restriction_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Description"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span> </h6>
								 <textarea class="form-control" name="description" id="description" rows="6">{{$quesionaire->description}}</textarea>
								 <span class="error" id="edit_description_error"></span>
							</div>
						</div>






					 </div>

				</div>

 				</div>
			</form>

			@include('admin.question.index')
