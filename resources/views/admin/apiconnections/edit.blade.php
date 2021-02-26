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
								@elseif($apiconnections->api_type ==2)
									 @foreach($api_contract as $key => $value)
									 	<option @if($apiconnections->api_provider == $key) {{ 'selected'}} @endif  value="{{$key}}">{{$value}}</option>
									 @endforeach
								@elseif($apiconnections->api_type == 3)
									 @foreach($api_notification as $key => $value)
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
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Username" title="Username"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/user-name.png" class="icon bl-icon" width="30" ></span>  </h6>
								<input type="text" class="form-control api_target" placeholder="Username" name="username"  value="{{$apiconnections->username}}" >
								 <span class="error" id="edit_username_error"></span>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Password" title="Password"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/password.png" class="icon bl-icon" width="30" ></span>  </h6>
								<input type="text" class="form-control api_target" placeholder="Password" name="password" value="{{$apiconnections->password}}">
								 <span class="error" id="edit_password_error"></span>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Integrator Key"  data-trigger="hover" data-content="Integrator Key" data-placement="left"><img src="{{asset('admin_assets')}}/images/secrect_key.png" class="icon bl-icon" width="25" ></span>  </h6>
								<input type="text" class="form-control api_target" placeholder="Integrator Key" name="integrator_key" value="{{$apiconnections->integrator_key}}" >
								 <span class="error" id="edit_integrator_key_error"></span>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Host"  data-trigger="hover" data-content="Host" data-placement="left"><img src="{{asset('admin_assets')}}/images/host.png" class="icon bl-icon" width="25" ></span>  </h6>
								<input type="text" class="form-control api_target" placeholder="Host" name="host" value="{{$apiconnections->host}}"  >
								 <span class="error" id="edit_host_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Api Title" title="Title"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span></h6>
							<input type="text" class="form-control" placeholder="Title" value="{{$apiconnections->api_title}}" name="api_title" required>
							 <span class="error" id="edit_api_title_error"></span>
						</div>
						</div>
						<div class="col-sm-4">
						<div class="form-group">
							<h6 class="sub-title"><span title="Api Key" class="iconWrap iconSize_32" data-content="Api Key" data-trigger="hover" data-placement="left" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-access.png" class="bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
							<input type="text" class="form-control api_target" value="{{ $apiconnections->api_key }}" placeholder="Api Key" name="api_key" required>
							 <span class="error" id="edit_api_key_error"></span>
						</div>
						</div>

						<div class="col-sm-4">
						<div class="form-group">
							<h6 class="sub-title"><span title="Api Secret"  class="iconWrap iconSize_32" data-content="Api Secret" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/secrect_key.png" class="bl-icon" width="25" ></span>  </h6>
							<input type="text" class="form-control api_target" value="{{ $apiconnections->api_secret }}" placeholder="Api Secret" name="api_secret" required>
							 <span class="error" id="edit_api_secret_error"></span>
						</div>
						</div>



						<div class="col-sm-12">
						<div class="text-center">
							<button class="btn btn-info test_apiconnections pl-2">Ping</button>
							<button data-id="{{ $apiconnections->id }}" class="btn btn-info edit_aapiconnections " type="submit">Update</button>
						</div>
					 </div>
					 </div>

			</form>
