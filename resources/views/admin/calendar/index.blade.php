@extends('layouts.admin-master')

@section('title')
Calendar
@endsection

@push('header_styles')
<link  href="{{ asset('assets/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" media="screen">
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link  href="{{  asset('assets/css/main.min.css') }}" rel="stylesheet">
<link  href="{{  asset('assets/css/daygrid.min.css') }}" rel="stylesheet">
<link  href="{{  asset('assets/css/timegrid.min.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
.fc-time-grid .fc-event .fc-time,
.fc-day-grid-event .fc-time {
  display: none;
}  
</style>
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Calendar</h1>
    <div class="section-header-breadcrumb">
      <a class="btn btn-primary trigger--fire-modal-2" id="fire-modal-2" href="#" style="width: auto; padding: 0 18px;">
        <i class="fa fa-plus"></i> Add Event
      </a>
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <?php 
            if(empty($access_token)) {
              $login_url = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode('https://www.googleapis.com/auth/calendar') . '&redirect_uri=' . urlencode( url('admin/calendar') ) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
              echo '<a href="'.$login_url.'" class="btn btn-primary">Login with Google</a>';
            } 
            ?>
            <div class="card-header-action">
              <a href="{{ url('admin/calendarsetting') }}" class="btn btn-primary">
                <i class="fas fa-cogs"></i>
              </a>
            </div>
          </div>
          <div class="card-body">
            <div id='calendar'></div>
          </div>
        </div>
      </div>
     </div>
  </div>
</section>

<div class="modalformpart" id="modal-form-part" style="display: none;">
  <a href="#" class="delete_event action_btn customedit_btn" style="
    z-index: 9;
    top: -38px;
    right: 50px;
    display: none;">
      <img src="{{ url('/') }}/assets/images/icons/case-icon3.svg">
    </a>
  <form action="{{ url('admin/calendar') }}" method="post" class="needs-validation" enctype="multipart/form-data">
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
  <br>
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
  <br>
  <div class="row">
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
      Who is consult with? *
    </div>
    <div class="col-md-8">
      
      <select class="selectpicker1" name="who_consult_with" required="required" multiple style="height: 150px;" data-live-search="true">
        @if (!$users->isEmpty())
        @foreach ($users as $user)
        <option value="{{$user->id}}">{{$user->name}}</option>
        @endforeach
        @endif
      </select>
    </div>
  </div>
  <br>
  <div class="row">  
    <div class="col-md-12 text-right">
      @csrf
      <input type="hidden" name="event_id" value="0" />
      <input type="submit" name="save" value="Create" class="btn btn-primary saveclientinfo_form"/>
    </div>
  </div>
  </form>
</div>
<div class="rktesting"></div>
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
    },1000);
    $("#fire-modal-2").fireModal({title: 'Create an event', body: $("#modal-form-part"), center: true});
    $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var s_date = $('input[name="s_date"]').val();
      var e_date = $('input[name="e_date"]').val();
      var event_id = $('input[name="event_id"]').val();
      var event_title = $('input[name="event_title"]').val();
      var event_description = $('textarea[name="event_description"]').val();
      var who_consult_with = $('select[name="who_consult_with"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('admin/calendar/create_admin_event') }}",
        data: {s_date:s_date, e_date:e_date, event_title:event_title, _token:_token, event_description:event_description, who_consult_with:who_consult_with, event_id: event_id},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('admin/calendar') }}";
          }
          else {
            alert(res.msg)
          }
          console.log(res);
        }
      });
    });
    $('#fire-modal-2').on('click', function(){
      $('.to_datetime input').val('');
      $('input[name="event_title"').val('');
      $('textarea[name="event_description"]').val('');
      $('select[name="who_consult_with"]').val('');
      $('input[name="event_id"').val(0);
      $('#fire-modal-1 .modal-header h5.modal-title').text('Create an event');
      $('.saveclientinfo_form').val('Create');
      jQuery('.selectpicker1').selectpicker('refresh');
      $('.delete_event').hide();
    });
  });
  // var element = $('.rktesting');
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var events = '{{$events}}';
    var varTitle = $('<textarea />').html(events).text();
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
      defaultView: 'dayGridMonth',
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
        // $('input[name="time"').val(data1);
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
            $('select.selectpicker1 option[value="'+item+'"]').prop('selected', true);
          });
        }
        $('input[name="event_id"').val(event_id);
        jQuery('.selectpicker1').selectpicker('refresh');
        $('input[name="s_date"').val(date);
        $('input[name="e_date"]').val(info.event.extendedProps.event_end)
        $('.to_datetime').datetimepicker('remove');
        $('.to_datetime').datetimepicker({
          startDate: date,
          todayBtn:  1,
          autoclose: 1,
          showMeridian: 1
        }); 
        $('input[name="event_title"').val(info.event.extendedProps.act_title);
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
  $(document).on('click', '.delete_event', function(e){
    e.preventDefault();
    var event_id = $('.delete_event').data('event_id');
    $.ajax({
        type:"get",
        url:"{{ url('delete_event') }}/"+event_id,
        data: {},
        success:function(res)
        {       
          window.location.href = "{{ url('admin/calendar') }}";
        }
      });
  })
</script>
@endpush