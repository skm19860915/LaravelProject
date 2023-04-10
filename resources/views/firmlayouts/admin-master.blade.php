<?php
PagesAuthentications();
$firm = DB::table('firms')->where('id', Auth::User()->firm_id)->first();
$firm_user = Session::get('user_count');
if(Auth::User()->role_id == '4' && $firm->account_type == 'CMS' && Request::route()->getName() != 'firm.users.create') {
  $Transaction = DB::table('transactions')->where('user_id', Auth::User()->id)->count();
  if($Transaction) {
      if(!empty($firm_user)) {
        $url = url('firm/payment_method');
        // echo '<script> window.location.href = "'.$url.'"</script>';
        // die();
      }   
  }
  else if(Auth::User()->email == $firm->email){
    $url = url('firm/payment_method2');
      echo '<script> window.location.href = "'.$url.'"</script>';
      die();
  }
  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title', 'Stisla Laravel') &mdash; {{ env('APP_NAME') }}</title>
  <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" sizes="32x32" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/summernote-bs4.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <!-- <link rel="stylesheet" href="{{ asset('assets/css/components.css')}}"> -->
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}">
  <link rel="stylesheet" href="{{ asset('assets/css/CustomLoad.css')}}?v=<?php echo rand(); ?>">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="{{ asset('assets/css/colortheme.css')}}">
  @stack('header_styles')
  @stack('header_script')
</head>
<?php
$theme_color = '';
$data = Auth::User();
$firm = DB::table('firms')->where('id', $data->firm_id)->first();
$firmadmin = DB::table('users')->where('firm_id', $data->firm_id)->where('role_id', 4)->first();
// if(Auth::User()->role_id != '6') {
  $theme_color = get_user_meta($data->id, 'theme_color');
