@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="title">
						<h2>Office List</h2>
					</div>
				</div>
				<!-- <div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="add_building.html" class="add-tender">Add Office</a>
					</div>
				</div> -->
			</div>
		</div><!--END header-->
		<!--my tenders-->
		<div class="custom-data-table">
			<div class="data-table">
		<div class="heading-search">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<h2>Total Office: {{count($data['offices'])}}</h2>
				</div>

			</div>
		</div>
		<div class="custom-table-height">
			<div class="table-responsive">
				<table class="table table-striped" id="office_datatable">
					<thead>
						<tr>
							<th>Office ID.</th>
						    <th>Building</th>
							<th>Office No.</th>
							<th>Office Name</th>
							<th>Total Office assets</th>
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
		</div><!--END my tenders-->
	</div>
</div>
@endsection
@push('css')
@endpush
@push('js')
  <script type="text/javascript" src="{{asset('public')}}/js/sweetalert.min.js"></script>
 <script type="text/javascript">
 		var asset_datatable =$('#office_datatable').DataTable({
			processing: true,
			serverSide: true,
			"ordering": false,
			destroy: true,
			ajax: base_url+'/admin/office',

			columns: [
				{ data: 'office_id', name: 'office_id' },
				{ data: 'building_name', name: 'building_name' },
				{ data: 'office_number', name: 'office_number' },
				{ data: 'office_name', name: 'office_name' },
				{ data: 'seats', name: 'seats' },
				{ data: 'created_at', name: 'created_at' },
				{ data: 'office_id', name: 'office_id' ,
					render: function (data, type, column, meta) {
						return '<a  href="'+base_url+'/admin/office/edit_office/'+column.office_id+'" data-id="'+column.office_id+'"  class="button accept edit_office_request">Edit</a>'+'<a href="'+base_url+'/admin/office/office_details/'+column.office_id+'" data-id="'+column.office_id+'" class="button accept  ">Details</a>'+
	 					'<button class="button reject btn-delete" data-url="'+base_url+'/admin/office/asset/delete/'+column.office_id+'">Delete</button>';
					}
				}
			]
		});

 	$(function(e){



          // Get Offices
  	    var getOffices = function(){
					$.ajax(
					{
						"headers":{
						'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
					},
						'type':'get',
						'url' : "{{url('admin/office/index')}}",
					beforeSend: function() {

					},
					'success' : function(response){
              	        $('.custom-data-table').html(response);
					},
  					'error' : function(error){
					},
					complete: function() {
					},
					});
  	    }

  	  //  getOffices();


  	    	 // Departmetn remove confirmation modal
  	 $('body').on('click','.btn-delete',function(e){
	 	  var url = $(this).attr('data-url');
  	 	 swal({
		  title: "Are you sure?",
		  text: "Once deleted, you will not be able to recover this office data!",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		 })
		 .then((willDelete) => {
			if(!willDelete){
				return false;
			}
				$.ajax({
					"headers":{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
				},
					'type':'get',
					'url' : url,
				beforeSend: function() {
				},
				'success' : function(response){
					if(response.status == 'success'){
						swal("Success!",response.message, "success");
					    getOffices();
					}
					if(response.status == 'failed'){
						swal("Failed!",response.message, "error");
					}
				},
				'error' : function(error){
				},
				complete: function() {
				},
				});
		 });
  	 });

 	})
 </script>
@endpush
