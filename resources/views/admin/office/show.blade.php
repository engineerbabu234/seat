@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="title">
						<h2>Office Name</h2>
						<p>{{$data['office']->office_name}}</p>
					</div>
				</div>
				<!-- <div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="add_building.html" class="add-tender">Add Office</a>
					</div>
				</div> -->
			</div>
		</div><!--END header-->
		<div class="seat-status">
			<h1>Seat Status</h1>
			<h2 class="ts">Total Seats: {{$data['office']->total_seats}}</h2>
			<h2 class="as">Available Seat: {{$data['office']->available_seat}}</h2>
			<h2 class="bs1">Booked Seat: {{$data['office']->reserved_seat}}</h2>
			<h2 class="bs">Blocked Seat: {{$data['office']->blocked_seat}}</h2>
		</div>
		<!--my tenders-->
		<div class="custom-data-table">
				<div class="data-table">
					<div class="heading-search">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<h2>Seat Details</h2>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
{{-- 												<div class="searchbar-wrapper">
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
										<th>Serial No.</th>
										<th>Seat No.</th>
										<th>Seat Status</th>
										<th>Date/Time </th>
										<th>Action</th>
									</tr>
								</thead>
								{{-- <tbody>
									@php $i = 1; @endphp
									@foreach($data['office']->seats as $seat)
										<!--single table row-->
										<tr>
											<td>{{$i++}}</td>
											<td>{{$seat->seat_no}}</td>
											<td>
												@if($seat->status=='1')
													<label class="label rejected">Booked</label>
												@else
													<label class="label accepted">Available</label>
												@endif
												
											</td>
											<td>{{date('d/m/Y',strtotime($seat->created_at))}},{{date('H:i A',strtotime($seat->created_at))}}</td>
											<td>
												
											</td>
										</tr> 
									@endforeach
								</tbody> --}}
							</table>
						</div>
					</div>
				</div>
			</div><!--END my tenders-->

	</div>
</div>
@endsection
<script type="text/javascript">
	var office_id='{{$data['office']->office_id}}';
</script>