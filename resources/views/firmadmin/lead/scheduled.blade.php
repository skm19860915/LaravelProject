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
                      <a class="nav-link active" data-status="1" id="scheduled-tab" data-toggle="tab" href="{{ url('firm/lead/scheduled') }}/{{$lead->id}}" role="tab" aria-controls="scheduled" aria-selected="false">Scheduled</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/lead/acceptpayment') }}/{{$lead->id}}" role="tab" aria-controls="acceptpayment" aria-selected="false">Accept Payment</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="schedule_history-tab" data-toggle="tab" href="{{ url('firm/lead/schedule_history') }}/{{$lead->id}}" role="tab" aria-controls="schedule_history" aria-selected="false">Scheduled History</a>
                    </li>  
                  </ul>
                </div>
                <div class="task-tabcontent-box">
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="scheduled" role="tabpanel" aria-labelledby="home-tab">
                      <form action="{{ url('firm/lead/SchedulePayment') }}" method="post" class="needs-validation" id="payment-form" enctype="multipart/form-data" novalidate="">
                        <br>
                        <div class="row form-group">
                          <div class="col-sm-6 col-md-6">
                            <div class="row">
                              <label class="col-form-label col-md-4 col-sm-4">Search Invoice Number
                              </label> 
                              <div class="col-sm-6 col-md-6">
                                <select placeholder="Search Invoice Number" name="invoice_number" class="selectpicker invoice_number" data-live-search="true">
                                  <option value="">Select</option> 
                                  <?php 
                                  if(!empty($invoice)) {
                                    foreach ($invoice as $k => $i) {
                                      $sl = '';
                                      if($i->id ==$id1) {
                                        $sl = 'selected';
                                      }
                                      echo '<option value="'.$i->id.'" '.$sl.'>Invoice #'.$i->id.'</option>';
                                    }
                                  }
                                  ?>
                                </select>
                                <div class="invalid-feedback">Invoice is required!</div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row form-group">
                          <div class="col-sm-6 col-md-6">
                            <div class="row">
                              <label class="col-form-label col-md-4 col-sm-4">Schedule Payment?</label>
                              <div class="col-sm-6 col-md-6">
                                <label class="custom-switch mt-2" style="padding-left: 0;">
                                  <input type="checkbox" name="is_schedule" class="custom-switch-input is_schedule" value="1">
                                  <span class="custom-switch-indicator"></span>
                                  <span class="custom-switch-description"></span>
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="payment_method_w card_payment">
                          <br>
                          <div class="row form-group">
                            <div class="col-sm-6 col-md-6">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Recurring Amount <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <span class="curruncy_symbol">$</span>
                                  <input placeholder="Recurring Amount" name="recurring_amount" class="form-control case_cost" value="" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-6 col-md-6">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">First Payment <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input placeholder="First Payment" name="first_payment" class="form-control datepicker" value="" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-6 col-md-6">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Frequency of Payment <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <select name="payment_cycle" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Quarterly">Quarterly</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-6 col-md-6">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Credit Card <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <select name="credit_card" class="form-control credit_card">
                                    <option value="">Select</option>
                                    <option value="New Credit Card">New Credit Card</option>
                                    <?php
                                    if(!empty($cards)) {
                                      foreach ($cards as $k => $v) {
                                        echo '<option value="'.$v['id'].'" data-card=\''.json_encode($v).'\'>'.$v['number'].'</option>';
                                      }
                                    }
                                    ?>
                                    <!-- <option value="">Select</option> -->
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="payment_method_w card_details">
                          <br>
                          <div class="row form-group">
                            <div class="col-sm-6 col-md-6">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Name of Credit Card <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input placeholder="Name of Credit Card" name="name_of_credit_card" class="form-control" value="" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-6 col-md-6">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Credit Card Number <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input type="text" placeholder="Credit Card Number" size="20" name="card_number" class="form-control" data-stripe="number" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- <div class="row form-group">
                            <div class="col-sm-6 col-md-6">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">ZipCode <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input type="text" placeholder="ZipCode" size="6" name="address_zip" data-stripe="address_zip" class="form-control" />
                                </div>
                              </div>
                            </div>
                          </div> -->
                          <div class="row form-group">
                            <div class="col-sm-6 col-md-6">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Expiring Month <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input type="text" placeholder="Expiring Month" name="exp_month" data-stripe="exp_month" class="form-control" />
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row form-group">
                            <div class="col-sm-6 col-md-6">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">Expiring Year <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input type="text" placeholder="Expiring Year" name="exp_year" size="2" data-stripe="exp_year" class="form-control">
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- <div class="row form-group">
                            <div class="col-sm-6 col-md-6">
                              <div class="row">
                                <label class="col-form-label col-md-4 col-sm-4">CVC <span style="color: red"> *</span></label>
                                <div class="col-sm-6 col-md-6">
                                  <input type="text" placeholder="CVC" size="4" data-stripe="cvc" name="cvc" class="form-control" />
                                </div>
                              </div>
                            </div>
                          </div> -->
                        </div>
                        <div class="row form-group"> 
                          <div class="col-sm-12 col-md-12">
                            <input type="hidden" name="redirect_url"  value="{{ url('firm/lead/invoice') }}/{{$lead->id}}">
                            <input type="hidden" name="id"  value="<?php if(!empty($qbinvoice)) { echo $qbinvoice->id; } ?>">
                            <input type="hidden" name="lead_id" value="{{$lead->id}}">
                            @csrf
                            <button class="btn btn-primary" value="1" type="submit" name="save_close">
                            <span>Save + Close</span>
                            </button>
                            <button class="btn btn-primary" value="1" type="submit" name="save_view">
                            <span>Save + View</span>
                            </button>
                          </div>
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