@extends('firmlayouts.admin-master')

@section('title')
Edit Firm
@endsection
@push('header_styles')
<style type="text/css">
.discount_r,
.discount_m {
    display: none;
}
</style>
@endpush 
@section('content')
<section class="section">
  <div class="section-header">
    <h1>Firm Account</h1>
    <div class="section-header-breadcrumb">
      <?php 
      if($viewamount) { ?>
        <a href="{{ url('firm/users/addnewuser') }}" class="btn btn-primary" style="width: 125px;">Add More Users</a>
      <?php }
      else { ?>
        <a href="{{ url('firm/exit_payment_page') }}" class="btn btn-primary" style="width: 125px;">Back</a>
        <a href="{{ url('firm/users/create') }}" class="btn btn-primary" style="width: 125px;">Add More Users</a>
      <?php } ?>
      
    </div>
  </div>
  <div class="section-body">
    
   <div class="row">
     <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">

            
            <div class="col-12 mb-4">
             <div class="payment-method">
                
               <div class="payment-left">
                 <form action="{{ url('firm/create_charge') }}" method="get" id="payment-form" enctype="multipart/form-data"> 
                
                
                <div class="state-billing" style="padding-bottom: 15px; padding-top: 15px;">
                 <div class="state-billing-one text-left" style="width: 100%;">Amount to be paid : <span>${{$amount}}</span></div>
                </div>
                <div class="addedusers payment-method-table">
                  
                    <table class="table table table-bordered table-striped"  id="table" >
                      <thead>
                        <tr>
                         <th> Name</th>
                         <th> Email</th>
                         <th> Cost</th>
                         <th> Action</th>
                        </tr>
                      </thead>
                    
                  <?php $c = 0; 
                  if(!empty($firm_user)) { 
                    $c = count($firm_user);
                    foreach ($firm_user as $k => $v) {
                      // pre($v);
                      ?>
                      <tr>
                        <td> <?php echo $v->name; ?></td>
                        <td> <?php echo $v->email; ?></td>
                        <td> ${{$firm->usercost}}</td>
                        <td>
                          <a href="#" class="action_btn viewuser" data-user='<?php echo str_replace("'", '*', json_encode($v)); ?>'>
                            <img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png">
                          </a>
                          <a href="{{url('firm/users/deletenew')}}/{{$v->id}}" class="action_btn">
                            <img src="{{ url('/') }}/assets/images/icons/case-icon3.svg">
                          </a>
                          
                        </td>
                      </tr>
                      <?php 
                    } } ?>
                    <!-- <tr>
                        <td> <?php echo $user->name; ?></td>
                        <td> <?php echo $user->email; ?></td>
                        <td> ${{$viewamount}}</td>
                        <td>
                           
                        </td>
                      </tr> -->
                    </table>
                </div>
                <div class="paymentdetails">
                  <h4>Summary</h4>
                  <table class="table table table-bordered table-striped"  id="table" >
                    <thead>
                      <tr>
                       <th> Cycle</th>
                       <th> Next Billing Period</th>
                       <th> Users</th>
                       <th> Total</th>
                      </tr>
                      <tr>
                       <td> <label class="custom-switch mt-2">
                          <span class="custom-switch-description" style="margin: 0 .5rem 0 0;">Annually</span> 
                          <input type="checkbox" name="payment_cycle" class="custom-switch-input annual_payment_cycle1" value="1" checked data-monthly_amount="{{$firm->usercost}}" data-tu="{{$c}}">
                          <span class="custom-switch-indicator"></span>
                          <span class="custom-switch-description">Monthly</span>
                      </label></td>
                       <td> <?php 
                         if(empty($_COOKIE['payment_cycle'])) {
                            echo date('d F Y', strtotime('+1 month')); 
                         }
                         else {
                          echo date('d F Y', strtotime('+1 year'));
                         }

                         ?></td>
                       <td> 
                        <?php if($viewamount) { echo $c+1; } else { echo $c; }; ?>*{{$firm->usercost}}<span class="discount_m">*12</span>
                       </td>
                       <td> $<span class="annual_payment_cycletext1">{{ number_format($c*$amount, 2) }}</span></td>
                      </tr>
                      <tr class="discount_r">
                        <td></td>
                        <td></td>
                        <td><strong>Discount</strong></td>
                        <td>$<span class="discount_amt"></span></td>
                    </tr>
                    <tr class="discount_r">
                        <td></td>
                        <td></td>
                        <td><strong>Total</strong></td>
                        <td>$<span class="discount_ttl"></td>
                    </tr>
                    </thead>
                  </table>
                </div>
                <div class="payment-form-card" id="card-element">
                 <h2>Payment details</h2>
                 <?php $pst = ''; if(!empty($card)) {
                  $pst = 'display:none;';
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
                 echo '<div class="row"><div class="col-md-12 text center">OR, <br><a href="#" class="btn btn-primary paywithnewbtn">Pay with new card</a></div></div>';
                 } ?>
                 <div class="newcardwrapper" style="{{$pst}}">
                 <div class="row card-payno">
                    <div class="col-md-12"><div class="payment-input">
                      <input type="text" placeholder="Card Number" size="20" data-stripe="number"/></div></div>
                   </div>
                   <div class="row">
                    <div class="col-md-6 col-sm-6"><div class="payment-input">
                      <input type="text" placeholder="Expiring Month/MM" data-stripe="exp_month"/>
                    </div>
                  </div>
                    <div class="col-md-6 col-sm-6"><div class="payment-input">
                      <input type="text" placeholder="Expiring Year/YYYY" size="2" data-stripe="exp_year">
                    </div>
                  </div>
                    
                   </div>
                   <div class="row">
                    <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="CVV Code" size="4" data-stripe="cvc"/></div></div>
                    <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="Postal Code" size="6" data-stripe="address_zip"/></div></div>
                    
                   </div>              
                 <div class="submit-login">
                  <input name="amount" value="{{$amount}}" type="hidden" />
                  <label class="payment-errors text-warning"></label><br>
                  <input value="Pay Now" type="submit" class="submit">
                 </div>
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
</div>
</section>
<a class="trigger--fire-modal-2" id="fire-modal-2" href="#" style="display: none;">
</a>
<div class="modalformpart" id="modal-form-part" style="display: none;">
  <form action="#" method="post" class="needs-validation" enctype="multipart/form-data">
    <div class="form-group row mb-4">
      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name
      </label> 
      <div class="col-sm-12 col-md-7">
        <input type="text" placeholder="Name" name="user_name" class="form-control" required="" value=""> 
        <div class="invalid-feedback">Name is required!</div>
      </div>
    </div> 
    
    <div class="form-group row mb-4">
      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email Address
      </label> 
      <div class="col-sm-12 col-md-7">
        <input type="email" placeholder="Email Address" name="email" class="form-control" required="" value=""> 
        <input type="hidden" placeholder="Email Address" name="oldemail" class="form-control" required="" value=""> 
        <div class="invalid-feedback">Email Address is required!</div> 
      </div>
    </div> 

    <div class="form-group row mb-4">
      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role
      </label> 
      <div class="col-sm-12 col-md-7">
        <select class="form-control" required="" name="role_type">
          <option value="4">Firm Admin</option>
          <option value="5">Firm User</option>
        </select>
        <div class="invalid-feedback">Please select Role!</div> 
      </div>
    </div>
    <div class="row">  
      <div class="col-md-12 text-right">
        @csrf
        <input type="hidden" name="id" value="0" />
        <input type="submit" name="save" value="Confirm" class="btn btn-primary updtenewuser"/>
      </div>
    </div>
  </form>
</div>
@endsection
@push('footer_script')

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">

  Stripe.setPublishableKey("{{ env('SRTIPE_PUBLIC_KEY') }}");
  $(document).ready(function(){
    setTimeout(function(){
        if(getCookie('payment_cycle')) { 
            $('.annual_payment_cycle1').prop('checked', false).trigger('change'); 
        }
    },100);
    $(".paywithnewbtn").on('click', function(e){
      e.preventDefault();
      $('.newcardwrapper').slideToggle();
    });
    $('.paywith_existing').on('click', function() {
      $('.paywith_existing').hide();
      $('input[name="card_source"]').prop('checked', false);
      $(this).closest('.row').find('input[type="checkbox"]').prop('checked', true);
      alert('Your payment is being processed');
    });
    $('.viewuser').on('click', function(e){
      e.preventDefault();
      var user = $(this).data('user');
      console.log(user);
      var nm = user.name;
      var uname = nm.replace('*', "'");
      $('.modalformpart input[name="id"]').val(user.id);
      $('.modalformpart input[name="user_name"]').val(uname);
      $('.modalformpart input[name="email"]').val(user.email);
      $('.modalformpart input[name="oldemail"]').val(user.email);
      $('.modalformpart select[name="role_type"]').val(user.role_id);
      $('#fire-modal-2').trigger('click');
    });
    $("#fire-modal-2").fireModal({title: 'User Details', body: $("#modal-form-part"), center: true});
    $('.updtenewuser').on('click', function(e){
      e.preventDefault();
      var id = $('.modalformpart input[name="id"]').val();
      var name = $('.modalformpart input[name="user_name"]').val();
      var email = $('.modalformpart input[name="email"]').val();
      var oldemail = $('.modalformpart input[name="oldemail"]').val();
      var Role_type = $('.modalformpart select[name="role_type"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/users/update_new') }}",
        data: {
          id:id, 
          name:name, 
          email:email, 
          _token:_token, 
          oldemail: oldemail,  
          Role_type: Role_type
        },
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/payment_method') }}";
          }
          else {
            alert(res.msg)
          }
        }
      });
    });
    $('.annual_payment_cycle1').on('change', function(){
      var monthly_amount = $(this).data('monthly_amount');
      var tu = $(this).data('tu');
      var annual_amount = "{!! \get_user_meta(1, 'annual_amount'); !!}";
      var amt = 0;
      var amt1 = 0;
      var txt = '';
      var pdate = '';
      var dt = new Date();
      if($(this).is(':checked')) {
        amt = parseInt(monthly_amount)*parseInt(tu);
        amt1 = parseInt(monthly_amount)*parseInt(tu);
        txt = amt;
        setCookie('payment_cycle', 0, 1);
        $('.discount_r').hide();
        $('.discount_m').hide();
        pdate = new Date(dt.setMonth(dt.getMonth() + 1));
      }
      else {
        amt = parseInt(monthly_amount)*12*parseInt(tu);
        amt1 = parseInt(monthly_amount)*12*parseInt(tu)-parseInt(annual_amount)*parseInt(tu);
        txt = amt;
        setCookie('payment_cycle', 1, 1);
        $('.discount_r').show();
        $('.discount_m').show();
        pdate = new Date(dt.setMonth(dt.getMonth() + 12));
      }
      var month1 = pdate.getUTCMonth(); //months from 1-12
      var day = pdate.getUTCDate();
      var year = pdate.getUTCFullYear();

      var month = new Array(12);
        month[0] = "January";
        month[1] = "February";
        month[2] = "March";
        month[3] = "April";
        month[4] = "May";
        month[5] = "June";
        month[6] = "July";
        month[7] = "August";
        month[8] = "September";
        month[9] = "October";
        month[10] = "November";
        month[11] = "December";

        var mm = month[month1];
      var pdate1 = day+' '+mm+' '+year;
      $('.paymentdate_r').text(pdate1);
      $('.discount_amt').text(annual_amount*parseInt(tu));
      $('.discount_ttl').text(amt1.toFixed(2));
      $('#payment-form input[name="amount"]').val(amt1);
      $('.annual_payment_cycletext1').text(txt.toFixed(2));
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