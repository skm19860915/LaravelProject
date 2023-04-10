@extends('firmlayouts.admin-master')

@section('title')
My Messages
@endsection

@push('header_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details">

<div class="section-header">
  <h1><a href="{{url('firm/clientdashboard')}}"><span>Home / </span></a> My Messages</h1>
</div>
  <div class="section-body">
       
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/clientcase') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
          </div>
          <div class="card-body">
            <div class="profile-new-client">
              <h2>Inbox</h2>
              <a href="" class="add-task-link" id="fire-modal-2"> Send Message</a>
              <div class="table-responsive table-invoice">
                <table class="table table table-bordered table-striped"  id="table" >
                  <thead>
                    <tr>
                      <th> Subject</th>
                      <th> Note</th>
                      <th> Created By</th>
                      <th> Type of Message/Note</th>
                      <th> Create Date/Time </th>
                      <th> Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    if(!empty($msg)) {
                      foreach ($msg as $k => $v) { ?>
                        <tr>
                          <td>{{$v->subject}}</td>
                          <td>{{$v->message}}</td>
                          <td>{{$v->created_by}}</td>
                          <td>{{$v->type}}</td>
                          <td>{{$v->create_date}} 
                            <span class="text-gray">{{$v->create_time}}</span>
                          </td>
                          <td>
                            <?php if($v->type == 'Note' && $v->is_edit) { ?>
                              <a href="#" class="action_btn editnote" data-toggle="tooltip" title="View/Edit" data-id="{{$v->id}}" data-subject="{{$v->subject}}" data-notes="{{$v->message}}"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>
                              <a href="{{url('firm/case/delete_note')}}/{{$v->id}}" class="action_btn" data-toggle="tooltip" title="Delete" onclick="return window.confirm('Are you sure you want to delete?');"><img src="{{ url('/') }}/assets/images/icons/case-icon3.svg"></a>
                            <?php } ?>
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
</section>

 <div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/lead/create_lead_note') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
      <div class="form-group row mb-4">
        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">To
        </label> 
        <div class="col-sm-12 col-md-7">
          <select class="selectpicker" name="to" style="width: 220px;" data-live-search="true">
            <option value="">Select</option>
            <?php
            if(!empty($ids)) {
              foreach ($ids as $key => $value) {
                  echo '<option value="'.$value->id.'"
                  data-cell_phone="'.$value->cell_phone.'" data-email="'.$value->email.'">'.$value->name.'</option>';
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
          <!-- <input type="submit" name="save" value="Send" class="btn btn-primary saveclientinfo_form"/> -->
          <button type="button" class="btn btn-primary saveclientinfo_form">
            <span>Send</span>
            <i class="fa fa-spin fa-spinner" style="display: none;"></i>
          </button>
        </div>
      </div>
    </form>
  </div>

<!-- Modal -->
<div id="ViewMsgModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Pay Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Message</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('firm/case/update_court_date') }}" method="post" id="payment-form" enctype="multipart/form-data"> 
          <div class="payment-form-card" id="card-element">
             <div class="row">
              <label class="col-form-label col-md-4 col-lg-4">Subject
              </label> 
              <div class="col-md-8 col-lg-8 sub" style="padding-top: 11px;">
                
              </div>
             </div>
             <div class="row">
              <label class="col-form-label col-md-4 col-lg-4">Message
              </label> 
              <div class="col-md-8 col-lg-8 fullmsg" style="padding-top: 11px;">
                
              </div>
             </div>
          </div>
          <div class="row">  
            <div class="col-md-12 text-right">
              @csrf
              <!-- <input type="hidden" class="case_id" name="case_id"  value="">
              <input type="submit" name="save" value="Update" class="submit btn btn-primary"/> -->
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection

@push('footer_script')
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
  // var index_url = "{{url('firm/textmessages/getData/')}}";
  // $('#table').DataTable({
  //   processing: true,
  //   serverSide: true,
  //   ajax: index_url,
  //   "order": [[ 0, "desc" ]],
  //   columns: [
  //   { data: 'subject', name: 'subject'},
  //   { data: 'message', name: 'message'},
  //   { data: 'created_by', name: 'created_by'},
  //   { data: 'type', name: 'type'},
  //   // { data: 'create_date', name: 'create_date'},
  //   {
  //    data: null,
  //    render: function(data){
  //     return data.create_date+'<span class="text-gray"> '+data.create_time+'</span>';
  //    }
  //  },
  //   {
  //    data: null,
  //    render: function(data){
  //       var view_button = ' <a href="#" class="action_btn viewmsg" title="View Full Message" data-toggle="tooltip" data-sub="'+data.subject+'" data-msg="'+data.message+'"><img src="{{ url('/') }}/assets/images/icon/Group 557.svg"></a>';
  //       var send_button = ' <a href="#" class="action_btn sendmsgbtn" data-id="'+data.msgfrom+'" title="Reply" data-toggle="tooltip"><img src="{{ url('/') }}/assets/images/icon/chat(1)@2x.png"></a>';
  //       return view_button + send_button;
  //   }, 
  //   orderable: "false"    
  //   }
  //   ],
  // });
  $(document).on('click', '.sendmsgbtn', function(e){
    e.preventDefault();
    var uid = $(this).data('id');
    console.log(uid);
    $('input[name="to"]').val(uid);
    $('#fire-modal-2').trigger('click');
  });
  $(document).on('click', '.viewmsg', function(e){
      e.preventDefault();
      var sub = $(this).data('sub');
      var msg = $(this).data('msg');
      $('.sub').text(sub);
      $('.fullmsg').text(msg);
      $('#ViewMsgModal').modal('show');
      console.log('ok');
    })
  $(document).ready(function(){
    $('#table').DataTable();
    $("#fire-modal-2").fireModal({title: 'Message', body: $("#modal-form-part"), center: true});
    $('select[name="to"]').on('change', function(){
      var v = $(this).val();
      var phone = $('.modalformpart select[name="to"] option[value="'+v+'"]').data('cell_phone');
      var email = $('.modalformpart select[name="to"] option[value="'+v+'"]').data('email');
      $('.phone_no').val(phone);
      $('.email_field').val(email);
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
