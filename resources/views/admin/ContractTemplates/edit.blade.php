 	<form method="POST" id="edit-apiconnections-form" action="#">
				@csrf

				<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Api Type" data-content="Api Type"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-type.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
							<select class="form-control api_type" name="api_type" id="api_type" required>
								 <option value="">-- Select Api Type --</option>
								 @foreach($api_type as $key => $value)
				                	<option @if($apiconnections->api_type == $key) {{ 'selected'}} @endif  value="{{$key}}">{{$value}}</option>
				                @endforeach
						</select>
						 <span class="error" id="edit_api_type_error"></span>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Api Provider" title="Api Provider"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-type.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>

							<select class="form-control api_provider" name="api_provider" id="api_provider"  >
								<option value="">-- Select Api Provider --</option>
								@if($apiconnections->api_type ==1 )
									 @foreach($api_teleconference as $key => $value)
									 	<option @if($apiconnections->api_provider == $key) {{ 'selected'}} @endif  value="{{$key}}">{{$value}}</option>
									 @endforeach
								@else
									 @foreach($api_contract as $key => $value)
									 	<option @if($apiconnections->api_provider == $key) {{ 'selected'}} @endif  value="{{$key}}">{{$value}}</option>
									 @endforeach
								@endif
				            </select>
						 <span class="error" id="edit_api_provider_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Api Description" title="Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="30" ></span>  </h6>
							 <textarea class="form-control" name="api_description" id="api_description">{{$apiconnections->api_description}}</textarea>
							 <span class="error" id="edit_api_description_error"></span>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Api Title" title="Title"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Title" value="{{$apiconnections->api_title}}" name="api_title" required>
							 <span class="error" id="edit_api_title_error"></span>
						</div>
						</div>
						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span title="Api Key"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-access.png" class="bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" value="{{ $apiconnections->api_key }}" placeholder="Api Key" name="api_key" required>
							 <span class="error" id="edit_api_key_error"></span>
						</div>
						</div>

						<div class="col-sm-6">
						<div class="form-group">
							<h6 class="sub-title"><span title="Api Secret"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/secrect_key.png" class="bl-icon" width="25" ></span>  </h6>
							<input type="text" class="form-control" value="{{ $apiconnections->api_secret }}" placeholder="Api Secret" name="api_secret" required>
							 <span class="error" id="edit_api_secret_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button data-id="{{ $apiconnections->id }}" class="btn btn-info edit_aapiconnections" type="submit"> Update Api Connections</button>
						</div>
					 </div>
					 </div>

			</form>
