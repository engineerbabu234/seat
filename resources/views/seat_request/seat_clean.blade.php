<h6 class="card-subtitle">Challenge </h6>
<form  method="post" action="" class="mt-3"><br>
	<input type="hidden" name="type" value="{{$type}}">
	<h6 class="card-subtitle"><strong>{{ $building->building_name }} > {{ $office->office_name }} > {{ $assets->title }} > {{ $seat->seat_no }}</strong></h6>
	@if(isset($reserve_seat) && $assets->seat_clean == 1 && $reserve_seat->cleaning == 0 && $reserve_seat->checkout == 1  && ( strtotime($assets->cleanstart_time)  <= strtotime("now")  &&  strtotime($assets->cleanend_time)  >= strtotime("now")) )
	<h5 class="card-text text-success mt-3 mb-0 seatcleantime"><strong>Please Clean Seat</strong></h5><br>
	<div class="form-group clean_text">
		<label>Notes for that seat clean</label>
		<textarea class="form-control" rows="5" name="cleaner_notes" id="cleaner_notes" placeholder="Enter Seat Clean notes"></textarea>
		<span class="error" id="cleaner_notes_error"></span>
	</div>
	<button  class="btn btn-info " data-id="{{ $reserve_seat->reserve_seat_id }}" id="user_seatclean">Clean Seat</button>
	@else
	<h5 class="card-text text-warning mt-3 mb-0"><strong>No Seat Requests are available for {{'Cleaner'}} 	as the time is not within your allowable window</strong></h5>
	@endif
</form>
