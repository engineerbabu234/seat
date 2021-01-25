$(document).ready(function(){ 

        $('#building_id').on('change', function(event) {
            event.preventDefault();
            var building_id = $(this).val();
            if(building_id){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/seatrequest/filter_office_list/"+building_id,

                   success:function(res)
                   {
                        if(res.data)
                        {
                            $("#office_id").empty();
                             $("#office_id").append("<option value=''>Select Office</option>");
                            $.each(res.data,function(key,value){
                                $("#office_id").append("<option value="+value.office_id+">"+value.office_name+"</option>");
                            });
                        }
                   }

                });
            } else{
            	 $("#office_asset_id").empty();
                 $("#office_asset_id").append("<option value=''>Select Office Assets</option>");
                 $("#office_id").empty();
            	$("#office_id").append("<option value=''>Select Office</option>");
            	$("#seat_id").empty();
	            $("#seat_id").append("<option value=''>Select Seat</option>");
            }
        });

        $('#office_id').on('change', function(event) {
            event.preventDefault();
            var office_id = $(this).val();
            if(office_id){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/seatrequest/filter_office_assets_list/"+office_id,

                   success:function(res)
                   {
                        if(res.data)
                        {
                            $("#office_asset_id").empty();
                               $("#office_asset_id").append("<option value=''>Select Office Assets</option>");
                            $.each(res.data,function(key,value){
                                $("#office_asset_id").append("<option value="+value.id+">"+value.title+"</option>");
                            });
                        }
                   }

                });
            } else{
            	$("#office_asset_id").empty();
            	$("#office_asset_id").append("<option value=''>Select Office Assets</option>");
            	$("#seat_id").empty();
	            $("#seat_id").append("<option value=''>Select Seat</option>");
            }
        });

        $('.confirm_code').attr('disabled', true);
        $('#office_asset_id').on('change', function(event) {
            event.preventDefault();
            var office_assets_id = $(this).val();
            if(office_assets_id){
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                   url:base_url+"/seatrequest/filter_seat_list/"+office_assets_id, 
                   success:function(res)
                   {

                        if(res.data)
                        {
                         
	                        $("#seat_id").empty();
	                          $("#seat_id").append("<option value=''>Select Seat</option>");
	                            $.each(res.data,function(key,value){
	                            $("#seat_id").append("<option value="+value.seat_id+">"+value.seat_no+"</option>");
	                        });
                        }
                   }

                });
            } 
        });

        $('#seat_id').on('change', function(event) {

                var seat_id = $(this).val();
                if(seat_id){
                   $('.confirm_code').attr('disabled', false);

                   setTimeout(function() {
                      $('.confirm_code').attr('data-toggle', 'tooltip');
                      $('.confirm_code').attr('data-placement', 'right');
                      $('.confirm_code').attr("data-title","You can only confirm a seat that is selected in from above, if the seat number is missing, then that seat needs a label deployed by your admin");
                      $('.confirm_code').tooltip();
                   }, 3000);
                 }
           });
       

          $('.confirm_code').on('click', function(event) {
            event.preventDefault();  
              var form = $(this).parents('form');
             var data = jQuery(this).parents('form:first').serialize();
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"post",
                    dataType: 'json',
                    data: data,
                    url:base_url+"/seatrequest/challenge",  
                    error: function(response) {


                        if (response.status == 400) {
                             
                            if(response.responseJSON.error == true){
                              $('#error_modal').show();
                              $('#error_modal').modal({ backdrop: 'static', keyboard: false});
                              $('#error_modal_text').html(response.responseJSON.html);
                            }

                             if(response.responseJSON.warning){
                                $('#warning_modal').show();
                                $('#warning_modal').modal({ backdrop: 'static', keyboard: false});
                                $('#warning_modal_text').html(response.responseJSON.html);
                            }
  
                            $.each(response.responseJSON.errors, function(k, v) {
                                $('#' + k + '_error').text(v);
                                $('#' + k + '_error').addClass('text-danger');
                            });

                        }
                       
                  }, 
                   success:function(res)
                   {    
                        $('.error').removeClass('text-danger');
                            $('.error').text('');   
                        if(res.success){ 
                         // form.submit();
                          $('#page_request').html(res.html);
                        }
                   }

                });
            
        });



        $('body').on('click','.upload_code',function(e){
          e.preventDefault();
          var form = $(this).parents('form');
           swal({
            title: "Are you sure?",
            text: "do you want to upload Code!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
           })
           .then((willDelete) => {
              if(!willDelete){
                  return false;
              }
              var data = jQuery(this).parents('form:first').serialize();
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"post",
                    dataType: 'json',
                    data: data,
                    url:base_url+"/seatrequest/upload_code",  
                    error: function(response) { 
                       
                  }, 
                   success:function(res)
                   {     
                        if(res.success){  
                          $('.code_uploaded').html(res.html);
                        }
                   }

                });
                
             
           });
       });


          $('body').on('click','#seat_request_book_seat',function(e){
          e.preventDefault();
          var form = $(this).parents('form');
           swal({
            title: "Are you sure?",
            text: "do you want to Book Seat!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
           })
           .then((willDelete) => {
              if(!willDelete){
                  return false;
              }
              var data = jQuery(this).parents('form:first').serialize();
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"post",
                    dataType: 'json',
                    data: data,
                    url:base_url+"/seatrequest/newseat_booking",  
                    error: function(response) { 
                       
                  }, 
                   success:function(res)
                   {     
                        if(res.success){  
                           
                        }
                   }

                });
                
             
           });
       });


     $('body').on('click','#user_checkin',function(e){
          e.preventDefault();
        var id = $(this).data('id');
         swal({
          title: "Are you sure?",
          text: "do you want to checkin!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
         })
         .then((willDelete) => {
            if(!willDelete){
                return false;
            }
              var data = jQuery(this).parents('form:first').serialize();
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                    dataType: 'json',
                    data: data,
                    url:base_url+"/seatrequest/checkin/"+id,  
                    error: function(response) {
                        if (response.status == 400) {
                            $.each(response.responseJSON.errors, function(k, v) {
                                $('#' + k + '_error').text(v);
                                $('#' + k + '_error').addClass('text-danger');
                            });

                        }
                       
                  }, 
                   success:function(res)
                   {    

                        if(res.time){ 
                           $('#user_checkin').hide();
                           $('.checkintime').html('<span class="text-success">You Have Successfull Checked In!</span>');

                          $('#success_modal').show();
                          $('#success_modal').modal({ backdrop: 'static', keyboard: false});
                          $('#success_modal_text').html(res.html);

                        } 
                        
                   }

                });
         });
     });

      $('body').on('click','#user_checkout',function(e){
          e.preventDefault();
        var id = $(this).data('id');
         swal({
          title: "Are you sure?",
          text: "do you want to Check Out!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
         })
         .then((willDelete) => {
            if(!willDelete){
                return false;
            }
              var data = jQuery(this).parents('form:first').serialize();
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"get",
                    dataType: 'json',
                    data: data,
                    url:base_url+"/seatrequest/checkout/"+id,  
                    error: function(response) {
                        if (response.status == 400) {
                            $.each(response.responseJSON.errors, function(k, v) {
                                $('#' + k + '_error').text(v);
                                $('#' + k + '_error').addClass('text-danger');
                            });

                        }
                       
                  }, 
                   success:function(res)
                   {    

                        if(res.time){
                           $('.error').removeClass('text-danger');
                            $('.error').text('');   
                           $('#user_checkout').hide();
                           $('.checkouttime').html('<span class="text-success">You Have Successfull Checked Out!</span>');

                          $('#success_modal').show();
                          $('#success_modal').modal({ backdrop: 'static', keyboard: false});
                          $('#success_modal_text').html(res.html);
                        } 
                        
                   }

                });
         });
     });

       $('body').on('click','#user_seatclean',function(e){
          e.preventDefault();
        var id = $(this).data('id');
         swal({
          title: "Are you sure?",
          text: "do you want to Seat Clean Seat!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
         })
         .then((willDelete) => {
            if(!willDelete){
                return false;
            }
              var data = jQuery(this).parents('form:first').serialize();
                $.ajax({
                     "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                   type:"post",
                    dataType: 'json',
                    data: data,
                    url:base_url+"/seatrequest/cleanseat/"+id,  
                    error: function(response) {
                        if (response.status == 400) {
                            $.each(response.responseJSON.errors, function(k, v) {
                                $('#' + k + '_error').text(v);
                                $('#' + k + '_error').addClass('text-danger');
                            });

                        }
                       
                  }, 
                   success:function(res)
                   {    
                        if(res.time){ 
                          $('.error').removeClass('text-danger');
                          $('.error').text('');  

                          $('#user_seatclean').hide();
                          $('.clean_text').hide();
                          $('.seatcleantime').html('<span class="text-success">You Have Successfull Cleaned Seat!</span>');

                          $('#success_modal').show();
                          $('#success_modal').modal({ backdrop: 'static', keyboard: false});
                          $('#success_modal_text').html(res.html);
                        } 
                        
                   }

                });
         });
     });

   
});