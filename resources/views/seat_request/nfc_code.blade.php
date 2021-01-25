@php
namespace App\Helpers;
use Illuminate\Support\Facades\Session;
use Auth;
@endphp
@include('layouts.seat_request.head')
<main class="clearfix">
	<div class="right-block">
		<div class="Navoverlay"></div>
		<div class="right-block-body"> </div>
		<div class="dev">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-sm-6 form-box-request seat-request-form-block">
						<div class="card">
							<div class="card-heading">
								<div class="user-profile">
									<span>
										<h5>{{Auth::User()->user_name}}
											<span class="authUser">
												@if(Auth::User()->role == 1)
													{{'Admin'}}
												@elseif(Auth::User()->role == 2)
													{{'User'}}
												@elseif(Auth::User()->role == 3)
													{{'Cleaner'}}
												@endif
												</span>
										</h5>

										<a href="{{ route('logout') }}" onclick="event.preventDefault();
											document.getElementById('logout-form').submit();" class="logout-inn">
											<img src="{{asset('admin_assets')}}/images/logout.png" class="menu_icons">
										</a>
										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											@csrf

											<input type="hidden" name="seat_request" value="1">
											<input type="hidden" name="type" value="{{$type}}">
										</form>
									</span>
								</div>
							</div>
							<div class="card-body">
								<div class="card-hedaing">
									<div class="w-100">
										<div class="row align-items-center">
											<div class="col-md-4">
												@php
												$Admin = \App\Models\User::where('role','1')->first();
												if($Admin->logo_status == 1){
												@endphp
												<img src="{{ImageHelper::getlogoImage($Admin->logo_image)}}" height="100" width="100" class="logo">
												@php
												}else{
												@endphp
												<img src="{{asset('admin_assets')}}/images/nav-logo2.png">
												@php
												}
												@endphp
											</div>
											<div class="col-md-6">

												@if($type =='nfccode')
													 <h5 class="card-title" >Seat Request, NFC Code</h5>
											   @elseif($type =='qrcode')
													<h5 class="card-title"   >Seat Request, QR Code</h5>
												@else
												<h5 class="card-title">Seat Request</h5>
												@endif
											</div>
											<div class="col-md-2 text-right">
												<span>
													@if($type =='nfccode')
													<img src="{{asset('admin_assets')}}/images/nfc.png" class="bl-icon" width="32" >
													@elseif($type =='qrcode')
													<img src="{{asset('admin_assets')}}/images/scan.png" class="bl-icon" width="32" >
													@else
													<img src="{{asset('admin_assets')}}/images/browser.png" class="bl-icon" width="32" >
													@endif
												</span>
											</div>
										</div>
									</div>

								</div>
								<div id="page_request">
								<h6 class="card-subtitle text-center">Challenge, please confirm seat </h6><br>
								@if($errors->any())
								<span class="text-danger">Please Selete All Dropdown</span>
								@endif
								<form method="POST" action="{{ url('seatrequest/challenge') }}">
									@csrf
									@if($type =='nfccode')
									<input type="hidden" name="nfc_code" value="{{$code }}">
									@elseif($type =='qrcode')
									<input type="hidden" name="qr_code" value="{{ $code }}">
									@endif
									<input type="hidden" name="type" value="{{isset($type) ? $type : 'browser'}}">
									@include('seat_request.dropdown')
									<div class="form-group row">
										<label for="staticEmail" class="col-sm-5 col-form-label"></label>
										<div class="col-sm-7">
											<button  class="btn btn-success confirm_code" >Confirm</button>
										</div>
									</div>
								</form>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
		@include('layouts.seat_request.foot')
