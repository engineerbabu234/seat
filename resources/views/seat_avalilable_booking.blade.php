<div class="row">
  <input type="hidden" name="office_asset_id" value="{{$assets_id}}">
  <input type="hidden" name="seat_no" id="seat_no" value="{{$dots_id}}">
  <input type="hidden" name="office_id" value="{{$Office->office_id}}">
  <input type="hidden" name="booking_date" value="{{$booking_date}}">
  <div class="col">
    <div class="row">
    <div class="col-2">
        <h6 title="Date" data-toggle="tooltip" data-placement="top"><img src="{{asset('admin_assets')}}/images/reservation_request.png"   class="bl-icon" width="25" ></h6>
      </div>
      <div class="col">
        <span>{{ date('d-m-Y',strtotime($booking_date))}}</span>
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


@if($office_asset->asset_type == 1)
  <div class="row">
     <div class="col-5">
        <h6>Privacy
          @if($privacy == 1)
          <div class="level-status low-level"  title="Low privacy" data-toggle="tooltip" data-placement="top">
            <span></span>
            <span></span>
            <span></span>
          </div>

          @elseif($privacy == 2)
           <div class="level-status mid-level" title="Medium privacy" data-toggle="tooltip" data-placement="top">
              <span></span>
              <span></span>
              <span></span>
            </div>
          @elseif($privacy == 3)
            <div class="level-status"  title="High privacy" data-toggle="tooltip" data-placement="top" >
              <span></span>
              <span></span>
              <span></span>
            </div>
          @endif
       </h6>
      </div>
    </div>
@endif
@if($office_asset->asset_type == 2)
<div class="row">
     <div class="col-9">
        <h6>Parking Difficulty
          @if($OfficeSeat->parking_difficulty == 1)
          <div class="level-status low-level"  title="Low privacy" data-toggle="tooltip" data-placement="top">
            <span></span>
            <span></span>
            <span></span>
          </div>

          @elseif($OfficeSeat->parking_difficulty == 2)
           <div class="level-status mid-level" title="Medium privacy" data-toggle="tooltip" data-placement="top">
              <span></span>
              <span></span>
              <span></span>
            </div>
          @elseif($OfficeSeat->parking_difficulty == 3)
            <div class="level-status"  title="High privacy" data-toggle="tooltip" data-placement="top" >
              <span></span>
              <span></span>
              <span></span>
            </div>
          @endif
       </h6>
      </div>
    </div>
@endif
@if($office_asset->asset_type == 1)
    <div class="row">
      @if($monitor == 1)
        <div class="col-4">
         <span class="iconWrap iconSize_32" data-content="Monitor" title="Monitor"  data-trigger="hover" data-placement="top"  ><img src="{{asset('admin_assets')}}/images/monitor.png"  alt="Monitor" class="icon bl-icon" width="30" ></span>
      </div>
      @endif

      @if($privatespace == 1)
        <div class="col-4">
        <span class="iconWrap iconSize_32" data-content="Private space"  title="Private space" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/privatespace.png" alt="Private space"  class="icon bl-icon" width="30" ></span>
      </div>
      @endif
        @if($wheelchair == 1)
        <div class="col-4">
      <span class="iconWrap iconSize_32" data-content="Wheelchair" title="Wheelchair" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/wheelchair.png"  title="Wheelchair" class="icon bl-icon" width="30" ></span>
      </div>
      @endif

      @if($dokingstation == 1)
        <div class="col-4">
        <span class="iconWrap iconSize_32" data-content="Doking station" title="Doking station" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/dokingstation.png"  alt="Doking station" class="icon bl-icon" width="30" ></span>
      </div>
      @endif


      @if($adjustableheight == 1)
        <div class="col-4">
       <span class="iconWrap iconSize_32" data-content="Adjustable high" title="Adjustable high" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/adjustableheight.png"  alt="Adjustable high" class="icon bl-icon" width="30" ></span>
      </div>
      @endif



       @if($usbcharger == 1)
        <div class="col-4">
        <span class="iconWrap iconSize_32" data-content="USB charger" title="USB charger" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/usbcharger.png"  alt="USB charger" class="icon bl-icon" width="30" ></span>
      </div>
      @endif
    </div>
