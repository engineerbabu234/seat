@extends('layouts.app')
@section('content')
<!--banner-->
    <section class="banner">
        <div class="container">
            <div class="text">
                <h3>Seat Reservation System</h3>
                <p>Welcome to the Seat Reservation system where you can reserve a seat within your organisation.</p>
            </div>
        </div>
    </section>
    <!--end-->

    <!--building office-->
    <section class="reaserve-seat">
        <div class="container">
            <div class="building-office-list">
                <div class="heading">
                    <h1>Office List</h1>
                    <p>Below is a list of offices inside the selected building.</p>
                    <p>Click on one of the offices below to view the office assets associated inside.</p>
                </div>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search Building or office" name="search_name" id="search_name">
                </div>
                <div class="row building-show">


                </div>
            </div>
        </div>
    </section><!--END building office-->
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready(function(){
            var building_id ='{{$data['building']->building_id}}';
            console.log(building_id);
            $.ajax({
                "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: base_url+'/get_office_list',

                data: {
                    "building_id" : building_id,
                    "search_name" : '',
                },
                'beforeSend': function() {

                },
                'success' : function(response){
                    console.log(response);
                    var html = '';
                    if(response.status){
                        console.log(response.data);
                        $.each(response.data, function( key , value ) {
                            html+='<div class="col-md-6 col-xs-12 col-sm-12">';
                            html+='<div class="single-list">';
                            html+='<h2><span><img src="'+base_url+'/admin_assets/images/offices.png" alt="offices" class="bl-icon" width="30" ></span>'+value.office_name+'</h2>';
                            html+='<p><span><img src="'+base_url+'/admin_assets/images/number.png" alt="office number" class="bl-icon" width="20" style="width: 13px;" ></span>'+value.office_number+'</p>';
                            //html+='<a href="'+base_url+'/reserve_seat?office_id='+value.office_id+'"> See Office</a>';
                            html+='<a href="'+base_url+'/assets_list?office_id='+value.office_id+'"> See Assets</a>';
                            html+='</div>';
                            html+='</div>';

                        });

                    }else{
                        html+='<h1>Record not found</h1>';
                    }
                    $('.building-show').append(html);
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
                    url: base_url+'/get_office_list',

                    data: {
                        "building_id" : building_id,
                        "search_name": search_name,
                    },
                    'beforeSend': function() {

                    },
                    'success' : function(response){
                        console.log(response);
                        $('.building-show').html('');
                        var html='';
                        if(response.status){
                            console.log(response.data);
                            if(response.data){
                                $.each(response.data, function( key , value ) {
                                    html+='<div class="col-md-6 col-xs-12 col-sm-12">';
                                    html+='<div class="single-list">';
                                    html+='<h2><span><img src="'+base_url+'/admin_assets/images/offices.png" alt="offices" class="bl-icon" width="30" ></span>'+value.office_name+'</h2>';
                                    html+='<p><span><img src="'+base_url+'/admin_assets/images/number.png" alt="office number" class="bl-icon" width="20" style="width: 13px;" ></span>'+value.office_number+'</p>';
                                    //html+='<a href="'+base_url+'/reserve_seat?office_id='+value.office_id+'"> See Office</a>';
                                     html+='<a href="'+base_url+'/assets_list?office_id='+value.office_id+'"> See Assets</a>';
                                    html+='</div>';
                                    html+='</div>';

                                });
                            }else{
                               html+='<h1>Record not found</h1>';
                            }
                        }else{
                            html+='<h1>Record not found</h1>';
                        }
                        $('.building-show').append(html);
                    },

                    'error' :  function(errors){
                        console.log(errors);
                    },
                    complete: function () {

                    },
                })
            });
        });
    </script>
@endpush
