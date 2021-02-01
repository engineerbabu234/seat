@extends('layouts.auth_app')
@section('content')
	<div class="login-form">
		<div class="logo">
			<img src="{{asset('front_end')}}/images/logo.png">
		</div>
		<div class="heading">
			<h2>Clear Login</h2>
			<p>Welcome Back..!!</p>
		</div>
		<form method="POST" action="{{ route('login') }}">
			@csrf

			<div class="body">
				<div class="form-group">
					<input type="hidden" name="role" value="3" >
					<input type="email" placeholder="Enter Email" name="email" value="{{ old('email') }}">
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

				<div class="frgt-pass">
					<a href="{{ route('password.request') }}">Forgot Password?</a>
				</div>

				<div class="lgn-btn">
					<button type="submit">Login <i class="fas fa-arrow-right"></i></button>
				</div>

				<div class="link">
					<p>Don’t have an account? <a href="{{url('/sign_up')}}"> Sign Up</a> </p>
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
