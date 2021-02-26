
    function get_documents(asset_id){  
          urls = base_url+'/admin/document/'+asset_id; 
        var laravel_datatable_document =$('#laravel_datatable_document').DataTable({
             
            serverSide: true,
            "ordering": false,
            destroy: true,
            ajax:urls ,
            columns: [
                { data: 'number_key', name: 'number_key' },
                { data: 'document_title', name: 'document_title' },
                { data: 'document_name', name: 'document_name' },
                { data: 'document_description', name: 'document_description' },
                { data: 'id', name: 'id' ,
                    render: function (data, type, column, meta) {
                        return '<a  href="#" data-id="'+column.id+'" class="button btn btn-wh edit_document_request"><img src="'+base_url+'/admin_assets/images/edit.png"  class="white-img"></a>'+
                        '<button class="button btn btn-wh btn-delete" data-url="'+base_url+'/admin/document/delete/'+column.id+'"><img src="'+base_url+'/admin_assets/images/delete.png"  class="white-img"></button>';
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
                        
                        var redrawtable = jQuery('#laravel_datatable_document').dataTable();
                        redrawtable.fnDraw();
                    }
                    if(response.status == 'failed'){
                        error_alert(response.message);
                        
                    }
                },
                'error' : function(error){
                },
                complete: function() {
                    var redrawtable = jQuery('#laravel_datatable_document').dataTable();
                        redrawtable.fnDraw();

                },
                });
         });
     });

    })



$(document).on("click", ".add_document", function(e) {
    e.preventDefault();
         
     $('.error').removeClass('text-danger');
     $('.error').text('');
     var form = $('form#add-document-form')[0];
       var data = new FormData(form);
      
    $.ajax({
        url: base_url + '/admin/document/store',
        type: 'post',
        dataType: 'json', 
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
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
                $("form#add-document-form")[0].reset();
                success_alert(response.message);
                 
                get_documents(response.office_asset_id);
                var redrawtable = jQuery('#laravel_datatable_document').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#add_document').modal('hide');
            }
        },
    });
});



$(document).on("click", ".edit_document", function(e) {
    e.preventDefault();
    $('.error').removeClass('text-danger');
     $('.error').text('');
     var form = $('form#edit-document-form')[0];
       var data = new FormData(form);  
    var id = $(this).data('id');
    $.ajax({
        url:  base_url+'/admin/document/update/'+id,
        type: 'post',
        dataType: 'json',
         enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
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
                $("form#edit-document-form")[0].reset();
                success_alert(response.message);
                 
                var redrawtable = jQuery('#laravel_datatable_document').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#edit_document').modal('hide');
            }
        },
    });
});


$(document).on("click", ".view_document", function(e) {
    e.preventDefault();
    var id = $(this).data('id'); 

    var aurls = base_url + "/admin/document/document_details/" + id;
    jQuery.ajax({
        url: aurls,
        type: 'get',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {

            if (response.success) {                 
                $('#view_documents').html(response.html); 
                $('#view_document').modal('show');
                $('#office_asset_id').val(id);
                  $(".selectmultiple").select2({ placeholder: "Select a Document",
    allowClear: true});
                   
                $('#document_attech').val(response.document_attech).trigger('change');
                
                get_pophover();

            }
        },
    });
});


$(document).on("click", ".edit_document_request", function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    var aurls = base_url + "/admin/document/edit_document/" + id;
    jQuery.ajax({
        url: aurls,
        type: 'get',
        dataType: 'json',
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
                $('#edit_document_info').html(response.html); 
                $('#edit_document').modal('show');
                get_pophover();

            }
        },
    });
});

$(document).on("click", ".save_document_attech", function(e) {
    e.preventDefault();
    
    var document_attech = $("#document_attech").val();  
    let office_assets_id = $('#office_assets_id').val();
    $.ajax({
        url: base_url + '/admin/document/save_documets_attech/',
        type: 'post',
        dataType: 'json',
        data: {'document_attech':document_attech,'office_assets_id':office_assets_id },
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
                $("form#document_attech_info")[0].reset();
                var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                success_alert(response.message);
                //swal("Success!", response.message, "success");
                $('.error').removeClass('text-danger');
                $('#view_document').modal('hide');
            }
        },
    });
});

 
$(document).on('click','.close_edit_document',function(){
        $('#edit_document').modal('hide');
    });

$(document).on('click','.close_add_document',function(){
        $('#add_document').modal('hide');
    });
 
$('.modal').css('overflow-y', 'auto');


