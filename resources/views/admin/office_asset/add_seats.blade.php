<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-seat-form">
@csrf

    <div class="container-fluid" style="height: 100%;">
            <div class="row" style="height: 100%;">
                <div class="col-lg-3 col-md-3 col-sm-12 mb-2">
                    <div class="container-fluid text-center" id="sidebar">
                        <h5 class="mt-5 mb-3">Office Toolbox</h5>
                        <div class="row justify-content-center mb-2">
                            <img src="{{asset('admin_assets')}}/images/seat.png" height="55" id="img-create">

                        </div>
                        <div class="row mb-4 justify-content-center">
                            <h6>Create Seat</h6>
                        </div>
                        <h5>Office Details</h5>
                        <div class="row mb-5 justify-content-center">
                            <div class="col">
                                <div class="container" id="detailsBox">
                                    <div class="row">
                                        <div class="col-sm-12 text-left">
                                            <label> Building:</label>

                                            {{ $officeAsset->building_name }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-left">
                                            <label>Office:</label>

                                             {{ $officeAsset->office_name }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-left">
                                            <label>OfficeID:</label>

                                             {{ $officeAsset->office_id }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5>Office Assets</h5>
                        <div class="row">
                            <div class="col p-0">
                                <div class="container-fluid p-0">
                                    <div class="row p-0 align-items-center">
                                        <div class="col p-0">
                                            <div class="container-fluid p-0 text-center" id="assets-box">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-1">
                                        <div class="col p-0">
                                            <div class="container-fluid p-0 text-center">
                                                <div class="row mb-4 p-0" id="create-box">
                                                    <div class="col justify-content-start text-left">
                                                        <span class="mr-2" style="font-size: 1.25rem; font-weight: 500">Create new Asset</span><button class="btn-assets btn-create" id="btnCreate">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 mb-2" id="main">
                    <canvas class="content" id="canvas"></canvas>
                </div>
            </div>
        </div>
         <input type="hidden" id="main_image" value="{{ $assets_image }}">
         <input type="hidden" id="asset_name" value="{{ $officeAsset->title }}">
         <input type="file" id="file-input" style="display: none" />
        <img src="{{asset('admin_assets')}}/images/seat_book/remove.png" class="removeImg" height="30">
        <img src="{{asset('admin_assets')}}/images/seat_book/dots.png" class="dotsImg" height="30">
        <div class="spinner-border text-warning" id="spinner"></div>

         <!-- modal for change status -->
        <div class="modal fade" id="changeModal">
            <div class="modal-dialog" id="change-dialog">
                <div class="modal-content" id="change-content">

                    <!-- Modal Header -->
                    <div class="modal-header" id="change-header">
                        <h4>Seat Status</h4>
                        <button class="btn btn-success btn-modal" type="button" id="btn-available">Available</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body" id="change-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <h5>Number:</h5>
                                </div>
                                <div class="col-lg-9 col-md-6 col-sm-6">
                                    <h5 id="change-number">3</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <h5 id="change-office">Office:</h5>
                                </div>
                                <div class="col-lg-9 col-md-6 col-sm-6">
                                    <h5>1 Floor</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <h5>Building:</h5>
                                </div>
                                <div class="col-lg-9 col-md-6 col-sm-6">
                                    <h5 id="change-building">Dublin Building</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <div class="container-fluid">
                            <div class="row">
                                <button type="button" class="btn btn-success btn-modal pl-4 pr-4 pt-2 pb-2 mr-3" id="btn-change">Change State</button>
                                <button type="button" class="btn btn-danger btn-modal pl-4 pr-4 pt-2 pb-2" data-dismiss="modal" id="btn-change-cancel">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</form>
