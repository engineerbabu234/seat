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
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="searchbar-wrapper">
						<div class="searchbar">
							<i class="fas fa-search"></i>
							<input type="text" name="">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="custom-table-height">
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Office ID.</th>
						    <th>Building</th>
							<th>Office No.</th>
							<th>Office Name</th>
							<th>Total Seat</th>
							<th>Date/Time </th>
							<th nowrap>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data['offices'] as $key => $office)
						<tr>
								<td>{{$office->office_id}}</td>
							    <td>{{$office->building->building_name ?? ''}}</td>
								<td>{{$office->office_number}}</td>
								<td>{{$office->office_name}}</td>
								<td>{{count($office->seats) ?? '0'}}</td>
								<td>{{date('d/m/Y',strtotime($office->created_at))}}, {{date('H:i A',strtotime($office->created_at))}}</td>
								<td nowrap>
									<a href="{{url('admin/office/edit_office',$office->office_id)}}" class="button accept">Edit</a>
									<a href="{{url('admin/office/office_details',$office->office_id)}}" class="button accept">Details</a>

									<button class="button reject btn-delete" data-url="{{route('admin/office/destroy',$office->office_id)}}">Delete</button>
								</td>
						</tr> <!--end-->
						@endforeach
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
 	$(function(e){
       
          // Get Offices
  	    var getOffices = function(){
					$.ajax(
					{
						"headers":{
						'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
					},
						'type':'get',
						'url' : "{{route('admin/office/index')}}",
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
