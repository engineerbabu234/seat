
												<div class="form-group row">
													<label for="building_id" class="col-sm-5 col-form-label ">Building <span class="text-danger">*</span></label>
													<div class="col-sm-7">
														<select class="form-control building" name="building_id" id="building_id">
															<option value="">Select Building</option>
															@foreach($Building as $key => $bvalue)
															<option value="{{$bvalue->building_id}}">{{$bvalue->building_name}}</option>
															@endforeach
														</select>
														<span class="error" id="building_id_error"></span>
													</div>
												</div>
												<div class="form-group row">
													<label for="office_id" class="col-sm-5 col-form-label">Office <span class="text-danger">*</span></label>
													<div class="col-sm-7">
														<select class="form-control office" name="office_id" id="office_id"><option value="">Select Office</option></select>
														<span class="error" id="office_id_error"></span>
													</div>
												</div>
												<div class="form-group row">
													<label for="office_asset_id" class="col-sm-5 col-form-label">Office Assets <span class="text-danger">*</span></label>
													<div class="col-sm-7">
														<select class="form-control office_assets" name="office_asset_id" id="office_asset_id"><option value="">Select Office Assets</option>
													</select>
													<span class="error" id="office_asset_id_error"></span>
												</div>
											</div>
											<div class="form-group row">
												<label for="seat_id" class="col-sm-5 col-form-label">Seat Number <span class="text-danger">*</span></label>
												<div class="col-sm-7">
													<select class="form-control seats" name="seat_id" id="seat_id">
														<option value="">Select Seat</option>
													</select>
													<span class="error" id="seat_id_error"></span>
												</div>
											</div>
