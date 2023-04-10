@extends('firmlayouts.admin-master')

@section('title')
Invoice
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
</style>
@endpush  

@section('content')
<section class="section client-listing-details">
  <div class="section-header">
    <h1><a href="{{route('firm.billing')}}"><span>Firm Billing / </span></a> Accept Payment</h1>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">          
          
          <div class="card-body">
            <div class="profile-new-client">
                <div class="profle-text-section">
                  <div class="task-tabbtn-box">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link" data-status="0" id="transactions-tab" data-toggle="tab" href="{{ url('firm/transactions') }}" role="tab" aria-controls="transactions" aria-selected="true">Transactions</a>
                      </li>
                      <?php if($firm->account_type == 'CMS') { ?>
                      <li class="nav-item">
                        <a class="nav-link" data-status="0" id="summary-tab" data-toggle="tab" href="{{ url('firm/billing') }}" role="tab" aria-controls="summary" aria-selected="true">Summary</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-status="1" id="invoices-tab" data-toggle="tab" href="{{ url('firm/billing/invoice') }}" role="tab" aria-controls="invoices" aria-selected="false">Invoices</a>
                      </li> 
                      <li class="nav-item">
                        <a class="nav-link" data-status="1" id="scheduled-tab" data-toggle="tab" href="{{ url('firm/billing/scheduled') }}" role="tab" aria-controls="scheduled" aria-selected="false">Scheduled</a>
                      </li> 
                      <li class="nav-item">
                        <a class="nav-link active" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/billing/acceptpayment') }}" role="tab" aria-controls="acceptpayment" aria-selected="false">Accept Payment</a>
                      </li>  
                      <?php } ?>
                    </ul>
                  </div>
                  <div class="task-tabcontent-box">
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="scheduled" role="tabpanel" aria-labelledby="home-tab">
                        <form action="{{ url('firm/client/payForClientInvoice') }}" method="post" class="needs-validation" id="payment-form" enctype="multipart/form-data" novalidate="">
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Select Client
                                </label> 
                                <div class="col-sm-6 col-md-6">
                                  <select placeholder="Search Invoice Number" name="selectclient" class="selectpicker selectclient" data-live-search="true">
                                    <option value="">Select</option> 
                                    <?php 
                                    $arr = array();
                                    if(!empty($invoice)) {
                                      foreach ($invoice as $k => $i) {
                                        if(!in_array($i->client_id, $arr)) {
                                          $arr[] = $i->client_id;
                                          echo '<option value="'.$i->client_id.'">'.$i->client_name.'</option>';
                                        }
                                      }
                                    }
                                    ?>
                                  </select>
                                  <div class="invalid-feedback">Invoice is required!</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Search Invoice Number <span style="color: red"> *</span>
                                </label> 
                                <div class="col-sm-6 col-md-6">
                                  <select placeholder="Search Invoice Number" name="invoice_number" class=" invoice_number" required="required" data-live-search="true">
                                    <option value="">Select</option> 
                                    <?php 
                                    $outamt = 0.00;
                                    if(!empty($invoice)) {
                                      foreach ($invoice as $k => $i) {
                                        $sl = '';
                                        if(isset(Session::get('data')['id']) && isset(Session::get('data')['id']) == $i->id) {
                                          $sl = 'selected';
                                          $outamt = number_format(($i->amount-$i->paid_amount), 2);
                                        }
                                        if($i->amount == $i->paid_amount) {
                                          continue;
                                        }
                                        echo '<option value="'.$i->id.'" data-cid="'.$i->client_id.'" data-amount="'.$i->amount.'" data-paid_amount="'.$i->paid_amount.'" '.$sl.'>Invoice #'.$i->id.' '.$i->client_name.'</option>';
                                        
                                      }
                                    }
                                    ?>
                                  </select>
                                  <div class="invalid-feedback invoice_no_err">Invoice is required!</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group outstanding_amount" <?php if(!isset(Session::get('data')['id'])) { echo 'style="display: none;"'; } ?>>
                            <div class="col-sm-7 col-md-7">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Outstanding Amount</label>
                                <div class="col-sm-6 col-md-6">
                                  <span>
                                    ${{$outamt}}
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
                                    <input type="text" placeholder="Credit Card Number" size="20" name="card_number" class="form-control" data-stripe="number" value="<?php if(isset(Session::get('data')['card_number'])) { echo Session::get('data')['card_number']; }?>" maxlength="16"/>
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
                                    <input type="text" placeholder="mm/yyyy" name="exp_date" data-stripe="exp_month" class="form-control exp_date" value="<?php if(isset(Session::get('data')['exp_date'])) { echo Session::get('data')['exp_date']; }?>"/>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row form-group">
                              <div class="col-sm-7 col-md-7">
                                <div class="row">
                                  <label class="col-form-label col-md-4 col-sm-4">CVV <span style="color: red"> *</span></label>
                                  <div class="col-sm-6 col-md-6">
                                    <input type="text" placeholder="CVV" size="4" data-stripe="cvc" name="cvc" class="form-control" value="<?php if(isset(Session::get('data')['cvc'])) { echo Session::get('data')['cvc']; }?>"/>
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

                              <input type="hidden" name="redirect_url"  value="{{ url('firm/billing/invoice') }}">
                              <input type="hidden" name="redirect_url1"  value="{{ url('firm/client/client_scheduled') }}">
                              <input type="hidden" name="id"  value="<?php if(isset(Session::get('data')['id'])) { echo Session::get('data')['id']; } ?>">
                              <input type="hidden" name="lead_id" value="">
                              @csrf
                              <button class="btn btn-primary" value="1" type="submit" name="create_firm_lead">
                              <span>Process Payment</span>
                              </button>
                              <input type="hidden" name="isscheduleb" value="1">
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
  $('.invoice_number').selectpicker();
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
  <?php if(isset(Session::get('data')['id'])) { ?>
    var v = $('select.invoice_number').val();
    var cid = $('select.invoice_number option[value="'+v+'"]').data('cid');
    $('input[name="redirect_url"]').val("{{ url('firm/billing/invoice') }}");
    $('input[name="redirect_url1"]').val("{{ url('firm/client/client_scheduled') }}/"+cid);
  <?php } ?>
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
    var cid = $('select.invoice_number option[value="'+v+'"]').data('cid');
    $('input[name="redirect_url"]').val("{{ url('firm/billing/acceptpayment') }}");
    $('input[name="redirect_url1"]').val("{{ url('firm/client/client_scheduled') }}/"+cid);
  });
  $('select.selectclient').on('change', function(){
    var v = $(this).val();
    $('.invoice_number').val('');
    if(v == '') {
      $('select.invoice_number option').show();
    }
    else {
      $('select.invoice_number option').hide();
      var cid = $('select.invoice_number option[data-cid="'+v+'"]').show();   
    }
    $('.invoice_number').selectpicker('refresh');
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
