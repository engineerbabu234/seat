@php
namespace App\Helpers;
Use Auth;
$product_place_holder_image=ImageHelper::getProductPlaceholderImage();
use Illuminate\Support\Facades\Session;
@endphp
<header>
	<div class="container">
		<div class="row">
            <div class="col-md-4 col-xs-4 col-sm-4">
                <a href="{{url('/')}}" class="logo">
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

                <nav>
                    <ul>
                        <li><a class="@if((substr(strrchr(url()->current(),"/"),1)=='seat_reservation')){{'active'}}@endif " href="{{url('/')}}"  href="{{url('/')}}"> Home</a></li>


                        @if (!Auth::guest())
                            <li><a class="@if((substr(strrchr(url()->current(),"/"),1)=='history')){{'active'}}@endif " href="{{url('/history')}}"> History</a></li>
                            <li >
                                <a class="@if((substr(strrchr(url()->current(),"/"),1)=='profile')){{'active'}}@endif " href="{{url('/profile')}}">
                                <div class="profile-icon">
                                <span><img src="{{ImageHelper::getProfileImage(Auth::User()->profile_image)}}"></span>
                                {{Auth::User()->user_name}}
                                </div>
                                </a>
                            </li>
                            <li>

                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="fas fa-power-off"></i>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                                </form>
                            </li>
                        @else
                            <li><a href="{{url('/login')}}"> Login</a></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
	</div>
</header>
