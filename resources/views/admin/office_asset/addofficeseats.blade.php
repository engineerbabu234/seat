<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-seat-form">
	@csrf
	<div class="row">
		<input type="hidden" name="seat_assets_id" id="seat_assets_id">
		<input type="hidden" name="seat_building_id" id="seat_building_id">
		<div class="col-sm-4">
			<div class="form-group">
				 <span class="iconWrap iconSize_32 seat_no_header" data-content="Seat no" title="Seat no" id="seat_no_header"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/seat-no.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span>
				<input type="number" class="form-control" placeholder="Seat no."  min="1" name="seat_no" id="seat_no" required>
				<span class="error" id="seat_no_error"></span>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				 <span title="Booking Mode" class="iconWrap iconSize_32" data-content="Booking Mode"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/booking-mode.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span>
				<select name="booking_mode" id="booking_mode" class="form-control" required>
					<option value="2">Auto Accept</option>
					<option value="1">Manual</option>
				</select>
				<span class="error" id="booking_mode_error"></span>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				 <span  class="iconWrap iconSize_32" data-content="Seat Type"  title="Seat Type"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/types-seats.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span>
				<select name="seat_type" id="seat_type" class="form-control" required>
					<option value="1">Unblocked</option>
					<option value="2">Blocked</option>
				</select>
				<span class="error" id="seat_type_error"></span>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				 <span class="iconWrap iconSize_32" data-content="Show user details" title="Show user details"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/user-details.png" class="icon bl-icon" width="30" ></span>   <span class="text-danger">*</span>
				<select name="is_show_user_details" id="is_show_user_details" class="form-control" required>
					<option value="1">Show</option>
					<option value="0">Hide</option>
				</select>
				<span class="error" id="is_show_user_details_error"></span>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="form-group">
				 <span class="iconWrap iconSize_32" data-content="Show user details" title="Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="22" ></span>
				<textarea rows="2" class="form-control"  placeholder="Write here..." name="description" required></textarea>
				<span class="error" id="description_error"></span>
			</div>
		</div>
		<div class="col-sm-6 deskattributes">
			<h5>Desk Attributes</h5>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"   name="monitor" id="monitor">
				<label class="form-check-label" for="monitor">
					<span class="iconWrap iconSize_32" data-content="Monitor" title="Monitor"  data-trigger="hover" data-placement="top"  ><img src="{{asset('admin_assets')}}/images/monitor.png"  alt="Monitor" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="dokingstation" id="dokingstation">
				<label class="form-check-label" for="dokingstation">
					<span class="iconWrap iconSize_32" data-content="Doking station" title="Doking station" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/dokingstation.png"  alt="Doking station" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="adjustableheight" id="adjustableheight">
				<label class="form-check-label" for="adjustableheight">
					<span class="iconWrap iconSize_32" data-content="Adjustable high" title="Adjustable high" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/adjustableheight.png"  alt="Adjustable high" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="privatespace" id="privatespace">
				<label class="form-check-label" for="privatespace">
					<span class="iconWrap iconSize_32" data-content="Private space"  title="Private space" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/privatespace.png" alt="Private space"  class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="wheelchair" id="wheelchair">
				<label class="form-check-label" for="wheelchair">
					<span class="iconWrap iconSize_32" data-content="Wheelchair" title="Wheelchair" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/wheelchair.png"  title="Wheelchair" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="usbcharger" id="usbcharger">
				<label class="form-check-label" for="usbcharger">
					<span class="iconWrap iconSize_32" data-content="USB charger" title="USB charger" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/usbcharger.png"  alt="USB charger" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
		</div>
		<div class="col-sm-6 deskattributes">
			<h5>Privacy</h5>
			<div class="form-check">
				<input class="form-check-input" type="radio" checked name="privacy" id="privacy1" value="1">
				<label class="form-check-label" for="privacy1">
					<div class="level-status low-level"  title="Low privacy" data-trigger="hover" data-placement="top">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</label>
			</div>
			<div class="form-check  ">
				<input class="form-check-input" type="radio" name="privacy" id="privacy2" value="2"  >
				<label class="form-check-label" for="privacy2">
					<div class="level-status mid-level" title="Medium privacy" data-trigger="hover" data-placement="top">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</label>
			</div>
			<div class="form-check  ">
				<input class="form-check-input" type="radio" name="privacy" id="privacy3" value="3"  >
				<label class="form-check-label" for="privacy3">
					<div class="level-status"  title="High privacy" data-trigger="hover" data-placement="top" >
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
				<input class="form-check-input" type="checkbox"   name="underground" id="underground">
				<label class="form-check-label" for="underground">
					<span class="iconWrap iconSize_32" data-content="Underground Parking" title="Underground Parking" data-trigger="hover" data-placement="top"  ><img src="{{asset('admin_assets')}}/images/underground.png"  alt="Underground Parking" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="pole_information" id="pole_information">
				<label class="form-check-label" for="pole_information">
					<span class="iconWrap iconSize_32" data-content="Pole information" title="Pole information" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/pole_information.png"  alt="Pole information" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="wheelchair_accessable" id="wheelchair_accessable">
				<label class="form-check-label" for="wheelchair_accessable">
					<span class="iconWrap iconSize_32" data-content="Wheelchair accessable" title="Wheelchair accessable" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/wheelchair_accessable.png"  alt="Wheelchair accessable" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
		</div>
		<div class="col-sm-6 carparkspace">
			<h5>Parking difficulty</h5>
			<div class="form-check">
				<input class="form-check-input" type="radio" checked name="parking_difficulty" id="difficulty1" value="1">
				<label class="form-check-label" for="difficulty1">
					<div class="level-status low-level"  title="Low difficulty" data-trigger="hover" data-placement="top">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</label>
			</div>
			<div class="form-check  ">
				<input class="form-check-input" type="radio" name="parking_difficulty" id="difficulty2" value="2"  >
				<label class="form-check-label" for="difficulty2">
					<div class="level-status mid-level" title="Medium difficulty" data-trigger="hover" data-placement="top">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</label>
			</div>
			<div class="form-check  ">
				<input class="form-check-input" type="radio" name="parking_difficulty" id="difficulty3" value="3"  >
				<label class="form-check-label" for="difficulty3">
					<div class="level-status"  title="High difficulty" data-trigger="hover" data-placement="top" >
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
				<input class="form-check-input" type="checkbox"   name="whiteboard_avaialble" id="whiteboard_avaialble">
				<label class="form-check-label" for="whiteboard_avaialble">
					<span class="iconWrap iconSize_32" data-content="Whiteboard avaialble" title="Whiteboard avaialble" data-trigger="hover" data-placement="top"  ><img src="{{asset('admin_assets')}}/images/whiteboard.png"  alt="Whiteboard avaialble" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="teleconference_screen" id="teleconference_screen">
				<label class="form-check-label" for="teleconference_screen">
					<span class="iconWrap iconSize_32" data-content="Teleconference screen" title="Teleconference screen" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/teleconference_screen.png"  alt="Teleconference screen" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="is_white_board_interactive" id="is_white_board_interactive">
				<label class="form-check-label" for="is_white_board_interactive">
					<span class="iconWrap iconSize_32" data-content="Whiteboard interactive" title="Whiteboard interactive" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard_interactive.png"  alt="Whiteboard interactive" class="bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check ">
				<input class="form-check-input telephone" type="checkbox"  name="telephone" id="telephone">
				<label class="form-check-label" for="telephone">
					<span class="iconWrap iconSize_32" data-content="Telephone" title="Telephone" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/telephone.png"  alt="Telephone" class="icon bl-icon" width="30" ></span>
				</label>
			</div>


			<div class="input-group telephone_no">
	            <div class="input-group-prepend">
	                <span class="input-group-text" id="basic-addon1" ><span class="iconWrap iconSize_24 iconSize_32" data-trigger="hover" title="Telephone Number" data-placement="top" data-content="Telephone Number" ><img src="{{asset('admin_assets')}}/images/telephone_number.png" class="icon bl-icon" width="25" ></span></span>
	            </div>
	            <input type="text" name="telephone_number" id="telephone_number" min="1" class="form-control" placeholder="Telephone Number">
	        </div>

			<div class="input-group pt-2 pb-1">
	            <div class="input-group-prepend">
	                <span class="input-group-text" id="basic-addon1" ><span class="iconWrap iconSize_24 iconSize_32" data-trigger="hover" title="Number Of Spare power sockets" data-placement="top" data-content="Number Of Spare power sockets" ><img src="{{asset('admin_assets')}}/images/number_of_spare_power_sockets.png" class="icon bl-icon" width="25" ></span></span>
	            </div>
	            <input type="number" name="number_of_spare_power_sockets" min="1" id="number_of_spare_power_sockets" class="form-control" placeholder="Number Of Spare power sockets">
	        </div>

			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="meeting_indicator_mounted_on_wall" id="meeting_indicator_mounted_on_wall">
				<label class="form-check-label" for="meeting_indicator_mounted_on_wall">
					<span class="iconWrap iconSize_32" data-content="Meeting indicator" title="Meeting indicator mounted on wall" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/meeting_indicator_mounted_on_wall.png"  alt="Meeting indicator mounted on wall" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
		</div>
		<div class="col-sm-6 collaboration_space">
			<h5>Collaboration space</h5>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"   name="kanban_board" id="kanban_board">
				<label class="form-check-label" for="kanban_board">
					<span class="iconWrap iconSize_32" data-content="Kanban boar" title="Kanban board" data-trigger="hover" data-placement="top"  ><img src="{{asset('admin_assets')}}/images/kanban_board.png"  alt="Kanban board" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="whiteboard" id="whiteboard">
				<label class="form-check-label" for="whiteboard">
					<span class="iconWrap iconSize_32" data-content="Whiteboard" title="Whiteboard" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard.png"  alt="Whiteboard" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="interactive_whiteboard" id="interactive_whiteboard">
				<label class="form-check-label" for="interactive_whiteboard">
					<span class="iconWrap iconSize_32" data-content="Interactive Whiteboard" title="Interactive Whiteboard " data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard_interactive.png"  alt="Interactive Whiteboard " class="icon bl-icon" width="30" ></span>
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="standing_only" id="standing_only">
				<label class="form-check-label" for="standing_only">
					<span class="iconWrap iconSize_32" data-content="Standing only" title="Standing only" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/standing_only.png"  alt="Standing only" class="icon bl-icon" width="30" ></span>
				</label>
			</div>

			<div class="form-check">
				<input class="form-check-input" type="checkbox"  name="telecomference_screen" id="telecomference_screen">
				<label class="form-check-label" for="telecomference_screen">
					<span class="iconWrap iconSize_32" data-content="Telecomference screen" title="Telecomference screen" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/teleconference_screen.png"  alt="Telecomference screen" class="icon bl-icon" width="30" ></span>
				</label>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="add-product-btn text-center">
				<button class="  btn btn-info add-booking-seat">Save</button>
			</div>
		</div>
	</div>
</form>
