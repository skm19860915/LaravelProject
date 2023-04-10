@extends('firmlayouts.admin-master')

@section('title')
View Case
@endsection

@section('content')
<section class="section client-listing-details">
	<!--new-header open-->
	  @include('firmadmin.case.case_header')
	<!--new-header Close-->
	<div class="section-body">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="back-btn-new">
			            <a href="{{ url('firm/case/case_family') }}/{{$id}}">
			              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
			           </div>
					<div class="card-header">
						<!-- <h4>Show Firm Case</h4> -->
					</div>
					<div class="card-body">
						<form action="{{url('firm/case/update_case')}}" method="post" id="payment-form">
						  <?php 
			              if($firm->account_type == 'CMS') {
			                $cname = $client->first_name;
			                if(!empty($client->middle_name)) {
			                  $cname .= ' '.$client->middle_name;
			                }
			                if(!empty($client->last_name)) {
			                  $cname .= ' '.$client->last_name;
			                }
			               ?>
			                <div class="form-group row mb-4">
			                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Firm Client
			                  </label> 
			                  <div class="col-sm-12 col-md-7">
			                    <input type="hidden" name="firmclient" value="{{$client->user_id}}">
			                    <input type="hidden" name="client_id" value="{{$client->id}}">
			                    <input class="form-control" type="text" value="{{$cname}}" readonly="readonly">
			                  </div>
			                </div>
			              <?php } else { ?>  
			                <input type="hidden" name="firmclient" value="0">
			                <input type="checkbox" name="VP_Assistance" class="custom-switch-input VP_Assistance" value="1" checked="checked">
			              <?php } ?>  
			              <div class="form-group row mb-4 accout_cms">
			                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Case Category
			                </label> 
			                <div class="col-sm-12 col-md-7">
			                	<input class="form-control" type="text" value="{{$case->case_category}}" readonly="readonly">
			                </div>
			              </div>

			              <div class="form-group row mb-4">
			                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Case Type
			                </label> 
			                <div class="col-sm-12 col-md-7">
			                	<input class="form-control" type="text" value="{{$case->case_type}}" readonly="readonly">
			                </div>
			              </div>
			              <?php 
			              	$additional_service = json_decode($case->additional_service);
      						if($case->case_category == 'Adjustment of Status' || $case->case_category == 'NVC Packet') { ?>
			              <div class="form-group row mb-4 additional_service">
			                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Additional Services</label> 
			                <div class="col-sm-12 col-md-7">
			                  <?php 
			                  if($case->case_category == 'NVC Packet') { ?>
			                  <div class="row nvc_packet" style="margin-bottom: 15px;">
			                    <div class="col-md-1">
			                    </div>
			                    <div class="col-md-6">
			                      <strong>NVC Packet</strong>
			                    </div>
			                    <div class="col-md-3">
			                      
			                    </div>
			                  </div>
			                  <div class="row nvc_packet" style="margin-bottom: 15px;">
			                    <div class="col-md-1"></div>
			                    <div class="col-md-6">
			                      Add a derivative beneficiary with own DS-260 only
			                    </div>
			                    <div class="col-md-3">
			                      <input type="hidden" name="nvc_packet" class="form-control" value="Add a derivative beneficiary with own DS-260 only">
			                      <input type="number" name="nvc_packet_quantity" min="0" class="form-control" value="{{$additional_service->nvc_packet_quantity}}" readonly="readonly">
			                    </div>
			                    <div class="col-md-2 add_more_wrapper">
			                      <!-- <a href="#" class="add_quantity_btn">
			                        <i class="fa fa-plus"></i>
			                      </a>
			                      <a href="#" class="remove_quantity_btn">
			                        <i class="fa fa-minus"></i>
			                      </a> -->
			                    </div>
			                  </div>
			                  <div class="row nvc_packet" style="margin-bottom: 15px;">
			                    <div class="col-md-1"></div>
			                    <div class="col-md-6 text-md-right">
			                      Total
			                    </div>
			                    <div class="col-md-3">
			                      <span class="nvc_total">${{$additional_service->nvc_packet_quantity*99}}</span>
			                    </div>
			                  </div>
			              	  <?php } ?>
			                  <div class="row" style="margin-bottom: 15px;">
			                    <div class="col-md-1">
			                    </div>
			                    <div class="col-md-6">
			                      <strong>Affidavit of Support</strong>
			                    </div>
			                    <div class="col-md-3">
			                      
			                    </div>
			                  </div>
			                  <?php 
			                  $adm = 0;
			                  if(!empty($additional_service->additional_service->additional_service)) {
			                      foreach ($additional_service->additional_service->additional_service as $k1 => $v1) {  
			                      	$adm += 99; ?>
			                  <div class="row" style="margin-bottom: 15px;">
			                    <div class="col-md-1">
			                      <!-- <input type="checkbox" name="additional_service[]" data-cost="99" value="I-864, Affidavit of Support (Co Sponsor)"> -->
			                    </div>
			                    <div class="col-md-6">
			                      {{$v1}}
			                    </div>
			                    <div class="col-md-3">
			                      <input type="text" name="extra_cost" class="form-control" value="$99" readonly="readonly">
			                    </div>
			                  </div>
			              	  <?php } ?>
			                  <div class="row" style="margin-bottom: 15px;">
			                    <div class="col-md-1"></div>
			                    <div class="col-md-6 text-md-right">
			                      Total
			                    </div>
			                    <div class="col-md-3">
			                      <span class="add_serv_total">${{$adm}}</span>
			                    </div>
			                  </div>
			                  <?php } ?>
			                  <div class="row" style="margin-bottom: 15px;">
			                    <div class="col-md-1">
			                    </div>
			                    <div class="col-md-6">
			                      <strong>Affidavit /Declaration</strong>
			                    </div>
			                    <div class="col-md-3">
			                      
			                    </div>
			                  </div>
			                  <div class="row" style="margin-bottom: 35px;">
			                    <div class="col-md-1">
			                      <input type="checkbox" name="additional_service1" data-cost="180" value="Affidavit Draft  - (a conferrence call or video available)" checked="checked" style="display: none;">
			                    </div>
			                    <div class="col-md-6">
			                      Affidavit Draft  - (a conferrence call or video available)
			                    </div>
			                    <div class="col-md-3">
			                    </div>
			                    <div class="col-md-2 add_more_wrapper">
			                      <!-- <a href="#" class="add_more_btn">
			                        <i class="fa fa-plus"></i>
			                      </a>
			                      <a href="#" class="remove_more_btn">
			                        <i class="fa fa-minus"></i>
			                      </a> -->
			                    </div>
			                  </div>
			                  <?php 
			                  $amtd = 0;
			                    if(!empty($additional_service->declaration->declaration)) {
			                      foreach ($additional_service->declaration->declaration as $k1 => $v1) {
			                      $amtd += 180; ?>
			                      	<div class="row rmrow" style="margin-bottom: 15px;">
									   <div class="col-md-6 offset-md-1">
									   		<input type="text" class="form-control totalcost" name="declaration[]" value="<?php echo $v1; 
				                          if($v1 == 'Other') {
				                            echo ' - '.$additional_service->declaration->declaration_other[$k1];
				                          }
				                          ?>" readonly="readonly">
									    </div>
									   <div class="col-md-3">
									   	<input type="text" class="form-control totalcost" value="$180" readonly="readonly">
									   </div>
									   <div class="col-md-2 add_more_wrapper">
									   	  
									   </div>
									</div>
		                      <?php } ?>
		                      	<div class="row nvc_packet" style="margin-bottom: 15px;">
				                    <div class="col-md-1"></div>
				                    <div class="col-md-6 text-md-right">
				                      Total
				                    </div>
				                    <div class="col-md-3">
				                      <span class="add_serv1_total">${{$amtd}}</span>
				                    </div>
				                  </div>
				                
		                      <?php } ?>
			                </div>  
			                <input type="hidden" name="additional_service_cost" value="0">
			                <input type="hidden" name="additional_service1_cost" value="0">
			              </div>
			              <?php 
			          		}
			              if($firm->account_type == 'CMS') { ?>
			              <div class="form-group row mb-4">
			                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Attorney of Record
			                </label> 
			                <div class="col-sm-12 col-md-7">
			                  <input class="form-control" type="text" value="{{$case->urn}}" readonly="readonly">
			                </div>
			              </div>
			              <div class="form-group row mb-4">
			              <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Assign Paralegal
			              </label> 
			              <div class="col-sm-12 col-md-7">
			                <input class="form-control" type="text" value="{{$case->upn}}" readonly="readonly">
			                <div class="invalid-feedback">Please select user Type!</div> 
			              </div>
			            </div>
			            <div class="row form-group">  
			              <label class="col-form-label text-md-left col-12 col-md-2 col-lg-2">
			                Assigned case to a TILA VP
			              </label>
			              <div class="col-sm-12 col-md-7">
			                <div class="selectgroup w-100">
			                  <label class="selectgroup-item">
			                    <input type="radio" name="VP_Assistance" value="1" class="selectgroup-input" <?php if($case->VP_Assistance == 1) { echo 'checked'; } ?> readonly="readonly">
			                    <span class="selectgroup-button">Yes</span>
			                  </label>
			                  <label class="selectgroup-item">
			                    <input type="radio" name="VP_Assistance" value="0" class="selectgroup-input" <?php if($case->VP_Assistance == 0) { echo 'checked'; } ?> readonly="readonly" disabled>
			                    <span class="selectgroup-button">No, Skip for now</span>
			                  </label>
			                  
			                </div>
			                <?php if($currunt_user->role_id == 5) { ?>
				                <i class="vp_case_notification" <?php if($case->VP_Assistance == 0) { echo 'style="display: none;"'; } ?>>A notification of authorization will be send to {{$firm->firm_admin_name}} for payment. You will receive an update when approved.</i>
				                <?php } ?>
			              </div>
			            </div>
			            <input name="firmuser" type="hidden" value="{{$case->user_id}}">
			            <input name="assign_paralegal" type="hidden" value="{{$case->assign_paralegal}}">
			            <?php } else { ?>
			            <input name="firmuser" type="hidden" value="{{$currunt_user->id}}">
			            <input name="assign_paralegal" type="hidden" value="{{$currunt_user->id}}">
			          <?php } ?>

			              <div class="form-group row mb-4 rkcasecost" <?php 
			              if($firm->account_type == 'CMS' && $case->VP_Assistance == 0) { ?> style="display: none;" <?php } ?>>
			                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Case Cost
			                </label> 
			                <div class="col-sm-12 col-md-7">
			                  <input type="text" placeholder="Case cost" name="casecost" class="form-control casecost1" value="{{$case->case_cost}}" readonly="readonly"> 
			                </div>
			              </div> 
			              <div class="form-group row mb-4 paymentwrapper" style="display: none;">
			                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
			                </label> 
			                  <div class="col-sm-12 col-md-7 payment-form-card" id="card-element">
			                   <h2>Payment details</h2>
			                   <?php if(!empty($card)) {
			                    echo '<div class="row"><div class="col-md-12 text center">Pay with existing card</div></div>';
			                    foreach ($card as $k => $v) {
			                    ?>
			                   <div class="row">
			                     <div class="col-md-8">
			                       <label>
			                         <input type="text" value="***********<?php echo $v->last4; ?>" class="form-control" readonly />
			                         <input type="checkbox" value="<?php echo $v->id; ?>" name="card_source" style="display: none;"/>
			                       </label>
			                     </div>
			                     <div class="col-md-4">
			                       <input value="Pay" type="submit" class="paywith_existing btn btn-primary" name="approve_pay">
			                     </div>
			                   </div>
			                   <?php }
			                   echo '<div class="row"><div class="col-md-12 text center">OR, Pay with new card</div></div>';
			                   } ?>
			                   <div class="row">
			                    <div class="col-md-12"><div class="payment-input">
			                      <input type="text" placeholder="Card Number" size="20" data-stripe="number"/></div></div>
			                   </div>
			                   <div class="row">
			                    <div class="col-md-6 col-sm-6"><div class="payment-input">
			                      <input type="text" placeholder="Expiring Month" data-stripe="exp_month"/>
			                    </div>
			                   </div>
			                    <div class="col-md-6 col-sm-6"><div class="payment-input">
			                      <input type="text" placeholder="Expiring Year" size="2" data-stripe="exp_year">
			                    </div>
			                  </div>
			                    
			                   </div>
			                   <div class="row">
			                    <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="CVV Code" size="4" data-stripe="cvc"/></div></div>
			                    <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="Postal Code" size="6" data-stripe="address_zip"/></div></div>
			                    
			                   </div>
			                   
			                   <div class="submit-login">
			                    <label class="payment-errors text-warning"></label><br>
			                    <input value="Create" type="submit" class="submit" name="approve_pay">
			                   </div>
			                   
			                  </div>
			              </div> 

			              <div class="form-group row mb-4">
			                <!--<label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
			                </label>--> 
			                <div class="col-sm-12 col-md-7">
			                  @csrf
			                  <input value="{{$case->id}}" type="hidden" name="id">
			                  <?php 
			                  if($currunt_user->role_id == 4) { ?>
				                <input class="btn btn-primary create_firm_case" type="submit" name="approve_pay" value="Submit & Pay" <?php if($case->VP_Assistance == 0) { echo 'style="display: none;"'; } ?> />
				                <input class="btn btn-primary decline" type="submit" name="decline" value="Decline" <?php if($case->VP_Assistance == 0) { echo 'style="display: none;"'; } ?> />
			              	  <?php } else { ?>
			              	  	<input class="btn btn-primary update_case" type="submit" name="update_case" value="Update Case" <?php if($case->VP_Assistance == 0) { echo 'style="display: none;"'; } ?> />
			              		<?php } ?>
			                </div>
			              </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
  var is_additional_service = 0;
  var cost1 = 0;
  var cost2 = 0;
  var costc = 0;
  var costd = 0;
  var costn = 0;
  $(document).ready(function(){

    $('.create_firm_case').on('click', function() {
      if($('input[name="VP_Assistance"]:checked').val() == 1 && "{{$currunt_user->role_id}}" == "4") {
        $('.paymentwrapper').slideDown();
        $(this).hide();
        $('.decline').hide();
      }
    });
    $('.paywith_existing').on('click', function() {
      $('input[name="card_source"]').prop('checked', false);
      $(this).closest('.row').find('input[type="checkbox"]').prop('checked', true);
    });
    $('.decline').on('click', function() {
      $('input[name="card_source"]').prop('checked', true);
    });

    $('input[name="VP_Assistance"]').on('click', function(){
      if($('input[name="VP_Assistance"]:checked').val() == 1) {
        $('.rkcasecost').show();
        $('.totalcost').show();
        $('.update_case').show();
        $('.vp_case_notification').show();
        $('.create_firm_case').show();
        $('.decline').show();
      }
      else {
      	$('.vp_case_notification').hide();
        $('.totalcost').hide();
        $('.paymentwrapper').slideUp();
        $('.rkcasecost').hide();
        $('.create_firm_case').show(); 
        $('.decline').show();
        $('.update_case').hide();
      }
    });
  });
  Stripe.setPublishableKey("{{ env('SRTIPE_PUBLIC_KEY') }}");

  $(function() {
    var $form = $('#payment-form');
    $form.submit(function(event) {
      console.log('2');
      if($('input[name="VP_Assistance"]:checked').val() == 1 && !$('input[name="card_source"]').is(':checked') && "{{$currunt_user->role_id}}" == "4") {
        // Disable the submit button to prevent repeated clicks:
        $form.find('.submit').prop('disabled', true);

        // Request a token from Stripe:
        Stripe.card.createToken($form, stripeResponseHandler);
        // alert('firm admin');
        // Prevent the form from being submitted:
        return false;
      }
    });
  });

  function stripeResponseHandler(status, response) {
    // Grab the form:
    var $form = $('#payment-form');

    if (response.error) { // Problem!

      // Show the errors on the form:
      $form.find('.payment-errors').text(response.error.message);
      $form.find('.submit').prop('disabled', false); // Re-enable submission

    } else { // Token was created!

      // Get the token ID:
      var token = response.id;
      // $form.find('.submit').prop('disabled', false);
      $form.append($('<input type="hidden" name="approve_pay">').val('Create'));
      // Insert the token ID into the form so it gets submitted to the server:
      // $form1 = $('#payment-form-res');
      $form.append($('<input type="hidden" name="stripeToken">').val(token));
      
      // Submit the form:
      $form.get(0).submit();
    }
  };
</script>
@endpush 