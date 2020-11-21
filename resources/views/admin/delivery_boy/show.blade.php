@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<div class="driver-details">
			<h2 style="font-size: 23px;">Driver Details</h2>
			<div class="top-details">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<!--profile-details-->
						<div class="profile-details">
							<div class="img-wrapper clearfix">
								<div class="img-p">
									<div class="img">
										<img src="{{$data['user']->profile_image}}">
									</div>
								</div>
								<div class="img-p">
									<div class="img">
										<img src="{{$data['user']->selfie_image}}">
									</div>
								</div>
							</div>
							<div class="txt-details">
								<ul>
									<li><span>Name :</span> {{$data['user']->user_name}}</li>
									<li><span>Email :</span> {{$data['user']->email}}</li>
									<li><span>Phone Number :</span> {{$data['user']->phone_number}}</li>
									<li><span>Vehicle Number :</span> {{$data['user']->vehicle_number}}</li>
									<li><span>Vehicle Color :</span> {{$data['user']->vehicle_color}}</li>
									<li><span>Vehicle Registration Number :</span> {{$data['user']->vehicle_registration_number}}</li>
									<li><span>Licence Number :</span> {{$data['user']->licence_number}}</li>
								</ul>
							</div>
						</div><!--end-->
					</div>

					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="photos clearfix">
							@if($data['user']->driverDocument->isEmpty())
								<h1 align="center" style="color: red">Record not found</h1> 
							@else
								@foreach($data['user']->driverDocument as $key => $value)
									<div class="p-wrapper">
										<div class="single-img" style="background-image: url('{{$value->document}}');">
										</div>
									</div>
								@endforeach
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="tab-content">
				<div class="driver-tabs">
					<ul class="nav nav-tabs">
					    <li class="active ongoing-order-history" user-id="{{$data['user']->id}}"><a data-toggle="tab" href="#Ongoing">Pending Trips</a></li>
					    <li class="repeat-order-history" user-id="{{$data['user']->id}}"><a data-toggle="tab" href="#Repeat">Completed Trips</a></li>
					    <li class="order-history" user-id="{{$data['user']->id}}"><a data-toggle="tab" href="#History">Cancelled Trips</a></li>
					</ul>
				</div>

				<div class="tab-content">
				    <div id="Ongoing" class="dashboard tab-pane fade in active">
				    	<div class="driver-data-table">
							<div class="data-table">
								<div class="table-fbutton clearfix">
									<div class="btns">
										<h2>Completed Trips :</h2>
									</div>
								</div>
								<div class="table-responsive">
									<table id="laravel_datatable" class="table">
										<thead>
											<tr>
												<th>Sr.No.</th>
												<th>Trip Reference Id</th>
												<th>Driver Name</th>
												<th>From</th>
												<th>To</th>
												<th>Price</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
				    </div>

				    <div id="Repeat" class="tab-pane fade">
						<div class="driver-data-table">
							<div class="data-table">
								<div class="table-fbutton clearfix">
									<div class="btns">
										<h2>Completed Trips :</h2>
									</div>
								</div>
								<div class="table-responsive">
									<table id="laravel_datatable2" class="table">
										<thead>
											<tr>
												<th>Sr.No.</th>
												<th>Trip Reference Id</th>
												<th>Driver Name</th>
												<th>From</th>
												<th>To</th>
												<th>Price</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
				    </div>

				    <div id="History" class="tab-pane fade">
						<div class="driver-data-table">

							<div class="data-table">
								<div class="table-fbutton clearfix">
									<div class="btns">
										<h2>Cancelled Trips :</h2>
									</div>
								</div>
								<div class="table-responsive">
									<table id="laravel_datatable3" class="table">
										<thead>
											<tr>
												<th>Sr.No.</th>
												<th>Trip Reference Id</th>
												<th>Driver Name</th>
												<th>From</th>
												<th>To</th>
												<th>Price</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
				    </div>
				</div>
		</div>
	</div>
</div>
@endsection
@push('js')
<script type="text/javascript">
	var user_id='{{$data['user']->id}}';
</script>
@endpush