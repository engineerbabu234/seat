<form action="#" enctype="multipart/form-data" method="post" id="edit-office-asset-seat-form">
	@csrf
	<div class="row">
		<input type="hidden" name="building_id" value="{{ $officeseat->building_id }}">
		<input type="hidden" name="office_asset_id" value="{{ $officeseat->office_asset_id }}">
		<input type="hidden" name="office_id" value="{{$officeseat->office_id }}">
		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">seat no <span class="text-danger">*</span></h6>
				<input type="number" value="{{ $officeseat->seat_no }}" class="form-control" placeholder="seat no." name="seat_no" id="seat_no" required>
				<span class="error" id="edit_seat_no_error"></span>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">Booking Mode <span class="text-danger">*</span></h6>
				<select name="booking_mode" id="booking_mode" class="form-control" required>
			 		 <option  @if($officeseat->booking_mode == 2) {{'selected'}} @endif  value="2">Auto Accept</option>
					 <option  @if($officeseat->booking_mode == 1) {{'selected'}} @endif value="1">Manual</option>
					  </select>
				<span class="error" id="edit_booking_mode_error"></span>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">Seat Type <span class="text-danger">*</span></h6>
				<select name="seat_type" id="seat_type" class="form-control" required>
			 		 <option  @if($officeseat->seat_type == 1) {{'selected'}} @endif value="1">Unblocked</option>
					 <option @if($officeseat->seat_type == 2) {{'selected'}} @endif value="2">Blocked</option>
					  </select>
				<span class="error" id="edit_booking_mode_error"></span>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">Show user details <span class="text-danger">*</span></h6>
				<select name="is_show_user_details" id="is_show_user_details" class="form-control" required>
			 		 <option  @if($officeseat->is_show_user_details == 0) {{'selected'}} @endif value="0">Hide</option>
					 <option  @if($officeseat->is_show_user_details == 1) {{'selected'}} @endif value="1">Show</option>
					  </select>
				<span class="error" id="edit_booking_mode_error"></span>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">Status<span class="text-danger">*</span></h6>
				<select name="status" id="status" class="form-control" required>
			 		 <option  @if($officeseat->status == 0) {{'selected'}} @endif value="0">Available</option>
					 <option @if($officeseat->status == 1) {{'selected'}} @endif value="1">Book</option>
					  </select>
				<span class="error" id="edit_status_mode_error"></span>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">Description  <span class="text-danger">*</span></h6>
				<textarea rows="2" class="form-control"  placeholder="Write here..." name="description" required>{{ $officeseat->description }}</textarea>
				<span class="error" id="edit_description_error"></span>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<h6 class="sub-title">Office Seat Image <span class="text-danger">*</span></h6>
				<input type="file" required id="preview_image" name="preview_image" class="form-control dropify-event" data-default-file="{{ $seat_image }}" /><br>
				<span class="error" id="edit_preview_image_error"></span>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="add-product-btn text-center">
				<button data-id="{{ $officeseat->seat_id }}" class="  btn btn-info edit-booking-seat"> Update Seat</button>
			</div>
		</div>
	</div>

</form>
