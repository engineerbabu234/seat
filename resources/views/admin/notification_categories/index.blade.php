@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->

		<div class="header">
					<div class="title">
			<div class="row align-items-center">
				<div class="col-md-6 col-sm-6 col-xs-6">
						<h2>Notification Categories List</h2>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_notification_categories"><i class="fas fa-plus"></i></a>
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
							<th class="text-left"><span class="iconWrap iconSize_32" title="ID."  data-trigger="hover" data-content="Id" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="25" ></span> </th>
						    <th><span class="iconWrap iconSize_32" title="Title" data-content="Title" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/title.png" class="icon bl-icon" width="30" ></span> </th>
						    <th><span class="iconWrap iconSize_32" title="Title" data-content="Api Title"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="30" ></span>  </th>
							<th><span class="iconWrap iconSize_32" title="Description" data-content="Description" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Update Date" data-content="Update Date"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="25" ></span>  </th>

							<th nowrap><span class="iconWrap iconSize_32" title="Action" data-content="Action" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="25" ></span> </th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
		</div>
	</div>
</div>




<!-- The Modal -->
<div class="modal" id="add_notification_categories">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Notification categories</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" id="add-notification_categories-form" action="#">
				@csrf
				<div class="add-notification_categories">
					<div class="row">

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Title"  data-trigger="hover" data-content="Title" data-placement="left"><img src="{{asset('admin_assets')}}/images/title.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Title" id="title" name="title" required>
								 <span class="error" id="title_error"></span>
							</div>

							 <div class="row">
							 	<div class="col-sm-6">Api Notification Title</div>
							 	<div class="col-sm-6">
							 		<div class="form-group">

										<select class="form-control  " name="notification_api_id" id="notification_api_id" required>
											@if($notification_api->isEmpty())
											<option value="">Record Not Found</option>
											@else
											@foreach($notification_api as $key => $value)
											@if($key == 0)
											<option value="">-- Select Notification Title--</option>
											@endif
											<option value="{{$value->id}}">{{$value->api_title}}</option>
											@endforeach
											@endif
										</select>
										<span class="error" id="notification_api_id_error"></span>
									</div>
							 	</div>
							 </div>


						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Description" data-content="Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span> <span class="text-danger">*</span></h6>
								 <textarea class="form-control" name="description" id="description" rows="6"></textarea>
								 <span class="error" id="description_error"></span>
							</div>
						</div>


						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button class="btn btn-info add_notification_categories_data" type="submit"> Add Notification Categories</button>
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


<div class="modal" id="edit_notification_categories">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"  id="edit_notification_categories_info">

    </div>
  </div>
</div>


@endsection
@push('css')
<style type="text/css">
	.close_new{
	    background-color: transparent;
	    border: 0;
	    font-size: 26px;
	}
</style>
@endpush
@push('js')
    <script src="{{asset('admin_assets/')}}/js/jquery-ui.js"></script>

 <script type="text/javascript" src="{{URL::asset('admin_assets/pages')}}/notification_categories/index.js"></script>

@endpush
