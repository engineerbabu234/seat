@php
namespace App\Helpers;
 use Illuminate\Support\Facades\Session;
@endphp
@include('layouts.seat_request.head')
		<main class="clearfix">
			<div class="right-block">
				<div class="Navoverlay"></div>
				<div class="right-block-body"> </div>
				<div class="dev">
						<div class="container">
							<div class="row justify-content-center">
								<div class="col-sm-4 form-box-request seat-request-form-block">
									<div class="card">
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
												<hr>
										<div class="card-body">
											<div class="card-hedaing">
												<h5 class="card-title">Seat Request</h5>
												<span>
													<img src="{{asset('admin_assets')}}/images/scan.png"    class="bl-icon" width="50" >
												</span>
											</div>


												<h6 class="card-subtitle">Challenge, please confirm seat </h6><br>
											@if($errors->any())
											     <span class="text-danger">Please Selete All Dropdown</span>
											@endif
												 <form method="POST" action="{{ url('seatrequest/challenge') }}">
								 						@csrf
								 						<input type="hidden" name="qr_code" value="{{ request()->query('QRCode') }}">


													<div class="form-group row">
														<label for="staticEmail" class="col-sm-5 col-form-label"></label>
														<div class="col-sm-7">
															<button  class="btn btn-success confirm_code">Confirm</button>
														</div>
													</div>

												</form>



									</div>
								</div>
							</div>
						</div>
					</div>
				</main>
@include('layouts.seat_request.foot')
