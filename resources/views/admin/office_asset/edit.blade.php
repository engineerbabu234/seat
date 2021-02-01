	<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-form">
					@csrf

					<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							 <span class="iconWrap iconSize_32"  title="Assets Title" data-content="Assets Title"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/title.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span>
							<input type="text" class="form-control" placeholder="Assets Title" name="title" value="{{ $officeAsset->title }}" required>
							 <span class="error" id="edit_title_error"></span>
						</div>
				</div>
					<div class="col-sm-4">
					<input type="hidden" name="id"  value="{{ $officeAsset->id }}">
					<div class="form-group">
						 <span class="iconWrap iconSize_32" data-content="Building" title="Building"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span>
						<select class="form-control bindOffice" name="building_id" id="edit_building_id" required>
							@if($buildings->isEmpty())
								<option value="">Record Not Found</option>
							@else
								@foreach($buildings as $key => $value)
								    @if($key == 0)
								     <option value="">-- Select building--</option>
									@endif
									<option value="{{$value->building_id}}" @if($officeAsset->building_id == $value->building_id) {{'selected'}} @endif>{{$value->building_name}}</option>
								@endforeach
							@endif
						</select>
						 <span class="error" id="edit_building_id_error"></span>
					</div>
				</div>
				<div class="col-sm-4">
					<!--single-entry-->
					<div class="single-entry">
						<div class="form-group">
							   <span  class="iconWrap iconSize_32" data-content="Office"  title="Office"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/offices.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span>
							  <select class="form-control OfficeData" name="office_id" id="edit_office_id" required>
								<option value="">-- Select Office -- </option>
								@foreach($Office as $key => $value)
									<option value="{{$value->office_id}}" @if($officeAsset->office_id == $value->office_id) {{'selected'}} @endif>{{$value->office_name}}</option>
								@endforeach
							  </select>
							  <span class="error" id="edit_office_id_error"></span>
						</div>
				</div>
				</div>

				<div class="col-sm-6">
						<div class="form-group">
							 <span class="iconWrap iconSize_32"  data-content="Description"  title="Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span>
							<textarea rows="4"  class="form-control" placeholder="Write here..." name="description">{{ $officeAsset->description }}</textarea>
							 <span class="error" id="edit_description_error"></span>
						</div>
						<div class="form-group">
							 <span class="iconWrap iconSize_32"  data-content="Office Assets Type" title="Office Assets Type"    data-trigger="hover" id="assets_type_edit" data-placement="left"><img src="{{asset('admin_assets')}}/images/objects.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span>
							  <select class="form-control" name="asset_type"  id="asset_type">
							  	<option @if($officeAsset->asset_type == 1) {{'selected'}} @endif value="1">Desks</option>
							  	<option @if($officeAsset->asset_type == 2) {{'selected'}} @endif  value="2">Carpark Spaces</option>
							  	<option @if($officeAsset->asset_type == 3) {{'selected'}} @endif value="3">Colobration Spaces</option>
							  	<option @if($officeAsset->asset_type == 4) {{'selected'}} @endif value="4">Meeting room spaces</option>
							  </select>
							  <span class="error" id="asset_type_error"></span>
						</div>
				</div>
				<div class="col-sm-6">
						<div class="form-group">
							 <span class="iconWrap iconSize_32" data-content="Preview Image" title=" Preview Image"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/preview-image.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span>
							<input type="file" required id="preview_image" name="preview_image" class="form-control dropify-event" data-default-file="{{ $assets_image }}" /><br>
                             <span class="error" id="edit_preview_image_error"></span>
						</div>
					</div>

				<div class="col-sm-6 align-items-center">
									<div class="form-group  ">
										<h6 class="sub-title">Billing Management
										<label><input type="radio" name="billing_managment" id="billing_managment0" @if($officeAsset->billing_managment == 0) {{'checked'}} @endif   value="0" >No</label>
										<label><input type="radio" name="billing_managment" id="billing_managment1" @if($officeAsset->billing_managment == 1) {{'checked'}} @endif  value="1" >Yes</label>
										</h6>
									</div>

								</div>
								<div class="col-sm-6 daily_cost" @if($officeAsset->billing_managment == 0){{'style=display:none'}} @else {{'style=display:block'}} @endif>
									<div class="form-group">
										 <span class="iconWrap iconSize_32" title="daily cost"  data-trigger="hover" data-content="daily cost" data-placement="left"><img src="{{asset('admin_assets')}}/images/euro.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span>
										<select class="form-control" name="daily_cost" id="daily_cost" required>
											@for($i=5;$i<=50;$i++)
											<option @if($officeAsset->daily_cost == $i){{'selected'}}  @endif value="{{$i}}">	&#128; {{$i}}</option>
											@endfor

										</select>
										<span class="error" id="edit_daily_cost_error"></span>
									</div>


								</div>


				<div class="col-sm-12">
					 <div class="card">
      					<div class="card-body">
						<div class="form-group  ">
							<h6 class="sub-title">Check in Management :
								   <label><input type="radio" name="checkin" value="0" @if($officeAsset->checkin == 0) {{'checked'}} @endif >No</label>
							 	  <label><input type="radio"  data-id="{{ $officeAsset->id }}" name="checkin" value="1" @if($officeAsset->checkin == 1) {{'checked'}} @endif>Yes</label>
							 </h6>

							 <div id="slider3" class="slider checktime" data-trigger="hover" @if($officeAsset->checkin == 0){{'style=display:none'}}  @endif></div>

							 <span class="error pt-1" id="edit_checkin_start_time_error"></span>

							 <span class="error pt-1" id="edit_checkin_end_time_error"></span>

							  <span class="error pt-1" id="edit_checkout_start_time_error"></span>

							   <span class="error pt-1" id="edit_checkout_end_time_error"></span>

						</div>

						<div class="col-sm-6  pt-2 checkin_methods">
			           <div class="form-group ">
			            <h6 class="sub-title">Check-in Methods </h6>
				             <div class="form-check">
				              <label class="form-check-label" for="nfc">
				              <input class="form-check-input" type="checkbox" @if($officeAsset->nfc == 1) {{'checked'}} @endif value="1"  name="nfc" id="nfc">
				              NFC
				              </label>
				            </div>

				             <div class="form-check">
				              <label class="form-check-label" for="qr">
				              <input class="form-check-input" type="checkbox" @if($officeAsset->qr == 1) {{'checked'}} @endif  name="qr" id="qr">
				              QR
				              </label>
				            </div>

				             <div class="form-check">
				              <label class="form-check-label" for="browser">
				              <input class="form-check-input" type="checkbox"  @if($officeAsset->browser == 1) {{'checked'}} @endif name="browser" id="browser">
				              Browser
				              </label>
				            </div>

				             <div class="form-check">
				              <label class="form-check-label" for="token">
				              <input class="form-check-input" type="checkbox"  @if($officeAsset->token == 1) {{'checked'}} @endif name="token" id="token">
				              Token
				              </label>
				            </div>

				            <div class="form-check">
				              <label class="form-check-label" for="presence">
				              <input class="form-check-input" type="checkbox" @if($officeAsset->presence == 1) {{'checked'}} @endif  name="presence" id="presence">
				              Presence
				              </label>
				            </div>
			          </div>

			          	<div class="form-group">
				             <div class="form-check">
				              <label class="form-check-label" for="register_noshow">
				              <input class="form-check-input" type="checkbox"  @if($officeAsset->register_noshow == 1) {{'checked'}} @endif   name="register_noshow" id="register_noshow">
				               Register No Show
				              </label>
				            </div>
			          	</div>

			         </div>

					</div>
				</div>
					</div>
					<hr>
					<div class="col-sm-12 pt-2 cleaning_management" @if($officeAsset->asset_type == 2) {{'style=display:none'}} @endif>
						<div class="card">
      					<div class="card-body">
						<div class="form-group">
							<h6 class="sub-title">Cleaning Management :
			                <label><input type="radio" name="seat_clean" id="seat_clean0" @if($officeAsset->seat_clean == 0) {{'checked'}} @endif value="0" >No</label>
			                <label><input type="radio"  data-id="{{ $officeAsset->id }}" name="seat_clean" id="seat_clean1" @if($officeAsset->seat_clean == 1) {{'checked'}} @endif value="1" >Yes</label>
			             	</h6>

			             	<div id="slider4" class="slider cleantime" data-trigger="hover" @if($officeAsset->seat_clean == 0){{'style=display:none'}}  @endif></div>

			             	 <span class="error" id="edit_cleanstart_time_error"></span>

			             	 <span class="error" id="edit_cleanend_time_error"></span>
						</div>

			          </div>
			      </div>
					</div>
					<hr>
								<div class="col-sm-12 pt-2 conference_management" @if($officeAsset->asset_type == 3 or $officeAsset->asset_type == 4) {{'style=display:block'}} @else {{'style=display:none'}}  @endif  >
									<div class="card">
										<div class="card-body">
											<div class="row">
													<div class="col-sm-12 d-flex align-items-center">
												<div class="form-group">
													<h6 class="sub-title">Conference Management :
													<label><input type="radio" name="conference_management" id="conference_management0" value="0" @if($officeAsset->conference_management == 0) {{'checked'}} @endif  >No</label>
													<label><input type="radio" name="conference_management" id="conference_management1" @if($officeAsset->conference_management == 1) {{'checked'}} @endif  value="1"  >Yes</label>
													</h6>
												</div>
											</div>
											</div>
											<div class="row   conferance"  @if($officeAsset->conference_management == 0) {{'style=display:none'}} @endif>
											<div class="col-sm-4  d-flex align-items-center">
												<div class="form-group">
													<label>Conference EndPoint</label>
													<select class="form-control" name="conference_endpoint" id="conference_endpoint">
														@if($apiconnections->isEmpty())
													<option value="">Record Not Found</option>
													@else
													@foreach($apiconnections as $key => $value)
													@if($key == 0)
													<option value="">-- Select Api Connection--</option>
													@endif
													<option @if($officeAsset->conference_endpoint == $value->id) {{'selected'}} @endif  value="{{$value->id}}">{{$value->api_title}}</option>
													@endforeach
													@endif
													</select>
													<span class="error" id="edit_conference_endpoint_error"></span>
												</div>
											</div>

											<div class="col-sm-4  d-flex align-items-center">
												<div class="form-group">
													<h6 class="sub-title" >Teleconferance Name </h6>
													<input type="text" class="form-control" placeholder="Teleconferance Name " value="{{$officeAsset->teleconferance_name}}" name="teleconferance_name"  >
													<span class="error" id="edit_teleconferance_name_error"></span>
												</div>
											</div>
											<div class="col-sm-4 d-flex align-items-center">
												<div class="form-group  ">
													<div class="form-check">
														<label class="form-check-label" for="email_user_link">
															<input class="form-check-input" type="checkbox"   name="email_user_link" value="{{$officeAsset->email_user_link}}" id="email_user_link">
															Email User link
														</label>
													</div>
												</div>
											</div>
										</div>

										</div>
									</div>
								</div>
					<div class="col-sm-6  pt-2">
						<div class="form-group">
							<div class="form-check">
							   <label class="form-check-label" for="auto_realese">
							  <input class="form-check-input" type="checkbox"   @if($officeAsset->auto_realese == 1) {{'checked'}} @endif   name="auto_realese" id="auto_realese">
							   Auto Release
							  </label>
							</div>
						</div>
						<div class="form-group">
			               <label class="form-check-label" for="book_within"> Can Book Within </label>
			               <select class="form-control" name="book_within" id="book_within">
				                @for($i=1;$i<=60;$i++)
				                <option value="{{ $i }}"  @if($officeAsset->book_within == $i) {{'selected'}} @endif>{{$i}} Days</option>
				                @endfor
			               </select>
				        </div>

				        <div class="form-group">
							<div class="form-check">
							  <input class="form-check-input" type="checkbox"   @if($officeAsset->auto_book == 1) {{'checked'}} @endif   name="auto_book" id="auto_book">
							   <label class="form-check-label" for="auto_book">
							   Auto Book
							  </label>
							</div>
						</div>
					</div>




					<div class="col-sm-12">
						<div class="edit-product-btn text-center">
							<button data-id="{{ $officeAsset->id }}" class="edit-office-btn btn btn-info"> Update Office Asset</button>
						</div>
					</div>

				</div>
		    </form>
