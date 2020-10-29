@extends('layouts.app')
@section('content')
    <!--banner-->
    <section class="banner">
        <div class="container">
            <div class="text">
                <h3>Lorem Ipsum is simply Of the printing and typesetting</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet, consectetur</p>
            </div>  
        </div>
    </section>
    <!--end-->

    <!--building office-->
    <section class="reaserve-seat">
        <div class="container">
            <div class="building-office-list">
                <div class="heading">
                    <h1>Building List</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet, consectetur consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet, consectetur</p>
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
            
            $.ajax({
                "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                method: 'get',
                url: base_url+'/get_building',

                data: {
                    "search_name": '',
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
                            html+='<h2><span>Building:</span>'+value.building_name+'</h2>';
                            html+='<p><span>Address:</span>'+value.building_address+'</p>';
                            html+='<a href="'+base_url+'/office_list?building_id='+value.building_id+'"> See Offices</a>';
                            html+='</div>';
                            html+='</div>';
                            
                        });
                        $('.building-show').append(html);  
                    }else{
                        alert(response.message);
                    }
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
                    url: base_url+'/get_building',

                    data: {
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
                                    html+='<h2><span>Building:</span>'+value.building_name+'</h2>';
                                    html+='<p><span>Address:</span>'+value.building_address+'</p>';
                                    html+='<a href="'+base_url+'/office_list?building_id='+value.building_id+'"> See Offices</a>';
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