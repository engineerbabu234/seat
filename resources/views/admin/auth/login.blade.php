@extends('admin.layouts.auth_app')
@section('content')
<form method="POST" action="{{ route('login') }}">
		 @csrf
	<div class="all-inputs">
		<div class="form-group">
			<input type="hidden" name="role" value="1" >
			<input type="email" placeholder="Enter Email" name="email" value="{{ old('email') }}">
			@error('email')
	            <span class="invalid-feedback" role="alert">
	                <strong style="color: red">{{ $message }}</strong>
	            </span>
	        @enderror
		</div>
		<div class="form-group">
			<input type="password" placeholder="Enter Password" name="password">
			@error('password')
				<span class="invalid-feedback" role="alert">
				<strong>{{ $message }}</strong>
				</span>
			@enderror
		</div>
		<div class="lr-btn">
			<button type="submit">Login <i class="fa fa-arrow-right"></i></button>
		</div>

		<div class="lr-links">
			{{-- <p>Don’t have an account? <a href="sign_up.html">Sign Up</a></p> --}}
			<p>Did You <a href="{{ route('password.request') }}">Forget your Password?</a></p>
		</div>
	</div>
</form>
@endsection
