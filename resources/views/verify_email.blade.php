@php
namespace App\Helpers;
use Auth;
$product_place_holder_image=ImageHelper::getProductPlaceholderImage();
use Illuminate\Support\Facades\Session;
@endphp
<!DOCTYPE html>
<html>
 @include('layouts.head')
 @stack('css')
 <style type="text/css">
    .successfully-email-varification{
        margin: 0;
        font-size: 30px;
        color: #192435;
        text-align: center;
        font-weight: 500;
    }
</style>
<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-xs-4 col-sm-4">
                <a href="index.html" class="logo">
                @php
                    $logo=env('Logo');
                    if($logo){
                        $Admin = \App\Models\User::where('role','1')->first();
                        @endphp
                            <img src="{{ImageHelper::getProfileImage($Admin->logo_image)}}">
                        @php
                    }else{
                        @endphp
                        <img src="{{asset('front_end')}}/images/logo.png">
                        @php
                    }
                    @endphp

                </a>
            </div>
            <div class="col-md-8 col-xs-8 col-sm-8">

            </div>
        </div>
    </div>
</header>
    @section('content')@show
       <!--building office-->
    <section class="reaserve-seat reaserve-seat-page">
        <div class="container">
            @if($data['status'] == '1')
             <h2 class="successfully-email-varification"><i class="far fa-check-circle"></i> Successfully verified your email</h2>
           @elseif($data['status'] == '2')
             <h2 class="successfully-email-varification"><i class="fa fa-times"></i> Failed to verify your email</h2>
           @else
            <h2 class="successfully-email-varification"><i class="far fa-clock"></i> This link is no longer valid</h2>
           @endif
           <p style="text-align:center;margin-top: 30px;"><a href="{{route('login')}}">--Login--</a><p>
        </div>
    </section><!--END building office-->
    @include('layouts.footer')
    @include('layouts.foot')
@stack('js')
</body>
</html>
