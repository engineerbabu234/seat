<div class="row">
  <div class="col">
    <div class="row">
      <div class="col-2">
        <h6 title="Date" data-toggle="tooltip" data-placement="top"><img src="{{asset('admin_assets')}}/images/reservation_request.png"   class="bl-icon" width="25" ></h6>
      </div>
      <div class="col">
        <span>{{ date('d-m-Y',strtotime($booking_date)) }}</span>
      </div>
    </div>
    <div class="row">
      <div class="col-2">
        <h6><span  title="Office Number" data-toggle="tooltip" data-placement="top"> <img src="{{asset('admin_assets')}}/images/number.png" alt="office number" class="bl-icon" width="20"   > </span></h6>
      </div>
      <div class="col">
        <span>{{$dots_id}}</span>
      </div>
    </div>
    <div class="row">
      <div class="col-2">
        <h6><span title="Office assets" data-toggle="tooltip" data-placement="top"><img src="{{asset('admin_assets')}}/images/assets.png" alt="office Assets" class="bl-icon" width="25" ></span></h6>
      </div>
      <div class="col">
        <span>{{$assets_name}}</span>
      </div>
    </div>
    <div class="row">
      <div class="col-2">
        <h6><span title="Office" data-toggle="tooltip" data-placement="top"><img src="{{asset('admin_assets')}}/images/offices.png" alt="office" class="bl-icon" width="25" ></span></h6>
      </div>
      <div class="col">
        <span>{{$Office->office_name}}</span>
      </div>
    </div>
    <div class="row">
      <div class="col-2">
        <h6 title="Building" data-toggle="tooltip" data-placement="top"><img src="{{asset('admin_assets')}}/images/building.png"   class="bl-icon" width="25" ></h6>
      </div>
      <div class="col">
        <span>{{$Building->building_name}}</span>
      </div>
    </div>
      <div class="row">
      <div class="col-2">
        <h6 title="Booking Mode" data-toggle="tooltip" data-placement="top"><img src="{{asset('admin_assets')}}/images/booking_mode.png"   class="bl-icon" width="25" ></h6>
      </div>
      <div class="col">
        <span>@if($booking_mode == 1){{ 'Manual'}} @else {{ 'Auto Accpted'}} @endif</span>
      </div>
    </div>

    @if($description)
    <div class="row">
      <div class="col-2">
        <h6 title="Description" data-toggle="tooltip" data-placement="top"><img src="{{asset('admin_assets')}}/images/description.png"   class="bl-icon" width="20" ></h6>
      </div>
      <div class="col">
        <span>{{ $description }} </span>
      </div>
    </div>
    @endif

  </div>
  <div class="col-4">
    @if($is_show_user_details == 1)
      @if($seat_status== 0)
           <div class="card text-white text-center" style="background-color: #5471BF;">
        @else
          <div class="card text-white bg-secondary text-center">
        @endif
      <div class="card-body">
        <div class="embed-responsive embed-responsive-1by1">
                    <div class="embed-responsive-item rounded-circle bg-light text-dark" style="background-image:url({{ $user_image }});"></div>

        </div>
           @if($seat_status == 0)
            <h5>Pending</h5>
          @else
            <h5>Booked</h5>
          @endif

          @if($user_id == Auth::User()->id)
          <p class="card-text text-center booked_seat_username">Me</p>
         @else
          <p class="card-text text-center booked_seat_username">{{ $username }}</p>
         @endif
      </div>
    </div>
    @else

     @if($user_id == Auth::User()->id)
      @if($seat_status== 0)
           <div class="card text-white text-center" style="background-color: #5471BF;">
        @else
          <div class="card text-white bg-secondary text-center">
        @endif

      <div class="card-body">
        <div class="embed-responsive embed-responsive-1by1">
                    <div class="embed-responsive-item rounded-circle bg-light text-dark" style="background-image:url({{ $user_image }});"></div>

        </div>
        @if($seat_status == 0)
          <h5>Pending</h5>
        @else
          <h5>Booked</h5>
        @endif
          <p class="card-text text-center booked_seat_username">Me</p>
      </div>
    </div>
     @else

        @if($seat_status == 0)
           <div class="card text-white text-center" style="background-color: #5471BF;">
        @else
          <div class="card text-white bg-secondary text-center">
        @endif

      <div class="card-body">
        <div class="embed-responsive embed-responsive-1by1">
          <div class="embed-responsive-item rounded-circle bg-light text-dark"> <span>?</span></div>
        </div>
         @if($seat_status== 0)
          <h5>Pending</h5>
        @else
          <h5>Booked</h5>
        @endif
        <p class="card-text text-center">Unknown</p>
      </div>
    </div>
    @endif

       @endif
  </div>
</div>
<hr>
<div class="row">
  <div class="col-6">
    <button class="btn btn-block btn-secondary btn-sm" disabled >Book</button>
  </div>
  <div class="col-6">
    <button class="btn btn-block btn-danger btn-sm  close_seat_status_modal"  data-dismiss="modal">Cancel</button>
  </div>
</div>
