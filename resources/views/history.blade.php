@extends('layouts.app')
@section('content')
    <!--building office-->
    <section class="reaserve-seat reaserve-seat-page">
        <div class="container">
            <div class="building-office-list">
                <div class="heading">
                    <h1>Your Seat Booking History</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet, consectetur consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet, consectetur</p>
                </div>

                <div class="your-history">
                    <div class="custom-data-table">
                        <div class="data-table">
                            <div class="heading-search">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <h2>Total: {{$data['SeatCount']}}</h2>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="searchbar-wrapper">
                                            <div class="searchbar">
                                                <i class="fas fa-search"></i>
                                                <input type="text" name="search_name"  id="search_name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-table-height">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Reservation ID</th>
                                                <th>Building Name</th>
                                                <th>Office</th>
                                                <th>Employee Name </th>
                                                <th>Seat No.</th>
                                                <th>Date </th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-data">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section><!--END building office-->
@endsection
@push('js')
<script type="text/javascript">
    $(document).ready(function(){
        //var current_date='{{date('Y-m-d')}}';
        $.ajax({
            "headers":{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            method: 'get',
            url: base_url+'/get_history',

            data: {
                "search_name": '',
            },
            'beforeSend': function() {

            },
            'success' : function(response){
                //console.log(response);
                var html = '';
                if(response.status){
                    console.log(response.data);
                    $.each(response.data, function( key , value ) {
                        console.log('created_date===='+value.created_date);
                        console.log('current_date===='+value.current_date);
                        html+='<tr>';
                        html+='<td>'+value.reservation_id+'</td>';
                        html+='<td>'+value.building_name+'</td>';
                        html+='<td>'+value.office_name+'</td>';
                        html+='<td>'+value.user_name+'</td>';
                        html+='<td>'+value.seat_no+'</td>';
                        html+='<td>'+value.reserve_date+'</td>';
                        if(value.status=='0'){
                            html+='<td>';
                            html+='<span class="pending">Pending</span>';
                            html+='</td>';
                            html+='<td>';
                            
                            if(value.reserve_date!=value.current_date){
                                html+='<button id="cancel" class="cancel cancel-status" reserve_seat_id="'+value.reserve_seat_id+'">Cancel</button>';
                            }else{
                                html+='<button id="cancel" class="cancel">Pending</button>';
                            }
                           
                            html+='</td>';
                        }else if(value.status=='1'){
                            html+='<td>';
                            html+='<span class="accepeted">Accepted</span>';
                            html+='<p>(By Admin)</p>';
                            html+='</td>';
                            html+='<td>';
                            if(value.reserve_date!=value.current_date){
                                html+='<button id="cancel" class="cancel cancel-status" reserve_seat_id="'+value.reserve_seat_id+'">Cancel</button>';
                            }else{
                                html+='<button id="cancel" disabled>Cancel</button>';
                            }
                            // html+='<button id="cancel" class="cancel cancel-status" reserve_seat_id="'+value.reserve_seat_id+'">Cancel</button>';
                            html+='</td>';
                        }else if(value.status=='2'){
                            html+='<td>';
                            html+='<span class="rejected">Rejected</span>';
                            html+='<p>(By Admin)</p>';
                            html+='</td>';
                            html+='<td>';
                            html+='<button disabled >Cancel</button>';
                            html+='</td>';
                        }else if(value.status=='3'){
                            html+='<td>';
                            html+='<span class="canceled">Cancelled</span>';
                            html+='<p>(By You)</p>';
                            html+='</td>';
                            html+='<td>';
                            html+='<button disabled>Cancel</button>';
                            html+='</td>';
                        }else if(value.status=='4'){
                            html+='<td>';
                            html+='<span class="accepeted">Accepted</span>';
                            html+='<p>(Auto Approved)</p>';
                            html+='</td>';
                            html+='<td>';
                            if(value.reserve_date!=value.current_date){
                                html+='<button id="cancel" class="cancel cancel-status" reserve_seat_id="'+value.reserve_seat_id+'">Cancel</button>';
                            }else{
                                html+='<button disabled>Cancel</button>';
                            }
                            // html+='<button id="cancel" class="cancel cancel-status" reserve_seat_id="'+value.reserve_seat_id+'">Cancel</button>';
                            html+='</td>';
                        }
                        html+='</tr>';  
                    });
                }else{
                    html='<h1>Record not found</h1>'
                }
                $('#table-data').append(html); 
            },

            'error' :  function(errors){
                console.log(errors);
            },
            complete: function () {

            },
        })
        
        $('#search_name').on('keyup', function(e) {
            var search_name = $('#search_name').val();
            $.ajax({
                "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: base_url+'/get_history',

                data: {
                    "search_name": search_name,
                },
                'beforeSend': function() {

                },
                'success' : function(response){
                    console.log(response);
                    $('#table-data').html('');
                    var html='';
                    if(response.status){
                        console.log(response.data);
                        if(response.data){
                            $.each(response.data, function( key , value ) {
                                html+='<tr>';
                                html+='<td>'+value.reservation_id+'</td>';
                                html+='<td>'+value.building_name+'</td>';
                                html+='<td>'+value.office_name+'</td>';
                                html+='<td>'+value.user_name+'</td>';
                                html+='<td>'+value.seat_no+'</td>';
                                html+='<td>'+value.reserve_date+'</td>';
                                if(value.status=='0'){
                                    html+='<td>';
                                    html+='<span class="pending">Pending</span>';
                                    html+='</td>';
                                    html+='<td>';
                                    
                                    if(value.reserve_date!=value.current_date){
                                        html+='<button id="cancel" class="cancel cancel-status" reserve_seat_id="'+value.reserve_seat_id+'">Cancel</button>';
                                    }else{
                                        html+='<button id="cancel" class="cancel">Pending</button>';
                                    }
                                   
                                    html+='</td>';
                                }else if(value.status=='1'){
                                    html+='<td>';
                                    html+='<span class="accepeted">Accepted</span>';
                                    html+='<p>(By Admin)</p>';
                                    html+='</td>';
                                    html+='<td>';
                                    html+='<button id="cancel" class="cancel cancel-status" reserve_seat_id="'+value.reserve_seat_id+'">Cancel</button>';
                                    html+='</td>';
                                }else if(value.status=='2'){
                                    html+='<td>';
                                    html+='<span class="rejected">Rejected</span>';
                                    html+='<p>(By Admin)</p>';
                                    html+='</td>';
                                    html+='<td>';
                                    html+='<button disabled >Cancel</button>';
                                    html+='</td>';
                                }else if(value.status=='3'){
                                    html+='<td>';
                                    html+='<span class="canceled">Cancelled</span>';
                                    html+='<p>(By You)</p>';
                                    html+='</td>';
                                    html+='<td>';
                                    html+='<button disabled>Cancel</button>';
                                    html+='</td>';
                                }else if(value.status=='4'){
                                    html+='<td>';
                                    html+='<span class="accepeted">Accepted</span>';
                                    html+='<p>(Auto Approved)</p>';
                                    html+='</td>';
                                    html+='<td>';
                                    html+='<button id="cancel" class="cancel cancel-status" reserve_seat_id="'+value.reserve_seat_id+'">Cancel</button>';
                                    html+='</td>';
                                }
                                html+='</tr>';
                            });
                        }else{
                           html+='<h1>Record not found</h1>'; 
                        } 
                    }else{
                        html+='<h1>Record not found</h1>';
                    }
                    $('#table-data').append(html); 
                },

                'error' :  function(errors){
                    console.log(errors);
                },
                complete: function () {

                },
            })
        });

        $('body').on('click','.cancel-status', function() {
            var reserve_seat_id = $(this).attr("reserve_seat_id");
            var success_status='Cancelled';            
            path='reservation_status_change';

            $.ajax({
                "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                'type':'get',
                'url' :  base_url + '/' +path,
                'data' : {  reserve_seat_id : reserve_seat_id},
                'beforeSend': function() {

                },
                'success' : function(response){
                    if(response.status){
                        swal(success_status ,response.message, 'success');
                        location.reload();
                    }
                },
                'error' :  function(errors){
                    console.log(errors);
                },
                'complete': function() {

                }
            });
        });
    });
</script>
@endpush