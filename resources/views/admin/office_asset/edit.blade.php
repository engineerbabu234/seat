	<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-form">
					@csrf
				<div class="add-office">
					<input type="hidden" name="id"  value="{{ $officeAsset->id }}">
					<div class="form-group">
						<h6 class="title">Building</h6>
						<select class="form-control bindOffice" name="building_id" id="edit_building_id" required>
							@if($buildings->isEmpty())
								<option value="">Record Not Found</option>
							@else
								@foreach($buildings as $key => $value)
								    @if($key == 0)
								     <option value="">-- Select building--</option>
									@endif
									<option value="{{$value->building_id}}" @if($officeAsset->building_id == $value->building_id) {{'selected'}} @endif>{{$value->building_name}}</option>
								@endforeach
							@endif
						</select>
						 <span class="error" id="edit_building_id_error"></span>
					</div>

					<!--single-entry-->
					<div class="single-entry">
						<div class="form-group">
							  <h6 class="sub-title">Office</h6>
							  <select class="form-control OfficeData" name="office_id" id="edit_office_id" required>
								<option value="">-- Select Office -- </option>
								@foreach($Office as $key => $value)
									<option value="{{$value->office_id}}" @if($officeAsset->office_id == $value->office_id) {{'selected'}} @endif>{{$value->office_name}}</option>
								@endforeach
							  </select>
							  <span class="error" id="edit_office_id_error"></span>
						</div>

						<div class="form-group">
							<h6 class="sub-title">Assets Title</h6>
							<input type="text" class="form-control" placeholder="Assets Title" name="title" value="{{ $officeAsset->title }}" required>
							 <span class="error" id="edit_title_error"></span>
						</div>

						<div class="form-group">
							<h6 class="sub-title">Description</h6>
							<textarea rows="4"  class="form-control" placeholder="Write here..." name="description">{{ $officeAsset->description }}</textarea>
							 <span class="error" id="edit_description_error"></span>
						</div>

						<div class="form-group">
							<h6 class="sub-title">Preview Image</h6>
							<input type="file" required id="preview_image" name="preview_image" class="form-control dropify-event" data-default-file="{{ $assets_image }}" /><br>
                             <span class="error" id="edit_preview_image_error"></span>

						</div>

					</div><!--END single-entry-->

					<div class="edit-product-btn text-right">
						<button data-id="{{ $officeAsset->id }}" class="edit-office-btn btn btn-info"> Update Office Asset</button>
					</div>

				</div><!--END my tenders-->
		    </form>
