<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-seat-form">
	@csrf
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">seat no <span class="text-danger">*</span></h6>
				<input type="number" class="form-control" placeholder="seat no." name="seat_no" id="seat_no" required>
				<span class="error" id="seat_no_error"></span>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">Booking Mode <span class="text-danger">*</span></h6>
				<select name="booking_mode" id="booking_mode" class="form-control" required>
			 		 <option value="2">Auto Accept</option>
					 <option value="1">Manual</option>
					  </select>
				<span class="error" id="booking_mode_error"></span>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">Seat Type <span class="text-danger">*</span></h6>
				<select name="seat_type" id="seat_type" class="form-control" required>
			 		 <option value="1">Unblocked</option>
					 <option value="2">Blocked</option>
					  </select>
				<span class="error" id="booking_mode_error"></span>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">Show user details <span class="text-danger">*</span></h6>
				<select name="is_show_user_details" id="is_show_user_details" class="form-control" required>
			 		 <option value="0">Hide</option>
					 <option value="1">Show</option>
					  </select>
				<span class="error" id="booking_mode_error"></span>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">Status<span class="text-danger">*</span></h6>
				<select name="status" id="status" class="form-control" required>
			 		 <option value="0">Available</option>
					 <option value="1">Book</option>
					  </select>
				<span class="error" id="status_mode_error"></span>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<h6 class="sub-title">Description  <span class="text-danger">*</span></h6>
				<textarea rows="2" class="form-control"  placeholder="Write here..." name="description" required></textarea>
				<span class="error" id="description_error"></span>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<h6 class="sub-title">Office Seat Image <span class="text-danger">*</span></h6>
				<input type="file" required id="preview_image" name="preview_image" class="form-control dropify-event" data-default-file="" /><br>
				<span class="error" id="preview_image_error"></span>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="add-product-btn text-center">
				<button class="  btn btn-info add-booking-seat"> Add Seat</button>
			</div>
		</div>
	</div>
</form>
