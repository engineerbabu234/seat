$(document).ready(function() {

$('.conference_management').hide();

    var url = window.location.pathname;
    var id = url.substring(url.lastIndexOf('/') + 1);
    if ($.isNumeric(id)) {
        urls = base_url + '/admin/office/asset/' + id;
    } else {
        urls = base_url + '/admin/office/asset/';
    }
    var laravel_datatable = $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        "ordering": false,
        destroy: true,
        ajax: urls,
        columns: [{ data: 'number_key', name: 'number_key' }, {          
            data: 'building_name',
            name: 'building_name'
        },  {
            data: 'office_name',
            name: 'office_name'
        },  {
            data: 'title',
            name: 'title'
        }, {
            data: 'id',
            name: 'id',
            render: function(data, type, column, meta) {
                return '<a href="#" data-id="' + column.id + '" class="button accept get_office_assets">' + column.total_seats + '</a>';
        }
        },{
            data: 'id',
            name: 'id',
            render: function(data, type, column, meta) {
                return '<a href="#" data-id="' + column.id + '" class="button  accept view_document">' + column.total_documents + '</a>';
            }
        }, { 
            data: 'id',
            name: 'id',
            render: function(data, type, column, meta) {
                if(column.total_quesionaire > 0 ){
                return '<a href="#" data-id="' + column.id + '" class="button accept question_logic_modal">' + column.total_quesionaire + '</a>';
                }else{
                        return '<a href="#" data-id="' + column.id + '" class="button accept question_logic_modal">0</a>';
                        }
            }
        }, {   data: 'asset_type',
            name: 'asset_type'
        }, {
            data: 'updated_at',
            name: 'updated_at'
        }, {
            data: 'building_id',
            name: 'building_id',
            render: function(data, type, column, meta) {
                return '<a href="#" data-id="' + column.id + '" class="button btn-wh edit_office_assets_request"  title="Edit" ><img src="'+base_url+'/admin_assets/images/edit.png" class="white-img"></a>' + '<button class="button btn-wh btn-delete" data-url="' + base_url + '/admin/office/asset/delete/' + column.id + '" title="Delete"><img src="'+base_url+'/admin_assets/images/delete.png"  class="white-img"></button>';
            }
        }, {
            data: 'id',
            name: 'id',
            render: function(data, type, column, meta) {
                return '<a href="#" data-id="' + column.id + '" class="button btn-wh contract_template"  data-content="Contract Template"  title="Contract Template"  data-trigger="hover" data-placement="left" ><img src="'+base_url+'/admin_assets/images/mail.png" class="white-img"></a>';
            }
        }]
    });
    $('body').on('click', '.btn-delete', function(e) {
        var url = $(this).attr('data-url');
        swal({
            title: "Are you sure you want to delete?",
            //text: "Once deleted, you will not be able to recover this office data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (!willDelete) {
                return false;
            }
            $.ajax({
                "headers": {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                'type': 'post',
                'url': url,
                beforeSend: function() {},
                'success': function(response) {
                    if (response.success) {
                        success_alert(response.message);
                        //swal("Success!", response.message, "success");
                    }
                    if (response.status == 'failed') {
                        error_alert(response.message);
                        //swal("Failed!", response.message, "error");
                    }
                },
                'error': function(error) {},
                complete: function() {
                    var redrawtable = jQuery('#laravel_datatable').dataTable();
                    redrawtable.fnDraw();

                },
            });
        });
    });
});
$(document).on("click", ".add-office-btn", function(e) {
    e.preventDefault();
    $('.error').removeClass('text-danger');
    $('.error').text('');
    var photo = $("form#add-office-asset-form").find(".dropify-render").find("img").attr("src");
    var data = jQuery(this).parents('form:first').serialize();

    slider1Values = slider1.noUiSlider.get();
    slider2Values = slider2.noUiSlider.get();
    
    checkin_start = '';
    checkin_end = '';
    checkout_start = '';
    checkout_end = '';
    if(slider1Values){
        checkin_start =  timeConversion(slider1Values[0]);
        checkin_end = timeConversion(slider1Values[1]);
        checkout_start = timeConversion(slider1Values[2]);
        checkout_end = timeConversion(slider1Values[3]);
         data +="&checkin_start_time="+checkin_start;
         data +="&checkin_end_time="+checkin_end;
         data +="&checkout_start_time="+checkout_start;
         data +="&checkout_end_time="+checkout_end;
    }

     if(slider2Values){
        clean_start =  timeConversion(slider2Values[0]);
        clean_end = timeConversion(slider2Values[1]); 
         data +="&cleanstart_time="+clean_start;
         data +="&cleanend_time="+clean_end; 
    }


    if (photo) {
        data += "&preview_image=" + photo;
    }
    $.ajax({
        url: base_url + '/admin/office/asset/save',
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
                $("form#add-office-asset-form")[0].reset();
                //success_alert(response.message);
                //swal("Success!", response.message, "success");
                var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                $('#add_asset').modal('hide');
                openOfficeAssetadd(response.assset_id,response.building_id);
            }
        },
    });
});

