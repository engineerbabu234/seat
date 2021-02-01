@extends('layouts.app')
@section('content')


    <section class="reaserve-seat">
        <div class="container">
            <h6>Welcome</h3>
        </div>
    </section>
@endsection
@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('front_end')}}/css/jquery-ui.css">
<link  href="{{asset('front_end')}}/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@push('js')
<script src="{{asset('front_end')}}/js/jquery.dataTables.min.js"></script>
<script src="{{asset('front_end')}}/js/jquery-ui.js"></script>

@endpush
