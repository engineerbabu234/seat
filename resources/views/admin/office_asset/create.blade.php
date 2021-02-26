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
								<a href="{{url('admin/office/add_office')}}">Add Office</a>
							</p>
						</div>
					</div>
				</div>
			</div><!--END header-->

			<form action="{{url('admin/office/store')}}" method="post" id="add-office-form">
					@csrf
				<div class="add-office">
					<div class="form-group">
						<h2 class="title">Link office with building</h2>
						<select class="form-control2" name="office_building" required>
							@if($data['buildings']->isEmpty())
								<option value="">Record Not Found</option>
							@else
								@foreach($data['buildings'] as $key => $value)
								    @if($key == 0)
								     <option value="">-- Select building--</option>
									@endif
									<option value="{{$value->building_id}}" @if(old('building')==$value->building_id) {{'selected'}} @endif>{{$value->building_name}}</option>
								@endforeach
							@endif
						</select>
					</div>


					<h2 class="title">Office Details</h2>

					<!--single-entry-->
					<div class="single-entry">
						<div class="form-group">
							<h4 class="sub-title">Office Name</h4>
							<input type="text" class="form-control2" placeholder="Office Name" name="office_name" required>
						</div>
						<div class="form-group">
							<h4 class="sub-title">Office Number</h4>
							<input type="text" class="form-control2" placeholder="Office Number" name="office_number" required>
						</div>

						<div class="form-group">
							<h4 class="sub-title">Description</h4>
							<textarea rows="4" class="form-control4" placeholder="Write here..." name="office_description"></textarea>
						</div>

						<!--add-seat-table-->
						<div class="add-seat-table">
							<div class="custom-data-table">
								<div class="data-table">
									<div class="heading-search">
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12">
												<h2>Add Seat</h2>
											</div>
										</div>
									</div>
									<div class="custom-table-height">
										<div class="table-responsive">
											<table class="table table-striped">
												<thead>
													<tr>
														<th>Seat No.</th>
														<th>Description</th>
														<th>Booking Mode</th>
														<th>Seat Type</th>
														<th>Show user details</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<button class="add-seat-btn add-more-btn"><i class="fas fa-plus"></i> Add Seat</button>
						</div><!--END add-seat-table-->

						<div class="form-group">
							<h4 class="sub-title">Add Office Seat Image</h4>

                           <div class="image-list">
                           </div>
					        <button class="add-image-btn"><i class="fas fa-plus"></i> Add Image</button>
						</div>

						<!-- <button class="delete-btn"> <i class="fas fa-trash-alt"></i> Remove</button> -->
					</div><!--END single-entry-->

					<div class="add-product-btn">
						<button class="add-office-btn"> Add Office</button>
					</div>

				</div>
		    </form>
		</div>
	</div>
@endsection

