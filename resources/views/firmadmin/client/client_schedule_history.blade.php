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
               <!-- <a href="#" class="add-task-link scheduledatebtn">Set Schedule</a> -->
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
                      <a class="nav-link" data-status="1" id="scheduled-tab" data-toggle="tab" href="{{ url('firm/client/client_scheduled') }}/{{$client->id}}" role="tab" aria-controls="scheduled" aria-selected="false">Scheduled</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/client/client_acceptpayment') }}/{{$client->id}}" role="tab" aria-controls="acceptpayment" aria-selected="false">Accept Payment</a>
                    </li>  
                    <li class="nav-item">
                      <a class="nav-link active" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/client/client_schedule_history') }}/{{$client->id}}" role="tab" aria-controls="client_schedule_history" aria-selected="false">Scheduled History</a>
                    </li>
                  </ul>
                </div>
                <div class="task-tabcontent-box">
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="scheduled" role="tabpanel" aria-labelledby="home-tab">
                      <br>
                      <h4>Scheduled Invoices</h4>
                      <div class="table-responsive table-invoice">
                        <table class="table table table-bordered table-striped"  id="table" >
                          <thead>
                            <tr>
                              <th> Client name </th>
                              <th> Client Number</th>
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
                                <?php echo $i->client_id; ?>
                              </td>
                              <td>
                                <?php echo $i->invoice_id; ?>
                              </td>
                              <td>
                                <?php echo $i->payment_cycle; ?>
                              </td>
                              <td>
                                <?php echo $i->next_payment; ?>
                              </td>
                              <td>
                                $<?php echo number_format($i->recurring_amount,2); ?>
                              </td>
                              <td>
                                $<?php 
                                $balance = $i->amount-$i->paid_amount;
                                echo number_format($balance,2); ?>
                              </td>
                              <td>
                                <?php //echo $i->amount; ?>
                              </td>
                            </tr>
                          <?php } } ?>
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
        },
        error: function (data) {
          $('.schedule_details').html(data.responseText);
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
        $('.skip_msg').text('Skiped successfully!');
      },
      error: function (data) {
        $('.skip_msg').text('Skiped successfully!');
      }
    });
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
  var new_exp_month = $('.card_detail_w input[name="new_exp_month"]').val();
  var new_exp_year = $('.card_detail_w input[name="new_exp_year"]').val();
  var invoice_id = $('.card_detail_w input[name="invoice_id1"]').val();
  var client_id = $('.card_detail_w input[name="related_id1"]').val();
  $.ajax({
      url: "{{url('firm/client/UpdateScheduleCard')}}",
      data: {
        _token: csrf1, 
        name_of_card:name_of_card, 
        new_card_number: new_card_number,
        new_exp_month: new_exp_month,
        new_exp_year: new_exp_year,
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