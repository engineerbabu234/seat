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

    // function to handle click event of create new asset button
    function createButtonClicked() {
      if (currentOfficeIndex > -1)
        saveOfficeItemCanvasObject(currentOfficeIndex);

      $("#myModal").modal("show")
    }

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

    // function to handle click event of delete asset item button
    function deleteButtonClicked(id) {
      showSpinner();
      $.ajax({
        url: "http://34.246.156.37:1337/office-assets?assetId=" + id,
        type: "GET",
        success: function(res) {
          if (res.length > 0) {
            $.ajax({
              url: "http://34.246.156.37:1337/office-assets/" + res[0].id,
              type: "DELETE",
              success: function(res) {
                officeAssets.map(((value, index) => {
                  if (Number(value.id) === Number(id)) {
                    officeAssets.splice(index, 1);
                    edit_flag = true;
                    hideSpinner();
                    canvas.clear();
                    rearrangeAssets();
                  }
                }));
              },
              error: function(err) {
                hideSpinner();
                console.log(err)
              }
            });
          } else {
            officeAssets.map(((value, index) => {
              if (Number(value.id) === Number(id)) {
                officeAssets.splice(index, 1);
                canvas.clear();
                edit_flag = true;
                hideSpinner();
                rearrangeAssets();
              }
            }));
          }
        },
        error: function(err) {
          hideSpinner();
          console.log(err)
        }
      })


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
      clonedCircles.splice(0, clonedCircles.length);
      circleNums = 0;
      officeAssets.map(((value, index) => {
        if (id === value.id) {
          canvas.clear();
          canvas.loadFromJSON(officeAssets[index].canvas, canvas.renderAll.bind(canvas), function(o, object) {
            if (object._objects) {
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
      }));
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

      $('#btnSave').on("click", function() {
        hideCircleToolBox();
        saveOfficeAsset($('#asset_id').val());
      });

      // for (let i = 0; i < officeAssets.length; i++) {
      //     let assetItemEle = document.createElement("div");
      //     assetItemEle.className = "row mb-4 p-0 asset-item";
      //     let colEle = document.createElement("div");
      //     colEle.className = "col-lg-6 col-md-12 col-sm-12 p-1";
      //     let assetTitle = document.createElement("h5");
      //     assetTitle.className = "text-left";
      //     assetTitle.innerText = officeAssets[i].name;
      //     let colEle1 = document.createElement("div");
      //     colEle1.className = "col-lg-2 col-md-12 col-sm-12 p-1";
      //     let editButton = document.createElement("button");
      //     if (currentOfficeIndex === officeAssets[i].id) {
      //         editButton.className = "btn-assets btn-edit active";
      //         editButton.disabled = true;
      //     } else {
      //         editButton.className = "btn-assets btn-edit";
      //         editButton.disabled = false;
      //     }
      //     editButton.id = "btnEdit_" + officeAssets[i].id.toString();
      //     editButton.innerText = "Edit";
      //     let colEle2 = document.createElement("div");
      //     colEle2.className = "col-lg-2 col-md-12 col-sm-12 p-1";
      //     let deleteButton = document.createElement("button");
      //     if (currentOfficeIndex === officeAssets[i].id) {
      //         deleteButton.className = "btn-assets btn-delete";
      //         deleteButton.disabled = false;
      //     } else {
      //         deleteButton.className = "btn-assets btn-delete active";
      //         deleteButton.disabled = true;
      //     }
      //     deleteButton.id = "btnDelete_" + officeAssets[i].id.toString();
      //     deleteButton.innerText = "Delete";
      //     let colEle3 = document.createElement("div");
      //     colEle3.className = "col-lg-2 col-md-12 col-sm-12 p-1";
      //     let saveButton = document.createElement("button");
      //     if (currentOfficeIndex === officeAssets[i].id) {
      //         if (officeAssets[i].is_saved === true) {
      //             saveButton.className = "btn-assets btn-edit active";
      //             saveButton.innerText = "Saved";
      //             saveButton.disabled = true;
      //         } else {
      //             saveButton.className = "btn-assets btn-edit";
      //             saveButton.disabled = false;
      //             saveButton.innerText = "Save";
      //         }
      //     } else {
      //         if (officeAssets[i].is_saved === true) {
      //             saveButton.innerText = "Saved";
      //         } else {
      //             saveButton.innerText = "Save";
      //         }
      //         saveButton.className = "btn-assets btn-edit active";
      //         saveButton.disabled = true;
      //     }
      //     saveButton.id = "btnSave_" + officeAssets[i].id.toString();

      //     editButton.addEventListener("click", function () {
      //         hideCircleToolBox();
      //          editButtonClicked(officeAssets[i].id);
      //     });
      //     deleteButton.addEventListener("click", function () {
      //         hideCircleToolBox()
      //         deleteButtonClicked(officeAssets[i].id);
      //     });
      //     saveButton.addEventListener("click", function () {
      //         hideCircleToolBox();
      //         saveOfficeAsset(officeAssets[i].id);
      //     });

      //     colEle.appendChild(assetTitle);
      //     assetItemEle.appendChild(colEle);
      //     colEle1.appendChild(editButton);
      //     assetItemEle.appendChild(colEle1);
      //     colEle2.appendChild(deleteButton);
      //     assetItemEle.appendChild(colEle2);
      //     colEle3.appendChild(saveButton);
      //     assetItemEle.appendChild(colEle3)
      //     $("#assets-box").append(assetItemEle);
      // }
    }

    $('#btnSave').on('click', function(event) {
      event.preventDefault();
      saveOfficeAsset($('#asset_id').val());
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

    // function to save canvas data
    function saveOfficeAsset(id) {
      if (officeAssets.length === 0) {
        return false;
      }

      //showSpinner();

      saveOfficeItemCanvasObject(id);

      officeAssets.map(((value, index) => {

        return false;
        if (Number(value.id) !== Number(id))
          return;
        res = 0;
        if (res.length > 0) {
          $.ajax({
            url: "http://34.246.156.37:1337/office-assets/" + res[0].id.toString(),
            type: "PUT",
            data: {
              canvas: JSON.stringify(value.canvas)
            },
            success: function(res) {
              setSavedStatus(value.id);
              hideSpinner();
              rearrangeAssets();
            },
            error: function(err) {
              hideSpinner()
              console.log(err);
            }
          });
        } else {
          $.ajax({
            url: "http://34.246.156.37:1337/office-assets",
            type: "POST",
            data: {
              assetId: value.id,
              name: value.name,
              description: value.description,
              canvas: JSON.stringify(value.canvas)
            },
            success: function(res) {
              officeAssets[index].is_saved = true;
              hideSpinner();
              rearrangeAssets();
            },
            error: function(err) {
              hideSpinner();
              console.log(err);
            }
          });
        }
      }));
      return false;
      $.ajax({
        url: "http://34.246.156.37:1337/office-assets/?assetId=" + id.toString(),
        type: "GET",
        success: function(res) {

        },
        error: function(err) {
          hideSpinner();
          console.log(err)
        }
      });
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

    // function to handle click event of circle b
    // clone new next circle from circle b
    function cloneCircleB() {
      circleNums++;
      let newCircle = new fabric.Circle({
        radius: circleR,
        stroke: strokeCircleBC,
        fill: circleBC,
        selectable: false,
      });
      let newText = new fabric.Text(circleNums.toString(), {
        top: circleR,
        left: circleR,
        fontSize: circleF,
        selectable: false,
        textAlign: "center",
        originX: "center",
        originY: "center",
        fill: "#fff"
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
        hasBorders: false
      });

      clonedCircles.push(newGroup);

      canvas.add(newGroup);
      canvas.renderAll();
    }

    // function to handle click event of circle a
    // clone new next circle from circle a
    function cloneCircleA() {
      circleNums++;
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
        selectable: false,
        textAlign: "center",
        originX: "center",
        originY: "center",
        fill: "#fff"
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
        hasBorders: false
      });

      clonedCircles.push(newGroup);
      canvas.add(newGroup);
      canvas.renderAll();
    }

    // show circle tool box
    function showCircleToolBox(e) {
      $(".removeImg").attr("id", "remove-" + e.target._objects[1].text);
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
          console.log(res.data.id);

          var main_image = '';
          var canvas_data = res.data.asset_canvas;

          if (canvas_data !== null) {
            console.log('start canvas');
            main_image = res.data.asset_canvas;
            var json = main_image;
            canvas.clear();
            canvas.loadFromJSON(json, function() {
              canvas.renderAll();
            });

          } else {
            main_image = res.assets_image;
            console.log('start second');

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
        } else if (value.top === e.target.top) {
          drawHorizontal(value.top, value.left, e.target.left);
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

    // function to handle click event of create new asset button
    $("#btnCreate").click(function() {
      createButtonClicked();
    });

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

      $("#changeModal").modal("hide");
      canvas.renderAll();
    });

    // handler to load image from open file window
    $("#file-input").change(function(e) {
      if (e.target.files.length < 1)
        return;

      let file = e.target.files[0];
      let url = URL.createObjectURL(file);
      let fr = new FileReader();
      fr.addEventListener("load", function(e) {
        document.getElementById("img-preview").src = e.target.result;
      });
      fr.readAsDataURL(file);

      if ($("#new-title").val() !== "") {
        $("#btn-save").prop("disabled", false);
      } else {
        $("#btn-save").prop("disabled", true);
      }
    });

    $("#new-title").keyup(function() {
      if ($(this).val() === "") {
        $("#btn-save").prop("disabled", true);
      } else {
        if ($("#img-preview").attr("src") === "") {
          $("#btn-save").prop("disabled", true);
        } else {
          $("#btn-save").prop("disabled", false);
        }
      }
    });

    // trigger click event of input type file tag when image preview is clicked
    $(".img-preview").click(function() {
      $("#file-input").trigger("click");
    });

    var canvas_images = $('#canvas_image').val();
    if (canvas_images == 0) {
      console.log('start first');
      start($("#main_image").val(), $("#asset_name").val());
    }

    // create new office asset when click save button on modal
    $("#btn-save").click(async function() {
      removeMainContent();
      canvas.clear();

      let url = document.getElementById("img-preview").src;

      $("#myModal").modal("hide");

      currentOfficeIndex = getOfficeNextIndex();
      officeAssets.push({
        id: getOfficeNextIndex(),
        name: $("#new-title").val(),
        description: $("#new-description").val(),
        url: url,
        is_saved: false
      });

      edit_flag = true;
      rearrangeAssets();
      start(url, $("#new-title").val());

      document.getElementById("new-title").value = "";
      document.getElementById("img-preview").src = "";
      document.getElementById("new-description").value = "";
      document.getElementById("btn-save").setAttribute("disabled", "true");
    });

    $("#img-create").mouseover(function() {
      hideCircleToolBox()
      $(this).attr("src", "assets/images/green-seat.png");
    });

    $("#img-create").mouseleave(function() {
      hideCircleToolBox()
      $(this).attr("src", "assets/images/seat-grey.png");
    });

    // clone new circle from circle a when click circle a
    $("#img-create").click(function() {
      hideCircleToolBox()
      if (edit_flag === false)
        return;
      cloneCircleA();
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
          $("#changeModal").modal("show");
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
      removeRulers();
    });

    canvas.on("mouse:move", function(e) {

      if (!started_flag)
        return;
      if (down_flag === false)
        return;
      if (!e.target)
        return;

      //setUnSavedStatus(currentOfficeIndex);

      rearrangeAssets();
      if (!e.target._objects)
        return;
      if (e.target._objects.length === 2) {
        if (e.target._objects[0].type === "circle") {
          $("#changeModal").modal("hide");
          removeRulers();
          calculate(e);
          showCircleToolBox(e)
        }
      }
    });

    var imageSaver = document.getElementById('btnSave');
    imageSaver.addEventListener('click', saveImage, false);

    function saveImage(e) {
      var image_json = canvas.toJSON();
      var asset_id = $('#asset_id').val();
      console.log(image_json);
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