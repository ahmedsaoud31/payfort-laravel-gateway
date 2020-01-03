<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Payment Gataway</title>
        <!-- Styles -->
        <link href="{{ asset('libs/bootstrap-3.3.7/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('libs/Font-Awesome/css/font-awesome.min.css') }}" rel="stylesheet">
		<link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
		<link href="{{ asset('libs/loading-bars/bars.css') }}" rel="stylesheet">
		<style>
			#cardIcon, #ccnameIcon, #emailIcon{
				font-size: 22px;
			}
			.site-color0{ color: #555555; }
			.site-color1{ color: #00249f; }
			.site-color2{ color: #cc0001; }
			.site-color3{ color: #4cae4c; }
			.site-color4{}

			#navbar{
				margin-top: 5px;
				color: #EEE;
			}
			footer{
				text-align: center;
				color: #AAA;
			}
			#print{
				font-size: 40px;
				text-align: center;
			}
			#print .fa{
				cursor: pointer;
				color: #999;
			}
			#print .fa:hover{
				color: #448AFF;
			}
		</style>
    </head>
<body>
	<header id="header">
		<nav class="navbar navbar-inverse">
		  <div class="container">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="{{ url('/') }}{{ ($payment)?'?payment_id='.$payment->payment_id:'' }}">{{ ($payment && $payment->user->company_name)?$payment->user->company_name.' Payments Gataway':'Payments Gataway' }}</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<div class="navbar-form navbar-right">
				<span class="link">{{ ($payment && $payment->user->phone)?'Phone: +2 '.$payment->user->phone:'' }}</span>
				<span class="link">{{ ($payment && $payment->user->email)?'E-mail: '.$payment->user->email:'' }}</span>
				</div>
			</div><!--/.navbar-collapse -->
		  </div>
		</nav>
	</header>
	<div class="flex-center position-ref full-height">
		<!--@if (Route::has('login'))
			<div class="top-right links">
				@if (Auth::check())
					<a href="{{ url('/home') }}">Home</a>
				@else
					<a href="{{ url('/login') }}">Login</a>
					<a href="{{ url('/register') }}">Register</a>
				@endif
			</div>
		@endif
		-->
		<div class="content">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="flash-message">
					@if(isset($alerts))
					@foreach (['danger', 'warning', 'success', 'info'] as $msg)
					  @if(array_key_exists('alert-'.$msg, $alerts))
						@foreach($alerts['alert-'.$msg] as $message)
						<p class="alert alert-{{ $msg }}">{{ $message }}<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
						@endforeach
					  @endif
					@endforeach
					@endif
					</div> <!-- end .flash-message -->
					<!-- CREDIT CARD FORM STARTS HERE -->
					<div class="panel panel-default credit-card-box" id="panelPrint">
						<div class="panel-heading" >
							<div class="row" >
								<div class="form-group text-center">
									<span><img src="https://reflectionstravel.net/images/icons/visa.png" width="100" /></span>
									<span><img src="https://reflectionstravel.net/images/icons/master.png" width="100" /></span>
									<span><img src="https://reflectionstravel.net/images/icons/ssl.png" width="60" /></span>
								</div>
							</div>                    
						</div>
						<div class="panel-body">
							@if($payment == false || $payment->status == 'pending')
							<form role="form" id="gatawayForm" method="POST" action="#">
								<input type="hidden" name="card_holder_name" value="">
								<input type="hidden" name="card_number" value="">
								<input type="hidden" name="card_security_code" value="">
								<input type="hidden" name="expiry_date" value="">
							</form>
							<form id="paymentsForm" novalidate autocomplete="on" method="POST">
								@if($payment)
									<input type="hidden" name="payment_id" value="{{ $payment->payment_id }}">
								@endif
								<div class="form-group">
									<label for="cc-name" class="control-label">Card Holder Name</label>
									<div class="input-group mb-2 mr-sm-2 mb-sm-0">
										<input id="cc-name" name="ccname" type="text" class="input-lg form-control" autocomplete="cc-name" placeholder="eg. Adam Yousif" required />
										<div class="input-group-addon"><i class="fa fa-address-card" id="ccnameIcon"></i></div>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="control-label">Your E-mail</label>
									<div class="input-group mb-2 mr-sm-2 mb-sm-0">
										<input name="email" type="text" class="input-lg form-control" autocomplete="email" placeholder="your email" required />
										<div class="input-group-addon"><i class="glyphicon glyphicon-envelope" id="emailIcon"></i></div>
									</div>
								</div>
								<div class="form-group">
									<label for="cc-number" class="control-label">Card Number</label>
									<div class="input-group mb-2 mr-sm-2 mb-sm-0">
										<input id="cc-number" name="cardnumber" type="tel" class="input-lg form-control cc-number" autocomplete="cc-number" placeholder="eg. 1234 1234 1234 1234" required />
										<div class="input-group-addon"><i class="fa fa-credit-card-alt" id="cardIcon"></i></div>
									</div>
								</div>
								<div class="row">
								  <div class="col-md-6 form-group">
									<label for="cc-exp" class="control-label">Expiry Date</label>
									<div class="input-group mb-2 mr-sm-2 mb-sm-0">
										<input id="cc-exp" name="cc-exp" type="tel" class="input-lg form-control cc-exp" autocomplete="cc-exp" placeholder="eg. 08 / 2017" required />
										<div class="input-group-addon"><span class="input-group-addon" id="expIcon">M/Y</span></div>
									</div>
								  </div>
								  <div class="col-md-6 form-group">
									<label for="cc-cvc" class="control-label">CV Code</label>
									<div class="input-group mb-2 mr-sm-2 mb-sm-0">
										<input id="cc-cvc" name="cvc" type="tel" class="input-lg form-control cc-cvc" autocomplete="off" placeholder="eg. 123" required />
										<div class="input-group-addon"><span class="input-group-addon" id="cvcIcon">CVC</div>
									</div>
								  </div>
								</div>
								<div class="alert alert-danger" style="display: none;">
									
								</div>
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Payment Details</h3>
									</div>
									<div class="panel-body">
										@if($payment)
										<div class="payment-details">
											@if($payment->name)
											<div>Payment title: <strong>{{ $payment->name }}</strong></div>
											@endif
											<div>Payment amount: <strong>{{ number_format($payment->amount+$payment->fee, 2) }} {{ $payment->currency }}</strong></div>
											@if($payment->user->company_name)
											<div>Payment to: <strong>{{ $payment->user->company_name }}</strong></div>
											@endif
											@if($payment->user->email)
											<div>Contact E-mail: <strong>{{ $payment->user->email }}</strong></div>
											@endif
											@if($payment->user->phone)
											<div>Contact Phone: <strong>{{ $payment->user->phone }}</strong></div>
											@endif
										</div>
										@endif
									</div>
								</div>
								
								
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Amount Details</h3>
									</div>
									
									<div class="panel-body">
										@if($payment)
											@if($payment->fee > 0)
											<div>Amount <span class="pull-right"><strong>{{ ($payment)?number_format($payment->amount, 2).' '.$payment->currency:'0.00' }}</strong></div>
											<div>Transaction Fee <span class="pull-right"><strong>{{ ($payment)?number_format($payment->fee, 2).' '.$payment->currency:'0.00' }}</strong></div>
											@endif
											<div>Total Amount <span class="pull-right"><strong>{{ ($payment)?number_format($payment->amount+$payment->fee, 2).' '.$payment->currency:'0.00' }}</strong></div>
										@endif
									</div>
									
								</div>
								<br/>
								<button type="submit" class="btn btn-success btn-lg btn-block" role="button">Pay</button>
								<h2 class="validation"></h2>
							</form>
							@else
							<div id="printArea">
								<div class="payment-details">
									<h2 class="text-center" style="color: #4CBB17;">Payment Success</h2>
									<div>Name: <strong>{{ $payment->ccname }}</strong></div>
									<div>Reference: <strong>#{{ $payment->id }}</strong></div>
									<div>Date: <strong>{{ date('d M Y', strtotime($payment->created_at)) }}</strong></div>
									@if($payment->name)
									<div>Title: <strong>{{ $payment->name }}</strong></div>
									@endif
									<div>Amount: <strong>{{ number_format($payment->amount+$payment->fee, 2) }}</strong></div>
									<div>Currency: <strong>{{ $payment->currency }}</strong></div>
									@if($payment->user->company_name)
									<div>Payment to: <strong>{{ $payment->user->company_name }}</strong></div>
									@endif
									@if($payment->user->email)
									<div>Contact E-mail: <strong>{{ $payment->user->email }}</strong></div>
									@endif
									@if($payment->user->phone)
									<div>Contact Phone: <strong>{{ $payment->user->phone }}</strong></div>
									@endif
								</div>
							@endif
						</div>
					</div>
				</div>
				@if($payment && $payment->status == 'success')
				<div id="print"><i class="fa fa-print" aria-hidden="true"></i></div>
				@endif
			</div>
		</div>
	</div>
	<hr>
	<footer>
		<div>{{ ($payment && $payment->user->company_name)?'© 2017 Copyright to '.$payment->user->company_name.' Payments Gataway':'© 2017 Copyright' }} All rights reserved.</div>
		<div>
			@if($payment)
				<span>{{ ($payment->user->phone)?'Phone: +2 '.$payment->user->phone:'' }}</span>
				<span>{{ ($payment->user->email)?'E-mail: '.$payment->user->email:'' }}</span>
			@else
				<!--<span>Phone: +20 109 222 1160</span>
				<span>E-mail: payments@reflectionstravel.net</span>-->
			@endif
		</div>
	</footer>
	<script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
	<script type="text/javascript" src="{{ asset('libs/bootstrap-3.3.7/js/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery-payment.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/payment-form.js') }}?{{ time() }}"></script>
	<script type="text/javascript" src="{{ asset('libs/sweetalert2/sweetalert2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('libs/loading-bars/bars.js') }}"></script>
	<script>
	$(function(){
		$('#print').click(function(){
			PrintElem('panelPrint');
		});
		$('#paymentsForm').find('.alert-danger').hide();
		
		$('#paymentsForm [type=submit]').click(function(){
			
			if(!($('[name=payment_id]').length > 0)){
				swal(
				  'Oops...',
				  'Payment ID not found, ask the merchant to resend the payment link to you again.',
				  'error'
				)
				return false;
			}
			
			if(!validatePaymentForm()) return false;
			var ccname = $('#paymentsForm [name=ccname]').val();
			var cardnumber = $('#paymentsForm [name=cardnumber]').val().replace(/[^0-9]/g, "");
			var cvc = $('#paymentsForm [name=cvc]').val();
			var ccexp = getCcExp($('#paymentsForm [name=cc-exp]').val());
			
			var sendData = {"_token": "{{csrf_token()}}",
							"payment_id": $('[name=payment_id]').val(),
							"email": $('#paymentsForm [name=email]').val(),
							};
			$.ajax({
				url: "{{url('/ajaxGetFormData')}}",
				type: "POST",
				data: sendData,
				beforeSend: function ( xhr ) {
					googleBar.start();
				}
				}).done(function ( data ) {
					if(typeof data.error !== 'undefined'){
						swal(
						  'Oops...',
						  data.error,
						  'error'
						)
					}else{
						$.each(data.inputs, function(k, v){
							$('<input>').attr({
								type: 'hidden',
								id: k,
								name: k,
								value: v
							}).appendTo('#gatawayForm'); 
						});
						$('#gatawayForm [name=card_holder_name]').val(ccname);
						$('#gatawayForm [name=card_number]').val(cardnumber);
						$('#gatawayForm [name=card_security_code]').val(cvc);
						$('#gatawayForm [name=expiry_date]').val(ccexp);
						$('#gatawayForm').attr('action', data.host).submit();
					}
					googleBar.stop();
			});
			return false;
		});
		
		
		$('.glyphicon-trash').click(function(){
			var thisElem = $(this);
			var sendData = {"id": $(this).attr('data-id'),
							"_token": "{{csrf_token()}}",
							"_method": "POST"
							};
			$.ajax({
				url: "{{url('/ajax/tour/cart/delete')}}",
				type: "POST",
				data: sendData,
				beforeSend: function ( xhr ) {
					googleBar.start();
				}
				}).done(function ( data ) {
					if(typeof data.error !== 'undefined'){
						swal(
						  'Oops...',
						  data.error,
						  'error'
						)
					}else{
						thisElem.parent().parent().parent().parent().fadeOut(1000).remove();
						total_amount = total_amount-thisElem.attr('data-amount');
						$('.checkout .amount strong').text('$'+$.number(total_amount, 2));
						$('.userinfo .badge-notify').text(parseInt($('.userinfo .badge-notify').text())-1);
					}
					googleBar.stop();
			});
			return false;
		});
	});
	function PrintElem(divName) {
		var printContents = document.getElementById(divName).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
	</script>
</body>
</html>