$(document).on("click", ".edit-office-btn", function(e) {
    e.preventDefault();
    $('.error').removeClass('text-danger');
    $('.error').text('');
    var photo = $("form#add-office-asset-form").find(".dropify-render").find("img").attr("src");
    var data = jQuery(this).parents('form:first').serialize();

     slider3Values = slider3.noUiSlider.get();
    slider4Values = slider4.noUiSlider.get();
    
    checkin_start = '';
    checkin_end = '';
    checkout_start = '';
    checkout_end = '';
    if(slider3Values){
        checkin_start =  timeConversion(slider3Values[0]);
        checkin_end = timeConversion(slider3Values[1]);
        checkout_start = timeConversion(slider3Values[2]);
        checkout_end = timeConversion(slider3Values[3]);
         data +="&checkin_start_time="+checkin_start;
         data +="&checkin_end_time="+checkin_end;
         data +="&checkout_start_time="+checkout_start;
         data +="&checkout_end_time="+checkout_end;
    }

     if(slider4Values){
        clean_start =  timeConversion(slider4Values[0]);
        clean_end = timeConversion(slider4Values[1]); 
         data +="&cleanstart_time="+clean_start;
         data +="&cleanend_time="+clean_end; 
    }


    if (photo) {
        data += "&preview_image=" + photo;
    }
    var id = $(this).data('id');
    $.ajax({
        url: base_url + '/admin/office/asset/update/' + id,
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
                $("form#add-office-asset-form")[0].reset();
                success_alert(response.message);
               // swal("Success!", response.message, "success");
                var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                $('.error').removeClass('text-danger');
                $('#edit_modal').modal('hide');
            }
        },
    });
});
$(document).on("change", ".bindOffice", function(e) {
    var building_id = jQuery(this).val();
    if (building_id != "") {
        jQuery('.OfficeData').find('option').not(':first').remove();
        var urls = base_url + "/admin/office/asset/getoffice/" + building_id;
        jQuery.ajax({
            url: urls,
            type: 'get',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    jQuery.each(response.data, function(i, item) {
                        jQuery('.OfficeData').append(jQuery('<option>', {
                            value: item.office_id,
                            text: item.office_name
                        }));
                    });
                }
            },
        });
    }
});
$(document).on("click", ".edit_office_assets_request", function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var aurls = base_url + "/admin/office/asset/edit/" + id;
    jQuery.ajax({
        url: aurls,
        type: 'post',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#edit_assets').html(response.html);
                var drEvent = $('.dropify-event').dropify();
                $('#edit_modal').modal('show'); 

                set_edit_assets(response.checkin_start_time,response.checkin_end_time ,response.checkout_start_time ,response.checkout_end_time ,response.cleanstart_time ,response.cleanend_time);
                get_pophover();
                $('#checkin_pophover').hide();
                $('#checkout_pophover').hide();
                $('#cleaning_pophover').hide();
                $('#s3-color1').attr('title','Checkin Window');
                
                $('#s3-color2').attr('title','CheckOut Window');
                $('#s4-color1').attr('title','Cleaning Window');  
                $('#s3-color1').popover({ 
                    content:  $('#checkin_pophover').html(),  
                    placement: 'top',
                    html: true,
                    trigger: 'hover'
                });

                $('#s3-color2').popover({ 
                    content:  $('#checkout_pophover').html(),  
                    placement: 'top',
                    html: true,
                    trigger: 'hover'
                });

                $('#s4-color1').popover({ 
                    content:  $('#cleaning_pophover').html(),  
                    placement: 'top',
                    html: true,
                    trigger: 'hover'
                });


                $("input[name$='checkin']").click(function() {
                    var checkin_value = $(this).val();
                   
                    if(checkin_value == 1){
                        $(".checktime").show(); 
                        $(".checkin_methods").show(); 
                    } else {
                        $(".checktime").hide();
                        $(".checkin_methods").hide();
                    }
                }); 
                
                $("input[name$='seat_clean']").click(function() {
                    var cleantime = $(this).val();
                    if(cleantime == 1){
                        $(".cleantime").show();
                    } else {
                        $(".cleantime").hide();
                    }
                  });

                

                     $("input[name$='conference_management']").click(function() {
                        var conference_management = $(this).val();
                        if(conference_management == 1){ 
                            $(".conferance").show(); 
                        } else {
                            $(".conferance").hide();
                        }
                      });

                     
                     $("input[name$='billing_managment']").click(function() {
                        var daily_cost = $(this).val();
                        if(daily_cost == 1){ 
                            $(".daily_cost").show(); 
                        } else {
                            $(".daily_cost").hide();
                        }
                      });




                
                    $('#assets_type_edit').popover({ 
                        content:  $('#assets_type_pophover').html(),  
                        placement: 'left',
                        html: true,
                        trigger: 'hover'
                    });

                
            }
        },
    });
});
$(document).on("click", ".get_office_assets", function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    openOfficeAsset(id);
});

