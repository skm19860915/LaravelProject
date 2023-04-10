@extends('firmlayouts.admin-master')

@section('title')
Transactions
@endsection
@push('header_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
    display: none;
} 
.dropdown.bootstrap-select.family_arr {
    margin-right: 15px;
}   
body div#table_filter {
    display: block;
}
</style>
@endpush  
@section('content')
<section class="section report-seaction-box">
  <div class="section-header">
    <h1>Transactions </h1>
    <div class="section-header-breadcrumb">
      <div class="report-icon-box"></div> 
    </div>
  </div>
  <div class="section-body">
    
   <div class="row">
     <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive table-invoice" style="min-height: 350px;">
            <div class="profile-new-client" style="margin-bottom: 20px;">
              <div class="card card-statistic-1" style="width: 250px;float: right;margin: 0;">
                  <div class="card-icon shadow-primary bg-primary d_card3">
                      <img src="{{url('assets/images/icon/ticket@2x.png')}}" />
                  </div>
                  <div class="card-wrap">
                      <div class="card-header runningtotal">
                          {{$t}}
                      </div>
                      <div class="card-body">
                          Total Amount
                      </div>
                  </div>
              </div>
              <div class="profle-text-section">

                <div class="task-tabbtn-box">
                  <ul class="nav nav-tabs" id="transactions" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" data-status="0" id="transactions-tab" data-toggle="tab" href="{{ url('firm/transactions') }}" role="tab" aria-controls="transactions" aria-selected="true">Transactions</a>
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
                      <a class="nav-link" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/billing/acceptpayment') }}" role="tab" aria-controls="acceptpayment" aria-selected="false">Accept Payment</a>
                    </li>
                    <?php } ?>  
                  </ul>
                </div>

              </div>
            </div>

            <div class="calender-box-new"><br> 
              <select class="selectpicker family_arr" style="width: 220px; display: inline-block; margin-right: 15px;" data-live-search="true">
                <option value="">All Transactions</option>
                <option value="User">Monthly user cost</option>
                <option value="Case">Case</option>
                <option value="Translation">Translation</option>
                <option value="Additional Service">Additional Service</option>
              </select>
              <label for="from">From</label> 
              <input type="text" class="from" value="<?php echo date('m/d/Y', strtotime("-1 month")); ?>" name="from">
              <label for="to">to</label>
              <input type="text" class="to" value="<?php echo date('m/d/Y'); ?>" name="to"> 
              <button class="filter btn btn-primary"  value="courtdate" ><img src="{{ url('/') }}/assets/images/icons/right-arrow-white.svg"></button>
            </div>
            <div>
              <br/>
              <label>View</label>
              <div class="selectgroup">
                <label class="selectgroup-item">
                  <input type="radio" name="pastdate" value="30" class="selectgroup-input">
                  <span class="selectgroup-button">Past 30 days</span>
                </label>
                <label class="selectgroup-item">
                  <input type="radio" name="pastdate" value="60" class="selectgroup-input">
                  <span class="selectgroup-button">Past 60 days</span>
                </label>
                <label class="selectgroup-item">
                  <input type="radio" name="pastdate" value="90" class="selectgroup-input">
                  <span class="selectgroup-button">Past 90 days</span>
                </label>
              </div>
            </div>
            
            <table class="table table table-bordered table-striped"  id="table" >
              <thead>
                <tr>
                  <th style="display: none;">ID</th>
                  <th>Date</th>
                  <th>Name</th>
                  <!-- <th>Case Type</th> -->
                  <th>Description</th>
                  <th>Payment Type</th>
                  <th>Payment Method</th>
                  <th>Total</th>
                </tr>
              </thead>
            </table>
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
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script type="text/javascript">

var index_url = "{{url('firm/getFirmTransactions')}}";
var type = '';
var pastdate = '';
var from = '';
var to = '';
getBillingData(type, pastdate, from, to);
function getBillingData(type, pastdate, from, to) {
    table = $('#table').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
        "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "type": type,
                "pastdate": pastdate,
                "from": from,
                "to": to,
            }
      },
      dom: 'lBfrtip',
      buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ],
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'created_at', name: 'created_at'},
        { data: 'clientname', name: 'clientname'},
        // { data: 'case_type', name: 'case_type'},
        { data: 'description', name: 'description'},
        // { data: 'tx_id', name: 'tx_id'},
        { data: 'type', name: 'type'},
        { data: 'p_method', name: 'p_method'},
        { data: 'amount', name: 'amount'}
      ],
    });
    table.buttons().container().appendTo($('.report-icon-box'));
}
$('select.family_arr').on('change', function(){
    type = $('select.family_arr').val();
    pastdate = $("input[name='pastdate']:checked").val();
    getBillingData(type, pastdate, from, to);
});
$('input[name="pastdate"]').on('click', function(){
    from = '';
    to = '';
    type = $('select.family_arr').val();
    pastdate = $("input[name='pastdate']:checked").val();
    getBillingData(type, pastdate, from, to);
});
$(".filter ").on("click", function(e){
    e.preventDefault();
    $("input[name='pastdate']:checked").prop('checked', false);
    pastdate = '';
    from = $('.from').val();
    to = $('.to').val();
    getBillingData(type, pastdate, from, to);
});
$(document).ready(function(){
  $('.nav-link').on('click', function(){
    var h = $(this).attr('href');
    window.location.href = h;
  });
});
//================ Edit user ============//

</script>
@endpush 