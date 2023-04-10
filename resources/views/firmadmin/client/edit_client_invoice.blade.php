@extends('firmlayouts.admin-master')

@section('title')
View client
@endsection
@push('header_styles')
<style type="text/css">
  .curruncy_symbol {
    position: absolute;
    left: 26px;
    top: 18px;
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
<section class="section client-listing-details">

<!--new-header open-->
  @include('firmadmin.client.client_header')
<!--new-header Close-->
  
   <div class="section-body cases-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="back-btn-new">
            <a href="{{ url('firm/client/client_invoice') }}/{{$client->id}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
           <div class="card-body">
            <br><br>
            <form action="{{ url('firm/client/update_client_invoice') }}" method="post" enctype="multipart/form-data">
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
                              if(!empty($residence_address->address_l2)) {
                                $addr .= ', '.$residence_address->address_l2;
                              }
                              if(!empty($residence_address->city)) {
                                $addr .= ', '.$residence_address->city;
                              }
                              if(!empty($residence_address->state)) {
                                $addr .= ', '.getStateName($residence_address->state);
                              }
                              if(!empty($residence_address->country)) {
                                $addr .= ', '.getCountryName($residence_address->country);
                              }
                              if(!empty($residence_address->zipcode)) {
                                $addr .= ', '.$residence_address->zipcode;
                              }
                              ?>
                              <div class="row">
                                <div class="col-md-2">
                                  <label>Name</label>
                                </div>
                                <div class="col-md-8">
                                  <input type="text" placeholder="Name" name="name" class="form-control" value="{{$invoice->client_name}}" required="required"> 
                                </div>
                              </div>
                              <div class="row mt-4">
                                <div class="col-md-2">
                                  <label>Address</label>
                                </div>
                                <div class="col-md-8">: {{$addr}}</div>
                              </div>
                          </address>
                          <!-- <address>
                            <strong>Payment Method:</strong><br>
                            {{$invoice->payment_method}}
                          </address> -->
                          <address>
                            <strong>Destination Account:</strong><br>
                            <select placeholder="Destination Account" name="destination_account" class="form-control" required="required">
                              <option value="">Select</option> 
                              <option value="Operating/Business Account" <?php if($invoice->destination_account == 'Operating/Business Account') { echo 'selected'; } ?>>Operating/Business Account</option> 
                              <option value="Trust Account" <?php if($invoice->destination_account == 'Trust Account') { echo 'selected'; } ?>>Trust Account</option> 
                            </select>
                          </address>
                        </div>
                        <div class="col-md-6 new-invoice-head">
                          <address>
                            <strong>Invoice Date:</strong><br>
                            <?php echo date('m/d/Y', strtotime($invoice->created_at)); ?><br><br>
                          </address>
                          <address>
                            <strong>Due Date:</strong><br>
                            <input type="text" placeholder="mm/dd/yyyy" name="due_date" class="form-control datepicker" value="{{$invoice->due_date}}" required="required" style="width: 120px;"><br><br>
                          </address>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row mt-4">
                    <div class="col-md-12">
                      <div class="section-title">Invoice Summary</div>
                      <!-- <p class="section-lead">All items here cannot be deleted.</p> -->
                      <div class="table-responsive" style="overflow: hidden;">
                        <table class="table table-striped table-hover table-md">
                          <tbody>
                            <tr>
                              <th>Description</th>
                              <th style="width: 250px;">Amount</th>
                              <th class="text-right">Total</th>
                            </tr>
                            <tr>
                              <td>
                                 <input type="text" placeholder="Description" name="description" class="form-control" value="{{$invoice->description}}" >
                              </td>
                              <td style="position: relative;">
                                <span class="curruncy_symbol">$</span>
                                <input type="text" placeholder="Total Amount" name="total_amount" class="form-control case_cost" value="{{$invoice->amount}}" required="required">
                              </td>
                              <td class="text-right trtotal">${{ number_format($invoice->amount, 2) }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    <div class="row mt-4">
                      <div class="col-lg-9">
                        <!-- <div class="section-title">We accept</div>
                        <p class="section-lead">The payment method that we provide is to make it easier for you to pay invoices.</p>
                        <div class="images card-img">
                          <img src="{{ asset('assets/img/visa-in.png') }}" alt="visa">
                          <img src="{{ asset('assets/img/jcb-in.png') }}" alt="jcb">
                          <img src="{{ asset('assets/img/mastercard-in.png') }}" alt="mastercard">
                        </div> -->
                      </div>
                      <div class="col-lg-3 text-right">
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">Subtotal</div>
                          <div class="invoice-detail-value totlaamount">
                            ${{ number_format($invoice->amount, 2) }}
                          </div>
                        </div>
                        <hr class="mt-2 mb-2">
                        <div class="invoice-detail-item">
                          <div class="invoice-detail-name">Total</div>
                          <div class="invoice-detail-value invoice-detail-value-lg totlaamount">${{ number_format($invoice->amount, 2) }}</div>
                        </div>
                      </div>
                    </div>
                    </div>
                  </div>

                </div>
                <hr>
              </div>
              <div class="row form-group"> 
                <div class="col-sm-12 col-md-12">
                  <input type="hidden" name="firmclient" value="{{$client->id}}">
                  <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
                  @csrf
                  <button class="btn btn-primary" value="1" type="submit" name="create_firm_lead">
                  <span>Update Invoice</span>
                  </button>
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
$(document).on('keyup', '.case_cost', function(){
  var a = $('.case_cost').val();
  var q = 1;
  var s = a*q;
  $('.trtotal').html('$'+s);
  $('.casecost').val(s);
  $('.totlaamount').html('$'+s);
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
  $('.datepicker').daterangepicker({
      startDate: '{{$invoice->due_date}}',
      timePicker: false,
      singleDatePicker: true,
      endDate: moment().startOf('hour').add(32, 'hour'),
      locale: {
        format: 'MM/DD/YYYY'
      },
      minDate: new Date()
  });
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