function openOfficeAsset(assetId) {
    var aurls = base_url + "/admin/office/asset/getofficeassets/" + assetId;
    jQuery.ajax({
        url: aurls,
        type: 'get',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#office_assets_seats').html(response.html);
                var drEvent = $('.dropify-event').dropify();
                $('#assets_seat_modal').modal('show');
                $("#office_assets_seats").canvasfiles();
            }
        },
    });
}


function openOfficeAssetadd(assetId,building_id) {
    var aurls = base_url + "/admin/office/asset/getofficeassets/" + assetId;
    jQuery.ajax({
        url: aurls,
        type: 'get',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#office_assets_seats').html(response.html);
                var drEvent = $('.dropify-event').dropify();
                $('#assets_seat_modal').modal('show');
                $("#office_assets_seats").canvasfiles();
                 $('#changeModal').show();
                 $('#seat_assets_id').val(assetId);
                 $('#seat_building_id').val(seat_building_id);
                $('#changeModal').modal({ backdrop: 'static', keyboard: false}); 
                $('.close_new').removeClass('seats_cancel');
                $('#seat_no_error').addClass('text-danger');
                $('#seat_no_error').text('Please Add one seat for office assets');
                $('#changeModal').data('bs.modal').$backdrop.css('background-color','black')
            }
        },
    });
}

$(document).on("click", ".editSeat", function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    openOfficeSeats(id);
});

