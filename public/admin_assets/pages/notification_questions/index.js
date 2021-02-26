$(document).ready(function() {

         urls = base_url+'/admin/notification_questions/';

        var laravel_datatable =$('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            "ordering": false,
            destroy: true,
            ajax:urls ,

            columns: [
                { data: 'number_key', name: 'number_key' },
                { data: 'question', name: 'question' },
                { data: 'description', name: 'description' },
                { data: 'user_type', name: 'user_type' },
                { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
                { data: 'repeat_value', name: 'repeat_value' },
                { data: 'ans_type', name: 'ans_type' },
                { data: 'updated_at', name: 'updated_at' }, 
                { data: 'id', name: 'id' ,
                    render: function (data, type, column, meta) {
                        return '<a  href="#" data-id="'+column.id+'" class="button btn-wh edit_notification_questions_request" title="edit"><img src="'+base_url+'/admin_assets/images/edit.png"  class="white-img"></a>'+
                        '<button class="button btn-wh btn-notification_questions-delete" data-url="'+base_url+'/admin/notification_questions/delete/'+column.id+'" title="delete"><img src="'+base_url+'/admin_assets/images/delete.png"  class="white-img"></button>';
                    }
                }
            ]
        });



     // notification_questions remove confirmation modal
     $('body').on('click','.btn-notification_questions-delete',function(e){
          var url = $(this).attr('data-url');
         swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this notification categories data!",
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
                    'type':'get',
                    'url' : url,
                beforeSend: function() {
                },
                'success' : function(response){  
                        if(response.success){
                          $('#warning_modal').show();
                          $('#warning_modal_text').html(response.html);
                          $('#warning_modal').data('bs.modal',null);
                          $('#warning_modal').modal({backdrop:'static', keyboard:false});
                          $('.warning_modal_close').hide();
                        }
                },
                'error' : function(error){
                    if(error.responseJSON.exist_inassets){
                          $('#error_modal').show();
                          $('#error_modal_text').html(error.responseJSON.html);
                          $('#error_modal').data('bs.modal',null);
                          $('#error_modal').modal({backdrop:'static', keyboard:false});
                    }
                },
                complete: function() {
                    var redrawtable = jQuery('#laravel_datatable').dataTable();
                    redrawtable.fnDraw();
                },
                });
         });
     });
 

});



$(document).on("click", ".add_notification_question_data", function(e) {
    e.preventDefault();

    $('.error').removeClass('text-danger');
    $('.error').text('');
    var data = jQuery(this).parents('form:first').serialize();

    $.ajax({
        url: base_url + '/admin/notification_questions/store',
        type: 'post',
        dataType: 'json',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(response) {
            if (response.status == 400) {
                $.each(response.responseJSON.errors, function(k, v) {
                    $('#' + k + '_error').text(v);
                    $('#' + k + '_error').addClass('text-danger');
                });
            }
        },
        success: function(response) {
            if (response.success) {
                $("form#add-notification_question-form")[0].reset();
              //  swal("Success!", response.message, "success");
                var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#add_notification_question').modal('hide'); 
                 

            }
        },
    });
});



$(document).on("click", ".edit_notification_question", function(e) {
    e.preventDefault();
     $('.error').removeClass('text-danger');
    $('.error').text('');
    var data = jQuery(this).parents('form:first').serialize();
    var id = $(this).data('id');
    $.ajax({
        url: base_url + '/admin/notification_questions/update/'+id,
        type: 'post',
        dataType: 'json',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(response) {
            if (response.status == 400) {
                $.each(response.responseJSON.errors, function(k, v) {
                    $('#edit_' + k + '_error').text(v);
                    $('#edit_' + k + '_error').addClass('text-danger');
                });
            }
        },
        success: function(response) {
            if (response.success) {
                $("form#edit-notification_questions-form")[0].reset();
                success_alert(response.message);
                 
                var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#edit_notification_question').modal('hide');
            }
        },
    });
});


$(document).on("click", ".edit_notification_questions_request", function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    var aurls = base_url + "/admin/notification_questions/edit/" + id;
    jQuery.ajax({
        url: aurls,
        type: 'get',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {

            if (response.success) {
                $('#edit_notification_question_info').html(response.html); 
                get_pophover(); 
                $('#edit_notification_question').modal('show');
                add_new_rows();
                 answer_type();
                if(response.ans_type == 1 || response.ans_type == 2  ){

                    $('.view_answare').show();
                } else{
                    $('.view_answare').hide();
                }

                 
                $(document).on('change',"#repeat",function(){
                    var repeat = $(this).find("option:selected");
                     if(repeat.val() == 1){

                        $('.repeat_options').show();
                    } else {
                         $('.repeat_options').hide();
                    }
                });

            }
        },
    });
});
 
 
    $(function(e){

     //   remove confirmation modal
     $('body').on('click','.btn-delete',function(e){
          var url = $(this).attr('data-url');
         swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this Notification Question data!",
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
                    'type':'get',
                    'url' : url,
                beforeSend: function() {
                },
                'success' : function(response){
                    if(response.status == 'success'){
                        success_alert(response.message);
                        
                        var redrawtable = jQuery('#laravel_datatable_question').dataTable();
                        redrawtable.fnDraw();
                    }
                    if(response.status == 'failed'){
                        error_alert(response.message);
                        
                    }
                },
                'error' : function(error){
                },
                complete: function() {
                    var redrawtable = jQuery('#laravel_datatable_question').dataTable();
                        redrawtable.fnDraw();

                },
                });
         });
     });

    })

 
$(document).on('click','.close_edit_notification_questions',function(){
   var redrawtable = jQuery('#laravel_datatable').dataTable();
    redrawtable.fnDraw(); 

    $('#edit_notification_question').modal('hide'); 
});
 

$('.modal').css('overflow-y', 'auto');
 

  function add_new_rows(){ 
       
        $('.admore-fields').fieldsaddmore({
            min:($('.fieldsaddmore-row').length>0)?0:1,
        }); 
         
    }

         
    $('.view_answare').hide(); 



answer_type();

 function answer_type(){
    $('.ans_type').on('change', function(event) {
        event.preventDefault();
        var ans_type = jQuery(this).val();

            if(ans_type == 1 || ans_type == 2  ){ 
                  $('.admore-fields').html('');    
                $('.view_answare').show();
                add_new_rows();
            } else{

                $('.view_answare').hide();
            }
    });
 }

   

 $('.repeat_options').hide();
$(document).on('change',"#repeat",function(){
        var repeat = $(this).find("option:selected");
         if(repeat.val() == 1){

            $('.repeat_options').show();
        } else {
             $('.repeat_options').hide();
        }

});