 	<form method="POST" id="edit-contracttemplates-form" action="#">
				@csrf

				<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Provider" data-content="Provider"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-type.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
								<select class="form-control edit_contract_id contract_id" name="contract_id" id="contract_id" required>
									<option value="">-- Select Provider --</option>
									 @foreach($api_provider as $key => $value)
					                <option @if($contract_templates->contract_id == $value->id) {{ 'selected'}} @endif value="{{$value->id}}">{{$value->api_title}}</option>
					                @endforeach
								</select>
							 	<span class="error" id="edit_contract_id_error"></span>

							</div>
						</div>

						 <div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Document" title="Document"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/document.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>

								<select class="form-control contract_document_id" name="contract_document_id" id="contract_document_id"  >
									@if($documents->isEmpty())
									<option value="">Record Not Found</option>
								@else
									@foreach($documents as $key => $value)
									    @if($key == 0)
									     <option value="">-- Select Document--</option>
										@endif
										<option value="{{$value->id}}" @if($contract_templates->contract_document_id == $value->id) {{'selected'}} @endif>{{$value->document_title}}</option>
									@endforeach
								@endif
					            </select>

							 <span class="error" id="edit_contract_document_id_error"></span>
							 		<button type="button" data-toggle="modal" data-target="#add_document" class="btn btn-success btn-sm edit_upload_document upload_document col-12 mt-1">Upload New</button>
							</div>
						</div>



						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Contract Title" title="Contract Title"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/Name.png" class="icon bl-icon" width="30" ></span>  <span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Contract title" name="contract_title" id="contract_title" value="{{$contract_templates->contract_title }}">
								 <span class="error" id="edit_contract_title_error"></span>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Contract Restrict Seat" title="Contract Restrict Seat"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/restrict-seat.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
								<select name="contract_restrict_seat" id="contract_restrict_seat" class="form-control">
									<option  @if($contract_templates->contract_restrict_seat == 1) {{'selected'}} @endif value="1">Yes</option>
									<option   @if($contract_templates->contract_restrict_seat == 0) {{'selected'}} @endif value="0"  >No</option>
								</select>
								 <span class="error" id="edit_contract_restrict_seat_error"></span>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Expire After"  data-toggle="tooltip" data-placement="left"><img src="{{asset('admin_assets')}}/images/expire_after.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
								<div class="row">

									<div class="col-sm-6">
											<select class="form-control" name="expired_value" id="expired_value">

												 <optgroup  id="eoption_day" @php if($contract_templates->expired_option ==  'Day'){ echo "style='display:block'"; } else { echo "style='display:none'";  } @endphp  id="option_day" label="days">

							        		 		 @for ($i = 1; $i <= 31; $i++)
								            		<option @if($contract_templates->expired_value == $i) {{'selected'}} @endif value="{{ 'Day_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>

							            	      <optgroup  id="eoption_month" @php if($contract_templates->expired_option ==  'Month'){ echo "style='display:block'"; } else { echo "style='display:none'";  } @endphp    id="option_month" label="Month">
							        		 		   @for ($i = 1; $i <= 12; $i++)
								            		<option @if($contract_templates->expired_value == $i) {{'selected'}} @endif value="{{ 'Month_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>

							            	      <optgroup id="eoption_week"  @php if($contract_templates->expired_option ==  'Week'){ echo "style='display:block'"; } else { echo "style='display:none'";  } @endphp  id="option_week" label="Week">
							        		 		 @for ($i = 1; $i <= 52; $i++)
								            		<option @if($contract_templates->expired_value == $i) {{'selected'}} @endif value="{{ 'Week_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>
							            	     <span class="error" id="edit_expired_value_error"></span>

							    		</select>
									</div>

									<div class="col-sm-6">
										<select class="form-control" name="expired_option" id="expired_option">
									        <option @if($contract_templates->expired_option ==  'Day') {{'selected'}} @endif  value="day">Day(s)</option>
									      <!--   <option  @if($contract_templates->expired_option ==  'Month') {{'selected'}} @endif value="month">Month(s)</option>
									        <option @if($contract_templates->expired_option ==  'Week') {{'selected'}} @endif value="week">Week(s)</option> -->
									    </select>
									</div>
								</div>
								 <span class="error" id="edit_expired_option_error"></span>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" data-content="Contract Description" title="Contract Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="30" ></span>  </h6>
								 <textarea class="form-control" name="contract_description" id="contract_description" placeholder="Contract Description">{{ $contract_templates->contract_description}}</textarea>
								 <span class="error" id="edit_contract_description_error"></span>
							</div>
						</div>




						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button data-id="{{ $contract_templates->id }}" class="btn btn-info edit_contracttemplate" type="submit"> Update Contract Template</button>
						</div>
					 </div>
					 </div>

			</form>
