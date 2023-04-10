@extends('firmlayouts.admin-master')

@section('title')
View Lead
@endsection

@push('header_styles')

@endpush 

@section('content')
<section class="section client-listing-details">
  <div class="section-header">
    <h1><a href="{{route('firm.lead')}}"><span>Lead /</span></a> Detail</h1>
    <div class="section-header-breadcrumb">
      <a href="{{ url('firm/lead/edit') }}/{{$lead->id}}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> Convert to client</a>      
    </div>
  </div>
  <div class="client-header-new">
   <div class="clent-profle-box">
    <div class="row">
     <div class="col-md-8">
      <div class="client-main-box-profile">
      <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
      <div class="client-right-text">
       <h3>
         <?php 
         echo $lead->name.' '.$lead->last_name;
         ?>
         <!-- <a href="#" class="action_btn customedit_btn" title="Edit Lead" data-toggle="tooltip" style="position: static;" data-id="{{$lead->id}}"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" style="width: 13px;" /></a> -->
       </h3>
       <p>{{ $lead->email }}<br />{{ $lead->cell_phone }}<br />
        Create Date : {{ date('M d, Y', strtotime($lead->created_at)) }}</p>
      </div>  
      </div>    
     </div>
     <div class="col-md-4">
      <div class="client-right-profile">
       <div class="clent-info"><span>Lead ID</span>:<span>#{{ $lead->id }}</span></div>
       <div class="clent-info"><span>Deported</span>:
        <span>
          <label class="custom-switch mt-2" style="padding-left: 0;">
            <input type="checkbox" name="is_deported" class="custom-switch-input is_deported" value="1" <?php echo $retVal = ($lead->is_deported == 1) ? "checked" : ""; ?>>
            <span class="custom-switch-indicator" style="width: 48px;"></span>
            <span class="custom-switch-description"></span>
          </label>
        </span>
      </div>
       <div class="clent-info"><span>Detained</span>:
        <span>
          <label class="custom-switch mt-2" style="padding-left: 0;">
            <input type="checkbox" name="is_detained" class="custom-switch-input is_detained" value="1" <?php echo $retVal = ($lead->is_detained == 1) ? "checked" : ""; ?>>
            <span class="custom-switch-indicator" style="width: 48px;"></span>
            <span class="custom-switch-description"></span>
          </label>
        </span>
       </div>
      </div>
     </div>
    </div>
   </div>
   <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'firm.lead.show' ? 'active-menu' : '' }}" href="{{ url('firm/lead/show') }}/{{ $lead->id }}">Overview</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.lead.billing' ? 'active-menu' : '' }}" href="{{ url('firm/lead/billing') }}/{{ $lead->id }}">Billing</a></li>
    </ul>
   </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card"> 
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/lead') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>      
          <div class="card-body">
            <div class="profile-new-client">
              <div class="profle-text-section">
                <div class="task-tabbtn-box">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" data-status="0" id="summary-tab" data-toggle="tab" href="{{ url('firm/lead/billing') }}/{{$lead->id}}" role="tab" aria-controls="summary" aria-selected="true">Summary</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="invoices-tab" data-toggle="tab" href="{{ url('firm/lead/invoice') }}/{{$lead->id}}" role="tab" aria-controls="invoices" aria-selected="false">Invoices</a>
                    </li> 
                    <!-- <li class="nav-item">
                      <a class="nav-link" data-status="1" id="scheduled-tab" data-toggle="tab" href="{{ url('firm/lead/scheduled') }}/{{$lead->id}}" role="tab" aria-controls="scheduled" aria-selected="false">Scheduled</a>
                    </li> --> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/lead/acceptpayment') }}/{{$lead->id}}" role="tab" aria-controls="acceptpayment" aria-selected="false">Accept Payment</a>
                    </li>  
                    <!-- <li class="nav-item">
                      <a class="nav-link" data-status="1" id="schedule_history-tab" data-toggle="tab" href="{{ url('firm/lead/schedule_history') }}/{{$lead->id}}" role="tab" aria-controls="schedule_history" aria-selected="false">Scheduled History</a>
                    </li>  -->
                  </ul>
                </div>
                <div class="task-tabcontent-box">
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="summary" role="tabpanel" aria-labelledby="home-tab">
                      <div class="overview-numbers">
                        <div class="overview-border-number" style="width: auto;"> 
                          <h3>${{ number_format($count['total_amount'], 2) }}</h3>
                          <p>Billed Amount</p>
                        </div>
                        <div class="overview-border-number" style="width: auto;">
                          <h3>${{ number_format($count['paid_amount'], 2) }} ({{$count['paid_percent']}}%)</h3>
                          <p>Recieved Amount</p>
                        </div>
                        <div class="overview-border-number" style="width: auto;">
                          <h3>${{ number_format($count['outstanding_amount'], 2) }}</h3>
                          <p>Outstanding Amount</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <h4>Scheduled Payments</h4> -->
              <div class="table-box-task" style="display: none;">
                <div class="table-responsive table-invoice">
                   <table class="table table-striped">
                     <thead>
                       <tr>
                         <th>Invoice Number</th>
                         <th>Amount</th>
                         <th>Due Date</th>
                       </tr>
                     </thead>
                     <tbody>
                      <?php 
                      $invoice_arr = array();
                      if(!empty($scheduled)) {
                        foreach ($scheduled as $k => $v) { 
                          $invoice_arr[] = $v->invoice_id;
                          ?>
                          <tr>
                             <td>#<?php echo $v->invoice_id; ?></td>
                             <td>$<?php echo number_format($v->recurring_amount, 2); ?></td>
                             <td><?php echo $v->first_payment; ?></td>
                          </tr>
                        <?php
                        }
                      }
                      ?>                      
                     </tbody>
                   </table>
                </div>
              </div>
              <h4>Payment History</h4>
              <div class="table-box-task">
                <div class="table-responsive table-invoice payment-his-table">
                   <table class="table table-striped">
                     <thead>
                       <tr>
                         <th>Invoice Number</th>
                         <th>Description</th>
                         <th>Total Amount</th>
                         <th>Payment Received</th>
                         <th>Outstanding Amount</th>
                         <th>Created Date</th>
                         <th>Paid Date</th>
                         <th>Status</th>
                         <th>Scheduled</th>
                       </tr>
                     </thead>
                     <tbody>
                      <?php 
                      if(!empty($invoice)) {
                        foreach ($invoice as $k => $v) { ?>
                          <tr>
                             <td>#<?php echo $v->id; ?></td>
                             <td><?php echo $v->description; ?></td>
                             <td>$<?php echo number_format($v->amount, 2); ?></td>
                             <td>$<?php echo number_format($v->paid_amount, 2); ?></td> 
                             <td>$<?php echo number_format(($v->amount-$v->paid_amount), 2); ?></td> 
                             <td><?php echo date('m/d/Y', strtotime($v->created_at)); ?></td> 
                             <td>
                              <?php 
                              if($v->paid_date) {
                                echo date('m/d/Y', strtotime($v->paid_date)); 
                              }
                              ?>
                             </td> 
                             <td>
                              <?php 
                                if($v->status == 1) {
                                    if($v->amount == $v->paid_amount) {
                                      echo '<div class="Active"><img src="'.url('/').'/assets/images/icon/right-green-sign.svg"> Paid</div>';
                                    }
                                    else {
                                      echo '<div class="Closed - Lost">Partially Paid</div>';
                                    }
                                }
                                else if($v->status == 3) {
                                    echo '<div class="Closed - Lost">Cancel</div>';
                                }
                                else {
                                    echo '<div class="Closed - Lost">Unpaid</div>';
                                }
                              ?>
                             </td> 
                             <td>
                              <?php 
                              if(in_array($v->id, $invoice_arr)) {
                                echo 'Yes';
                              }
                              else {
                                echo 'No';
                              }
                              ?>
                             </td> 
                          </tr>
                        <?php
                        }
                      }
                      ?>                      
                     </tbody>
                   </table>
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
<script type="text/javascript">
$(document).ready(function(){
  $('.nav-link').on('click', function(){
    var h = $(this).attr('href');
    window.location.href = h;
  });
});
</script>
@endpush