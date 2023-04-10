@extends('firmlayouts.admin-master')

@section('title')
Invoice
@endsection

@push('header_styles')
<style type="text/css">
  .curruncy_symbol {
    position: absolute;
    left: 25px;
    top: 34px;
  }  
  .case_cost {
    padding-left: 25px !important;
  }
  .invoice-detail-item {
    padding-right: 15px;
  }
</style>
@endpush  

@section('content')
<section class="section invoice_client invoice_client_edit">
  <div class="section-header">
    <h1><a href="{{ url('firm/invoice') }}"><span>Invoice / </span></a> Edit</h1>
    <!--/*<div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.billing')}}">Billing</a>
      </div>
    </div>*/-->
  </div>
  <div class="section-body invoice-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
                <a href="{{ url('firm/invoice') }}">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
               </div>
            <!-- <h4>Invoice</h4> -->
            <div class="card-header-action">

              
            </div>
          </div>
          
          <div class="card-body">
            <br><br>
            <form action="{{ url('firm/billing/invoice/update_invoice') }}" method="post" enctype="multipart/form-data">
              <div class="invoice">
                <div class="invoice-print">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="invoice-title">                       
                        <div class="invoice-number">
                          
                        </div>
                      </div>                    
                      <div class="row">
                        <div class="col-md-6 new-invoice-head">
                          <address>
                            <strong>Bill To:</strong><br>
                              <?php 
                              $addr = '';
                              $residence_address = json_decode($client->residence_address);
                              if(!empty($residence_address->address)) {
                                $addr .= $residence_address->address;
                              }
                              if(!empty($residence_address->city)) {
                                $addr .= ', '.getCityName($residence_address->city);
                              }
                              if(!empty($residence_address->state)) {
                                $addr .= ', '.getStateName($residence_address->state);
                              }
                              if(!empty($residence_address->country)) {
                                $addr .= ', '.getCountryName($residence_address->country);
                              }
                              ?>
                              <div class="row mb-4">
                                <div class="col-md-2 col-form-label">
                                  <label>Name</label>
                                </div>
                                <div class="col-md-8">
                                  <input type="text" name="client_name" value="{{$invoice->client_name}}" class="form-control" required>
                                </div>
                              </div>
                              <div class="row mb-4">
                                <div class="col-md-2 col-form-label">
                                  <label>Email</label>
                                </div>
                                <div class="col-md-8">
                                  <input type="text" name="client_email" value="{{$client->email}}" class="form-control" readonly="readonly">
                                </div>
                              </div>
                              <div class="row mb-4">
                                <div class="col-md-2 col-form-label">
                                  <label>Address</label>
                                </div>
                                <div class="col-md-8">
                                  <textarea name="client_address" class="form-control" required>{{$invoice->client_address}}</textarea>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-2 col-form-label">
                                  <label>Tax ID</label>
                                </div>
                                <div class="col-md-8">
                                  <input type="text" name="tax_id" value="{{$invoice->tax_id}}" class="form-control">
                                </div>
                              </div>
                          </address>
                        </div>
                        <div class="col-md-6 new-invoice-head">
                          <address>
                            <strong>Invoice Type:</strong><br>
                            <select class="form-control" name="payment_method" required="required" style="width: 140px;">
                              <option value="">Select One</option>
                              <option value="Manual" <?php if($invoice->payment_method == 'Manual') { echo "selected='selected'";} ?>>Manual</option>
                              <option value="Card" <?php if($invoice->payment_method == 'Card') { echo "selected='selected'";} ?>>Card via LawPay</option>
                            </select>
                          </address>
                          <address>
                            <strong>Invoice Date:</strong><br>
                            <?php echo date('M d, Y', strtotime($invoice->created_at)); ?><br><br>
                          </address>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row mt-4">
                    <div class="col-md-12">
                      <div class="section-title">Invoice Summary</div>
                      <p class="section-lead">All items here cannot be deleted.</p>
                      <div class="table-responsive" style="overflow: hidden;">
                        <table class="table table-striped table-hover table-md">
                          <tbody>
                            <tr>
                              <th data-width="40" style="width: 40px;">#</th>
                              <th class="text-center">Item</th>
                              <th class="text-center" style="width: 250px;">Price</th>
                              <!-- <th class="text-center" style="width: 150px;">Quantity</th> -->
                              <th class="text-right">Totals</th>
                              <th class="text-right"></th>
                            </tr>
                            <?php 
                            $invoice_items = json_decode($invoice->invoice_items); 
                            foreach ($invoice_items->item_name as $k => $item) { ?>
                            <tr>
                              <td><?php echo $k+1; ?></td>
                              <td class="text-center">
                                <input type="text" name="invoice_items[item_name][]" class="form-control" value="<?php echo $invoice_items->item_name[$k]; ?>" required="required">
                              </td>
                              <td class="text-center" style="position: relative;">
                                <span class="curruncy_symbol" style="top:19px;">$</span>
                                <input type="number" name="invoice_items[item_cost][]" class="form-control case_cost" value="<?php echo $invoice_items->item_cost[$k]; ?>" required="required">
                              </td>
                              <!-- <td class="text-center">
                                
                              </td> -->
                              <input type="hidden" name="invoice_items[item_qty][]" class="form-control" value="<?php echo $invoice_items->item_qty[$k]; ?>" required="required">
                              <td class="text-right trtotal">$<?php echo $invoice_items->item_cost[$k]*$invoice_items->item_qty[$k]; ?></td>
                              <td>
                                <a href="#" class="btn btn-primary removetr"><i class="fa fa-times"></i></a>
                              </td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                        <a href="#" class="btn btn-primary addnewitem"><i class="fa fa-plus"></i> Add new</a>
                      </div>
                    <div class="row mt-4">
                      <div class="col-lg-9">
                        <div class="section-title">We accept</div>
                        <p class="section-lead">The payment method that we provide is to make it easier for you to pay invoices.</p>
                        <div class="images card-img">
                          <img src="{{ asset('assets/img/visa-in.png') }}" alt="visa">
                          <img src="{{ asset('assets/img/jcb-in.png') }}" alt="jcb">
                          <img src="{{ asset('assets/img/mastercard-in.png') }}" alt="mastercard">
                          <!-- <img src="{{ asset('assets/img/paypal.png') }}" alt="paypal"> -->
                        </div>
                      </div>
                      <div class="col-lg-3 text-right">
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">Subtotal</div>
                          <div class="invoice-detail-value totlaamount">
                            ${{$invoice->amount}}
                          </div>
                        </div>
                        <hr class="mt-2 mb-2">
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">Total</div>
                          <div class="invoice-detail-value invoice-detail-value-lg totlaamount">${{$invoice->amount}}</div>
                        </div>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="text-md-left generate-btn">
                  @csrf
                  <input type="hidden" name="casecost" class="casecost" value="{{$invoice->amount}}" />
                  <input type="hidden" name="firmclient" value="{{$client->id}}" />
                  <input type="hidden" name="id" value="{{$invoice->id}}" />
                  <button class="btn btn-primary btn-icon icon-left" type="submit">Save Invoice</button>
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
<script type="text/javascript">
$(document).on('keyup', 'input[name="invoice_items[item_cost][]"], input[name="invoice_items[item_qty][]"]', function(){
  var v = 0;
  $('table.table tr').each(function(){
    var a = $(this).find('input[name="invoice_items[item_cost][]"]').val();
    var q = $(this).find('input[name="invoice_items[item_qty][]"]').val();
    var s = a*q;
    if(s) {  
        v = v+s;
        $(this).find('.trtotal').html('$'+s);
    }
  });
  $('.casecost').val(v);
  $('.totlaamount').html('$'+v);
});
$(document).on('click', '.removetr', function(e){
  e.preventDefault();
  $(this).closest('tr').remove();
  var v = 0;
  $('table.table tr').each(function(){
    var a = $(this).find('input[name="invoice_items[item_cost][]"]').val();
    var q = $(this).find('input[name="invoice_items[item_qty][]"]').val();
    var s = a*q;
    if(s) {  
        v = v+s;
        $(this).find('.trtotal').html('$'+s);
    }
  });
  $('.casecost').val(v);
  $('.totlaamount').html('$'+v);
});
$(document).ready(function(){
  
  $('.addnewitem').on('click', function(e){
    e.preventDefault();
    var n = $(this).prev('table').find('tbody tr').length;
    var tr = '<tr><td>'+n+'</td>';
      tr += '<td><input type="text" name="invoice_items[item_name][]" class="form-control" value="" required="required"></td>';
      tr += '<td class="text-center" style="position: relative;">';
      tr += '<span class="curruncy_symbol" style="top:22px;">$</span>';
      tr += '<input type="number" name="invoice_items[item_cost][]" class="form-control case_cost" value="" required="required">';
      tr += '</td><td class="text-center">';
      tr += '<input type="number" name="invoice_items[item_qty][]" class="form-control" value="1" required="required"></td>';
      tr += '<td class="text-right trtotal">$0</td><td>';
      tr += '<a href="#" class="btn btn-primary removetr"><i class="fa fa-times"></i></a></td></tr>';
    $(this).prev('table').find('tbody').append(tr);
  })
});  
</script>
@endpush 
