$(document).ready(function(){
	console.log("Trips");
	console.log(base_url);
	$('#laravel_datatable').DataTable({
		processing: true,
		serverSide: true,
		"ordering": false,
		ajax: base_url+'/admin/trip',
		//dom: 'lBfrtip',
		//"scrollX": false,
		/*buttons:[
		'copy', 'csv', 'pdf', 'print'
		],*/
		//"lengthMenu": [ 10, 50, 100, 500],
		columns: [
			{ data: 'number_key', name: 'number_key' },
			{ data: 'user_name', name: 'user_name' },
			{ data: 'driver_name', name: 'driver_name' },
			{ data: 'from', name: 'from' },
			{ data: 'to', name: 'to',
				/*render: function (data, type, column, meta) {
						return '<p>' ;
					}*/
			},
			//{ data: 'payment_method', name: 'payment_method' },
			//{ data: 'price', name: 'price' },
			//{ data: 'delivery_charge', name: 'delivery_charge' },
			{ data: 'charge', name: 'charge' },
			{ data: 'status', name: 'status' },
			/*{ data: 'status', name: 'status',
				render: function (data, type, column, meta) {
						//console.log('order_id=>'+column.order_id);
						html = '';
						html += '<div class="select-class">';
						html +='<select name="order-status-change" class="order-status-change"  order_id="'+column.order_id+'">';
						html += '<option value="0"';
						if(data == 0){
							html += 'selected';
						}
						html +=  '>Pending</option>';
						html += '<option value="1"';
						if(data == 1){
							html += 'selected';
						}
						html +='>Completed</option>';
						html += '<option value="2"';
						if(data == 2){
							html += 'selected';
						}
						html +='>Rejected</option>';
						html += '</select>';
						html += '<div>';
						//data = 0;
						return html;
					}
			},*/

			{ data: 'trip_id', name: 'trip_id' , 
				render: function (data, type, column, meta) {
				return '<div class="btns">'+
					'<a href="'+base_url+'/admin/trip/show/'+column.trip_id+'"><button class="eye"><i class="fa fa-eye"></i></button></a>'+
					//'<a href="'+base_url+'/admin/order/edit/'+column.order_id+'"><button class="pen"><i class="fa fa-pencil"></i></button></a>'+
					'</div>' ;
				}
			}
		]
	});

	$('body').on('change','.trip-status-change', function() {
		var order_id = $(this).attr("order_id");
		status      =this.value;
		console.log('status===='+status)
		console.log('order_id===='+order_id)
		//return false;

		//status=$(this).closest('tr').find('.active-status-change').val();

		if(status==0){
			var success_status='Order Status Change';
		}else{
			var success_status='Order Status Change';
		}
		path='admin/trip/order_status_change/';

		$.ajax({
			"headers":{
				'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
			},
			'type':'PUT',
			'url' :  base_url + '/' +path,
			'data' : {  order_id : order_id, status:status },
			'beforeSend': function() {

			},
			'success' : function(response){
			if(response.status == 'success'){
				swal(success_status ,response.message, 'success')
					/*if(status==1){
						$(this).closest('tr').find('.active-status-change').prop('checked', false);
					}else{
						$(this).closest('tr').find('.active-status-change').prop('checked', true);
					}*/
				}
			},
			'error' :  function(errors){
				console.log(errors);
			},
			'complete': function() {

			}
		});
	});
});