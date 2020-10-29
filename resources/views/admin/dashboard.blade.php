@extends('admin.layouts.app')
@section('content')
	<div class="main-body">
		<div class="inner-body">
			<!--header-->
			<div class="header">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<div class="title">
							<h2>Dashbaord</h2>
						</div>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						{{-- <div class="btns">
							<a href="add_building.html" class="add-tender">Add Building/Office</a>
						</div> --}}
					</div>
				</div>
			</div><!--END header-->

			<!--my tenders-->
			<div class="setas-details">
				<div class="inner-header">
					<h2>All Buildings offices details</h2>
					<p>In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate the visual form of a document or a n publishing and graphic design,</p>
				</div>

				<div class="row">
					@if($data['buildings']->isEmpty())
						<h1>Record not found</h1>
					@else
						@foreach($data['buildings'] as $key => $value)
							<div class="col-md-3 col-sm-4 col-xs-6 col-xs-6">
								<a href="{{url('admin/building/office_list/'.$value->building_id)}}">
									<div class="single-data">
										<div class="heading">
											<h3>{{$value->building_name}}</h3>
										</div>
										<div class="txt">
											<img src="{{asset('admin_assets')}}/images/seat.png">
											<h2>Total Office</h2>
											<p>{{$value->office_count}}</p>
										</div>
									</div>
								</a>
							</div>
						@endforeach
					@endif
				</div>
			</div><!--END my tenders-->
		</div>
	</div>
@endsection
