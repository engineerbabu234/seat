@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<div class="title">
						<h2>Quesionaire List</h2>
					</div>
				</div>

				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="#" class="add-asset btn btn-info"  data-toggle="modal" data-target="#add_quesionaire"><i class="fas fa-plus"></i></a>
					</div>
				</div>

			</div>
		</div><!--END header-->
		<!--my tenders-->
		<div class="custom-data-table">
			<div class="data-table">

		<div class="custom-table-height">
			<div class="table-responsive">
				<table class="table table-striped text-center" id="laravel_datatable">
					<thead>
						<tr>
							<th class="text-left"><span class="iconWrap iconSize_32" title="ID."  data-trigger="hover" data-content="Id" data-placement="left"><img src="{{asset('admin_assets')}}/images/id.png" class="icon bl-icon" width="25" ></span> </th>
						    <th><span class="iconWrap iconSize_32" title="Title" data-content="Title" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/title.png" class="icon bl-icon" width="30" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Description" data-content="Description" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Update Date" data-content="Update Date"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/order_date.png" class="icon bl-icon" width="25" ></span>  </th>
							<th><span class="iconWrap iconSize_32" title="Expire User After"  data-trigger="hover" data-content="Expire User After" data-placement="left"><img src="{{asset('admin_assets')}}/images/expire_after.png" class="icon bl-icon" width="25" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="Restrict Seat" data-content="Restrict Seat"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/restrict-seat.png" class="icon bl-icon" width="25" ></span> </th>
							<th><span class="iconWrap iconSize_32" title="No Quetions"  data-content="No Quetions"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/no-label.png" class="icon bl-icon" width="25" ></span> </th>
							<th nowrap><span class="iconWrap iconSize_32" title="Action" data-content="Action" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="25" ></span> </th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
		</div><!--END my tenders-->
	</div>
</div>




<!-- The Modal -->
<div class="modal" id="add_quesionaire">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Quesionaire</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" id="add-quesionaire-form" action="#">
				@csrf
				<div class="add-quesionaire">
					<div class="row">

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title" ><span class="iconWrap iconSize_32" title="Title"  data-trigger="hover" data-content="Title" data-placement="left"><img src="{{asset('admin_assets')}}/images/title.png" class="icon bl-icon" width="40" ></span> <span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Title" id="title" name="title" required>
								 <span class="error" id="title_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Expire After" data-content="Expire After" data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/expire_after.png" class="icon bl-icon" width="30" ></span> <span class="text-danger">*</span></h6>
								<div class="row">
									<div class="col-sm-6">
											<select class="form-control" name="expired_value" id="expired_value">
												 <optgroup id="option_day" label="days">
							        		 		 @for ($i = 1; $i <= 31; $i++)
								            		<option value="{{ 'Day_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>
							            	      <optgroup id="option_month" label="Month">
							        		 		   @for ($i = 1; $i <= 12; $i++)
								            		<option value="{{ 'Month_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>
							            	      <optgroup id="option_week" label="Week">
							        		 		 @for ($i = 1; $i <= 52; $i++)
								            		<option value="{{ 'Week_'.$i }}">{{ $i }}</option>
								            		@endfor
							            	     </optgroup>
							    		</select>


									</div>
									<div class="col-sm-6">
										<select class="form-control" name="expired_option" id="expired_option">
									        <option value="day">Day(s)</option>
									        <option value="month">Month(s)</option>
									        <option value="week">Week(s)</option>
									    </select>
									</div>
								</div>


								 <span class="error" id="expired_option_error"></span>
							</div>
						</div>

						<div class="col-sm-4">
						<div class="form-group">
							<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Restric Seat" data-content="Restric Seat"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/restrict-seat.png" class="icon bl-icon" width="22" ></span> </h6>
							 <select class="form-control" name="restriction" id="restriction">
							 	<option value="0" selected>No</option>
							 	<option value="1">Yes</option>
							 </select>
							 <span class="error" id="restriction_error"></span>
						</div>
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title"><span class="iconWrap iconSize_32" title="Description" data-content="Description"  data-trigger="hover" data-placement="left"><img src="{{asset('admin_assets')}}/images/description.png" class="icon bl-icon" width="25" ></span> <span class="text-danger">*</span></h6>
								 <textarea class="form-control" name="description" id="description" rows="6"></textarea>
								 <span class="error" id="description_error"></span>
							</div>
						</div>


						<div class="col-sm-12">
						<div class="add-product-btn text-center">
							<button class="btn btn-info add_quesionaire_data" type="submit"> Add Quesionaire</button>
						</div>
					 </div>
					 </div>

				</div>
			</form>
      </div>
      <!-- Modal footer -->


    </div>
  </div>
</div>


<div class="modal" id="edit_quesionaire">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"  id="edit_quesionaire_info">

    </div>
  </div>
</div>


@endsection
@push('css')
<style type="text/css">
	.close_new{
	    background-color: transparent;
	    border: 0;
	    font-size: 26px;
	}
</style>
@endpush
@push('js')
    <script src="{{asset('admin_assets/')}}/js/jquery-ui.js"></script>

 <script type="text/javascript" src="{{URL::asset('admin_assets/pages')}}/quesionaire/index.js"></script>
 <script type="text/javascript">
	$('#option_day').show();
	$('#option_week').hide();
	$('#option_month').hide();

$(document).on('change',"#expired_option",function(){

	 $(this).find('option').removeAttr("selected");
	 $("#expired_value").find('option').removeAttr("selected");

	var options = $(this).find("option:selected");

	if(options.val() == 'month'){
		$('#eoption_day').css('display', 'none');
		$('#eoption_week').css('display', 'none');
		$('#eoption_month').css('display', 'block');
		$('#expired_option option[value=1]').attr('selected','selected');

		$('#option_day').hide();
		$('#option_week').hide();
		$('#option_month').show();
	}

	if(options.val() == 'day'){
		$('#eoption_day').css('display', 'block');
		$('#eoption_week').css('display', 'none');
		$('#eoption_month').css('display', 'none');
		$('#expired_option option[value=1]').attr('selected','selected');

		$('#option_day').show();
		$('#option_week').hide();
		$('#option_month').hide();
	}

	if(options.val() == 'week'){

		$('#eoption_day').css('display', 'none');
		$('#eoption_week').css('display', 'block');
		$('#eoption_month').css('display', 'none');
		$('#expired_option option[value=1]').attr('selected','selected');

		$('#option_day').hide();
		$('#option_week').show();
		$('#option_month').hide();
	}

});


 </script>
@endpush
