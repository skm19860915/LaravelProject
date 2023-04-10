@extends('layouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details task-new-header">
<!--new-header open-->
  @include('admin.case.case_header')
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
             <h2>Notes</h2>
             <?php if(!empty($admintask)) { ?>
             <a href="#" class="add-task-link" id="fire-modal-2">+ Add New</a>
             <?php } ?>
             <div class="notes-list-box">
              <div class="table-responsive table-invoice all-case-table">
                <table class="table table table-bordered table-striped"  id="table" >
                  <thead>
                    <tr>
                      <th> Subject</th>
                      <th> Note</th>
                      <th> User</th>
                      <th> Create Date/Time </th>
                      <th> Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                if(!empty($notes_list)) {
                  foreach ($notes_list as $k => $v) { ?>
                    <tr>
                      <td>{{$v->subject}}</td>
                      <td>{{$v->notes}}</td>
                      <td>{{$v->username}}</td>
                      <td>
                        <?php echo date('Y-m-d ', strtotime($v->created_at)); ?>
                        <span class="text-gray"><?php echo date('h:i A', strtotime($v->created_at)); ?></span>
                      </td>
                      <td>
                        <?php if(!empty($admintask)) { ?>
                        <a href="#" class="action_btn editnote" data-toggle="tooltip" title="View/Edit" data-id="{{$v->id}}" data-subject="{{$v->subject}}" data-notes="{{$v->notes}}"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>
                        <a href="{{url('admin/allcases/deletecasenote')}}/{{$v->id}}/{{$case->id}}" class="action_btn" data-toggle="tooltip" title="Delete"><img src="{{ url('/') }}/assets/images/icons/case-icon3.svg"></a>
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
          <input type="hidden" name="case_id" value="{{ $case->id }}" > 
          <input type="hidden" name="note_id" value="0" > 
          @csrf
          <input type="submit" name="save" value="Create" class="btn btn-primary saveclientinfo_form"/>
        </div>
      </div>
    </form>
  </div>
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $("#fire-modal-2").fireModal({title: 'Add A New Note', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var note_id = $('input[name="note_id"]').val();
      var subject = $('input[name="subject"]').val();
      var case_id = $('input[name="case_id"]').val();
      var note = $('textarea[name="note"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('admin/allcases/addnewnotes') }}",
        data: {_token:_token, subject:subject, note:note, case_id:case_id, note_id:note_id},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('admin/allcases/casenotes') }}/{{ $case->id }}";
          }
          else {
            alert('Mendatory fields are required!')
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

@endpush 