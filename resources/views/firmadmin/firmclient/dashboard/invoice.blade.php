@extends('firmlayouts.admin-master')

@section('title')
Invoice
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
  display: none;
}  
body div#table_filter {
  display: block;
} 
</style>
@endpush  

@section('content')
<section class="section firmclient-invoice">
  <div class="section-header">
    <h1>Invoice</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="profile-new-client">
              <div class="table-responsive table-invoice">
                <table class="table table table-bordered table-striped"  id="table" >
                  <thead>
                    <tr>
                      <th style="display: none;"> Id</th>
                      <th> Client name </th>
                      <th>Description</th>
                      <th> Invoice No.</th>
                      <th> Amount</th>
                      <th> create date </th>
                      <th> Payment Method</th>
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
        <form action="{{ url('firm/document_request/pay_for_translation') }}" method="get" id="payment-form" enctype="multipart/form-data"> 
          <div class="payment-form-card" id="card-element">
               <h4 class="provided_cost"></h4>
               <div class="row">
                <div class="col-md-12"><div class="payment-input">
                  <input type="text" placeholder="Card Number" size="20" name="card_number"/></div></div>
               </div>
               <div class="row">
                <div class="col-md-6 col-sm-6"><div class="payment-input">
                  <input type="text" placeholder="Expiring Month" name="exp_month"/>
                </div>
              </div>
                <div class="col-md-6 col-sm-6"><div class="payment-input">
                  <input type="text" placeholder="Expiring Year" size="2" name="exp_year">
                </div>
              </div>
                
               </div>
               <div class="row">
                <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="CVV Code" size="4" name="cvc"/></div></div>
                <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="Postal Code" size="6" name="address_zip"/></div></div>
                
               </div>
               

               
              </div>
          <div class="row">  
            <div class="col-md-12 text-right">
              @csrf
              <input type="hidden" name="invoice_id"  value="">
              <input type="submit" name="save" value="Pay" class="payinvoice btn btn-primary "/>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection

@push('footer_script')

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
    { data: 'name', name: 'name'},
    { data: 'description', name: 'description'},
    { data: 'id', name: 'id' },
    { data: 'amount', name: 'amount'},
    { data: 'created_at', name: 'created_at'},
    { data: 'payment_method', name: 'payment_method'},
    { data: 'st', name: 'st'},
    { data: null,
      render: function(data){
        var view_button='';
        // if(data.status == 0) {
            view_button = ' <a href="{{url('firm/firmclient/billing/invoice/viewinvoice')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="View Invoice"><img src="{{ url('/') }}/assets/images/icon/Group 557.svg"></a>';
        // }
        return view_button;

      }, orderable: "false"
    },
  ],
});
$(document).ready(function(){
  $('.payinvoice').on('click', function(e){
      e.preventDefault();
      var invoice_id = $('input[name="invoice_id"]').val();
      var _token = $('input[name="_token"]').val();
      var card_number = $('input[name="card_number"]').val();
      var exp_month = $('input[name="exp_month"]').val();
      var exp_year = $('input[name="exp_year"]').val();
      var cvc = $('input[name="cvc"]').val();
      var address_zip = $('input[name="address_zip"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/firmclient/billing/payForInvoice') }}",
        data: {
        	invoice_id:invoice_id, 
        	_token:_token,
        	card_number:card_number,
        	exp_month:exp_month,
        	exp_year:exp_year,
        	cvc:cvc,
        	address_zip:address_zip
        },
        success:function(res)
        {      
          alert('Paid successfully'); 
          window.location.href = "{{ url('firm/firmclient/billing/invoice') }}";
        }
      });
    });
})  
$(document).on('click', '.invoicepopup', function(e){
  e.preventDefault();
  var id = $(this).data('id');
  var amount = $(this).data('amount');
  $('input[name="invoice_id"]').val(id);
  $('.provided_cost').text('Invoice amount : $'+amount);
  $("#Invoice_PopUp").modal('show');
});
</script>
@endpush 