@endif
@if($office_asset->asset_type == 2)
    <div class="row">
      @if($OfficeSeat->underground == 1)
        <div class="col-4">
        <span class="iconWrap iconSize_32" data-content="Underground Parking" title="Underground Parking" data-trigger="hover" data-placement="top"  ><img src="{{asset('admin_assets')}}/images/underground.png"  alt="Underground Parking" class="icon bl-icon" width="30" ></span>
      </div>
      @endif

      @if($OfficeSeat->pole_information == 1)
        <div class="col-4">
        <span class="iconWrap iconSize_32" data-content="Pole information" title="Pole information" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/pole_information.png"  alt="Pole information" class="icon bl-icon" width="30" ></span>
      </div>
      @endif
        @if($OfficeSeat->wheelchair_accessable == 1)
        <div class="col-4">
        <span class="iconWrap iconSize_32" data-content="Wheelchair accessable" title="Wheelchair accessable" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/wheelchair_accessable.png"  alt="Wheelchair accessable" class="icon bl-icon" width="30" ></span>
      </div>
      @endif

    </div>
@endif
@if($office_asset->asset_type == 3)
    <div class="row">
      @if($OfficeSeat->kanban_board == 1)
        <div class="col-4">
        <span class="iconWrap iconSize_32" data-content="Kanban boar" title="Kanban board" data-trigger="hover" data-placement="top"  ><img src="{{asset('admin_assets')}}/images/kanban_board.png"  alt="Kanban board" class="icon bl-icon" width="30" ></span>
      </div>
      @endif

      @if($OfficeSeat->whiteboard == 1)
        <div class="col-4">
          <span class="iconWrap iconSize_32" data-content="Whiteboard" title="Whiteboard" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard.png"  alt="Whiteboard" class="icon bl-icon" width="30" ></span>
      </div>
      @endif
        @if($OfficeSeat->interactive_whiteboard == 1)
        <div class="col-4">
          <span class="iconWrap iconSize_32" data-content="Interactive Whiteboard" title="Interactive Whiteboard " data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard_interactive.png"  alt="Interactive Whiteboard " class="icon bl-icon" width="30" ></span>
      </div>
      @endif

       @if($OfficeSeat->standing_only == 1)
        <div class="col-4">
         <span class="iconWrap iconSize_32" data-content="Standing only" title="Standing only" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/standing_only.png"  alt="Standing only" class="icon bl-icon" width="30" ></span>
      </div>
      @endif

        @if($OfficeSeat->telecomference_screen == 1)
        <div class="col-4">
        <span class="iconWrap iconSize_32" data-content="Telecomference screen" title="Telecomference screen" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/teleconference_screen.png"  alt="Telecomference screen" class="icon bl-icon" width="30" ></span>
      </div>
      @endif


    </div>
@endif

@if($office_asset->asset_type == 4)
    <div class="row">
      @if($OfficeSeat->whiteboard_avaialble == 1)
        <div class="col-4">
      <span class="iconWrap iconSize_32" data-content="Whiteboard avaialble" title="Whiteboard avaialble" data-trigger="hover" data-placement="top"  ><img src="{{asset('admin_assets')}}/images/whiteboard.png"  alt="Whiteboard avaialble" class="icon bl-icon" width="30" ></span>
      </div>
      @endif

      @if($OfficeSeat->teleconference_screen == 1)
        <div class="col-4">
          <span class="iconWrap iconSize_32" data-content="Teleconference screen" title="Teleconference screen" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/teleconference_screen.png"  alt="Teleconference screen" class="icon bl-icon" width="30" ></span>
      </div>
      @endif
        @if($OfficeSeat->is_white_board_interactive == 1)
        <div class="col-4">
          <span class="iconWrap iconSize_32" data-content="Whiteboard interactive" title="Whiteboard interactive" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard_interactive.png"  alt="Whiteboard interactive" class="bl-icon" width="30" ></span>
      </div>
      @endif

       @if($OfficeSeat->meeting_indicator_mounted_on_wall == 1)
        <div class="col-4">
         <span class="iconWrap iconSize_32" data-content="Meeting indicator" title="Meeting indicator mounted on wall" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/meeting_indicator_mounted_on_wall.png"  alt="Meeting indicator mounted on wall" class="icon bl-icon" width="30" ></span>
      </div>
      @endif

    </div>
@endif

  </div>
  <div class="col-4">

    <div class="card text-white bg-success text-center">
      <div class="card-body">
        <div class="embed-responsive embed-responsive-1by1">
           <div class="embed-responsive-item rounded-circle bg-light text-dark"> <span>?</span></div>
        </div>
        <p class="card-text text-center">Available</p>
      </div>
    </div>

  </div>
</div>
<hr>
<div class="row">
  <div class="col-6">
    <button class="btn btn-block btn-success btn-sm booking-seat" >Book</button>
  </div>
  <div class="col-6">
    <button class="btn btn-block btn-danger btn-sm close_seat_book_modal" data-dismiss="modal">Cancel</button>
  </div>
</div>
