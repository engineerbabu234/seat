window.onload = function() {
    let today = new Date();
    $("#date").datepicker({
        minDate: today,
        dateFormat: "yy-mm-dd"
    });

    // initialize coordinate and size
    let contentW = $("#canvas").width(),
        contentH = $("#canvas").height();

    // initialize color of objects on canvas
    let circleAC = "#62AF57",
        circleBC = "#DA0000",
        circleGC = "#7E7E7E";

    // dynamic canvas object
    let officeAssets = [];
    let clonedCircles = [];
    let currentOfficeIndex = -1;
    let reservedData = [];

    // flags of status
    let started_flag = false;

    // initialize canvas
    let canvas = new fabric.Canvas("canvas", {
        controlsAboveOverlay: true,
        preserveObjectStacking: true,
        fireRightClick: true
    });
    canvas.setWidth(contentW);
    canvas.setHeight(contentH);

    let pickDate = null;

    // function to set save status
    function setSavedStatus(id) {
        officeAssets.map(((value, index) => {
            if (Number(value.id) === Number(id)) {
                officeAssets[index].is_saved = true;
            }
        }));
    }

    // function to set un saved status
    function setUnSavedStatus(id) {
        officeAssets.map(((value, index) => {
            if (Number(value.id) === Number(id)) {
                officeAssets[index].is_saved = false;
            }
        }));
    }

    // function to handle click event of edit asset item button
    function editButtonClicked(id) {
        showSpinner();
        started_flag = true;
        loadOfficeItemCanvasObject(id);
        officeAssets.map(((value, index1) => {
            if (Number(value.id) === Number(id)) {
                currentOfficeIndex = id;
                rearrangeAssets();
                hideSpinner();
            }
        }));
    }

    // function to load canvas json object into canvas
    function loadOfficeItemCanvasObject(id) {
        clonedCircles.splice(0, clonedCircles.length);

        $.ajax({
            url: "http://34.246.156.37:1337/seat-reservations/?assetId=" + id.toString() + "&date=" + pickDate,
            type: "GET",
            success: function (res) {
                let seatNum = null;
                if (res.length > 0)
                    seatNum = res[0].seatNum;
                officeAssets.map(((value, index) => {
                    if (id === value.id) {
                        canvas.clear();
                        canvas.loadFromJSON(officeAssets[index].canvas, canvas.renderAll.bind(canvas), function (o, object) {
                            if (object._objects) {
                                if (object._objects.length === 2) {
                                    if (object._objects[0].type === "circle") {
                                        if (seatNum) {
                                            reservedData.push({
                                                assetId: id,
                                                seatNum: seatNum,
                                                date: pickDate
                                            })
                                            console.log(object._objects)
                                            if (Number(object._objects[1].text) === Number(seatNum)) {
                                                object._objects[0].set("fill", circleGC);
                                            }
                                        }
                                        object.set("selectable", false)
                                        clonedCircles.push(object);
                                    }
                                }
                            } else if (object.type === "image") {
                                object.set("selectable", false);
                            }
                        });
                        canvas.renderAll();
                    }
                }));
            },
            error: function (err) {
                console.log(err);
            }
        });
    }

    // function to re arrange office assets items
    function rearrangeAssets() {
        $("#assets-box").empty();

        for (let i = 0; i < officeAssets.length; i++) {
            let assetItemEle = document.createElement("div");
            assetItemEle.className = "row mb-4 p-0 asset-item";
            let colEle = document.createElement("div");
            colEle.className = "col-lg-6 col-md-12 col-sm-12 p-1";
            let assetTitle = document.createElement("h5");
            assetTitle.className = "text-left";
            assetTitle.innerText = officeAssets[i].name;
            let colEle1 = document.createElement("div");
            colEle1.className = "col-lg-3 col-md-12 col-sm-12 p-1";
            let editButton = document.createElement("button");
            if (currentOfficeIndex === officeAssets[i].id) {
                editButton.className = "btn-assets btn-edit active";
                editButton.disabled = true;
            } else {
                editButton.className = "btn-assets btn-edit";
                editButton.disabled = false;
            }
            editButton.id = "btnEdit_" + officeAssets[i].id.toString();
            editButton.innerText = "Select";
            let colEle2 = document.createElement("div");
            colEle2.className = "col-lg-3 col-md-12 col-sm-12 p-1";
            let saveButton = document.createElement("button");
            if (officeAssets[i].is_saved === true) {
                saveButton.className = "btn-assets btn-edit active";
                saveButton.disabled = true;
                saveButton.innerText = "Saved";
            } else {
                saveButton.className = "btn-assets btn-edit";
                saveButton.disabled = false;
                saveButton.innerText = "Save";
            }


            editButton.addEventListener("click", function () {
                if (!pickDate)
                    return;

                editButtonClicked(officeAssets[i].id);
            });

            saveButton.addEventListener("click", function () {
                saveReservation(officeAssets[i].id)
            });

            colEle.appendChild(assetTitle);
            assetItemEle.appendChild(colEle);
            colEle1.appendChild(editButton);
            assetItemEle.appendChild(colEle1);
            colEle2.appendChild(saveButton);
            assetItemEle.appendChild(colEle2);
            $("#assets-box").append(assetItemEle);
        }
    }

    // function to show spinner
    function showSpinner() {
        $("#spinner").show();
    }

    // function to hide spinner
    function hideSpinner() {
        $("#spinner").hide();
    }

    // loadDataFromStrapi();
    // load data from strapi
    function loadDataFromStrapi() {
        showSpinner();
        $.ajax({
            url: base_url + "/admin/office/asset/edit/" + id,
            type: "GET",
            success: function (res) {
                res.map(((value, index) => {
                    officeAssets.push({
                        id: value.assetId,
                        name: value.name,
                        description: value.description,
                        canvas: JSON.parse(value.canvas),
                        is_saved: true,
                    })
                }));
                hideSpinner();
                rearrangeAssets();
            },
            error: function (err) {
                console.log(err);
                hideSpinner();
            }
        });
    }

    //function to show available modal
    function showModal(status, num)
    {
        $("#modal-date").text(pickDate);
        $("#modal-number").text(num);
        if (status === "available") {
            $(".logo-box").css("background-color", circleAC);
            $(".logo-status").text("Available");
            $("#btn-change").css("background-color", circleAC);
            $("#btn-change").attr("disabled", false);
        } else if (status === "block") {
            $(".logo-box").css("background-color", circleBC)
            $(".logo-status").text("Block");
            $("#btn-change").css("background-color", circleGC);
            $("#btn-change").attr("disabled", true)
        } else if (status === "booked") {
            $(".logo-box").css("background-color", circleGC);
            $(".logo-status").text("Booked")
            $("#btn-change").css("background-color", circleGC);
            $("#btn-change").attr("disabled", true);
        }
        $("#changeModal").modal("show");
    }

    // function to save reserved data
    function saveReservation(id) {
        showSpinner();
        reservedData.map(((value, index) => {
            if (Number(id) === Number(value.assetId)) {
                $.ajax({
                    url: "http://34.246.156.37:1337/seat-reservations/?assetId=" + id.toString() + "&date=" + pickDate,
                    type: "GET",
                    success: function (res) {
                        if (res.length > 0) {
                            $.ajax({
                                url: "http://34.246.156.37:1337/seat-reservations/?assetId=" + value.assetId,
                                type: "PUT",
                                data: { seatNum: value.seatNum },
                                success: function(res) {
                                    hideSpinner();
                                    setSavedStatus(value.assetId);
                                    rearrangeAssets();
                                },
                                error: function (err) {
                                    hideSpinner();
                                }
                            })
                        } else {
                            $.ajax({
                                url: "http://34.246.156.37:1337/seat-reservations",
                                type: "POST",
                                data: { assetId: value.assetId, seatNum: value.seatNum, date: pickDate },
                                success: function(res) {
                                    console.log("a")
                                    hideSpinner();
                                    setSavedStatus(value.assetId);
                                    rearrangeAssets();
                                },
                                error: function (err) {
                                    hideSpinner();
                                }
                            })
                        }
                    },
                    error: function (err) {
                        hideSpinner();
                    }
                });
            }
        }));
    }

    // function to validate reserved status or not
    function isReserved() {
        let is_reserved = false;

        reservedData.map((value, index) => {
            if (value.assetId === currentOfficeIndex) {
                if (value.seatNum && value.date === pickDate) {
                    is_reserved = true;
                    return;
                }
            }
        });
        return is_reserved;
    }

    $("#btn-change").click(function () {
        if (isReserved() === true)
            return;

        clonedCircles.map(((value, index) => {
            if (value._objects.length < 2)
                return;
            if (value._objects[1].text === $("#modal-number").text()) {
                value._objects[0].set("fill", circleGC);

                let reserve = {
                    assetId: Number(currentOfficeIndex),
                    seatNum: Number($("#modal-number").text()),
                    date: pickDate
                };

                reservedData.push(reserve);

                officeAssets.map(((value1, index1) => {
                    if (value1.id === currentOfficeIndex) {
                        officeAssets[index1].is_saved = false;
                    }
                }));
                rearrangeAssets();
            }
        }));
        canvas.renderAll();
    });

    $("#date").change(function () {
        pickDate = $(this).val();
        currentOfficeIndex = -1;
        canvas.clear();
        rearrangeAssets();
    });

    // add listener for click event on canvas
    canvas.on("mouse:down", function (e) {
        if (!started_flag)
            return;
        if (!e.target)
            return;
        if (!e.target._objects)
            return;
        if (!pickDate)
            return;
        if (e.target._objects.length === 2) {
            if (e.target._objects[0].type === "circle") {
                if (e.target._objects[0].fill === circleAC) {
                    let num = e.target._objects[1].textLines[0];
                    showModal("available", num)
                } else if (e.target._objects[0].fill === circleBC) {
                    let num = e.target._objects[1].textLines[0];
                    showModal("block", num)
                } else if (e.target._objects[0].fill === circleGC) {
                    let num = e.target._objects[1].textLines[0];
                    showModal("booked", num)
                }
            }
        }
    });

    canvas.renderAll();
};