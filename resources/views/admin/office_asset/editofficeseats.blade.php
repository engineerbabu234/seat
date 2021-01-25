<form action="#" enctype="multipart/form-data" method="post" id="edit-office-asset-seat-form">
	@csrf
	<div class="row">

		<input type="hidden" name="building_id" value="{{ $officeseat->building_id }}">
		<input type="hidden" name="office_asset_id" id="seat_asset_id" value="{{ $officeseat->office_asset_id }}">
		<input type="hidden" name="office_id" value="{{$officeseat->office_id }}">
		<input type="hidden" id="seat_id" value="{{$officeseat->seat_no }}">
		<div class="col-sm-4">
			<div class="form-group">
				<span class="iconWrap iconSize_32 seat_no_header" data-content="Seat no" title="Seat no" id="seat_no_header" data-content="Seat no"  data-trigger="hover"  data-placement="left"><img src="{{asset('admin_assets')}}/images/seat-no.png" class="icon bl-icon" width="25" ></span> <span class="text-danger">*</span>
				<input type="number" value="{{ $officeseat->seat_no }}" disabled class="form-control" placeholder="Seat no."  required>
				<span class="error" id="edit_seat_no_error"></span>
			</div>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<span  class="iconWrap iconSize_32" data-content="Booking Mode" title="Booking Mode"  data-trigger="hover"  data-content="Booking Mode" data-placement="left"><img src="{{asset('admin_assets')}}/images/booking-mode.png" class="icon bl-icon" width="25" ></span>  <span class="text-danger">*</span>
				<select name="booking_mode" id="booking_mode" class="form-control" required>
					  <option  @if($officeseat->booking_mode == 2) {{'selected'}} @endif  value="2">Auto Accept</option>
					 <option  @if($officeseat->booking_mode == 1) {{'selected'}} @endif value="1">Manual</option>
					  </select>
				<span class="error" id="edit_booking_mode_error"></span>
			</div>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				<span  class="iconWrap iconSize_32" data-content="Seat Type" title="Seat Type"  data-trigger="hover"  data-placement="left"><img src="{{asset('admin_assets')}}/images/types-seats.png" class="icon bl-icon" width="25" ></span> <span class="text-danger">*</span>
				<select name="seat_type" id="seat_type" class="form-control seat_type_change" required>
				 	 <option  @if($officeseat->seat_type == 1) {{'selected'}} @endif value="1">Unblocked</option>
					 <option @if($officeseat->seat_type == 2) {{'selected'}} @endif value="2">Blocked</option>
					  </select>
				<span class="error" id="edit_seat_type_error"></span>
			</div>
		</div>

		<div class="col-sm-4">
			<div class="form-group">
				 <span class="iconWrap iconSize_32" data-content="Show user details" title="Show user details"  data-trigger="hover"  data-placement="left"><img src="{{asset('admin_assets')}}/images/user-details.png" class="icon bl-icon" width="25" ></span> <span class="text-danger">*</span>
				<select name="is_show_user_details" id="is_show_user_details" class="form-control" required>
					  <option  @if($officeseat->is_show_user_details == 0) {{'selected'}} @endif value="0">Hide</option>
					 <option  @if($officeseat->is_show_user_details == 1) {{'selected'}} @endif value="1">Show</option>
					  </select>
				<span class="error" id="edit_is_show_user_details_error"></span>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="form-group">
				<span class="iconWrap iconSize_32" data-content="Description" title="Description"  data-trigger="hover"  data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="22" ></span>   <span class="text-danger">*</span>
				<textarea rows="2" class="form-control"  placeholder="Write here..." name="description" required>{{ $officeseat->description }}</textarea>
				<span class="error" id="edit_description_error"></span>
			</div>
		</div>

		<div class="col-sm-6 deskattributes">
			<h5>Desk Attributes</h5>
			<div class="form-check">
			  <input class="form-check-input" type="checkbox"   @if($officeseat->monitor == 1) {{'checked'}} @endif name="monitor" id="monitor">
			  <label class="form-check-label" for="monitor">
			    <span class="iconWrap iconSize_32" data-content="Monitor" title="Monitor" data-trigger="hover"  data-placement="top"  ><img src="{{asset('admin_assets')}}/images/monitor.png"  alt="Monitor" class="icon bl-icon" width="30" ></span>
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="checkbox"   @if($officeseat->dokingstation == 1) {{'checked'}} @endif name="dokingstation" id="dokingstation">
			  <label class="form-check-label" for="dokingstation">
			     <span class="iconWrap iconSize_32" data-content="Doking station" title="Doking station" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/dokingstation.png"  alt="Doking station" class="icon bl-icon" width="30" ></span>
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="checkbox"  @if($officeseat->adjustableheight == 1) {{'checked'}} @endif name="adjustableheight" id="adjustableheight">
			  <label class="form-check-label" for="adjustableheight">
			    <span class="iconWrap iconSize_32" data-content="Adjustable high" title="Adjustable high" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/adjustableheight.png"  alt="Adjustable high" class="icon bl-icon" width="30" ></span>
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="checkbox"  @if($officeseat->privatespace == 1) {{'checked'}} @endif name="privatespace" id="privatespace">
			  <label class="form-check-label" for="privatespace">
			     <span class="iconWrap iconSize_32" data-content="Private space" title="Private space" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/privatespace.png" alt="Private space"  class="icon bl-icon" width="30" ></span>
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="checkbox" @if($officeseat->wheelchair == 1) {{'checked'}} @endif name="wheelchair" id="wheelchair">
			  <label class="form-check-label" for="wheelchair">
			     <span class="iconWrap iconSize_32" data-content="Wheelchair" title="Wheelchair" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/wheelchair.png"  title="Wheelchair" class="icon bl-icon" width="30" ></span>
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="checkbox"  name="usbcharger"  @if($officeseat->usbcharger == 1) {{'checked'}} @endif id="usbcharger">
			  <label class="form-check-label" for="usbcharger">
			      <span class="iconWrap iconSize_32" data-content="USB charger" title="USB charger" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/usbcharger.png"  alt="USB charger" class="icon bl-icon" width="30" ></span>
			  </label>
			</div>
		</div>

		<div class="col-sm-6 deskattributes">
			<h5>Privacy</h5>
			<div class="form-check">
		          <input class="form-check-input" type="radio"  @if($officeseat->privacy == 1) {{'checked'}} @endif  name="privacy" id="privacy1" value="1">
		          <label class="form-check-label" for="privacy1">
		             <div class="level-status low-level"  title="Low privacy" data-trigger="hover"  data-placement="top">
						<span></span>
						<span></span>
						<span></span>
					</div>
		          </label>
		        </div>
		        <div class="form-check  ">
		          <input class="form-check-input" type="radio"  @if($officeseat->privacy == 2) {{'checked'}} @endif name="privacy" id="privacy2" value="2"  >
		          <label class="form-check-label" for="privacy2">
		              <div class="level-status mid-level" title="Medium privacy" data-trigger="hover"  data-placement="top">
						<span></span>
						<span></span>
						<span></span>
						</div>
		          </label>
		        </div>
		          <div class="form-check  ">
		          <input class="form-check-input" type="radio" name="privacy" id="privacy3"  @if($officeseat->privacy == 3) {{'checked'}} @endif  value="3"  >
		          <label class="form-check-label" for="privacy3">
		              <div class="level-status"  title="High privacy" data-trigger="hover"  data-placement="top" >
						<span></span>
						<span></span>
						<span></span>
					</div>
		          </label>
		        </div>

		</div>


		<div class="col-sm-6 carparkspace">
			<h5>Carpark Space</h5>
			<div class="form-check">
				<input class="form-check-input" type="checkbox" @if($officeseat->underground == 1) {{'checked'}} @endif  name="underground" id="underground">
				<label class="form-check-label" for="underground">
					<span class="iconWrap iconSize_32" data-content="Underground" title="Underground Parking" data-trigger="hover"  data-placement="top"  ><img src="{{asset('admin_assets')}}/images/underground.png"  alt="Underground Parking" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox" @if($officeseat->pole_information == 1) {{'checked'}} @endif  name="pole_information" id="pole_information">
				<label class="form-check-label" for="pole_information">
					<span class="iconWrap iconSize_32" data-content="Pole information"  title="Pole information" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/pole_information.png"  alt="Pole information" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  @if($officeseat->wheelchair_accessable == 1) {{'checked'}} @endif  name="wheelchair_accessable" id="wheelchair_accessable">
				<label class="form-check-label" for="wheelchair_accessable">
					<span class="iconWrap iconSize_32" data-content="Wheelchair"  title="Wheelchair accessable" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/wheelchair_accessable.png"  alt="Wheelchair accessable" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
		</div>
		<div class="col-sm-6 carparkspace">
			<h5>Parking difficulty</h5>
			<div class="form-check">
				<input class="form-check-input" type="radio" value="1"  @if($officeseat->parking_difficulty == 1) {{'checked'}} @endif @if($officeseat->parking_difficulty == 0) {{'checked'}} @endif  name="parking_difficulty" id="difficulty1"  >
				<label class="form-check-label" for="difficulty1">
					<div class="level-status low-level"  title="Low difficulty" data-trigger="hover"  data-placement="top">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</label>
			</div>
			<div class="form-check  ">
				<input class="form-check-input" type="radio"  @if($officeseat->parking_difficulty == 2) {{'checked'}} @endif  name="parking_difficulty" id="difficulty2" value="2"  >
				<label class="form-check-label" for="difficulty2">
					<div class="level-status mid-level" title="Medium difficulty" data-trigger="hover"  data-placement="top">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</label>
			</div>
			<div class="form-check  ">
				<input class="form-check-input" type="radio" @if($officeseat->parking_difficulty == 3) {{'checked'}} @endif  name="parking_difficulty" id="difficulty3" value="3"  >
				<label class="form-check-label" for="difficulty3">
					<div class="level-status"  title="High difficulty" data-trigger="hover"  data-placement="top" >
						<span></span>
						<span></span>
						<span></span>
					</div>
				</label>
			</div>
		</div>
		<div class="col-sm-6 meetingspace">
			<h5>Meeting space</h5>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  @if($officeseat->whiteboard_avaialble == 1) {{'checked'}} @endif  name="whiteboard_avaialble" id="whiteboard_avaialble">
				<label class="form-check-label" for="whiteboard_avaialble">
					<span class="iconWrap iconSize_32" data-content="Whiteboard"  title="Whiteboard avaialble" data-trigger="hover"  data-placement="top"  ><img src="{{asset('admin_assets')}}/images/whiteboard.png"  alt="Whiteboard avaialble" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox" @if($officeseat->teleconference_screen == 1) {{'checked'}} @endif   name="teleconference_screen" id="teleconference_screen">
				<label class="form-check-label" for="teleconference_screen">
					<span class="iconWrap iconSize_32" data-content="Teleconference screen" title="Teleconference screen" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/teleconference_screen.png"  alt="Teleconference screen" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox" @if($officeseat->is_white_board_interactive == 1) {{'checked'}} @endif  name="is_white_board_interactive" id="is_white_board_interactive">
				<label class="form-check-label" for="is_white_board_interactive">
					<span class="iconWrap iconSize_32" data-content="Whiteboard interactive"  title="Whiteboard interactive" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard_interactive.png"  alt="Whiteboard interactive" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check ">
				<input class="form-check-input telephone" type="checkbox" @if($officeseat->telephone == 1) {{'checked'}} @endif name="telephone" id="telephone">
				<label class="form-check-label" for="telephone">
					<span class="iconWrap iconSize_32" data-content="Telephone" title="Telephone" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/telephone.png"  alt="Telephone" class="icon bl-icon" width="30" ></span>
				</label>
			</div>


			<div class="input-group  telephone_no" @if($officeseat->telephone == 0) {{'style=display:none'}}  @endif >
	            <div class="input-group-prepend">
	                <span class="input-group-text" id="basic-addon1" ><span class="iconWrap iconSize_24 iconSize_32" data-trigger="hover" title="Telephone Number" data-placement="top" data-content="Telephone Number" ><img src="{{asset('admin_assets')}}/images/telephone_number.png" class="icon bl-icon" width="25"  ></span></span>
	            </div>
	            <input type="text" name="telephone_number" id="telephone_number" min="1" class="form-control" value="{{ isset($officeseat->telephone_number) ? $officeseat->telephone_number : '' }}" placeholder="Telephone Number">
	        </div>

			<div class="input-group pt-2 pb-1">
	            <div class="input-group-prepend">
	                <span class="input-group-text" id="basic-addon1" ><span class="iconWrap iconSize_24 iconSize_32" data-trigger="hover" title="Number Of Spare power sockets" data-placement="top" data-content="Number Of Spare power sockets" ><img src="{{asset('admin_assets')}}/images/number_of_spare_power_sockets.png" class="icon bl-icon" width="25" ></span></span>
	            </div>
	            <input type="number" name="number_of_spare_power_sockets" min="1" id="number_of_spare_power_sockets" class="form-control" value="{{ isset($officeseat->number_of_spare_power_sockets) ? $officeseat->number_of_spare_power_sockets : '' }}" placeholder="Number Of Spare power sockets">
	        </div>

			<div class="form-check">
				<input class="form-check-input" type="checkbox" @if($officeseat->meeting_indicator_mounted_on_wall == 1) {{'checked'}} @endif   name="meeting_indicator_mounted_on_wall" id="meeting_indicator_mounted_on_wall">
				<label class="form-check-label" for="meeting_indicator_mounted_on_wall">
					<span class="iconWrap iconSize_32" data-content="Meeting indicator" title="Meeting indicator mounted on wall" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/meeting_indicator_mounted_on_wall.png"  alt="Meeting indicator mounted on wall" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
		</div>
		<div class="col-sm-6 collaboration_space">
			<h5>Collaboration space</h5>
			<div class="form-check">
				<input class="form-check-input" type="checkbox" @if($officeseat->kanban_board == 1) {{'checked'}} @endif  name="kanban_board" id="kanban_board">
				<label class="form-check-label" for="kanban_board">
					<span class="iconWrap iconSize_32" data-content="Kanban board" title="Kanban board" data-trigger="hover"  data-placement="top"  ><img src="{{asset('admin_assets')}}/images/kanban_board.png"  alt="Kanban board" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox" @if($officeseat->whiteboard == 1) {{'checked'}} @endif  name="whiteboard" id="whiteboard">
				<label class="form-check-label" for="whiteboard">
					<span class="iconWrap iconSize_32" data-content="Whiteboard" title="Whiteboard" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard.png"  alt="Whiteboard" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox" @if($officeseat->interactive_whiteboard == 1) {{'checked'}} @endif name="interactive_whiteboard" id="interactive_whiteboard">
				<label class="form-check-label" for="interactive_whiteboard">
					<span class="iconWrap iconSize_32" data-content="Interactive Whiteboard "  title="Interactive Whiteboard " data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard_interactive.png"  alt="Interactive Whiteboard " class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  @if($officeseat->standing_only == 1) {{'checked'}} @endif  name="standing_only" id="standing_only">
				<label class="form-check-label" for="standing_only">
					<span class="iconWrap iconSize_32" data-content="Standing only" title="Standing only" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/standing_only.png"  alt="Standing only" class="icon bl-icon" width="30" ></span>
				</label>
			</div>

			<div class="form-check">
				<input class="form-check-input" type="checkbox" @if($officeseat->telecomference_screen == 1) {{'checked'}} @endif  name="telecomference_screen" id="telecomference_screen">
				<label class="form-check-label" for="telecomference_screen">
					<span class="iconWrap iconSize_32" data-content="Telecomference screen" title="Telecomference screen" data-trigger="hover"  data-placement="top"><img src="{{asset('admin_assets')}}/images/teleconference_screen.png"  alt="Telecomference screen" class="bl-icon" width="30" ></span>
				</label>
			</div>
		</div>

		<div class="col-sm-12">
			<div class="add-product-btn text-center">
				<button data-id="{{ $officeseat->seat_id }}" dot-id="{{ $officeseat->dots_id }}" class="btn btn-info edit-booking-seat">Save</button>
			</div>
		</div>
	</div>

</form>
