
<!DOCTYPE html>
<html>
	<head>
		<title>Seat Reservation</title>
		<meta charset="UTF-8">
		<meta name="keywords" content="">
		<meta name="author" content="Nxsol team, info@nxsol.com">
		<meta name="url" content="http://nxsol.com">
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!--css-->
		<link rel="stylesheet" type="text/css" href="{{asset('front_end')}}/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="{{asset('admin_assets')}}/css/modal.css">
		<link rel="stylesheet" type="text/css" href="{{asset('front_end')}}/css/style.css">

		<link rel="stylesheet" type="text/css" href="{{asset('front_end')}}/css/responsive.css">
		<!--font awesome 4-->
		<link rel="stylesheet" type="text/css" href="{{asset('front_end')}}/fonts/fontawesome/css/all.min.css">

	<link rel="stylesheet" href="{{asset('front_end')}}/css/jBox.css">
		<style type="text/css">
			.form-box-request .card{
		height:100%;
		}
		.form-box-request .card-body{
		height:100%;
		}
		.card-hedaing{
		display:flex;
		align-items:center;
		justify-content: space-between;
		margin-bottom:20px;
		}
		.card-hedaing h5{
		padding-right:10px;
		}
		.card-hedaing span img{
		width:32px
		}
		</style>
	</head>
	<body onload="loaderfun()">
		<div id="loader-wrapper">
			<div id="loader">
				<div class="svg-wrapper">
					<img src="{{asset('front_end')}}/images/loader1.gif">
				</div>
			</div>
		</div>
