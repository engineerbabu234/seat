@extends('layouts.app')
@section('content')
<!--building office-->
<section class="reaserve-seat reaserve-seat-page">
    <div class="container">
        <div class="building-office-list reservation-block">
            <div class="row">
                <div class="col-sm-4 mb-3">
                    <div class="card card-color-1">
                      <div class="card-body">
                        <div class="top-content-block">
                            <h5 class="card-title ">{{Auth::User()->user_name}}</h5>
                            <p class="card-text">This Week You Have :</p>
                            <ul>
                                <li><span id="confirmed_seat"> </span> confirmed</li>
                                <li><span id="pending_seat"> </span> Pending </li>
                                <li><span id="completed_seat"> </span> Completed</li>
                                <li><span id="no_show_seat"> </span> No Show</li>
                                <li> <span >Reservations</span></li>
                            </ul>

                        </div>
                      </div>
                    </div>
                </div>
                <div class="col-sm-4 mb-3">
                    <div class="card card-color-2">
                      <div class="card-body">
                        <div class="top-content-block">
                            <h5 class="card-title "> Today Reservation </h5>
                            @if(isset($today_reservation->reservation_id) && $today_reservation->reservation_id !='')
                            <ul>
                                <li>
                                    <span title="Building" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/building.png"   class="bl-icon" width="25" ></span> {{ isset($today_building->building_name) ? $today_building->building_name : '' }} </li>
                               <li>
                                    <span title="Office" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/offices.png" alt="office" class="bl-icon" width="25" ></span>  {{ isset($today_office->office_name) ? $today_office->office_name : '' }}
                                </li>
                               <li>
                                    <span title="Office assets" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/assets.png" alt="office Assets" class="bl-icon bl-icon" width="25" ></span>  {{ isset($today_assets->title) ? $today_assets->title : '' }}
                                </li>
                               <li>
                                    <span title="Reservation Id" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/number.png" class="bl-icon" width="25" ></span>
                                    <span>{{ isset($today_reservation->reservation_id) ? $today_reservation->reservation_id : ''}}
                                    </span>
                                </li>

                                 <li>
                                    <span title="Status" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/status.png" class="bl-icon" width="25" >
                                    @if ($today_reservation['status'] == '1' OR $today_reservation['status'] == '4' )
                                    <span class="text-success" id="today_dlt_status">Approved</span>
                                    @elseif( $today_reservation['status'] == '0')
                                         <span class="text-danger" id="today_dlt_status">Pending</span>
                                    @endif

                                    </span>
                                 </li>
                            </ul>
                            @else
                              <h4 class="card-title pt-50">No Reservations!</h4>
                            @endif
                        </div>

                      </div>
                </div>
                </div>
                <div class="col-sm-4 mb-3">
                    <div class="card card-color-3">
                      <div class="card-body">
                        <div class="top-content-block">
                            <h5 class="card-title"> Next Reservation </h5>

                            @if(isset($tomorrow_reservation->reservation_id) && $tomorrow_reservation->reservation_id !='')

                             <ul>
                               <li>
                                    <span><img src="{{asset('admin_assets')}}/images/reservation_request.png" title="Reservation Date" data-trigger="hover" data-placement="top" class="bl-icon" width="25" ></span><span id="dlt_building_id"> {{ isset($tomorrow_reservation->reserve_date) ? date('d/m/Y',strtotime($tomorrow_reservation->reserve_date)) : '' }} </span>
                                  </li>

                                <li>
                                    <span><img src="{{asset('admin_assets')}}/images/building.png" title="Building" data-trigger="hover" data-placement="top" class="bl-icon" width="25" ></span><span id="dlt_building_id"> {{ isset($tomorrow_building->building_name) ? $tomorrow_building->building_name : '' }} </span></li>
                               <li>
                                    <span title="Office" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/offices.png" alt="office" class="bl-icon" width="25" ></span>  <span id="dlt_offices_id">{{ isset($tomorrow_office->office_name) ? $tomorrow_office->office_name : '' }} </span>
                                </li>
                               <li>
                                    <span title="Office assets" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/assets.png" alt="office Assets" class="bl-icon " width="25" ></span><span id="dlt_assets_id">  {{ isset($tomorrow_assets->title) ? $tomorrow_assets->title : '' }} </span>
                                </li>
                              <li>
                                    <span title="Reservation Id" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/number.png" class="bl-icon" width="25" ></span><span id="dlt_id">{{ isset($tomorrow_reservation->reservation_id) ? $tomorrow_reservation->reservation_id : ''}}</span>
                                </li>

                                   <li>
                                    <span title="Status" data-trigger="hover" data-placement="top"><img src="{{asset('admin_assets')}}/images/status.png" class="bl-icon" width="25" >
                                    @if ($tomorrow_reservation['status'] == '1' OR $tomorrow_reservation['status'] == '4' )
                                    <span class="text-success" id="dlt_status">Approved</span>
                                    @elseif( $tomorrow_reservation['status'] == '0')
                                        <span class="text-warning" id="dlt_status">Pending</span>
                                    @endif
                                    </span>
                                 </li>
                            </ul>
                             @else
                              <h4 class="card-title pt-50"> No Reservations!</h4>
                            @endif

                        </div>
                        @if(isset($tomorrow_reservation->reservation_id))
                         @if ($tomorrow_reservation['status'] == '1' OR $tomorrow_reservation['status'] == '4' )
                            <div class="check-in-btn">
                                <button class="btn btn-danger delete-seat-status" reservation_id="{{$tomorrow_reservation->reservation_id}}" reserve_seat_id="{{$tomorrow_reservation->reserve_seat_id}}" >Delete</button>
                            </div>
                        @elseif( $tomorrow_reservation['status'] == '0')
                             <div class="check-in-btn">
                                <button class="btn btn-secondary" title="Delete Reservation"  id="delete_hover"   data-trigger="hover" >Delete</button>
                            </div>

                        @endif
                        @endif
                      </div>
                </div>

                </div>
            </div>
            <!-- <div class="heading">
                <h1>Your Seat Booking History</h1>
                <p>Below is your seat booking history</p>
                <p>You can Cancel any pending or accepted seat reservation here, however you cannot cancel a seat booked for today due to COVID restrictions</p>
            </div> -->
            <div class="your-history">
                        <div class="custom-data-table">
                            <div class="data-table">
                                <div class="custom-table-height">
                                    <div class="table-responsive">
                                        <table class="table table-striped text-center" id="laravel_datatable">
                                            <thead>
                                                <tr>
                                                    <th><span class="iconWrap iconSize_32" title="Reservation ID" data-content="Reservation ID" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="30" ></span></th>
                                                    <th><span class="iconWrap iconSize_32"  data-content="This field contains the  building  that the seat is located in"  title="Building"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="icon bl-icon" width="30" ></span></th>
                                                    <th><span class="iconWrap iconSize_32" title="Office"  data-trigger="hover" data-content="This field contains which  office  in the specified building that the seat is located in" data-placement="left"><img src="{{asset('admin_assets')}}/images/offices.png" class="icon bl-icon" width="30" ></span></th>
                                                    <th><span class="iconWrap iconSize_32" data-content="This field contains the  Office Asset (Office Area)  that the Seat is located within the Office"  title="Assets"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/assets.png" class="icon bl-icon" width="30" ></span></th>
                                                    <th><span class="iconWrap iconSize_32" title="Seat No."  data-trigger="hover" data-content="This field contains the  seat number identifier within the office asset (office area)" data-placement="left"><img src="{{asset('admin_assets')}}/images/seat-no.png" class="icon bl-icon" width="30" ></span> </th>
                                                    <th><span class="iconWrap iconSize_32" title="Date"  data-trigger="hover" data-content="Order Date" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="30" ></span>  </th>
                                                    <th><span class="iconWrap iconSize_32"  data-content="Booking status" title="Status"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/status.png" class="icon bl-icon" width="30" ></span></th>
                                                    <th><span class="iconWrap iconSize_32" title="Action"  data-trigger="hover" data-content="Booking Action" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="30" ></span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                </div>
            </div>
        </div>

         <div id="delete_pophover">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Only Reservations that are made beyond today and are not pending admin approval can be canelled.</p>
                                <p>If your reservation is pending, please contact admin to cancel or approve the reservation </p>
                            </div>
                        </div>
                    </div>

        </section><!--END building office-->
        @push('css')
