@extends('layouts.app')
@section('content')
<!--banner-->
    <section class="banner">
        <div class="container">
            <div class="text">
            </div>
        </div>
    </section>
    <!--end-->

    <!--building office-->
    <section class="reaserve-seat">
        <div class="container">
            <div class="building-office-list">
                <div class="heading">
                    <h1>Assets List</h1>

                </div>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search Building or office" name="search_name" id="search_name">
                </div>
                <div class="row building-show">


                </div>
            </div>
        </div>
    </section><!--END building office assets-->
@endsection
@push('js')
    <script type="text/javascript">
        $(document).ready(function(){
            var office_id ='{{$data['assets']->office_id}}';
            console.log(office_id);
            $.ajax({
                "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: base_url+'/get_assets_list',

                data: {
                    "office_id" : office_id,
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
                            html+='<h2><span>Assets Name :</span>'+value.title+'</h2>';
                            html+='<p><span>Description :</span>'+value.description+'</p>';
                            html+='<a href="'+base_url+'/reserve_seat?assets_id='+value.id+'"> See All Seats</a>';
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
                    url: base_url+'/get_assets_list',

                    data: {
                        "office_id" : office_id,
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
                                    html+='<h2><span>Assets Name :</span>'+value.title+'</h2>';
                                    html+='<p><span>Description :</span>'+value.office_number+'</p>';
                                    html+='<a href="'+base_url+'/reserve_seat?assets_id='+value.id+'"> See All Seats</a>';
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
