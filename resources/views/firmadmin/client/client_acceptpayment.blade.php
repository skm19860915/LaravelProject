@extends('firmlayouts.admin-master')

@section('title')
View client
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
  .payment_method_w {
    display: none;
  }
  .curruncy_symbol {
    position: absolute;
    left: 30px;
    top: 7px;
  } 
  input.form-control.case_cost {
    padding-left: 25px !important;
  }
   /* Chrome, Safari, Edge, Opera */
  input.case_cost::-webkit-outer-spin-button,
  input.case_cost::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Firefox */
  input.case_cost {
    -moz-appearance: textfield;
  }
</style>
@endpush

@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('firmadmin.client.client_header')
<!--new-header Close-->
  
   <div class="section-body cases-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/client') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
           <div class="card-body">
           
             <div class="profile-new-client">
               <!-- <h2>Invoices</h2> -->
               <!-- <a href="{{ url('firm/client/add_new_invoice/')}}/{{$client->user_id}}" class="add-task-link">Add Invoice</a> -->
               <div class="profle-text-section">
                <div class="task-tabbtn-box">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link" data-status="0" id="summary-tab" data-toggle="tab" href="{{ url('firm/client/client_billing') }}/{{$client->id}}" role="tab" aria-controls="summary" aria-selected="true">Summary</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="invoices-tab" data-toggle="tab" href="{{ url('firm/client/client_invoice') }}/{{$client->id}}" role="tab" aria-controls="invoices" aria-selected="false">Invoices</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="scheduled-tab" data-toggle="tab" href="{{ url('firm/client/client_scheduled') }}/{{$client->id}}" role="tab" aria-controls="scheduled" aria-selected="false">Scheduled</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link active" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/client/client_acceptpayment') }}/{{$client->id}}" role="tab" aria-controls="acceptpayment" aria-selected="false">Accept Payment</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="client_schedule_history-tab" data-toggle="tab" href="{{ url('firm/client/client_schedule_history') }}/{{$client->id}}" role="tab" aria-controls="client_schedule_history" aria-selected="false">Scheduled History</a>
                    </li> 
                  </ul>
                </div>
                <div class="task-tabcontent-box">
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="acceptpayment" role="tabpanel" aria-labelledby="home-tab">
                      <form action="{{ url('firm/client/payForClientInvoice') }}" method="post" class="needs-validation" id="payment-form" enctype="multipart/form-data" novalidate="">
                        <div class="row form-group">
                          <div class="col-sm-7 col-md-7">
                            <div class="row">
                              <label class="col-form-label col-md-4 col-sm-4">Search Invoice Number <span style="color: red"> *</span>
                              </label> 
                              <div class="col-sm-6 col-md-6">

                                <select placeholder="Search Invoice Number" name="invoice_number" class="selectpicker invoice_number" required="required" data-live-search="true">
                                  <option value="">Select</option> 
                                  <?php 
                                  if(!empty($invoice)) {
                                    foreach ($invoice as $k => $i) {
                                      $sl = '';
                                      if($i->id ==$id1) {
                                        $sl = 'selected';
                                      }
                                      if($i->amount-$i->paid_amount) {
                                        echo '<option value="'.$i->id.'" '.$sl.' data-amount="'.$i->amount.'" data-paid_amount="'.$i->paid_amount.'">Invoice #'.$i->id.'-'.$i->description.'</option>';
                                      }
                                    }
                                  }
                                  ?>
                                </select>
                                <div class="invalid-feedback invoice_no_err">Invoice is required!</div>
                                <!-- <div class="outstanding_amount" style="display: none; padding: 5px 0 0 15px;">Outstanding Amount : <span></span></div> -->
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row form-group outstanding_amount" <?php if(empty($qbinvoice)) { echo 'style="display: none;"'; } ?>>
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Outstanding Amount</label>
                                <div class="col-sm-6 col-md-6">
                                  <span>
                                    <?php if(!empty($qbinvoice)) {
                                    $aa = number_format($qbinvoice->amount-$qbinvoice->paid_amount, 2);
                                    echo '$'.$aa;
                                 } ?>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        <div class="row form-group">
                          <div class="col-sm-7 col-md-7">
                            <div class="row">
                              <label class="col-form-label col-md-4 col-sm-4">Method Of Payment <span style="color: red"> *</span>
                              </label> 
                              <div class="col-sm-6 col-md-6">
                                <select placeholder="Destination Account" name="payment_method" class="form-control payment_method" required="required">
                                  <option value="">Select</option> 
                                  <option value="Credit Card" <?php if(isset(Session::get('data')['payment_method']) && Session::get('data')['payment_method'] == 'Credit Card') { echo 'selected="selected"'; }?>>Credit Card</option> 
                                  <option value="Cash">Cash</option> 
                                  <option value="E-Check" <?php if(isset(Session::get('data')['payment_method']) && Session::get('data')['payment_method'] == 'E-Check') { echo 'selected="selected"'; }?>>E-Check</option> 
                                  <option value="Money Order">Money Order</option> 
                                </select>
                                <div class="invalid-feedback">Method Of Payment is required!</div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="payment_method_w card_payment" <?php if(isset(Session::get('data')['payment_method']) && Session::get('data')['payment_method'] == 'Credit Card') { echo 'style="display:block;"'; }?>>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Name of Credit Card <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input placeholder="Name of Credit Card" name="name_of_credit_card" class="form-control" value="<?php if(isset(Session::get('data')['name_of_credit_card'])) { echo Session::get('data')['name_of_credit_card']; }?>" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Credit Card Number <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input type="text" placeholder="Credit Card Number" size="20" name="card_number" class="form-control" data-stripe="number" maxlength="16" value="<?php if(isset(Session::get('data')['card_number'])) { echo Session::get('data')['card_number']; }?>" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">ZipCode <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input type="text" placeholder="ZipCode" size="6" name="address_zip" data-stripe="address_zip" class="form-control" value="<?php if(isset(Session::get('data')['address_zip'])) { echo Session::get('data')['address_zip']; }?>"/>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Expiration Date <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input type="text" placeholder="mm/yyyy" name="exp_date" data-stripe="exp_date" class="form-control exp_date" value="<?php if(isset(Session::get('data')['exp_date'])) { echo Session::get('data')['exp_date']; }?>"/>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">CVV <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input type="text" placeholder="CVC" size="4" data-stripe="cvc" name="cvc" class="form-control" value="<?php if(isset(Session::get('data')['cvc'])) { echo Session::get('data')['cvc']; }?>"/>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Amount <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <span class="curruncy_symbol">$</span>
                                  <input type="number" min="1" placeholder="Amount" name="amount" class="form-control case_cost" value="<?php if(isset(Session::get('data')['amount'])) { echo Session::get('data')['amount']; }?>" />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="payment_method_w cash_payment">
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Amount <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <span class="curruncy_symbol">$</span>
                                  <input type="number" min="1" placeholder="Amount" name="cash_amount" class="form-control case_cost" value="" />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="payment_method_w echeck_payment" <?php if(isset(Session::get('data')['payment_method']) && Session::get('data')['payment_method'] == 'E-Check') { echo 'style="display:block;"'; }?>>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Routing Number <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input placeholder="Routing Number" name="routing_number" class="form-control" value="<?php if(isset(Session::get('data')['routing_number'])) { echo Session::get('data')['routing_number']; }?>" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Bank Account Number <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input placeholder="Bank Account Number" name="account_number" class="form-control" value="<?php if(isset(Session::get('data')['account_number'])) { echo Session::get('data')['account_number']; }?>" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Amount <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <span class="curruncy_symbol">$</span>
                                  <input type="number" min="1" placeholder="Amount" name="check_amount" class="form-control case_cost" value="<?php if(isset(Session::get('data')['check_amount'])) { echo Session::get('data')['check_amount']; }?>" />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="payment_method_w moneyorder_payment">
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Refrence Number <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input placeholder="Refrence Number" name="refrence_number" class="form-control" value="" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Amount <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <span class="curruncy_symbol">$</span>
                                  <input type="number" min="1" placeholder="Amount" name="moneyorder_amount" class="form-control case_cost" value="" />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row form-group"> 
                          <div class="col-sm-12 col-md-12">
                            <input type="hidden" name="redirect_url"  value="{{ url('firm/client/client_invoice') }}/{{$client->id}}">
                            <input type="hidden" name="redirect_url1"  value="{{ url('firm/client/client_scheduled') }}/{{$client->id}}">
                            <input type="hidden" name="id"  value="<?php if(!empty($qbinvoice)) { echo $qbinvoice->id; } ?>">
                            <input type="hidden" name="lead_id" value="{{$client->id}}">
                            <input type="hidden" name="ctype" value="Client">
                            @csrf
                            <button class="btn btn-primary" value="1" type="submit" name="create_firm_lead">
                            <span>Process Payment</span>
                            </button>
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
  </div>
  
