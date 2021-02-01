<div class="row">
  <div class="col">
     <p>Failed to book seat as one or more questionaties have failed or expired...
Please navigate to  <a class="btn btn-info" href="{{url('questionaries')}}">Questionarie History</a> to resolve the conflicts </p>
    <div class="row">
      <div class="col-5">
        <h6>Date</h6>
      </div>
      <div class="col">
        <span>{{date('d-m-Y',strtotime($booking_date))}}</span>
      </div>
    </div>
    <div class="row">
      <div class="col-5">
        <h6>Questionarie </h6>
      </div>
    </div>
  </div>

</div>
<br>
