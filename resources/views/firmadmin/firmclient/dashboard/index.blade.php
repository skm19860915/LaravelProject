@extends('firmlayouts.admin-master')

@section('title')
Dashboard
@endsection

@push('header_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush 

@section('content')
<section class="section firmclient-dashboard">
    <div class="section-header">
        <h1><a href="{{url('firm/clientdashboard')}}"><span>Home / </span></a> Dashboard</h1>
        <div class="section-header-breadcrumb">
            <a href="" class="add-task-link" id="fire-modal-2"> Send Message</a>
        </div>
    </div>
    <br>
    <div class="section-body firmclient-dashboard">
        @if(session()->has('info'))
        <div class="alert alert-primary alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>×</span>
                </button>
                {{ session()->get('info') }}
            </div>
        </div>
        @endif 
        <div class="row">
            <div class="col-md-3" style="display: none;">
                <div class="card card-statistic-1">
                    <a href="{{url('firm/mybalance')}}">
                        <div class="card-icon shadow-primary bg-primary d_card1">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">                        
                            <div class="card-header">
                                {{$count['outstanding_amount']}}
                            </div>
                            <div class="card-body">
                                Balance
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3"  style="display: none;">
                <div class="card card-statistic-1">
                    <div class="card-icon shadow-primary bg-primary d_card2">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                         {{$count['paid_amount']}}                         
                        </div>
                        <div class="card-body">
                           Payments
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <a href="{{url('firm/mymessages')}}">
                        <div class="card-icon shadow-primary bg-primary d_card3">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">                            
                                {{count($count['message']['textmsg'])}}
                            </div>
                            <div class="card-body">
                               New Message
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">

                    <div class="card-icon shadow-primary bg-primary d_card4">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">                           
                          {{$count['case_opned']}}
                        </div>
                        <div class="card-body">
                           Active Cases
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="theme_text">My Appointments</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-invoice">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Appointment Type</th>
                                        <th>Meeting With</th>
                                        <!-- <th>Location</th> -->
                                    </tr>
                                @if ($count['events']->isEmpty())

                                @else
                                @foreach ($count['events'] as $event)
                                <tr>
                                    <td class="font-weight-600">
                                        {{$event->s_date}}
                                    </td>
                                    <td>
                                        {{$event->s_time}}
                                    </td>
                                    <td>{{$event->event_type}}</td>
                                    <td>{{$event->meetingwith}}</td>
                                    <!-- <td></td> -->
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="theme_text">My Tasks</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-invoice">
                            <table class="table table-striped">
                                <tbody>
                                  <tr>
                                    <th>Task</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Action</th>
                                </tr>
                                @if ($count['task']->isEmpty())

                                @else
                                @foreach ($count['task'] as $task)
                                <tr>
                                    <td class="font-weight-600">
                                        {{$task->title}}
                                    </td>
                                    <td>
                                        {{$task->description}}
                                    </td>
                                    <td>
                                        <?php if($task->status) {
                                            echo 'Completed';
                                        }
                                        else {
                                            echo 'Open';
                                        } ?>
                                    </td>
                                    <td>
                                        {{$task->e_date}} 
                                        <span class="text-gray">{{$task->e_time}}</span>
                                    </td>
                                    <td>
                                        <?php if(!$task->status) { ?>
                                            <a href="{{url('firm/complete_task')}}/{{$task->id}}" class="btn btn-primary">Complete</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </div>
</section>

 <div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/lead/create_lead_note') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
      <div class="form-group row mb-4">
        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">To
        </label> 
        <div class="col-sm-12 col-md-7">
          <select class="selectpicker" name="to" style="width: 220px;" data-live-search="true" required>
            <option value="">Select</option>
            <?php
            if(!empty($ids)) {
              foreach ($ids as $key => $value) {
                  echo '<option value="'.$value->id.'"
                  data-cell_phone="'.$value->contact_number.'" data-email="'.$value->email.'">'.$value->name.'</option>';
              }
            }
            ?>
          </select>
        </div>
      </div>
      <div class="form-group row mb-4" style="display: none;">
        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Send via Text Message
        </label> 
        <div class="col-sm-12 col-md-7">
          <label class="custom-switch mt-2 p-0">
            <input type="checkbox" name="is_text_send" class="custom-switch-input" value="1">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description">
              <input type="hidden" name="phone_no" class="form-control phone_no" value="" placeholder="Phone Number">
            </span>
          </label>
        </div>
      </div>
      <div class="form-group row mb-4">
        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Send via Email
        </label> 
        <div class="col-sm-12 col-md-7">
          <label class="custom-switch mt-2 p-0">
            <input type="checkbox" name="is_email_send" class="custom-switch-input" value="1" checked>
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description">
              <input type="text" name="email" class="form-control email_field" value="" placeholder="Email">
            </span>
          </label>
        </div>
      </div> 
      <div class="form-group row mb-4">
        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Subject
        </label> 
        <div class="col-sm-12 col-md-7">
            <input type="text" class="form-control" name="subject" placeholder="Subject" required="required">
        </div>
      </div>
      <div class="form-group row mb-4">
        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Message
        </label> 
        <div class="col-sm-12 col-md-7">
            <textarea name="message" class="form-control" style="height: 150px;"></textarea>
        </div>
      </div>
      <div class="row">  
        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
        </label> 
        <div class="col-sm-12 col-md-7">
          @csrf
          <button type="button" class="btn btn-primary saveclientinfo_form">
            <span>Send</span>
            <i class="fa fa-spin fa-spinner" style="display: none;"></i>
          </button>
          <!-- <input type="submit" name="save" value="Send" class="btn btn-primary saveclientinfo_form"/> -->
        </div>
      </div>
    </form>
  </div>
@endsection
@push('footer_script')
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
  $(document).on('click', '.sendmsgbtn', function(e){
    e.preventDefault();
    var uid = $(this).data('id');
    console.log(uid);
    $('input[name="to"]').val(uid);
    $('#fire-modal-2').trigger('click');
  });
  $(document).ready(function(){
    
    $("#fire-modal-2").fireModal({title: 'Message', body: $("#modal-form-part"), center: true});
    $('select[name="to"]').on('change', function(){
      var v = $(this).val();
      var phone = $('.modalformpart select[name="to"] option[value="'+v+'"]').data('cell_phone');
      var email = $('.modalformpart select[name="to"] option[value="'+v+'"]').data('email');
      $('.modalformpart .phone_no').val(phone);
      $('.modalformpart .email_field').val(email);
    });
    $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      if($(this).hasClass('runningajax')) {
        return false;
      }
      if($('select[name="to"]').val() == '') {
        alert('please select TILA VP');
        return false;
      }
      if(!$('.modalformpart input[name="is_text_send"]').is(':checked') && !$('.modalformpart input[name="is_email_send"]').is(':checked')) {
        alert('please select send via email!');
        return false;
      }
      var to = $('.modalformpart select[name="to"]').val();
      var subject = $('.modalformpart input[name="subject"]').val();
      var msg = $('.modalformpart textarea[name="message"]').val();
      var _token = $('input[name="_token"]').val();

      var phone_no = $('.modalformpart .phone_no').val();
      if($('.modalformpart input[name="is_text_send"]').is(':checked') && phone_no == '') {
        alert('Phone Number is required!');
        return false;
      }
      var is_text_send = false;
      if($('.modalformpart input[name="is_text_send"]').is(':checked')) {
        is_text_send = true;
        var filter = /^\+(?:[0-9] ?){6,14}[0-9]$/;
        if (filter.test(phone_no)) { }
        else {
          alert('Phone number is not valid!');
          return false;
        }
      }

      var email_field = $('.modalformpart .email_field').val();
      var is_email_send = false;
      if($('.modalformpart input[name="is_email_send"]').is(':checked') && email_field == '') {
        alert('Email is required!');
        return false;
      }
      if($('.modalformpart input[name="is_email_send"]').is(':checked')) {
        is_email_send = true;
      }
      if(subject == '') {
        alert('Subject is required!');
        return false;
      }
      if(msg == '') {
        alert('Message is required!');
        return false;
      }
      var $this = $(this);
      $.ajax({
        type:"post",
        url:"{{ url('firm/sendtextmsg') }}",
        data: {
          subject:subject,
          msg:msg, 
          to:to, 
          phone_no:phone_no,
          email:email_field,
          is_text_send:is_text_send,
          is_email_send:is_email_send,
          _token:_token
        },
        beforeSend: function() {
          $this.addClass('runningajax');
        },
        success:function(res)
        {       
          $this.removeClass('runningajax');
          alert('Message send successfully');
          window.location.href = "{{ url('firm/mymessages') }}";
       }
     });
    });
    $('input[name="is_text_send"]').on('click', function(){
      if($(this).is(':checked')) {
        $('.phone_no').attr('type', 'text');
        $('.phone_no').prop('required', true);
      }
      else {
        $('.phone_no').attr('type', 'hidden');
        $('.phone_no').prop('required', false);
      }
    });
    $('input[name="is_email_send"]').on('click', function(){
      if($(this).is(':checked')) {
        $('.email_field').attr('type', 'text');
        $('.email_field').prop('required', true);
      }
      else {
        $('.email_field').attr('type', 'hidden');
        $('.email_field').prop('required', false);
      }
    });
  });
</script>

@endpush 