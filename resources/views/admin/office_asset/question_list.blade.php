
@php $category = null @endphp
@foreach($question as $key => $value)
 	@if ($value->title != $category)
 		@php   $category = $value->title; @endphp
 	 	<div class="scrollit">
 	 	<div class="row">
       	{{  $category }}
  @endif

<div class="col-sm-12"> <div class="choice label label-info"  id="{{$value->id}}">{{$value->id}} </div> {{$value->question}}
</div>
@if ($value->title != $category)
</div></div>
 @endif

@endforeach
