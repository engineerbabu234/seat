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
								<a href="{{url('admin/building/')}}">My Building</a>
								<a href="#">Edit Building</a>
							</p>
						</div>
					</div>
					<!-- <div class="col-md-6 col-sm-6 col-xs-12">
						<div class="btns">
							<button class="add-tender">Add Tenders</button>
						</div>
					</div> -->
				</div>
			</div><!--END header-->

			<!--my tenders-->
			<form method="POST" enctype="multipart/form-data" action="{{ route('admin/building/update',$data['building']->building_id) }}">
				@csrf
				{{ method_field('PUT') }}
				<div class="add-office">
					<h2 class="title"><i class="fas fa-building"></i> Edit Building</h2>

					<!--single-entry-->
					<div class="single-entry">
						<div class="form-group">
							<h4 class="sub-title">1. Building Name</h4>
							<input type="text" class="form-control2" placeholder="Building Name" name="building_name" value="{{$data['building']->building_name}}">
							@error('building_name')
								<span class="invalid-feedback" role="alert">
									<strong style="color: red">{{ $message }}</strong>
								</span>
							@enderror
						</div>
						<div class="form-group">
							<h4 class="sub-title">Building Address</h4>
							<input type="text" class="form-control2" placeholder="Building Address" name="building_address" value="{{$data['building']->building_address}}">
							@error('building_address')
								<span class="invalid-feedback" role="alert">
									<strong style="color: red">{{ $message }}</strong>
								</span>
							@enderror
						</div>
						<!-- <div class="form-group">
							<h4 class="sub-title">Total No. of Offices</h4>
							<input type="number" class="form-control3" placeholder="00" name="">
						</div> -->
						<div class="form-group">
							<h4 class="sub-title">Description</h4>
							<textarea rows="4" class="form-control4" placeholder="Write here..." name="description">{{$data['building']->description}}</textarea>
							@error('description')
								<span class="invalid-feedback" role="alert">
									<strong style="color: red">{{ $message }}</strong>
								</span>
							@enderror
						</div>
						<!-- <button class="delete-btn"> <i class="fas fa-trash-alt"></i> Remove Building</button> -->
					</div><!--END single-entry-->

					<div class="add-product-btn">
						<button type="submit"> Update Bulding</button>
					</div>

				</div><!--END my tenders-->
			</form>

		</div>	
	</div>
@endsection