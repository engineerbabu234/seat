@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="title">
						<h2>Office List</h2>
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
								<h2>Total Office: {{$data['office_count']}}</h2>
							</div>
							{{-- <div class="col-md-6 col-sm-6 col-xs-12">
								<div class="searchbar-wrapper">
									<div class="searchbar">
										<i class="fas fa-search"></i>
										<input type="text" name="">
									</div>
								</div>
							</div> --}}
						</div>
					</div>
					<div class="custom-table-height">
						<div class="table-responsive" >
							<table class="table table-striped" id="laravel_datatable">
								<thead>
									<tr>
										<th>Serial No.</th>
										<th>Office No.</th>
										<th>Office Name</th>
										<th>Total Seat</th>
										<th>Date/Time </th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>501</td>
										<td> Office 1 </td>
										<td> 120 </td>
										<td>14/08/2020, 11:40 AM</td>
										<td>
											<a href="{{url('admin/office/edit_office/')}}" class="button accept">Edit</a>
											<a href="{{url('admin/office/office_details/1')}}" class="button accept">Details</a>

											<button class="button reject">Delete</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div><!--END my tenders-->

	</div>
</div>
@endsection
@push('js')
<script type="text/javascript">
	var building_id='{{$data['building_id']}}';
	//console.log(building_id);
</script>
@endpush