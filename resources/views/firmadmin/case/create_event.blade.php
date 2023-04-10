@extends('firmlayouts.admin-master')

@section('title')
Schedule a consult 
@endsection

@push('header_styles')
<link  href="{{ asset('assets/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" media="screen">
<link  href="{{  asset('assets/css/main.min.css') }}" rel="stylesheet">
<link  href="{{  asset('assets/css/daygrid.min.css') }}" rel="stylesheet">
<link  href="{{  asset('assets/css/timegrid.min.css') }}" rel="stylesheet">
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
  width: calc(100% - 20px);
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
<section class="section client-listing-details task-new-header-tasks">
<!--new-header open-->
  @include('firmadmin.case.case_header')
<!--new-header Close-->

  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
            <a href="{{ url('firm/case/case_event') }}/{{$case->id}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
            <?php 
            if(empty($access_token)) {
              echo '<a href="'.$authUrl.'" class="google_btn" data-toggle="tooltip"  title="Login with google">
              <img src="'.url('/assets/images/google_logo.png').'">
              </a>';
            } 
            ?>
            <h4></h4>
            <a class="btn btn-primary trigger--fire-modal-2 card-header-action" id="fire-modal-2" href="#">Create New Event</a>
          </div>
          <div class="card-body">
            <div id='calendar'></div>
          </div>
        
      </div>
        </div>
      </div>
  </div>
</section>
    <?php 
    $style='display: none;';
    $reschedule='';
    if(isset($_REQUEST['reschedule']) && $_REQUEST['reschedule']==1){ 
      $reschedule=1;
     } ?>
  <div class="modalformpart" id="modal-form-part" data-reschedule="<?php echo $reschedule; ?>" style="display: none;">
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
          <option value="Reminder" <?php if($dateandtime['event_type'] == 'Reminder') { echo 'selected="selected"'; } ?>>Reminder</option>
          <option value="Consultation" <?php if($dateandtime['event_type'] == 'Consultation') { echo 'selected="selected"'; } ?>>Consultation</option>
          <option value="Court Date" <?php if($dateandtime['event_type'] == 'Court Date') { echo 'selected="selected"'; } ?>>Court Date</option>
          <option value="Other" <?php if($dateandtime['event_type'] == 'Other') { echo 'selected="selected"'; } ?>>Other</option>
        </select>
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-4">
        Event Title * 
      </div>
      <div class="col-md-8">
        <input type="text" placeholder="Event Title" name="event_title" class="form-control" value="<?php echo $dateandtime['event_title']; ?>">
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

<option value="{{$user->id}}" <?php if($user->userset==1){  echo 'selected="selected"';   } elseif ($user_id == $user->id && !isset($_GET['reschedule'])) {
  echo 'selected="selected"';
} ?>>{{$user->name}}</option>
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
        <input type="hidden" name="lead_id" value="{{ $id }}" >  
        <input type="hidden" name="event_id" value="0" />
        @csrf
        <input type="submit" name="save" value="Create" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>



@endsection

@push('footer_script')
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-datetimepicker.js') }}" charset="UTF-8"></script>
<script src="{{ asset('assets/js/main.min.js') }}"></script>
<script src="{{ asset('assets/js/interaction.min.js') }}"></script>
<script src="{{ asset('assets/js/daygrid.min.js') }}"></script>
<script src="{{ asset('assets/js/timegrid.min.js') }}"></script>
<script src="{{ asset('assets/js/list.min.js') }}"></script>
<script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script>
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
      <?php if($dateandtime['event_type'] == 'Reminder') { ?>
      // $('.datepicker').daterangepicker({
      //     timePicker: true,
      //     singleDatePicker: true,
      //     endDate: moment().startOf('hour').add(32, 'hour'),
      //     locale: {
      //       format: 'MM/DD/YYYY hh:mm A'
      //     },
      //     minDate: new Date()
      // });
    <?php } else { ?>
      // $('.datepicker').daterangepicker({
      //     timePicker: true,
      //     endDate: moment().startOf('hour').add(32, 'hour'),
      //     locale: {
      //       format: 'MM/DD/YYYY hh:mm A'
      //     },
      //     minDate: new Date()
      // });
    <?php } ?>
    },1000);
    if($('.modalformpart').data('reschedule')==1)
    {
      
        setTimeout(function(){
          console.log('show');
        $('#fire-modal-2').trigger('click');
      },1000);
    }

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
      var coutner = parseInt("{{$dateandtime['coutner']}}")+1;
      var event_description = $('textarea[name="event_description"]').val();
      var event_id = $('input[name="event_id"]').val();
      var reschedule = false;
      <?php if(isset($_GET['reschedule'])) { ?>
        var reschedule = true;
      <?php } ?>
	    $.ajax({
	    	type:"post",
	    	url:"{{ url('firm/case/create_case_event') }}",
	    	data: {s_date:s_date, e_date:e_date, event_title:event_title, event_type:event_type, lead_id:lead_id, who_consult_with:who_consult_with, _token:_token, reschedule:reschedule, coutner: coutner, event_reminder: event_reminder, event_description: event_description, event_id: event_id},
	    	success:function(res)
	    	{       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/case/case_event') }}/{{$case->id}}";
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
        $('#fire-modal-2').trigger('click');
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
        $('select[name="event_type"] option[value="'+info.event.extendedProps.event_type+'"').prop('selected', true);
        jQuery('.selectpicker1').selectpicker('refresh');
        $('textarea[name="event_description"]').val(info.event.extendedProps.description);
        $('#fire-modal-1 .modal-body > div').show();
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
    $.ajax({
        type:"get",
        url:"{{ url('delete_event') }}/"+event_id,
        data: {},
        success:function(res)
        {       
          window.location.href = "{{ url('firm/case/create_event') }}/{{$case->id}}";
        }
      });
  };
  $(document).on('click', '.remover', function(e){
    e.preventDefault();
    $(this).closest('.row').remove();
  });
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
</script>
@endpush