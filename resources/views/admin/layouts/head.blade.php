<head>
	<title>Workspace Management System</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!--css-->
	<link rel="stylesheet" type="text/css" href="{{asset('front_end')}}/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="{{asset('admin_assets')}}/css/style.css">
	<link rel="stylesheet" type="text/css" href="{{asset('admin_assets')}}/css/modal.css">
	<link rel="stylesheet" type="text/css" href="{{asset('admin_assets')}}/css/responsive.css">
	<!--font awesome 4-->
	<link rel="stylesheet" type="text/css" href="{{asset('admin_assets')}}/fonts/fontawesome/css/all.min.css">

	<link  href="{{asset('admin_assets')}}/css/jquery.dataTables.min.css" rel="stylesheet">
	<link  href="{{asset('admin_assets')}}/css/buttons.dataTables.min.css" rel="stylesheet">
	<script src="{{asset('admin_assets')}}/js/jquery-3.3.1.js"></script>
	<script src="{{asset('admin_assets')}}/js/custom.js"></script>
	<script src="{{asset('admin_assets')}}/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('admin_assets')}}/js/dataTables.buttons.min.js"></script>
	<script src="{{asset('admin_assets')}}/js/buttons.flash.min.js"></script>
	<script src="{{asset('admin_assets')}}/js/jszip.min.js"></script>
	<script src="{{asset('admin_assets')}}/js/pdfmake.min.js"></script>
	<script src="{{asset('admin_assets')}}/js/vfs_fonts.js"></script>
	<script src="{{asset('admin_assets')}}/js/buttons.html5.min.js"></script>
	<script src="{{asset('admin_assets')}}/js/buttons.print.min.js"></script>

	{{-- <script src="{{asset('admin_assets')}}/js/sweetalert.min.js"></script> --}}
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<script src="{{asset('front_end')}}/js/alert.js" type="text/javascript"></script>



</head>
