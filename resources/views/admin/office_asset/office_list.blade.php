<div class="data-table">
		<div class="heading-search">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<h2>Total Office: {{count($data['offices'])}}</h2>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="searchbar-wrapper">
						<div class="searchbar">
							<i class="fas fa-search"></i>
							<input type="text" name="">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="custom-table-height">
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Office No.</th>
							<th>Office No.</th>
							<th>Office Name</th>
							<th>Total Seat</th>
							<th>Date/Time </th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data['offices'] as $key => $office)
						<tr>
								<td>{{$office->office_id}}</td>
								<td>{{$office->office_number}}</td>
								<td>{{$office->office_name}}</td>
								<td>{{count($office->seats)}}</td>
								<td>{{date('d/m/Y',strtotime($office->created_at))}}, {{date('H:i A',strtotime($office->created_at))}}</td>
								<td>
									<a href="{{url('admin/office/edit_office',$office->office_id)}}" class="button accept">Edit</a>
									<a href="{{url('admin/office/office_details',$office->office_id)}}" class="button accept">Details</a>

									<button class="button reject btn-delete" data-url="{{route('admin/office/destroy',$office->office_id)}}">Delete</button>
								</td>
						</tr> <!--end-->
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>