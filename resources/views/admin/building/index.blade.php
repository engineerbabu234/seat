@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="title">
						<h2>Building List</h2>
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
								<h2>Total Buildings: {{$data['building_count']}}</h2>
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
										<th>Serial No.</th>
										<th>Building Name</th>
										<th>Total Office</th>
										<th>Date/Time </th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>	
									<tr>
										<td>1</td>
										<td> Building 1 </td>
										<td> 5 </td>
										<td>14/08/2020, 11:40 AM</td>
										<td>
											<a href="my_offices_list.html" class="button accept">Details</a>
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