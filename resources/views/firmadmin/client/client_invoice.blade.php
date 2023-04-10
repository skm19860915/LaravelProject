@extends('firmlayouts.admin-master')

@section('title')
View client
@endsection

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
               <h2>Invoices</h2>
               <a href="{{ url('firm/client/add_new_invoice/')}}/{{$client->user_id}}" class="add-task-link">Add Invoice</a>
               <div class="profle-text-section">
                <div class="task-tabbtn-box">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link" data-status="0" id="summary-tab" data-toggle="tab" href="{{ url('firm/client/client_billing') }}/{{$client->id}}" role="tab" aria-controls="summary" aria-selected="true">Summary</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link active" data-status="1" id="invoices-tab" data-toggle="tab" href="{{ url('firm/client/client_invoice') }}/{{$client->id}}" role="tab" aria-controls="invoices" aria-selected="false">Invoices</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="scheduled-tab" data-toggle="tab" href="{{ url('firm/client/client_scheduled') }}/{{$client->id}}" role="tab" aria-controls="scheduled" aria-selected="false">Scheduled</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="acceptpayment-tab" data-toggle="tab" href="{{ url('firm/client/client_acceptpayment') }}/{{$client->id}}" role="tab" aria-controls="acceptpayment" aria-selected="false">Accept Payment</a>
                    </li> 
                    <li class="nav-item">
                      <a class="nav-link" data-status="1" id="client_schedule_history-tab" data-toggle="tab" href="{{ url('firm/client/client_schedule_history') }}/{{$client->id}}" role="tab" aria-controls="client_schedule_history" aria-selected="false">Scheduled History</a>
                    </li>  
                  </ul>
                </div>
                  <div class="table-box-task">
                    <div class="task-tabcontent-box">
                      <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="invoices" role="tabpanel" aria-labelledby="home-tab">
                          <div class="table-responsive table-invoice invoice-admin-client">
                           <table class="table table-striped">
                             <thead>
                               <tr>
                                 <th>Invoice No.</th>
                                 <th>Description</th>
                                 <th>Total Amount</th>
                                 <th>Payment Received</th>
                                 <th>Outstanding Amount</th>
                                 <th>Create Date</th>
                                 <th>Schedule</th>
                                 <th>Due Date</th>
                                 <th>Status</th>
                                 <th>Actions</th>
                               </tr>
                             </thead>
                             <tbody>
                              <?php 
                              if(!empty($invoice)) {
                                $invoice_arr = array();
                                foreach ($invoice as $k => $v) {
                                  if(in_array($v->id, $invoice_arr)) {
                                    continue;
                                  }
                                  $invoice_arr[] = $v->id;
                                 ?>
                                  <tr>
                                     <td>#<?php echo $v->id; ?></td>
                                     <td><?php echo $v->description; ?></td>
                                     <td>$<?php echo number_format($v->amount, 2); ?></td>
                                     <td>$<?php echo number_format($v->paid_amount, 2); ?></td> 
                                     <td>$<?php echo number_format(($v->amount-$v->paid_amount), 2); ?></td>
                                     <td>
                                      <?php echo date('m/d/Y', strtotime($v->created_at)); ?> 
                                     <span class="text-gray"><?php echo date('h:i a', strtotime($v->created_at)); ?></span></td>
                                     <td>
                                      <?php 
                                      if(!empty($v->sid)) {
                                        echo 'Yes';
                                      }
                                      else {
                                        echo 'No';
                                      }
                                      ?>
                                     </td>
                                     <td>
                                      <?php echo date('m/d/Y', strtotime($v->due_date)); ?> 
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
                                        <a href="{{url('firm/client/view_client_invoice')}}/<?php echo $v->id; ?>" class="action_btn" data-toggle="tooltip" data-placement="top" title="View Invoice"><img src="{{ url('/') }}/assets/images/icon/Group 557.svg"></a>
                                        <?php
                                        //if($v->status == 0) { ?>
                                          <a href="{{url('firm/client/edit_client_invoice')}}/<?php echo $v->id; ?>" class="action_btn" data-toggle="tooltip" data-placement="top" title="Edit Invoice"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>
                                          <!-- <a href="{{url('firm/client/cancel_client_invoice')}}/<?php echo $v->id; ?>/<?php echo $v->client_id; ?>" class="action_btn" data-toggle="tooltip" data-placement="top" title="Cancel Invoice" onclick="return confirm('Are you sure?')"><img src="{{ url('/') }}/assets/images/icon/cases-icon3.svg"></a> -->
                                          <?php 
                                          //if($v->payment_method == 'Card') { ?>
                                          
                                          <?php //} ?>
                                      <?php // } ?>   
                                        <a href="{{url('firm/client/view_client_invoice')}}/<?php echo $v->id; ?>" class="action_btn" data-toggle="tooltip" title="Send Invoice"><img src="{{url('assets/images')}}/icons/clock(3).svg"></a>
                                        <?php if($v->amount != $v->paid_amount) { ?> 
                                        <a href="#" class="action_btn rapid_pay_btn" data-toggle="tooltip" data-placement="top" title="Rapid Pay" data-id="{{$v->id}}" data-cost="{{$v->amount}}"><img src="{{ url('/') }}/assets/images/icon/ticket@2x.png"></a>
                                        <?php } ?>
                                        <!-- c<a href="{{url('firm/client/client_acceptpayment')}}/{{$client->id}}/<?php echo $v->id; ?>" class="action_btn" data-toggle="tooltip" data-placement="top" title="Rapid Pay" data-id="{{$v->id}}" data-cost="{{$v->amount}}"><img src="{{ url('/') }}/assets/images/icon/ticket@2x.png"></a> -->
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
      </div>
     </div>
  </div>
</section>
<!-- Modal -->
<div id="RapidPayModal" class="modal fade" role="dialog" style="position: fixed;">
  <div class="modal-dialog">
    <!-- Pay Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Rapid Pay</h4>
      </div>
      <div class="modal-body">
        <p>Select One</p>
        <label class="selectgroup-item">
          <input type="radio" name="n_type" value="email" class="selectgroup-input" data-on="{{$client->email}}" checked> 
          <span class="selectgroup-button">Email</span>
        </label>
        <label class="selectgroup-item">
          <input type="radio" name="n_type" value="text" class="selectgroup-input" data-on="{{$client->cell_phone}}">
          <span class="selectgroup-button">Text</span>
        </label>
        <input class="form-control contact_info" type="text" name="contact_info" placeholder="Email" value="{{$client->email}}" required>
        <input type="hidden" class="invoice_id" name="invoice_id" value="">
        <div class="invalid-feedback c_info_err">This is required!</div>
      </div>
      <div class="modal-footer text-right">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary sendbtn">Send</button>
      </div>
    </div>
  </div>
</div>
@csrf
@endsection
@push('footer_script')
<script type="text/javascript">
$(document).ready(function(){
  $('.nav-link').on('click', function(){
    var h = $(this).attr('href');
    window.location.href = h;
  });
  $('.rapid_pay_btn').on('click', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('.invoice_id').val(id);
    $('#RapidPayModal').modal('show');
  });
  $('input[name="n_type"]').on('click', function(){
    $('.c_info_err').hide();
    var on = $(this).data('on');
    $('.contact_info').val(on);
    var v = $(this).val();
    if(v == 'email') {
      //$('.contact_info').mask('+100000000000');
      $('.contact_info').attr('placeholder', 'Email');
    }
    else {
      //$('.contact_info').mask('+100000000000');
      $('.contact_info').attr('placeholder', 'Cell Phone');
    }
  });

  $('.sendbtn').on('click', function(){
    var r = $('.contact_info').val();
    if(r == '') {
      $('.c_info_err').show();
      return false;
    }
    else {
      $('.c_info_err').hide();
    }

    var id = $('.invoice_id').val();
    var contact_info = $('.contact_info').val();
    var n_type = $('input[name="n_type"]:checked').val();
    var csrf1 = $('input[name="_token"]').val();

    if(n_type == 'text') {
      var filter = /^\+(?:[0-9] ?){6,14}[0-9]$/;
      if (filter.test(contact_info)) { }
      else {
        alert('Phone number is not valid!');
        return false;
      }
    }

    $.ajax({
     type:"post",
     url:"{{ url('send_invoice') }}",
     data: {id: id, contact_info: contact_info, n_type: n_type, _token: csrf1},
     success:function(res)
     {       
        alert('Send successfully!');
        location.reload();
      }

    });
  });
});
</script>
@endpush 