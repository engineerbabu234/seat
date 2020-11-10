@extends('layouts.app')
@section('content')
    <!--building office-->
    <section class="reaserve-seat reaserve-seat-page">
        <div class="container">
            <div class="building-office-list">


                <div class="single-list">
                    <div class="slider-box">

                    </div>
                    <div class="building-office-details">
                        {{-- <div class="img">
                            <img src="{{$data['offices']->office_image}}" class="img-fluid">
                        </div> --}}
                        <h2><span>Building:</span> {{$data['offices']->building_name}}</h2>
                        <p><span>Address:</span>{{$data['offices']->building_address}}</p>
                        <h3><span>Office:</span> {{$data['offices']->office_name}}</h3>
                        <p><span>Office Number:</span>{{$data['offices']->office_number}}</p>
                          <h3><span>Assets:</span> {{$data['offices']->title}}</h3>
                        <p><span>Assets Description:</span>{{$data['offices']->description}}</p>
                    </div>

                    <div class="seat-status">
                        <h5>Seat Status</h5>

                        {{-- <h6 class="ts total_seats">Total Seats: {{$data['offices']->total_seats}}</h6>
                        <h6 class="as available_seats">Available Seat: {{$data['offices']->available_seat}}</h6>
                        <h6 class="bs1 booked_seats">Booked Seat: {{$data['offices']->reserved_seat}}</h6>
                        <h6 class="bs blocked_seats">Blocked Seat: {{$data['offices']->blocked_seat}}</h6> --}}
                        <h6 class="ts total_seats"></h6>
                        <h6 class="as available_seats"></h6>
                        <h6 class="bs1 booked_seats"></h6>
                        <h6 class="bs blocked_seats"></h6>
                        <h6 class="bs pending_seats"></h6>
                    </div>
                    <form method="POST" action="{{ route('seat_reservation') }}" >
                        @csrf
                        {{ method_field('PUT') }}
                        <input type="hidden" name="office_id" value="{{$data['offices']->office_id}}">
                        <div class="input-feilds">
                            <h4><img src="{{asset('front_end')}}/images/seat.png"> Select Your Seat</h4>

                            <div class="input-details">
                                <div class="select-seat">
                                    <div class="clearfix seats-list">
                                        {{-- @if($data['offices']->seats->isEmpty())
                                            <h1>Record not found</h1>
                                        @else
                                        @foreach($data['offices']->seats as $key => $value)
                                            <div class="single-seat">
                                                <label class="@if($value->seat_type=='2'){{'booked'}}@elseif($value->status=='1'){{'disabled'}}@endif">
                                                    <input type="radio" @if($value->seat_type=='2'){{'disabled'}}@elseif($value->status=='1'){{'disabled'}}@endif value="{{$value->seat_id}}"  name="seat_number">
                                                    <span>{{$value->seat_no}}</span>
                                                    <p>{{$value->description}}</p>
                                                </label>
                                            </div>
                                        @endforeach
                                        @endif--}}
                                        @error('seat_number')
                                            <span class="invalid-feedback" role="alert" style="display: block;">
                                            <strong style="color: red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    {{-- <div class="rserve-advance">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck" name="checkbox" value="30" >
                                            <label class="custom-control-label" for="customCheck">Reserve your seat in advance for 30 days</label>
                                        </div>
                                        @error('checkbox')
                                            <span class="invalid-feedback" role="alert" style="display: block;">
                                            <strong style="color: red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> --}}
                                </div><!--END select-seat-->
                                <div class="form-group">
                                    <h2>Select Date</h2>
                                    <div class="custom-date-picker">
                                        <input type="text" placeholder="Date" name="reserve_date" value="{{date('d/m/Y')}}" id="datepicker" readonly="" >
                                    </div>



                                    @error('reserve_date')
                                        <span class="invalid-feedback" role="alert" style="display: block;">
                                            <strong style="color: red">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @if (!Auth::guest())
                                <button type="submit"> <i class="fas fa-chair"></i> Reserve Seat</button>
                            @else
                                <a href="{{url('login')}}"><button> <i class="fas fa-chair"></i> Reserve Seat</button></a>
                            @endif
                        </div>
                    </form>

                    {{-- <button id="swal"> <i class="fas fa-chair"></i> Reserve Seat</button> --}}

                </div>
            </div>
        </div>
    </section><!--END building office-->
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

