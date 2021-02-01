<form method="POST" id="edit-users-form"  action="#">
				@csrf

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<h6 class="sub-title">User Type<span class="text-danger">*</span></h6>
					<select class="form-control" name="role">
						<option @if($users->role == 2) {{'selected'}} @endif value="2">User</option>
						<option @if($users->role == 3) {{'selected'}} @endif value="3">Cleaner</option>
					</select>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<h6 class="sub-title">Api Access<span class="text-danger">*</span></h6>
					<select class="form-control" name="api_access">
						<option @if($users->api_access == 1) {{'selected'}} @endif value="1">Yes</option>
						<option @if($users->api_access == 0) {{'selected'}} @endif value="0">No</option>
					</select>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<div class="add-product-btn text-center"><br>
						<button data-id="{{ $users->id }}" class="btn btn-info edit_users" type="submit"> Update Role</button>
					</div>
				</div>
		 	</div>
		 </div>

	</div>
</form>
