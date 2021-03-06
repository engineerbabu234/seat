 	<form method="POST" id="edit-office-form" action="#">
				@csrf

					<div class="row">
						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title">Building <span class="text-danger">*</span></h6>
							<select class="form-control" name="building_id" required>
							@if($buildings->isEmpty())
								<option value="">Record Not Found</option>
							@else
								@foreach($buildings as $key => $value)
								    @if($key == 0)
								     <option value="">-- Select building --</option>
									@endif
									<option value="{{$value->building_id}}" @if($office->building_id == $value->building_id) {{'selected'}} @endif>{{$value->building_name}}</option>
								@endforeach
							@endif
						</select>
						 <span class="error" id="edit_building_id_error"></span>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title">Office Name <span class="text-danger">*</span></h6>
							<input type="text" value="{{ $office->office_name }}" class="form-control" placeholder="Office Name" name="office_name" required>
							 <span class="error" id="edit_office_name_error"></span>
						</div>
						</div>
						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title">Office Number <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Office Number" name="office_number" value="{{ $office->office_number }}" required>
							 <span class="error" id="edit_office_number_error"></span>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title">Description </h6>
							<textarea rows="4" class="form-control" placeholder="Write here..." name="description">{{ $office->office_number }}</textarea>
							 <span class="error" id="edit_description_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button data-id="{{ $office->office_id }}" class="btn btn-info edit_office" type="submit"> Update Office</button>
						</div>
					 </div>
					 </div>


			</form>
