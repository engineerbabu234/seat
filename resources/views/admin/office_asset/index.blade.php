@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<div class="title">
						<h2>Office Assets List</h2>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_asset"><i class="fas fa-plus"></i></a>
					</div>
				</div>
			</div>
			</div><!--END header-->
			<!--my tenders-->
			<div class="custom-data-table">
				<div class="data-table">
					<div class="custom-table-height">
						<div class="table-responsive">
							<table class="table table-striped text-center" id="laravel_datatable">
								<thead>
									<tr>
										<th><span  class="iconWrap iconSize_32"  title="Assets ID."  data-trigger="hover" data-content="Assets ID." data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="30" ></span> </th>
										<th><span class="iconWrap iconSize_32"  data-content="Building"  title="Building"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="icon bl-icon" width="30" ></span></th>
										<th><span class="iconWrap iconSize_32" data-content="Office Name"  title="Office Name"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/offices.png" class="icon bl-icon" width="30" ></span></th>
										<th><span class="iconWrap iconSize_32" data-content="Assets Name" title="Assets Name"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/assets.png" class="icon bl-icon" width="30" ></span> </th>
										<th><span class="iconWrap iconSize_32" data-content="Total Seats" title="Total Seats"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/seat-no.png" class="icon bl-icon" width="30" ></span>  </th>
										<th><span class="iconWrap iconSize_32"  data-content="Quesionaire"  title="Quesionaire"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/questionarie.png" class="icon bl-icon" width="30" ></span>  </th>
										<th><span class="iconWrap iconSize_32"  data-content="Document Upload"  title="Document Upload"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/document_upload.png" class="icon bl-icon" width="30" ></span>  </th>
										<th><span class="iconWrap iconSize_32" title="Office Assets Type"  title="Status"  data-trigger="hover" id="assets_type" data-placement="left"><img src="{{asset('admin_assets')}}/images/objects.png" class="icon bl-icon" width="30" ></span> </th>
										<th><span class="iconWrap iconSize_32" data-content="Update Date"  title="Update Date"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="30" ></span> </th>
										<th nowrap><span class="iconWrap iconSize_32" data-content="Action"  title="Action"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="30" ></span></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				</div><!--END my asset-->
			</div>
		</div>
		<!-- The Modal -->
		<div class="modal" id="add_asset">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header">
						<h4 class="modal-title">Add Office Asset</h4>
						<button type="button" class="close_new close_office_assets" >&times;</button>
					</div>
					<!-- Modal body -->
					<div class="modal-body">
						<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-form">
							@csrf
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										 <span  class="iconWrap iconSize_32 "   title="Assets Title" data-content="Assets Title"   data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/title.png" class="icon bl-icon"  ></span>  <span class="text-danger">*</span>
										<input type="text" class="form-control" placeholder="Assets Title" name="title" required>
										<span class="error" id="title_error"></span>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										 <span class="iconWrap iconSize_32" title="Building"  data-trigger="hover" data-content="Building" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span>
										<select class="form-control bindOffice" name="building_id" id="building_id" required>
											@if($buildings->isEmpty())
											<option value="">Record Not Found</option>
											@else
											@foreach($buildings as $key => $value)
											@if($key == 0)
											<option value="">-- Select Building--</option>
											@endif
											<option value="{{$value->building_id}}">{{$value->building_name}}</option>
											@endforeach
											@endif
										</select>
										<span class="error" id="building_id_error"></span>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										 <span class="iconWrap iconSize_32" title="Office" data-content="Office"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/offices.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span>
										<select class="form-control OfficeData" name="office_id" id="bindoffices"><option value="">-- Select Office -- </option></select>
										<span class="error" id="office_id_error"></span>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										 <span class="iconWrap iconSize_32" title="Description"  data-content="Description" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span>
										<textarea rows="4" class="form-control" placeholder="Write here..." name="description"></textarea>
										<span class="error" id="description_error"></span>
									</div>
									<div class="form-group">
										 <span class="iconWrap iconSize_32" title="Office Assets Type"  data-content="Office Assets Type"  data-trigger="hover" id="assets_type_add" data-placement="left"><img src="{{asset('admin_assets')}}/images/objects.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span>
										<select class="form-control" name="asset_type"  id="asset_type">
											<option value="1">Desks</option>
											<option value="2">Carpark Spaces</option>
											<option value="3">Colobration Spaces</option>
											<option value="4">Meeting room spaces</option>
										</select>
										<span class="error" id="asset_type_error"></span>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										 <span class="iconWrap iconSize_32" title=" Preview Image" data-content="Preview Image"    data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/preview-image.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span>
										<input type="file" required id="preview_image" name="preview_image" class="form-control dropify-event" data-default-file="" /><br>
										<span class="error" id="preview_image_error"></span>
									</div>
								</div>


								<div class="col-sm-6 align-items-center">
									<div class="form-group  ">
										<h6 class="sub-title">Billing Management
										<label><input type="radio" name="billing_managment" id="billing_managment0" checked  value="0" >No</label>
										<label><input type="radio" name="billing_managment" id="billing_managment1" value="1" >Yes</label>
										</h6>
									</div>

								</div>
								<div class="col-sm-6 daily_cost">
									<div class="form-group">
										 <span class="iconWrap iconSize_32" title="daily cost"  data-trigger="hover" data-content="daily cost" data-placement="left"><img src="{{asset('admin_assets')}}/images/euro.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span>
										<select class="form-control" name="daily_cost" id="daily_cost" required>
											@for($i=5;$i<=50;$i++)
											<option value="{{$i}}">	&#128; {{$i}}</option>
											@endfor

										</select>
										<span class="error" id="daily_cost_error"></span>
									</div>


								</div>

								<div class="col-sm-12">
									<div class="card">
										<div class="card-body">
											<div class="form-group  ">
												<h6 class="sub-title">Check in Management :
												<label><input type="radio" name="checkin" id="checkin0" value="0" >No</label>
												<label><input type="radio" name="checkin" id="checkin1" value="1" checked>Yes</label>
												</h6>
												<div id="slider1"   data-trigger="hover"  class="slider checktime"></div>
												<span class="error pt-1" id="checkin_start_time_error"></span>
												<span class="error pt-1" id="checkin_end_time_error"></span>
												<span class="error pt-1" id="checkout_start_time_error"></span>
												<span class="error pt-1" id="checkout_end_time_error"></span>
											</div>

									   <div class="col-sm-6 pt-2 checkin_methods">
										<div class="form-group ">
											<h6 class="sub-title">Check-in Methods </h6>
											<div class="form-check">
												<label class="form-check-label" for="nfc">
													<input class="form-check-input" type="checkbox"   name="nfc" id="nfc">
													NFC
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label" for="qr">
													<input class="form-check-input" type="checkbox"   name="qr" id="qr">
													QR
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label" for="browser">
													<input class="form-check-input" type="checkbox"   name="browser" id="browser">
													Browser
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label" for="token">
													<input class="form-check-input" type="checkbox"   name="token" id="token">
													Token
												</label>
											</div>
											<div class="form-check">
												<label class="form-check-label" for="presence">
													<input class="form-check-input" type="checkbox"   name="presence" id="presence">
													Presence
												</label>
											</div>
										</div>
										<div class="form-group">
											<div class="form-check">
												<label class="form-check-label" for="register_noshow">
													<input class="form-check-input" type="checkbox"   name="register_noshow" id="register_noshow">
													Register No Show
												</label>
											</div>
										</div>
									</div>
										</div>
									</div>
								</div>
								<hr>
								<div class="col-sm-12 pt-2 cleaning_management">
									<div class="card">
										<div class="card-body">
											<div class="form-group">
												<h6 class="sub-title">Cleaning Management :
												<label><input type="radio" name="seat_clean" id="seat_clean0" value="0" >No</label>
												<label><input type="radio" name="seat_clean" id="seat_clean1" value="1" checked>Yes</label>
												</h6>
												<div id="slider2" data-trigger="hover"  class="slider cleantime"></div>
												<span class="error" id="cleanstart_time_error"></span><br>
												<span class="error" id="cleanend_time_error"></span>
											</div>
											<div class="form-group cleantime">
												<div class="form-check">
													<label class="form-check-label" for="required_after_checkout">
														<input class="form-check-input" type="checkbox"   name="required_after_checkout" id="required_after_checkout">
														Require Clean After Checkout
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<hr>
								<div class="col-sm-12 pt-2 conference_management" >
									<div class="card">
										<div class="card-body">
											<div class="row">
													<div class="col-sm-12 d-flex align-items-center">
												<div class="form-group">
													<h6 class="sub-title">Conference Management :
													<label><input type="radio" name="conference_management"  id="conference_management0" value="0" checked >No</label>
													<label><input type="radio" name="conference_management" id="conference_management1" value="1" >Yes</label>
													</h6>
												</div>
											</div>
											</div>
											<div class="row conferance">
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
													<option value="{{$value->id}}">{{$value->api_title}}</option>
													@endforeach
													@endif
													</select>
												</div>
											</div>

											<div class="col-sm-4  d-flex align-items-center">
												<div class="form-group">
													<h6 class="sub-title" >Teleconferance Name </h6>
													<input type="text" class="form-control" placeholder="Teleconferance Name " name="teleconferance_name"  >
													<span class="error" id="teleconferance Name _error"></span>
												</div>
											</div>
											<div class="col-sm-4 d-flex align-items-center">
												<div class="form-group  ">
													<div class="form-check">
														<label class="form-check-label" for="email_user_link">
															<input class="form-check-input" type="checkbox"   name="email_user_link" id="email_user_link">
															Email User link
														</label>
													</div>
												</div>
											</div>
										</div>

										</div>
									</div>
								</div>
									<div class="col-sm-6 pt-2">
										<div class="form-group">
											<div class="form-check">
												<input class="form-check-input" type="checkbox"   name="auto_realese" id="auto_realese">
												<label class="form-check-label" for="auto_realese">
													Auto Release
												</label>
											</div>
										</div>
										<div class="form-group">
											<label class="form-check-label" for="book_within">
												Can Book Within
											</label>
											<select class="form-control" name="book_within" id="book_within">
												@for($i=1;$i<=60;$i++)
												<option value="{{ $i }}" @if($i == 14){{'selected'}} @endif>{{$i}} Days</option>
												@endfor
											</select>
										</div>
										<div class="form-group">
											<div class="form-check">
												<input class="form-check-input" type="checkbox"   name="auto_book" id="auto_book">
												<label class="form-check-label" for="auto_book">
													Auto Book
												</label>
											</div>
										</div>
									</div>

								</div>
								<div class="col-sm-12">
									<div class="add-product-btn text-center">
										<button class="add-office-btn btn btn-info"> Add Office Asset</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<!-- Modal footer -->
				</div>
			</div>
		</div>
		<!-- Start Edit Assets -->
		<div class="modal" id="edit_modal">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Edit Office Assets</h4>
						<button type="button" class="close_new colse_edit_modal" >&times;</button>
					</div>
					<div class="modal-body" id="edit_assets">
					</div>
				</div>
			</div>
		</div>
		<!-- End display assets -->
		<!-- Start Display assets -->
		<div class="modal" id="assets_seat_modal">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<div class="w-100">
							<div class="row align-items-center">
								<div class="col-sm-2">
									<h4 class="modal-title">Canvas Editor</h4>
								</div>
								 <div class="col-sm-2 mb-1">
				                  <button class="btn btn-success set_canvas_scale" hidden  data-toggle="modal" data-target="#set_canvas_scale_modal">Set Canvas Scale</button>
				                </div>
								<div class="col-sm-3">
									<div class="form-group row align-items-center mb-0">
										<label class="col-5 mb-0">Circle Size: </label>
										<div class="col-sm-7 ">
											<select class="form-control" name="circle_size" id="circle_size">
												<option value="1">Small</option>
												<option value="2" selected>Medium</option>
												<option value="3">Large</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-5 text-right">
									<a href="#" title="Add Seat" class="btn btn-info mr-3 assets_type_button" id="img-create" > Click To Add Seat</a>
									<button type="button" class="close_new  closeOfficeAssetModal"  >&times;</button>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-body" id="office_assets_seats">
					</div>
				</div>
			</div>
		</div>
		<div class="modal"   id="question_logic_modal">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header">
						<h4 class="modal-title">Attach Questionaire to Office Asset</h4>
						<button type="button" class="close " data-dismiss="modal">&times;</button>
					</div>
					<!-- Modal body -->
					<div class="modal-body" id="question_logic_info">
					</div>
				</div>
			</div>
		</div>

		<div class="modal" id="set_canvas_scale_modal">
			<div class="modal-dialog modal-xs">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header">
						<h4 class="modal-title">Set Canvas Scale</h4>
						<button type="button" class="close " data-dismiss="modal">&times;</button>
					</div>
					<!-- Modal body -->
					<div class="modal-body" id="set_cancas_scale_info">
						<div class="row">
							<div class="col-sm-12">
								<p>1. Click To Drew a Horizontal line.</p>
								<p>2. Enter the Lenght of the Line in cms.</p>
								<p>3. Click to Drew a Vertical line.</p>
								<p>4. Enter the Lenght of the line in cms.</p>
							</div>
							<div class="col-sm-6">
								<img src="{{asset('admin_assets')}}/images/set_line.png" class="" width="200" >
							</div>
							<div class="col-sm-6 d-inline-flex justify-content-end align-items-end add_drewline"><button name="continue" class="btn btn-success add_line_for_seats">Continue</button></div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="modal v-h-scale" id="set_canvas_scale_modal1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header text-center">
				<h4 class="modal-title">Horizontal and Vertical scaling is now set!</h4>
						<button type="button" class="close " data-dismiss="modal">&times;</button>
					</div>
					<!-- Modal body -->
					<div class="modal-body" id="set_cancas_scale_info">
						<div class="row">
							<div class="col-sm-12 text-center">
								<p>This will be used later to aid COVID aware space management</p>
							</div>
							<div class="col-sm-12 justify-content-center d-flex add_drewline"><button name="continue" class="btn btn-success">Continue</button></div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="modal v-h-scale" id="set_canvas_scale_modal2">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header text-center">
						<h4 class="modal-title">Set your safe distance!</h4>
						<button type="button" class="close " data-dismiss="modal">&times;</button>
					</div>
					<!-- Modal body -->
					<div class="modal-body" id="set_cancas_scale_info">
						<div class="row">
							<div class="col-sm-12 text-center">
								<p>Our system will warn you visually when two objects are
