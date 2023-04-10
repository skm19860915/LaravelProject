@extends('layouts.admin-master')

@section('title')
Firm Detail
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#usertable tbody tr td:nth-child(1) {
  display: none;
}
</style>
@endpush

@section('content')
<section class="section client-listing-details">
  <div class="section-header">
    <h1><a href="{{ url('admin/firm') }}"><span>Firm /</span></a> Detail</h1>
  </div>
  <!--new-header open-->
  @include('admin.firm.firm_header')
  <!--new-header Close-->
  <div class="section-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="back-btn-new">
            <a href="{{ url('admin/firm') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
          </div>
          <div class="card-body">
            <div class="profile-new-client">
              <h2>Notes</h2>
              <a href="#" class="add-task-link" id="fire-modal-2">+ Compose Note</a>
              <div style="float: left;" class="country-left-select">
                <select class="form-control" name="role" id="role">
                    <option value="">All</option>
                    <?php 
                    if(!empty($users)) {
                      foreach ($users as $key => $value) {
                        echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                      }
                    } ?>
                </select>               
              </div>

              <div class="notes-list-box">
                <div class="table-responsive table-invoice all-case-table">
                  <table class="table table table-bordered table-striped"  id="table" >
                    <thead>
                      <tr>
                        <th> Subject</th>
                        <th> Notes</th>
                        <th> User</th>
                        <th> Created Date/Time </th>
                        <th> Actions</th>
                      </tr>
                    </thead>
                    <tbody>
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
        <input type="hidden" name="firm_id" value="{{ $firm->id }}" > 
        <input type="hidden" name="note_id" value="0" >  
        @csrf
        <input type="submit" name="save" value="Create Note" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>
@endsection
@push('footer_script')
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function(){
  $("#fire-modal-2").fireModal({title: 'Add A New Note', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
    e.preventDefault();
    var note_id = $('input[name="note_id"]').val();
    var firm_id = $('input[name="firm_id"]').val();
    var subject = $('input[name="subject"]').val();
    var note = $('textarea[name="note"]').val();
    var _token = $('input[name="_token"]').val();
    $.ajax({
      type:"post",
      url:"{{ url('admin/firm/add_firm_notes') }}",
      data: {_token:_token, subject:subject, note:note, firm_id:firm_id, note_id:note_id},
      success:function(res)
      {       
        res = JSON.parse(res);
        if(res.status) {
          alert(res.msg)
          window.location.href = "{{ url('admin/firm/firm_notes') }}/{{$firm->id}}";
        }
        else {
          alert(res.msg);
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

  $(document).on('click', '.editnote', function(e) {
    e.preventDefault();
    $("#fire-modal-2").trigger('click');
    $('input[name="subject"]').val($(this).data('subject'));
    $('textarea[name="note"]').val($(this).data('notes'));
    $('input[name="note_id"]').val($(this).data('id'));
    $('.saveclientinfo_form').val('Update Note');
    $('h5.modal-title').text('Update Note');
  });

  var r = "{{$firm->id}}";
  var role = '';
  function getNotedata(r, role) {
    var index_url = "{{ url('admin/firm/get_firmnote_data') }}";
    var table = $('#table').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
        "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "id": r,
                "role": role,
            }
        },
      order: [ [3, 'desc'] ],
      columns: [
        { data: 'subject', name: 'subject'},
        { data: 'notes', name: 'notes'},
        { data: 'username', name: 'username'},
        { data: 'created_at', name: 'created_at'},
        { data: null,
          render: function(data){
            var editbtn = ' <a href="#" class="action_btn editnote" data-toggle="tooltip" title="View/Edit" data-id="'+data.id+'" data-subject="'+data.subject+'" data-notes="'+data.notes+'"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>';
            
            var deletebtn =' <a href="{{url('admin/firm/delete_firm_note')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" title="Delete"><img src="{{ url('/') }}/assets/images/icons/case-icon3.svg"></a>';
            return editbtn + deletebtn;
          }, orderable: "false"
        },
      ], 
    });
  }
  getNotedata(r, role);
  $('#role').on('change', function(){
    var role = $(this).val();
    getNotedata(r, role);
  });
});

</script>

@endpush 