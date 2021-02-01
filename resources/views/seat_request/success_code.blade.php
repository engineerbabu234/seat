<div id="new_code">
	<form  method="post" id="upload_code" class="mt-3">
		<h6 class="card-subtitle"><strong>{{ $building->building_name }} > {{ $office->office_name }} > {{ $assets->title }} > {{ $seat->seat_no }}</strong></h6>
		@if($type =='nfccode')
		<h5 class="card-text text-info mt-3 mb-0"><strong>
NFC Code Uploaded , Seat Requests by NFC now capable for this seat	 </strong></h5>
		@elseif($type =='qrcode')
		<h5 class="card-text text-info mt-3 mb-0"><strong>QR Code Uploaded , Seat Requests by QR now capable for this seat</strong></h5>
		@endif
	</div>
