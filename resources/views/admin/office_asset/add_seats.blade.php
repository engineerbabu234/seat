<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-image-form">
@csrf

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
        <input type="hidden"  id="seat_type" value="">
        <input type="hidden"  id="seat_status" value="">
        <input type="hidden"  id="total_count" value="">
        <input type="hidden"  id="last_id" value="">
        <input type="hidden" name="seat_ids" id="seat_ids" value="">
        <input type="hidden" name="is_edit" id="is_edit" value="">
        <input type="hidden" name="dots_id" id="dots_id" value="">
         <input type="hidden" name="building_id" id="building_id" value="{{ $officeAsset->building_id }}">
         <input type="hidden" name="office_id" id="office_id" value="{{ $officeAsset->office_id }}">
         <input type="hidden" id="main_image" value="{{ $assets_image }}">
         <input type="hidden" id="canvas_image" value="{{ isset($officeAsset->asset_canvas) ? 1 : 0 }}">
         <input type="hidden" name="asset_id" id="asset_id" value="{{ $officeAsset->id }}">
         <input type="file" id="file-input" style="display: none" />


        <img src="{{asset('admin_assets')}}/images/seat_book/remove.png" class="removeImg" height="30" data-asset="{{ $officeAsset->id }}">
        <img src="{{asset('admin_assets')}}/images/seat_book/dots.png" class="dotsImg" height="30" data-asset="{{ $officeAsset->id }}">


        <div class="spinner-border text-warning" id="spinner"></div>



        <div class="modal" id="changeModal">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Add Seat <span class="ml-5" id="change-number"></span></h4>
                <button type="button" class="close_new seats_cancel"  >&times;</button>
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
                <button type="button" class="close_new seats_update_cancel" >&times;</button>
              </div>
              <div class="modal-body" id="edit_office_seats" >
              </div>
            </div>
          </div>
        </div>


</form>
