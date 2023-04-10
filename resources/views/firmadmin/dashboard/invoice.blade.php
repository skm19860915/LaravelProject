@extends('firmlayouts.admin-master')

@section('title')
Invoice
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1),
#table tbody tr td:nth-child(2) {
    display: none;
}   
body div#table_filter {
  display: block !important;
} 
.main-wrapper .main-content .section-body .card .card-body .table tbody tr td a {
  color: #6777ef;
}
.main-content .section-body .card-body .dataTables_wrapper .table thead tr th {
    border-bottom-color: #767280 !important;
    width: 50px !important;
    min-width: 50px !important;
}
</style>
@endpush  

@section('content')
<section class="section invoice_list_view">
  <div class="section-header">
    <h1><a href="{{route('firm.billing')}}"><span>Firm Billing / </span></a> Invoice</h1>
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
                        <a class="nav-link active" data-status="1" id="invoices-tab" data-toggle="tab" href="{{ url('firm/billing/invoice') }}" role="tab" aria-controls="invoices" aria-selected="false">Invoices</a>
                      </li> 
                      <li class="nav-item">
                        <a class="nav-link" data-status="1" id="scheduled-tab" data-toggle="tab" href="{{ url('firm/billing/scheduled') }}" role="tab" aria-controls="scheduled" aria-selected="false">Scheduled</a>
                      </li> 
                      <li class="nav-item">
                        <a class="nav-link" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/billing/acceptpayment') }}" role="tab" aria-controls="acceptpayment" aria-selected="false">Accept Payment</a>
                      </li>  
                      <?php } ?>
                    </ul>
                  </div>
                  <div class="task-tabcontent-box">
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="invoices" role="tabpanel" aria-labelledby="home-tab">
                        <div class="table-responsive table-invoice invoice-table-width invoice_width_table">
                          <table class="table table table-bordered table-striped"  id="table" >
                            <thead>
                              <tr>
                                <th style="display: none;"> Id</th>
                                <th style="display: none;"> Client name </th>
                                <th> Client name </th>
                                <th> Description</th>
                                <th> Client Number</th>
                                <th> Invoice Number</th>
                                <th> Create Date </th>
                                <th> Invoice Amount</th>
                                <th> Piad Amount</th>
                                <th> Outstanding Amount</th>
                                <th> Recurring Payment Schedule</th>
                                <th> Status</th>
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
        </div>
      </div>
     </div>
  </div>
</section>
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.nav-link').on('click', function(){
    var h = $(this).attr('href');
    window.location.href = h;
  });
});
var index_url = "{{route('firm.billing.getInvoiceData')}}";
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
        {
          data : null,
          render: function(data) {
            return '<a href="'+data.link+'">'+data.name+'</a>';
          }, orderable: "false"
        },
        { data: 'description', name: 'description'},
        { data: 'client_id', name: 'client_id'},
        { data: 'id', name: 'id' },
        { data: 'created_at', name: 'created_at'},
        { data: 'amount', name: 'amount'},
        { data: 'paid_amount', name: 'paid_amount'},
        { data: 'outstanding_amount', name: 'outstanding_amount'},
        { data: 'scheduled', name: 'scheduled'},
        { data: 'st', name: 'st'},
      ],
    });
</script>
@endpush 
