@extends('admin.layouts.app')
@section('content')
<div class="main-body">
  <div class="inner-body">

      <div class="header">
            <div class="title">
        <div class="row align-items-center">
          <div class="col-md-6 col-sm-6 col-xs-6">
              <h2>Contactless Labels Orders</h2>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="btns">
             <a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_seatlabel"><i class="fas fa-plus"></i></a>

            </div>
            </div>
          </div>
        </div>
      </div><!--END header-->


      <div class="custom-data-table">
        <div class="data-table">
          <div class="custom-table-height">
            <div class="table-responsive">
              <table class="table table-striped text-center" id="laravel_datatable">
                <thead>
                  <tr>
                    <th > <span class="iconWrap iconSize_32" title="Order ID"  data-trigger="hover" id="order_id" data-placement="top"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="30" ></span></th>
                    <th ><span class="iconWrap iconSize_32" title="Order Date"  data-trigger="hover" id="order_date" data-placement="top"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="30" > </span></th>
                    <th ><span class="iconWrap iconSize_32" title="Label Config"  data-trigger="hover" id="order_config" data-placement="bottom"><img src="{{asset('admin_assets')}}/images/config.png" class="icon bl-icon" width="30" ></span></th>
                    <th ><span class="iconWrap iconSize_32" title="No Labels" data-content="No Labels" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/no-label.png" class="icon bl-icon" width="30" ></span></th>
                    <th ><span class="iconWrap iconSize_32" title="Status"  data-trigger="hover" id="order_status" data-placement="top"><img src="{{asset('admin_assets')}}/images/status.png" class="icon bl-icon" width="30" ></span></th>
                  </tr>
                </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
  <!-- The Modal -->
  <div class="modal" id="add_seatlabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Seat Label</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
          <form method="POST" id="add-seatlabel-form" enctype="multipart/form-data" action="#">
            @csrf
            <div class="add-office">
              <label>Area</label>
              <div class="row">
                <div class="col-sm-3">
                  <label><span class="iconWrap iconSize_32" title="Building"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="icon bl-icon" width="30" ></span>
                </label>
                <select class="form-control building" name="building_id" id="building_id">
                  <option value="">All</option>
                  @foreach($buildings as $key => $bvalue)
                  <option value="{{$bvalue->building_id}}">{{$bvalue->building_name}}</option>
                  @endforeach
                </select>
                <span class="error" id="building_id_error"></span>
              </div>
              <div class="col-sm-3">
                <label><span class="iconWrap iconSize_32" title="Office"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/offices.png" class="icon bl-icon" width="30" ></span></label>
                <select class="form-control office" name="office_id" id="office_id"><option value="">All</option>
              </select>
              <span class="error" id="office_id_error"></span>
            </div>
            <div class="col-sm-3">
              <label><span class="iconWrap iconSize_32" title="Office Assets"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/assets.png" class="icon bl-icon" width="30" ></span> </label>
              <select class="form-control office_assets" name="office_asset_id" id="office_asset_id"><option value="">All</option>
            </select>
            <span class="error" id="office_asset_id_error"></span>
          </div>
          <div class="col-sm-3">
            <label><span class="iconWrap iconSize_32" title="Seats"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/seat-no.png" class="icon bl-icon" width="30" ></span> </label>
            <select class="form-control seats" name="seat_id" id="seat_id">
              <option value="">All</option>
            </select>
            <span class="error" id="seat_error"></span>
          </div>

          <div class="col-sm-12">
          <div class="row pt-3">
            <div class="col-sm-1">  <label><span class="iconWrap iconSize_32" title="Config"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/config.png" class="icon bl-icon" width="30" ></span> </label></div>
            <div class="col-sm-1">  <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="scan" name="scan" checked>
              <label class="custom-control-label" for="scan"><span title="QR Codes"  data-trigger="hover" data-placement="right"><img src="{{asset('admin_assets')}}/images/scan.png" class="icon bl-icon" width="30" ></span></label>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="nfc" name="nfc" checked>
              <label class="custom-control-label" for="nfc"><span title="NFC"  data-trigger="hover" data-placement="right"><img src="{{asset('admin_assets')}}/images/nfc.png" class="icon bl-icon" width="30" ></span> </label>
            </div>
          </div>
          <span class="error" id="search_booking_date_error"></span>
        </div>
      </div>
      </div>
      <div class="row">
        <div class="col-sm-12 text-right">
          <button type="submit"  class="btn btn-info addseatlabel"> Add Seat Label</button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- Modal footer -->
</div>
</div>
</div>
<div class="modal" id="deploy_label_modal">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<!-- Modal Header -->
<div class="modal-header">
  <h4 class="modal-title">Deploy Labels</h4>
  <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<!-- Modal body -->
<div class="modal-body" id="deploy_label">
</div>
</div>
</div>
</div>
<div id="orderid_pophover">
<div class="row">
<div class="col-sm-12">
<p>This is the Order ID of the seat labels</p>
</div>
</div>
</div>
<div id="orderdate_pophover">
<div class="row">
<div class="col-sm-12">
<p>This is the date that the order was made</p>
</div>
</div>
</div>
<div id="orderstatus_pophover">
<h6>Order :</h6><p>
We have ordered the labels to be printed / programmed. The next step is shipping the labels</p>
<h6>Deploy :</h6><p>
Labels have shipped, when you receive them please deploy all of them together by clicking on link in this columm</p>
<h6>Activated :</h6><p>All the labels are deployed to the seats</p>
</div>
<div id="orderconfig_pophover">
<div class="row">
<div class="col-sm-12">
<p>Label Order Seat Request Configuration</p>
<p><img title="QR Code" src="{{asset('admin_assets')}}/images/scan.png"   class="bl-icon" width="20" >: This Labels order contains QR codes to support Contactless Seat Requests</p>
<p><img title="NFC Code" src="{{asset('admin_assets')}}/images/nfc.png"   class="bl-icon" width="20" >: This Labels order contains NFC codes to support Contactless Seat Requests</p>
<p>Seat requests made by users are : Check In, Check Out</p>
<p>Seat requests made by admins are: Upload new QR / NFC codes</p>
<p>Seat requests made by cleaners are: Clean Seat</p>
<p>NFC is a wireless Tap system, QR Codes  uses barcode scan technologies, Having both options available provides more options to allow the user to submit a "Seat Request"</p>
</div>
</div>
</div>
@endsection
@push('js')
<script type="text/javascript" src="{{URL::asset('admin_assets/pages')}}/seat_label/index.js"></script>
@endpush
