@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->

		@if(Session::get('message'))
			<div class="alert @if(Session::get('status')) alert-success @else alert-danger @endif">
				@if(Session::get('status'))
			       <strong>Success!</strong> {{Session::get('message')}}
				@else
			       <strong>Failed!</strong> {{Session::get('message')}}
				@endif
			</div>
 		@endif
		<div class="header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="title">
						<h2>Invited Users</h2>
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
							<h2>Total User: {{$users->total()}}</h2>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12 text-right">
							 <a href="{{route('create.invitation.link')}}">Create Invitation Link</a>
						</div>
					</div>
				</div>
				<div class="custom-table-height">
					<div class="table-responsive">
						<table class="table table-striped text-center" id="laravel_datatable">
							<thead>
								<tr>
									<th>#Id</th>
									<th>Name</th>
									<th>Phone</th>
									<th>Is Registered</th>
									<th>Date </th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach($users as $key => $value)
									<tr>
										<td>{{$value->id}}</td>
										<td>{{$value->name}}</td>
										<td>{{$value->email}}</td>
										<td>{{$value->phone}}</td>
										<td>{{date('Y-M-d',strtotime($value->created_at))}}</td>
										<td>
											<button data-id="{{$value->id}}" class="button btn-danger delete">Delete</button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
