
									<h6 class="card-subtitle">Challenge </h6>
									<form  method="post" action="" class="mt-3"><br>
										<input type="hidden" name="type" value="{{$type}}">
										<h6 class="card-subtitle"><strong>{{ $building->building_name }} > {{ $office->office_name }} > {{ $assets->title }} > {{ $seat->seat_no }}</strong></h6>
										@if(isset($reserve_seat) && $assets->checkin == 1 && (($assets->qr == 1 && $type=='qrcode') OR ($assets->nfc == 1 && $type=='nfccode') OR ($assets->browser == 1 && $type=='browser'))  && $reserve_seat->checkin == 0 &&   strtotime($assets->checkin_start_time)  <=  strtotime("now")  &&  strtotime($assets->checkin_end_time)  >=  strtotime("now"))
										<h5 class="card-text text-success mt-3 mb-0 checkintime"><strong>Please Check in</strong></h5>
										<div class="col-sm-12 text-center">
										<button  class="btn btn-success text-center" data-id="{{ $reserve_seat->reserve_seat_id }}" id="user_checkin">Check In</button>

										</div>
										@elseif(isset($reserve_seat) && $assets->checkin == 1 && $reserve_seat->checkin == 1   &&  $reserve_seat->checkout == 0 && (($assets->qr == 1 && $type=='qrcode') OR ($assets->nfc == 1 && $type=='nfccode') OR ($assets->browser == 1 && $type=='browser')) &&   strtotime($assets->checkout_start_time)  <=  strtotime("now")  &&  strtotime($assets->checkout_end_time)  >=  strtotime("now"))

										<h5 class="card-text text-success mt-3 mb-0 checkouttime"><strong>Please Check Out</strong></h5>
										<div class="col-sm-12 text-center">
										<button  class="btn btn-success" data-id="{{ $reserve_seat->reserve_seat_id }}" id="user_checkout">Check Out</button>
									</div>
										@else
										<h5 class="card-text text-warning mt-3 mb-0"><strong>No Seat Requests are available for @if(Auth::User()->role == 1)
													{{'Admin'}}
												@elseif(Auth::User()->role == 2)
													{{'User'}}
												@elseif(Auth::User()->role == 3)
													{{'Cleaner'}}
												@endif as the time is not within your allowable window</strong></h5>
										@endif
									</form>
