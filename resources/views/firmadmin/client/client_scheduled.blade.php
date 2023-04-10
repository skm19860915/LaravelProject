@extends('firmlayouts.admin-master')

@section('title')
View client
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
/*  .card_detail_w input.form-control:read-only {
    background: transparent;
    border: 0;
    padding: 0;
    height: auto;
  }*/
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
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/client') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
           <div class="card-body">
           
             <div class="profile-new-client">
               <!-- <h2>Invoices</h2> -->
               <a href="#" class="add-task-link scheduledatebtn">Set Schedule</a>
               <div class="profle-text-section">
                <div class="task-tabbtn-box">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link" data-status="0" id="summary-tab" data-toggle="tab" href="{{ url('firm/client/client_billing') }}/{{$client->id}}" role="tab" aria-controls="summary" aria-selected="true">Summary</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="invoices-tab" data-toggle="tab" href="{{ url('firm/client/client_invoice') }}/{{$client->id}}" role="tab" aria-controls="invoices" aria-selected="false">Invoices</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link active" data-status="1" id="scheduled-tab" data-toggle="tab" href="{{ url('firm/client/client_scheduled') }}/{{$client->id}}" role="tab" aria-controls="scheduled" aria-selected="false">Scheduled</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/client/client_acceptpayment') }}/{{$client->id}}" role="tab" aria-controls="acceptpayment" aria-selected="false">Accept Payment</a>
                    </li>  
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/client/client_schedule_history') }}/{{$client->id}}" role="tab" aria-controls="client_schedule_history" aria-selected="false">Scheduled History</a>
                    </li>
                  </ul>
                </div>
                <div class="task-tabcontent-box">
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="scheduled" role="tabpanel" aria-labelledby="home-tab">
                      <div class="row form-group">
                        <div class="col-sm-6 col-md-6">
                          <div class="row">
                            <label class="col-form-label col-md-4 col-sm-4">Search Invoice Number <span style="color: red"> *</span>
                            </label> 
                            <div class="col-sm-6 col-md-6">
                              <select placeholder="Search Invoice Number" name="invoice_number" class="selectpicker invoice_number1" required="required" data-live-search="true">
                                <option value="">Select</option> 
                                <?php 
                                if(!empty($invoice)) {
                                  foreach ($invoice as $k => $i) {
                                    echo '<option value="'.$i->invoice_id.'">Invoice #'.$i->invoice_id.'-'.$i->description.'</option>';
                                  }
                                }
                                ?>
                              </select>
                              <div class="invalid-feedback">Invoice is required!</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="schedule_details"></div>
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
<!-- Modal -->
<div id="ScheduleModal" class="modal fade" role="dialog" style="position: fixed;">
  <div class="modal-dialog">

    <!-- Pay Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Schedule Payment</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('firm/client/ClientSchedulePayment') }}" method="post" class="needs-validation" id="payment-form" enctype="multipart/form-data" novalidate="">
          <div class="row form-group">
            <div class="col-sm-12 col-md-12">
              <div class="row">
                <label class="col-form-label col-md-5 col-sm-5">Search Invoice Number <span style="color: red"> *</span>
                </label> 
                <div class="col-sm-7 col-md-7">
                  <select placeholder="Search Invoice Number" name="invoice_number" class="selectpicker invoice_number" data-live-search="true" required>
                    <option value="">Select</option> 
                    <?php 
                    if(!empty($invoice1)) {
                      foreach ($invoice1 as $k => $i) {
                        if(!empty($i->sid)) {
                          continue;
                        }
                        $sl = '';
                        if($i->id ==$id1) {
                          $sl = 'selected';
                        }
                        if($i->paid_amount == $i->amount) {
                          continue;
                        }
                        echo '<option value="'.$i->id.'" '.$sl.' data-amount="'.$i->amount.'" data-paid_amount="'.$i->paid_amount.'">Invoice #'.$i->id.'-'.$i->description.'</option>';
                      }
                    }
                    ?>
                  </select>
                  <div class="invalid-feedback invoice_no_err">Invoice is required!</div>
                  <!-- <div class="outstanding_amount" style="display: none; padding: 5px 0 0 15px;">Outstanding Amount : <span></span></div> -->
                </div>
              </div>
            </div>
          </div>
          <div class="row form-group outstanding_amount" <?php if(empty($qbinvoice)) { echo 'style="display: none;"'; } ?>>
            <div class="col-sm-12 col-md-12">
              <div class="row">
                <label class="col-form-label col-md-5 col-sm-5">Outstanding Amount</label>
                <div class="col-sm-7 col-md-7">
                  <span>
                    <?php if(!empty($qbinvoice)) {
                      $aa = number_format($qbinvoice->amount-$qbinvoice->paid_amount, 2);
                      echo '$'.$aa;
                   } ?> 
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="row form-group">
            <div class="col-sm-12 col-md-12">
              <div class="row">
                <label class="col-form-label col-md-5 col-sm-5">Schedule Payment? <span style="color: red"> *</span></label>
                <div class="col-sm-7 col-md-7">
                  <label class="custom-switch mt-2" style="padding-left: 0;">
                    <input type="checkbox" name="is_schedule" class="custom-switch-input is_schedule" value="1" <?php if(isset(Session::get('data')['is_schedule']) && Session::get('data')['is_schedule'] == 1) { echo 'checked'; }?> required>
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description"></span>
                  </label>
                  <div class="invalid-feedback is_schedule_err">Schedule Payment is required!</div>
                </div>
              </div>
            </div>
          </div>
          <div class="payment_method_w card_payment"  <?php if(isset(Session::get('data')['is_schedule']) && Session::get('data')['is_schedule'] == 1) { echo 'style="display:block;"'; }?>>
            <br>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Recurring Amount <span style="color: red"> *</span></label>
                  <div class="col-sm-7 col-md-7">
                    <span class="curruncy_symbol" style="top: 11px;">$</span>
                    <input type="number" min="1" placeholder="Recurring Amount" name="recurring_amount" class="form-control case_cost" value="<?php if(isset(Session::get('data')['name_of_credit_card'])) { echo Session::get('data')['name_of_credit_card']; }?>" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">First Payment <span style="color: red"> *</span></label>
                  <div class="col-sm-7 col-md-7">
                    <input placeholder="First Payment" name="first_payment" class="form-control datepicker" value="<?php if(isset(Session::get('data')['name_of_credit_card'])) { echo Session::get('data')['name_of_credit_card']; }?>" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Frequency of Payment <span style="color: red"> *</span></label>
                  <div class="col-sm-7 col-md-7">
                    <select name="payment_cycle" class="form-control">
                      <option value="">Select</option>
                      <option value="Weekly" <?php if(isset(Session::get('data')['payment_cycle']) && Session::get('data')['payment_cycle'] == 'Weekly') { echo 'selected="selected"'; }?>>Weekly</option>
                      <option value="Monthly" <?php if(isset(Session::get('data')['payment_cycle']) && Session::get('data')['payment_cycle'] == 'Monthly') { echo 'selected="selected"'; }?>>Monthly</option>
                      <option value="Quarterly" <?php if(isset(Session::get('data')['payment_cycle']) && Session::get('data')['payment_cycle'] == 'Quarterly') { echo 'selected="selected"'; }?>>Quarterly</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Credit Card <span style="color: red"> *</span></label>
                  <div class="col-sm-7 col-md-7">
                    <select name="credit_card" class="form-control credit_card">
                      <option value="">Select</option>
                      <option value="New Credit Card" <?php if(isset(Session::get('data')['credit_card']) && Session::get('data')['credit_card'] == 'New Credit Card') { echo 'selected="selected"'; }?>>New Credit Card</option>
                      <?php
                      if(!empty($cards)) {
                        foreach ($cards as $k => $v) {
                          echo '<option value="'.$v['id'].'" data-card=\''.json_encode($v).'\'>'.$v['number'].'</option>';
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="payment_method_w card_details" <?php if(isset(Session::get('data')['credit_card'])) { echo 'style="display:block;"'; }?>>
            <br>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Name of Credit Card <span style="color: red"> *</span></label>
                  <div class="col-sm-7 col-md-7">
                    <input placeholder="Name of Credit Card" name="name_of_credit_card" class="form-control" value="<?php if(isset(Session::get('data')['name_of_credit_card'])) { echo Session::get('data')['name_of_credit_card']; }?>" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Credit Card Number <span style="color: red"> *</span></label>
                  <div class="col-sm-7 col-md-7">
                    <input type="text" placeholder="Credit Card Number" size="20" name="card_number" class="form-control" data-stripe="number" maxlength="16" value="<?php if(isset(Session::get('data')['card_number'])) { echo Session::get('data')['card_number']; }?>" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-5 col-sm-5">Expiration Date <span style="color: red"> *</span></label>
                  <div class="col-sm-7 col-md-7">
                    <input type="text" placeholder="mm/yyyy" name="exp_date" data-stripe="exp_date" class="form-control exp_date" value="<?php if(isset(Session::get('data')['exp_date'])) { echo Session::get('data')['exp_date']; }?>" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row form-group"> 
            <div class="col-sm-12 col-md-12">
              <input type="hidden" name="redirect_url"  value="{{ url('firm/client/client_invoice') }}/{{$client->id}}">
              <input type="hidden" name="id"  value="<?php if(!empty($qbinvoice)) { echo $qbinvoice->id; } ?>">
              <input type="hidden" name="client_id" value="{{$client->id}}">
              @csrf
              <button class="btn btn-primary" value="1" type="submit" name="save_close">
              <span>Save + Close</span>
              </button>
              <!-- <button class="btn btn-primary" value="1" type="submit" name="save_view">
              <span>Save + View</span>
              </button> -->
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="SkipPaymentModal" class="modal fade" role="dialog" style="position: fixed;">
  <div class="modal-dialog">

    <!-- Pay Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Skip Payment</h4>
      </div>
      <div class="modal-body text-center">
        <p>Are you sure? Next set payment will be skipped</p>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Cancel</button>
        <button type="button" class="btn btn-primary yes_skip">Yes, Skip</button>
        <p class="text-success skip_msg"></p>
      </div>
    </div>
  </div>
</div>
@endsection
@push('footer_script')
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.exp_date').mask('00/0000');
  $('.nav-link').on('click', function(){
    var h = $(this).attr('href');
    window.location.href = h;
  });
  $('.is_schedule').on('change', function(){
    if ($(this).is(':checked')) {
      $('.card_payment').show();
      $('.card_payment input').prop('required', true);
      $('.card_payment select').prop('required', true);
    }
    else {
      $('.card_payment').hide();
      $('.card_payment input').prop('required', false);
      $('.card_payment select').prop('required', false);
    }
    if($('.is_schedule').is(':checked')) {
      $('.is_schedule_err').hide();
    }
    else {
      $('.is_schedule_err').show();
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
        var exdate = c.exp_month+'/'+c.exp_year;
        if(c.exp_month < 10) {
          exdate = '0'+c.exp_month+'/'+c.exp_year
        }
        $('.exp_date').val(exdate);
        // $('input[name="exp_year"]').val(c.exp_year);
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
    if(v == '') {
      $('.invoice_no_err').show();
    }
    else {
      $('.invoice_no_err').hide();
    }
    
    var amount = $('select.invoice_number option[value="'+v+'"]').data('amount');
    var paid_amount = $('select.invoice_number option[value="'+v+'"]').data('paid_amount');
    var t = parseInt(amount);
    if(paid_amount) {
      t = parseInt(amount)-parseInt(paid_amount);
    }
    $('.case_cost').attr('max', t);
    console.log(amount, paid_amount, t);
    if(t) {
      var t1 = '$'+t.toFixed(2);
      $('.outstanding_amount').show();
      $('.outstanding_amount span').text(t1);
    }
    else {
      $('.outstanding_amount').hide();
    }
    $('input[name="id"]').val(v);
  });
  <?php if(isset(Session::get('data')['credit_card']) && Session::get('data')['credit_card'] == 'New Credit Card') { ?>
    $("#ScheduleModal").modal('show');
  <?php } ?>
  $('.scheduledatebtn').on('click', function(e){
    e.preventDefault();
    $("#ScheduleModal").modal('show');
  });
  $('select.invoice_number1').on('change', function(){
    var v = $(this).val();
    var csrf1 = $('input[name="_token"]').val();
    if(v != '') {
      $.ajax({
        url: "{{url('firm/client/GetClientSchedulePayment')}}/{{$client->id}}/"+v,
        data: {_token: csrf1},
        dataType: 'json',
        type: 'get',
        async: false,
        success: function (data) {
          $('.schedule_details').html(data.responseText);
          $(document).find('.new_exp_date').mask('00/0000');
        },
        error: function (data) {
          $('.schedule_details').html(data.responseText);
          $(document).find('.new_exp_date').mask('00/0000');
        }
      });
    }
    else {
      $('.schedule_details').html('<p>Please select invoice.</p>');
    }
  });
  $('.yes_skip').on('click', function(e){
    e.preventDefault();
    $('.skip_msg').text('');
    var id = $(this).data('id');
    var sdate = $(this).data('sdate');
    var csrf1 = $('input[name="_token"]').val();
    $.ajax({
      url: "{{url('firm/client/SkipPayment')}}/"+id,
      data: {_token: csrf1, sdate:sdate},
      dataType: 'json',
      type: 'get',
      async: false,
      success: function (data) {
        $(document).find('.nextpayment_s').text(data.next_payment);
        $('.skip_msg').text('Skiped successfully!');
      },
      error: function (data) {
        $(document).find('.nextpayment_s').text(data.next_payment);
        $('.skip_msg').text('Skiped successfully!');
      }
    });
  });
  $('#ScheduleModal form').on('submit', function(){
    var v = $('select.invoice_number').val();
    if(v == '') {
      $('.invoice_no_err').show();
    }
    else {
      $('.invoice_no_err').hide();
    }

    if($('.is_schedule').is(':checked')) {
      $('.is_schedule_err').hide();
    }
    else {
      $('.is_schedule_err').show();
    }
  });
});
$(document).on('click', '.skip_payment', function(e){
  e.preventDefault();
  var id = $(this).data('id');
  var sdate = $(this).data('sdate');
  $('.yes_skip').data('id', id);
  $('.yes_skip').data('sdate', sdate);
  $("#SkipPaymentModal").modal('show');
});
$(document).on('click', '.card_edit_btn', function(e){
  e.preventDefault();
  $('.card_detail_w input.form-control').prop('readonly', false);
  $('.card_detail_w .update_card').show();
})
$(document).on('click', '.update_card', function(e){
  e.preventDefault();
  var csrf1 = $('input[name="_token"]').val();
  var name_of_card = $('.card_detail_w input[name="name_of_card"]').val();
  var new_card_number = $('.card_detail_w input[name="new_card_number"]').val();
  var new_exp_date = $('.card_detail_w input[name="new_exp_date"]').val();
  var invoice_id = $('.card_detail_w input[name="invoice_id1"]').val();
  var client_id = $('.card_detail_w input[name="related_id1"]').val();
  $.ajax({
      url: "{{url('firm/client/UpdateScheduleCard')}}",
      data: {
        _token: csrf1, 
        name_of_card:name_of_card, 
        new_card_number: new_card_number,
        new_exp_date: new_exp_date,
        invoice_id: invoice_id,
        client_id: client_id
      },
      dataType: 'json',
      type: 'post',
      async: false,
      success: function (data) {
        alert('Card updated successfully!');
        window.location.href = "{{url('firm/client/client_scheduled')}}/"+client_id;
        //$('.skip_msg').text('Skiped successfully!');
      },
      error: function (data) {
        alert('Card updated successfully!');
        window.location.href = "{{url('firm/client/client_scheduled')}}/"+client_id;
        //$('.skip_msg').text('Skiped successfully!');
      }
    });
});
</script>
@endpush