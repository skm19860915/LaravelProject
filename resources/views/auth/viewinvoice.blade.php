<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Invoice Details &mdash; {{ env('APP_NAME') }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/css/CustomLoad.css')}}?v=<?php echo rand(); ?>">
<!--   <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css')}}"> -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style type="text/css">
.main-content {
    padding-left: 0;
    padding-top: 0;
    padding-right: 0;
}	
.payment-metothd {
	text-align: center;
}
.payment_method_w {
	display: none;
}
</style>
</head>

<body ng-app="myApp" ng-controller="myCtrl">
	@include('flash-message')
  <?php if(empty($invoice) || ($invoice->amount == $invoice->paid_amount)) { ?>
  	<!-- Fonts -->
  	<link rel="dns-prefetch" href="//fonts.gstatic.com">
  	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  	<!-- Styles -->
  	<style>
  		html, body {
  			background-color: #fff;
  			color: #636b6f;
  			font-family: 'Nunito', sans-serif;
  			font-weight: 100;
  			height: 100vh;
  			margin: 0;
  		}

  		.full-height {
  			height: 100vh;
  		}

  		.flex-center {
  			align-items: center;
  			display: flex;
  			justify-content: center;
  		}

  		.position-ref {
  			position: relative;
  		}

  		.code {
  			border-right: 2px solid;
  			font-size: 26px;
  			padding: 0 15px 0 15px;
  			text-align: center;
  		}

  		.message {
  			font-size: 18px;
  			text-align: center;
  		}
  	</style>
  	<div class="flex-center position-ref full-height">
  		<div class="code">
  			404            
  		</div>
  		<div class="message" style="padding: 10px;">
  			Not Found            
  		</div>
  	</div>
  <?php } else {
  	if(empty($invoice_password) || $invoice_password != $invoice->invoice_pass) { ?>
  	<!-- Fonts -->
  	<link rel="dns-prefetch" href="//fonts.gstatic.com">
  	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  	<!-- Styles -->
  	<style>
  		html, body {
  			background-color: #fff;
  			color: #636b6f;
  			font-family: 'Nunito', sans-serif;
  			font-weight: 100;
  			height: 100vh;
  			margin: 0;
  		}

  		.full-height {
  			height: 100vh;
  		}

  		.flex-center {
  			align-items: center;
  			display: flex;
  			justify-content: center;
  		}

  		.position-ref {
  			position: relative;
  		}

  		.code {
  			border-right: 2px solid;
  			font-size: 26px;
  			padding: 0 15px 0 15px;
  			text-align: center;
  		}

  		.message {
  			font-size: 18px;
  			text-align: center;
  		}
  		.amount-input {
		    width: 220px;
		    float: none;
		    margin: 0 auto;
		}
		.btn.btn-primary {
		    width: 100%;
		    margin-top: 15px;
		    border-radius: 25px;
		    padding: 10px 0;
		}
  	</style>
  	<div class="flex-center position-ref full-height">
  		<div class="enter-amount-box">
  			<form action="">
		  		<div class="heading-amount">Enter Password</div>
		  		<div class="input-amount-box">
		  			<div class="amount-input">
		  				<input type="text" name="invoice_password" placeholder="Enter Password" class="form-control" required />
		  				<?php if(!empty($invoice_password)) { ?>
		  					<p class="text-danger text-center">You have enter a wrong password!</p>
		  				<?php } ?>
		  				<input type="submit" value="Submit" class="btn btn-primary"/>
		  			</div>
		  		</div>
	  		</form>
	  	</div>
  	</div>
  	<?php }
  	else {
  	?>
	  <div id="app">
	    <div class="main-wrapper">
	      <!-- Main Content -->
	      <div class="main-content">
	      	
	        <section data-dashboard="1" class="section dashboard-new-design">
				<div class="section-body container">
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col-md-8 offset-md-2"> 
											<div class="payment-page">
												
												<div class="row">
													<div class="col-md-6">
														<?php 
														$theme_logo = get_user_meta($firm->uid, 'theme_logo');

														if(!empty($theme_logo) && $theme_logo != '[]') { ?>
															<img src="{{asset('storage/app')}}/{{$theme_logo}}" alt="logo" width="200">
														<?php } else { ?>
															<img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="200">
														<?php } ?>
														<div class="row">
															<div class="col-md-12">
																<label>Firm Name : {{$firm->firm_name}} </label>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<label>Phone Number : {{$firm->contact_number}} </label>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<label>Email : {{$firm->email}} </label>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<br><br><br><br>
														<div class="row">
															<div class="col-md-12">
																<label>Client Name : {{$invoice->client_name}}</label>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<label>Invoice Number : {{$invoice->id}}</label>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<label>Discription : {{$invoice->description}}</label>
															</div>
														</div>
														
													</div>
												</div>
												<br>
												<h3>Payment Details</h3>

												<div class="payment-info">
													<div class="clent-info"><span>Total</span>:<span>${{ number_format($invoice->amount, 2) }}</span></div>
													<div class="clent-info"><span>Paid</span>:<span>${{ number_format($invoice->paid_amount, 2) }}</span></div>
													<div class="clent-info"><span>Due</span>:<span>${{ number_format(($invoice->amount-$invoice->paid_amount), 2) }}</span></div>
												</div>

												<div class="due-payment-box">
													Due : &nbsp; ${{ number_format(($invoice->amount-$invoice->paid_amount), 2) }}
												</div>

												<div class="amount-box-auto">
													<form action="{{url('pay_for_invoice')}}" method="post" id="view_payment_form">
														<div class="enter-amount-box">
															<div class="heading-amount">Enter Amount You are Paying</div>
															<div class="input-amount-box">
																<div class="amount-head">Amount</div>
																<div class="amount-input">
																	<input type="number" placeholder="$00.00" class="form-control" max="{{($invoice->amount-$invoice->paid_amount)}}" name="amount" value="<?php if(isset(Session::get('data')['amount'])) { echo Session::get('data')['amount']; }?>" required />
																</div> 
															</div>
														</div>
														<div class="payment-metothd">
															<div class="heading-amount">Payment Method</div>
															<div class="selectgroup">
											                  <label class="selectgroup-item">
											                    <input type="radio" name="payment_method" value="Credit Card" class="selectgroup-input payment_method" <?php if(isset(Session::get('data')['payment_method']) && Session::get('data')['payment_method'] == 'Credit Card') { echo 'checked'; }?>>
											                    <span class="selectgroup-button">Card</span>
											                  </label>
											                  <label class="selectgroup-item">
											                    <input type="radio" name="payment_method" value="E-Check" class="selectgroup-input payment_method" <?php if(isset(Session::get('data')['payment_method']) && Session::get('data')['payment_method'] == 'E-Check') { echo 'checked'; }?>>
											                    <span class="selectgroup-button">E-Check</span>
											                  </label>
											                </div>
											                <div class="invalid-feedback payment_method_err">Please select payment method!</div>
														</div>
														<div class="card-payment-full payment_method_w card_payment" <?php if(isset(Session::get('data')['payment_method']) && Session::get('data')['payment_method'] == 'Credit Card') { echo 'style="display:block;"'; }?>>

															<div class="pay-input-name">
																<div class="row">
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<label>Name on Credit Card</label>
																	</div>
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<input placeholder="Name of Credit Card" name="name_of_credit_card" class="form-control" value="<?php if(isset(Session::get('data')['name_of_credit_card'])) { echo Session::get('data')['name_of_credit_card']; }?>" />                    
																	</div>
																</div>
															</div>

															<div class="pay-input-name">
																<div class="row">
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<label>Credit Card Number</label>
																	</div>
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<input type="text" placeholder="Credit Card Number" size="20" name="card_number" class="form-control" data-stripe="number" maxlength="16" value="<?php if(isset(Session::get('data')['card_number'])) { echo Session::get('data')['card_number']; }?>" />                      
																	</div>
																</div>
															</div>


															<div class="pay-input-name">
																<div class="row">
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<div class="row">
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<label>Cvv</label>
																			</div>
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<input type="text" placeholder="CVC" size="4" data-stripe="cvc" name="cvc" class="form-control" value="<?php if(isset(Session::get('data')['cvc'])) { echo Session::get('data')['cvc']; }?>"/>
																				                      
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<div class="row">
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<label>Expiration Date</label>
																			</div>
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<input type="text" placeholder="mm/yyyy" name="exp_date" data-stripe="exp_date" class="form-control exp_date" value="<?php if(isset(Session::get('data')['exp_date'])) { echo Session::get('data')['exp_date']; }?>"/>                      
																			</div>
																		</div>
																	</div>
																</div>

															</div>

															<div class="pay-input-name">
																<div class="row">
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<div class="row">
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<label>Zip Code</label>
																			</div>
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<input type="text" placeholder="ZipCode" size="6" name="address_zip" data-stripe="address_zip" class="form-control" value="<?php if(isset(Session::get('data')['address_zip'])) { echo Session::get('data')['address_zip']; }?>"/>                     
																			</div>
																		</div>
																	</div>
																</div>

															</div>                

														</div>
														<div class="card-payment-full-2 payment_method_w echeck_payment" <?php if(isset(Session::get('data')['payment_method']) && Session::get('data')['payment_method'] == 'E-Check') { echo 'style="display:block;"'; }?>>

															<div class="pay-input-name">
																<div class="row">
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<div class="row">
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<label>First Name</label>
																			</div>
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<input type="text" name="first_name" value="<?php if(isset(Session::get('data')['first_name'])) { echo Session::get('data')['first_name']; }?>" class="form-control">
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<div class="row">
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<label>Last Name</label>
																			</div>
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<input type="text" name="last_name" value="<?php if(isset(Session::get('data')['last_name'])) { echo Session::get('data')['last_name']; }?>" class="form-control">
																			</div>
																		</div>
																	</div>
																</div>

															</div>

															<div class="pay-input-name">
																<div class="row">
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<label>Bank Account Number</label>
																	</div>
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<input type="text" name="account_number" value="<?php if(isset(Session::get('data')['account_number'])) { echo Session::get('data')['account_number']; }?>" class="form-control">                      
																	</div>
																</div>
															</div>


															<div class="pay-input-name">
																<div class="row">
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<div class="row">
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<label>Routing Number</label>
																			</div>
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<input placeholder="Routing Number" name="routing_number" class="form-control" value="<?php if(isset(Session::get('data')['routing_number'])) { echo Session::get('data')['routing_number']; }?>"/>                      
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6 col-sm-6 col-xs-12">
																		<div class="row">
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<label>Account Type</label>
																			</div>
																			<div class="col-md-12 col-sm-12 col-xs-12">
																				<select name="account_type" class="form-control">
																					<option value="CHECKING" <?php if(isset(Session::get('data')['account_type']) && Session::get('data')['account_type'] == 'CHECKING') { echo 'selected'; }?>>Checking</option>
																					<option value="CHECKING" <?php if(isset(Session::get('data')['account_type']) && Session::get('data')['account_type'] == 'CHECKING') { echo 'selected'; }?>>Saving</option>
																					<option value="CHECKING" <?php if(isset(Session::get('data')['account_type']) && Session::get('data')['account_type'] == 'CHECKING') { echo 'selected'; }?>>Business Checking</option>
																					<option value="CHECKING" <?php if(isset(Session::get('data')['account_type']) && Session::get('data')['account_type'] == 'CHECKING') { echo 'selected'; }?>>Business Saving</option>
																				</select>                      
																			</div>
																		</div>
																	</div>
																</div>

															</div>
														</div>
														<div class="payment-box-submit">
															@csrf
															<input type="hidden" name="invoice_password" value="{{$invoice_password}}">
															<input type="hidden" name="id" value="{{$invoice->id}}">
															<input type="submit" name="submit" value="Submit Payment"> 
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
	      </div>
	    </div>
	  </div>
<?php } } ?>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.mask.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.exp_date').mask('00/0000');
	$('#view_payment_form').on('submit', function(e){
		var v = $('input[name="payment_method"]:checked').val();
		$('.payment_method_err').hide();
		if(v != 'Credit Card' && v != 'E-Check') {
			$('.payment_method_err').show();
			e.preventDefault();
		}
	});
	$('.payment_method').on('click', function(){
		var v = $(this).val();
		$('.payment_method_w').hide();
		$('.payment_method_w input').prop('required', false);
		if(v == 'Credit Card') {
			$('.card_payment').show();
			$('.card_payment input').prop('required', true);
		}
		else if(v == 'E-Check') {
			$('.echeck_payment').show();
			$('.echeck_payment input').prop('required', true);
		}
	});
});
</script>
</body>
</html>



