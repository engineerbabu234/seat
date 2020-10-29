@php
namespace App\Helpers;
$product_place_holder_image=ImageHelper::getProductPlaceholderImage();
use Illuminate\Support\Facades\Session;
@endphp
@extends('admin.layouts.app')
@section('content')
	<div class="main-body">
		<div class="inner-body">
			<h2 style="font-size: 23px;">Update Vehicle Type</h2>
			<div class="add-driver clear-col">
				@if($message = Session::get('success'))
					<div class="alert alert-success">
						<ul>
							<li>{{ $message }}</li>
						</ul>
					</div>
				@endif

				@if($message = Session::get('error'))
					<div class="alert alert-danger">
						<ul>
							<li>{{ $message }}</li>
						</ul>
					</div>
				@endif
				<form method="POST" enctype="multipart/form-data" action="{{ route('admin/vehicle_type/update',$data['vehicle_type']->vehicle_type_id) }}">
					@csrf
					{{ method_field('PUT') }}
					<div class="input-form ">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label>Vehicle Type Name:</label>
									<input type="text" placeholder="Vehicle Type Name" class="form-control" name="vehicle_type_name" value="{{$data['vehicle_type']->name}}">
									@error('vehicle_type_name')
										<span class="invalid-feedback" role="alert">
											<strong style="color: red">{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label>Image:</label>
									<div class="upload-file">
										<button>
											<input type="file" class="form-control" name="image" id="upload-photo-1">
											<span>Choose File</span>
										</button>
									</div>
									<img src="{{$data['vehicle_type']->image}}" id="show-image-1" width="70"  height="70">
									@error('image')
										<span class="invalid-feedback" role="alert">
										<strong style="color: red">{{ $message }}</strong>
										</span>
									@enderror
								</div>
							</div>
						</div>
					</div>
					<div class="buttons">
						<button type="submit" class="same-btn1">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection