@extends('firmlayouts.admin-master')

@section('title')
Firm Calendar
@endsection

@push('header_styles')
<link  href="{{ asset('assets/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" media="screen">
<link  href="{{  asset('assets/css/main.min.css') }}" rel="stylesheet">
<link  href="{{  asset('assets/css/daygrid.min.css') }}" rel="stylesheet">
<link  href="{{  asset('assets/css/timegrid.min.css') }}" rel="stylesheet">
<link  href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
.modalformpart .row {
  margin-bottom: 5px;
}
.modalformpart .colorpickerinput {
    padding: 5px 10px !important;
    height: auto !important;
    color: #fff;
}

.fc-time-grid .fc-event .fc-time,
.fc-day-grid-event .fc-time {
  display: none;
}
</style>
@endpush 

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Firm Calendar</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/admindashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
             <?php if($firm->account_type == 'CMS') { ?>
             <?php 
            if(empty($access_token)) {
              echo '<a href="'.$authUrl.'" class="google_btn" data-toggle="tooltip"  title="Sync with Google Calendar">
              <img src="'.url('/assets/images/google_logo.png').'">
              </a>';
            } 
            else { ?>
                <a href="javascript:void(0);" class="btn btn-primary">
                    Synced with Google Calendar
                </a>
            <?php } 
            ?>
            <h4></h4>
             <a class="btn btn-primary trigger--fire-modal-2 card-header-action" id="fire-modal-2" href="#">Create New Event</a>
            <?php } ?>
          </div>
          <div class="card-body">
            <?php if($firm->account_type == 'CMS') { ?>
            <div id='calendar'></div>
            <?php } else { ?>
            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <br><br>
                                    <form action="{{url('firm/pay_for_cms')}}" method="post" id="payment-form" enctype="multipart/form-data">
                                        <div class="card card-info text-center">
                                          <br>
                                          <div class="card-body">
                                            <h6>
                                                <i class="fa fa-exclamation-triangle"></i> 
                                                This feature is for case management software users
                                            </h6>
                                            <h5 style="max-width: 320px;margin: 15px auto;">
                                                Get full CMS access for your Firm we are all using it.
                                            </h5>
                                            <h5>
                                                $<span class="annual_payment_cycletext">{{$firm->usercost}} a month</span> <br> per user
                                            </h5>

                                            <label class="custom-switch mt-2">
                                                <span class="custom-switch-description" style="margin: 0 .5rem 0 0;">Bill Annually</span> 
                                                <input type="checkbox" name="payment_cycle" class="custom-switch-input annual_payment_cycle" value="1" checked data-monthly_amount="{{$firm->usercost}}">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Bill Monthly</span>
                                            </label>
                                            <div class="saved_amount_text"></div>
                                          </div>
                                          <div class="card-footer">
                                            @csrf
                                            <input type="hidden" name="amount" value="55">
                                            <!-- <button type="button" name="payforcms" class="btn btn-primary payforcms">Get Started</button> -->
                                            <a href="{{url('firm/upgradetocms')}}" class="btn btn-primary">Upgrade</a>
                                          </div>
                                          <div class="payment-form-card" id="card-element" style="display: none;">
                                             <h2 class="provided_cost"></h2>
                                             <?php if(!empty($card)) {
                                              echo '<div class="row card-payno-tx"><div class="col-md-12 text center">Pay with existing card</div></div>';
                                              foreach ($card as $k => $v) {
                                              ?>
                                             <div class="row">
                                               <div class="col-md-8">
                                                 <label>
                                                   <input type="text" value="***********<?php echo $v->last4; ?>" class="form-control" readonly />
                                                   <input type="checkbox" value="<?php echo $v->id; ?>" name="card_source" style="display: none;"/>
                                                 </label>
                                               </div>
                                               <div class="col-md-4">
                                                 <input value="Pay" type="submit" class="paywith_existing btn btn-primary">
                                               </div>
                                             </div>
                                             <?php }
                                             echo '<div class="row"><div class="col-md-12 text center">OR, Pay with new card</div></div>';
                                             } ?>
                                             <div class="row card-payno">
                                                <div class="col-md-12"><div class="payment-input">
                                                  <input type="text" placeholder="Card Number" size="20" data-stripe="number"/></div></div>
                                               </div>
                                               <div class="row">
                                                <div class="col-md-6 col-sm-6"><div class="payment-input">
                                                  <input type="text" placeholder="Expiring Month" data-stripe="exp_month"/>
                                                </div>
                                              </div>
                                                <div class="col-md-6 col-sm-6"><div class="payment-input">
                                                  <input type="text" placeholder="Expiring Year" size="2" data-stripe="exp_year">
                                                </div>
                                              </div>
                                                
                                               </div>
                                               <div class="row">
                                                <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="CVV Code" size="4" data-stripe="cvc"/></div></div>
                                                <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="Postal Code" size="6" data-stripe="address_zip"/></div></div>
                                                
                                               </div>              
                                             <div class="submit-login">
                                              @csrf
                                              <input value="Upgrade" type="submit" class="submit">
                                             </div>
                                             
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
            <?php } ?>
          </div>
        
      </div>
        </div>
      </div>
  </div>
