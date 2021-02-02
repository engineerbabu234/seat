@php
namespace App\Helpers;
$product_place_holder_image=ImageHelper::getProductPlaceholderImage();
use Illuminate\Support\Facades\Session;
@endphp
@extends('admin.layouts.app')
@section('content')
	<div class="main-body">
		<div class="inner-body">
			<!--header-->
			<div class="header">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="title">
							<!-- <h2>My Tenders</h2> -->
							<p class="navigation">
								<a href="{{route('dashboard')}}">Dashboard</a>
								<a href="{{route('admin.team.index')}}">Team</a>
                                <a href="javascript:void(0)">Users</a>
							</p>
						</div>
						@if(Session::get('status') == 'success')
							<div class="alert alert-success">
								<ul>
									<li>{{ Session::get('message') }}</li>
								</ul>
							</div>
						@endif

						@if(Session::get('status') == 'failed')
							<div class="alert alert-danger">
								<ul>
									<li>{{ Session::get('message') }}</li>
								</ul>
							</div>
						@endif
					</div>
				</div>
			</div><!--END header-->

			<!--my tenders-->
			<form method="POST" action="{{ route('admin.team.user.store') }}">
				@csrf
                <input type="hidden" name="id" value="{{$id}}" />
				<div class="add-office">
					<!--single-entry-->
					<div class="single-entry">
						<div class="form-group">
                            <select name="users[]" class="form-control" multiple>
                              @foreach($users as $user)
                               <option @if(in_array($user->id,$selectedUsers)) selected @endif value="{{$user->id}}">{{$user->user_name}}</option>
                              @endforeach
                            </select>
							@error('user')
								<span class="invalid-feedback" role="alert">
									<strong style="color: red">{{ $message }}</strong>
								</span>
							@enderror
						</div>
					</div><!--END single-entry-->
					<div class="add-product-btn">
						<button type="submit">Update</button>
					</div>
				</div><!--END my tenders-->
			</form>

		</div>
	</div>
@endsection
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
@endpush
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" ></script>
<script>
   $(document).ready(function() {
    $('select[name="users[]"]').select2();
   });
</script>
@endpush
