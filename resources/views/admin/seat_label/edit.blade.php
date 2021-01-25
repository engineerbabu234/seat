<form method="POST" id="edit-building-form"  action="#">
				@csrf
				<div class="add-office">
					<div class="row">
						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title">Building Name <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Building Name" name="building_name" value="{{ $building->building_name }}">
							 <span class="error" id="edit_building_name_error"></span>
						</div>
						</div>
						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title">Building Address <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Building Address" name="building_address" value="{{ $building->building_address }}">
							 <span class="error" id="edit_building_address_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title">Description <span class="text-danger">*</span></h6>
							<textarea rows="4" class="form-control" placeholder="Write here..." name="description">{{ $building->description }}</textarea>
							 <span class="error" id="edit_description_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button data-id="{{ $building->building_id }}" class="btn btn-info edit_building" type="submit"> Update Bulding</button>
						</div>
					 </div>
					 </div>

				</div>
			</form>
