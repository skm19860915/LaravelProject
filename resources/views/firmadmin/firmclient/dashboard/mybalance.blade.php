@extends('firmlayouts.admin-master')

@section('title')
My Balance
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
  display: none;
}  
body div#table_filter {
  display: block;
} 
  .payment_method_w {
    display: none;
  }
  .curruncy_symbol {
    position: absolute;
    left: 30px;
    top: 11px;
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
  #Invoice_PopUp .modal-dialog {
    max-width: 650px;
  }
</style>
@endpush  

@section('content')
<section class="section firmclient-invoice">
  <div class="section-header">
    <h1><a href="{{url('firm/clientdashboard')}}"><span>Home / </span></a> My Balance</h1>
    <div class="section-header-breadcrumb">
        <a href="#" class="add-task-link invoicepopup">Payments</a>
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="profile-new-client">
              <br>
              <?php if(!empty($count['payment_info'])) { ?>
                <div class="row text-center">
                  <div class="col-md-12">
                    <h5>Your Account is set on recurring payments contact firm for any changes.</h5>
                  </div>
                </div>
                <br>
              <?php } ?>
              <div class="row text-center">
                <div class="col-md-6">
                  <h5>Balance Owed</h5>
                  <h4>{{$count['outstanding_amount']}}</h4>
                </div>
                <div class="col-md-6">
                  <h5>Payments</h5>
                  <h4>{{$count['paid_amount']}}</h4>
                </div>
              </div>
              <br>
              <?php if(!empty($count['payment_info'])) { ?>
              <div class="row text-center">
                <div class="col-md-6">
                   <h5>My Last Payment : 
                   {{ date('m/d/Y', strtotime($count['payment_info']['payment_date'])) }}</h5>
                </div>
                <div class="col-md-6">
                    <h5>Amount : ${{ number_format($count['payment_info']['paid_amount'], 2) }}</h5>
                </div>
              </div>
              <div class="row text-center">
                <div class="col-md-6">
                   <h5>Next Payment Due : 
                   {{ $count['schedule_invoice']['next_payment'] }}</h5>
                </div>
                <div class="col-md-6">
                    <h5>Amount : ${{ number_format($count['schedule_invoice']['recurring_amount'], 2) }}</h5>
                </div>
              </div>
              <br>
              <?php } ?>
              <h4>Invoice History</h4>
              <div class="table-responsive table-invoice">
                <table class="table table table-bordered table-striped"  id="table" >
                  <thead>
                    <tr>
                      <th style="display: none;"> Id</th>
                      <th> Invoice No.</th>
                      <!-- <th> Client Name </th> -->
                      <th> Description</th>
                      <th> Amount</th>
                      <th> Payment Received</th>
                      <th> Create Date </th>
                      <th> Scheduled</th>
                      <th> Status</th>
                      <th> Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
     </div>
  </div>