function openOfficeSeats(seatid) {
    var aurls = base_url + "/admin/office/asset/edit_seats/" + seatid;
    var asset_id = $('#asset_id').val();
    jQuery.ajax({
        url: aurls,
        type: 'POST',
        data:{'asset_id':asset_id},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#edit_office_seats').html(response.html);
                $('#updateseatsModal').modal('show'); 
               
                if(response.asset_type){
                     
                      if(response.asset_type == 1){  
                          $('.update_modal_title').text('Configure Seat');
                             $('.deskattributes').show();
                          $('.carparkspace').hide();
                          $('.meetingspace').hide();
                          $('.collaboration_space').hide();
                      } else if(response.asset_type == 2){       
                           
                           $('.update_modal_title').text('Configure Carpark Space');
                           $('#seat_no').attr('placeholder','Space No');
                            $('.seat_no_header').attr('data-content','Space No');
                           $('.seat_no_header').attr('title','Space');
                           $('.carparkspace').show();
                           $('.deskattributes').hide();
                           $('.meetingspace').hide();
                             $('.collaboration_space').hide();
                      } else if(response.asset_type == 3){                         
                           $('.update_modal_title').text('Configure Collaboration Space');
                            $('#seat_no').attr('placeholder','Standing No');
                            $('.seat_no_header').attr('title','Standing');
                            $('.seat_no_header').attr('data-content','Standing');
                          $('.deskattributes').hide();
                          $('.carparkspace').hide();
                          $('.meetingspace').hide();
                            $('.collaboration_space').show();
                      } else if(response.asset_type == 4){                         
                           $('.update_modal_title').text('Configure Meeting Room Space');
                            $('.meetingspace').show();
                          $('.deskattributes').hide();
                          $('.carparkspace').hide();
                          $('.collaboration_space').hide();
                           $('.telephone').change(function () {
                              if($(this).is(':checked')){
                                 
                                  $(".telephone_no").show();   
                              }
                              else {
                                  $(".telephone_no").hide();  
                              }
                         });
                      }
                  }
                 get_pophover();

            }
        },
    });
}
$(document).on("click", ".edit-booking-seat", function(e) {
    e.preventDefault();
     showPageSpinner();
    var data = jQuery(this).parents('form:first').serialize(); 
    var id = $(this).data('id');
    $.ajax({
        url: base_url + '/admin/office/asset/updateSeat/' + id,
        type: 'post',
        dataType: 'json',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function(response){
            showPageSpinner();
        },
        error: function(response) {
            if (response.status == 400) {
                $.each(response.responseJSON.errors, function(k, v) {
                    $('#edit_' + k + '_error').text(v);
                    $('#edit_' + k + '_error').addClass('text-danger');
                });

            }
            hidePageSpinner(); 
        }, 
        success: function(response) {
            if (response.success) {
                $("#edit-office-asset-seat-form").trigger('reset');
                $('.error').removeClass('text-danger');
                $('#updateseatsModal').modal('hide');
                $('.error').removeClass('text-danger');
                $('.error').text('');
                success_alert(response.message);
                //swal("Success!", response.message, "success");
            }
        },complete:function(response) {
              hidePageSpinner();                 
        },
    });
});





$(document).on("click", ".question_logic_modal", function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    var aurls = base_url + "/admin/office/asset/question_logic/"+id;
    jQuery.ajax({
        url: aurls,
        type: 'post',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       
        success: function(response) {

            if (response.success) {
                $('#question_logic_info').html(response.html);
                
                console.log(response.quesionaire);
                $('#question_logic_modal').modal('show');
                
                $(".selectmultiple").select2({ placeholder: "Select a questionarie",
    allowClear: true});
                $('#quesionaire_id').val(response.quesionaire).trigger('change');
               
            }
        },
        
    });
});
 
 
                  $("input[name$='seat_clean']").click(function() {
                    var cleantime = $(this).val();
                    if(cleantime == 1){
                      
                        $(".cleantime").show();


                    } else {
                        $(".cleantime").hide();
                    }
                  });

$(document).on("click", ".save_question_logic", function(e) {
    e.preventDefault();
    var logic_data = [];
    $('.choice').each(function(n) {
        if($(this).attr('data-id')){
            logic_data[n] = $(this).attr('data-id');
        }
    });
     
    var quesionaire_id = $("#quesionaire_id").val();

    console.log(quesionaire_id);
    
    let office_assets_id = $('#office_assets_id').val();
    $.ajax({
        url: base_url + '/admin/office/asset/save_question_logic',
        type: 'post',
        dataType: 'json',
        data: {'logic':logic_data,'office_assets_id':office_assets_id,'quesionaire_id':quesionaire_id},
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
                $("form#question-logic")[0].reset();
                var redrawtable = jQuery('#laravel_datatable').dataTable();
                redrawtable.fnDraw();
                success_alert(response.message);
                //swal("Success!", response.message, "success");
                $('.error').removeClass('text-danger');
                $('#question_logic_modal').modal('hide');
            }
        },
    });
});
  
function timeConversion(milliseconds) {

       //Get hours from milliseconds
  var hours = milliseconds / (1000*60*60);
  var absoluteHours = Math.floor(hours);
  var h = absoluteHours > 9 ? absoluteHours : '0' + absoluteHours;

  //Get remainder from hours and convert to minutes
  var minutes = (hours - absoluteHours) * 60;
  var absoluteMinutes = Math.floor(minutes);
  var m = absoluteMinutes > 9 ? absoluteMinutes : '0' +  absoluteMinutes;

  //Get remainder from minutes and convert to seconds
  var seconds = (minutes - absoluteMinutes) * 60;
  var absoluteSeconds = Math.floor(seconds);
  var s = absoluteSeconds > 9 ? absoluteSeconds : '0' + absoluteSeconds;

        return h + ':' + m + ':' + s;
 }
 

