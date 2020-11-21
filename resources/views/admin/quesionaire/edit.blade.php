 	<form method="POST" id="edit-quesionaire-form" action="#">
				@csrf

					<div class="edit-quesionaire">
					<div class="row">

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title">Title<span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Title" id="title" name="title" value="{{$quesionaire->title}}" required>
								 <span class="error" id="edit_title_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title">Expired Date Option<span class="text-danger">*</span></h6>
								<select class="form-control" name="expired_option" id="expired_option">
							        <option>Select Expired Date Option</option>
							        <optgroup label="Day">
							        	@for ($i = 1; $i <= 31; $i++)
							            		<option  @if($quesionaire->expired_option == 'Day') @if($quesionaire->expired_value == $i) {{'selected'}} @endif @endif  value="{{ 'Day_'.$i }}">{{ $i }}</option>

							            @endfor
							        </optgroup>
							        <optgroup label="Month">
							          @for ($i = 1; $i <= 12; $i++)

							            		<option  @if($quesionaire->expired_option == 'Month') @if($quesionaire->expired_value == $i) {{'selected'}} @endif @endif value="{{  'Month_'.$i }}">{{ $i }}</option>

							            @endfor
							        </optgroup>
							         <optgroup label="Week">
							          	@for ($i = 1; $i <= 52; $i++)

							            		<option @if($quesionaire->expired_option == 'Week') @if($quesionaire->expired_value == $i) {{'selected'}} @endif  @endif value="{{  'Week_'.$i }}">{{ $i }}</option>

							            @endfor
							        </optgroup>
							    </select>
								 <span class="error" id="edit_expired_option_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
						<div class="form-group">
							<h6 class="sub-title">Restriction</h6>
							 <select class="form-control" name="restriction" id="restriction">
							 	<option @if($quesionaire->restriction == 0) {{'selected'}} @endif value="0" selected>No</option>
							 	<option @if($quesionaire->restriction == 1) {{'selected'}} @endif value="1">Yes</option>
							 </select>
							 <span class="error" id="restriction_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title">Description<span class="text-danger">*</span></h6>
								 <textarea class="form-control" name="description" id="description" rows="6">{{$quesionaire->description}}</textarea>
								 <span class="error" id="edit_description_error"></span>
							</div>
						</div>


						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button data-id="{{$quesionaire->id}}" class="btn btn-info edit_quesionaire" type="submit"> Update Quesionaire</button>
						</div>
					 </div>
					 </div>

				</div>


			</form>