closer than this limit</p>
							</div>
							<div class="safe-distance-block">
								<div class="col-sm-6">
									<img src="{{asset('admin_assets')}}/images/safe-distance.png" class="" width="200" >
								</div>
								<div class="col-sm-6 flex-wrap d-flex add_drewline">

									<div class="safe-disatnce-basge">
										<span class="badge badge-secondary">New</span>
										CM
									</div>
									<button name="continue" class="btn btn-success">Continue</button>
								</div>
							</div>

						</div>

					</div>
				</div>
			</div>
		</div>

		<div class="modal v-h-scale" id="set_vertical_line">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header text-center">
					<h4 class="modal-title">Add Vertical and Horizontal Line</h4>
						<button type="button" class="close " data-dismiss="modal">&times;</button>
					</div>
					<!-- Modal body -->
					<div class="modal-body" id="set_cancas_scale_info">
						<div class="row">
							<div class="col-sm-12 text-center">
								<p>Vertical Line size</p>
								<input type="text" name="vertical_cm" id="vertical_cm" min="10" max="200">
							</div>
							<div class="col-sm-12 text-center">
								<p>Horizontal Line size</p>
								<input type="text" name="horizontal_cm" id="horizontal_cm" min="10" max="200">
							</div>
							<div class="col-sm-12 justify-content-center d-flex"><button name="continue" class="btn btn-success">Add</button></div>
						</div>

					</div>
				</div>
			</div>
		</div>


		<div id="checkin_pophover">
			<div class="row">
				<div class="col-sm-12">
					<p> This range of time represents the time that the user can checkin to their seat.</p>
				</div>
			</div>
		</div>
		<div id="checkout_pophover">
			<div class="row">
				<div class="col-sm-12">
					<p>This range of time represents the time that the user can check out of  their seat.</p>
				</div>
			</div>
		</div>
		<div id="cleaning_pophover">
			<div class="row">
				<div class="col-sm-12">
					<p> This range of time represents the time that the cleaner is allowed to register the seats are deep cleaned.</p>
				</div>
			</div>
		</div>

		<div id="assets_type_pophover">
			<div class="row">
				<div class="col-sm-12">
					<p><span class="iconWrap iconSize_32 mr-1"  ><img title="Desks" src="{{asset('admin_assets')}}/images/desks.png"   class="icon bl-icon" width="30"  ></span><b>Desks</b>: Contains many monitors, r-mi chargers, seats and a single seat map, Booking, Checkin checkout and cleaning requests are supported </p>
					<p><span class="iconWrap iconSize_32"  ><img title="Carpark Spaces" src="{{asset('admin_assets')}}/images/carparking.png"  width="30" class="bl-icon"  ></span><b>Carpark Spaces</b>: Contains many carpark spaces and a single carpark map Booking, Checkin checkout </p>
					<p><span class="iconWrap iconSize_32"  ><img title="Collaboration Spaces" src="{{asset('admin_assets')}}/images/colobration.png"  width="30" class="bl-icon"   ></span><b>Collaboration Spaces</b>:Contains many standing spaces, many seats, many collaboration tools and also a single collaboration map Booking, Checkin checkout and cleaning requests are supported  </p>
					<p><span class="iconWrap iconSize_32"  ><img title="Meeting Room Spaces" src="{{asset('admin_assets')}}/images/meetings.png"  width="30" class="bl-icon"   ></span><b>Meeting Room Spaces</b>:Contains many seats, many meeting room tools and also a single meeting room map Booking, Checkin checkout and cleaning requests are supported </p>
				</div>
			</div>
			<!-- End display assets -->
			@endsection
			@push('css')
			<link  href="{{asset('admin_assets')}}/css/dropify.min.css" rel="stylesheet">
			<style type="text/css">
				.close_new{
				background-color: transparent;
				border: 0;
				font-size: 26px;
				}
				#choices {
			min-width: 200px;
			min-height: 60px;
			}
			.choice {
			float: left;
			border: 2px solid gray;
			margin: 5px;
			padding: 5px;
			cursor: pointer;
			}
			.questionContainer, .answerContainer {
			border: 2px solid gray;
			float: left;
			margin: 5px;
			width: 100%;
			height: 80px;
			padding: 10px;
			}
			.answerContainer {
			border-style: dashed;
			}
			.clearfix {
			clear: both;
			}
			.scrollit { height:100px;width: 100%; overflow-y:scroll;  }
			.remove_logics {
			position: inherit;
			/* top: 0px;
			right: 0px;*/
			display:block;
			box-sizing:border-box;
			width:20px;
			height:20px;
			border-width:3px;
			border-style: solid;
			border-color:red;
			border-radius:100%;
			background: -webkit-linear-gradient(-45deg, transparent 0%, transparent 46%, white 46%,  white 56%,transparent 56%, transparent 100%), -webkit-linear-gradient(45deg, transparent 0%, transparent 46%, white 46%,  white 56%,transparent 56%, transparent 100%);
			background-color:red;
			box-shadow:0px 0px 5px 2px rgba(0,0,0,0.5);
			transition: all 0.3s ease;
			}
			</style>
			<!-- fabric canvas css -->
			<link  href="{{asset('admin_assets')}}/css/seat_book/main.css" rel="stylesheet">
			<link  href="{{asset('admin_assets')}}/css/seat_book/modal.css" rel="stylesheet">
			<link  href="{{asset('admin_assets')}}/css/jquery.datetimepicker.css" rel="stylesheet">
			<link  href="{{asset('admin_assets')}}/css/slider.css" rel="stylesheet">
			<link  href="{{asset('admin_assets')}}/css/range_styles.css" rel="stylesheet">
			<!-- fabric canvas css -->
			<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
			@endpush
			@push('js')
			<script type="text/javascript" src="{{asset('admin_assets')}}/js/dropify.min.js"></script>
			<!-- fabric canvas js library -->
			<script type="text/javascript" src="{{asset('admin_assets')}}/js/seat_book/fabric/fabric.min.js"></script>
			<script src="{{asset('admin_assets')}}/js/seat_book/fabric/centering_guidelines.js"></script>
			<script type="text/javascript" src="{{asset('admin_assets')}}/js/seat_book/fabric/aligning_guidelines.js"></script>
			<script type="text/javascript" src="{{asset('admin_assets')}}/pages/office_assets/asset-canvas.js"></script>
			<script type="text/javascript" src="{{asset('admin_assets')}}/js/select2.min.js"></script>
			<script type="text/javascript" src="{{asset('admin_assets')}}/js/jquery.datetimepicker.full.js"></script>
			<script src="{{asset('admin_assets/')}}/js/jquery-ui.js"></script>
			<script src="{{asset('admin_assets/')}}/js/slider.js"></script>
			<script type="text/javascript" src="{{URL::asset('admin_assets/pages')}}/office_assets/index.js"></script>
			<script type="text/javascript">
				$(function() {
			var drEvent = $('.dropify-event').dropify();
			});
				$(document).on('change','.seat_type_change',function(){
						let seat_no = $('#seat_id').val();
						let asset_id = $('#seat_asset_id').val();
					if(this.value == '2') {
						var aurls = base_url + "/admin/office/asset/block_notification/"+asset_id+ "/"+seat_no;
						jQuery.ajax({
						url: aurls,
						type: 'get',
						dataType: 'json',
						headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						success: function(response) {
						if (response.success) {
						$('#warning_modal').show();
						$('#warning_modal_text').html(response.html);
				$('#warning_modal').modal({ backdrop: 'static', keyboard: false});
				$('.warning_modal_close').hide();
						}
						},
						});
				} else {
					$('#seat_type option[value="1"]').attr("selected", "selected");
				}
			});
				$(document).on('click','.cancel_change_block',function(){
					$('.seat_type_change').val($(".seat_type_change option:first").val());
					$(".seat_type_change").prop("selectedIndex", 0);
			$('#warning_modal').modal('hide');
			});
				$(document).on('click','.seats_cancel',function(){
			$('#changeModal').modal('hide');
			$('.error').removeClass('text-danger');
			$('.error').text('');
			});
			$(document).on('click','.colse_edit_modal',function(){
			$('#edit_modal').modal('hide');
			$('.error').removeClass('text-danger');
			$('.error').text('');
			});
			$(document).on('click','.close_office_assets',function(){
			$('#add_asset').modal('hide');
			$('.error').removeClass('text-danger');
			$('.error').text('');
			$('.dropify-preview').css('display','none');
			});
			//  $(document).on('click','#add_asset',function(){
			//     $('.dropify-preview').css('display','none');
			// });
			$(document).on('click','.seats_update_cancel',function(){
			$('#updateseatsModal').modal('hide');
			});
			$(document).on('click','.closeOfficeAssetModal',function(){
			$('#assets_seat_modal').modal('hide');
			var redrawtable = jQuery('#laravel_datatable').dataTable();
							redrawtable.fnDraw();
			});
			$('.modal').css('overflow-y', 'auto');
			$("input[name$='checkin']").click(function() {
			var checkin_value = $(this).val();
			if(checkin_value == 1){
			$(".checktime").show();
			} else {
			$(".checktime").hide();
			}
			});
			$('.timepicker').datetimepicker({
			datepicker:false,
			formatTime:"h:i A",
			step:30,
			format:"h:i A"
			});
			</script>
			@endpush