</section>

<div class="modalformpart" id="modal-form-part" style="display: none;">
  <a href="#" class="delete_event action_btn customedit_btn" onclick="if (confirm('Are you sure you want to delete?')) { delete_event_callback(); } else { return false; }" style="
  z-index: 9;
  top: -38px;
  right: 50px;
  display: none;">
    <img src="{{ url('/') }}/assets/images/icons/case-icon3.svg">
  </a>
  <form action="{{ url('firm/lead/create_lead_event') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">  
    <div class="col-md-4">
      Event Type * 
    </div>
    <div class="col-md-8">
      <select name="event_type" class="selectpicker1" required="required">
        <option value="">Select One</option>
        <option value="Reminder">Reminder</option>
        <option value="Consultation">Consultation</option>
        <option value="Court Date">Court Date</option>
        <option value="Other">Other</option>
      </select>
    </div>
  </div>
  <br>
  <div class="row">  
    <div class="col-md-4">
      Event Title * 
    </div>
    <div class="col-md-8">
      <input type="text" placeholder="Event Title" name="event_title" class="form-control" value="">
    </div>
  </div>
  <br>
  <div class="row">  
    <div class="col-md-4">
      Event Description 
    </div>
    <div class="col-md-8">
      <textarea placeholder="Event Description" name="event_description" class="form-control">
      </textarea>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      Start Date * 
    </div>
    <div class="col-md-8">
      <div class="input-group date form_datetime" data-date-format="mm/dd/yyyy HH:ii p">
          <input class="form-control" size="16" type="text" value="" placeholder="Event date" name="s_date">
          <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
      </div>
    </div>
  </div>
  <br class="enddatewrpper">
  <div class="row enddatewrpper">
    <div class="col-md-4">
      End Date * 
    </div>
    <div class="col-md-8">
      <div class="input-group date to_datetime" data-date-format="mm/dd/yyyy HH:ii p">
          <input class="form-control" size="16" type="text" value="" placeholder="Event date" name="e_date">
          <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
      </div>
    </div>
  </div>
  <br>
  <div class="row">  
    <div class="col-md-4" style="padding-right :0;">
      Assign To *
    </div>
    <div class="col-md-8">
      
      <select class="selectpicker1" name="who_consult_with" required="required" multiple style="height: 150px;">
        <!-- <option value="">select</option> -->
        @if (!$users->isEmpty())
        @foreach ($users as $user)
          <option value="{{$user->id}}">{{$user->name}}</option>
        @endforeach
        @endif
      </select>
    </div>
  </div>
  <br class="related_to" style="display: none;">
  <div class="row related_to" style="display: none;">  
    <div class="col-md-4" style="padding-right :0;">
      Related To 
    </div>
    <div class="col-md-8">
      
      <select class="selectpicker1" name="related_to" required="required" style="height: 150px;">
        <option value="">select</option>
        <option value="NONE">None</option>
        <option value="LEAD">Lead</option>
        <option value="CASE">Case</option>
        <option value="CLIENT">Client</option>
      </select>
    </div>
  </div>
  <br class="firmlead" style="display: none;">
  <div class="row firmlead" style="display: none;">  
    <div class="col-md-4" style="padding-right :0;">
      Lead
    </div>
    <div class="col-md-8">
      
      <select class="selectpicker1" name="firmlead" style="height: 150px;">
        <option value="">select</option>
        <?php if(!empty($firmlead)) { ?>
        @foreach ($firmlead as $l)
          <option value="{{$l->id}}">{{$l->name}}</option>
        @endforeach
        <?php } ?>
      </select>
    </div>
  </div>
  <br class="firmcase" style="display: none;">
  <div class="row firmcase" style="display: none;">  
    <div class="col-md-4" style="padding-right :0;">
      Case
    </div>
    <div class="col-md-8">
      
      <select class="selectpicker1" name="firmcase" style="height: 150px;">
        <option value="">select</option>
        @if (!$firmcase->isEmpty())
        @foreach ($firmcase as $c)
          <option value="{{$c->id}}">{{$c->case_type}}</option>
        @endforeach
        @endif
      </select>
    </div>
  </div>
  <br class="firmclient" style="display: none;">
  <div class="row firmclient" style="display: none;">  
    <div class="col-md-4" style="padding-right :0;">
      Client
    </div>
    <div class="col-md-8">
      
      <select class="selectpicker1" name="firmclient" style="height: 150px;">
        <!-- <option value="">select</option> -->
        @if (!$firmclient->isEmpty())
        @foreach ($firmclient as $cl)
          <option value="{{$cl->id}}">{{$cl->first_name}} {{$cl->middle_name}} {{$cl->last_name}}</option>
        @endforeach
        @endif
      </select>
    </div>
  </div>
  <br>
  <div class="remiderwrap">
    <div class="row">
      <div class="col-md-4">
        Notification
      </div>
      <div class="col-md-3">
        <input type="number" placeholder="" name="event_reminder[count][]" class="form-control" value="1" min="1">
      </div>
      <div class="col-md-5">
        <select name="event_reminder[type][]" class="form-control">
          <option value="minutes">minutes</option>
          <option value="hours">hours</option>
          <option value="days">days</option>
          <option value="weeks">weeks</option>
          <option value="months">months</option>
          <option value="years">years</option>
        </select>
      </div>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-10"></div>
    <div class="col-md-2 text-right">
      <a href="#" class="btn btn-primary addmorereminder">
        <i class="fa fa-plus"></i>
      </a>
    </div>
  </div>
  <br>
  <div class="row">  
    <div class="col-md-12 text-right">
      <input type="hidden" name="lead_id" value="" >  
      <input type="hidden" name="event_id" value="0" />
      @csrf
      <input type="submit" name="save" value="Create" class="btn btn-primary saveclientinfo_form"/>
    </div>
  </div>
  </form>