</section>
<!-- Modal -->
<div id="Invoice_PopUp" class="modal fade invoice_modal-firmclient" role="dialog">
  <div class="modal-dialog">

    <!-- Pay Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Pay For Invoice</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('firm/firmclient/billing/payForInvoice1') }}" method="post" enctype="multipart/form-data" id="invoicepaymentform">
          <div class="row form-group">
            <div class="col-sm-12 col-md-12">
              <div class="row">
                <label class="col-form-label col-md-4 col-sm-4">Search Invoice Number <span style="color: red"> *</span>
                </label> 
                <div class="col-sm-6 col-md-6">
                  <select placeholder="Search Invoice Number" name="invoice_number" class="selectpicker invoice_number" required="required" data-live-search="true">
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($count['invoice'])) {
                      foreach ($count['invoice'] as $k => $i) {
                        if($i->amount-$i->paid_amount) {
                          echo '<option value="'.$i->id.'" data-amount="'.$i->amount.'" data-paid_amount="'.$i->paid_amount.'" data-client_name="'.$i->client_name.'" data-description="'.$i->description.'">Invoice #'.$i->id.'-'.$i->description.'</option>';
                        }
                      }
                    }
                    ?>
                  </select>
                  <div class="invalid-feedback invoice_no_err">Invoice is required!</div>
                </div>
              </div>
            </div>
          </div>
          <div class="row form-group outstanding_amount" style="display: none;">
            <div class="col-sm-12 col-md-12">
              <div class="row">
                <label class="col-form-label col-md-4 col-sm-4">Client Name</label>
                <div class="col-sm-6 col-md-6">
                  <label class="i_clientname col-form-label"></label>
                </div>
              </div>
              <div class="row">
                <label class="col-form-label col-md-4 col-sm-4">Description</label>
                <div class="col-sm-6 col-md-6">
                  <label class="i_decription col-form-label"></label>
                </div>
              </div>
              <div class="row">
                <label class="col-form-label col-md-12 col-sm-12">Payment History</label>
                <div class="col-sm-12 col-md-12">
                  <table class="table table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Total :</th>
                        <th class="total_amount">$0</th>
                        <th>Paid :</th>
                        <th class="paid_amount">$0</th>
                        <th>Due : </th>
                        <th class="due_amount">$0</th>
                      </tr>
                    </thead>
                  </table>
                  <h5 class="text-center">Due : <span class="due_amount"></span></h5>
                </div>
              </div>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-12 col-md-12">
              <div class="row">
                <label class="col-form-label col-md-4 col-sm-4">Amount <span style="color: red"> *</span></label>
                <div class="col-sm-6 col-md-6">
                  <span class="curruncy_symbol">$</span>
                  <input type="number" min="1" placeholder="Amount" name="amount" class="form-control case_cost" value="" max="" required="required" />
                </div>
              </div>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-12 col-md-12">
              <div class="row">
                <label class="col-form-label col-md-4 col-sm-4">Method Of Payment <span style="color: red"> *</span>
                </label>
                <div class="col-sm-6 col-md-6">
                  <select placeholder="Destination Account" name="payment_method" class="form-control payment_method" required="required">
                    <option value="">Select</option> 
                    <option value="Credit Card">Credit Card</option> 
                    <option value="E-Check">E-Check</option> 
                  </select>
                  <div class="invalid-feedback">Method Of Payment is required!</div>
                </div>
              </div>
            </div>
          </div>
          <div class="payment_method_w card_payment">
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Name of Credit Card <span style="color: red"> *</span></label>
                  <div class="col-sm-6 col-md-6">
                    <input placeholder="Name of Credit Card" name="name_of_credit_card" class="form-control" value="" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Credit Card Number <span style="color: red"> *</span></label>
                  <div class="col-sm-6 col-md-6">
                    <input type="text" placeholder="Credit Card Number" size="20" name="card_number" class="form-control" data-stripe="number" maxlength="16" value="" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">ZipCode <span style="color: red"> *</span></label>
                  <div class="col-sm-6 col-md-6">
                    <input type="text" placeholder="ZipCode" size="6" name="address_zip" data-stripe="address_zip" class="form-control" value=""/>
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Expiration Date <span style="color: red"> *</span></label>
                  <div class="col-sm-6 col-md-6">
                    <input type="text" placeholder="mm/yyyy" name="exp_date" data-stripe="exp_date" class="form-control exp_date" value=""/>
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">CVV <span style="color: red"> *</span></label>
                  <div class="col-sm-6 col-md-6">
                    <input type="text" placeholder="CVC" size="4" data-stripe="cvc" name="cvc" class="form-control" value=""/>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="payment_method_w echeck_payment">
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Routing Number <span style="color: red"> *</span></label>
                  <div class="col-sm-6 col-md-6">
                    <input placeholder="Routing Number" name="routing_number" class="form-control" value="" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Bank Account Number <span style="color: red"> *</span></label>
                  <div class="col-sm-6 col-md-6">
                    <input placeholder="Bank Account Number" name="account_number" class="form-control" value="" />
                  </div>
                </div>
              </div>
            </div>
            
          </div>
          <div class="payment-form-card" id="card-element">
            <div class="row">
              <div class="col-md-12">
                @csrf
                <input type="hidden" name="id"  value="0">
                <input type="hidden" name="lead_id" value="">
                <input type="hidden" name="ctype" value="Client">
                <button class="btn btn-primary payinvoice" value="1" type="submit" name="create_firm_lead">
                  <span>Submit Payment</span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection

@push('footer_script')
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
var index_url = "{{route('firm.firmclient.billing.getInvoiceData')}}";
console.log(index_url);
var srn = 0;
srn++;
$('#table').DataTable({
  processing: true,
  serverSide: true,
  ajax: index_url,
  "order": [[ 0, "desc" ]],
  columns: [
    { data: 'id', name: srn},
    { data: 'id', name: 'id' },
    // { data: 'name', name: 'name'},
    { data: 'description', name: 'description'},
    { data: 'amount', name: 'amount'},
    { data: 'paid_amount', name: 'paid_amount'},
    { data: 'created_at', name: 'created_at'},
    { data: 'scheduled', name: 'scheduled'},
    { data: 'st', name: 'st'},
    { data: null,
      render: function(data){
        var view_button='';
        // if(data.status == 0) {
            view_button = ' <a href="{{url('firm/firmclient/billing/invoice/viewinvoice')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="Pay this invoice"><img src="{{ url('/') }}/assets/images/icon/Group 557.svg"></a>';
        // }
        return view_button;

      }, orderable: "false"
    },
  ],
});
$(document).ready(function(){
  $('.exp_date').mask('00/0000');
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
  $('select.invoice_number').on('change', function(){
    var v = $(this).val();
    if(v == '') {
      $('.invoice_no_err').show();
    }
    else {
      $('.invoice_no_err').hide();
    }
    var client_name = $('select.invoice_number option[value="'+v+'"]').data('client_name');
    var description = $('select.invoice_number option[value="'+v+'"]').data('description');
    var amount = $('select.invoice_number option[value="'+v+'"]').data('amount');
    var paid_amount = $('select.invoice_number option[value="'+v+'"]').data('paid_amount');
    var t = parseInt(amount);
    if(paid_amount) {
      t = parseInt(amount)-parseInt(paid_amount);
    }
    $('.case_cost').attr('max', t);
    console.log(amount, paid_amount, t);
    if(t) {
      var ta = '$'+amount.toFixed(2);
      var pa = '$'+paid_amount.toFixed(2);
      var da = '$'+t.toFixed(2);
      $('.outstanding_amount').show();
      $('.due_amount').text(da);
      $('.total_amount').text(ta);
      $('.paid_amount').text(pa);
      $('.i_clientname').text(client_name);
      $('.i_decription').text(description);
    }
    else {
      $('.outstanding_amount').hide();
    }
    $('input[name="id"]').val(v);
  });
  $('.payinvoice').on('click', function(e){
      // e.preventDefault();

      // var invoice_id = $('input[name="invoice_id"]').val();
      // var _token = $('input[name="_token"]').val();
      // var card_number = $('input[name="card_number"]').val();
      // var exp_month = $('input[name="exp_month"]').val();
      // var exp_year = $('input[name="exp_year"]').val();
      // var cvc = $('input[name="cvc"]').val();
      // var address_zip = $('input[name="address_zip"]').val();
      // $.ajax({
      //   type:"post",
      //   url:"{{ url('firm/firmclient/billing/payForInvoice') }}",
      //   data: {
      //   	invoice_id:invoice_id, 
      //   	_token:_token,
      //   	card_number:card_number,
      //   	exp_month:exp_month,
      //   	exp_year:exp_year,
      //   	cvc:cvc,
      //   	address_zip:address_zip
      //   },
      //   success:function(res)
      //   {      
      //     alert('Paid successfully'); 
      //     window.location.href = "{{ url('firm/firmclient/billing/invoice') }}";
      //   }
      // });
    });
})  
$(document).on('click', '.invoicepopup', function(e){
  e.preventDefault();
  $("#Invoice_PopUp").modal('show');
});
</script>
@endpush 
