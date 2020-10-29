@extends('layouts.auth_app')
@section('content')
	<div class="login-form">
		<div class="logo">
			<img src="{{asset('front_end')}}/images/logo.png">
		</div>

		<div class="heading">
			<h2>Forget Password</h2>
			{{-- <p>Welcome Back..!!</p> --}}
		</div>
		<form method="POST" action="{{ route('password.email') }}">
			@csrf

			<div class="body">
				<div class="form-group">
					<input type="email" placeholder="Enter Email" name="email" value="{{ old('email') }}">
					@error('email')
						<span class="invalid-feedback" role="alert" style="display: block;">
						<strong style="color: red">{{ $message }}</strong>
						</span>
					@enderror
				</div>

				<div class="lgn-btn">
					<button type="submit">Submit</i></button>
				</div>

				<div class="link">
					<p>Already have an account? <a href="{{url('/login')}}"> Sign In</a> </p>
				</div>
			</div>
		</form>
	</div>
@endsection
@push('js')
<script type="text/javascript">
	@if (session('status'))
		title='Success';
        message='{{ session('status') }}';
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
</script>
@endpush
