@extends('layouts.admin-master')

@section('title')
View client
@endsection

@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('admin.adminuser.userclient.client_header')  
<!--new-header Close-->
  
   <div class="section-body">
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
             <h2>Notes</h2>
             <a href="#" class="add-task-link" id="fire-modal-2" style="display: none;">Add Note</a>
             
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
                        <?php if($v->type == 'Note' && $v->is_edit) { ?>
                          <a href="#" class="action_btn editnote" data-toggle="tooltip" title="View/Edit" data-id="{{$v->id}}" data-subject="{{$v->subject}}" data-notes="{{$v->message}}"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>
                          <a href="{{url('admin/userclient/deletenotes')}}/{{ $client->user_id }}/{{$v->id}}" class="action_btn" data-toggle="tooltip" title="Delete" onclick="return window.confirm('Are you sure you want to delete?');"><img src="{{ url('/') }}/assets/images/icons/case-icon3.svg"></a>
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
        <input type="hidden" name="client_id" value="{{ $client->id }}" >  
        <input type="hidden" name="note_id" value="0" >  
        @csrf
        <input type="submit" name="save" value="Create Client Note" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>
@endsection


@push('footer_script')

<script type="text/javascript">
$(document).ready(function(){
  $("#fire-modal-2").fireModal({title: 'Add A New Note', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
    e.preventDefault();
    var note_id = $('.modalformpart input[name="note_id"]').val();
    var client_id = $('.modalformpart input[name="client_id"]').val();
    var subject = $('.modalformpart input[name="subject"]').val();
    var note = $('.modalformpart textarea[name="note"]').val();
    var _token = $('input[name="_token"]').val();
    $.ajax({
      type:"post",
      url:"{{ url('admin/userclient/addnotes') }}",
      data: {_token:_token, subject:subject, note:note, client_id:client_id, note_id:note_id},
      success:function(res)
      {       
        res = JSON.parse(res);
        if(res.status) {
          alert(res.msg)
          window.location.href = "{{ url('admin/userclient/viewnotes') }}/{{ $client->user_id }}";
        }
        else {
          alert(res.msg)
        }
        console.log(res);
      }
    });
  });

  $('#fire-modal-2').on('click', function(){
    $('input[name="note_id"]').val(0);
    $('.saveclientinfo_form').val('Create Note');
    $('h5.modal-title').text('Add A New Note');
    $('input[name="subject"]').val('');
    $('textarea[name="note"]').val('');
  });

  $('.editnote').on('click', function(e) {
    e.preventDefault();
    $("#fire-modal-2").trigger('click');
    $('input[name="subject"]').val($(this).data('subject'));
    $('textarea[name="note"]').val($(this).data('notes'));
    $('input[name="note_id"]').val($(this).data('id'));
    $('.saveclientinfo_form').val('Update Note');
    $('h5.modal-title').text('Update Note');
  });

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