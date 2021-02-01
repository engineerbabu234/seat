@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="title">
						<h2>Seat Reservation Request</h2>
					</div>
				</div>
				<!-- <div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="add_building.html" class="add-tender">Add Office</a>
					</div>
				</div> -->
			</div>
		</div><!--END header-->

		<!--my tenders-->
		<div class="custom-data-table">
			<div class="data-table">
				<div class="heading-search">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<h2>Total Request: {{$data['seat_count']}}</h2>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							{{-- <div class="searchbar-wrapper">
								<div class="searchbar">
									<i class="fas fa-search"></i>
									<input type="text" name="">
								</div>
							</div> --}}
						</div>
					</div>
				</div>
				<div class="custom-table-height">
					<div class="table-responsive">
						<table class="table table-striped" id="laravel_datatable">
							<thead>
								<tr>
									<th><span class="iconWrap iconSize_32" title="Reservation ID"  data-trigger="hover" data-content="Reservation ID" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="30" ></span> </th>
									<th><span class="iconWrap iconSize_32" title="Building" data-content="Building"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="icon bl-icon" width="30" ></span></th>
									<th><span class="iconWrap iconSize_32" title="Office" data-content="Office"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/offices.png" class="icon bl-icon" width="30" ></span></th>
									<th><span class="iconWrap iconSize_32" title="Employee" data-content="Employee" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/employee.png" class="icon bl-icon" width="30" ></span> </th>
									<th><span class="iconWrap iconSize_32" title="Email" data-content="Email" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/email.png" class="icon bl-icon" width="30" ></span>  </th>
									<th><span class="iconWrap iconSize_32" title="Profile Image"  data-trigger="hover" data-content="Profile Image" data-placement="left"><img src="{{asset('admin_assets')}}/images/progile-image.png" class="icon bl-icon" width="30" ></span>  </th>
									<th><span class="iconWrap iconSize_32" title="Seat No." data-content="Seat No" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/seat-no.png" class="icon bl-icon" width="30" ></span> </th>
									<th><span class="iconWrap iconSize_32" title="Date" data-content="Date" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="30" ></span> </th>
									<th><span class="iconWrap iconSize_32" title="Action" data-content="Action" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="30" ></span> </th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>17575</td>
									<td>Pukharaj Corporate</td>
									<td> Codemeg Soft </td>
									<td> John Cartor </td>
									<td>5</td>
									<td>14/08/2020</td>
									<td>
										<button class="button accept">Accept</button>
										<button class="button reject">Reject</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
