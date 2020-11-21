@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<div class="driver-data-table">
			<div class="top-trip clearfix">
				<h2>Trips</h2>
				<!-- <button data-toggle="modal" data-target="#filter">Filter</button> -->
			</div>

			<div class="data-table">
				<div class="table-fbutton clearfix">
					
					<div class="s-btn">
						<div class="searchbar">
							<label>Search :</label>
							<input type="text" name="">
						</div>
						{{-- <a href="{{route('admin/promocode/create')}}"><button>Add</button></a> --}}
					</div>
				</div>
				<div class="table-responsive">
					<table id="laravel_datatable" class="table">
						<thead>
							<tr>
								<th>Sr.No.</th>
								<th>User Name</th>
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
@endsection