<form method="POST" id="question-logic" action="#">
	@csrf
	<div class="row">
		<div class="col-sm-12">
			<h5>Quesionaire</h5>
			<input type="hidden" name="office_assets_id" id="office_assets_id" value="{{ $office_assets_id }}">
			<select class="form-control selectmultiple"  multiple name="quesionaire_id[]" id="quesionaire_id" required>
				@if($quesionaire->isEmpty())
					<option value="">Record Not Found</option>
				@else
					@foreach($quesionaire as $key => $value)
						<option value="{{$value->id}}"  >{{$value->title}}</option>
					@endforeach
				@endif
			</select>
			<span class="error" id="quesionaire_id_error"></span>
		</div>
		<br>

		<div class="col-sm-6 text-right">
			<div class="add-product-btn  pt-5  ">
				<button class="btn btn-info save_question_logic" type="submit"> Save</button>
			</div>
		</div>

	</div>
</form>
