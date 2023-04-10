@extends('firmlayouts.admin-master')

@section('title')
Text Message
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
  display: none;
}  
</style>
@endpush  

@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('firmadmin.firmclient.dashboard.client_header')
<!--new-header Close-->
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
              <a href="" class="add-task-link" id="fire-modal-2"> Compose <i class="fas fa-plus"></i></a>
              <div class="table-responsive table-invoice">
                <table class="table table table-bordered table-striped"  id="table" >
                  <thead>
                    <tr>
                     <th style="display: none;"> ID </th>
                     <th> From </th>
                     <th> To </th>
                     <th> Message </th>
                     <th> Date </th>
                     <!-- <th> </th> -->
                    </tr>
                  </thead>
                  
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
    <div class="row">  
      <div class="col-md-12">
        Text Message 
        <br>
      </div>
      <div class="col-md-12">
        <textarea name="msg" class="form-control" style="height: 150px;"></textarea>
      </div>
    </div>
    <div class="row">  
      <div class="col-md-12 text-right">
          <input type="hidden" name="to" value="<?php echo $ids->id; ?>" >   
        @csrf
        <input type="submit" name="save" value="Send" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>

@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
  var index_url = "{{url('firm/textmessages/getData/')}}";
  $('#table').DataTable({
    processing: true,
    serverSide: true,
    ajax: index_url,
    "order": [[ 0, "desc" ]],
    columns: [
    { data: 'id', name: 'id'},
    { data: 'msgfromname', name: 'msgfromname'},
    { data: 'msgto', name: 'msgto'},
    { data: 'msg', name: 'msg'},
    { data: 'created_at', name: 'created_at'}
    // {
    //  data: null,
    //  render: function(data){
    //   var send_button = '';
    //   if(data.send) {
    //     send_button = ' <a href="#" class="action_btn sendmsgbtn" data-id="'+data.msgfrom+'"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>';
    //   }
    //   return send_button;
    // }, 
    // orderable: "false"    
    // }
    ],
  });
  $(document).on('click', '.sendmsgbtn', function(e){
      e.preventDefault();
      var uid = $(this).data('id');
      console.log(uid);
      $('input[name="to"]').val(uid);
      $('#fire-modal-2').trigger('click');
    });
  $(document).ready(function(){
    
    $("#fire-modal-2").fireModal({title: 'Text Message', body: $("#modal-form-part"), center: true});

    $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var to = $('input[name="to"]').val();
      var msg = $('textarea[name="msg"]').val();
      var _token = $('input[name="_token"]').val();
      if(msg == '') {
        alert('Text Message is required!');
        return false;
      }
      $.ajax({
        type:"post",
        url:"{{ url('firm/sendtextmsg') }}",
        data: {msg:msg, to:to, _token:_token},
        success:function(res)
        {       
         alert('Text Message send successfully');
         window.location.href = "{{ url('firm/textmessage') }}/{{$case->case_id}}";
       }
     });
    });
  });
</script>

@endpush 
