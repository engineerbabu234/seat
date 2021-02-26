
	<div class="row">
		<div class="col-sm-12 mb-3"><span class="sheetIcon" title="Order ID"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="bl-icon" width="30" ></span>: {{ $SeatLabel->id}} </div>
		<div class="col-sm-12  mb-3"><span class="sheetIcon" title="Order Date"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="bl-icon" width="30" ></span>: {{ date('d-m-Y',strtotime($SeatLabel->label_order_date)) }}</div>
		<div class="col-sm-12 mb-3">
			<span class="seetLabel sheetIcon"   title="Config"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/config.png" class="bl-icon" width="25" ></span>:
			@if($SeatLabel->scan == 1)
			<span class="seetLabel sheetIcon" title="QR Code"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/scan.png" class="menu_icons bl-icon"></span>
			@endif
			@if($SeatLabel->nfc == 1)
			<span class="seetLabel sheetIcon" title="NFC Code"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/nfc.png" class="menu_icons bl-icon"></span>
			@endif
		 </div>
		<div class="col-sm-12 mb-3"><span class="sheetIcon" title="No Labels"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/no-label.png" class="bl-icon" width="30" ></span>: {{$deploy_total}}</div>
	</div>

		<div class="custom-data-table">
			<div class="data-table">

		<div class="custom-table-height">
			<div class="table-responsive">
				<table class="table table-striped text-center" id="laravel_datatable_deploy">
					<thead>
						<tr>
							<th><span title="Deploy ID."  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="bl-icon" width="30" ></span> </th>
						    <th><span title="Building"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="bl-icon" width="30" ></span> </th>
							<th><span title="Office"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/offices.png" class="bl-icon" width="30" ></span> </th>
							<th><span title="Office Assets"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/assets.png" class="bl-icon" width="30" ></span> </th>
							<th><span title="Seat"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/seat-no.png" class="bl-icon" width="30" ></span>  </th>
							<th><span title="Status"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/status.png" class="bl-icon" width="30" ></span> </th>
							<th><span title="Action"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="bl-icon" width="30" ></span> </th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
		</div>
