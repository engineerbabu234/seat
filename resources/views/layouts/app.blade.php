<!DOCTYPE html>
<html>
 @include('layouts.head')
 @stack('css')
<body>
    @include('layouts.header')
    @section('content')@show
    @include('layouts.footer')
    @include('layouts.foot')
@stack('js')
</body>
</html>