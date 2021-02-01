<h6 class="card-subtitle"><strong>{{ $building->building_name }} > {{ $office->office_name }} > {{ $assets->title }} > {{ $seat->seat_no }}</strong></h6>
<h5 class="card-text text-warning mt-3 mb-0"><strong>No Booking Detected, Like to Autobook before Checkin</strong></h5>
<form method="post" id="seat-booking">
	<input type="hidden" name="seat_id" id="seat_id" value="{{$seat->seat_id}}">
	<input type="hidden" name="building_id" id="building_id" value="{{$building->building_id}}">
	<input type="hidden" name="office_id" id="office_id" value="{{$office->office_id}}">
	<input type="hidden" name="assets_id" id="assets_id" value="{{$assets->id}}">
	<input type="hidden" name="seat_id" id="seat_id" value="{{$seat->seat_id}}">
<div class="col-sm-12 text-center">
	<button class="btn btn-success" id="seat_request_book_seat">Yes</button>
</div>
</form>