// }
?>
<body ng-app="myApp" ng-controller="myCtrl" class="{{$theme_color}}">
  <div id="app">
    <div class="main-wrapper <?php echo str_replace('.', '', Request::route()->getName()); ?>">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        @include('firmadmin.partials.topnav')
      </nav>
      <div class="main-sidebar">
        @include('firmadmin.partials.sidebar')
      </div>

      <!-- Main Content -->
      <div class="main-content">
        @include('flash-message')
        @yield('content')
      </div>
      <footer class="main-footer">
        @include('firmadmin.partials.footer')
      </footer>
    </div>
  </div>

  <!-- Add Note Modal -->
  <div id="AddNoteWrapper" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Pay Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="float: right;
          position: absolute;
          right: 22px;
          top: 15px;
          ">&times;</button>
          <h4 class="modal-title">Add New Note</h4>
        </div>
        <div class="modal-body">
          <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
            <div class="row">  
              <div class="col-md-2">
                <label>Subject</label>
              </div>
              <div class="col-md-10">
                <input name="subject" class="form-control" placeholder="Subject" value="">
              </div>
            </div>
            <br>
            <div class="row">  
              <div class="col-md-2">
                <label>Note</label>
              </div>
              <div class="col-md-10">
                <textarea name="note" class="form-control" placeholder="Write your note here..." style="height: 150px;"></textarea>
              </div>
            </div>
            <div class="row">  
              <div class="col-md-12 text-right">
                <input type="hidden" name="client_id" value="0" >  
                <input type="hidden" name="note_id" value="0" >  
                @csrf
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer text-right">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary savenotebtn">
            <span>Save</span>
            <i class="fa fa-spin fa-spinner" style="display: none;"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Send Message Modal -->
  <div id="SendMessageWrapper" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Pay Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="float: right;
          position: absolute;
          right: 22px;
          top: 15px;
          ">&times;</button>
          <h4 class="modal-title">Send Message</h4>
        </div>
        <div class="modal-body">
          <form action="{{ url('firm/lead/create_lead_note') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">To
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" class="form-control" name="name" value="" readonly>
              </div>
            </div>
            <div class="form-group row mb-4">
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
                  <input type="checkbox" name="is_email_send" class="custom-switch-input" value="1">
                  <span class="custom-switch-indicator"></span>
                  <span class="custom-switch-description">
                    <input type="hidden" name="email" class="form-control email_field" value="" placeholder="Email">
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
                  <textarea class="summernote-simple form-control" name="message" placeholder="Write Message Here...." required="required"></textarea>
              </div>
            </div> 
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                  @csrf
                  <input type="hidden" name="to" value="" >
              </div>
            </div> 
          </form>
        </div>
        <div class="modal-footer text-right">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary sendmessagebtn">Send</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Case Note Modal -->
  <div id="AddCaseNoteWrapper" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Pay Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="float: right;
          position: absolute;
          right: 22px;
          top: 15px;
          ">&times;</button>
          <h4 class="modal-title">Add New Note</h4>
        </div>
        <div class="modal-body">
          <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
            <div class="row">  
              <div class="col-md-2">
                <label>Subject</label>
              </div>
              <div class="col-md-10">
                <input name="subject" class="form-control" placeholder="Subject" value="">
              </div>
            </div>
            <br>
            <div class="row">  
              <div class="col-md-2">
                <label>Note</label>
              </div>
              <div class="col-md-10">
                <textarea name="note" class="form-control" placeholder="Write your note here..." style="height: 150px;"></textarea>
              </div>
            </div>
            <div class="row">  
              <div class="col-md-12 text-right">
                <input type="hidden" name="case_id" value="0" >  
                <input type="hidden" name="note_id" value="0" >  
                @csrf
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer text-right">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary savecasenotebtn">
            <span>Save</span>
            <i class="fa fa-spin fa-spinner" style="display: none;"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div id='tawk_5f258a5f4f3c7f1c910da022'></div>
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/js/popper.js') }}"></script>
  <script src="{{ asset('assets/js/tooltip.js') }}"></script>
  <script src="{{ route('js.dynamic') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  

  <script src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="{{ asset('assets/js/stisla.js') }}"></script>
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.mask.js') }}"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
  <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
  <script  src="{{ asset('assets/js/Chating.js') }}"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" />
  <script src="{{ asset('assets/js/custom_ng.js') }}"></script>
  <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  <script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
    }
  $(document).ready(function(){
    $('.supportbtn').on('click', function(e){
      e.preventDefault();
      $('#tawk_5f258a5f4f3c7f1c910da022').slideToggle();
    })
    // $("#searchname").keyup(function() {
    //   var get_url = "{{url('firm/admindashboard/search')}}";
    //   $.ajax({
    //     type: "POST",
    //     url: get_url,
    //     data: {  
    //       _token :' {{ csrf_token()}}',
    //       keyword: $("#searchname").val(),
    //     },
    //     async: false,
    //     success: function(data)
    //     {
    //       $('#search-name-list').fadeIn();  
    //       $('#search-name-list').html(data);
    //     }
    //   });

    //   $(document).on('click', 'li', function(){  
    //       $('#searchname').val($(this).text());  
    //       $('#search-name-list').fadeOut();
    //   });
    // });

  });

  </script>

  @stack('footer_styles')
  @stack('footer_script')
  @yield('scripts')
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://clipboardjs.com/dist/clipboard.min.js"></script>
<!--Firm SLide-->
<div class="SidesRight">
    <div class="actionbox">
        Please Enter Questions    <i class="OpenQuestionsByType fa fa-times"></i>
    </div>
    <div class="ExtraBoxContainer">
        
    </div>
