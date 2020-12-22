@extends('layouts.auth_app')
@section('content')
	<div class="login-form">
		<div class="logo">
			<img src="{{asset('front_end')}}/images/logo.png">
		</div>
		<div class="heading">
			<h2>Sign Up</h2>
			<p>Enter All Ddetails..!!</p>
		</div>

		@if(Session::get('message'))
			<div class="alert" style="color:red">
				 <p>{{Session::get('message')}}</p>
			</div>
		@endif

		<form method="POST" action="{{ url('invite/user/registration') }}">
			@csrf
            <input type="hidden" name="_id" value="{{ $data['id'] }}">
			<div class="body">
				<div class="form-group">
					<input type="text" placeholder="User Name" name="user_name" value="{{ $data['user_name'] ?? old('user_name') }}">
					@error('user_name')
						<span class="invalid-feedback" role="alert" style="display: block;">
						<strong style="color: red">{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group">
					<input type="text" placeholder="Job Profile" name="job_profile" value="{{ old('job_profile') }}">
					@error('job_profile')
						<span class="invalid-feedback" role="alert" style="display: block;">
						<strong style="color: red">{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group">
					<input type="email" placeholder="Enter Email" name="email" value="{{$data['email']}}" readonly>
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
					<input type="password" placeholder="Confirm Password" name="confirm_password">
					@error('confirm_password')
						<span class="invalid-feedback" role="alert" style="display: block;">
						<strong style="color: red">{{ $message }}</strong>
						</span>
					@enderror
				</div>

				<div class="lgn-btn">
					<button type="submit">Sign Up <i class="fas fa-arrow-right"></i></button>
				</div>

				<div class="link">
					<p>Already have an account? <a href="{{url('/login')}}"> Sign In</a> </p>
				</div>
			</div>
		</form>
	</div>
@endsection
