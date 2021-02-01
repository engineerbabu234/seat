@extends('layouts.app')
@section('content')

<!--building office-->
<section class="reaserve-seat reaserve-seat-page">
    <div class="container">
        <div class="building-office-list">
            <div class="heading">
                @if($officeAsset->asset_type == 1 OR $officeAsset->asset_type == 4)
                <h1>Reserve Seat</h1>
                <p>Click on the Seatmap below  to reserve your Seat.</p>
                @elseif($officeAsset->asset_type == 3)
                <h1>Reserve Standing</h1>
                <p>Click on the Standingmap below  to reserve your Standing.</p>
                @elseif($officeAsset->asset_type == 2)
                <h1>Reserve Space</h1>
                <p>Click on the Spacemap below  to reserve your Space.</p>
                @endif
            </div>
            <div class="single-list">
                <div class="slider-box">
                </div>
                <input type="hidden"  id="seat_type" value="">
                <input type="hidden"  id="seat_status" value="">
                <input type="hidden"  id="total_count" value="">
                <input type="hidden" name="seat_ids" id="seat_ids" value="">
                <div class="seat-status">
                     @if($officeAsset->asset_type == 1 OR $officeAsset->asset_type == 4)
                     <h5>Seat Status</h5>
                     @elseif($officeAsset->asset_type == 3)
                      <h5>Standing Status</h5>
                    @elseif($officeAsset->asset_type == 2)
                        <h5>Space Status</h5>
                     @endif
                    <h6 class="ts total_seats"></h6>
                    <h6 class="as available_seats"></h6>
                    <h6 class="bs1 booked_seats"></h6>
                    <h6 class="bs blocked_seats"></h6>
                    <h6 class="bs pending_seats"></h6>
                </div>
                <div class="building-office-details">
                    <div class="row">
                        <div class="col-sm-4">
                            <h2><span title="building"><img src="{{asset('admin_assets')}}/images/building.png" alt="building" class="bl-icon" width="30" > </span> {{$data['offices']->building_name}}</h2>
                            <p><span title="address"><img src="{{asset('admin_assets')}}/images/address.png" alt="address" class="bl-icon" width="20" style="width: 13px;" > </span>{{$data['offices']->building_address}}</p>
                        </div>
                        <div class="col-sm-4">
                            <h3><span title="Office"><img src="{{asset('admin_assets')}}/images/offices.png" alt="office" class="bl-icon" width="30" ></span> {{$data['offices']->office_name}}</h3>
                            <p><span  title="Office Number"> <img src="{{asset('admin_assets')}}/images/number.png" alt="office number" class="bl-icon" width="20" style="width: 13px;" > </span>{{$data['offices']->office_number}}</p>
                        </div>
                        <div class="col-sm-4">
                            <h3><span title="Assets"><img src="{{asset('admin_assets')}}/images/assets.png" alt="assets" class="bl-icon" width="30" ></span> {{$data['offices']->title}}</h3>
                            <p><span title="Description"><img src="{{asset('admin_assets')}}/images/description.png" alt="Description" class="bl-icon" width="20" style="width: 13px;" >  </span>{{$data['offices']->description}}</p>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('seat_reservation') }}" >
                    @csrf
                    {{ method_field('PUT') }}
                    <input type="hidden" name="office_id" value="{{$data['offices']->office_id}}">
                    <div class="input-feilds">
                        <div class="row">
                            <div class="col-sm-4">
                            <h4>
                                @if($officeAsset->asset_type == 1 OR $officeAsset->asset_type == 4)
                                <img src="{{asset('admin_assets')}}/images/seat.png">Select Your Seat
                                @elseif($officeAsset->asset_type == 3)
                                <img src="{{asset('admin_assets')}}/images/standing_only.png">Select Your Standing
                                @elseif($officeAsset->asset_type == 2)
                               <img src="{{asset('admin_assets')}}/images/pole_information.png">Select Your Space
                                @endif
                            </h4>
                            </div>
                            <div class="col-sm-2">
                                <div class="custom-date-picker">
                                    <input type="input" class="form-control datepicker " hidden value="{{ date('d-m-Y') }}" placeholder="Select Book date" name="booking_date" id="booking_date"> </div>
                                </div>
                                <div class="col-sm-3 seat_date"> Date : <span id="today_date">{{ date('d-m-Y') }}</span> </div>
                            </div>
                            <div class="container-fluid" style="height: 100%;">
                                <input type="hidden"  id="total_count" value="">
                                <input type="hidden"  id="last_id" value="">
                                <input type="hidden" name="seat_ids" id="seat_ids" value="">
                                <input type="hidden" name="is_edit" id="is_edit" value="">
                                <input type="hidden" name="dots_id" id="dots_id" value="">
                                <input type="hidden" name="building_id" id="building_id" value="{{ $officeAsset->building_id }}">
                                <input type="hidden" name="office_id" id="office_id" value="{{ $officeAsset->office_id }}">
                                <input type="hidden" id="main_image" value="{{ $assets_image }}">
                                <input type="hidden" id="canvas_image" value="{{ isset($officeAsset->asset_canvas) ? 1 : 0 }}">
                                <input type="hidden" name="asset_id" id="asset_id" value="{{ $officeAsset->id }}">
                                <input type="file" id="file-input" style="display: none" />
                                <a href="#" style="display: none" class="arrow up">Up</a>

                                  <img src="{{asset('front_end')}}/images/click_arrow.png" class="view_arrow" height="30" >


                                <div class="row" style="height: 100%;">
                                    <div class=" col-sm-12" id="main">
                                        <canvas class="content" id="canvas"></canvas>
                                    </div>
                                </div>
                                <hr>
                                <span style="display: none" class="shadowImg" height="30"></span>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal" id="user_exam_modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span>Take Exam</span></h4>
                        <button type="button" class="close_new close_question_exam_modal" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" >
                        <div  id="view_exam_details"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="seat_book_modal">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span>
                    @if($officeAsset->asset_type == 1 OR $officeAsset->asset_type == 4)
                     Seat Status
                     @elseif($officeAsset->asset_type == 3)
                      Standing Status
                    @elseif($officeAsset->asset_type == 2)
                        Space Status
                     @endif</span></h4>
                        <button type="button" class="close_new close_seat_book_modal"  data-dismiss="modal" >&times;</button>
                    </div>
                    <div class="modal-body" >
                        <form action="#"  method="post" id="add-seat-book-form">
                            <div  id="seat_book_details"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="blocked_seat_modal">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span> @if($officeAsset->asset_type == 1 OR $officeAsset->asset_type == 4)
                     Seat Status
                     @elseif($officeAsset->asset_type == 3)
                      Standing Status
                    @elseif($officeAsset->asset_type == 2)
                        Space Status
                     @endif</span></h4>
                        <button type="button" class="close_new close_blocked_seat_modal"  data-dismiss="modal" >&times;</button>
                    </div>
                    <div class="modal-body" >
                        <div  id="blocked_seat_details"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="bookedseat_modal" >
            <div class="modal-dialog  ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> @if($officeAsset->asset_type == 1 OR $officeAsset->asset_type == 4)
                     Seat Status
                     @elseif($officeAsset->asset_type == 3)
                      Standing Status
                    @elseif($officeAsset->asset_type == 2)
                        Space Status
                     @endif</h4>
                        <button type="button" class="close_new close_seat_status_modal btn btn-light"  data-dismiss="modal" >×</button>
                    </div>
                    <div class="modal-body" >
                        <div  id="view_booked_seat_details"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
    @push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('front_end')}}/css/jquery-ui.css">
    <link  href="{{asset('admin_assets')}}/css/seat_book/main.css" rel="stylesheet">
    <link  href="{{asset('admin_assets')}}/css/seat_book/modal.css" rel="stylesheet">
    @endpush
    @push('js')
    <script src="{{asset('front_end')}}/js/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script type="text/javascript" src="{{asset('admin_assets')}}/js/seat_book/fabric/fabric.min.js"></script>
    <script src="{{asset('admin_assets')}}/js/seat_book/fabric/centering_guidelines.js"></script>
    <script type="text/javascript" src="{{asset('admin_assets')}}/js/seat_book/fabric/aligning_guidelines.js"></script>
    <script type="text/javascript" src="{{asset('front_end')}}/js/asset-canvas.js"></script>
    <script>
    $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    });
    </script>
    <script type="text/javascript">
    $("#main").canvasfiles();
    $(document).ready(function(){
    var office_id ='{{$data['offices']->office_id}}';
    var office_assset_id ='{{$officeAsset->id}}';
    var reserve_date = $('#booking_date').val();
    $.ajax({
    "headers":{
    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    },
    method: 'get',
    url: base_url+'/get_seat_list',
    data: {
    "reserve_date": reserve_date,
    "office_id" : office_id,
    "asset_id" : office_assset_id,
    },
    'beforeSend': function() {
    },
    'success' : function(response){
    var html = '';
    if(response.status){
    $('.total_seats').text('Total : '+response.count_data.total_seats);
    $('.available_seats').text('Available : '+response.count_data.available_seats);
    $('.booked_seats').text('Reserved : '+response.count_data.booked_seats);
    $('.blocked_seats').text('Blocked : '+response.count_data.blocked_seats);
    $('.pending_seats').text('Pending : '+response.count_data.pending_seats);
    }
    },
    'error' :  function(errors){
    console.log(errors);
    },
    complete: function () {
    },
    })
    $("#booking_date").on('change',function(){
    var reserve_date = $('#booking_date').datepicker({ dateFormat: 'dd-mm-yy' }).val();
    $('#today_date').text(reserve_date);
    $('#booking_date').val(reserve_date);
    $.ajax({
    "headers":{
    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    },
    method: 'get',
    url: base_url+'/get_seat_list',
    data: {
    "reserve_date": reserve_date,
    "asset_id" : office_assset_id,
    "office_id" : office_id,
    },
    'beforeSend': function() {
    },
    'success' : function(response){
    $('.seats-list').html('');
    var html = '';
    if(response.status){
    $('.total_seats').text('Total : '+response.count_data.total_seats);
    $('.available_seats').text('Available : '+response.count_data.available_seats);
    $('.booked_seats').text('Reserved : '+response.count_data.booked_seats);;
    $('.blocked_seats').text('Blocked : '+response.count_data.blocked_seats);
    $('.pending_seats').text('Pending : '+response.count_data.pending_seats);
    }
    },
    'error' :  function(errors){
    console.log(errors);
    },
    complete: function () {
    },
    })
    })
    });
    </script>

    <script type="text/javascript">
    @if($message = Session::get('success'))
    title='Success';
    message='{{$message}}';
    myalert(title,message)
    function myalert(title,msg){
    $.alert(msg, {
    title: title,
    closeTime: 10000,
    // withTime: $('#withTime').is(':checked'),
    //isOnly: !$('#isOnly').is(':checked')
    });
    }
    @endif
    @if($message = Session::get('error'))
    title='Error';
    message='{{$message}}';
    myalert(title,message)
    function myalert(title,msg){
    $.alert(msg, {
    title: title,
    closeTime: 10000,
    type:'danger',
    });
    }
    @endif
    $(document).on('click','.close_book_seat_modal',function(){
    $('#bookseatModal').modal('hide');
    });
    $(document).on('click','.close_question_modal',function(){
    $('#seatquestion').modal('hide');
    });

    $(document).on('click','.close_seat_status_modal',function(){
    $('#bookedseat_modal').hide();
    });
    $(document).on('click','.close_question_exam_modal',function(){
    $('#user_exam_modal').hide();
    });
    $(document).on('click','.close_seat_book_modal',function(){
    $('#seat_book_modal').hide();
    });
    $(document).on('click','.close_blocked_seat_modal',function(){
    $('#blocked_seat_modal').hide();
    });

    $("#booking_date").datepicker({
        dateFormat: 'dd-mm-yy',
        changeYear: true,
        changeMonth: true,
        minDate:0,
        maxDate:'{{isset($officeAsset->book_within) ? ($officeAsset->book_within-1) : 13 }}',
        yearRange: "-100:+20",
        showOn: "button",
        buttonText: "Calender"
    });


    </script>
    @endpush
