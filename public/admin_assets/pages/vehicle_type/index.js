$(document).ready(function(){
	console.log("Product");
	console.log(base_url);
	$('#laravel_datatable').DataTable({
		processing: true,
		serverSide: true,
		"ordering": false,
		ajax: base_url+'/admin/vehicle_type',
		//dom: 'lBfrtip',
		//"scrollX": false,
		/*buttons:[
		'copy', 'csv', 'pdf', 'print'
		],*/
		//"lengthMenu": [ 10, 50, 100, 500],
		columns: [
			{ data: 'number_key', name: 'number_key' },
			{ data: 'name', name: 'name' },
			{ data: 'image', name: 'image',
				render: function (data, type, column, meta) {
						return '<div class="img">'+
						'<img src="'+column.image+'">'+
						'</div>' ;
					}
			},
			{ data: 'vehicle_type_id', name: 'vehicle_type_id' , 
				render: function (data, type, column, meta) {
				return '<div class="btns">'+
					/*'<a href="'+base_url+'/admin/vehicle_type/show/'+column.vehicle_type_id+'"><button class="eye"><i class="fa fa-eye"></i></button></a>'+*/
					'<a href="'+base_url+'/admin/vehicle_type/edit/'+column.vehicle_type_id+'"><button class="pen"><i class="fa fa-pencil"></i></button></a>'+
					/*'<button class="close product-delete" id="'+column.product_id+'"><i class="fa fa-close"></i></button>'+*/
					'</div>' ;
				}
			}
		]
	});
});