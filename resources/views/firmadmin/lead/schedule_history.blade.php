@extends('firmlayouts.admin-master')

@section('title')
View Lead
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
  .payment_method_w {
    display: none;
  }
  .curruncy_symbol {
    position: absolute;
    left: 30px;
    top: 7px;
  } 
  input.form-control.case_cost {
    padding-left: 25px !important;
  }
</style>
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
                      <a class="nav-link" data-status="0" id="summary-tab" data-toggle="tab" href="{{ url('firm/lead/billing') }}/{{$lead->id}}" role="tab" aria-controls="summary" aria-selected="true">Summary</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="invoices-tab" data-toggle="tab" href="{{ url('firm/lead/invoice') }}/{{$lead->id}}" role="tab" aria-controls="invoices" aria-selected="false">Invoices</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="scheduled-tab" data-toggle="tab" href="{{ url('firm/lead/scheduled') }}/{{$lead->id}}" role="tab" aria-controls="scheduled" aria-selected="false">Scheduled</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/lead/acceptpayment') }}/{{$lead->id}}" role="tab" aria-controls="acceptpayment" aria-selected="false">Accept Payment</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link active" data-status="1" id="schedule_history-tab" data-toggle="tab" href="{{ url('firm/lead/schedule_history') }}/{{$lead->id}}" role="tab" aria-controls="schedule_history" aria-selected="false">Scheduled History</a>
                    </li>  
                  </ul>
                </div>
                <div class="task-tabcontent-box">
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="schedule_history" role="tabpanel" aria-labelledby="home-tab">
                      <form action="{{ url('firm/lead/SchedulePayment') }}" method="post" class="needs-validation" id="payment-form" enctype="multipart/form-data" novalidate="">
                        <br>
                        <h4>Scheduled Invoices</h4>
                        <div class="table-responsive table-invoice">
                          <table class="table table table-bordered table-striped"  id="table" >
                            <thead>
                              <tr>
                                <th> Lead </th>
                                <th> Lead Number</th>
                                <th> Invoice Number</th>
                                <th> Frequency</th>
                                <th> Next Payment</th>
                                <th> Recurring Amount</th>
                                <th> Balance</th>
                                <th> Status</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                                  if(!empty($invoice)) {
                                    foreach ($invoice as $k => $i) { ?>
                              <tr>
                                <td>
                                  <!-- <a href="<?php echo $i->client_name; ?>"> -->
                                <?php echo $i->client_name; ?>
                              <!-- </a> -->
                                </td>
                                <td>
                                  <?php echo $i->lead_id; ?>
                                </td>
                                <td>
                                  <?php echo $i->invoice_id; ?>
                                </td>
                                <td>
                                  <?php echo $i->payment_cycle; ?>
                                </td>
                                <td>
                                  <?php echo $i->first_payment; ?>
                                </td>
                                <td>
                                  $<?php echo number_format($i->recurring_amount,2); ?>
                                </td>
                                <td>
                                  $<?php echo number_format($i->amount,2); ?>
                                </td>
                                <td>
                                  <?php //echo $i->amount; ?>
                                </td>
                              </tr>
                            <?php } } ?>
                            </tbody>
                          </table>
                        </div>
                      </form>
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
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.nav-link').on('click', function(){
    var h = $(this).attr('href');
    window.location.href = h;
  });
  $('.is_schedule').on('change', function(){
    if ($(this).is(':checked')) {
      $('.card_payment').show();
      $('.card_payment input').prop('required', true);
    }
    else {
      $('.card_payment').hide();
      $('.card_payment input').prop('required', false);
    }
  });
  $('.credit_card').on('change', function(){
    if ($(this).val() == 'New Credit Card') {
      $('input[name="name_of_credit_card"]').val('');
      $('input[name="card_number"]').val('');
      $('input[name="exp_month"]').val('');
      $('input[name="exp_year"]').val('');
      $('.card_details').show();
      $('.card_details input').prop('required', true);
    }
    else {
      if($(this).val() != '') {
        var v = $(this).val();
        var c = $('.credit_card option[value="'+v+'"]').data('card');
        console.log(c);
        $('input[name="name_of_credit_card"]').val(c.name);
        $('input[name="card_number"]').val(c.number);
        $('input[name="exp_month"]').val(c.exp_month);
        $('input[name="exp_year"]').val(c.exp_year);
        // $('input[name=""]').val(c.name);
        // $('input[name=""]').val(c.name);
      }
      $('.card_details').show();
      $('.card_details input').prop('required', true);
    }
  });
  $('.datepicker').daterangepicker({
      timePicker: false,
      singleDatePicker: true,
      endDate: moment().startOf('hour').add(32, 'hour'),
      locale: {
        format: 'MM/DD/YYYY'
      },
      minDate: new Date()
  });
  $('select.invoice_number').on('change', function(){
    var v = $(this).val();
    $('input[name="id"]').val(v);
  });
});
</script>
@endpush