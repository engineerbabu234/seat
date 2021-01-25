@if($modal_type == 'booking_failed')
<h4 class="card-title ">Booking Failed</h4>
 <p class="card-text "> Failed to book a seat you already have a seat booked the same day.</p>
 <h6 class="card-sub-title">What's next?</h6>
@if($reservation_date == date('Y-m-d'))
<p class="card-text" >Reservation ID : {{ $reservation_id }} already made for today, cannot cancel this reservation
@else
 <p class="card-text" > Browse to your <a class="label label-info" href="{{ url('reservation') }}">Reservation history</a> page and Cancel reservation ID: {{ $reservation_id }}, due to COVID we cannot do that.</p>
@endif

@endif

@if($modal_type == 'booking_success')
<h4 class="card-title">Booking Success</h4>
 <p class="card-text">Your booking has been automatically approved by your admin, Your reservation ID  {{ $reservation_id }} is Check your <a class="label label-info" href="{{ url('reservation') }}">booking history</a> for reference </p>
 <h6 class="card-sub-title">What's next?</h6><p class="card-text"> Nothing, you are good to go!  </p>
@endif


@if($modal_type == 'booking_order_success')
<h4 class="card-title">Booking Success</h4>
 <p class="card-text">Your booking has been sent to your admin for approval. Your reservation ID is {{ $reservation_id }} . Check your <a class="label label-info" href="{{ url('reservation') }}">booking history</a> for reference. </p>
 <h6 class="card-sub-title">What's next?</h6><p class="card-text"> Your admin has been notified and approve/reject your request  </p>
@endif

@if($modal_type == 'exam_failed')
<h4 class="card-title">Booking Failed</h4>
 <p class="card-text  "> Failed to book a seat as one or more questionaries attached to this seat have failed/Expired </p>
 <h6 class="card-sub-title">What's next?</h6><p class="card-text"> Please visit your <a class="label label-info" href="{{ url('question_history') }}">Questionary History</a> to resolve  </p>
@endif

@if($modal_type == 'morethen14_days')
<h4 class="card-title ">Booking Failed</h4>
 <p class="card-text "> You Can not Book After {{$days}} day from now </p>
@endif


@if($modal_type == 'booking_not_saved')
<h4 class="card-title  ">Booking Failed</h4>
 <p class="card-text "> Failed to book a seat </p>
 <h6 class="card-sub-title">What's next?</h6><p class="card-text"> Please visit your Questionary History to resolve  </p>
@endif


@if($modal_type == 'deleted_failed')
<h4 class="card-title  ">Delete Failed</h4>
 <p class="card-text ">Failed to delete this Questionnaire as one or more office assets are still associated to the questionnaire</p>
 <h6 class="card-sub-title">What's next?</h6><p class="card-text"> Browse all you office assets and remove the questionnaire from all assets effected Continue  </p>
@endif



@if($modal_type == 'cancel_failed')
<h4 class="card-title text-danger">Cancellation Failed </h4>
 <p class="card-text "> You cannot Cancel a booking booked for today due to COVID restrictions </p>
 <h6 class="card-sub-title">What's next?</h6><p class="card-text"> Nothing, you cannot reserve another seat today unfortunately  </p>
@endif


@if($modal_type == 'delete_questions')
<h4 class="card-title text-warning">Remove Questionary and Questions</h4>
 <p class="card-text "> Are you sure you want to remove the questionary and its associated questions? </p>
   <button type="button" class="btn btn-warning" class="close" data-dismiss="modal" aria-label="Close">Cancel</button>
    <button type="button" data-id="{{$id}}" class="btn btn-warning remove_questions" data-dismiss="modal" aria-label="Close">Ok</button>
@endif



@if($modal_type == 'seat_block_notification')
<h4 class="card-title text-warning">Cancel All Reservations?</h4>
 <p class="card-text "> There are active reservations under this seat and all reservations will be cancelled , are you sure ? </p>
   <button type="button" class="btn btn-warning cancel_change_block" >Cancel</button>
    <button type="button" data-seatid="{{$seat_id}}" data-assets="{{$assets_id}}" class="btn btn-warning cancel_seat_reservations" data-dismiss="modal" aria-label="Close">Ok</button>
@endif

@if($modal_type == 'seat_request_error')
<h4 class="card-title text-danger">{{$title}}</h4>
 <p class="card-text "> {{$message}} </p>
 <h6 class="card-sub-title">What's next?</h6><p class="card-text"> {{$what_is_next}} </p><br>
@endif

@if($modal_type == 'seat_request_success')
<h4 class="card-title text-success">{{$title}}</h4>
 <p class="card-text "> {{$message}} </p>
 <h6 class="card-sub-title">What's next?</h6><p class="card-text"> {{$what_is_next}}  </p><br>
@endif


@if($modal_type == 'seat_request_warning')
<h4 class="card-title text-warning">{{$title}}</h4>
 <p class="card-text"> {{$message}} </p>
 <h6 class="card-sub-title">What's next?</h6><p class="card-text"> {{$what_is_next}}  </p><br>
@endif
