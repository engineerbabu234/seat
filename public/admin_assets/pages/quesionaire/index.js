$(document).ready(function() {
         urls = base_url+'/admin/quesionaire/';

        var laravel_datatable =$('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            "ordering": false,
            destroy: true,
            ajax:urls ,

            columns: [
                { data: 'number_key', name: 'number_key' },
                { data: 'title', name: 'title' },
                { data: 'description', name: 'description' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'expired_option', name: 'expired_option' },
                { data: 'restriction', name: 'restriction' },
                { data: 'id', name: 'id',
                    render: function (data, type, column, meta) {
                        if(column.questions > 0 ){
                        return  column.questions;
                            //return '<a href="'+base_url+'/admin/question/'+column.id+'" target="_blank" class="button accept">'+column.questions+'</a>';
                        } else{
                            return  column.questions;
                        return '<a href="#"  class="button accept">'+column.questions+'</a>';
                        }
                    } },
                { data: 'id', name: 'id' ,
                    render: function (data, type, column, meta) {
                        return '<a  href="#" data-id="'+column.id+'" class="button btn-wh edit_quesionaire_request" title="edit"><img src="'+base_url+'/admin_assets/images/edit.png"  class="white-img"></a>'+
                        '<button class="button btn-wh btn-quesionaire-delete" data-url="'+base_url+'/admin/quesionaire/delete/'+column.id+'" title="delete"><img src="'+base_url+'/admin_assets/images/delete.png"  class="white-img"></button>';
                    }
                }
            ]
        });



     // quesionaire remove confirmation modal
     $('body').on('click','.btn-quesionaire-delete',function(e){
          var url = $(this).attr('data-url');
         swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this Quesionaire data!",
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

      $('body').on('click','.remove_questions',function(e){
            var id = $(this).data('id');
            var urls =  base_url+'/admin/quesionaire/destroy_questions/'+id;
            $.ajax({
                    "headers":{
                    'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                },
                    'type':'get',
                    'url' : urls,
                beforeSend: function() {
                },
                'success' : function(response){ 

                    if(response.status == 'success'){
                        success_alert(response.message);
                       
                    }
                    if(response.status == 'failed'){
                        error_alert(response.message);
                       
                    }

                },
                'error' : function(error){
                    
                },
                complete: function() {
                    var redrawtable = jQuery('#laravel_datatable').dataTable();
                    redrawtable.fnDraw();
                },
                });
      });



    });



$(document).on("click", ".add_quesionaire_data", function(e) {
    e.preventDefault();

    var data = jQuery(this).parents('form:first').serialize();

    $.ajax({
        url: base_url + '/admin/quesionaire/store',
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
                $("form#add-quesionaire-form")[0].reset();
              //  swal("Success!", response.message, "success");
                var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#add_quesionaire').modal('hide'); 
                open_edit_modal(response.quesionaire_id);  

            }
        },
    });
});



$(document).on("click", ".edit_quesionaire", function(e) {
    e.preventDefault();

    var data = jQuery(this).parents('form:first').serialize();
    var id = $(this).data('id');
    $.ajax({
        url: base_url + '/admin/quesionaire/update/'+id,
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
                $("form#edit-quesionaire-form")[0].reset();
                success_alert(response.message);
                 
                var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#edit_quesionaire').modal('hide');
            }
        },
    });
});


$(document).on("click", ".edit_quesionaire_request", function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    var aurls = base_url + "/admin/quesionaire/edit_quesionaire/" + id;
    jQuery.ajax({
        url: aurls,
        type: 'get',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {

            if (response.success) {
                $('#edit_quesionaire_info').html(response.html); 
                get_pophover();
                get_question(id);
                $('#edit_quesionaire').modal('show');

            }
        },
    });
});


    function open_edit_modal(id) {
         var aurls = base_url + "/admin/quesionaire/edit_quesionaire/" + id;
            jQuery.ajax({
                url: aurls,
                type: 'get',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    if (response.success) {
                        $('#edit_quesionaire_info').html(response.html); 
                        $('#edit_quesionaire').modal('show');
                        $('#add_question').show();
                        $('#add_question').modal({ backdrop: 'static', keyboard: false}); 
                        $('.close_new').removeClass('close_add_questions');
                        $('#question_error').addClass('text-danger');
                        $('#question_error').text('Please Add one Question for Qestionarie');
                        $('#add_question').data('bs.modal').$backdrop.css('background-color','black')
                    }
                },
            });
    }

    function get_question(quesionaire_id){


            urls = base_url+'/admin/question/'+quesionaire_id;


        var laravel_datatable_question =$('#laravel_datatable_question').DataTable({
            processing: true,
            serverSide: true,
            "ordering": false,
            destroy: true,
            ajax:urls ,

            columns: [
                { data: 'number_key', name: 'number_key' },
                { data: 'question', name: 'question' },
                { data: 'correct_answer', name: 'correct_answer' },
                { data: 'id', name: 'id' ,
                    render: function (data, type, column, meta) {
                        return '<a  href="#" data-id="'+column.id+'" class="button btn-wh edit_question_request"><img src="'+base_url+'/admin_assets/images/edit.png"  class="white-img"></a>'+
                        '<button class="button btn-wh btn-delete" data-url="'+base_url+'/admin/question/delete/'+column.id+'"><img src="'+base_url+'/admin_assets/images/delete.png"  class="white-img"></button>';
                    }
                }
            ]
        });

    }

    $(function(e){

     // Departmetn remove confirmation modal
     $('body').on('click','.btn-delete',function(e){
          var url = $(this).attr('data-url');
         swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this Question data!",
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



$(document).on("click", ".add_question", function(e) {
    e.preventDefault();

    var data = jQuery(this).parents('form:first').serialize();

    $.ajax({
        url: base_url + '/admin/question/store',
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
                $("form#add-question-form")[0].reset();
                success_alert(response.message);
                 
                get_question(response.quesionaire_id);
                var redrawtable = jQuery('#laravel_datatable_question').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#add_question').modal('hide');
            }
        },
    });
});



$(document).on("click", ".edit_question", function(e) {
    e.preventDefault();

    var data = jQuery(this).parents('form:first').serialize();
    var id = $(this).data('id');
    $.ajax({
        url:  base_url+'/admin/question/update/'+id,
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
                $("form#edit-question-form")[0].reset();
                success_alert(response.message);
                 
                var redrawtable = jQuery('#laravel_datatable_question').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#edit_question').modal('hide');
            }
        },
    });
});


$(document).on("click", ".edit_question_request", function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    var aurls = base_url + "/admin/question/edit_question/" + id;
    jQuery.ajax({
        url: aurls,
        type: 'get',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {

            if (response.success) {
                $('#edit_question_info').html(response.html);

                $('#edit_question').modal('show');
                get_pophover();

            }
        },
    });
});

$(document).on('click','.close_edit_questions',function(){
        $('#edit_question').modal('hide');
    });

$(document).on('click','.close_add_questions',function(){
        $('#add_question').modal('hide');
    });


$(document).on('click','.close_edit_quesionaire',function(){
  var redrawtable = jQuery('#laravel_datatable').dataTable();
                        redrawtable.fnDraw();

        $('#edit_quesionaire').modal('hide');


    });


 

$('.modal').css('overflow-y', 'auto');