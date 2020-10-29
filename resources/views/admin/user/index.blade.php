@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="title">
						<h2>Users</h2>
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
							<h2>Total User: {{$data['user_count']}}</h2>
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
									<th>Serial No</th>
									<th>User Name</th>
									<th>Job Profile</th>
									<th>Email</th>
									<th>Profile Image </th>
									<th>Date </th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Ganesh</td>

									<td>ganeshdhamande7@gmail.com</td>
									<td><img src=""></td>
									<td>08-08-2020</td>
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