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
          circleS = 18 * scaleX,
          circleR = 22 * scaleX,
          circleF = 26 * scaleX,
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
          fireRightClick: true
      });
      initCenteringGuidelines(canvas);
      initAligningGuidelines(canvas);
      canvas.selectionColor = "transparent";
      canvas.selection = false;
      canvas.setWidth(contentW);
      canvas.setHeight(contentH);

       var state = true;
         var indicator = 0;

  //$(document).on("click", ".add-booking-seat", function(e) {
    $(".add-booking-seat").unbind().click(function(e) {
    e.preventDefault(); 
     
             var data = jQuery(this).parents('form:first').serialize();
             
            var myThis = $(this);
            $.ajax({
                url: base_url + '/admin/office/asset/addseat',
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
                       showSpinner();
                        $("form#add-office-asset-image-form")[0].reset();
                        $('.dotsImg').data('seat_id', response.id);
                        $('.dotsImg').data('status', response.status);
                        success_alert(response.message);
                        //swal("Success!", response.message, "success");
                        $('#seat_ids').val(response.id);
                        $('#btnSave').click();
                        $('.error').removeClass('text-danger');
                        $('.error').text('');
                        $('#changeModal').modal('hide'); 

                        
                        cloneCircleA(response.seat_no);     
                          
                    }
                },
            });
        });

      function setSavedStatus(id) {
          officeAssets.map(((value, index) => {
              if (Number(value.id) === Number(id)) {
                  officeAssets[index].is_saved = true;
              }
          }));
      }

      function setUnSavedStatus(id) {
          officeAssets.map(((value, index) => {
              if (Number(value.id) === Number(id)) {
                  officeAssets[index].is_saved = false;
              }
          }));
      }
      // function to handle click event of edit asset item button
      function editButtonClicked(id) {
          started_flag = true;
          saveOfficeItemCanvasObject(currentOfficeIndex);
          loadOfficeItemCanvasObject(id);
          canvas.renderAll();
          edit_flag = true;
          officeAssets.map(((value, index1) => {
              if (Number(value.id) === Number(id)) {
                  edit_flag = true;
                  currentOfficeIndex = id;
                  // officeAssets[index1].is_saved = false;
                  rearrangeAssets();
              }
          }));
      }
      // function to save canvas json data of office item
      function saveOfficeItemCanvasObject(id) {
          officeAssets.map(((value, index) => {
              if (id === value.id) {
                  officeAssets[index].canvas = canvas.toJSON(["selectable", "lockMovementX", "lockMovementY", "lockScalingX", "lockScalingY", "lockRotation", "hasControls", "hasBorders"]);
              }
          }));
      }
      // function to load canvas json object into canvas
      function loadOfficeItemCanvasObject(id) {
          let total_object = canvas._objects;
          console.log('cloned circle');
          clonedCircles.splice(0, clonedCircles.length);
          circleNums = 0;
          canvas.clear();
          canvas.loadFromJSON(canvas, canvas.renderAll.bind(canvas), function(o, object) {
              if (object._objects) {
                  console.log(bject._objects[1].name);
                  if (object._objects.length === 2) {
                      if (object._objects[0].type === "circle") {
                          if (object._objects[1].text !== "B" && object._objects[1].text !== "A") {
                              clonedCircles.push(object);
                              circleNums++;
                          }
                      }
                  }
              }
          });
          canvas.renderAll();
      }
      // function to set disable on edit button and delete button
      function setDisableButton(editButton, deleteButton) {
          editButton.disabled = true;
          editButton.style.opacity = 0.5;
          deleteButton.disabled = true;
          deleteButton.style.opacity = 0.5
      }
      // function to set enable on edit button and delete button
      function setEnableButton(editButto, deleteButton) {
          editButton.disabled = false;
          editButton.style.opacity = 1;
          deleteButton.disabled = false;
          deleteButton.style.opacity = 1;
      }
      // function to set disable on save button
      function setDisableSaveButton() {
          $("#btnSave").css("opacity", 0.5);
          $("#btnSave").attr("disabled", true);
      }
      // function to set enable button
      function setEnableSaveButton() {
          $("#btnSave").css("opacity", 1);
          $("#btnSave").attr("disabled", false);
      }
      // function to redraw asset buttons
      function redrawAreaMapButtons(item) {
          officeAssets.map(((value, index1) => {
              if (Number(value.id) === Number(item.id)) {
                  setDisableButton(value.editButton, value.deleteButton);
              } else {
                  setEnableButton(value.editButton, value.deleteButton);
              }
          }));
      }
      //saveOfficeAsset($('#asset_id').val());
      // function to re arrange office assets items
      function rearrangeAssets() {
          $("#assets-box").empty();
      }
      $('#btnSave').on('click', function(event) {
          event.preventDefault();
          saveImage(event);
      });
      // function remove main content
      function removeMainContent() {
          clonedCircles.map((value => {
              canvas.remove(value)
          }));
          if (mainImage) canvas.remove(mainImage)
      }
      // function to get max index of cloned circles
      function getOfficeNextIndex() {
          let num = 0;
          officeAssets.map(((value, index) => {
              if (Number(value.id) >= num) num = Number(value.id) + 1;
          }));
          return num;
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

      //add horizontal line 
      $('.add_line_for_seats').on('click' , function(event) {
          event.preventDefault();  
              
              var line = new fabric.Line([160, 100, 350, 100], {stroke: 'black',  strokeWidth: 3, strokeDashArray: [10, 5]});
              var line2 = new fabric.Line([350, 100, 330, 110], {stroke: 'black',  strokeWidth: 3});
              var line3 = new fabric.Line([350, 100, 330, 90], {stroke: 'black',  strokeWidth: 3});
              var strele = new fabric.Group([line, line2, line3], {originX: "center", originY: "center"});
              var tekst = new fabric.IText('234', {left: 160,  top: 70,  fontFamily: 'Arial',fill: 'red',  fontSize: 20, selectable: false,
                originX: "center", originY: "center"
              });
              canvas.add(strele, tekst);
              canvas.setActiveObject(canvas.item(0));
              canvas.renderAll(); 
      });

      // function to handle to start edit asset
      function start(url, title = '') {
          clonedCircles.slice(0, clonedCircles.length);
          //circleNums = 0;
          canvas.set("fill", "#fff");
          edit_flag = true;
          setEnableSaveButton();
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
      // clone new next circle from circle a

      var circle_size = '';  
      function cloneCircleA(circle_ids) {
            
          var circle_type = $('#circle_size :selected').val();
          if(circle_type == 1){
            circle_size = circleS;
          } else if(circle_type == 2){
              circle_size = circleR;
          } else{
             circle_size = circleF;
          }            

          let newCircle = new fabric.Circle({
              radius: circle_size,
              stroke: strokeCircleAC,
              fill: circleAC,
              selectable: false,
          });
          let newText = new fabric.Text(circle_ids.toString(), {
              top: circle_size,
              left: circle_size,
              fontSize: circle_size,
              selectable: true,
              textAlign: "center",
              originX: "center",
              originY: "center",
              fill: "#fff",
          });
          let newGroup = new fabric.Group([newCircle, newText], {
              top: canvas.getHeight() / 2 - circle_size,
              left: canvas.getWidth() / 2 - circle_size,
              lockRotation: true,
              lockScalingX: true,
              lockScalingY: true,
              originX: "center",
              originY: "center",
              hasControls: false,
              hasBorders: false,
          });
          // canvas.on('object:modified', function (e) {                             
          //     alert(e.target.id);
          //     alert(e.target.name);
          // });
          clonedCircles.push(newGroup);
          canvas.add(newGroup);
          canvas.renderAll();
          hideSpinner()
          saveImage(1);
         

      }
      // show circle tool box
      function showCircleToolBox(e) {
          // console.log(e.target._objects[1]);
          $(".removeImg").attr("id", "remove-" + e.target._objects[1].text);
          $(".removeImg").data("id", e.target._objects[1].group.seatid);
          $(".removeImg").css("left", e.target.left + $("#main").position().left + 25);
          $(".removeImg").css("top", e.target.top + 25);
          $(".removeImg").css("display", "block");
          $(".dotsImg").attr("id", "dots-" + e.target._objects[1].text);
          $(".dotsImg").css("left", e.target.left + $("#main").position().left - 20);
          $(".dotsImg").css("top", e.target.top + 25);
          $(".dotsImg").css("display", "block");
      }
      // hide circle tool box
      function hideCircleToolBox() {
          $(".removeImg").css("display", "none");
          $(".dotsImg").css("display", "none");
      }
      //getcanvas iamge
      loadDataFromStrapi($('#asset_id').val());
      // load data from strapi
      function loadDataFromStrapi(id) {
          // showSpinner();
          $.ajax({
              url: base_url + "/admin/office/asset/getofficeassetsinfo/" + id,
              type: "GET",
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              success: function(res) {
                  var main_image = '';

                  if(res.data.asset_type){
                      if(res.data.asset_type == 1){
                          $('.assets_type_button').text('Click To Add Desk');
                          $('.assets_type_button').attr('title','Add Desk'); 
                          $('.modal_title').text('Configure Seat');
                          $('.deskattributes').show();
                          $('.carparkspace').hide();
                          $('.meetingspace').hide();
                          $('.collaboration_space').hide();
                      } else if(res.data.asset_type == 2){
                          $('.assets_type_button').text('Click To Add Carpark Space');
                          $('.assets_type_button').attr('title','Add Carpark Space');
                           $('#seat_no').attr('placeholder','Space No'); 
                           $('.seat_no_header').attr('data-content','Space No');
                           $('.seat_no_header').attr('title','Space');
                          $('.modal_title').text('Configure Carpark Space');
                          $('.carparkspace').show();
                           $('.deskattributes').hide();
                           $('.meetingspace').hide();
                             $('.collaboration_space').hide();
                      } else if(res.data.asset_type == 3){
                          $('.assets_type_button').text('Click To Add Collaboration Space');
                          $('.assets_type_button').attr('title','Add Collaboration Space');
                          $('.modal_title').text('Configure Collaboration Space'); 
                          $('#seat_no').attr('placeholder','Standing No');
                           $('.seat_no_header').attr('title','Standing No');
                           $('.seat_no_header').attr('data-content','Standing No');
                          $('.deskattributes').hide();
                          $('.carparkspace').hide();
                          $('.meetingspace').hide();
                            $('.collaboration_space').show();
                      } else if(res.data.asset_type == 4){
                          $('.assets_type_button').text('Click To Add Meeting Room Space');
                          $('.assets_type_button').attr('title','Add Meeting Room Space');
                          $('.modal_title').text('Configure Meeting Room Space');
                          $('.meetingspace').show();
                          $('.deskattributes').hide();
                          $('.carparkspace').hide();
                          $('.collaboration_space').hide();
                      }
                  }

                  get_pophover();

                  $(".telephone_no").hide();  
                  $('.telephone').change(function () {
                      if($("#telephone").is(':checked')){
                         
                          $(".telephone_no").show();   
                      }
                      else {
                          $(".telephone_no").hide();  
                      }
                 });

                  
                  $('#last_id').val(res.last_id);
                  var canvas_data = res.data.asset_canvas;
                  if (canvas_data !== null && canvas_data.length > 0 && canvas_data !== undefined) {
                      var candata = jQuery.parseJSON(canvas_data);
                      if (candata.objects.length !== 0) {
                          main_image = res.data.asset_canvas;
                          var json = main_image;
                          canvas.clear();
                          canvas.loadFromJSON(json, function() {
                              canvas.renderAll();
                          });
                          //loadOfficeItemCanvasObject(0);
                          $('#is_edit').val('yes');

                            setTimeout(function() {
                                obj = canvas.getObjects(); 

                                  obj.forEach(function(item, i) { 
                                      if (item._objects) { 

                                        item._objects.forEach(function(object) { 
                                                 
                                                  if (object && object.text != '') {

                                                    if (res.seat_info) { 
                                                       
                                                        $.each(res.seat_info, function(index, val) { 
                                                          if (object.text == val.seat_no) { 

                                                                if(val.seat_type == 2){ 
                                                                  item._objects[0].set("fill", '#ff0000');    
                                                                  } else { 
                                                                  item._objects[0].set("fill", '#84AA4F');
                                                                } 
                                                            }   
                                                         }); 
                                                    canvas.renderAll();           
                                                        
                                                  }   
                                                       
                                                  }
                                              
                                          });
                                           
                                      }
                                  });
                                saveImage(1);
                            }, 1000);
                                 
                      } else {
                          start($("#main_image").val(), $("#asset_name").val());
                      }
                  } else {
                      main_image = res.assets_image;
                      start($("#main_image").val(), $("#asset_name").val());
                  }
                  //hideSpinner();
                  rearrangeAssets();
              },
              error: function(err) {
                  console.log(err);
                  hideSpinner();
              }
          });
      }

 



      function calculate(e) {
          canvas.getObjects().map(((value, index) => {
              if (e.target === value) return;
              if (value.left === e.target.left) {
                  drawVertical(value.left, value.top, e.target.top);
                  console.log('vertical');
              } else if (value.top === e.target.top) {
                  drawHorizontal(value.top, value.left, e.target.left);
                  console.log('horizontal');
              }
          }))
      }

      function drawHorizontal(top, left1, left2) {
          let left;
          let leftOrigin;
          if (left1 > left2) {
              left = left1 - left2;
              leftOrigin = left2;
          } else {
              left = left2 - left1;
              leftOrigin = left1
          }
          let ruler = new fabric.Text(left.toFixed(2).toString(), {
              top: top,
              left: leftOrigin + left / 2 - circleR / 2,
              fill: "#000",
              fontSize: 11,
              selectable: false
          });
          rulers.push(ruler);
          canvas.add(ruler)
      }

      function drawVertical(left, top1, top2) {
          let top;
          let topOrigin;
          if (top1 > top2) {
              top = top1 - top2;
              topOrigin = top2;
          } else {
              top = top2 - top1;
              topOrigin = top1;
          }
          let ruler = new fabric.Text(top.toFixed(2).toString(), {
              top: topOrigin + top / 2,
              left: left,
              fill: "#000",
              fontSize: 11,
              selectable: false
          });
          rulers.push(ruler);
          canvas.add(ruler)
      }
      // function to remove rulers on canvas
      function removeRulers() {
          rulers.map(((value, index) => {
              canvas.remove(rulers[index])
          }));
      }
      // function to set saved status
      function setSavedStatus(id) {
          officeAssets.map(((value, index) => {
              if (Number(value.id) === Number(id)) {
                  officeAssets[index].is_saved = true;
              }
          }));
      }
      // function to set unsaved status
      function setUnSavedStatus(id) {
          officeAssets.map(((value, index) => {
              if (Number(value.id) === Number(id)) {
                  officeAssets[index].is_saved = false;
              }
          }));
      }
      // handler for click event of status change button
      $("#btn-change").click(function() {
          let num = $("#change-number").text();
          clonedCircles.map(((value, index) => {
              if (value._objects.length < 2) return;
              if (value._objects[1].text === num) {
                  if (value._objects[0].fill === circleBC) {
                      value._objects[0].set("fill", circleAC);
                      value._objects[0].set("stroke", strokeCircleAC)
                  } else if (value._objects[0].fill === circleAC) {
                      value._objects[0].set("fill", circleBC);
                      value._objects[0].set("stroke", strokeCircleBC)
                  }
              }
          }));
          $("#changeModal").modal("hide");
          canvas.renderAll();
      });
      // trigger click event of input type file tag when image preview is clicked
      $(".img-preview").click(function() {
          $("#file-input").trigger("click");
      });

     
     

      // clone new circle from circle a when click circle a
      $("#img-create").click(function() {
          saveImage(1);
        
          hideCircleToolBox()
          //if (edit_flag === false) return;
           loadDataFromStrapi($('#asset_id').val()); 
          $("#changeModal").modal("show");
          $('#seat_assets_id').val($('#asset_id').val()); 

        $('.timepicker').datetimepicker({
           datepicker:false,
           formatTime:"h:i A",
           step:30,
           format:"h:i A"
        });

        $("input[name$='seat_clean']").click(function() {
            var cleantime = $(this).val();
            if(cleantime == 1){
                $(".cleantime").show();
            } else {
                $(".cleantime").hide();
            }
          });

          
      });
     
      $('.add-booking-seat').on('click', function(event) {
          event.preventDefault();
          setTimeout(function() {}, 2000);
      });
      // handler for delete icon click event
      $(".removeImg").click(function() {
          let id = $(this).attr("id").split("-")[1];
          let asset_id = $(this).data("asset");
          remove_objects(asset_id, id);
          clonedCircles.map(((value, index) => {
              if (value._objects[1].text === id) {
                  canvas.remove(clonedCircles[index])
                  hideCircleToolBox();
                  delete_object(assets_id, id);
                  saveImage(1);
              }
          }));
      });

      function remove_objects(assets_id, id) {
          obj = canvas.getObjects();
          obj.forEach(function(item, i) {
              if (item._objects) {
                  item._objects.forEach(function(object) {
                      if (object.text == id) {
                          swal({
                              title: "Are you sure",
                              text: "you want to remove this seat ?",
                              icon: 'warning',
                              dangerMode: true,
                              buttons: {
                                  cancel: "No",
                                  delete: "Yes"
                              }
                          }).then(function(willDelete) {
                              if (willDelete) {
                                  canvas.remove(item);
                                  $('#remove-' + id).hide();
                                  $('#dots-' + id).hide();
                                  delete_object(assets_id, id);
                                  saveImage(1);
                              }
                          });
                      }
                  });
              }
          });
          
      }
      $(".dotsImg").click(function() {
          let id = $(this).attr("id").split("-")[1];
          let dots = $(this).attr("id");
          let seat_id = $(this).attr("id").split("-")[1];
          let asset_id = $('#asset_id').val();
          getlastdata(asset_id, seat_id);
          setTimeout(function() {
              if ($('#total_count').val() == false) {
                  console.log(asset_id);
                  console.log(seat_id);
                  console.log($('#total_count').val());
                  $("#changeModal").modal("show");
              } else {
                  openOfficeSeats($('#seat_ids').val());
              }
          }, 1000);
          clonedCircles.map(((value, index) => {
              if (value._objects[1].text === id) {
                  if (value._objects[0].fill === circleBC) {
                      $("#btn-available").css("background-color", circleBC);
                      $("#btn-available").text("Blocked");
                  } else if (value._objects[0].fill === circleAC) {
                      $("#btn-available").css("background-color", circleAC);
                      $("#btn-available").text("Available");
                  }
                  $("#change-number").text(value._objects[1].text);
                  $("form#add-office-asset-image-form").find("#dots_id").val(id);
                  var edit_seat = $('.dotsImg').hasClass('editSeat');
              }
          }));
      });

      function saveImage(e) {
          var image_json = canvas.toJSON();
          var asset_id = $('#asset_id').val();
          $.ajax({
              url: base_url + '/admin/office/asset/updateassets_image/' + asset_id,
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
      canvas.renderAll();
      // add listener for click event on canvas
      canvas.on("mouse:down", function(e) {
          if (!started_flag) return;
          hideCircleToolBox();
            
          down_flag = true;
          if (!e.target) return;
          if (!e.target._objects) return;
          if (e.target._objects.length === 2) {
              if (e.target._objects[0].type === "circle") {
                  showCircleToolBox(e)
                  return;
              }
          }
          hideCircleToolBox();
      });


    function stopDragging(element) {
        if(typeof element !== "undefined")
        {              
          element.hasControls = false;
          element.hasBorders = false;  
        }
  }

  function set_controll(element) {
              element.lockAspectRatio = true;
              element.lockUniScaling = true;
              element.hasControls = true;
              element.hasBorders = false;  
  }

  var timeoutTriggered = false;
  function onMoving(e) {
    if (!timeoutTriggered) { 
      
       setTimeout(function() {
        stopDragging(e.target);
      }, 50);
      timeoutTriggered = true;
    }
  }
   
      
      canvas.on("mouse:up", function(e) {
          down_flag = false;
          if (e.target) {
           // stopDragging(e.target);
              
             // onMoving(e);
              // console.log(e.target._objects);
              if (e.target._objects && e.target._objects[1].text != '') {
                  let asset_id = $('#asset_id').val();
                  getlastdata(asset_id, e.target._objects[1].text);
                  setTimeout(function() {
                      let status = $('#seat_status').val();
                      let seat_type = $('#seat_type').val();
                       
                      if (seat_type != '' && seat_type == 2) {
                          e.target._objects[0].set("fill", '#ff0000');
                          e.target._objects[0].set("stroke", '#fff');
                          canvas.renderAll();
                      } else{
                          e.target._objects[0].set("fill", '#84AA4F');
                          e.target._objects[0].set("stroke", '#fff');
                          canvas.renderAll();
                      }
                  }, 1000);
                      saveImage(1);
              } else {
                set_controll(e.target);
              }
               saveImage(1);
          } else {
              //add rectangle
          }
      });

      
      $(document).on("click", ".edit-booking-seat", function(e) {
          
          let dots_id = $(this).attr('dot-id');

         let seat_type_change = $(".seat_type_change").val();
           
          obj = canvas.getObjects();
           
          obj.forEach(function(item, i) {
              if (item._objects) {
                  item._objects.forEach(function(object) {
                      if (object.text == dots_id) {
                          console.log(object)
                          if (object && object.text != '') {
                              let asset_id = $('#asset_id').val();

                              setTimeout(function() { 
                                   if (seat_type_change != '' && seat_type_change == 2) {
                                      item._objects[0].set("fill", '#ff0000');
                                      item._objects[0].set("stroke", '#fff');
                                      canvas.renderAll();
                                  } else {
                                    item._objects[0].set("fill", '#84AA4F');
                                      item._objects[0].set("stroke", '#fff');
                                      canvas.renderAll();
                                  }
                                 saveImage(1);  
                                  
                              },2000);
                          }
                      }
                  });
              }
          });
      });


        $(document).on("change", "#circle_size1", function(e) {
          
            var circle_type = $('#circle_size :selected').val();
            if(circle_type == 1){
              circle_size = circleS;
            } else if(circle_type == 2){
                circle_size = circleR;
            } else{
               circle_size = circleF;
            }        

              obj = canvas.getObjects();
               
              obj.forEach(function(item, i) { 

                  if (item._objects) {
                     item._objects.set("radius",circle_size);
                      item._objects.forEach(function(object) { 
                              console.log(object)
                              if (object && object.text != '') { 
                                 item._objects.set("top", circle_size);
                                 item._objects.set("left", circle_size);
                                 item._objects.set("fontSize", circle_size); 
                                  canvas.getHeight() / 2 - circle_size;
                                  canvas.getWidth() / 2 - circle_size; 
                                  // canvas.renderAll();                                      
                                  //    saveImage(1);                                       
                                
                              }
                         
                      });
                  }
              });
      });

      
      canvas.on("mouse:move", function(e) {
          // if (!started_flag) return;
          // if (down_flag === false) return;
          // if (!e.target) return;
          // setUnSavedStatus(currentOfficeIndex);
          // rearrangeAssets();
          //if (!e.target._objects) return;
           
          if (e.target._objects.length === 2) {
              if (e.target._objects[0].type === "circle") {
                  $("#changeModal").modal("hide");
                  removeRulers();
                  //calculate(e);
                  stopDragging(e.target);
                  showCircleToolBox(e)
              }
          }
      });
      canvas.on("mouse:over", function(e) {
          if (!started_flag) return;
          if (!e.target) return;
          if (!e.target._objects) return;
          if (e.target._objects.length === 2) {
                 
              if (e.target._objects[0].type === "circle") {
                  $("#changeModal").modal("hide");
                  showCircleToolBox(e)
              }
          }
      });
      canvas.on("mouse:out", function(e) {
          // hideCircleToolBox()
      });
  };

  function delete_object(assets_id, id) {
       
      let urls = base_url + '/admin/office/asset/deleteSeat/' + assets_id + '/' + id;
      $.ajax({
          "headers": {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          'type': 'post',
          'url': urls,
          'success': function(response) {
              if (response.status == 'success') {
                success_alert(response.message);
              }
              if (response.status == 'failed') {
                    error_alert(response.message);
                 // swal("Failed!", response.message, "error");
              }
          },
          'error': function(error) {},
          complete: function() {},
      });
  }
  
  // load data from strapi
  function getlastdata(assets_id, dots_id) {
      $.ajax({
          url: base_url + "/admin/office/asset/getAssetsSeats/" + assets_id + '/' + dots_id,
          type: "GET",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(res) { 

                  
              $('#total_count').val(res.seat_count);
              $('#seat_ids').val(res.seat_id);
              $('#seat_status').val(res.status);
              $('#seat_type').val(res.seat_type);
          },
          error: function(err) {
              console.log(err);
          }
      });
  }
