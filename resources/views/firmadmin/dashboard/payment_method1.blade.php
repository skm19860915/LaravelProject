@extends('layouts.admin-master')

@section('title')
Edit Firm
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Firm Account</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
    
   <div class="row">
     <div class="col-md-12">
      <div class="card">
        <div class="row">

          
          <div class="col-12 mb-4">
           <div class="payment-method">
            
             <div class="payment-left">
               <form action="{{ url('firm/create_charge1') }}" method="get" id="payment-form" enctype="multipart/form-data"> 
              
              
              <div class="state-billing" style="padding-bottom: 15px; padding-top: 15px;">
               <div class="state-billing-one text-left" style="width: 100%;">Amount to be paid : <span>${{$amount}}</span></div>
              </div>
             
              <div class="payment-form-card" id="card-element">
               <h2>Payment details</h2>
               <?php if(!empty($card)) {
                echo '<div class="row card-payno-tx"><div class="col-md-12 text center">Pay with existing card</div></div>';
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
                   <input value="Pay" type="submit" class="paywith_existing btn btn-primary">
                 </div>
               </div>
               <?php }
               echo '<div class="row"><div class="col-md-12 text center">OR, Pay with new card</div></div>';
               } ?>
               <div class="row card-payno">
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
                <input name="amount" value="{{$amount}}" type="hidden" />
                <input value="Upgrade" type="submit" class="submit">
               </div>
               
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
</section>

@endsection
@push('footer_script')

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">

  Stripe.setPublishableKey("{{ env('SRTIPE_PUBLIC_KEY') }}");
  $(document).ready(function(){
    $('.paywith_existing').on('click', function() {
      console.log('1');
      $('input[name="card_source"]').prop('checked', false);
      $(this).closest('.row').find('input[type="checkbox"]').prop('checked', true);
    });
  });
  $(function() {
    var $form = $('#payment-form');
    $form.submit(function(event) {
      if(!$('input[name="card_source"]').is(':checked')) {
        // Disable the submit button to prevent repeated clicks:
        $form.find('.submit').prop('disabled', true);

        // Request a token from Stripe:
        Stripe.card.createToken($form, stripeResponseHandler);

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

      // Insert the token ID into the form so it gets submitted to the server:
      // $form1 = $('#payment-form-res');
      $form.append($('<input type="hidden" name="stripeToken">').val(token));

      // Submit the form:
      $form.get(0).submit();
    }
  };
</script>

@endpush 