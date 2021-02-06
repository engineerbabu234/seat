
	<div class="inner-body">
		<form method="POST" id="document_attech_info" action="#">
	@csrf
	<div class="row">
		<div class="col-sm-12">
			<h5>Document Attech</h5>
			<input type="hidden" name="office_assets_id" id="office_assets_id" value="{{ isset($id) ? $id : ''}} ">
			<select class="form-control selectmultiple"  multiple name="document_attech[]" id="document_attech" required>
				@if(isset($documents))
					@if($documents->isEmpty())
						<option value="">Record Not Found</option>
					@else
						@foreach($documents as $key => $value)
							<option value="{{$value->id}}"  >{{$value->document_title}}</option>
						@endforeach
					@endif
				@endif
			</select>
			<span class="error" id="document_attech_error"></span>
		</div>


		<div class="col-sm-12 text-right">
			<div class="add-product-btn  pt-5  ">
				<button class="btn btn-info save_document_attech" type="submit"> Save</button>
			</div>
		</div>

	</div>
</form>
