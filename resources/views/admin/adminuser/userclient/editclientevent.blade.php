@extends('layouts.admin-master')

@section('title')
View client
@endsection

@push('header_styles')
<link  href="{{ asset('assets/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" media="screen">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
.selectgroup-button {
  min-width: 100%;
  width: 35px;
  padding: 0;
}  
.selectgroup-pills .selectgroup-item {
    margin-right: 3px;
}
select[name="event_reminder[type][]"] {
  width: calc(100% - 30px);
  display: inline-block;
  margin-right: 8px;
}
.fc-time-grid .fc-event .fc-time,
.fc-day-grid-event .fc-time {
  display: none;
}
</style>
@endpush 

@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('admin.adminuser.userclient.client_header') 
<!--new-header Close-->
  
   <div class="section-body events-table">
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
              <h2>Edit Event</h2>
              <div class="row">
                <div class="col-md-8">
                  <form action="{{ url('firm/client/create_client_event') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
                    <div class="row">  
                      <div class="col-md-4">
                        Event Type * 
                      </div>
                      <div class="col-md-8">
                        <select name="event_type" class="selectpicker1" required="required">
                          <option value="">Select One</option>
                          <option value="Reminder" <?php if($event->event_type == 'Reminder') { echo 'selected="selected"'; } ?>>Reminder</option>
                          <option value="Consultation" <?php if($event->event_type == 'Consultation') { echo 'selected="selected"'; } ?>>Consultation</option>
                          <option value="Court Date" <?php if($event->event_type == 'Court Date') { echo 'selected="selected"'; } ?>>Court Date</option>
                          <option value="Other" <?php if($event->event_type == 'Other') { echo 'selected="selected"'; } ?>>Other</option>
                        </select>
                      </div>
                    </div>
                    <br>
                    <div class="row">  
                      <div class="col-md-4">
                        Event Title * 
                      </div>
                      <div class="col-md-8">
                        <input type="text" placeholder="Event Title" name="event_title" class="form-control" value="{{$event->event_title}}">
                      </div>
                    </div>
                    <br>
                    <div class="row">  
                      <div class="col-md-4">
                        Event Description 
                      </div>
                      <div class="col-md-8">
                        <textarea placeholder="Event Description" name="event_description" class="form-control">{{$event->event_description}}
                        </textarea>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        Start Date * 
                      </div>
                      <div class="col-md-8">
                        <div class="input-group date form_datetime" data-date-format="mm/dd/yyyy HH:ii p">
                          <input class="form-control" size="16" type="text" value="{{ date('m/d/Y', strtotime($event->s_date)) }} {{$event->s_time}}" placeholder="Event date" name="s_date">
                          <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                      </div>
                    </div>
                    <br class="enddatewrpper" style="<?php if($event->event_type == 'Reminder') { echo 'display: none;'; } ?>">
                    <div class="row enddatewrpper" style="<?php if($event->event_type == 'Reminder') { echo 'display: none;'; } ?>">
                      <div class="col-md-4">
                        End Date * 
                      </div>
                      <div class="col-md-8">
                        <div class="input-group date to_datetime" data-date-format="mm/dd/yyyy HH:ii p">
                          <input class="form-control" size="16" type="text" value="{{ date('m/d/Y', strtotime($event->e_date)) }} {{$event->e_time}}" placeholder="Event date" name="e_date">
                          <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                      </div>
                    </div>
                    <br>
                    
                    <?php if(empty($event->event_reminder)) { ?>
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
                    <?php } else { 
                      $event_reminder = json_decode($event->event_reminder, true);
                      echo '<div class="remiderwrap">';
                      foreach ($event_reminder['type'] as $k => $v) { ?>
                      
                      <div class="row">
                        <div class="col-md-4">
                          Notification
                        </div>
                        <div class="col-md-3">
                          <input type="number" placeholder="" name="event_reminder[count][]" class="form-control" value="<?php echo $event_reminder['count'][$k]; ?>" min="1">
                        </div>
                        <div class="col-md-5">
                          <select name="event_reminder[type][]" class="form-control">
                            <option value="minutes" <?php if($v == 'minutes') { echo 'selected'; } ?>>minutes</option>
                            <option value="hours" <?php if($v == 'hours') { echo 'selected'; } ?>>hours</option>
                            <option value="days" <?php if($v == 'days') { echo 'selected'; } ?>>days</option>
                            <option value="weeks" <?php if($v == 'weeks') { echo 'selected'; } ?>>weeks</option>
                            <option value="months" <?php if($v == 'months') { echo 'selected'; } ?>>months</option>
                            <option value="years" <?php if($v == 'years') { echo 'selected'; } ?>>years</option>

                          </select>
                          <?php if($k) { ?>
                            <a href="#" class="remover"><i class="fa fa-times"></i></a>
                          <?php } ?>
                        </div>
                      </div>
                     
                    <?php } echo '</div>'; } ?>
                    
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
                        <input type="hidden" name="lead_id" value="{{ $client->id }}" >
                        <input type="hidden" name="who_consult_with" value="{{ $client->user_id }}" >
                        <input type="hidden" name="event_id" value="{{$event->id}}" />  
                        @csrf
                        <input type="submit" name="save" value="Update Event" class="btn btn-primary saveclientinfo_form"/>
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
</section>
@endsection


@push('footer_script')
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  setTimeout(function(){
    $('.selectpicker1').selectpicker();
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
  $('.saveclientinfo_form').on('click', function(e){
    e.preventDefault();
    var event_reminder = $('[name*=event_reminder]').serializeArray();
    var lead_id = $('input[name="lead_id"]').val();
    var s_date = $('input[name="s_date"]').val();
    var e_date = $('input[name="e_date"]').val();
    var event_title = $('input[name="event_title"]').val();
    var event_type = $('select[name="event_type"]').val();
    var who_consult_with = $('input[name="who_consult_with"]').val();
    var _token = $('input[name="_token"]').val();
    var coutner = 1;
    var event_description = $('textarea[name="event_description"]').val();
    var event_id = $('input[name="event_id"]').val();
    var reschedule = false;
    $.ajax({
      type:"post",
      url:"{{ url('admin/userclient/createclientevent') }}",
      data: {s_date:s_date, e_date:e_date, event_title:event_title, event_type:event_type, lead_id:lead_id, who_consult_with:who_consult_with, _token:_token, reschedule:reschedule, coutner:coutner, event_reminder: event_reminder, event_description: event_description, event_id: event_id},
      success:function(res)
      {       
        res = JSON.parse(res);
        if(res.status) {
          window.location.href = "{{ url('admin/userclient/clientsevents') }}/{{$client->user_id}}";
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
$(document).on('click', '.remover', function(e){
  e.preventDefault();
  $(this).closest('.row').remove();
});

//================ Edit user ============//

</script>
<style type="text/css">
  .card .card-header .btn {
    margin-top: 1px;
    padding: 2px 12px;
  }
</style>
@endpush 