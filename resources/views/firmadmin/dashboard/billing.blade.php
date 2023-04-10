@extends('firmlayouts.admin-master')

@section('title')
Firm Billing
@endsection
@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
    display: none;
}    
</style>
@endpush  
@section('content')
<section class="section">
  <div class="section-header">
    <h1>Firm Billing</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
    
   <div class="row">
     <div class="col-md-12">
      <div class="card">

          <div class="card-body">
            <div class="table-responsive table-invoice">
              
              <div class="profile-new-client">
                <div class="profle-text-section">
                  <div class="task-tabbtn-box">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link" data-status="0" id="transactions-tab" data-toggle="tab" href="{{ url('firm/transactions') }}" role="tab" aria-controls="transactions" aria-selected="true">Transactions</a>
                      </li>
                      <?php if($firm->account_type == 'CMS') { ?>
                      <li class="nav-item">
                        <a class="nav-link active" data-status="0" id="summary-tab" data-toggle="tab" href="{{ url('firm/billing') }}" role="tab" aria-controls="summary" aria-selected="true">Summary</a>
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
                  <div class="task-tabcontent-box">
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="summary" role="tabpanel" aria-labelledby="home-tab">
                        <div class="overview-numbers">
                          <div class="overview-border-number" style="width: auto;"> 
                            <h3>${{ number_format($count['total_amount'], 2) }}</h3>
                            <p>Invoiced</p>
                          </div>
                          <div class="overview-border-number" style="width: auto;">
                            <h3>${{ number_format($count['paid_amount'], 2) }} ({{$count['paid_percent']}}%)</h3>
                            <p>Recieved</p>
                          </div>
                          <div class="overview-border-number" style="width: auto;">
                            <h3>${{ number_format($count['outstanding_amount'], 2) }}</h3>
                            <p>Outstanding</p>
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
</script>
@endpush 