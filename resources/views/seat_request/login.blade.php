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
					 <form method="POST" action="{{ route('login') }}">
                        @csrf
						<div class="container">
							<div class="row justify-content-center">
								<div class="col-sm-6 form-box-request seat-request-form-block">
									<div class="card">
										<div class="card-body">


										<div class="row justify-content-between align-items-center">
										<div class="col-lg-auto">
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
										<div class="col-lg-auto">
												<h5>Seat Request</h5>
											</div>

											<div class="col-lg-auto">
												<img src="{{$img}} " class="bl-icon" width="50" >

											</div>
											</div>

												<hr>
											<h5 class="card-title">Login :</h5>

												 <div class="form-group row"><br>
                            		<label for="email" class="col-md-4 col-form-label text-md-left">Username</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-left">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

												<div class="form-group row">
													<label for="staticEmail" class="col-sm-4 col-form-label"></label>
													<div class="col-sm-7">
														<button type="submit" class="btn btn-success">Login</button>
													</div>
												</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</section>
			</div>
		</div>
	</main>
@include('layouts.seat_request.foot')
<script type="text/javascript">
    @if($message = Session::get('success'))
        title='Success';
        message='{{$message}}';
        myalert(title,message)
        function myalert(title,msg){
            $.alert(msg, {
            title: title,
            closeTime: 3000,
            // withTime: $('#withTime').is(':checked'),
            //isOnly: !$('#isOnly').is(':checked')
            });
        }
    @endif

    @if($message = Session::get('error'))
        title='Error';
        message='{{$message}}';
        myalert(title,message)
        function myalert(title,msg){
            $.alert(msg, {
            title: title,
            closeTime: 3000,
            type:'danger',
            // withTime: $('#withTime').is(':checked'),
            //isOnly: !$('#isOnly').is(':checked')
            });
        }
    @endif
</script>
