  $.fn.canvasfiles = function() {
      // initialize coordinate and size
      let originContentW = 1370,
          originContentH = 885,
          contentW = $("#canvas").width(),
          contentH = $("#canvas").height(),
          scaleX = contentW / originContentW,
          scaleY = contentH / originContentH,
          borderR = 25,
          strokeW = 3,
          normalF = 22 * scaleX,
          sidebarX = 10 * scaleX,
          sidebarY = 80,
          sidebarWidth = 350 * scaleX,
          sidebarHeight = contentH - 10,
          mainX = sidebarX + sidebarWidth + 15 * scaleX,
          mainY = 0,
          mainW = contentW - sidebarX - sidebarWidth - 15 * scaleX - 10 * scaleX,
          mainH = contentH - 10,
          sidebarTitleX = 65 * scaleX,
          sidebarTitleY = 50,
          sidebarTitleF = 40 * scaleX,
          circleR = 25 * scaleX,
          circleF = 32 * scaleX,
          circleY = sidebarTitleY + sidebarTitleF + 20,
          circleBX = sidebarWidth / 2 - circleR - 50 * scaleX,
          circleAX = sidebarWidth / 2 + 50 * scaleX,
          statusY = circleY + circleR * 2 + 5,
          statusTextF = 26 * scaleX,
          blockedStatusX = sidebarWidth / 2 - 95 * scaleX,
          availableStatusX = sidebarWidth / 2 + 25 * scaleX,
          officeDetailsY = statusY + 80,
          officeDetailsX = sidebarWidth / 2,
          officeDetails = sidebarWidth / 2,
          officeAssetsY = officeDetailsY + sidebarTitleF + normalF * 3 + 70,
          officeAssetsX = sidebarX + 10,
          imageX = 0,
          imageY = 0,
          modalW = 600 * scaleX,
          modalH = 350,
          modalX = mainX + (mainW - modalW) / 2,
          modalY = mainY + 50,
          modalTitleX = 30,
          modalTitleY = 20,
          modalTitleF = 24,
          btnW = 83 * scaleX,
          btnH = 30 * scaleX,
          btnR = 5 * scaleX,
          btnF = (17 * scaleX).toString() + "px",
          btnML = (20 * scaleX).toString() + "px",
          createBtnW = 40 * scaleX,
          mapN = 2;
      // initialize color of objects on canvas
      let strokeC = "#D37C3A",
          strokeCircleAC = "#84AA4F",
          strokeCircleBC = "#3E538C",
          circleAC = "#62AF57",
          circleBC = "#DA0000",
          editBtnC = "#62AF57",
          deleteBtnC = "#DB0000",
          strokeBtnC = "#3E538C",
          selectedBtnC = "#D8EBD5",
          greyC = "#808080",
          opacityC = 0.2,
          modalStrokeC = "#3D538C";
      // dynamic canvas object
      let officeAssets = [];
      let clonedCircles = [];
      let rulers = [];
      let circleNums = 0;
      let currentOfficeIndex = -1;
      // flags of status
      let edit_flag = false;
      let started_flag = false;
      let down_flag = false;
      let mainImage = null;
      // initialize canvas
      let canvas = new fabric.Canvas("canvas", {
          controlsAboveOverlay: true,
          preserveObjectStacking: true,
          fireRightClick: true,
          selection: false
      });
      initCenteringGuidelines(canvas);
      initAligningGuidelines(canvas);
      canvas.selectionColor = "transparent";
      canvas.selection = false;
      canvas.setWidth(contentW);
      canvas.setHeight(contentH);


       var url = window.location.href;
      var search_asset =  url.split("?")[1];
      var first =  url.split("&")[1];
      var second =  url.split("&")[2];
      if(search_asset){
          var search_asset_id =  search_asset.split("=")[1];  
          
      }
      if(first){
        var arrow_id =  first.split("=")[1];  
      }

      if(second){
        var search_date =  second.split("=")[1]; 
        if(search_date){ 
            // $("#booking_date").datepicker({"setDate": search_date});
            setTimeout(function() { 
              $('#today_date').text(search_date);
              $('#booking_date').val(search_date);
              loadDataFromStrapi($('#asset_id').val(),search_date); 
            }, 1000);
        }


      
      }

       // show circle tool box
      function showShadowToolBox(e) {   
          if(e.target){
            stopDragging(e.target);  
            $(".shadowImg").css("left", e.target.left + $("#main").position().left-5);
            $(".shadowImg").css("top", e.target.top + 492);
            $(".shadowImg").css("width", 40);
            $(".shadowImg").css("height", 40);
            $(".shadowImg").css("display", "block");
          } 
      }

      // hide circle tool box
      function hideShadowToolBox() { 
          $(".shadowImg").css("display", "none"); 
      }
  
      // function to show spinner
      function showSpinner() {
           $('#loader-wrapper').css("visibility", "visible"); 
          $('#loader-wrapper').css("opacity", "1");         
      }
      // function to hide spinner
      function hideSpinner() {
         $('#loader-wrapper').css("visibility", "hidden"); 
          $('#loader-wrapper').css("opacity", "0"); 
          
      }


      canvas.on('mouse:move',function(e){ 
          if (!e.target) return;
          if (!e.target._objects) return;
          if (e.target._objects.length === 2) {
              if (e.target._objects[0].type === "circle") { 
                   showShadowToolBox(e);
                  return;
              }
          }
          
      });


      canvas.on('mouse:down',function(e){ 
          if (!e.target) return;
          if (!e.target._objects) return;
          if (e.target._objects.length === 2) {
              if (e.target._objects[0].type === "circle") { 
                   showShadowToolBox(e);
                  return;
              }
          }
          
      });

      canvas.on('mouse:out',function(e){
          if (!e.target) return;
          if (!e.target._objects) return;
          if (e.target._objects.length === 2) {
              if (e.target._objects[0].type === "circle") { 
                  hideShadowToolBox();
                  return;
              }
          }
           
      });
 

      

      // function to handle to start edit asset
      function start(url, title = '') {
          clonedCircles.slice(0, clonedCircles.length);
          circleNums = 0;
          canvas.set("fill", "#fff");
          edit_flag = true;
           
          if (mainImage) {
              canvas.remove(mainImage);
              canvas.renderAll();
          }
          fabric.Image.fromURL(url, function(Img) {
              mainImage = Img;
              mainImage.set({
                  top: imageY + 100,
                  left: imageX
              }, {
                  crossOrigin: 'annonymous'
              });
              canvas.add(mainImage);
              canvas.renderAll();
          });
          let titleText = new fabric.Text(title, {
              left: 15,
              top: 10,
              fontSize: 50,
              fontWeight: "bold",
              fill: "#000",
              selectable: false
          });
          //canvas.add(titleText);
          started_flag = true;
      };
      // function to handle click event of circle a
       

       $("#booking_date").on('change',function(e){ 
          e.preventDefault();
          setTimeout(function() {
              loadDataFromStrapi($('#asset_id').val(),$('#today_date').text()); 


          }, 1000);
       });
      //getcanvas iamge
      loadDataFromStrapi($('#asset_id').val());
      // load data from strapi
      function loadDataFromStrapi(id, booking_date = null) {

          var date = '';
          if (booking_date) {
              date = booking_date;
          } else {
              date = $('#today_date').text();
          }

          $.ajax({
              url: base_url + "/getofficeassetsinfo/" + id,
              type: "POST",
              data: {
                  'date': date
              },
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(res) {
                  var main_image = '';
                  var canvas_data = res.data.asset_canvas;
                  if (canvas_data !== null && canvas_data.length > 0 && canvas_data !== undefined) {
                      var candata = jQuery.parseJSON(canvas_data);
                      if (candata.objects.length !== 0) {
                          main_image = res.data.asset_canvas;
                          var json = main_image;
                          canvas.clear();
                          canvas.loadFromJSON(json, function() {
                              canvas.item(0).selectable = false;
                              canvas.renderAll();
                          }); 
                           
                          $('#is_edit').val('yes');
                      } else {
                          start($("#main_image").val(), $("#asset_name").val());
                      }

                  
                      showSpinner();
                      setTimeout(function() {
                         
                           /* iterate through array or object */
                                  obj = canvas.getObjects();
                                  
                                  obj.forEach(function(item, i) {

                                      if (item._objects) {
                                        
                                          item._objects.forEach(function(object) {
                                                object.group._objects[0].set("fill", '#84AA4F'); 
                                                 canvas.renderAll(); 
                                            if (object && object.text != ''  ) {  

                                                
                                                  if(object.text == arrow_id){
                                                      $(".view_arrow").css("left", item.left + $("#main").position().left-95);
                                                      $(".view_arrow").css("top", item.top + 467);
                                                      $(".view_arrow").css("width", 100);
                                                      $(".view_arrow").css("height", 100);
                                                      $(".view_arrow").css('-webkit-animation', 'uparrow 0.6s infinite alternate ease-in-out');
                                                      $(".view_arrow").css("display", "block");
                                                  }


                                                if (res.seats_status) { 
                                                    $.each(res.seats_status, function(index, sval) { 
                                                      if (object.text == sval.seat_no) {


                                                              let asset_id = id; 
                                                              object.group._objects[0].set("fill", '#F32D13'); 
                                                              canvas.renderAll();  
                                                        }   
                                                     }); 
                                                  } 

                                                 if (res.reserve_seats) { 
                                                    $.each(res.reserve_seats, function(index, val) { 
                                                      if (object.text == val.seat_no) { 

                                                            if(val.status == 0){
                                                              let asset_id = id; 
                                                              object.group._objects[0].set("fill", '#5471BF');
                                                              object.group._objects[0].set("stroke", '#fff');
                                                              canvas.renderAll();                                                               
                                                            }

                                                            if(val.status == 1 || val.status == 4){
                                                              let asset_id = id; 
                                                              object.group._objects[0].set("fill", '#9E9E9E');
                                                              object.group._objects[0].set("stroke", '#fff');
                                                              canvas.renderAll();                                                               
                                                            }
                                                            
                                                        }  

                                                     });

                                                  } 

                                                 
                                              }
                                          });                                          
                                      }                                 
                              });
                              saveImage(1);     
                        hideSpinner();          

                      }, 2000);
                     
                        

                  } else {
                      main_image = res.assets_image;
                      start($("#main_image").val(), $("#asset_name").val());
                        hideSpinner();
                  }
                  
              },
              error: function(err) {
                  console.log(err);
                  hideSpinner();
              }
          });
      }


      setTimeout(function() {
        $(".view_arrow").css("display", "none");

      }, 4000);

      
      // add listener for click event on canvas
      canvas.on("mouse:down", function(e) {
         
          stopDragging(e.target);
          if (!started_flag) return;
           hideShadowToolBox();
          down_flag = true;
          if (!e.target) return;
          if (!e.target._objects) return;
          if (e.target._objects.length === 2) {
              if (e.target._objects[0].type === "circle") { 
                   showShadowToolBox();
                  return;
              }
          }
           hideShadowToolBox();
      });
      var objs = canvas.getObjects().map(function(o) {
          console.log('all objects');
      });

      function stopDragging(element) {
        if(typeof element !== "undefined")
          {
          
          element.lockMovementY = true;
          element.lockMovementX = true;
          element.selectable = false;
          element.hasControls = false;
        }
      }
      var timeoutTriggered = false;

      function onMoving(e) {
          if (!timeoutTriggered) {
              stopDragging(e.target);
              timeoutTriggered = true;
          }
      }
      canvas.on("mouse:up", function(e) {
          down_flag = false;
          if (e.target) {

              //clicked on object  
              getassetsdetails(e.target._objects[1].text);
               if (e.target._objects.length === 2) {
              if (e.target._objects[0].type === "circle") { 
                  showShadowToolBox();
              }
          }
              
              stopDragging(e.target);
              //add rectangle
          }
           
      });
      canvas.renderAll();


       
      // seat booking and validation
       $(document).on("click", ".booking-seat", function(e) {
                 $('#seat_book_modal').modal('hide');
                e.preventDefault(); 
                var data = jQuery(this).parents('form:first').serialize();
                var myThis = $(this);
                $.ajax({
                    url: base_url + '/bookOfficeSeats',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () {
                       showPageSpinner();
                    },
                    error: function(response) { 
                        if (response.status == 400) {
                            if(response.responseJSON.html){  
                               
                                    $('#error_modal').show();
                                    $('#error_modal_text').html(response.responseJSON.html);
                                    $('#seat_book_modal').modal('hide');
                                    $('#error_modal').data('bs.modal',null);
                                    $('#error_modal').modal({backdrop:'static', keyboard:false});   
                                     hidePageSpinner();                                
                                

                            } 
                            
                        }
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#add-office-asset-seat-booking-form").trigger('reset');
                             
                             
                             setTimeout(function() {
                                if(response.order){
                                    $('#warning_modal').show();
                                    $('#warning_modal').modal({ backdrop: 'static', keyboard: false});
                                    $('#warning_modal_text').html(response.html);

                                } else{
                                    $('#success_modal').show();
                                    $('#success_modal_text').html(response.html);
                                    $('#success_modal').modal({ backdrop: 'static', keyboard: false});
                                } 


                                 let dots_id = $('#seat_no').val();  
                                  obj = canvas.getObjects();
                                  obj.forEach(function(item, i) {
                                      if (item._objects) {
                                          item._objects.forEach(function(object) {
                                              if (object.text == dots_id) {
                                                  if (object && object.text != '') {
                                                      let asset_id = $('#asset_id').val();
                                                      
                                                         if(response.order){
                                                           object.group._objects[0].set("fill", '#5471BF');
                                                         } else {
                                                          object.group._objects[0].set("fill", '#9E9E9E');                                                      
                                                         }
                                                          object.group._objects[0].set("stroke", '#fff');
                                                          canvas.renderAll(); 
                                                  }
                                              }
                                          });
                                      }
                                  });
                                 get_seat_data($('#today_date').text()); 
                                 
                               }, 1000);

                             saveImage(1);   
                        }
                    },
                    'complete': function() {
                        hidePageSpinner();
                    }
                });

                
        });



      canvas.on("mouse:move", function(e) {
          if (!started_flag)
            return;
          if (down_flag === false)
            return;
          if (!e.target)
            return;
          if (e.target._objects.length === 2) {
            console.log(e.target._objects.length);
              if (e.target._objects[0].type === "circle") {
                   
                  showShadowToolBox();
              }
          }
          onMoving(e.target);
          
      });
      // var imageSaver = document.getElementById('btnSave');
      // imageSaver.addEventListener('click', saveImage, false);
      function saveImage(e) {
          var image_json = canvas.toJSON();
          var asset_id = $('#asset_id').val();
          $.ajax({
              url: base_url + '/updateassets_image/' + asset_id,
              type: "POST",
              dataType: 'json',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: {
                  canvas: JSON.stringify(image_json)
              },
              success: function(res) {
                  // rearrangeAssets();
              },
              error: function(err) {
                  console.log(err);
              }
          });
      }
      canvas.on("mouse:over", function(e) {
          if (!started_flag) return;
          if (!e.target) return;
          if (!e.target._objects) return;
          if (e.target._objects.length === 2) {
              if (e.target._objects[0].type === "circle") {
                    showShadowToolBox();
              }
          }
      });
      canvas.on("mouse:out", function(e) {
          hideShadowToolBox();
      });
  };

  function getassetsdetails(dots_id = '') {
      let asset_id = $('#asset_id').val();
      let booking_date = $('#today_date').text();
      $.ajax({
          url: base_url + "/check_user_seat_booking/" + asset_id + "/" + dots_id,
          type: "POST",
          data: {
              'booking_date': booking_date
          },
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          error: function(response) {
                   
              if (response.status == 400) {

                 if(response.responseJSON.message){
                    swal("Failed!",response.responseJSON.message, "error");
                } 
              }
          },
          success: function(res) {
              if (res.success) {
                  if (res.booked_seat) {
                      $('#bookedseat_modal').show();
                      $('#bookedseat_modal').modal({ backdrop: 'static', keyboard: false});
                      $('#view_booked_seat_details').html(res.html);
                  }
                  if (res.available) {
                      $("#seat_book_modal").modal("show");
                      $('#seat_book_details').html(res.html); 
                  }
                  if (res.exam) {
                      $("#user_exam_modal").modal("show");
                      $('#view_exam_details').html(res.html);
                  }

                   if (res.bloked) {
                      $("#blocked_seat_modal").modal("show");
                      $('#blocked_seat_details').html(res.html);

                  }
                  get_pophover();
                  $('[data-toggle="tooltip"]').tooltip();
              }
          },
      });
  }

  function get_seat_data(reserve_date){
         
        $.ajax({
        "headers":{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },
        method: 'get',
        url: base_url+'/get_seat_list',
        data: {
        "reserve_date": reserve_date,
        "asset_id" : $('#asset_id').val(),
        "office_id" :  $('#office_id').val(),
        },
        'beforeSend': function() {
        },
        'success' : function(response){
        $('.seats-list').html('');
        var html = '';
        if(response.status){

        $('.total_seats').text('Total : '+response.count_data.total_seats);
        $('.available_seats').text('Available : '+response.count_data.available_seats);
        $('.booked_seats').text('Reserved : '+response.count_data.booked_seats);;
        $('.blocked_seats').text('Blocked : '+response.count_data.blocked_seats);
        $('.pending_seats').text('Pending : '+response.count_data.pending_seats);
        }
        },
        'error' :  function(errors){
        console.log(errors);
        },
        complete: function () {
        },
        })
        
  }