</div>
<!-- Add Note Modal -->
<div id="AddUserWrapper" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Pay Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="float: right;
          position: absolute;
          right: 22px;
          top: 15px;
          ">&times;</button>
          <h4 class="modal-title">Add New User</h4>
        </div>
        <div class="modal-body">
          <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Name" name="user_name" class="form-control" required="" value="<?php if(isset(Session::get('data')['name'])) { echo Session::get('data')['name']; }?>"> 
                <div class="invalid-feedback">Name is required!</div>
              </div>
            </div> 
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email Address
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="email" placeholder="Email Address" name="email" class="form-control" required="" value="<?php if(isset(Session::get('data')['email'])) { echo Session::get('data')['email']; }?>"> 
                <div class="invalid-feedback">Email Address is required!</div> 
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="" name="Role_type">
                  <?php if($firm->account_type == 'CMS') { ?>
                    <option value="4" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == '4') { echo 'selected="selected"'; }?>>Firm Admin</option>
                    <option value="5" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == '5') { echo 'selected="selected"'; }?>>Firm User</option>
                  <?php } else { ?>
                    <option value="5" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == '5') { echo 'selected="selected"'; }?>>Attorney</option>
                  <?php } ?>
                </select>
                <div class="invalid-feedback">Please select Role!</div> 
              </div>
            </div>
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7 text-right">
                @csrf
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary createfirmuserbtn" type="submit" name="create_firm_user" value="Create and Add More">
                  <span>Create</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection

