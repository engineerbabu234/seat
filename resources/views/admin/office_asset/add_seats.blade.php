<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-image-form">
@csrf

    <!-- <div class="seat-status">
      <h1>Seat Status</h1>
      <h2 class="ts">Total Seats: 2</h2>
      <h2 class="as">Available Seat: 2</h2>
      <h2 class="bs1">Booked Seat: 0</h2>
      <h2 class="bs">Blocked Seat: 0</h2>
    </div> -->

    <div class="container-fluid" style="height: 100%;">
            <div class="row" style="height: 100%;">
                <div class="col-lg-12 col-md-12 col-sm-12" id="main">
                    <canvas class="content" id="canvas"></canvas>
                </div>
            </div>
            <hr>
            <div class="row">
            <div class="col-sm-6 text-right">  <a href="#" title="Add Seat" class="btn btn-link" id="img-create" >  <img src="{{asset('admin_assets')}}/images/seat_book/green-seat.png" height="50"></a></div>
            <div class="col-sm-6"><button  class="btn btn-info btn-sm ml-5" id="btnSave" >Add seats</button></div>
          </div>

        </div>
        <input type="hidden" name="dots_id" id="dots_id" value="">
         <input type="hidden" name="building_id" id="building_id" value="{{ $officeAsset->building_id }}">
         <input type="hidden" name="office_id" id="office_id" value="{{ $officeAsset->office_id }}">
         <input type="hidden" id="main_image" value="{{ $assets_image }}">
         <input type="hidden" id="canvas_image" value="{{ isset($officeAsset->asset_canvas) ? 1 : 0 }}">
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

        <div class="modal" id="updateseatsModal">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Update Seat <span class="ml-5" id="change-number"></span></h4>
                <button type="button" class="close seats_update_cancel" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body" id="edit_office_seats" >
              </div>
            </div>
          </div>
        </div>


</form>
