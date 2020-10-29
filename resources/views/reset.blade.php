@extends('layouts.auth_app')
@section('content')
	<div class="login-form">
		<div class="logo">
			<img src="{{asset('front_end')}}/images/logo.png">
		</div>
		<div class="heading">
			<h2>Change Password</h2>
		</div>
		<form method="POST" action="{{ route('password.update') }}">
			@csrf
			<input type="hidden" name="token" value="{{ $token }}">
			<div class="body">
				<div class="form-group">
					<input type="hidden" placeholder="Enter Email" name="email"  value="{{ Request::get('email') }}">
					@error('email')
						<span class="invalid-feedback" role="alert" style="display: block;">
						<strong style="color: red">{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group">
					<input type="password" placeholder="Password" name="password">
					@error('password')
						<span class="invalid-feedback" role="alert" style="display: block;">
						<strong style="color: red">{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group">
					<input type="password" class="form-control" placeholder="Password Confirmation" name="password_confirmation">
					@error('password')
						<span class="invalid-feedback" role="alert" style="display: block;">
						<strong style="color: red">{{ $message }}</strong>
						</span>
					@enderror
				</div>




				<div class="lgn-btn">
					<button type="submit">Submit</i></button>
				</div>


			</div>
		</form>
	</div>
@endsection
@push('js')
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
@endpush
