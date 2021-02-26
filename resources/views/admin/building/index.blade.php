@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
					<div class="title">
			<div class="row align-items-center">
				<div class="col-md-6 col-sm-6 col-xs-6">
						<h2>Building List</h2>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_building"><i class="fas fa-plus"></i></a>
					</div>
				</div>
			</div>
					</div>
		</div><!--END header-->


		<div class="custom-data-table">
				<div class="data-table">

					<div class="custom-table-height">
						<div class="table-responsive">
							<table class="table table-striped text-center" id="laravel_datatable">
								<thead>
									<tr>
										<th><span class="iconWrap iconSize_32" title="Serial No."  data-trigger="hover" data-content="Serial No of Building" data-placement="left"><img src="{{asset('admin_assets')}}/images/number.png" class="icon bl-icon" width="20" ></span> </th>
										<th><span class="iconWrap iconSize_32" title="Building" data-content="Building Name"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="icon bl-icon" width="30" ></span> </th>
										<th><span class="iconWrap iconSize_32" data-content="Total Office for This Building" title="Total Office"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/offices.png" class="icon bl-icon" width="30" ></span></th>
										<th><span class="iconWrap iconSize_32" data-content="Update Date " title="Update Date"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/reservation_request.png" class="icon bl-icon" width="30" > </th>
										<th><span class="iconWrap iconSize_32" data-content="Action For Building" title="Action"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="30" ></span></th>
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
		</div>
	</div>
</div>


<!-- The Modal -->
<div class="modal" id="add_building">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Building</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" id="add-building-form" enctype="multipart/form-data" action="#">
				@csrf
				<div class="add-office">
					<div class="row">
						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Building Name" title="Building Name"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/building.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Building Name" name="building_name" value="{{old('building_name')}}">
							 <span class="error" id="building_name_error"></span>
						</div>
						</div>
						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Building Address"  title="Building Address"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/address.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Building Address" name="building_address" value="{{old('building_address')}}">
							 <span class="error" id="building_address_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32"  data-content="Building Description"  title="Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span></h6>
							<textarea rows="4" class="form-control" placeholder="Write here..." name="description">{{old('description')}}</textarea>
							 <span class="error" id="description_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button class="btn btn-info add_building" type="submit"> Add Bulding</button>
						</div>
					 </div>
					 </div>

				</div>
			</form>
      </div>
      <!-- Modal footer -->


    </div>
  </div>
</div>


<div class="modal" id="edit_building">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Building</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="edit_building_info">

      </div>
    </div>
  </div>
</div>

@endsection
