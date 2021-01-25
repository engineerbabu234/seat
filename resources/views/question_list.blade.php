@php $i = 1; @endphp

	@foreach ($Question as $key => $value)
	<div class="row">

	<div class="col-sm-8">{{ $i++ . ') ' . $value->question }}
		<span class="error question_error" id="logic_question_{{$quesionaire_id}}_{{$value->id}}_error">
	</div>
		<div class="col-sm-2"><label>Yes<input type="radio"  name="logic_question[{{$quesionaire_id}}][{{$value->id}}]" class="radio" value="1"></label></div>
		<div class="col-sm-2"><label>No<input type="radio"  name="logic_question[{{$quesionaire_id}}][{{$value->id}}]" class="radio" value="0"></label></div>

	</div>
		@endforeach
