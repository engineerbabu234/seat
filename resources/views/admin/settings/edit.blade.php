<form method="POST" id="edit-setting-form"  action="#">
				@csrf

		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<h6 class="sub-title"><span title="Api Access"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-access.png" class="bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
					<select class="form-control" name="api_access">
						<option @if($settings->api_access == 0) {{'selected'}} @endif value="0">No Api Access to User</option>
						<option @if($settings->api_access == 1) {{'selected'}} @endif value="1">Api Access to User</option>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
				<div class="add-product-btn text-center"><br>
					<button data-id="{{ $settings->id }}" class="btn btn-info edit_settings" type="submit"> Update Setting</button>
				</div>
			</div>
		 </div>
		 </div>

	</div>
</form>