<script type="text/javascript" src="{{asset('admin_assets')}}/pages/office_assets/asset-canvas.js"></script>
<script>

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
<script type="text/javascript">
    $(document).ready(function(){
        var office_id ='{{$data['offices']->office_id}}';
        var reserve_date = $('#datepicker').val();
        $.ajax({
            "headers":{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            method: 'get',
            url: base_url+'/get_seat_list',

            data: {
                "reserve_date": reserve_date,
                "office_id" : office_id,
            },
            'beforeSend': function() {

            },
            'success' : function(response){
                console.log(response);
                var html = '';
                if(response.status){
                    console.log(response.data);
                    $('.total_seats').text('Total : '+response.count_data.total_seats);
                    $('.available_seats').text('Available : '+response.count_data.available_seats);
                    $('.booked_seats').text('Reserved : '+response.count_data.booked_seats);
                    $('.blocked_seats').text('Blocked : '+response.count_data.blocked_seats);
                    $('.pending_seats').text('Pending : '+response.count_data.pending_seats);

                    $.each(response.data, function( key , value ) {
                        html+='<div class="single-seat">';
                        if(value.seat_type=='2'){
                            html+='<label class="booked">';
                            html+='<input type="radio" disabled value="'+value.seat_id+'"  name="seat_number">';
                            html+='<span>'+value.seat_no+'</span>';
                            html+='<p>'+value.description+'</p>';
                            html+='</label>';
                        }
                        else if(value.seat_reserve_status=='1')
                        {
                            if(value.book_status=='0'){
                                add_class='pending';
                            }else{
                                add_class='disabled';
                            }
                            if(value.tool=='1'){
                                var tool='data-toggle="tooltip" title="I have booked!"';
                            }else{
                                if(value.is_show_user_details=='0'){
                                    var tool='data-toggle="tooltip" title="Booked"';
                                }else{
                                    var tool='data-toggle="tooltip" title="Booked by '+value.user_name+'"';
                                }
                            }
                            html+='<label '+tool+' class="'+add_class+'">';

                            html+='<input type="radio" disabled value="'+value.seat_id+'"  name="seat_number">';
                                if(value.is_show_user_details=='0'){
                                    html+='<span>';
                                    html+='<p>';
                                    html+='<i>';
                                    html+='<img src="'+value.profile_image+'">';
                                    //html+='<img src="quetion.png">';
                                    html+='</i>' ;
                                    html+=''+value.seat_no+'</p>'
                                    html+='</span>';
                                }else{
                                    html+='<span>';
                                    html+='<p>';
                                    html+='<i>';
                                    html+='<img src="'+value.profile_image+'">';
                                    //html+='<img src="quetion.png">';
                                    html+='</i>' ;
                                    html+=''+value.seat_no+'</p>'
                                    html+='</span>';
                                }
                            //html+='<span>'+value.seat_no+'</span>';
                            html+='<p>'+value.description+'</p>';
                            html+='</label>';
                        }
                        else{
                            html+='<label class="">';
                            html+='<input type="radio" value="'+value.seat_id+'"  name="seat_number">';
                            html+='<span>'+value.seat_no+'</span>';
                            html+='<p>'+value.description+'</p>';
                            html+='</label>';
                        }
                        html+='</div>';
                        // else if(value.book_status=='0' || value.book_status=='1' || value.book_status=='4'){


                        // }
                        // else{
                        //     html+='<label class="">';
                        //     html+='<input type="radio" value="'+value.seat_id+'"  name="seat_number">';
                        //     html+='<span>'+value.seat_no+'</span>';
                        //     html+='<p>'+value.description+'</p>';
                        //     html+='</label>';
                        // }
                    });
                }else{
                    html+='<h1>No seats in this office</h1>'
                }
                $('.seats-list').append(html);
            },

            'error' :  function(errors){
                console.log(errors);
            },
            complete: function () {

            },
        })

        $("#datepicker").on('change',function(){
            var reserve_date = $('#datepicker').datepicker({ dateFormat: 'dd-mm-yy' }).val();
            $('#datepicker').val(reserve_date);
            $.ajax({
                "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: base_url+'/get_seat_list',

                data: {
                    "reserve_date": reserve_date,
                    "office_id" : office_id,
                },
                'beforeSend': function() {

                },
                'success' : function(response){
                    console.log(response);
                    $('.seats-list').html('');
                    var html = '';
                    if(response.status){
                        console.log(response.data);
                        $('.total_seats').text('Total : '+response.count_data.total_seats);
                        $('.available_seats').text('Available : '+response.count_data.available_seats);
                        $('.booked_seats').text('Reserved : '+response.count_data.booked_seats);;
                        $('.blocked_seats').text('Blocked : '+response.count_data.blocked_seats);
                        $('.pending_seats').text('Pending : '+response.count_data.pending_seats);
                        $.each(response.data, function( key , value ) {
                            html+='<div class="single-seat">';
                            if(value.seat_type=='2'){
                                html+='<label class="booked">';
                                html+='<input type="radio" disabled value="'+value.seat_id+'"  name="seat_number">';
                                html+='<span>'+value.seat_no+'</span>';
                                html+='<p>'+value.description+'</p>';
                                html+='</label>';
                            }
                            else if(value.seat_reserve_status=='1')
                            {
                                if(value.book_status=='0'){
                                    add_class='pending';
                                }else{
                                    add_class='disabled';
                                }
                                if(value.tool=='1'){
                                    var tool='data-toggle="tooltip" title="I have booked!"';
                                }else{
                                    if(value.is_show_user_details=='0'){
                                        var tool='data-toggle="tooltip" title="Booked"';
                                    }else{
                                        var tool='data-toggle="tooltip" title="Booked by '+value.user_name+'"';
                                    }
                                    //var tool='data-toggle="tooltip" title="Booked by '+value.user_name+'"';

                                }
                                html+='<label '+tool+' class="'+add_class+'">';

                                html+='<input type="radio" disabled value="'+value.seat_id+'"  name="seat_number">';
                                    if(value.is_show_user_details=='0'){
                                        html+='<span>';
                                        html+='<p>';
                                        html+='<i>';
                                        html+='<img src="'+value.profile_image+'">';
                                        //html+='<img src="quetion.png">';
                                        html+='</i>' ;
                                        html+=''+value.seat_no+'</p>'
                                        html+='</span>';
                                    }else{
                                        html+='<span>';
                                        html+='<p>';
                                        html+='<i>';
                                        html+='<img src="'+value.profile_image+'">';
                                        //html+='<img src="quetion.png">';
                                        html+='</i>' ;
                                        html+=''+value.seat_no+'</p>'
                                        html+='</span>';
                                    }
                                //html+='<span>'+value.seat_no+'</span>';
                                html+='<p>'+value.description+'</p>';
                                html+='</label>';
                            }
                            else{
                                html+='<label class="">';
                                html+='<input type="radio" value="'+value.seat_id+'"  name="seat_number">';
                                html+='<span>'+value.seat_no+'</span>';
                                html+='<p>'+value.description+'</p>';
                                html+='</label>';
                            }
                            html+='</div>';
                        });
                    }else{
                        html+='<h1>No seats in this office</h1>'
                    }
                    $('.seats-list').append(html);
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
<script language="javascript">
    $(function() {
       $("#datepicker").datepicker({
            showOn: "button",
            buttonImage: "{{asset('front_end')}}/images/calendar.gif",
            buttonImageOnly: true,
            buttonText: "Select date",
            dateFormat: 'dd/mm/yy',
            changeYear: true,
            changeMonth: true,
            minDate:0,
            maxDate:13,
            yearRange: "-100:+20",

       });
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
</script>
@endpush