</div>
<script src="//rawgit.com/notifyjs/notifyjs/master/dist/notify.js"></script>
<script src="{{ asset('assets/js/newcustomjs.js') }}?v=<?php echo rand(); ?>"></script>
<link rel="stylesheet" type="text/css" href="/assets/css/emojis.css">
<script type="text/javascript" src="https://twemoji.maxcdn.com/v/latest/twemoji.min.js"></script>
<script type="text/javascript" src="/assets/js/DisMojiPicker.js"></script>
<script src="https://www.jqueryscript.net/demo/Emoji-Picker-For-Textarea-jQuery-Emojiarea/assets/js/jquery.emojiarea.js"></script>
<script type="text/javascript">
  function setCookie(name,value,days) {
      var expires = "";
      if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days*24*60*60*1000));
          expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + (value || "")  + expires + "; path=/";
  }
  function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for(var i=0;i < ca.length;i++) {
          var c = ca[i];
          while (c.charAt(0)==' ') c = c.substring(1,c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      }
      return null;
  }
  function eraseCookie(name) {   
      document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  }
  $(document).ready(function(){
    $('.phone_no').mask('+1 (000) 000-0000');
    $('.is_complete').on('click', function(){
      var status = $(this).data('status');
      var cid = $(this).data('cid');
      var vp = $(this).data('vp');
      console.log(status, cid, vp);
      if($(this).is(':checked') && vp == 0 && status != 9) {
        console.log('complete 1');
        var url = "{{url('firm/case/case_complete1')}}/"+cid;
        window.location.href = url;
      }
      else if($(this).is(':checked') && vp == 1 && status == 6) {
        console.log('complete 2');
        var url = "{{url('firm/case/case_complete1')}}/"+cid;
        window.location.href = url;
      }
      else if(!$(this).is(':checked') && status == 9) {
        var url = "{{url('firm/case/case_incomplete')}}/"+cid;
        window.location.href = url;
      }
      else {
        return false;
        console.log('complete 3');
      }
    });

    $('.is_portal_access_btn').on('click', function(){
      var cid = $(this).data('cid');
      var access = 0;
      if($(this).is(':checked')) {
        access = 1;
      }
      var _token = $('#AddNoteWrapper input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/client/portal_access') }}",
        data: {_token:_token, cid:cid, access:access},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            alert(res.msg)
            // window.location.href = window.location.href;
          }
          else {
            alert(res.msg)
          }
        }
      });
    });

    $('.addnotebtn').on('click', function(e){
      e.preventDefault();
      var client_id = $(this).data('client_id');
      $('#AddNoteWrapper input[name="client_id"]').val(client_id);
      $('#AddNoteWrapper').modal('show');
    });

    $('.savenotebtn').on('click', function(e){
      e.preventDefault();
      if($(this).hasClass('runningajax')) {
        return false;
      }
      var note_id = $('#AddNoteWrapper input[name="note_id"]').val();
      var client_id = $('#AddNoteWrapper input[name="client_id"]').val();
      var subject = $('#AddNoteWrapper input[name="subject"]').val();
      var note = $('#AddNoteWrapper textarea[name="note"]').val();
      var _token = $('#AddNoteWrapper input[name="_token"]').val();
      var $this = $(this);
      $.ajax({
        type:"post",
        url:"{{ url('firm/client/add_notes') }}",
        data: {_token:_token, subject:subject, note:note, client_id:client_id, note_id:note_id},
        beforeSend: function() {
          $this.addClass('runningajax');
        },
        success:function(res)
        {       
          $this.removeClass('runningajax');
          res = JSON.parse(res);
          if(res.status) {
            alert(res.msg)
            window.location.href = window.location.href;
          }
          else {
            alert(res.msg)
          }
          console.log(res);
        }
      });
    });

    $('.sendmsgbtn').on('click', function(e){
      e.preventDefault();
      var to = $(this).data('to');
      var name = $(this).data('name');
      var phone_no = $(this).data('phone_no');
      var email = $(this).data('email');
      $('#SendMessageWrapper input[name="to"]').val(to);
      $('#SendMessageWrapper input[name="name"]').val(name);
      $('#SendMessageWrapper input[name="phone_no"]').val(phone_no);
      $('#SendMessageWrapper input[name="email"]').val(email);
      $('#SendMessageWrapper').modal('show');
    });

    $('.sendmessagebtn').on('click', function(e){
      e.preventDefault();
      if($(this).hasClass('runningajax')) {
        return false;
      }
      if(!$('#SendMessageWrapper input[name="is_text_send"]').is(':checked') && !$('#SendMessageWrapper input[name="is_email_send"]').is(':checked')) {
        alert('please select send via email or phone number!');
        return false;
      }
      var to = $('#SendMessageWrapper input[name="to"]').val();
      var subject = $('#SendMessageWrapper input[name="subject"]').val();
      var msg = $('#SendMessageWrapper textarea[name="message"]').val();
      var _token = $('#SendMessageWrapper input[name="_token"]').val();

      var phone_no = $('#SendMessageWrapper .phone_no').val();
      if($('#SendMessageWrapper input[name="is_text_send"]').is(':checked') && phone_no == '') {
        alert('Phone Number is required!');
        return false;
      }
      var is_text_send = '';
      if($('#SendMessageWrapper input[name="is_text_send"]').is(':checked')) {
        is_text_send = true;
        var filter = /^\+(?:[0-9] ?){6,14}[0-9]$/;
        if (phone_no.length >= 17) { 

        }
        else {
          alert('Phone number is not valid!');
          return false;
        }
      }

      var email_field = $('#SendMessageWrapper .email_field').val();
      var is_email_send = '';
      if($('#SendMessageWrapper input[name="is_email_send"]').is(':checked') && email_field == '') {
        alert('Email is required!');
        return false;
      }
      if($('#SendMessageWrapper input[name="is_email_send"]').is(':checked')) {
        is_email_send = true;
      }
      if(subject == '') {
        alert('Subject is required!');
        return false;
      }
      if(msg == '') {
        alert('Text Message is required!');
        return false;
      }
      var $this = $(this);
      $.ajax({
        type:"post",
        url:"{{ url('firm/client/send_text_msg') }}",
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
          window.location.href = window.location.href;
       }
     });
    });
    $('#SendMessageWrapper input[name="is_text_send"]').on('click', function(){
      if($(this).is(':checked')) {
        $('#SendMessageWrapper .phone_no').attr('type', 'text');
        $('#SendMessageWrapper .phone_no').prop('required', true);
      }
      else {
        $('#SendMessageWrapper .phone_no').attr('type', 'hidden');
        $('#SendMessageWrapper .phone_no').prop('required', false);
      }
    });
    $('#SendMessageWrapper input[name="is_email_send"]').on('click', function(){
      if($(this).is(':checked')) {
        $('#SendMessageWrapper .email_field').attr('type', 'text');
        $('#SendMessageWrapper .email_field').prop('required', true);
      }
      else {
        $('#SendMessageWrapper .email_field').attr('type', 'hidden');
        $('#SendMessageWrapper .email_field').prop('required', false);
      }
    });

    $('.addcasenotebtn').on('click', function(e){
      e.preventDefault();
      var case_id = $(this).data('case_id');
      $('#AddCaseNoteWrapper input[name="case_id"]').val(case_id);
      $('#AddCaseNoteWrapper').modal('show');
    });

    $('.savecasenotebtn').on('click', function(e){
      e.preventDefault();
      if($(this).hasClass('runningajax')) {
        return false;
      }
      var note_id = $('#AddCaseNoteWrapper input[name="note_id"]').val();
      var case_id = $('#AddCaseNoteWrapper input[name="case_id"]').val();
      var subject = $('#AddCaseNoteWrapper input[name="subject"]').val();
      var note = $('#AddCaseNoteWrapper textarea[name="note"]').val();
      var _token = $('#AddCaseNoteWrapper input[name="_token"]').val();
      var $this = $(this);
      $.ajax({
        type:"post",
        url:"{{ url('firm/case/add_case_notes') }}",
        data: {_token:_token, subject:subject, note:note, case_id:case_id, note_id:note_id},
        beforeSend: function() {
          $this.addClass('runningajax');
        },
        success:function(res)
        {       
          $this.removeClass('runningajax');
          res = JSON.parse(res);
          if(res.status) {
            alert(res.msg)
            window.location.href = window.location.href;
          }
          else {
            alert(res.msg)
          }
          console.log(res);
        }
      });
    });

    
    

  });
$(document).on('click', '.annual_payment_cycle', function(){
      var monthly_amount = $(this).data('monthly_amount');
      var annual_amount = "{!! \get_user_meta(1, 'annual_amount'); !!}";
      var amt = 0;
      var txt = '';
      if($(this).is(':checked')) {
        amt = parseInt(monthly_amount);
        txt = amt+" a month";
        $('.saved_amount_text').hide();
        setCookie('payment_cycle', 0, 1);
      }
      else {
        amt = parseInt(monthly_amount)*12-parseInt(annual_amount);
        txt = amt+" a year";
        $('.saved_amount_text').show();
        setCookie('payment_cycle', 1, 1);
      }

      $('.annual_payment_cycletext').text(txt);
      $('.saved_amount_text').text("You saved $"+annual_amount);
    });
  $(".emojis").disMojiPicker()
  $(".emojis").picker(emoji => $('.emoji-editor').html($('.emoji-editor').html() + emoji));
  twemoji.parse(document.body);

/* Start of Tawk.to Script */
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date(); Tawk_API.embedded='tawk_5f258a5f4f3c7f1c910da022';
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5f258a5f4f3c7f1c910da022/1etj6athv';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);})();
/* End of Tawk.to Script */
</script>
</body>
</html>
