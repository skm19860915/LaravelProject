@extends('firmlayouts.admin-master')

@section('title')
Upgrade to CMS
@endsection
@push('header_styles')
<style type="text/css">
.clent-info span {
    width: 100%;
}
.table-responsive {
    overflow: hidden;
}
.removeuserbtn {
    margin-right: 10px;
}
.discount_r,
.discount_m {
    display: none;
}
</style>
@endpush 
@section('content')
<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <h1>Upgrade to CMS</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"></div>
        </div>
    </div>
    <div class="section-body">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body payment-form-card">
                        <form action="{{url('firm/pay_for_cms')}}" method="post" id="payment-form" enctype="multipart/form-data">
                            <br>
                            <h2>Admin Information</h2>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="client-right-profile">
                                        <div class="clent-info">
                                            <span>Firm Name : {{ $firm->firm_name }}</span>
                                        </div>
                                        <div class="clent-info">
                                            <span>Firm Admin Email : {{ $firm->email }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="client-right-profile">
                                        <div class="clent-info">
                                            <span>Account Type : CMS</span>
                                        </div>
                                        <div class="clent-info">
                                            <span>Firm Admin Name : {{ $data->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <h2>Users</h2>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="table-responsive table-invoice client-ng-table">
                                      <table class="table table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th> Firm Admin/Owner Email </th>
                                                <th> Admin <a href="#" title="Access to Firm Financials" data-toggle="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                                <th> Non Admin <a href="#" title="No access to Firm Financials" data-toggle="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $c = 0;  
                                            if(!empty($users)) { 
                                                $c = count($users);
                                                foreach ($users as $k => $user) { ?>
                                                    <tr>
                                                        <td>
                                                            <?php if($user->email != $firm->email) { ?>
                                                            <a class="btn btn-primary removeuserbtn" href="#" title="Remove User" data-toggle="tooltip" data-msg="This action will delete {{$user->name}} from your account. Do you wish to proceed?" data-id="{{$user->id}}">
                                                                <i class="fas fa-times"></i>
                                                            </a> 
                                                            <?php } else { ?>
                                                                <a class="btn btn-primary removeuserbtn" data-msg="" style="opacity: 0;">
                                                                    <i class="fas fa-times"></i>
                                                                </a> 
                                                            <?php } ?>
                                                            {{$user->email}}
                                                            <input type="hidden" name="user_id[]" value="{{$user->id}}">
                                                        </td>
                                                        <td>
                                                            <input type="radio" name="user_permmition{{$user->id}}" value="4" <?php if($user->role_id == 4) { echo 'checked'; } ?>>
                                                        </td>
                                                        <td>
                                                            <input type="radio" name="user_permmition{{$user->id}}" value="5" <?php if($user->role_id == 5) { echo 'checked'; } ?> <?php if($user->email == $firm->email) { echo 'disabled'; } ?>>
                                                        </td>
                                                    </tr>
                                            <?php } } ?>
                                        </tbody>
                                      </table>
                                      <a href="#" class="btn btn-primary adduserbtn">Add User</a>
                                    </div>
                                    <div class="paymentdetails">
                                        <br>
                                        <h2>Summary</h2>
                                        <table class="table table table-bordered table-striped">
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
                                                   <td class="paymentdate_r"> <?php 
                                                   if(empty($_COOKIE['payment_cycle'])) {
                                                       echo date('d F Y', strtotime('+1 month')); 
                                                   }
                                                   else {
                                                    echo date('d F Y', strtotime('+1 year'));
                                                   }

                                                   ?></td>
                                                   <td> 
                                                    {{$c}}*{{$firm->usercost}}<span class="discount_m">*12</span>
                                                    </td>
                                                    <td> $<span class="annual_payment_cycletext1">{{ number_format($c*$firm->usercost, 2) }}</span></td>
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
                                        <div class="saved_amount_text"></div>
                                    </div>
                                    <div class="payment-form-card" id="card-element">
                                        <br>
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
                                                <div class="col-md-12">
                                                    <div class="payment-input">
                                                        <input type="text" placeholder="Card Number" size="20" data-stripe="number"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="payment-input">
                                                        <input type="text" placeholder="Expiring Month/MM" data-stripe="exp_month"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="payment-input">
                                                        <input type="text" placeholder="Expiring Year/YYYY" size="2" data-stripe="exp_year">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="CVV Code" size="4" data-stripe="cvc"/></div></div>
                                                <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="Postal Code" size="6" data-stripe="address_zip"/></div></div>
                                                
                                            </div>              
                                            <div class="submit-login">
                                                <input name="amount" value="{{$c*$firm->usercost}}" type="hidden" />
                                                <input name="redirect_url" value="{{url('firm/admindashboard')}}" type="hidden" />
                                                @csrf
                                                <input value="Pay Now" type="submit" class="submit">
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Add Note Modal -->
<div id="AddUserWrapper" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Pay Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="float: right;
          position: absolute;
          right: 22px;
          top: 15px;
          ">&times;</button>
          <h4 class="modal-title">Add New User</h4>
        </div>
        <div class="modal-body">
          <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Name" name="user_name" class="form-control" required="" value="<?php if(isset(Session::get('data')['name'])) { echo Session::get('data')['name']; }?>"> 
                <div class="invalid-feedback">Name is required!</div>
              </div>
            </div> 
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email Address
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="email" placeholder="Email Address" name="email" class="form-control" required="" value="<?php if(isset(Session::get('data')['email'])) { echo Session::get('data')['email']; }?>"> 
                <div class="invalid-feedback">Email Address is required!</div> 
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="" name="Role_type">
                  <?php if($firm->account_type == 'CMS') { ?>
                    <option value="4" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == '4') { echo 'selected="selected"'; }?>>Firm Admin</option>
                    <option value="5" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == '5') { echo 'selected="selected"'; }?>>Firm User</option>
                  <?php } else { ?>
                    <option value="5" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == '5') { echo 'selected="selected"'; }?>>Attorney</option>
                  <?php } ?>
                </select>
                <div class="invalid-feedback">Please select Role!</div> 
              </div>
            </div>
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7 text-right">
                @csrf
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary createfirmuserbtn" type="submit" name="create_firm_user" value="Create and Add More">
                  <span>Create</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection
@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
$(document).ready(function () {
    setTimeout(function(){
        if(getCookie('payment_cycle')) { 
            $('.annual_payment_cycle1').prop('checked', false).trigger('change'); 
        }
    },100);
    $(".removeuserbtn").click(function(e) {
        e.preventDefault();
        var $this = $(this);
        var msg = $(this).data('msg');
        var id = $(this).data('id');
        if(msg != '') {
            var confirm=window.confirm(msg);
            if (confirm==true) {
                $.ajax({
                    type:"get",
                    url:"{{ url('firm/users/delete') }}/"+id,
                    data: {},
                    success:function(res) {       
                        alert('Firm User deleted successfully!');
                        $this.closest('tr').fadeOut();
                        window.location.href = "{{ url('firm/upgradetocms') }}";
                    }

                });
                
            }
        }
    });
    $('.adduserbtn').on('click', function(e){
        e.preventDefault();
        var client_id = $(this).data('client_id');
        $('#AddUserWrapper').modal('show');
    });
    $(".createfirmuserbtn").click(function(e) {
        e.preventDefault();
        var $this = $(this);
        var user_name = $('#AddUserWrapper form input[name="user_name"]').val();
        var email = $('#AddUserWrapper form input[name="email"]').val();
        var Role_type = $('#AddUserWrapper form select[name="Role_type"]').val();
        var _token = $('input[name="_token"]').val();

        $.ajax({
            type:"post",
            url:"{{ url('firm/users/createuser') }}",
            data: {
                _token:_token, 
                user_name:user_name, 
                email:email,
                Role_type:Role_type
            },
            success:function(res) { 
                res = JSON.parse(res);
                if(res.status) {
                    alert(res.msg);
                    window.location.href = "{{ url('firm/upgradetocms') }}";
                }
                else {
                    alert(res.msg);
                }
            }

        });        
    });
    $(".paywithnewbtn").on('click', function(e){
      e.preventDefault();
      $('.newcardwrapper').slideToggle();
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
        $('.saved_amount_text').hide();
        setCookie('payment_cycle', 0, 1);
        $('.discount_r').hide();
        $('.discount_m').hide();
        pdate = new Date(dt.setMonth(dt.getMonth() + 1));
      }
      else {
        amt = parseInt(monthly_amount)*12*parseInt(tu);
        amt1 = parseInt(monthly_amount)*12*parseInt(tu)-parseInt(annual_amount)*parseInt(tu);
        txt = amt;
        $('.saved_amount_text').show();
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
      $('.saved_amount_text').text("You saved $"+annual_amount*parseInt(tu));
    });
});
Stripe.setPublishableKey("{{ env('SRTIPE_PUBLIC_KEY') }}");
$(document).ready(function(){
  $('.paywith_existing').on('click', function() {
    console.log('1');
    $('input[name="card_source"]').prop('checked', false);
    $(this).closest('.row').find('input[type="checkbox"]').prop('checked', true);
  });
  $('.payforcms').on('click', function(e){
    e.preventDefault();
    $(this).hide();
    $('#card-element').slideDown();
  })
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