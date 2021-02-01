
								<div class="code_uploaded">
								<form method="POST" action="{{ url('seatrequest/upload_code') }}">
									@csrf
									<h6 class="card-subtitle"><strong>{{ $building->building_name }} > {{ $office->office_name }} > {{ $assets->title }} > {{ $seat->seat_no }}</strong></h6>
									<input type="hidden" name="type" value="{{$type}}">
									<input type="hidden" name="seat_id" value="{{$seat->seat_id}}">
									@if($type =='nfccode')
									<h5 class="card-text text-success mt-3 mb-0"><strong class="text-info">Activate Label by uploading NFC code :</strong></h5><br>
									@if(isset($seat->nfc_code) && $seat->nfc_code != '')
									<span class="text-warning">Detected NFC Code, if You press the "Upload Code" button the old code will be erased</span><br><br>
									@endif
									<button type="button" data-id="{{$seat->seat_id}}" class="btn btn-success upload_code ">Upload code</button>
									@elseif($type =='qrcode')
									<h5 class="card-text text-success mt-3 mb-0"><strong class="text-info">Activate Label by uploading QR Code :</strong></h5><br>
									@if(isset($seat->qr_code) && $seat->qr_code != '')
									<span class="text-warning">Detected QR Code, if You press the "Upload Code" button the old code will be erased</span><br><br>
									@endif
									<button type="button" data-id="{{$seat->seat_id}}" class="btn btn-success upload_code ">Upload code</button>
									@elseif($type == 'browser')
									<h5 class="card-text text-success mt-3 mb-0"><strong class="text-info">No Action!</strong></h5>
									@else
									<h5 class="card-text text-success mt-3 mb-0"><strong class="text-info">Please Upload QR code Or NFC Code</strong></h5>
									@endif
								</form>
								</div>