@push('js')
<script type="text/javascript" src="{{asset('public')}}/js/sweetalert.min.js"></script>
<script type="text/javascript">
	  $('.add-more-btn').on('click', function(e){
        e.preventDefault();
			var seats = '<?php $seats = env('TOTAL_MAX_SEATS');
echo $seats - 1?>';
			if($('body tbody tr').length > seats){
			seatcount=$('body tbody tr').length;
			swal("Failed!",'You can add only '+seatcount+' seats', "error");
			return false;
			}
        daynamicSeat();
      });

      $('body').on('click', '.remove-more-btn' , function(e){
             e.preventDefault();
               $(this).closest('.row').remove();
      });

      $('body').on('click','.remove-seat',function(e){
      	    e.preventDefault();
      	    var count   = $("table tbody tr").length;
		    var seat_no = $(this).attr('data-id') ? $(this).attr('data-id') : '';
		         if(count < 2){
		         	swal("Atleat one seat is required");
		         }else{
					swal({
						title: "Are you sure?",
						text: "Are you sure you want to delete seat "+seat_no+"?",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
			         	    $(this).parents('tr').remove();
						}
					});
		         }
      });

      $('body').on('change','input[name="seat_no[]"]',function(e){
      	 $(this).parents('tr').find('.remove-seat').attr('data-id',$(this).val());
      });

	   function daynamicSeat(){
	   	 html = '';
	   	 html += '<tr>';
		 html +=   '<td>';
		 html +=     '<input type="number" placeholder="seat no." name="seat_no[]" required>';
		 html +=   '</td>';
		 html +=  '<td>';
		 html +=     '<textarea rows="1" placeholder="Write here..." name="description[]"></textarea>';
		 html +=  '</td>';
		 html +=  '<td>';
		 html +=     '<select name="booking_mode[]" required>';
 		 html +=        '<option value="2">Auto Accept</option>';
		 html +=        '<option value="1">Manual</option>';
		 html +=     '</select>';
		 html +=  '</td>';
		 html +=  '<td>';
		 html +=     '<select name="seat_type[]" required>';
 		 html +=        '<option value="1">Unblocked</option>';
		 html +=        '<option value="2">Blocked</option>';
		 html +=     '</select>';
		 html +=  '</td>';
		 html +=  '<td>';
		 html +=     '<select name="is_show_user_details[]" required>';
 		 html +=        '<option value="0">Hide</option>';
		 html +=        '<option value="1">Show</option>';
		 html +=     '</select>';
		 html +=  '</td>';
	     html +=  '<td>';
	     html +=    '<button class="button reject remove-seat">Remove</button>';
		 html +=  '</td>';
		 html += '</tr>';
         $('body tbody').append(html);
	   }
	   daynamicSeat();

	   $('body').on('click','.add-image-btn',function(e){
	   	   e.preventDefault();
	   	   	if($('.image-list .show-chair-dis-main').length > 7){
				swal("Failed!",'You can uploaded only 8 images', "error");
				return false;
			}
		    html  = '';
			html +=	 '<div class="show-chair-dis-main">';
			html +=		'<button type="button" class="chairs-remove-btn">X</button>';
		    html +=			'<div class="show-chair-img">';
		    html +=     '<label>';
			html +=		'<img src="{{asset('uploads/office_image/')}}/default-image.png">';
			html +=     '<input type="file" name="images[]" style="display:block;" accept="image/*" required>';
			html +      '</label>';
			html +=		'</div>';
			html +=		'<div class="chairs-dis">';
			html +=		   '<textarea placeholder="Seat map description" rows="4" name="image_description[]" required></textarea>';
			html +=		'</div>';
			html +=	 '</div>';
			$('.image-list').append(html);
	   });

	      $('body').on('change','input[type="file"]',function(e){
	   	     $(this).parents('label').find('img').attr('src',URL.createObjectURL(event.target.files[0]));
          });

          $('body').on('click','.chairs-remove-btn',function(e){
             swal({
				title: "Are you sure?",
				text: "Are you sure you want to remove this image?",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			 })
			 .then((willDelete) => {
				if (willDelete) {
         	       $(this).parents('.show-chair-dis-main').remove();
				}
		 	 });
          });

	   // Submit add office form
	      // store or update clinic
     $('#add-office-form').on('submit',function(e){
			e.preventDefault();
			if($('.image-list .show-chair-dis-main').length == 0){
				swal("Failed!",'Please at least a image upload', "error");
				return false;
			}
			$('.add-office-btn').prop('disabled', true);
			$('.add-office-btn').text('Please wait your data is uploading');
			//return false;
			//$('.add-office-btn').
			var click = $(this);
			let form  = $(this);
 		    let data  = new FormData(this);
			$.ajax({
				"headers":{
				'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
			},
				'type':'POST',
				'url' : form.attr('action'),
				'data' : data,
				cache : false,
				contentType : false,
				processData : false,
			beforeSend: function() {

			},
			'success' : function(response){
				if(response.status == 'success'){
   			      swal("Success!",response.message, "success");
    				 setTimeout(function(){ window.location.href = "{{url('admin/office')}}" }, 2000);
				}
				if(response.status == 'failed'){
					$('.add-office-btn').prop('disabled', false);
					$('.add-office-btn').text('Add Office');
				  swal("Failed!",response.message, "error");
				}
				if(response.status == 'error'){
					$('.add-office-btn').prop('disabled', false);
					$('.add-office-btn').text('Add Office');
				}
			},
			'error' : function(error){
				$('.add-office-btn').prop('disabled', false);
				$('.add-office-btn').text('Add Office');
				console.log(error);
			},
			complete: function() {

			},
			});
     });

     	 $(function(){
		 	$('body').on('change','input[name="seat_no[]"]',function(e){
		 		    reserveSeats = [];
				    $('input[name="seat_no[]"]').each(function(e){
				    	var seatNo = $(this).val() ? parseInt($(this).val()) : '';
				    	if(seatNo != '' && seatNo != NaN){
					    	if(!reserveSeats.includes(seatNo)){
	                            reserveSeats.push(seatNo);
					    	}else{
	                          swal("You are trying to add a seat that already exists");
	                          $(this).val('');
					    	}
				    	}
				    })
				    console.log(reserveSeats);
		 	});
		 });


</script>
@endpush
