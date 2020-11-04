@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="title">
						<h2>Office Assets</h2>
					</div>
					<div class="btns pull-right">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_asset"><i class="fas fa-plus"></i></a>
					</div>
				</div>

			</div>
		</div><!--END header-->
		<!--my tenders-->
		<div class="custom-data-table">
			<div class="data-table">

		<div class="custom-table-height">
			<div class="table-responsive">
				<table class="table table-striped" id="asset_datatable">
					<thead>
						<tr>
							<th>Assets ID.</th>
						    <th>Building</th>
							<th>Office Name</th>
							<th>Assets Name</th>
							<th>Date/Time </th>
							<th nowrap>Action</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
		</div><!--END my asset-->
	</div>
</div>






<!-- The Modal -->
<div class="modal" id="add_asset">
  <div class="modal-dialog ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Office Asset</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form action="#" enctype="multipart/form-data" method="post" id="add-office-asset-form">
					@csrf

					<div class="form-group">
						<h6 class="title">Building</h6>
						<select class="form-control bindOffice" name="building_id" id="building_id" required>
							@if($buildings->isEmpty())
								<option value="">Record Not Found</option>
							@else
								@foreach($buildings as $key => $value)
								    @if($key == 0)
								     <option value="">-- Select building--</option>
									@endif
									<option value="{{$value->building_id}}">{{$value->building_name}}</option>
								@endforeach
							@endif
						</select>
						 <span class="error" id="building_id_error"></span>
					</div>


						<div class="form-group">
							  <h6 class="sub-title">Office</h6>
							  <select class="form-control OfficeData" name="office_id" id="bindoffices"><option value="">-- Select Office -- </option></select>
							  <span class="error" id="office_id_error"></span>
						</div>

						<div class="form-group">
							<h6 class="sub-title">Assets Title</h6>
							<input type="text" class="form-control" placeholder="Assets Title" name="title" required>
							 <span class="error" id="title_error"></span>
						</div>

						<div class="form-group">
							<h6 class="sub-title">Description</h6>
							<textarea rows="4" class="form-control" placeholder="Write here..." name="description"></textarea>
							 <span class="error" id="description_error"></span>
						</div>

						<div class="form-group">
							<h6 class="sub-title">Preview Image</h6>
							<input type="file" required id="preview_image" name="preview_image" class="form-control dropify-event" data-default-file="" /><br>
                             <span class="error" id="preview_image_error"></span>

						</div>

					<div class="add-product-btn text-right">
						<button class="add-office-btn btn btn-info"> Add Office Asset</button>
					</div>

		    </form>
      </div>
      <!-- Modal footer -->


    </div>
  </div>
</div>


<div class="modal" id="edit_modal">
  <div class="modal-dialog ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Office Assets</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="edit_assets">

      </div>

      <!-- Modal footer -->


    </div>
  </div>
</div>

@endsection
@push('css')
<link  href="{{asset('admin_assets')}}/css/dropify.min.css" rel="stylesheet">
@endpush
@push('js')
<script type="text/javascript" src="{{asset('admin_assets')}}/js/dropify.min.js"></script>
<script type="text/javascript" src="{{URL::asset('admin_assets/pages')}}/office_assets/index.js"></script>
<script type="text/javascript">

	$(function() {

        var drEvent = $('.dropify-event').dropify();
    });
</script>
@endpush
