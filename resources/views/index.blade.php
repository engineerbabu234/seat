@extends('layouts.app')
@section('content')
<!--building office-->
<section class="reaserve-seat">
    <div class="container">
        <div class="building-office-list">
            <form id="seat_search_form" method="post" action="#" >
                <div class="card card-body">
                    <input type="hidden" id="bookdate">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5><i class="fa fa-search fa-xs"></i> Find A Seat, Space And Standing</h5>
                            <p>To find a Seat select the collect Building, office and office assets that you want to book your Seat, Space And Standing in</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1" title="Building"><span class="iconWrap iconSize_24"><img class="icon bl-icon" src="{{asset('admin_assets')}}/images/building.png" title="Building"  data-trigger="hover" id="building_info" data-placement="top" class="bl-icon"  width="30" ></span></span>
                                </div>
                                <select class="form-control building" name="building_id" id="building_id">
                                    <option value="">All</option>
                                    @foreach($Building as $key => $bvalue)
                                    <option value="{{$bvalue->building_id}}">{{$bvalue->building_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="error" id="building_id_error"></span>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1" title="Office"><span class="iconWrap iconSize_24"><img class="icon bl-icon" src="{{asset('admin_assets')}}/images/offices.png" title="Office"  data-trigger="hover" id="office_info" data-placement="top" class="bl-icon"  width="30" ></span></span>
                                </div>
                                <select class="form-control office" name="office_id" id="office_id"><option value="">All</option>
                            </select>
                        </div>
                        <span class="error" id="office_id_error"></span>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" title="Office Assets"><span class="iconWrap iconSize_24"><img src="{{asset('admin_assets')}}/images/assets.png" title="Office Assets"  data-trigger="hover" id="office_assets_info"  data-placement="top" class="bl-icon" width="25" ></span></span>
                            </div>
                            <select class="form-control office_assets" name="office_asset_id" id="office_asset_id"><option value="">All</option>
                        </select>
                    </div>
                    <span class="error" id="office_asset_id_error"></span>
                </div>
                 <div class="col-sm-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1" ><span class="iconWrap iconSize_24 iconSize_32"  title="Office Assets Type"  data-trigger="hover" id="assets_type"  data-placement="top"><img src="{{asset('admin_assets')}}/images/objects.png" class="bl-icon" width="25" ></span></span>
                            </div>
                            <select class="form-control office_assets_type" name="office_asset_type_id" id="office_asset_type_id"><option value="">All</option>
                                <option value="1">Desk</option>
                                <option value="2">Carpark Spaces</option>
                                <option value="3">Collaboration Spaces</option>
                                <option value="4">Meeting Room Spaces</option>
                        </select>
                    </div>
                    <span class="error" id="office_asset_type_error"></span>
                </div>
                <div class="col-sm-4">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" ><span class="iconWrap iconSize_24 iconSize_32" data-trigger="hover" title="Date" data-placement="top" data-content="Booking Date" ><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="25" ></span></span>
                        </div>
                        <input type="text" name="search_booking_date" id="search_booking_date" value="{{ date('d-m-Y')}}" class="form-control  ">
                    </div>
                    <span class="error" id="search_booking_date_error"></span>
                </div>
                <div class="col-sm-4">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1" title="Booking Status"><span class="iconWrap iconSize_24"><img src="{{asset('admin_assets')}}/images/status.png" title="Booking Status"  id="main_status_pop" data-trigger="hover" data-placement="top" class="bl-icon" width="25" ></span></span>
                        </div>
                        <select class="form-control" name="seat_status" id="seat_status">
                            <option value="1" selected >Available</option>
                            <option value="2">Blocked</option>
                            <option value="3">Booked</option>
                            <option value="4">Pending</option>
                            <option value="5">CleanRequired</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12"><br>
                    <div class="panel panel-info">
                        <div class="panel-heading collapsed" data-toggle="collapse" data-target="#bar">
                            <h5>Seat, Space And Standing Attributes<i class="fa fa-fw fa-chevron-down"></i>
                            <i class="fa fa-fw fa-chevron-right"></i></h5>
                        </div>
                        <div class="panel-body">
                            <!-- The inside div eliminates the 'jumping' animation. -->
                            <div class="collapse" id="bar">
                                <div class="card card-body">
                                    <div class="row deskattributes">
                                         <div class="col-sm-12"><h6>Desk Attibutes</h6></div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" title="Monitor" data-trigger="hover" data-content="Monitor" data-placement="top"><img   src="{{asset('admin_assets')}}/images/monitor.png" class="icon bl-icon" ></span> <input type="checkbox" name="monitor"  checked id="monitor">
                                                </div>
                                                <div class="col-4">
                                                     <span class="iconWrap iconSize_32 vertical-align-middle"  title="Private space" data-trigger="hover" data-content="Private space" data-placement="top"><img  src="{{asset('admin_assets')}}/images/privatespace.png"   class="icon bl-icon" ></span>  <input type="checkbox" name="privatespace" id="privatespace">
                                                </div>
                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle"  title="Wheelchair" data-trigger="hover" data-content="Wheelchair" data-placement="top"><img  src="{{asset('admin_assets')}}/images/wheelchair.png"   class="icon bl-icon pl-1" ></span> <input type="checkbox" name="wheelchair" id="wheelchair">
                                                </div>
                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" title="Dokingstation" data-trigger="hover" data-content="Dokingstation" data-placement="top"><img src="{{asset('admin_assets')}}/images/dokingstation.png"     class="icon bl-icon" ></span><input type="checkbox"  checked name="dokingstation" id="dokingstation">
                                                </div>
                                                <div class="col-4">
                                                   <span class="iconWrap iconSize_32 vertical-align-middle" title="Adjustable height" data-trigger="hover" data-content="Adjustable height" data-placement="top"> <img src="{{asset('admin_assets')}}/images/adjustableheight.png"    class="icon bl-icon" ></span><input type="checkbox" name="adjustableheight" id="adjustableheight">
                                                </div>
                                                <div class="col-4">
                                                     <span class="iconWrap iconSize_32 vertical-align-middle" title="USB charger" data-trigger="hover" data-content="USB charger" data-placement="top" ><img src="{{asset('admin_assets')}}/images/usbcharger.png"   class="bl-icon" width="32" ></span><input type="checkbox" name="usbcharger" id="usbcharger">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Privacy</label>
                                                <select class="form-control" name="privacy" id="privacy">
                                                    <option value="">Select Privacy Level</option>
                                                    <option value="1">Low</option>
                                                    <option value="2">Medium</option>
                                                    <option value="3">Height</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="row carparkspace">
                                        <div class="col-sm-12"><h6>CarParking Space Attributes</h6></div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" title="underground" data-trigger="hover" data-content="Underground Parking" data-placement="top"><img   src="{{asset('admin_assets')}}/images/underground.png" class="icon bl-icon" ></span> <input type="checkbox" name="underground" id="underground">
                                                </div>
                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Pole information" title="Pole information" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/pole_information.png"  alt="Pole information" class="icon bl-icon" width="30" ></span>
                                                    <input   type="checkbox"  name="pole_information" id="pole_information">
                                                </div>
                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Wheelchair accessable" title="Wheelchair accessable" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/wheelchair_accessable.png"  alt="Wheelchair accessable" class="icon bl-icon" width="30" ></span>
                                                   <input   type="checkbox"  name="wheelchair_accessable" id="wheelchair_accessable">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 vertical-align-middle">
                                            <div class="form-group">
                                                <label>Parking Difficulty</label>
                                                <select class="form-control" name="parking_difficulty" id="parking_difficulty">
                                                    <option value="">Select Parking Difficulty</option>
                                                    <option value="1">Low</option>
                                                    <option value="2">Medium</option>
                                                    <option value="3">Height</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row meetingspace">
                                        <div class="col-sm-12"><h6>Meeting space Attributes</h6></div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-4">
                                                <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Whiteboard avaialble" title="Whiteboard avaialble" data-trigger="hover" data-placement="top"  ><img src="{{asset('admin_assets')}}/images/whiteboard.png"  alt="Whiteboard avaialble" class="icon bl-icon" width="30" ></span>
                                                <input   type="checkbox"   name="whiteboard_avaialble" id="whiteboard_avaialble">

                                                </div>
                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Teleconference screen" title="Teleconference screen" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/teleconference_screen.png"  alt="Teleconference screen" class="icon bl-icon" width="30" ></span>
                                                   <input   type="checkbox"  name="teleconference_screen" id="teleconference_screen">

                                                </div>
                                                <div class="col-4">
                                                <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Whiteboard interactive" title="Whiteboard interactive" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard_interactive.png"  alt="Whiteboard interactive" class="bl-icon" width="30" ></span>
                                                   <input  type="checkbox"  name="is_white_board_interactive" id="is_white_board_interactive">
                                                </div>
                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Meeting indicator" title="Meeting indicator mounted on wall" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/meeting_indicator_mounted_on_wall.png"  alt="Meeting indicator mounted on wall" class="icon bl-icon" width="30" ></span>
                                                    <input  type="checkbox"  name="meeting_indicator_mounted_on_wall" id="meeting_indicator_mounted_on_wall">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                     <div class="row collaborationspace">
                                        <div class="col-sm-12"><h6>Collaboration Space Attributes</h6></div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-4">
                                                <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Kanban boar" title="Kanban board" data-trigger="hover" data-placement="top"  ><img src="{{asset('admin_assets')}}/images/kanban_board.png"  alt="Kanban board" class="icon bl-icon" width="30" ></span>
                                                <input  type="checkbox"   name="kanban_board" id="kanban_board">
                                                </div>

                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Whiteboard" title="Whiteboard" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard.png"  alt="Whiteboard" class="icon bl-icon" width="30" ></span>
                                                    <input  type="checkbox"  name="whiteboard" id="whiteboard">
                                                </div>

                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Interactive Whiteboard" title="Interactive Whiteboard " data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/whiteboard_interactive.png"  alt="Interactive Whiteboard " class="icon bl-icon" width="30" ></span>
                                                    <input   type="checkbox"  name="interactive_whiteboard" id="interactive_whiteboard">
                                                </div>

                                                <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Standing only" title="Standing only" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/standing_only.png"  alt="Standing only" class="icon bl-icon" width="30" ></span>
                                                    <input  type="checkbox" name="standing_only" id="standing_only">
                                                </div>
                                                 <div class="col-4">
                                                    <span class="iconWrap iconSize_32 vertical-align-middle" data-content="Telecomference screen" title="Telecomference screen" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/teleconference_screen.png"  alt="Telecomference screen" class="icon bl-icon" width="30" ></span>
                                                    <input   type="checkbox"  name="telecomference_screen" id="telecomference_screen">
                                                </div>
                                            </div>
                                        </div>

                                    </div>




                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group text-right"><br>
                        <input type="button" name="searchbtn" id="searchbtn" value="Find a Seat" class="form-control btn btn-info col-2">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row " id="result_table">
        <div class="col-sm-12">
            <div class="card card-body">
                <div class="custom-data-table">
                    <div class="data-table">
                        <div class="custom-table-height">
                            <div class="table-responsive">
                                <table class="table table-striped text-center" id="laravel_datatable">
                                    <thead>
                                        <tr>
                                            <th ><span class="iconWrap iconSize_32" title="Building"  data-trigger="hover" id="tbl_building_info" data-placement="top" ><img src="{{asset('admin_assets')}}/images/building.png"  class="icon bl-icon"   ></span></th>
                                            <th ><span class="iconWrap iconSize_32" title="Office" data-trigger="hover" id="tbl_office_info"  data-placement="top" ><img src="{{asset('admin_assets')}}/images/offices.png" class="icon bl-icon"  ></span></th>
                                            <th ><span class="iconWrap iconSize_32" title="Office Assets"  data-trigger="hover" id="tbl_office_assets_info"  data-placement="top"><img src="{{asset('admin_assets')}}/images/assets.png"  class="bl-icon"  ></span></th>
                                            <th ><span class="iconWrap iconSize_32"  title="Seat Attributes"  id="seat_attribute_hover"   data-trigger="hover"><img src="{{asset('admin_assets')}}/images/settings.png"    class="icon bl-icon"  > </span></th>
                                            <th ><span class="iconWrap iconSize_32" title="Seat Number"  data-trigger="hover" id="seat_info" data-placement="top"><img src="{{asset('admin_assets')}}/images/number.png"  class="icon bl-icon"  ></span></th>
                                            <th ><span class="iconWrap iconSize_32" title="Booking Status"  id="status_pop" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/status.png"  class="icon bl-icon"  ></span></th>
                                            <th ><span class="iconWrap iconSize_32" title="Booking Mode" data-trigger="hover" id="bookingmode" data-placement="top"><img src="{{asset('admin_assets')}}/images/booking_mode.png"   class="icon bl-icon"  ></span></th>
                                             <th ><span class="iconWrap iconSize_32" title="Assets Type" data-trigger="hover" id="tbl_assets_type" data-placement="top"><img src="{{asset('admin_assets')}}/images/objects.png"   class="icon bl-icon"  ></span></th>
                                            <th><span class="iconWrap iconSize_32" data-trigger="hover" id="questionarie_hover" data-placement="top" title="Questionaries" ><img src="{{asset('admin_assets')}}/images/questionarie.png"    class="icon bl-icon"  ></span></th>
                                             <th><span class="iconWrap iconSize_32" data-trigger="hover" id="contract_hover_info" data-placement="top" title="Contract" ><img src="{{asset('admin_assets')}}/images/contract.png" class="icon bl-icon"  ></span></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modes">
        <div class="row">
            <div class="col-sm-12">
                <span class="btn btn-sm booking_mode_css booking_status_a">A</span> <span>Seat Will be auto booked</span> <br>
                <span class="btn btn-warning btn-sm booking_mode_css booking_manual_css mt-2" >M</span> <span>Seat Require approval by admin</span>
            </div>
        </div>
    </div>
    <div id="questionarie_pophover">
        <div class="row">
            <div class="col-sm-12">
                <p>This shows the number of questionaries that
                    are required to be successfully completed and
                    in data prior to be able to successfully book
                this seat.</p>
                <p>
                    Click on the number to show the
                Questionaries attached</p>
            </div>
        </div>
    </div>
    <div id="seat_attribute_pophover">
        <div class="row">
            <div class="col-sm-12">
                <img title="Monitor" src="{{asset('admin_assets')}}/images/monitor.png"   class="bl-icon" width="20" > <span>Seat has a <b>monitor</b> attached</span><br>
                <img src="{{asset('admin_assets')}}/images/dokingstation.png" class="bl-icon" width="20" ><span>Seat has a <b>docking station</b> attached</span><br>
                <img src="{{asset('admin_assets')}}/images/adjustableheight.png"    class="bl-icon" width="20" > <span>The Desk has <b>adjustable height</b> capabilities</span><br>
                <img   src="{{asset('admin_assets')}}/images/privatespace.png"   class="bl-icon" width="20" > <span>This seat is located in a <b>private space</b></span><br>
                <img src="{{asset('admin_assets')}}/images/wheelchair.png"   class="bl-icon pl-1" width="20" ><span>This seat caters for <b>wheelchair</b> users</span><br>
                <img src="{{asset('admin_assets')}}/images/usbcharger.png"   class="bl-icon" width="20" ><span>This seat has a <b>USB charging</b> hub</span><br>
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
    </div>


    <div id="status_pophover">
        <div class="row">
            <div class="col-sm-12">
                <div class="mb-2"><span class="btn btn-sm booking_status booking_status_a" >A</span><span> Seat is <b>Available</b> to be booked</span></div>
                <div class="mb-2"><span class="btn btn-sm booking_status booking_status_b mt-2">B</span><span> Seat is <b>Blocked</b> and cannot be booked</span></div>
                <div class="mb-2"><span class="btn btn-sm booking_status booking_status_p">P</span> <span> Seat is <b>Pending</b> booking approval by an admin and cannot be booked by somebody else</span></div>
                <div class="mb-2"><span class="btn btn-sm booking_status booking_status_r">R</span> <span> Seat is <b>Reserved</b> by a user and cannot be booked by somebody else</span></div>
                <div class="mb-2"><span class="btn btn-sm booking_status booking_status_c">C</span><span> Seat is Requires <b>Cleaning</b> and cannot be booked until deep cleaning is complete</span></div>
            </div>
        </div>
    </div>
    <div id="user_questionarie_pophover">
        <div class="row">
            <div class="col-sm-12">
                <div id="user_questionarie_info"></div>
                <p>There are <span id="total_questionarie"></span> questionaries attached to this seat, below are your customized results</p>
                <p> </p>
            </div>
        </div>
    </div>
     <div id="user_contract_pophover">
        <div class="row">
            <div class="col-sm-12">
                <div id="user_contract_info"></div>
                <p>There are <span id="total_contract"></span> Contract Atteched to this seat </p>
                <p> </p>
            </div>
        </div>
    </div>

    <div id="contract_pophover">
        <div class="row">
            <div class="col-sm-12">
            <p>This shows the number of contract that
                are required to be successfully completed and
                in data prior to be able to successfully book
            this seat.</p>
            <p>
                Click on the number to show the
            contract attached</p>
            </div>
        </div>
    </div>


    <div id="building_popup">
        <div class="row">
            <div class="col-sm-12">
                <p>This field contains the <b>building</b> that the seat is located in</p>
            </div>
        </div>
    </div>
    <div id="office_popup">
        <div class="row">
            <div class="col-sm-12">
                <p>This field contains which <b>office</b> in the specified building that the seat is located in</p>
            </div>
        </div>
    </div>
    <div id="office_assets_popup">
        <div class="row">
            <div class="col-sm-12">
                <p>This field contains the <b>Office Asset (Office Area)</b> that the Seat is located within the Office</p>
            </div>
        </div>
    </div>
    <div id="seat_popup">
        <div class="row">
            <div class="col-sm-12">
                <p>This field contains the <b>seat number</b> identifier within the office asset (office area)</p>
            </div>
        </div>
    </div>
    <br>
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search Building or office" name="search_name" id="search_name">
    </div>
    <div class="row building-show"> </div>
</div>
</div>
</section><!--END building office-->
@endsection
@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('front_end')}}/css/jquery-ui.css">
<link  href="{{asset('front_end')}}/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@push('js')
<script src="{{asset('front_end')}}/js/jquery.dataTables.min.js"></script>
<script src="{{asset('front_end')}}/js/jquery-ui.js"></script>
<script src="{{asset('front_end')}}/js/filter_seat/index.js"></script>
@endpush
