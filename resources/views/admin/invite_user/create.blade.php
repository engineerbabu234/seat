@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-12">
					<form {{-- class="form-inline" --}} action="{{route('store.invitation.link')}}" method="post">
						@csrf
						<div class="form-group">
							<input type="text" name="name" placeholder="Name" class="form-control" value="{{old('name')}}" required>
						</div>
						<div class="form-group">
							<input type="email" name="email" placeholder="Email" class="form-control" value="{{old('email')}}" required>
                              @error('email')
                                 <span class="invalid-feedback" role="alert" style="display: block;">
  	                                 <strong style="color: red">{{ $message }}</strong>
	                             </span>
                              @enderror
						</div>
                        <button type="submit" class="btn btn-success">Invite</button>
					</form>
				</div>
			</div>
		</div><!--END header-->

	</div>
</div>

@endsection
@push('css')
  <style type="text/css">
  	input{
  		margin: 10px;
  	}
  </style>
@endpush
