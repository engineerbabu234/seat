@php
namespace App\Helpers;
//$product_place_holder_image=ImageHelper::getProductPlaceholderImage();
//use Illuminate\Support\Facades\Session;
@endphp
@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<h2 style="font-size: 23px;">Trip Details</h2>
		<div class="driver-details">
			<div class="tab-content">
			    <div id="Dashboard" class="dashboard tab-pane fade in active">
			    	<div class="row">
			    		<div class="col-md-6">
					    	<div class="profile-details clearfix">
								<div class="img">
									@if($data['trip']->id)

										<img src="{{ImageHelper::getProfileImage($data['trip']->user->profile_image)}}">
									@endif
									
								</div>
								<div class="txt-details">
									<ul>
										<li><span>User Name :</span> {{$data['trip']->user->user_name}}</li>
										<li><span>Phone Number :</span> {{$data['trip']->user->phone_number}}</li>
										<li><span>Delivery Address :</span> {{$data['trip']->user->address}}</li>
										{{-- @if($data['order']->delivery_type=='1')
											<li><span>Delivery Type :</span> {{'Once'}}</li>
											<li><span>Delivery Date :</span> {{$data['order']->delivery_date}}</li>
										@elseif($data['order']->delivery_type=='2')
											<li><span>Delivery Type :</span> {{'Daily'}}</li>
										@elseif($data['order']->delivery_type=='3')
											<li><span>Delivery Type :</span> {{'Custom'}}</li>
										@else
											<li><span>Delivery Type :</span> {{'Custom'}}</li>
										@endif
										
										@if($data['order']->payment_method=='1')
											<li><span>Payment Method :</span> {{'COD'}}</li>
										@elseif($data['order']->payment_method=='2')
											<li><span>Payment Method :</span> {{'Card'}}</li>
										@elseif($data['order']->payment_method=='3')
											<li><span>Payment Method :</span> {{'Paypal'}}</li>
										@elseif($data['order']->payment_method=='4')
											<li><span>Payment Method :</span> {{'Wallet'}}</li>
										@else
											<li><span>Payment Method :</span> {{'Other'}}</li>
										@endif

										<li><span>Price :</span> {{$data['order']->price}}</li>
										<li><span>Delivery Charge :</span> {{$data['order']->delivery_charge}}</li>
										<li><span>Total Price :</span> {{$data['order']->total_price}}</li>
										@if($data['order']->status=='0')
											<li><span>Order Status :</span> {{'Pending'}}</li>
										@elseif($data['order']->status=='1')
											<li><span>Order Status :</span> {{'Completed'}}</li>
										@elseif($data['order']->status=='2')
											<li><span>Order Status :</span> {{'Rejected'}}</li>
										@else
											<li><span>Order Status :</span> {{'Other'}}</li>
										@endif

										<li>
											<span>Order Status :</span> 
											<div class="order-zone">
												<select name="order_zone_change" class="order-zone-change" order_id="{{$data['order']->order_id}}">
													@if($data['zones']->isEmpty())
													<option value="">Record Not Found</option>
													@else
													@foreach($data['zones'] as $key => $value)
													<option value="{{$value->zone_id}}" @if($data['order']->zone_id==$value->zone_id) {{'selected'}} @endif>{{$value->zone_name}}</option>	
													@endforeach
													@endif
												</select>
											</div>
										</li> --}}
										
										
									</ul>
								</div>
							</div><!--end-->
			    		</div>
			    		<div class="col-md-6">
			    			<div id="map" style="height: 400px; width: 400px;"></div>
			    		</div>
			    	</div>
					
			    </div>
			   {{-- @if($data['order']->order_id)
				   <div id="" class="">
						<div class="driver-data-table">
							<div class="data-table">
								<div class="table-fbutton clearfix">
									<div class="btns">
										<h2>Products :</h2>
									</div>
									<div class="s-btn">
										<div class="searchbar">
											<label>Search :</label>
											<input type="text" name="">
										</div>
									</div>
								</div>
								<div class="table-responsive">
									<table id="laravel_datatable" class="table">
										<thead>
											<tr>
												<th>Sr.No.</th>
												<th>Product Image</th>
												<th>Product Name</th>
												<th>Price</th>
												<th>Quantity</th>
												<th>Total Price</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
				   </div>
			   @endif  --}}
			</div>
		</div>
	</div>
</div>
@endsection
