@php
namespace App\Helpers;
$product_place_holder_image=ImageHelper::getProductPlaceholderImage();
use Illuminate\Support\Facades\Session;
@endphp
@extends('admin.layouts.app')
@section('content')
	<div class="main-body">
		<div class="inner-body">

			<!--header-->
			<div class="header">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="title">
							<!-- <h2>My Tenders</h2> -->
							<p class="navigation">
								<a href="{{route('dashboard')}}">Dashboard</a>
								<a href="{{route('admin.team.index')}}">Team</a>
   								<a href="javascript:void(0)">Access Rule</a>
							</p>
						</div>
						@if($message = Session::get('success'))
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
			</div><!--END header-->

			<!--my tenders-->
			<form method="POST" action="{{ route('admin.team.block') }}">
				@csrf
                <input type="hidden" name="id" value="{{$id}}" />
				<div class="add-office">

                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <select name="building" class="form-control" required>
                             @foreach($buildings as $key => $value)
                                @if($key == '0')
                                <option value="">select building</option>
                                @endif
                                <option value="{{ $value->building_id }}">{{ $value->building_name }}</option>
                             @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <select name="office" class="form-control">
                             <option value="">first select building</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <select name="seat" class="form-control">
                             <option value="">first select office</option>
                          </select>
                        </div>
                      </div>
                        <div class="col-md-3">
                            <div class="add-product-btn">
                                <button type="submit"> Block</button>
                            </div>
                        </div>
                    </div>
				</div><!--END my tenders-->
			</form>

		</div>
				<!--my tenders-->
		<div class="custom-data-table">
				<div class="data-table">

					<div class="custom-table-height">
						<div class="table-responsive">
							<table class="table table-striped text-center" id="datatable">
								<thead>
									<tr>
										<th>Serial No.</th>
										<th>Building</th>
 								    	<th>Office</th>
										<th>Seat</th>
										<th>Created at</th>
										<th>Delete</th>
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
@endsection
@push('js')
  <script>
 
         var id = $('input[name="id"]').val();

		 console.log(id);

          var oTable = $('#datatable').DataTable({
				processing: true,
				serverSide: true,
				"ordering": false,
				destroy: true,
				ajax: base_url+'/admin/team/access/rules/' + id,

				columns: [
					{ data: 'id', name: 'id' },
					{ data: 'building_name', name: 'building_name' },
					{ data: 'office_name', name: 'office_name'},
  			    	{ data: 'number', name: 'number'},
					{ data: 'created_at', name: 'created_at'},
					{ data: 'action', name: 'action' , 
			   	       render: function (data, type, column, meta) {
						   return  '<button class="button reject btn-delete"  data-url="'+base_url+'/admin/team/block/delete/'+column.id+'">Delete</button>';
				         }
		        	}
				]
		   	});

        $('body').on('change','select[name="building"]',function(e)
         {
            if($(this).val()){
		   let url = base_url + '/ajax/offices/' + $(this).val();
		   	 $.ajax({
					"headers":{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
			  },
					'type':'GET',
					'url' : url,
			  beforeSend: function() {
			  },
			 'success' : function(response){
					if(response.status == 'success'){
                        var html = '';
                            response.data.map(function(office,index){
                                if(index == 0){
                                    html += '<option value="">Select office</option>';
                                }
                                    html += '<option value="'+office.office_id+'">'+office.office_name+'</option>';
                            });
                          $('select[name="office"]').html(html);
					}
                    if(response.status == 'failed'){
                          $('select[name="office"]').html('<option value="">Office not available</option>');
                    }
			 },
			 'error' : function(error){
			  },
			 complete: function() {
			 },
			 });
            }else{
               $('select[name="office"]').html('<option value="">first select building</option>');
            }
	   });
       $('body').on('change','select[name="office"]',function(e)
         {
            if($(this).val()){
		   let url = base_url + '/ajax/seats/' + $(this).val();
		   	 $.ajax({
					"headers":{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
			  },
					'type':'GET',
					'url' : url,
			  beforeSend: function() {
			  },
			 'success' : function(response){
					if(response.status == 'success'){
                        var html = '';
                            response.data.map(function(seat,index){
                                if(index == 0){
                                    html += '<option value="">Select seat</option>';
                                }
                                    html += '<option value="'+seat.id+'">'+seat.number+'</option>';
                            });
                          $('select[name="seat"]').html(html);
					}
                    if(response.status == 'failed'){
                          $('select[name="seat"]').html('<option value="">Seat not available</option>');
                    }
			 },
			 'error' : function(error){
			  },
			 complete: function() {
			 },
			 });
            }else{
               $('select[name="office"]').html('<option value="">first select building</option>');
            }
	   });

	   $('body').on('click','.btn-delete',function(e){
	 	  var url = $(this).attr('data-url');
  	 	 swal({
		  title: "Are you sure you want to delete?",
		  text: "Once deleted, you will not be able to recover",
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
					'type':'DELETE',
					'url' : url,
				beforeSend: function() {
				},
				'success' : function(response){
					if(response.status == 'success'){
						swal("Success!",response.message, "success");
						 oTable.ajax.reload();
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

  </script>
@endpush