function set_edit_assets(checkin_start_time,checkin_end_time,checkout_start_time,checkout_end_time,cleanstart_time,cleanend_time){

// -------- Slider 1
var slider3 = document.getElementById("slider3");

noUiSlider.create(slider3, {
  // Indicate the handle starting positions here
  // Using Unix epoch as a base
  start: [ timestamp("1970-01-01T"+checkin_start_time+"Z"), timestamp("1970-01-01T"+checkin_end_time+"Z"), timestamp("1970-01-01T"+checkout_start_time+"Z"), timestamp("1970-01-01T"+checkout_end_time+"Z")],
  connect: [false, true, false, true, false],
  range: {
    // Start at 07:00, End 23:00
    // Using Unix epoch as a base for easier calculations
    min: timestamp("1970-01-01T07:00:00Z"),
    max: timestamp("1970-01-01T23:00:00Z"),
  },
  tooltips: [
    { to: formatHour, from: epoch },
    { to: formatHour, from: epoch },
    { to: formatHour, from: epoch },
    { to: formatHour, from: epoch },
  ],
  pips: {
    mode: "steps",
    density: 2,
    filter: filterPips,
    format: {
      to: formatHour,
      from: epoch,
    },
  },
  // Steps of one minute
  step: 30 * 60 * 1000,
  behaviour: "tap-drag",
});

//  Adding slider3's ranges to a connects array and giving each range an id
var connectsList = slider3.querySelectorAll(".noUi-connect");
var connects = Array.from(connectsList)
var rngIDs = ["s3-color1", "s3-color2", "s4-color1"];  // used for other ranges, makes it easier to track ids
for (var i = 0; i < connects.length; i++) {
  connects[i].id = rngIDs[i];
}

// -------- Slider 2
var slider4 = document.getElementById("slider4");

noUiSlider.create(slider4, {
  // Indicate the handle starting positions here
  start: [timestamp("1970-01-01T"+cleanstart_time+"Z"), timestamp("1970-01-01T"+cleanend_time+"Z")],
  connect: [false, true, false],
  range: {
    // Start at 07:00, End 23:00
    // Using Unix epoch as a base for easier calculations
    min: timestamp("1970-01-01T07:00:00Z"),
    max: timestamp("1970-01-01T23:00:00Z"),
  },
  tooltips: [
    { to: formatHour, from: epoch },
    { to: formatHour, from: epoch },
  ],
  pips: {
    mode: "steps",
    density: 2,
    filter: filterPips,
    format: {
      to: formatHour,
      from: epoch,
    },
  },
  // Move at increments of 30 minutes
  step: 30 * 60 * 1000,
  behaviour: "tap-drag",
});

// Adding slider4's range to our connects array and giving it an id
connects.push(slider4.querySelectorAll(".noUi-connect")[0]);
connects[2].id = rngIDs[2];

// Ensure Range3 can't supercede Ranges 1 or 2
// Current behavior is to limit the changing slider, this can be changed
slider3.noUiSlider.on("change", function () {
  max = Number(slider4.noUiSlider.get()[0]) - slider3.noUiSlider.options.step;
  current = Number(slider3.noUiSlider.get()[3]);
  if (current > max) {
    slider3.noUiSlider.setHandle(3, max);
  }
});

slider4.noUiSlider.on("change", function () {
  min = Number(slider3.noUiSlider.get()[3]) + slider4.noUiSlider.options.step;
  current = Number(slider4.noUiSlider.get()[0]);
  if (current < min) {
    slider4.noUiSlider.setHandle(0, min);
  }
});

// All ranges in a slider need to be at least 30mins long and apart

// Distances handles functions
slider3.noUiSlider.on("change", function (values, handle) {
  if (handle < 3 && values[handle + 1] == values[handle]) {
    slider3.noUiSlider.setHandle(
      handle,
      Number(values[handle]) - slider3.noUiSlider.options.step
    );
  }
  if (handle != 0 && values[handle - 1] == values[handle]) {
    slider3.noUiSlider.setHandle(
      handle,
      Number(values[handle]) + slider3.noUiSlider.options.step
    );
  }
});

slider4.noUiSlider.on("change", function (values, handle) {
  if (handle < 1 && values[handle + 1] == values[handle]) {
    slider4.noUiSlider.setHandle(
      handle,
      Number(values[handle]) - slider4.noUiSlider.options.step
    );
  }
  if (handle != 0 && values[handle - 1] == values[handle]) {
    slider4.noUiSlider.setHandle(
      handle,
      Number(values[handle]) + slider4.noUiSlider.options.step
    );
  }
});
 
} 

