@extends('admin.layouts.app')
@section('content')
<div class="main-body">
	<div class="inner-body">
		<!--header-->
		<div class="header">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
					<div class="title">
						<h2>Team List</h2>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="btns">
						<a href="javascript:void(0)" class="add-asset btn btn-info"  id="add-team-btn"><i class="fas fa-plus"></i></a>
					</div>
				</div>
			</div>
		</div><!--END header-->

		<!--my tenders-->
		<div class="custom-data-table">
				<div class="data-table">

					<div class="custom-table-height">
						<div class="table-responsive">
							<table class="table table-striped text-center" id="datatable">
								<thead>
									<tr>
										<th>Serial No.</th>
										<th>Title</th>
 								    	<th>Number Of users</th>
										<th>Created At</th>
										<th>Action</th>
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
<div class="modal" id="add-team-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Team</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" action="{{route('admin.team.store')}}" id="add-team-form">
				@csrf
				<div class="add-office">
						<div class="row">
							<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title">Title <span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Title" name="title" value="{{old('title')}}">
							</div>
							</div>
							<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title">Description <span class="text-danger">*</span></h6>
								<textarea rows="4" class="form-control" placeholder="Write here..." name="description">{{old('description')}}</textarea>
							</div>
							</div>
							<div class="col-sm-12">
							<div class="add-product-btn text-center">
								<button class="btn btn-info add_building" type="submit"> Add</button>
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

<!-- The Modal -->
<div class="modal" id="edit-team-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Team</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<form method="POST" action="{{route('admin.team.update')}}" id="edit-team-form">
				@csrf
				@method('put')
				<input type="hidden" name="id" value=""/>
				<div class="add-office">
						<div class="row">
							<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title">Title <span class="text-danger">*</span></h6>
								<input type="text" class="form-control" placeholder="Title" name="title" value="{{old('title')}}">
							</div>
							</div>
							<div class="col-sm-12">
							<div class="form-group">
								<h6 class="sub-title">Description <span class="text-danger">*</span></h6>
								<textarea rows="4" class="form-control" placeholder="Write here..." name="description">{{old('description')}}</textarea>
							</div>
							</div>
							<div class="col-sm-12">
							<div class="add-product-btn text-center">
								<button class="btn btn-info add_building" type="submit"> Update</button>
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

@endsection
@push('js')
 <script>
    $(document).ready(function(){

		var oTable = $('#datatable').DataTable({
				processing: true,
				serverSide: true,
				"ordering": false,
				destroy: true,
				ajax: base_url+'/admin/teams',

				columns: [
					{ data: 'id', name: 'id' },
					{ data: 'title', name: 'title' },
					{ data: 'users', name: 'users',    
	   			   	     render: function (data, type, column, meta) {
                              return '10';
				         }
					 },
					{ data: 'created_at', name: 'created_at'},
					{ data: 'action', name: 'action' , 
			   	       render: function (data, type, column, meta) {
						  return '<a href="'+base_url+'/admin/team/access/rules/'+column.id+'" class="button btn-info" data-url="'+base_url+'/admin/edit/team/'+column.id+'">Access Rule</a>&nbsp;<button class="button btn-edit btn-info" data-url="'+base_url+'/admin/edit/team/'+column.id+'">Edit</button> ' +
						   ' <button class="button reject btn-delete"  data-url="'+base_url+'/admin/delete/team/'+column.id+'">Delete</button>';
				         }
		        	}
				]
			});

			 $('#add-team-btn').on('click',function(e){
				 console.log('Hello');
				$('#add-team-modal').modal('show');
			 });

	$('#add-team-form').on('submit',function(e){
		e.preventDefault();
		var click = $(this);
		let form  = $(this);
		let data = form.serialize();
		$.ajax({
		"headers":{
		'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
		},
		'type':'POST',
		'url' : form.attr('action'),
		'data' : data,
		beforeSend: function() {

		},
		'success' : function(response){
		click.find('span').remove();
		click.find('input').val('');
		if(response.status == 'success'){
		swal("Success!",response.message, "success");
            oTable.ajax.reload();
			$('#add-team-modal').modal('hide');
		}
		if(response.status == 'failed'){
		swal("Failed!",response.message, "error");
		}
		if(response.status == 'error'){
		$.each(response.errors, function (key, val) {
		click.find('[name='+key+']').after('<span style="color:red">'+val+'</span>');
		});
		}
		},
		'error' : function(error){
		console.log(error);
		},
		complete: function() {

		},
		});
	});

	$('#edit-team-form').on('submit',function(e){
		e.preventDefault();
		var click = $(this);
		let form  = $(this);
		let data = form.serialize();
		$.ajax({
		"headers":{
		'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
		},
		'type':'PUT',
		'url' : form.attr('action'),
		'data' : data,
		beforeSend: function() {

		},
		'success' : function(response){
		click.find('span').remove();
		if(response.status == 'success'){
		swal("Success!",response.message, "success");
            oTable.ajax.reload();
			$('#edit-team-modal').modal('hide');
		}
		if(response.status == 'failed'){
		swal("Failed!",response.message, "error");
		}
		if(response.status == 'error'){
		$.each(response.errors, function (key, val) {
		click.find('[name='+key+']').after('<span style="color:red">'+val+'</span>');
		});
		}
		},
		'error' : function(error){
		console.log(error);
		},
		complete: function() {

		},
		});
	});

		$('body').on('click','.btn-delete',function(e){
	 	  var url = $(this).attr('data-url');
  	 	 swal({
		  title: "Are you sure you want to delete?",
		  text: "Once deleted, you will not be able to recover this team and all ossociated data!",
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

	   $('body').on('click','.btn-edit',function(e){
		   let url = $(this).attr('data-url');
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
	                     $('#edit-team-modal form input[name="id"]').val(response['data']['id']);
  						 $('#edit-team-modal form input[name="title"]').val(response['data']['title']);
						 $('#edit-team-modal form input[name="description"]').val(response['data']['description']);
                         $('#edit-team-modal').modal('show');
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
