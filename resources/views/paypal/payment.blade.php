<!DOCTYPE html>
<html>
<head>
   <title>Check Out</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" type="text/css" href="{{asset('admin_assets')}}/css/bootstrap.min.css">
   <script type="text/javascript" src="{{asset('admin_assets')}}/js/jquery-3.3.1.js"></script>
     <script src="https://js.braintreegateway.com/web/dropin/1.22.0/js/dropin.min.js"></script>
   <style type="text/css">
    .braintree-large-button.braintree-toggle {
      display: none;
     }
    </style>
</head>
<body>
  <div class="container">
  	<div class="row">
  		<div class="col-md-12">
  			<p>Pay Amount : $ {{$data['amount']}}</p>
  		</div>
  		<div class="col-md-12">
  			<form role="form" action="{{url('api/user/card_payment')}}" method="POST">
  				<input type="hidden" name="nonce" value="">
          <input type="hidden" name="user_id" value="{{$data['user_id']}}">
          <input type="hidden" name="for_payment" value="{{$data['for_payment']}}">
          <input type="hidden" name="trip_id" value="{{$data['trip_id']}}">
          <input type="hidden" name="amount" value="{{$data['amount']}}">
  				@csrf()
     			<div id="dropin-container"></div>
     				<input type="button" id="pay" value="Pay" class="btn btn-block btn-success">
  			</form>
  		</div>
  	</div>
  </div>
</body>
</html>
<script type="text/javascript">
	var button = document.querySelector('#pay');
				    braintree.dropin.create({
				      authorization: "{{ $data['token'] }}",

				      container: '#dropin-container'
				    }, function (createErr, instance) {
				      button.addEventListener('click', function () {
				        instance.requestPaymentMethod(function (err, payload) {
				        	console.log(payload,' == err == ',err);
				        	if(typeof payload != 'undefined'){
				              nonce = payload.nonce;
				              $('input[name="nonce"]').val(nonce);
				              $('form').submit();
				        	}
				        });
				      });
				    });
</script>
