@extends('layouts.app')
@section('content')
    <!--building office-->
    <section class="reaserve-seat reaserve-seat-page">
        <div class="container">
            <div class="building-office-list">
                <div class="heading">
                    <h1>Change Password or Profile Details</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet, consectetur consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Lorem ipsum dolor sit amet, consectetur</p>
                </div>

                <div class="profile-page">
                    <div class="row">
                        <!--Acount details-->
                        <div class="col-md-4">
                            <div class="profile-details">
                                <div class="header">
                                    <h2>Account details</h2>
                                </div>
                                <div class="body">
                                    <form method="POST" action="{{ route('update_profile') }}">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="form-group">
                                            <label>Full Name:</label>
                                            <input type="text" class="form-control" placeholder="User Name" name="user_name" value="{{$data['user']->user_name}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Job Profile:</label>
                                            <input type="text" class="form-control" placeholder="Job Profile" name="job_profile" value="{{$data['user']->job_profile}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <input type="email" class="form-control" placeholder="Email" name="email" value="{{$data['user']->email}}" readonly="">
                                        </div>
                                        {{-- <div class="form-group">
                                            <label>Mobile Number:</label>
                                            <input type="text" class="form-control" placeholder="Mobile Number" name="">
                                        </div> --}}

                                        <div class="form-group">
                                            <button type="submit" class="same-btn1">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!--end-->

                        <!--Password details-->
                        <div class="col-md-4">
                            <div class="profile-details">
                                <div class="header">
                                    <h2>Change Password</h2>
                                </div>
                                <div class="body">
                                <form method="POST" action="{{ route('update_password') }}">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="form-group">
                                        <label>Current Password:</label>
                                        <input type="password" class="form-control" placeholder="Current Password" name="old_password">
                                        @error('old_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong style="color: red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>New Password:</label>
                                        <input type="password" class="form-control" placeholder="Current Password" name="new_password">
                                        @error('new_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong style="color: red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password:</label>
                                        <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password">
                                        @error('confirm_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong style="color: red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="same-btn1">Change</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div><!--end-->

                        <div class="col-md-4">
                            <div class="profile-details">
                                <div class="header">
                                    <h2>Profile Picture</h2>
                                </div>
                                <div class="body">
                                    <form method="POST" action="{{ url('update_profile_image') }}" enctype="multipart/form-data"  >
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="profile-picture">
                                            <label>
                                                <input type="file" name="profile_image" id="upload-photo-1">
                                                <img src="{{$data['user']->profile_image}}" id="show-image-1" width="200" >
                                                <input type="hidden" name="profile_base64" id="profile_base64">
                                            </label>

                                        </div>
                                        <div class="form-group text-center">
                                            <button type="submit" class="same-btn1">Update Profile</button>
                                        </div>
{{--                                         <div class="form-group">
                                            <button type="submit" class="same-btn1">Update Profile</button>
                                        </div> --}}
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section><!--END building office-->


<div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel"> </h4>
                            </div>
                            <div class="modal-body">
                            <div id="upload-demo" class="center-block"></div>
                      </div>
                             <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
      </div>
                            </div>
                          </div>
                        </div>



@endsection

@push('css')
<link rel="stylesheet" href="https://foliotek.github.io/Croppie/croppie.css">
<style type="text/css">
    .invalid-feedback {
    display: block;
    width: 100%;
    margin-top: .25rem;
    font-size: 80%;
    color: #dc3545;
}

label.cabinet{
    display: block;
    cursor: pointer;
}

label.cabinet input.file{
    position: relative;
    height: 100%;
    width: auto;
    opacity: 0;
    -moz-opacity: 0;
  filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);
  margin-top:-30px;
}

#upload-demo{
    width: 250px;
    height: 250px;
  padding-bottom:25px;
}
figure figcaption {
    position: absolute;
    bottom: 0;
    color: #fff;
    width: 100%;
    padding-left: 9px;
    padding-bottom: 5px;
    text-shadow: 0 0 10px #000;
}
</style>
@endpush
@push('js')
<script type="text/javascript">
    @if($message = Session::get('success'))
        title='Success';
        message='{{$message}}';
        myalert(title,message)
        function myalert(title,msg){
            $.alert(msg, {
            title: title,
            closeTime: 3000,
            // withTime: $('#withTime').is(':checked'),
            //isOnly: !$('#isOnly').is(':checked')
            });
        }
    @endif

    @if($message = Session::get('error'))
        title='Error';
        message='{{$message}}';
        myalert(title,message)
        function myalert(title,msg){
            $.alert(msg, {
            title: title,
            closeTime: 3000,
            // withTime: $('#withTime').is(':checked'),
            //isOnly: !$('#isOnly').is(':checked')
            });
        }
    @endif
</script>

  <script src="https://foliotek.github.io/Croppie/croppie.js"></script>
<script type="text/javascript">
    var $uploadCrop,
    tempFilename,
    rawImg,
    imageId;
     function readFile(input) {
                            if (input.files && input.files[0]) {
                              var reader = new FileReader();
                                reader.onload = function (e) {
                                    $('.upload-demo').addClass('ready');
                                    $('#cropImagePop').modal('show');
                                    rawImg = e.target.result;
                                    $('#profile_base64').val(rawImg);
                                }
                                reader.readAsDataURL(input.files[0]);
                            }
                            else {
                                swal("Sorry - you're browser doesn't support the FileReader API");
                            }
                        }


   function readURL1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#show-image-1').attr('src', e.target.result);

            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#upload-photo-1").change(function(){
        //console.log("Iage");
        readURL1(this);
    });


                        $uploadCrop = $('#upload-demo').croppie({
                            viewport: {
                                width: 150,
                                height: 200,
                            },
                            enforceBoundary: false,
                            enableExif: true
                        });
                        $('#cropImagePop').on('shown.bs.modal', function(){
                            // alert('Shown pop');
                            $uploadCrop.croppie('bind', {
                                url: rawImg
                            }).then(function(){
                                console.log('jQuery bind complete');
                            });
                        });

                        $('#upload-photo-1').on('change', function () { imageId = $(this).data('id'); tempFilename = $(this).val();

                         $('#cancelCropBtn').data('id', imageId); readFile(this); });
                        $('#cropImageBtn').on('click', function (ev) {
                            $uploadCrop.croppie('result', {
                                type: 'base64',
                                format: 'jpeg',
                                size: {width: 150, height: 200}
                            }).then(function (resp) {
                                $('#item-img-output').attr('src', resp);
                                $('#cropImagePop').modal('hide');
                            });
                        });
</script>
@endpush
