<form method="POST" id="contract_template" action="#">
	@csrf
	<div class="row">
		<div class="col-sm-12">
			<h5>Request a Contract Signature</h5>
			<input type="hidden" name="office_asset_id" id="office_asset_id" value="{{ $office_assets_id }}">

			<div class="row">
                <div class="col-sm-4">
                    <h5><span title="building"><img src="{{asset('admin_assets')}}/images/building.png" alt="building" class="bl-icon" width="30" > </span> {{$building->building_name}}</h5>
                </div>
                <div class="col-sm-4">
                    <h5><span title="Office"><img src="{{asset('admin_assets')}}/images/offices.png" alt="office" class="bl-icon" width="30" ></span> {{$office->office_name}}</h5>
                </div>
                <div class="col-sm-4">
                    <h5><span title="Assets"><img src="{{asset('admin_assets')}}/images/assets.png" alt="assets" class="bl-icon" width="30" ></span> {{$office_assets->title}}</h5>

                    </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-6">
                	<h5>Select Contract</h5>
					<select class="form-control" name="document_id" id="document_id" required>
					@if($contract == '')
						<option value="">Record Not Found</option>
					@else
						<option value="">-- Select Contract Template --</option>
						@foreach($contract as $key => $value)
							<option value="{{$value->id}}"  >{{$value->contract_title}}</option>
						@endforeach
					@endif
					</select>
					<span class="error" id="contract_id_error"></span>
			</div>

			<div class="col-sm-6">
				<h5>Select User</h5>
				<select class="form-control userlist" name="user_id" id="user_id" required>
					@if($user_list->isEmpty())
						<option value="">Record Not Found</option>
					@else
						<option value="">-- Select User --</option>
						@foreach($user_list as $key => $value)
							<option value="{{$value->id}}"  >{{$value->email}}</option>
						@endforeach
					@endif
				</select>
			</div>
			<span class="error" id="user_id_error"></span>
		</div>
		<br>

		<div class="col-sm-12 text-center">
			<div class="  pt-5  ">
				<button class="btn btn-info send_contract_template" type="submit">Request Signature</button>
			</div>
		</div>

	</div>
</form>
