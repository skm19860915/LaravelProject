@extends('layouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
    
</style>
@endpush  

@section('content')
<section class="section client-listing-details new_task_notes">
<!--new-header open-->
  @include('admin.adminuser.usertask.task_header')
<!--new-header Close-->
 
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('admin/all_case') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
            <div class="profile-new-client">
              <h2>Messages</h2>
              <a href="#" class="add-task-link trigger--fire-modal-2" id="fire-modal-2" style="display: none;"> Compose <i class="fas fa-plus"></i></a>
              <div class="notes-list-box">
              <div class="table-responsive table-invoice all-case-table">
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
                        <?php if($v->type == 'Note') { ?>
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
  </div>
</section>
<div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/lead/create_lead_note') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">  
      <div class="col-md-12">
        <textarea name="msg" class="form-control" placeholder="Write your text message here..."  style="height: 150px;"></textarea>
      </div>
    </div>
    <div class="row">  
      <div class="col-md-12 text-right">
        <input type="hidden" name="to" value="{{$admintask->firm_admin_id}}" >  
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
$(document).ready(function(){
  // var index_url = "{{url('admin/usertask/getMessageData/')}}/{{$admintask->firm_admin_id}}";
  // $('#table').DataTable({
  //   processing: true,
  //   serverSide: true,
  //   ajax: index_url,
  //   "order": [[ 0, "desc" ]],
  //   columns: [
  //   { data: 'id', name: 'id'},
  //   { data: 'msgfrom', name: 'msgfrom'},
  //   { data: 'msgto', name: 'msgto'},
  //   { data: 'msg', name: 'msg'},
  //   { data: 'created_at', name: 'created_at'},
  //   ],
  // });
  
  $("#fire-modal-2").fireModal({title: 'Add New Text Message', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var to = $('input[name="to"]').val();
      var case_id = "{{$case->id}}";
      var msg = $('textarea[name="msg"]').val();
      var _token = $('input[name="_token"]').val();
      if(msg == '') {
        alert('Text Message is required!');
        return false;
      }
      $.ajax({
        type:"post",
        url:"{{ url('admin/usertask/sendtextmsg') }}",
        data: {msg:msg, to:to, case_id:case_id, _token:_token},
        success:function(res)
        {       
         alert('Text Message send successfully');
         window.location.href = "{{ url('admin/usertask/caseinbox') }}/{{$admintask->id}}";
       }
     });
    });
});

//================ Edit user ============//

</script>

@endpush 