<link  href="{{asset('front_end')}}/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
        @endsection
        @push('js')
        <script src="{{asset('front_end')}}/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">

        urls = base_url+'/get_history/';

        var asset_datatable =$('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            "ordering": false,
            destroy: true,
            ajax:urls ,
            columns: [
                { data: 'number_key', name: 'number_key' },
                { data: 'building_name', name: 'building_name' },
                { data: 'office_name', name: 'office_name' },
                { data: 'assets_name', name: 'assets_name' },
                { data: 'seat_no', name: 'seat_no' },
                { data: 'reserve_date', name: 'reserve_date' },
                { data: 'status', name: 'status' },
                { data: 'cancel_button', name: 'cancel_button' },
            ]
        });





        $('body').on('click','.cancel-status', function() {

         var reserve_seat_id = $(this).attr("reserve_seat_id");
        var success_status='Cancelled';
        path='reservation_status_change';
         swal({
          title: "Are you sure?",
          text: "Once cancel seat booking, you will not be able to recover this seat data!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
         })
         .then((willDelete) => {
            if(!willDelete){
                return false;
            }
                $.ajax({
                    "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                'url' :  base_url + '/' +path,
                'data' : {  reserve_seat_id : reserve_seat_id},
                beforeSend: function() {
                },
                'success' : function(response){
                    if(response.status){
                        swal(success_status ,response.message, 'success');
                        var redrawtable = jQuery('#laravel_datatable').dataTable();
                        redrawtable.fnDraw();
                         view_reservation();
                    }
                },
                'error' : function(error){
                },
                complete: function() {
                    var redrawtable = jQuery('#laravel_datatable').dataTable();
                    redrawtable.fnDraw();
                     view_reservation();
                },
                });
         });


        });


        $('body').on('click','.delete-seat-status', function() {

         var reserve_seat_id = $(this).attr("reserve_seat_id");
         var reservation_id = $(this).attr("reservation_id");
        var success_status='Cancelled';
        path='reservation_status_change';
         swal({
          title: "Are you sure?",
          text: "Once Delete seat booking, you will not be able to recover this seat "+reservation_id+" data!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
         })
         .then((willDelete) => {
            if(!willDelete){
                return false;
            }
                $.ajax({
                    "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                'url' :  base_url + '/' +path,
                'data' : {  reserve_seat_id : reserve_seat_id},
                beforeSend: function() {
                },
                'success' : function(response){
                    if(response.status){
                        swal(success_status ,response.message, 'success');
                        $('#dlt_building_id').text('');
                        $('#dlt_offices_id').text('');
                        $('#dlt_assets_id').text('');
                        $('#dlt_reservation_id').text('');
                        $('#dlt_id').text('');
                        $('#dlt_status').text('');
                        $('.delete-seat-status').hide();
                         var redrawtable = jQuery('#laravel_datatable').dataTable();
                        redrawtable.fnDraw();
                         view_reservation();
                    }
                },
                'error' : function(error){
                },
                complete: function() {
                      $('#dlt_building_id').text('');
                        $('#dlt_offices_id').text('');
                        $('#dlt_assets_id').text('');
                        $('#dlt_id').text('');
                        $('#dlt_status').text('');
                        $('.delete-seat-status').hide();
                         var redrawtable = jQuery('#laravel_datatable').dataTable();
                        redrawtable.fnDraw();
                         view_reservation();
                },
                });
         });


        });

         $('body').on('click','.seat_check_in', function() {

         var reserve_seat_id = $(this).attr("reserve_seat_id");
        var success_status='Checkin';
        path='seat_check_in';
         swal({
          title: "Are you sure?",
          text: "Want to check-in this seat !",
          icon: "warning",
          buttons: true,
          dangerMode: true,
         })
         .then((willDelete) => {
            if(!willDelete){
                return false;
            }
                $.ajax({
                    "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                'url' :  base_url + '/' +path,
                'data' : {  reserve_seat_id : reserve_seat_id},
                'method':'post',
                beforeSend: function() {
                },
                'success' : function(response){
                    if(response.status){

                        swal(success_status ,response.message, 'success');
                        $('.checkinbtn').html("<label class='text-white pr-4'>Check in time </label><span id='checkin_time' class='text-white' >"+response.time+"</span>");
                    }
                },
                'error' : function(error){
                },
                complete: function() {

                },
                });
         });
        });

        view_reservation();
        function view_reservation() {
             $.ajax({
                "headers":{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                'url' :  base_url+'/get_reservation_info',
                'method':'post',
                beforeSend: function() {
                },
                'success' : function(response){
                    if(response.success){
                        $('#confirmed_seat').text(response.confirmed);
                        $('#pending_seat').text(response.pending);
                        $('#completed_seat').text(response.completed);
                        $('#no_show_seat').text(response.no_show);
                    }
                },
                'error' : function(error){
                },
                complete: function() {
                },
            });

       }

       $('#delete_pophover').hide();
       $('#delete_hover').popover({
            content:  $('#delete_pophover').html(),
            placement: 'left',
            html: true
        });

        </script>
        @endpush
