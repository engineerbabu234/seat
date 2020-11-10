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
      fireRightClick: true
    });

    initCenteringGuidelines(canvas);
    initAligningGuidelines(canvas);
    canvas.selectionColor = "transparent";
    canvas.selection = false;

    canvas.setWidth(contentW);
    canvas.setHeight(contentH);
 

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
          officeAssets[index].canvas = canvas.toJSON([
            "selectable",
            "lockMovementX",
            "lockMovementY",
            "lockScalingX",
            "lockScalingY",
            "lockRotation",
            "hasControls",
            "hasBorders"
          ]);
        }
      }));
    }

 

    // function to load canvas json object into canvas
    function loadOfficeItemCanvasObject(id) {
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

      if (mainImage)
        canvas.remove(mainImage)
    }

    // function to get max index of cloned circles
    function getOfficeNextIndex() {
      let num = 0;
      officeAssets.map(((value, index) => {
        if (Number(value.id) >= num)
          num = Number(value.id) + 1;
      }));
      return num;
    }

    // function to show spinner
    function showSpinner() {
      $("#spinner").show();
    }

    // function to hide spinner
    function hideSpinner() {
      $("#spinner").hide();
    }

    
    // function to handle to start edit asset
    function start(url, title = '') {

      clonedCircles.slice(0, clonedCircles.length);
      circleNums = 0;
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
    function cloneCircleA() {
       circleNums++;

      var last_id = $('#last_id').val();
    
      let newCircle = new fabric.Circle({ 
     
        radius: circleR,
        stroke: strokeCircleAC,
        fill: circleAC,
        selectable: false,  
      });  
     
      let newText = new fabric.Text(circleNums.toString(), { 
        top: circleR,
        left: circleR,
        fontSize: circleF,
        selectable: true,
        textAlign: "center",
        originX: "center",
        originY: "center",
        fill: "#fff",   
        subscript:{'seatid':54}
      });   

      
 
      let newGroup = new fabric.Group([newCircle, newText], {
        top: canvas.getHeight() / 2 - circleR,
        left: canvas.getWidth() / 2 - circleR,
        lockRotation: true,
        lockScalingX: true,
        lockScalingY: true,
        originX: "center",
        originY: "center",
        hasControls: false,
        hasBorders: false, 
       
      });



      clonedCircles.push(newGroup);
      canvas.add(newGroup);
      canvas.renderAll(); 
    }
 

    // show circle tool box
    function showCircleToolBox(e) {
     
      $(".removeImg").attr("id", "remove-" + e.target._objects[1].text);
      $(".removeImg").data("id",  e.target._objects[1].group.seatid);
      $(".removeImg").css("left", e.target.left + $("#main").position().left + 25);
      $(".removeImg").css("top", e.target.top + 25);
      $(".removeImg").css("display", "block");
      $(".dotsImg").attr("id", "dots-4" );
      //$(".dotsImg").attr("id", "dots-" + e.target._objects[1].text);
      $(".dotsImg").data("id", e.target._objects[1].group.seatid);
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
        url: base_url + "/getofficeassetsinfo/" + id,
        type: "GET",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
           
          var main_image = '';
          var canvas_data = res.data.asset_canvas;   
          if (canvas_data !== null && canvas_data.length > 0 && canvas_data !== undefined ) {
              var candata = jQuery.parseJSON(canvas_data );
              if( candata.objects.length !== 0 ){
              main_image = res.data.asset_canvas;
              var json = main_image;
               
              canvas.clear();
              canvas.loadFromJSON(json, function() { 
                canvas.renderAll();
              });

              loadOfficeItemCanvasObject(0);
              $('#is_edit').val('yes');
            } else {
                 start($("#main_image").val(), $("#asset_name").val());
            }
          } else {
            main_image = res.assets_image;
            start($("#main_image").val(), $("#asset_name").val());
          }

          hideSpinner();
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
        if (e.target === value)
          return;

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
        if (value._objects.length < 2)
          return; 
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

        $("#bookseatModal").modal("hide");
       canvas.renderAll();
    });
 
    // trigger click event of input type file tag when image preview is clicked
    $(".img-preview").click(function() {
      $("#file-input").trigger("click");
    });

    var canvas_images = $('#canvas_image').val();

    if (canvas_images == 0) {
      start($("#main_image").val(), $("#asset_name").val());
    }
 
    // clone new circle from circle a when click circle a
    $("#img-create").click(function() {
      hideCircleToolBox();
        console.log('clone user');
      // if (edit_flag === false)
      //   return;
        getlastdata($('#asset_id').val()); 
        setTimeout(function() {
            cloneCircleA();
        }, 1000);
         
    });

     

    $('.add-booking-seat').on('click', function(event) {
        event.preventDefault();  
        setTimeout(function() { 
           
        }, 2000);
        
        
    });

    // handler for delete icon click event
    $(".removeImg").click(function() {


      let id = $(this).attr("id").split("-")[1]; 
      clonedCircles.map(((value, index) => { 
                if (value._objects[1].text === id) {
                  canvas.remove(clonedCircles[index])
                  hideCircleToolBox();
                }
              }));

      
     
    });

    function remove_objects(id){
        clonedCircles.map(((value, index) => { 
                if (value._objects[1].text === id) {
                  canvas.remove(clonedCircles[index])
                  hideCircleToolBox();
                }
              }));
    }

    $(".dotsImg").click(function() {

      let id = $(this).attr("id").split("-")[1];
      let dots = $(this).attr("id");
      let seat_id = $(this).attr("id").split("-")[1];
      let asset_id = $('#asset_id').val(); 
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
          $("form#add-office-asset-image-form").find("#dots_id").val(dots);  
            var edit_seat = $('.dotsImg').hasClass('editSeat');
          
          
            $("#bookseatModal").modal("show");
          // $('#office_assets_seats').html("");
          // $('#assets_seat_modal').modal('hide');
            
        }
      }));
    });



    canvas.renderAll();

    
    // add listener for click event on canvas
    canvas.on("mouse:down", function(e) {
       
      if (!started_flag)
        return;
      hideCircleToolBox();
      down_flag = true;
      if (!e.target)
        return;
      if (!e.target._objects)
        return;
      if (e.target._objects.length === 2) {
        if (e.target._objects[0].type === "circle") {
          showCircleToolBox(e)
          return;
        }
      }
      hideCircleToolBox();
    });

    canvas.on("mouse:up", function(e) {
      down_flag = false;
      if (e.target) {
        //clicked on object 
         console.log(e.target._objects[0]);
         getassetsdetails();
      }else{
        //add rectangle
        
      }
      removeRulers();
    });

    canvas.on("mouse:move", function(e) {
       
      // if (!started_flag)
      //   return;
      // if (down_flag === false)
      //   return;
      // if (!e.target)
      //   return;
      
      //setUnSavedStatus(currentOfficeIndex);
      

    if (!e.target._objects)
        return;
      if (e.target._objects.length === 2) {
        if (e.target._objects[0].type === "circle") {
            ;
          $("#changeModal").modal("hide");
          removeRulers();
          calculate(e);
          showCircleToolBox(e)
        }
      }
    });

    // var imageSaver = document.getElementById('btnSave');
    // imageSaver.addEventListener('click', saveImage, false);

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


    function bookseats(){
      
      
    }


    canvas.on("mouse:over", function(e) {
     
      if (!started_flag)
        return;

      if (!e.target)
        return;


      if (!e.target._objects)
        return;

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

  

  function delete_object(){ 
     swal({
          title: "Are you sure you want to delete?",
          //text: "Once deleted, you will not be able to recover this office data!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
         })
         .then((willDelete) => {
            if(!willDelete){
                return false;
            }
              
              swal("Success!",'Seat Removed', "success");

                // $.ajax({
                //     "headers":{
                //     'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                // },
                //     'type':'get',
                //     'url' : url,
                // beforeSend: function() {
                // },
                // 'success' : function(response){
                //     if(response.status == 'success'){
                      
                        
                //     }
                //     if(response.status == 'failed'){
                //         swal("Failed!",response.message, "error");
                //     }
                // },
                // 'error' : function(error){
                // },
                // complete: function() {
                // },
                // });
         });
  }


   // load data from strapi
    function getlastdata(id) {

      // showSpinner();
        $.ajax({
        url: base_url + "/admin/office/asset/getAssetsSeats/" + id,
        type: "GET",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
           
            $('#total_count').val(res.seat_count);
            $('#last_id').val(res.last_id);
          
        },
        error: function(err) {
          console.log(err); 
        }
      });

     
    }


     function getassetsdetails() {

        let asset_id = $('#asset_id').val();
        $.ajax({
        url: base_url + "/getassetsdetails/" + asset_id,
        type: "GET",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(res) {
            $("#bookseatModal").modal("show");  
            $('#building_name').text(res.data.building_name);
            $('#office_name').text(res.data.office_name);
            $('#asset_name').text(res.data.title); 
        },
        error: function(err) {
          console.log(err); 
        }
      });

     
    }