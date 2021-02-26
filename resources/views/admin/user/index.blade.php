@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="title">
						<h2>Users</h2>
					</div>
				</div>

			</div>
		</div><!--END header-->

		<div class="custom-data-table">
			<div class="data-table">
				<div class="heading-search">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<h2>Total User: {{$data['user_count']}}</h2>
						</div>
					</div>
				</div>
				<div class="custom-table-height">
					<div class="table-responsive">
						<table class="table table-striped text-center" id="laravel_datatable">
							<thead>
								<tr>
									<th><span class="iconWrap iconSize_32" title="Serial No." data-content="Serial no Based on user list" data-trigger="hover" ><img src="{{asset('admin_assets')}}/images/no-label.png" class="icon bl-icon" width="30" ></span></th>
									<th><span class="iconWrap iconSize_32" title="User Name"  data-content="User Name" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/user-name.png" class="icon bl-icon" width="30" ></span> </th>
									<th><span class="iconWrap iconSize_32"  data-content="User Profile Types" title="Job Profile"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/job-profile.png" class="icon bl-icon" width="30" ></span>  </th>
									<th><span class="iconWrap iconSize_32"  title="Role"  id="usertype_info"   data-trigger="hover"> <img src="{{asset('admin_assets')}}/images/role.png" class="icon bl-icon" width="30" > </span></th>
									<th><span class="iconWrap iconSize_32"  title="Email"  data-content="User Email Address" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/email.png" class="icon bl-icon" width="30" ></span></th>
									<th><span class="iconWrap iconSize_32"  title="Profile Image"   data-content="User Profile Image" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/progile-image.png" class="icon bl-icon" width="30" ></span> </th>
									<th><span class="iconWrap iconSize_32"  title="Update Date" data-content="User Profile Update Date"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/reservation_request.png" class="icon bl-icon" width="30" ></span> </th>
									<th><span class="iconWrap iconSize_32"  title="Action" data-content="User Data Actions"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="30" ></span>  </th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="usertype_popup">
    <div class="row">
        <div class="col-sm-12">
             <img title="User" src="{{asset('admin_assets')}}/images/user.png"   class="bl-icon" height="20" width="auto"> <span> Cleaner: </span><br>
             <p>The cleaner can clean specified seats depending on which team(s) they below to.</p><br>
             <img title="cleaner" src="{{asset('admin_assets')}}/images/cleaner.png" class="bl-icon" height="20" width="auto"><span>User: </span><br>
             <p>The User can PreBook seats, Checkin to a seat and Checkout from a seat. The seat that they are allowed to access depends on the Teams(s) they are associated to.</p>
        </div>
    </div>
</div>


<div class="modal" id="edit_users">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit User Type</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="edit_users_info">

      </div>
    </div>
  </div>
</div>


@endsection