@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script src="{{ asset('assets/js/main.min.js') }}"></script>
<script src="{{ asset('assets/js/interaction.min.js') }}"></script>
<script src="{{ asset('assets/js/daygrid.min.js') }}"></script>
<script src="{{ asset('assets/js/timegrid.min.js') }}"></script>
<script src="{{ asset('assets/js/list.min.js') }}"></script>
<script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script>
  
  $(document).ready(function(){
    $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var inps = document.getElementsByName('setting[]');
      var s = {};
      for (var i = 0; i <inps.length; i++) {
        var inp=inps[i];
        var key = inp.getAttribute('data-key');
        s[key] = inp.value;
      }
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/setting/calendar_setting') }}",
        data: { setting: s, _token:_token },
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = window.location.href;
          }
          else {
            alert('Mendatory fields are required!')
          }
        }
      });
    });
    setTimeout(function(){
      $(".colorpickerinput").colorpicker({
        format: 'hex',
        component: '.input-group-append',
      });
      var s_date = $('.form_datetime').datetimepicker({
        startDate: new Date(),
        todayBtn:  1,
        autoclose: 1,
        showMeridian: 1
      }).on('changeDate', function(e) {
        console.log(e.date);
        $('.to_datetime').datetimepicker('remove');
        $('.to_datetime').datetimepicker({
          startDate: e.date,
          todayBtn:  1,
          autoclose: 1,
          showMeridian: 1
        });
      });
      var e_date = $('.to_datetime').datetimepicker({
        startDate: new Date(),
        todayBtn:  1,
        autoclose: 1,
        showMeridian: 1
      });
    },1000);
    $('.colorpickerinput').on('change', function(){
      var v = $(this).val();
      $(this).css('background', v);
    });
    $("#fire-modal-2").fireModal({title: 'Create an event', body: $("#modal-form-part"), center: true});
    $('#fire-modal-2').on('click', function(){
      $('input[name="event_title"').val('');
      $('.to_datetime input').val('');
      $('textarea[name="event_description"]').val('');
      $('select[name="who_consult_with"]').val('');
      $('input[name="event_id"').val(0);
      $('#fire-modal-1 .modal-header h5.modal-title').text('Create an event');
      $('.saveclientinfo_form').val('Create');
      jQuery('.selectpicker1').selectpicker('refresh');
      $('.delete_event').hide();
      $('.related_to').show();
    });
    $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var event_reminder = $('[name*=event_reminder]').serializeArray();
      var lead_id = $('input[name="lead_id"]').val();
      var s_date = $('input[name="s_date"]').val();
      var e_date = $('input[name="e_date"]').val();
      var event_type = $('select[name="event_type"]').val();
      var event_title = $('input[name="event_title"]').val();
      var who_consult_with = $('select[name="who_consult_with"]').val();
      var _token = $('input[name="_token"]').val();
      var coutner = 1;
      var event_description = $('textarea[name="event_description"]').val();
      var event_id = $('input[name="event_id"]').val();
      var related_to = $('select[name="related_to"').val();
      if(event_id == 0) {
        if(related_to == '') {
          alert('Plese select related to'+related_to);
          return false;
        }
        if(related_to == 'LEAD') {
          lead_id = $('select[name="firmlead"]').val();
        }
        if(related_to == 'CASE') {
          lead_id = $('select[name="firmcase"]').val();
        }
        if(related_to == 'CLIENT') {
          lead_id = $('select[name="firmclient"]').val();
        }
        if(lead_id == 0 && related_to != 'NONE') {
          alert('Plese select '+related_to.toLowerCase());
          return false;
        }
      }
      var reschedule = false;
      <?php if(isset($_GET['reschedule'])) { ?>
        var reschedule = true;
      <?php } ?>
      $.ajax({
        type:"post",
        url:"{{ url('firm/calendar/create_firm_event') }}",
        data: {s_date:s_date, e_date:e_date, event_title:event_title, event_type:event_type, lead_id:lead_id, who_consult_with:who_consult_with, _token:_token, reschedule:reschedule, coutner: coutner, event_reminder: event_reminder, event_description: event_description, event_id: event_id, related_to: related_to},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/calendar') }}";
          }
          else {
            alert(res.msg);
          }
          console.log(res);
        }
      });
    });
    $('select[name="event_type"]').on('change', function(){
      var v = $(this).val();
      if(v == 'Reminder') {
        $('.enddatewrpper').hide();
      }
      else {
        $('.enddatewrpper').show();
      }
    });
    $('select[name="related_to"]').on('change', function(){
      var v = $(this).val();
      $('.firmlead').hide();
      $('.firmcase').hide();
      $('.firmclient').hide();
      if(v == 'LEAD') {
        $('.firmlead').show();
      }
      else if(v == 'CASE') {
        $('.firmcase').show();
      }
      else if(v == 'CLIENT') {
        $('.firmclient').show();
      }
    });
    $('.addmorereminder').on('click', function(e){
      e.preventDefault();
      var n = $('.remiderwrap > div').length;
      var tr = '<div class="row"><div class="col-md-4">Notification</div>';
      tr += '<div class="col-md-3">';
      tr += '<input type="number" placeholder="" name="event_reminder[count][]" class="form-control" value="1" min="1">';
      tr += '</div><div class="col-md-5">';
      tr += '<select name="event_reminder[type][]" class="form-control">';
      tr += '<option value="minutes">minutes</option>';
      tr += '<option value="hours">hours</option>';
      tr += '<option value="days">days</option>';
      tr += '<option value="weeks">weeks</option>';
      tr += '<option value="months">months</option>';
      tr += '<option value="years">years</option>';
      tr += '</select>';
      tr += '<a href="#" class="remover"><i class="fa fa-times"></i></a></div></div>';
      if(n < 5) {
        $('.remiderwrap').append(tr);
      }
    });
  });
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var events = '{{$events}}';
    var varTitle = $('<textarea />').html(events).text();
    console.log(eval(varTitle));
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
      defaultView: 'timeGridWeek',
      defaultDate: '<?php echo date('Y-m-d'); ?>',
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      events: eval(varTitle),
      eventLimit: true,
      views: {
        timeGridDay: {
          eventLimit: 2 // adjust to 6 only for timeGridWeek/timeGridDay
        },
        timeGridWeek: {
          eventLimit: 2
        }
        ,
        dayGridMonth: {
          eventLimit: 2
        },
        dayGrid: {
          eventLimit: 2 // options apply to dayGridMonth, dayGridWeek, and dayGridDay views
        },
        timeGrid: {
          eventLimit: 2 // options apply to timeGridWeek and timeGridDay views
        },
        week: {
          eventLimit: 2 // options apply to dayGridWeek and timeGridWeek views
        },
        day: {
          eventLimit: 2 // options apply to dayGridDay and timeGridDay views
        }
      },
      dateClick: function(info) {
        
        // console.log(info.event.title);
        if($(info.dayEl).hasClass('fc-past')) {
          return false;
        }
        ev_date = info.date; 
        var years = ev_date.getFullYear();
        var months = ev_date.getMonth()+1;
        months = months < 10 ? '0'+months : months;
        var day = ev_date.getDate();
        day = day < 10 ? '0'+day : day;
        var hours = ev_date.getHours();
        hours = hours < 10 ? '0'+hours : hours;

        var minutes = ev_date.getMinutes();
        minutes = minutes < 10 ? '0'+minutes : minutes;

        var seconds = ev_date.getSeconds();
        seconds = seconds < 10 ? '0'+seconds : seconds;

        var data1 = hours+':'+minutes+':'+seconds;
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        
        var strTime = hours + ':' + minutes + ' ' + ampm;
        var date = months+'/'+day+'/'+years+' '+strTime;
        $('input[name="s_date"').val(date);
        $('.to_datetime').datetimepicker('remove');
        $('.to_datetime').datetimepicker({
          startDate: date,
          todayBtn:  1,
          autoclose: 1,
          showMeridian: 1
        });
        $('select[name="related_to"').val('')
        $('input[name="lead_id"').val(0);
        $('input[name="event_id"').val(0);
        $('#fire-modal-2').trigger('click');
        
        $('.firmlead').hide();
        $('.firmcase').hide();
        $('.firmclient').hide();
        $('#fire-modal-1 .modal-body > div').show();
      },
      eventClick: function(info) {
        
        if($(info.dayEl).hasClass('fc-past')) {
          return false;
        }
        var who_consult_with = info.event.extendedProps.who_consult_with;
        var event_id = info.event.extendedProps.event_id;
        ev_date = info.event.start; 
        var years = ev_date.getFullYear();
        var months = ev_date.getMonth()+1;
        months = months < 10 ? '0'+months : months;
        var day = ev_date.getDate();
        day = day < 10 ? '0'+day : day;
        var hours = ev_date.getHours();
        hours = hours < 10 ? '0'+hours : hours;

        var minutes = ev_date.getMinutes();
        minutes = minutes < 10 ? '0'+minutes : minutes;

        var seconds = ev_date.getSeconds();
        seconds = seconds < 10 ? '0'+seconds : seconds;

        var data1 = hours+':'+minutes+':'+seconds;
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        
        var strTime = hours + ':' + minutes + ' ' + ampm;
        var date = months+'/'+day+'/'+years+' '+strTime;
        $('#fire-modal-2').trigger('click');
        if(who_consult_with) {
          who_consult_with.forEach(function(item, index){
            console.log(item);
            console.log(index);
            $('select[name="who_consult_with"] option[value="'+item+'"]').prop('selected', true);
          });
        }
        console.log(info.event.extendedProps.event_type);
        $('input[name="event_id"').val(event_id);
        
        $('input[name="s_date"').val(date);
        $('input[name="e_date"]').val(info.event.extendedProps.event_end)
        $('.to_datetime').datetimepicker('remove');
        $('.to_datetime').datetimepicker({
          startDate: date,
          todayBtn:  1,
          autoclose: 1,
          showMeridian: 1
        });
        if(info.event.extendedProps.event_type == 'Reminder') {
          $('.enddatewrpper').hide();
        }
        else {
          $('.enddatewrpper').show();
        }
        $('input[name="event_title"').val(info.event.extendedProps.act_title);
        $('input[name="lead_id"').val(info.event.extendedProps.related_id);
        $('select[name="event_type"] option[value="'+info.event.extendedProps.event_type+'"').prop('selected', true);
        jQuery('.selectpicker1').selectpicker('refresh');
        $('textarea[name="event_description"]').val(info.event.extendedProps.description);
        $('#fire-modal-1 .modal-body > div').show();
        $('select[name="related_to"').val('')
        $('.related_to').hide();
        $('.firmlead').hide();
        $('.firmcase').hide();
        $('.firmclient').hide();
        $('#fire-modal-1 .modal-header h5.modal-title').text('Update event');
        $('.saveclientinfo_form').val('Update');
        $('.delete_event').show();
        $('.delete_event').data('event_id', event_id);
        info.jsEvent.preventDefault();
      }
    });

    calendar.render();
  });
  function delete_event_callback(){
    var event_id = $('.delete_event').data('event_id');
    console.log(event_id);
    $.ajax({
        type:"get",
        url:"{{ url('delete_event') }}/"+event_id,
        data: {},
        success:function(res)
        {       
          window.location.href = "{{ url('firm/calendar') }}";
        }
      });
  };
  $(document).on('change', 'select[name="event_reminder[type][]"]', function(e){
    e.preventDefault(); 
    var d = $('.datepicker').val();
    var d1 = $(this).closest('.row').find('input[name="event_reminder[count][]"]').val();
    var d2 = $(this).closest('.row').find('select[name="event_reminder[type][]"]').val();
    var ndate = new Date(d);
    var result = new Date();
    if(d2 == 'minutes') {
      result.setTime(result.getTime() + parseInt(d1)*60000);
    }
    else if(d2 == 'hours') {
      result.setHours(result.getHours()+parseInt(d1));
    }
    else if(d2 == 'days') {
      result.setDate(result.getDate() + parseInt(d1));
    }
    else if(d2 == 'weeks') {
      result.setDate(result.getDate() + parseInt(d1)*7);
    }
    else if(d2 == 'months') {
      result.setMonth(result.getMonth()+parseInt(d1));
    }
    else if(d2 == 'years') {
      result.setFullYear(result.getFullYear() + parseInt(d1));
    }
    if(ndate.getTime() < result.getTime()) {
      $(this).closest('.row').find('input[name="event_reminder[count][]"]').val(1)
      $(this).closest('.row').find('select[name="event_reminder[type][]"]').val('minutes')
      alert('Notification time can not be greater than event end time')
    }
  });
  $(document).on('change', 'input[name="event_reminder[count][]"]', function(e){
    e.preventDefault(); 
    var d = $('.datepicker').val();
    var d1 = $(this).closest('.row').find('input[name="event_reminder[count][]"]').val();
    var d2 = $(this).closest('.row').find('select[name="event_reminder[type][]"]').val();
    var ndate = new Date(d);
    var result = new Date();
    if(d2 == 'minutes') {
      result.setTime(result.getTime() + parseInt(d1)*60000);
    }
    else if(d2 == 'hours') {
      result.setHours(result.getHours()+parseInt(d1));
    }
    else if(d2 == 'days') {
      result.setDate(result.getDate() + parseInt(d1));
    }
    else if(d2 == 'weeks') {
      result.setDate(result.getDate() + parseInt(d1)*7);
    }
    else if(d2 == 'months') {
      result.setMonth(result.getMonth()+parseInt(d1));
    }
    else if(d2 == 'years') {
      result.setFullYear(result.getFullYear() + parseInt(d1));
    }
    if(ndate.getTime() < result.getTime()) {
      $(this).closest('.row').find('input[name="event_reminder[count][]"]').val(1)
      $(this).closest('.row').find('select[name="event_reminder[type][]"]').val('minutes')
      alert('Notification time can not be greater than event end time')
    }
  });