$('#checkin_pophover').hide();
$('#checkout_pophover').hide();
$('#cleaning_pophover').hide();
$('#s1-color1').attr('title','Checkin Window');
$('#s1-color2').attr('title','CheckOut Window');
$('#s2-color1').attr('title','Cleaning Window');  
$('#s1-color1').popover({ 
    content:  $('#checkin_pophover').html(),  
    placement: 'top',
    html: true,
    trigger: 'hover'
});

$('#s1-color2').popover({ 
    content:  $('#checkout_pophover').html(),  
    placement: 'top',
    html: true,
    trigger: 'hover'
});

$('#s2-color1').popover({ 
    content:  $('#cleaning_pophover').html(),  
    placement: 'top',
    html: true,
    trigger: 'hover'
});

$('#assets_type_pophover').hide();
$('#assets_type').popover({ 
    content:  $('#assets_type_pophover').html(),  
    placement: 'left',
    html: true,
    trigger: 'hover'
});

$('#assets_type_add').popover({ 
    content:  $('#assets_type_pophover').html(),  
    placement: 'left',
    html: true,
    trigger: 'hover'
});

$('#assets_type_edit').popover({ 
    content:  $('#assets_type_pophover').html(),  
    placement: 'left',
    html: true,
    trigger: 'hover'
});

 $('.conference_management').hide();

$(document).on("change", "#asset_type", function(e) {

    var assets_type_id = jQuery(this).val();
    if(assets_type_id == 2){
        $('.cleaning_management').hide();
    } else{
        $('.cleaning_management').show();
    }

   
    if(assets_type_id == 4 || assets_type_id == 3){
        $('.conference_management').show();
    } else{
        $('.conference_management').hide();
    }


});

 $(".conferance").hide();
 $("input[name$='conference_management']").click(function() {
    var conference_management = $(this).val();
    if(conference_management == 1){ 
        $(".conferance").show(); 
    } else {
        $(".conferance").hide();
    }
  });

 $(".daily_cost").hide();
 $("input[name$='billing_managment']").click(function() {
    var daily_cost = $(this).val();
    if(daily_cost == 1){ 
        $(".daily_cost").show(); 
    } else {
        $(".daily_cost").hide();
    }
  });
 

$(document).on('click' , '.set_canvas_scale',function(event) { 
    //$(this).attr('disabled',true);
});

$('#contract_template_pophover').hide();  
$('#contract_template').popover({ 
    content:  $('#contract_template_pophover').html(),  
    placement: 'left',
    html: true,
    trigger: 'hover'
});


$(document).on("click", ".contract_template", function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    
    var data = jQuery(this).parents('form:first').serialize(); 
    var aurls = base_url + "/admin/office/asset/view_contract_template/"+id;
    jQuery.ajax({
        url: aurls,
        type: 'post',
        dataType: 'json',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
       
        success: function(response) {
            if (response.success) { 
                $('#contract_tempalte_info').html(response.html); 
                $('#contract_template_modal').modal('show');
            }
        },
        
    });
});

$(document).on("click", ".send_contract_template", function(e) {
    e.preventDefault();
     showPageSpinner();    
    var data = jQuery(this).parents('form:first').serialize(); 
    var aurls = base_url + "/admin/office/asset/send_contract_signature_request/";
    jQuery.ajax({
        url: aurls,
        type: 'post',
        dataType: 'json',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, 
        error: function(response) {
             hidePageSpinner();
        },
        success: function(response) {
            if (response.success) { 
                 hidePageSpinner();
               success_alert(response.message);  
               $('#contract_template_modal').modal('hide');    
            }   
        },
        
    });
});