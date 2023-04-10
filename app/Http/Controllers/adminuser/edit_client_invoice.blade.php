@extends('firmlayouts.admin-master')

@section('title')
View client
@endsection
@push('header_styles')
<style type="text/css">
  .curruncy_symbol {
    position: absolute;
    left: 16px;
    top: 34px;
  }  
  .case_cost {
    padding-left: 25px !important;
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
           <div class="card-body">

            <form action="{{ url('firm/client/update_client_invoice') }}" method="post" enctype="multipart/form-data">
              <div class="invoice p-0">
                <div class="invoice-print">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="invoice-title">                       
                        <div class="invoice-number">
                          {{$client->name}}
                        </div>
                      </div>                    
                      <div class="row">
                        <div class="col-md-6 new-invoice-head">
                          <address>
                            <strong>Billed To:</strong><br>
                              {{$firm->firm_name}}<br>
                              {{$firm->email}}
                              <!-- <br>
                              1234 Main<br>
                              Apt. 4B<br>
                              Bogor Barat, Indonesia -->
                          </address>
                        </div>
                        <div class="col-md-6 text-md-right new-invoice-head">
                          <address>
                            <strong>Shipped To:</strong><br>
                            {{$client->name}}<br>
                            <?php 
                            $residence_address = json_decode($client->residence_address);
                            if(!empty($residence_address->address)) {
                              echo $residence_address->address;
                            }
                            if(!empty($residence_address->city)) {
                              echo '<br> '.getCityName($residence_address->city);
                            }
                            if(!empty($residence_address->state)) {
                              echo ', '.getStateName($residence_address->state);
                            }
                            if(!empty($residence_address->country)) {
                              echo ', '.getCountryName($residence_address->country);
                            }
                            ?>
                          </address>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 new-invoice-head">
                          <address>
                            <strong>Payment Method:</strong><br>
                            <select class="form-control" name="payment_method" required="required" style="width: 140px;">
                              <option value="">Select One</option>
                              <option value="Manual" <?php if($invoice->payment_method == 'Manual') { echo "selected='selected'";} ?>>Manual</option>
                              <option value="Card" <?php if($invoice->payment_method == 'Card') { echo "selected='selected'";} ?>>Card via LawPay</option>
                            </select>
                          </address>
                        </div>
                        <div class="col-md-6 text-md-right new-invoice-head">
                          <address>
                            <strong>Order Date:</strong><br>
                            <?php echo date('M d, Y'); ?><br><br>
                          </address>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row mt-4">
                    <div class="col-md-12">
                      <div class="section-title">Order Summary</div>
                      <p class="section-lead">All items here cannot be deleted.</p>
                      <div class="table-responsive">
                        <table class="table table-striped table-hover table-md">
                          <tbody>
                            <tr>
                              <th data-width="40" style="width: 40px;">#</th>
                              <th class="text-center">Item</th>
                              <th class="text-center" style="width: 250px;">Price</th>
                              <th class="text-center" style="width: 150px;">Quantity</th>
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
                              <td class="text-center">
                                <input type="number" name="invoice_items[item_qty][]" class="form-control" value="<?php echo $invoice_items->item_qty[$k]; ?>" required="required">
                              </td>
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
                        <div class="section-title">Payment Method</div>
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


<div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">  
      <div class="col-md-12">
        Note 
        <br>
      </div>
      <div class="col-md-12">
        <textarea name="note" class="form-control" style="height: 150px;"></textarea>
      </div>
    </div>
    <div class="row">  
      <div class="col-md-12 text-right">
        <input type="hidden" name="client_id" value="{{ $client->id }}" >  
        @csrf
        <input type="submit" name="save" value="Create Client Note" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>
@endsection


@push('footer_script')

<script type="text/javascript">
$(document).ready(function(){
  $("#fire-modal-2").fireModal({title: 'Add Client Notes', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var client_id = $('input[name="client_id"]').val();
      var note = $('textarea[name="note"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/client/add_notes') }}",
        data: {_token:_token, note:note, client_id:client_id},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/client/view_notes') }}/{{ $client->id }}";
          }
          else {
            alert('Mendatory fields are required!')
          }
          console.log(res);
        }
      });
    });
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

//================ Edit user ============//

</script>
<style type="text/css">
  .card .card-header .btn {
    margin-top: 1px;
    padding: 2px 12px;
  }
</style>
@endpush 