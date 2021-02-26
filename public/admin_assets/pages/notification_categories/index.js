$(document).ready(function() {

         urls = base_url+'/admin/notification_categories/';

        var laravel_datatable =$('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            "ordering": false,
            destroy: true,
            ajax:urls ,

            columns: [
                { data: 'number_key', name: 'number_key' },
                { data: 'title', name: 'title' },
                { data: 'api_title', name: 'api_title' },
                { data: 'description', name: 'description' },
                { data: 'updated_at', name: 'updated_at' }, 
                
                { data: 'id', name: 'id' ,
                    render: function (data, type, column, meta) {
                        return '<a  href="#" data-id="'+column.id+'" class="button btn-wh edit_notification_categories_request" title="edit"><img src="'+base_url+'/admin_assets/images/edit.png"  class="white-img"></a>'+
                        '<button class="button btn-wh btn-notification_categories-delete" data-url="'+base_url+'/admin/notification_categories/delete/'+column.id+'" title="delete"><img src="'+base_url+'/admin_assets/images/delete.png"  class="white-img"></button>';
                    }
                }
            ]
        });



     // notification_categories remove confirmation modal
     $('body').on('click','.btn-notification_categories-delete',function(e){
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



$(document).on("click", ".add_notification_categories_data", function(e) {
    e.preventDefault();

    var data = jQuery(this).parents('form:first').serialize();

    $.ajax({
        url: base_url + '/admin/notification_categories/store',
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
                $("form#add-notification_categories-form")[0].reset();
              //  swal("Success!", response.message, "success");
                var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#add_notification_categories').modal('hide'); 
                 

            }
        },
    });
});



$(document).on("click", ".edit_notification_categories", function(e) {
    e.preventDefault();

    var data = jQuery(this).parents('form:first').serialize();
    var id = $(this).data('id');
    $.ajax({
        url: base_url + '/admin/notification_categories/update/'+id,
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
                $("form#edit-notification_categories-form")[0].reset();
                success_alert(response.message);
                 
                var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#edit_notification_categories').modal('hide');
            }
        },
    });
});


$(document).on("click", ".edit_notification_categories_request", function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    var aurls = base_url + "/admin/notification_categories/edit/" + id;
    jQuery.ajax({
        url: aurls,
        type: 'get',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {

            if (response.success) {
                $('#edit_notification_categories_info').html(response.html); 
                get_pophover(); 
                $('#edit_notification_categories').modal('show');

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
          text: "Once deleted, you will not be able to recover this Notification Categories data!",
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

 
$(document).on('click','.close_edit_notification_categories',function(){
   var redrawtable = jQuery('#laravel_datatable').dataTable();
    redrawtable.fnDraw(); 
    $('#edit_notification_categories').modal('hide'); 
});


 

$('.modal').css('overflow-y', 'auto');