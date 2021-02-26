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
                     
                    var html = '';
                    if(response.status){
                        
                        $.each(response.data, function( key , value ) {
                            html+='<div class="col-md-4 col-xs-12 col-sm-12">';
                            html+='<div class="single-list"><div class="list-building">';
                            html+='<h2><span > <img src="'+base_url+'/admin_assets/images/building.png" alt="building" class="bl-icon" width="30" ></span>'+value.building_name+'</h2>';
                            html+='<p><span class="icon_color"><img src="'+base_url+'/admin_assets/images/address.png" alt="address" class="bl-icon" width="20" style="width: 13px;" ></span>'+value.building_address+'</p></div>';
                            html+='<div class="d-flex my-2"><a href="'+base_url+'/office_list?building_id='+value.building_id+'"> See Offices</a></div>';
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
                        
                        $('.building-show').html('');
                        var html='';
                        if(response.status){
                           
                            if(response.data){
                                $.each(response.data, function( key , value ) {
                                    html+='<div class="col-md-4 col-xs-12 col-sm-12">';
                                    html+='<div class="single-list"><div calss="list-building">';
                                    html+='<h2><span><img src="'+base_url+'/admin_assets/images/building.png" alt="building" class="bl-icon" width="30" ></span>'+value.building_name+'</h2>';
                                    html+='<p><span><img src="'+base_url+'/admin_assets/images/address.png" alt="address" class="bl-icon" width="20" style="width: 13px;" ></span>'+value.building_address+'</p></div>';
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


        $('#building_id').on('change', function(event) {
            event.preventDefault();
            var building_id = $(this).val();
            if(building_id){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/filter_office_list/"+building_id,

                   success:function(res)
                   {    
                      
                        if(res.data)
                        {
                            $("#office_id").empty();
                            $("#office_id").append("<option value=''>All</option>");
                          
                            $("#office_asset_id").append("<option value=''>All</option>");
                            $.each(res.data,function(key,value){
                                $("#office_id").append("<option value="+value.office_id+">"+value.office_name+"</option>");
                            });
                        }
                   }

                });
            } else {
                $("#office_id").empty(); 
                $("#office_id").append("<option value=''>All</option>");
                 $("#office_asset_id").empty(); 
               $("#office_asset_id").append("<option value=''>All</option>");
            }
        });

          $('#office_id').on('change', function(event) {
            event.preventDefault();

            var office_id = $(this).val();
            if(office_id && office_id != 'All'){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/filter_office_assets_list/"+office_id,

                   success:function(res)
                   {   
                        if(res.data)
                        {
                             $("#office_asset_id").empty(); 
                            $("#office_asset_id").append("<option value=''>All</option>");
                            $.each(res.data,function(key,value){
                                $("#office_asset_id").append("<option value="+value.id+">"+value.title+"</option>");
                            });
                        }
                   }

                });
            } else{ 
                 
                $("#office_asset_id").empty(); 
                  $("#office_asset_id").append("<option value=''>All</option>");
            }
        });


        $('#office_asset_id').on('change', function(event) {
            event.preventDefault();
            var office_assets_id = $(this).val();
            if(office_assets_id){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/filter_office_assets_info/"+office_assets_id,

                   success:function(res)
                   {
                        if(res.data)
                        {
                            $('#bookdate').val(res.data.book_within-1);
                        }
                   }

                });
            } 
        });

         $('#office_asset_id').on('change', function(event) {
            event.preventDefault();
            var office_assets_id = $(this).val();
            if(office_assets_id){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/filter_office_assets_type_info/"+office_assets_id,

                   success:function(res)
                   {
                        if(res.data)
                        {
                            $("#office_asset_type_id").empty(); 
                            $("#office_asset_type_id").append("<option value=''>All</option>");
                            $.each(res.data,function(key,value){
                                $("#office_asset_type_id").append("<option value="+key+">"+value+"</option>");
                            });
                        }
                   }

                });
            } 
        });

        $("#search_booking_date").focusin(function () {
            let bookdatea = $('#bookdate').val();
                 $('#search_booking_date').datepicker('destroy');
                $("#search_booking_date").datepicker({
                    dateFormat: 'dd-mm-yy',
                    changeYear: true,
                    changeMonth: true,
                    minDate:0,
                    maxDate:bookdatea,
                    yearRange: "-100:+20",
                });
            });


         $('#result_table').hide();
        $('#searchbtn').on('click', function(event) {

            get_pophover();
            var formdata = jQuery(this).parents('form:first').serialize();

                $('#result_table').show(); 

                laravel_datatable = $('#laravel_datatable').dataTable({
                "bProcessing": false,
                "bServerSide": true,
                "bStateSave": true,
                "autoWidth": false,
                "destroy": true,
                 "ordering": false,
                "sAjaxSource": base_url+"/search_seat/",
                "fnServerParams": function(aoData) {
                    var building_id =  $('#building_id option:selected').val();
                    var office_id =  $('#office_id option:selected').val();
                    var office_asset_id = $('#office_asset_id option:selected').val();
                    var office_asset_type_id = $('#office_asset_type_id option:selected').val();
                    var search_booking_date = $('#search_booking_date').val();
                    var seat_status =  $('#seat_status option:selected').val();
                    var privacy = $('#privacy option:selected').val();
                    var monitor = ( $("#monitor").is(':checked') ) ? 1 : 0;
                    var dokingstation = ( $("#dokingstation").is(':checked') ) ? 1 : 0;
                    var privatespace = ( $("#privatespace").is(':checked') ) ? 1 : 0;
                    var adjustableheight = ( $("#adjustableheight").is(':checked') ) ? 1 : 0;
                    var wheelchair = ( $("#wheelchair").is(':checked') ) ? 1 : 0;
                    var usbcharger =  ( $("#usbcharger").is(':checked') ) ? 1 : 0;

                    var underground =  ( $("#underground").is(':checked') ) ? 1 : 0;
                    var pole_information =  ( $("#pole_information").is(':checked') ) ? 1 : 0;
                    var wheelchair_accessable =  ( $("#wheelchair_accessable").is(':checked') ) ? 1 : 0;
                    var parking_difficulty =  $('#parking_difficulty option:selected').val();

                    var whiteboard_avaialble =  ( $("#whiteboard_avaialble").is(':checked') ) ? 1 : 0;
                    var teleconference_screen =  ( $("#teleconference_screen").is(':checked') ) ? 1 : 0;
                    var is_white_board_interactive =  ( $("#is_white_board_interactive").is(':checked') ) ? 1 : 0;
                 
                    var kanban_board =  ( $("#kanban_board").is(':checked') ) ? 1 : 0;
                    var whiteboard =  ( $("#whiteboard").is(':checked') ) ? 1 : 0;
                    var interactive_whiteboard =  ( $("#interactive_whiteboard").is(':checked') ) ? 1 : 0;
                    var standing_only =  ( $("#standing_only").is(':checked') ) ? 1 : 0;
                    var telecomference_screen =  ( $("#telecomference_screen").is(':checked') ) ? 1 : 0;
                    var meeting_indicator_mounted_on_wall =  ( $("#meeting_indicator_mounted_on_wall").is(':checked') ) ? 1 : 0;
                    
                    aoData.push({
                        "name": "building_id",
                        "value": building_id
                    },  {
                        "name": "office_id",
                        "value": office_id
                    }, {
                        "name": "office_asset_id",
                        "value": office_asset_id
                    }, {
                        "name": "office_asset_type_id",
                        "value": office_asset_type_id
                    }, {
                        "name": "search_booking_date",
                        "value": search_booking_date
                    }, {
                        "name": "seat_status",
                        "value": seat_status
                    }, {
                        "name": "privacy",
                        "value": privacy
                    }, {
                        "name": "monitor",
                        "value": monitor
                    }, {
                        "name": "dokingstation",
                        "value": dokingstation
                    }, {
                        "name": "privatespace",
                        "value": privatespace
                    }, {
                        "name": "adjustableheight",
                        "value": adjustableheight
                    }, {
                        "name": "wheelchair",
                        "value": wheelchair
                    }, {
                        "name": "usbcharger",
                        "value": usbcharger
                    }, {
                        "name": "underground",
                        "value": underground
                    }, {
                        "name": "pole_information",
                        "value": pole_information
                    }, {
                        "name": "wheelchair_accessable",
                        "value": wheelchair_accessable
                    }, {
                        "name": "parking_difficulty",
                        "value": parking_difficulty
                    }, {
                        "name": "whiteboard_avaialble",
                        "value": whiteboard_avaialble
                    }, {
                        "name": "teleconference_screen",
                        "value": teleconference_screen
                    }, {
                        "name": "is_white_board_interactive",
                        "value": is_white_board_interactive
                    }, {
                        "name": "kanban_board",
                        "value": kanban_board
                    }, {
                        "name": "whiteboard",
                        "value": whiteboard
                    }, {
                        "name": "interactive_whiteboard",
                        "value": interactive_whiteboard
                    }, {
                        "name": "standing_only",
                        "value": standing_only
                    }, {
                        "name": "telecomference_screen",
                        "value": telecomference_screen
                    }, {
                        "name": "meeting_indicator_mounted_on_wall",
                        "value": meeting_indicator_mounted_on_wall
                    });
                 },
                "sPaginationType": "numbers",
                "oLanguage": {
                "sLengthMenu": "Display _MENU_ records"
                },
                "aoColumns": [
                { "mData": "building_name",bSortable: true,sWidth: "15%"},
                { "mData": "office_name",bSortable: true,sWidth: "10%"},
                { "mData": "title",bSortable: true,sWidth: "10%"},
                {
                    "Data": "seat_attribute",
                    Width: "10%",
                    Class: "text-center",
                    mRender: function(v, t, o) {
                         let icons = '';
                        if (o.asset_type == 1) {
                            if (o.monitor == 1) {
                                icons =  '<img src="'+base_url+'/admin_assets/images/monitor.png" class="bl-icon pb-1  iconSize_32" width="30" > ';
                            }
                            if (o.dokingstation == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/dokingstation.png" class="bl-icon pb-1 " width="30" > ';
                            }

                            if (o.adjustableheight == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/adjustableheight.png" class="bl-icon pb-1 " width="30" > ';
                            }
                            if (o.privatespace == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/privatespace.png" class="bl-icon pb-1 " width="30" > ';
                            }
                              if (o.wheelchair == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/wheelchair.png" class="bl-icon pb-1 " width="30" > ';
                            }
                              if (o.usbcharger == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/usbcharger.png" class="bl-icon pb-1 " width="30" > ';
                            }

                        } else if (o.asset_type == 2) {
                            if (o.underground == 1) {
                                icons +=  '<img src="'+base_url+'/admin_assets/images/underground.png" class="bl-icon pb-1 " width="30" > ';
                            }
                            if (o.pole_information == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/pole_information.png" class="bl-icon pb-1 " width="30" > ';
                            }

                            if (o.wheelchair_accessable == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/wheelchair_accessable.png" class="bl-icon pb-1 " width="30" > ';
                            } 

                        }else if (o.asset_type == 3) { 

                            if (o.kanban_board == 1) {
                                icons +=  '<img src="'+base_url+'/admin_assets/images/kanban_board.png" class="bl-icon pb-1 " width="30" > ';
                            }
                            
                            if (o.whiteboard == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/whiteboard.png" class="bl-icon pb-1 " width="30" > ';
                            }

                            if (o.interactive_whiteboard == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/whiteboard_interactive.png" class="bl-icon pb-1 " width="30" > ';
                            }
                            if (o.standing_only == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/standing_only.png" class="bl-icon pb-1 " width="30" > ';
                            } 

                        }  else if (o.asset_type == 4) { 

                            if (o.whiteboard_avaialble == 1) {
                                icons +=  '<img src="'+base_url+'/admin_assets/images/whiteboard.png" class="bl-icon pb-1 " width="30" > ';
                            }

                            if (o.teleconference_screen == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/teleconference_screen.png" class="bl-icon pb-1 " width="30" > ';
                            }

                            if (o.is_white_board_interactive == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/whiteboard_interactive.png" class="bl-icon pb-1 " width="30" > ';
                            }
                            if (o.meeting_indicator_mounted_on_wall == 1) {
                                icons += '<img src="'+base_url+'/admin_assets/images/meeting_indicator_mounted_on_wall.png" class="bl-icon pb-1 " width="30" > ';
                            } 

                        } 

                        return icons;
                    },
                },
                { "mData": "seat_number",bSortable: true,sWidth: "5%"},
                { "mData": "status",bSortable: true,sWidth: "10%"},
                { "mData": "booking_mode",bSortable: true,sWidth: "10%"},
                 {
                    "Data": "asset_type",
                    Width: "10%",
                    Class: "text-center",
                    mRender: function(v, t, o) {
                         let icons = '';
                        if (o.asset_type == 1) {
                            icons =  '<img src="'+base_url+'/admin_assets/images/desks.png" class="bl-icon" width="30" > ';
                        }
                        if (o.asset_type == 2) {
                            icons += '<img src="'+base_url+'/admin_assets/images/carparking.png" class="bl-icon" width="30" > ';
                        }

                        if (o.asset_type == 3) {
                            icons += '<img src="'+base_url+'/admin_assets/images/colobration.png" class="bl-icon" width="30" > ';
                        }

                        if (o.asset_type == 4) {
                            icons += '<img src="'+base_url+'/admin_assets/images/meetings.png" class="bl-icon" width="30" > ';
                        }
                           
                        return icons;
                    },
                },
                 {
                    "Data": "total_quesionaire",
                    Width: "5%",
                    Class: "text-center",
                    mRender: function(v, t, o) {
                         let questionaries = '';
                        if (o.total_quesionaire) {
                            questionaries =  '<span data-assets="'+o.office_asset_id+'"   title="Questionaries Atteched"  class="btn btn-link user_questionaries">'+o.total_quesionaire+'</span>';
                        }
                       
                        return questionaries;
                    },
                },{
                    "Data": "contract_total",
                    Width: "5%",
                    Class: "text-center",
                    mRender: function(v, t, o) {
                         let contract = '';
                        if (o.contract_total) {
                            contract =  '<span data-assets="'+o.office_asset_id+'"   title="Contract Sign"     class="btn btn-link   user_contract">'+o.contract_total+'</span>';
                        }
                       
                        return contract;
                    },
                },
                { "mData": "viewseat",bSortable: true,sWidth: "20%"},

                ],
                fnPreDrawCallback: function() {
                },
                fnDrawCallback: function(oSettings) {
                     
                     $('.user_questionaries').popover({
                        html: true,
                        trigger: 'manual',
                        content: function() {
                          
                         var assets =  $(this).data('assets'); 
                          return $.ajax({url: base_url+"/get_user_questionarie_result/",
                                        "headers":{
                                                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                                        },
                                        data:{'asset_id':assets,'date':$('#search_booking_date').val()},
                                         type:"post",
                                         dataType: 'html',
                                         async: false}).responseText;
                        }
                      }).click(function(e) {
                        $(this).popover('toggle');
                      });

                 
                     $('.user_contract').popover({
                        html: true,
                        trigger: 'manual',
                        content: function() {
                         var assets =  $(this).data('assets');       
                          return $.ajax({url: base_url+"/get_user_contract_sign_result/"+assets,
                                         dataType: 'html',
                                         async: false}).responseText;
                        }
                      }).click(function(e) {
                        $(this).popover('toggle');
                      });
   
 
                },
                });


        });

        $('[data-toggle="tooltip"]').tooltip();

         $('#modes').hide();
         $('#questionarie_pophover').hide();
         $('#seat_attribute_pophover').hide();
         $('#status_pophover').hide();
          

         $('#building_popup').hide();
         $('#office_popup').hide();
         $('#office_assets_popup').hide();
         $('#seat_popup').hide();

        $('#building_info').popover({
            content:  $('#building_popup').html(),  
            placement: 'left',
            html: true
        });

        $('#tbl_building_info').popover({
            content:  $('#building_popup').html(),  
            placement: 'left',
            html: true
        });

        $('#office_info').popover({
            content:  $('#office_popup').html(),  
            placement: 'left',
            html: true
        });

        $('#tbl_office_info').popover({
            content:  $('#office_popup').html(),  
            placement: 'left',
            html: true
        });

        $('#office_assets_info').popover({
            content:  $('#office_assets_popup').html(),  
            placement: 'left',
            html: true
        });

        $('#tbl_office_assets_info').popover({
            content:  $('#office_assets_popup').html(),  
            placement: 'left',
            html: true
        });

        $('#seat_info').popover({
            content:  $('#seat_popup').html(),  
            placement: 'left',
            html: true
        });


         $('#bookingmode').popover({
                content:  $('#modes').html(),  
            placement: 'left',
            html: true
        });

         $('#questionarie_hover').popover({
            content:  $('#questionarie_pophover').html(),  
            placement: 'left',
            html: true
        });



         $('#seat_attribute_hover').popover({
            content:  $('#seat_attribute_pophover').html(),  
            placement: 'left',
            html: true
        });

          $('#status_pop').popover({
            content:  $('#status_pophover').html(),  
            placement: 'left',
            html: true
        });

        $('#main_status_pop').popover({
            content:  $('#status_pophover').html(),  
            placement: 'left',
            html: true
        });

        $('#assets_type_pophover').hide();
        $('#assets_type').popover({ 
            content:  $('#assets_type_pophover').html(),  
            placement: 'left',
            html: true 
        });

         $('#tbl_assets_type').popover({ 
            content:  $('#assets_type_pophover').html(),  
            placement: 'left',
            html: true 
        });
        
       

        $('#contract_pophover').hide();
         $('#contract_hover_info').popover({
            content:  $('#contract_pophover').html(),  
            placement: 'left',
            html: true
        });

        
     $('#office_asset_type_id').on('change', function(event) {
         event.preventDefault();  
         var assets_type_id = jQuery(this).val();  
         if(assets_type_id == 1){
            $('.deskattributes').show();
            $('.carparkspace').hide();
            $('.meetingspace').hide();
            $('.collaborationspace').hide();
         } else if(assets_type_id == 2){
            $('.deskattributes').hide();
            $('.carparkspace').show();
            $('.meetingspace').hide();
            $('.collaborationspace').hide();
         } else if(assets_type_id == 3){
            $('.deskattributes').hide();
            $('.carparkspace').hide();
            $('.meetingspace').hide();
            $('.collaborationspace').show();
         } else{
            $('.deskattributes').show();
            $('.carparkspace').show();
            $('.meetingspace').show();
            $('.collaborationspace').show();
         }
     });
 