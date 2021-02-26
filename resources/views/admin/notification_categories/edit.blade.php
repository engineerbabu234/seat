 	<form method="POST" id="edit-notification_categories-form" action="#">
 	   <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Notification Categories</h4>

        <button type="button" class="close_new close_edit_notification_categories"  >&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" >
				@csrf
					<div class="edit-notification_categories">
					<div class="row">
						<input type="hidden" id="notification_categories_id" value="{{$id}}">
						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"  ><span class="iconWrap iconSize_32" title="Title"  data-toggle="tooltip"  data-content="Title"  data-placement="left"><img src="{{asset('admin_assets')}}/images/title.png" class="icon bl-icon" width="40" ></span><span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Title" id="title" name="title" value="{{$notification_categories->title}}" required>
								 <span class="error" id="edit_title_error"></span>
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
											<option @if($notification_categories->notification_api_id == $value->id) {{ 'selected'}}  @endif value="{{$value->id}}">{{$value->api_title}}</option>
											@endforeach
											@endif
										</select>
										<span class="error" id="edit_notification_api_id_error"></span>
									</div>
							 	</div>
							 </div>


						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Description"  data-toggle="tooltip"  data-content="Description"  data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span> </h6>
								 <textarea class="form-control" name="description" id="description" rows="6">{{$notification_categories->description}}</textarea>
								 <span class="error" id="edit_description_error"></span>
							</div>
						</div>

						<div class="col-sm-12">
							<div class="add-product-btn text-center">
									<button data-id="{{$notification_categories->id}}" class="btn btn-success edit_notification_categories" type="submit"> Save</button>
							</div>
						</div>

					 </div>

				</div>

 				</div>
			</form>