</section>
@endsection


@push('footer_script')
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.exp_date').mask('00/0000');
  $('.nav-link').on('click', function(){
    var h = $(this).attr('href');
    window.location.href = h;
  });
  $('.payment_method').on('change', function(){
    var v = $(this).val();
    $('.payment_method_w').hide();
    $('.payment_method_w input').prop('required', false);
    if(v == 'Credit Card') {
      $('.card_payment').show();
      $('.card_payment input').prop('required', true);
    }
    else if(v == 'Cash') {
      $('.cash_payment').show();
      $('.cash_payment input').prop('required', true);
    }
    else if(v == 'E-Check') {
      $('.echeck_payment').show();
      $('.echeck_payment input').prop('required', true);
    }
    else if(v == 'Money Order') {
      $('.moneyorder_payment').show();
      $('.moneyorder_payment input').prop('required', true);
    }
  });
  $('select.invoice_number').on('change', function(){
    var v = $(this).val();
    if(v == '') {
      $('.invoice_no_err').show();
    }
    else {
      $('.invoice_no_err').hide();
    }
    var amount = $('select.invoice_number option[value="'+v+'"]').data('amount');
    var paid_amount = $('select.invoice_number option[value="'+v+'"]').data('paid_amount');
    var t = parseInt(amount);
    if(paid_amount) {
      t = parseInt(amount)-parseInt(paid_amount);
    }
    $('.case_cost').attr('max', t);
    console.log(amount, paid_amount, t);
    if(t) {
      var t1 = '$'+t.toFixed(2);
      $('.outstanding_amount').show();
      $('.outstanding_amount span').text(t1);
    }
    else {
      $('.outstanding_amount').hide();
    }
    $('input[name="id"]').val(v);
  });
  $('#payment-form').on('submit', function(){
    var v = $('select.invoice_number').val();
    if(v == '') {
      $('.invoice_no_err').show();
    }
    else {
      $('.invoice_no_err').hide();
    }
  });
});
</script>
@endpush 