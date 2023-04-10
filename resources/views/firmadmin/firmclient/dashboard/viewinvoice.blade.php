@extends('firmlayouts.admin-master')

@section('title')
Invoice
@endsection

@push('header_styles')
<style type="text/css">
  .payment_method_w {
    display: none;
  }
  .curruncy_symbol {
    position: absolute;
    left: 28px;
    top: 11px;
  }  
  .case_cost {
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
<section class="section">
  <div class="section-header">
    <h1><a href="{{ url('firm/firmclient/billing/invoice') }}"><span>Invoice / </span></a> Detail</h1>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/firmclient/billing/invoice') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
             <h4></h4>
             <a href="#" class="btn btn-primary card-header-action printinvoice">
                Print
             </a>
             <a href="#payment-page" class="btn btn-primary card-header-action" onclick="convert_HTML_To_PDF();">
              Download
             </a>
          </div>
          
          <div class="card-body">
            <div class="row">
              <div class="col-md-12 cli-invoice"> 
                <div class="payment-page" id="payment-page" style="background: #fff; padding: 50px 25px; height: 100%;">

                  <div class="row">
                    <div class="col-md-6">
                      <?php 
                      $theme_logo = get_user_meta($firm->uid, 'theme_logo');

                      if(!empty($theme_logo) && $theme_logo != '[]') { ?>
                        <img src="{{asset('storage/app')}}/{{$theme_logo}}" alt="logo" width="200">
                      <?php } else { ?>
                        <img src="{{ asset('assets/img/tila-logo.png') }}" alt="logo" width="200">
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
                
                  <div class="amount-box-auto1">
                    <h4>Payment History</h4> 
                    <div class="table-responsive" style="overflow: hidden;">
                      <table class="table table-striped table-hover table-md">
                        <tbody>
                          <tr>
                            <th>Invoice Number</th>
                            <th>Total Amount</th>
                            <th>Payment Received</th>
                            <th>Outstanding Amount</th>
                            <th>Status</th>
                            <th>Paid Date</th>
                          </tr>
                          <?php 
                          if(!empty($transaction)) {
                            $amt2 = $invoice->amount;
                            $OutstandingA = 0;
                            foreach ($transaction as $k => $v) {
                              $amt2 = $amt2-($v->amount/100);
                             ?>
                            <tr>
                              <td>
                                #{{$invoice->id}}
                              </td>
                              <td>
                                ${{ number_format($invoice->amount, 2) }}
                              </td>
                              <td>
                                ${{ number_format(($v->amount/100), 2) }}
                              </td>
                              <td>
                                ${{ number_format($amt2, 2) }}
                              </td>
                              <td>
                                <?php 
                                if($v->amount) {
                                    echo 'Paid';
                                }
                                else {
                                    echo 'Skipped';
                                }
                                ?>
                              </td>
                              <td>
                                {{ date('m/d/Y', strtotime($v->created_at)) }}
                              </td>
                            </tr>
                          <?php } } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <?php if($invoice->amount > $invoice->paid_amount) { ?>
                  <form action="{{ url('firm/firmclient/billing/payForInvoice') }}" method="post" enctype="multipart/form-data">
                    <div class="row form-group">
                      <div class="col-sm-7 col-md-7">
                        <div class="row">
                          <label class="col-form-label col-md-4 col-sm-4">Method Of Payment <span style="color: red"> *</span>
                          </label>
                          <div class="col-sm-6 col-md-6">
                            <select placeholder="Destination Account" name="payment_method" class="form-control payment_method" required="required">
                              <option value="">Select</option> 
                              <option value="Credit Card" <?php if(isset(Session::get('data')['payment_method']) && Session::get('data')['payment_method'] == 'Credit Card') { echo 'selected="selected"'; }?>>Credit Card</option> 
                              <option value="E-Check" <?php if(isset(Session::get('data')['payment_method']) && Session::get('data')['payment_method'] == 'E-Check') { echo 'selected="selected"'; }?>>E-Check</option> 
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
                              <input type="number" min="1" placeholder="Amount" name="amount" class="form-control case_cost" value="<?php if(isset(Session::get('data')['amount'])) { echo Session::get('data')['amount']; }?>" max="{{$invoice->amount-$invoice->paid_amount}}"/>
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
                              <input type="number" min="1" placeholder="Amount" name="check_amount" class="form-control case_cost" value="<?php if(isset(Session::get('data')['check_amount'])) { echo Session::get('data')['check_amount']; }?>" max="{{$invoice->amount-$invoice->paid_amount}}"/>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="payment-form-card" id="card-element">
                      <div class="row">
                        <div class="col-md-12">
                          @csrf
                          <input type="hidden" name="id"  value="{{$invoice->id}}">
                          <input type="hidden" name="lead_id" value="{{$client->id}}">
                          <input type="hidden" name="ctype" value="Client">
                          <button class="btn btn-primary" value="1" type="submit" name="create_firm_lead">
                            <span>Process Payment</span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     </div>
  </div>
</section>
<div id="elementH"></div>
@endsection

@push('footer_script')
<script src="{{ asset('assets/js/jspdf.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.exp_date').mask('00/0000');
    $('.printinvoice').on('click', function(e){
      e.preventDefault();
      var divContents = $(".payment-page").html();
      var printWindow = window.open('', '', 'height=400,width=800');
      printWindow.document.write('<html><head><title>Invoice Details</title>');
      printWindow.document.write('</head><body><div class="payment-page" id="payment-page" style="background: #fff; padding: 50px 25px;">');
      printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"><link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}">');
      printWindow.document.write(divContents);
      printWindow.document.write('</div></body></html>');
      printWindow.document.close();
      printWindow.print();
    });

    $('.payment_method').on('change', function(){
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
  function convert_HTML_To_PDF() {
    setTimeout(function(){
      var pdf = new jsPDF('p', 'pt', 'a4');
      pdf.addHTML($('#payment-page')[0], 0, 0, function () {
         pdf.save('invoice.pdf');
      });
    }, 10);
  }
</script>

@endpush 