Stripe.setPublishableKey("{{ env('SRTIPE_PUBLIC_KEY') }}");
$(document).ready(function(){
  $(".removeuserbtn").click(function(e) {
        e.preventDefault();
        var $this = $(this);
        var msg = $(this).data('msg');
        var id = $(this).data('id');
        if(msg != '') {
            var confirm=window.confirm(msg);
            if (confirm==true) {
                $.ajax({
                    type:"get",
                    url:"{{ url('firm/users/delete') }}/"+id,
                    data: {},
                    success:function(res) {       
                        alert('Firm User deleted successfully!');
                        $this.closest('tr').fadeOut();
                    }

                });
                
            }
        }
    });
    $('.adduserbtn').on('click', function(e){
        e.preventDefault();
        var client_id = $(this).data('client_id');
        $('#AddUserWrapper').modal('show');
    });
    $(".createfirmuserbtn").click(function(e) {
        e.preventDefault();
        var $this = $(this);
        var user_name = $('#AddUserWrapper form input[name="user_name"]').val();
        var email = $('#AddUserWrapper form input[name="email"]').val();
        var Role_type = $('#AddUserWrapper form select[name="Role_type"]').val();
        var _token = $('input[name="_token"]').val();

        $.ajax({
            type:"post",
            url:"{{ url('firm/users/createuser') }}",
            data: {
                _token:_token, 
                user_name:user_name, 
                email:email,
                Role_type:Role_type
            },
            success:function(res) { 
                res = JSON.parse(res);
                if(res.status) {
                    alert(res.msg);
                    window.location.href = "{{ url('firm/calendar') }}";
                }
                else {
                    alert(res.msg);
                }
            }

        });        
    });
    $(".paywithnewbtn").on('click', function(e){
      e.preventDefault();
      $('.newcardwrapper').slideToggle();
    });
  $('.paywith_existing').on('click', function() {
    console.log('1');
    $('input[name="card_source"]').prop('checked', false);
    $(this).closest('.row').find('input[type="checkbox"]').prop('checked', true);
  });
  $('.payforcms').on('click', function(e){
    e.preventDefault();
    $(this).hide();
    $('#card-element').slideDown();
  })
});
$(function() {
var $form = $('#payment-form');
$form.submit(function(event) {
  if(!$('input[name="card_source"]').is(':checked')) {
    // Disable the submit button to prevent repeated clicks:
    $form.find('.submit').prop('disabled', true);

    // Request a token from Stripe:
    Stripe.card.createToken($form, stripeResponseHandler);

    // Prevent the form from being submitted:
    return false;
  }
});
});

function stripeResponseHandler(status, response) {
// Grab the form:
var $form = $('#payment-form');

if (response.error) { // Problem!

  // Show the errors on the form:
  $form.find('.payment-errors').text(response.error.message);
  $form.find('.submit').prop('disabled', false); // Re-enable submission

} else { // Token was created!

  // Get the token ID:
  var token = response.id;

  // Insert the token ID into the form so it gets submitted to the server:
  // $form1 = $('#payment-form-res');
  $form.append($('<input type="hidden" name="stripeToken">').val(token));

  // Submit the form:
  $form.get(0).submit();
}
};
</script>
@endpush