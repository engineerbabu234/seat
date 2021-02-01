@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<div class="title">
						<h2>Settings</h2>
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
										<th><span class="iconWrap iconSize_32" title="No."  data-trigger="hover" data-content="No" data-placement="left"><img src="{{asset('admin_assets')}}/images/no-label.png" class="icon bl-icon" width="30" ></span></th>
										<th> <span class="iconWrap iconSize_32" title="Api Access"  data-trigger="hover" data-content="Api Access" data-placement="left"><img src="{{asset('admin_assets')}}/images/api-access.png" class="icon bl-icon" width="30" ></span> </th>
										<th><span class="iconWrap iconSize_32" title="Action"  data-trigger="hover" data-content="Action" data-placement="left"><img src="{{asset('admin_assets')}}/images/action.png" class="icon bl-icon" width="30" ></span> </th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
		</div>

	</div>
</div>




<div class="modal" id="edit_settings">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Settings</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="edit_settings_info">

      </div>
    </div>
  </div>
</div>

@endsection
@push('js')
 <script type="text/javascript">
 	$(document).ready(function(){

	$('#laravel_datatable').DataTable({
		processing: true,
		serverSide: true,
		"ordering": false,
		destroy: true,
		ajax: base_url+'/admin/settings',

		columns: [
			{ data: 'number_key', name: 'number_key' },
			{ data: 'api_access', name: 'api_access' },
			{ data: 'settings_id', name: 'settings_id' ,
				render: function (data, type, column, meta) {
					return '<a  title="Edit"  data-id="'+column.id+'" href="#" class="button btn-wh edit_settings_request"><img src="'+base_url+'/admin_assets/images/edit.png"  class="white-img"></a>';
				}
			}
		]
	});

$(document).on("click", ".edit_settings", function(e) {
	e.preventDefault();

	var data = jQuery(this).parents('form:first').serialize();
	var id = $(this).data('id');
	$.ajax({
		url: base_url + '/admin/settings/update/'+id,
		type: 'post',
		dataType: 'json',
		data: data,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		error: function(response) {
			if (response.status == 400) {
				$.each(response.responseJSON.errors, function(k, v) {
					$('#edit_' + k + '_error').text(v);
					$('#edit_' + k + '_error').addClass('text-danger');
				});
			}
		},
		success: function(response) {
			if (response.success) {
				$("form#edit-setting-form")[0].reset();
				success_alert(response.message);
				//swal("Success!", response.message, "success");
				var redrawtable = jQuery('#laravel_datatable').dataTable();
				redrawtable.fnDraw();
				$('.error').removeClass('text-danger');
				$('#edit_settings').modal('hide');
			}
		},
	});
});


$(document).on("click", ".edit_settings_request", function(e) {
	e.preventDefault();
	var id = $(this).data('id');

	var aurls = base_url + "/admin/settings/edit_settings/" + id;
	jQuery.ajax({
		url: aurls,
		type: 'get',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(response) {

			if (response.success) {
				$('#edit_settings_info').html(response.html);

				$('#edit_settings').modal('show');

			}
		},
	});
});
});
 </script>
@endpush
