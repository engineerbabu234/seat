<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-seat-form">
@csrf

    <div class="container-fluid" style="height: 100%;">
            <div class="row" style="height: 100%;">
                <div class="col-lg-12 col-md-12 col-sm-12  " id="main">
                    <canvas class="content" id="canvas"  width="200" height="200" ></canvas>
                </div>
            </div>
        </div>
         <input type="hidden" name="building_id" id="building_id" value="{{ $officeAsset->building_id }}">
         <input type="hidden" name="office_id" id="office_id" value="{{ $officeAsset->office_id }}">
         <input type="hidden" id="main_image" value="{{ $assets_image }}">
         <input type="hidden" name="asset_id" id="asset_id" value="{{ $officeAsset->id }}">
         <input type="file" id="file-input" style="display: none" />
         <img src="{{asset('admin_assets')}}/images/seat_book/remove.png" class="removeImg" height="30">
        <img src="{{asset('admin_assets')}}/images/seat_book/dots.png" class="dotsImg" height="30">
        <div class="spinner-border text-warning" id="spinner"></div>

        <div class="modal" id="changeModal">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Add Seat <span class="ml-5" id="change-number"></span></h4>
                <button type="button" class="close seats_cancel" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body" >
                  @include("admin/office_asset/addofficeseats")
              </div>

            </div>
          </div>
        </div>


</form>
