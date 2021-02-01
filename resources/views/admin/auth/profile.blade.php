@extends('admin.layouts.app')
@section('content')
	<div class="main-body">
		<div class="inner-body">
            <div class="header">
                <div class="title">
                    <h2>Profile</h2>
                </div>
            </div>
		<div class="profile-page">
				<div class="row">
					<div class="col-sm-12">@if($message = Session::get('success'))
						<div class="alert alert-success">
							<ul>
								<li>{{ $message }}</li>
							</ul>
						</div>
					@endif

					@if($message = Session::get('error'))
						<div class="alert alert-danger">
							<ul>
								<li>{{ $message }}</li>
							</ul>
						</div>
					@endif
                </div>
				</div>
				<div class="row"  >



						<div class="col-md-3">

							<div class="profile-details">
								<div class="header">
									<h2>Account details</h2>
								</div>
								<div class="body">
									<form method="POST" action=" ">

										 @csrf

										<div class="form-group">
											<label>First Name:</label>
											<input type="text" class="form-control" placeholder="First Name" name="first_name" value="{{$data['user']->first_name}}">
										</div>
										<div class="form-group">
											<label>Last Name:</label>
											<input type="text" class="form-control" placeholder="Last Name" name="last_name" value="{{$data['user']->last_name}}">
										</div>
										<div class="form-group">
											<label>Email:</label>
											<input type="email" class="form-control" placeholder="Email" name="email" value="{{$data['user']->email}}"  readonly="">
										</div>

                                        <div class="form-group">
                                            <label>Timezone</label>
                                            <select class="form-control" name="timezone" id="timezone" required>
                                            @foreach($data['timezone_list'] as $key => $value)
                                            <option  @if($key == $data['user']->timezone) {{'selected'}}
                                             @elseif($key == 'Europe/Dublin') {{'selected'}}   @endif  value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                            </select>
                                        </div>

										<div class="form-group">
											<label>Phone Number:</label>
											<input type="text" class="form-control" placeholder="Phone Number" name="phone_number" value="{{$data['user']->phone_number}}">
										</div>

										<div class="form-group">
											<button type="submit" class="same-btn1 profile_info">Save</button>
										</div>
									</form>
								</div>
							</div>
						</div><!--end-->

						<!--Password details-->
						<div class="col-md-3">
							<div class="profile-details">
								<div class="header">
									<h2>Change Password</h2>
								</div>
								<div class="body">

									<form method="POST" action="{{ route('admin_update_password') }}">
										@csrf
										{{ method_field('PUT') }}
										<div class="form-group">
											<label>Current Password: <span class="text-danger">*</span></label>
											<input type="password" class="form-control" placeholder="Current Password" name="old_password">
											@error('old_password')
												<span class="invalid-feedback" role="alert">
													<strong style="color: red">{{ $message }}</strong>
												</span>
											@enderror
										</div>
										<div class="form-group">
											<label>New Password: <span class="text-danger">*</span></label>
											<input type="password" class="form-control" placeholder="Current Password" name="new_password">
											@error('new_password')
												<span class="invalid-feedback" role="alert">
													<strong style="color: red">{{ $message }}</strong>
												</span>
											@enderror
										</div>
										<div class="form-group">
											<label>Confirm Password: <span class="text-danger">*</span></label>
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

						<!--Admin Profile Picture-->
						<div class="col-md-3">
							<div class="profile-details">
								<div class="header">
									<h2>Admin Profile Picture</h2>
								</div>
								<div class="body">
									<form method="POST" id="profile_images" action="{{ route('update_profile_image') }}" enctype="multipart/form-data" >
										@csrf

										 <div class="profile-picture">
                                            <label>

                                                <img src="{{$data['user']->profile_image}}" id="show-image-1" width="200" >
                                            </label>
                                             <input type="hidden"  name="profile_base64" id="profile_base64">
                                              @error('profile_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong style="color: red">{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>

									</form>
								</div>
							</div>
						</div><!--end-->

						<div class="col-md-3">
							<div class="profile-details">
								<div class="header">
									<h2>Customise page</h2>
								</div>
								<div class="body">
									<form method="POST" action="{{ route('update_logo_image') }}" enctype="multipart/form-data" >
										@csrf
	                                    {{ method_field('PUT') }}
										<div class="profile-picture " id="logo-profile">
                                            <label>
                                                <img src="{{$data['user']->logo_image}}" id="show-logo-image-1" width="200" >
                                            </label>
                                             <input type="hidden"  name="logo_base64" id="logo_base64">
                                              @error('profile_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong style="color: red">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label style="color:{{$data['user']->color}} ">Color <span class="text-danger">*</span></label>
                                            <input type="text" value="{{$data['user']->color}}" class="form-control colorpicker" placeholder="Set Navbar color" name="color" id="color">
                                        </div>

                                        <div class="form-group">
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox"  @if($data['user']->api_access == 1) {{'checked'}} @endif name="api_access" id="api_access">
                                              <label class="form-check-label" for="api_access">
                                                User API Access
                                              </label>
                                            </div>
                                        </div>

                                        @if($logo_access == 1)
                                        <div class="form-group text-center">
                                            <button type="submit" class="same-btn1">Update</button>
                                        </div>
                                        @else
                                        <div class="form-group text-center">
                                            <span  data-trigger="hover" id="plan_pop"  title="Upgrade Plan" disabled class="btn btn-secondary">Update</span>
                                        </div>
                                        @endif
									</form>
								</div>
							</div>
						</div><!--end-->

            				</div>
            			</div>
            		</div>
            	</div>

					<div class="modal" id="cropImagePop"  >
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


                        <div class="modal" id="cropLogoImagePop"  >
                          <div class="modal-dialog profile-page ">
                            <div class="modal-content  profile-details">
                            <div class="modal-header"><h4 class="modal-title">Edit Logo</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel"> </h4>
                            </div>
                            <div class="modal-body">
                            <div id="upload-logo-demo" class="center-block"></div>
                            </div>
                             <div class="modal-footer">
                                 <input type="file" style="display: none" name="logo_image" id="upload-logo-1">
                                     <button type="button" id="upload_logo_image" class="same-btn1">Change Photo</button>
                                    <button type="button" id="croplogoImageBtn" class="same-btn1">Save</button>
                                  </div>
                            </div>
                          </div>
                        </div>

                         <div id="plan_pophover">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Please upgrade your plan in the subscription portal to be able to customize the site</p>
                            </div>
                        </div>
                    </div>

@endsection
@push('css')

<link rel="stylesheet" href="{{asset('admin_assets')}}/css/croppie.css">
<link rel="stylesheet" href="{{asset('admin_assets')}}/css/bootstrap-colorpicker.min.css">
@endpush
@push('js')
<script type="text/javascript">

$(document).ready(function() {

    $('#plan_pophover').hide();
    $('#plan_pop').popover({
    content:  $('#plan_pophover').html(),
    placement: 'left',
    html: true
    });

});

$(document).on("click", "#cropImageBtn", function(e) {
    e.preventDefault();
    $('.error').removeClass('text-danger');
    $('.error').text('');
    var photo = $("form#profile_images").find("#profile_base64").find("img").attr("src");


     console.log()
    $.ajax({
        url: base_url + '/admin/update_profile_image/',
        type: 'post',
        dataType: 'json',
        data:  {'profile_base64': $('#profile_base64').val()},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(response) {
            success_alert(response.message);
        },
        success: function(response) {
            if (response.success) {
                $("form#profile_images")[0].reset();
                $('#user-profile').attr('src', response.image);
                success_alert(response.message);
            }
        },
    });
});

$(document).on("click", ".profile_info", function(e) {
    e.preventDefault();
    $('.error').removeClass('text-danger');
    $('.error').text('');
      var data = jQuery(this).parents('form:first').serialize();

    $.ajax({
        url: base_url + '/admin/update',
        type: 'post',
        dataType: 'json',
        data:  data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        error: function(response) {
            success_alert(response.message);
        },
        success: function(response) {
            if (response.success) {
                 if(response.time){
                    $('#header_time').text(response.time);
                 }
                success_alert(response.message);
            }
        },
    });
});

$('#upload_image').click(function(){ $('#upload-photo-1').trigger('click'); });

    @if($message = Session::get('success'))
        title='Success';
        message='{{$message}}';
        myalert(title,message)
        function myalert(title,msg){
            //  alert(msg, {
            // title: title,
            // closeTime: 3000,
            // // withTime: $('#withTime').is(':checked'),
            // //isOnly: !$('#isOnly').is(':checked')
            // });
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

    $('#upload_logo_image').click(function(){ $('#upload-logo-1').trigger('click'); });

</script>
</script>

 <script type="text/javascript" src="{{asset('admin_assets')}}/js/croppie.js"></script>
 <script type="text/javascript" src="{{asset('admin_assets')}}/js/bootstrap-colorpicker.min.js"></script>
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
                                    // $('.upload-demo').addClass('ready');
                                    // $('#cropImagePop').modal('show');
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
                                size: {width: 400, height: 400}
                            }).then(function (resp) {
                                $('#show-image-1').attr('src',resp);
                                $('#item-img-output').attr('src', resp);
                                 $('#profile_base64').val(resp);
                                $('#cropImagePop').modal('hide');
                            });
                        });



    var logoimage = "{{$data['user']->logo_image}}";
    var $logouploadCrop,
    tempFilenamelogo,
    rawImglogo,
    imageId;
     function readFilelogo(input) {

                            if (input.files && input.files[0]) {
                              var reader = new FileReader();
                                reader.onload = function (e) {
                                    $('.upload-logo-demo').addClass('ready');
                                    $('#cropLogoImagePop').modal('show');
                                    rawImglogo = e.target.result;
                                    //$('#show-logo-image-1').attr('src', rawImglogo);
                                     $('#upload-logo-1').attr('src', rawImglogo);
                                      $logouploadCrop.croppie('bind', {
                                        url:rawImglogo,
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
                $('#show-logo-image-1').attr('src', e.target.result);

            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#upload-logo-1").change(function(){
        //  readURL1(this);
        readFilelogo(this);
        $('.upload-logo-demo').addClass('ready');
        $('#cropLogoImagePop').modal('show');

    });

    $("#logo-profile").on('click',function(){
        $logouploadCrop.croppie('bind', {
            url:logoimage,
        });
        $('.upload-logo-demo').addClass('ready');
        $('#cropLogoImagePop').modal('show');
        $('#cropImagePop').modal('hide');
        //  readURL1(this);
        //readFile(this);
    });


                        $logouploadCrop = $('#upload-logo-demo').croppie({
                            viewport: { width: 200, height:200, type: 'circle' },
                            enforceBoundary: false,
                            enableExif: true
                        });

                        $('#cropLogoImagePop').on('shown.bs.modal', function(){
                            // alert('Shown pop');
                            $('#show-logo-image-1').attr('src', rawImglogo);
                            var image1 =  $('#show-logo-image-1').attr('src');
                            $logouploadCrop.croppie('bind', {

                                url: image1
                            }).then(function(){
                                console.log('jQuery bind complete');
                            });
                        });

                        $('#upload-logo-1').on('change', function () { imageId = $(this).data('id'); tempFilenamelogo = $(this).val();

                         $('#cancelCropBtn').data('id', imageId); readFilelogo(this); });
                        $('#croplogoImageBtn').on('click', function (ev) {
                            $logouploadCrop.croppie('result', {
                                type: 'base64',
                                format: 'png',
                                size: {width: 400, height: 400}
                            }).then(function (resp) {
                                $('#show-logo-image-1').attr('src',resp);
                                $('#item-img-output').attr('src', resp);
                                 $('#logo_base64').val(resp);
                                $('#cropLogoImagePop').modal('hide');
                            });
                        });

                         $('.colorpicker').colorpicker();

                          $('[data-toggle="tooltip"]').tooltip();

                          $('#color').change(function(event) {
                               $('.left-block').css('background',  $('#color').val());
                          });
</script>
@endpush
