<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-seat-form">
@csrf

    <div class="container-fluid" style="height: 100%;">
            <div class="row" style="height: 100%;">
                <div class="col-lg-12 col-md-12 col-sm-12  " id="main">
                    <canvas class="content" id="canvas"></canvas>
                </div>
            </div>
        </div>
         <input type="hidden" id="main_image" value="{{ $assets_image }}">
         <input type="hidden" id="asset_name" value="">
         <input type="file" id="file-input" style="display: none" />


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
