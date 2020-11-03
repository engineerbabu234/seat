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
                                    <h2>Update Account details</h2>
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
                                    <h2>Update Password</h2>
                                </div>
                                <div class="body">
                                <form method="POST" action="{{ route('update_password') }}">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="form-group">
                                        <label>Current Password  <span class="text-danger">*</span>  </label>
                                        <input type="password" class="form-control" placeholder="Current Password" name="old_password">
                                        @error('old_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong style="color: red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>New Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" placeholder="Current Password" name="new_password">
                                        @error('new_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong style="color: red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password">
                                        @error('confirm_password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong style="color: red">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="same-btn1">Update</button>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div><!--end-->

                        <div class="col-md-4">
                            <div class="profile-details">
                                <div class="header">
                                    <h2>Update Profile Picture</h2>
                                </div>
                                <div class="body">
                                    <form method="POST" action="{{ url('update_profile_image') }}" enctype="multipart/form-data"  >
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="profile-picture">
                                            <label>
                                                <!-- <input type="file" name="profile_image" id="upload-photo-1"> -->
                                                <img src="{{$data['user']->profile_image}}" id="show-image-1" width="200" >
                                            </label>
                                             <input type="hidden"  name="profile_base64" id="profile_base64">
                                              @error('profile_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong style="color: red">{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>
                                        <div class="form-group text-center">
                                            <button type="submit" class="same-btn1">Update Profile</button>
                                        </div>
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
                          <div class="modal-dialog profile-page ">
                            <div class="modal-content  profile-details">
                            <div class="modal-header"><h4 class="modal-title">Edit Photo</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel"> </h4>
                            </div>
                            <div class="modal-body">
                            <div id="upload-demo" class="center-block"></div>
                            </div>
                             <div class="modal-footer">
                                 <input type="file" style="display: none" name="profile_image" id="upload-photo-1">
                                     <button type="button" id="upload_image" class="same-btn1">Change Photo</button>
                                    <button type="button" id="cropImageBtn" class="same-btn1">Save</button>
                                  </div>
                            </div>
                          </div>
                        </div>



@endsection

@push('css')
<link rel="stylesheet" href="{{asset('front_end')}}/css/croppie.css">
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
    width: 470px;
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
$('#upload_image').click(function(){ $('#upload-photo-1').trigger('click'); });

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

 <script type="text/javascript" src="{{asset('front_end')}}/js/croppie.js"></script>
<script type="text/javascript">
    var image = "{{$data['user']->profile_image}}";
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
                                    //$('#show-image-1').attr('src', rawImg);
                                     $('#upload-photo-1').attr('src', rawImg);
                                      $uploadCrop.croppie('bind', {
                                        url:rawImg,
                                    });
                                }
                                reader.readAsDataURL(input.files[0]);

                            }
                            else {
                                console.log('cancel');
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
        //  readURL1(this);
        readFile(this);
        $('.upload-demo').addClass('ready');
        $('#cropImagePop').modal('show');

    });

    $(".profile-picture").on('click',function(){
        $uploadCrop.croppie('bind', {
            url:image,
        });
        $('.upload-demo').addClass('ready');
        $('#cropImagePop').modal('show');

        //  readURL1(this);
        //readFile(this);
    });


                        $uploadCrop = $('#upload-demo').croppie({
                            viewport: { width: 200, height:200, type: 'circle' },
                            enforceBoundary: false,
                            enableExif: true
                        });

                        $('#cropImagePop').on('shown.bs.modal', function(){
                            // alert('Shown pop');
                            $('#show-image-1').attr('src', rawImg);
                            var image1 =  $('#show-image-1').attr('src');
                            $uploadCrop.croppie('bind', {

                                url: image1
                            }).then(function(){
                                console.log('jQuery bind complete');
                            });
                        });

                        $('#upload-photo-1').on('change', function () { imageId = $(this).data('id'); tempFilename = $(this).val();

                         $('#cancelCropBtn').data('id', imageId); readFile(this); });
                        $('#cropImageBtn').on('click', function (ev) {
                            $uploadCrop.croppie('result', {
                                type: 'base64',
                                format: 'png',
                                size: {width: 200, height: 200}
                            }).then(function (resp) {
                                $('#show-image-1').attr('src',resp);
                                $('#item-img-output').attr('src', resp);
                                 $('#profile_base64').val(resp);
                                $('#cropImagePop').modal('hide');
                            });
                        });
</script>
@endpush
