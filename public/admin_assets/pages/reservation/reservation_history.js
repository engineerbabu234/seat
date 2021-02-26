$(document).ready(function(){
    
    $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        "ordering": false,
        destroy: true,
        ajax: base_url+'/admin/reservation/reservation_history',

        columns: [
            { data: 'reservation_id', name: 'reservation_id' },
            { data: 'building_name', name: 'building_name' },
            { data: 'office_name', name: 'office_name' },
            { data: 'user_name', name: 'user_name' },
            { data: 'email', name: 'email' },
            { data: 'profile_image', name: 'profile_image' , 
                render: function (data, type, column, meta) {
                    return '<div class="img">'+
                    '<img src="'+column.profile_image+'" width="70" >'+
                    '</div>' ;
                }
            },
            { data: 'seat_no', name: 'seat_no' },
            { data: 'reserve_date', name: 'reserve_date' },
            { data: 'building_id', name: 'building_id' , 
                render: function (data, type, column, meta) {
                    if(column.status=='1'){
                        return '<label class="label accepted">Accepted</label>'+
                                                '<p>(Approved by Admin)</p>';
                       
                    }else if(column.status=='2'){
                        return '<label class="label rejected">Rejected</label>'+
                                                '<p>(Rejected by Admin)</p>'; 
                    }else if(column.status=='3'){
                        return '<label class="label canceled">Cancelled</label>'+
                                                '<p>(By User)</p>';
                    }else if(column.status=='4'){
                         return '<label class="label accepted">Accepted</label>'+
                                                '<p>(Auto Approved)</p>';
                    }
                }
            },
            { data: 'reservation_id', name: 'reservation_id' , 
                render: function (data, type, column, meta) {
                    if(column.current_date!=column.reserve_date){
                        return '<button class="button btn-wh delete-status" title="Delete"  reserve_seat_id="'+column.reserve_seat_id+'" ><img src="'+base_url+'/admin_assets/images/delete.png" class="white-img"></button>';
                    }else{
                        return '<button class="button reject" >Not Deleted</button>';
                    } 
                }
            }
        ]
    });

    $('body').on('click','.delete-status', function() {
        var reserve_seat_id = $(this).attr("reserve_seat_id");
        var success_status='Deleted';            
        path='admin/reservation/delete/'+reserve_seat_id;
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this seat reservation request data!",
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
                'url' :  base_url + '/' +path,
                'data' : {  reserve_seat_id : reserve_seat_id},
                'beforeSend': function() {

                },
                'success' : function(response){
                    if(response.status){
                        success_alert(response.message);
                      //  swal(success_status ,response.message, 'success');
                        //location.reload();
                        var redrawtable = jQuery('#laravel_datatable').dataTable();
                        redrawtable.fnDraw();
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
});