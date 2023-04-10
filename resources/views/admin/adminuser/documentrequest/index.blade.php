@extends('layouts.admin-master')

@section('title')
Clients
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <div class="breadcrumb-item">
      <a href="{{route('admin.usertask')}}">Task</a>
    </div>
    <div class="breadcrumb-item">
      <h1>Request Document</h1>
    </div>
    <div class="section-header-breadcrumb">
      <button href="#" id="fire-modal-2" class="btn btn-primary trigger--fire-modal-2 trigger--fire-modal-1"><i class="fas fa-plus"></i>
      </button>
    </div>
  </div>
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
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th> Id</th>
                   <th> Case ID </th>
                   <th> Name </th>
                   <th> Type</th>
                   <th> Due Date</th>
                   <!-- <th> Created</th> -->
                   <th> Status</th>
                   <th> Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
     </div>
  </div>
</section>
<div class="modalformpart" id="modal-form-part" style="display: none;">
  <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">
      <div class="col-md-4">
        Type * 
      </div>
      <div class="col-md-8">
        <select name="file_type" class="selectpicker rkselect" multiple="multiple" required="required" data-live-search="true">
          <option value="">Select One</option>
          <?php 
          if(!empty($CaseTypes)) {
            foreach ($CaseTypes[0]->Required_Documentation_en as $key => $v) {
              if(in_array(trim($v), $already_requested)) {
                echo "<option value='$v' disabled='disabled'>$v</option>"; 
              }
              else {
                echo "<option value='$v'>$v</option>"; 
              }
               
            } 
          }
          ?>
        </select>
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-4">
        Due Date * 
      </div>
      <div class="col-md-8">
        <input type="text" placeholder="Due Date" name="expiration_date" class="form-control datepicker">
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-12 text-right">
        @csrf
        <input name="client_id" type="hidden" value="{{$client_id}}" required="required">
        <input name="case_id" type="hidden" value="{{$id}}" required="required">
        <input type="submit" name="save" value="Save" class="btn btn-primary convert_client_act"/>
      </div>
    </div>
  </form>
  <div class="row uploadFileswrap" >
    <div class="col-md-12">
        <ul class="uploadFiles"></ul>
        <input type="hidden" name="id" class="doc_id" value="">
        <input type="hidden" name="id" class="doc_id" value="">
        <div class="row">
          <div class="col-md-4">
            Status
          </div>
          <div class="col-md-8">
            <select class="form-control select_status">
              <option value="0" disabled="disabled">Requested</option>
              <option value="1">Submitted</option>
              <option value="2">Accepted</option>
              <option value="3">Requires Translation</option>
              <option value="4">Rejected</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-right">
            <br>
            <input type="hidden" name="case_id" class="case_id">
            <input type="button" name="Complete" value="Update" class="btn btn-primary completeform"/>
          </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="UploadTranslatedDocument" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Upload Document</h4>
      </div>
      <div class="modal-body">
        <form action="{{url('admin/document_request/setDataDocument2')}}" method="post" enctype="multipart/form-data" class="dropzone1" id="mydropzone1">
          <div class="row">
            <!-- <div class="col-md-12">
              File * 
            </div> -->
            <div class="col-md-12 fallback1">
              <input name="file[]" type="file" required="required"/>
            </div>
            <div class="col-md-12">
                <ul class="uploadFiles"></ul>
            </div>
          </div>
          <br>
          <div class="row">  
            <div class="col-md-12 text-right">
              @csrf
              <input type="hidden" name="id" class="uploaddoc_id" value="">
              <input type="hidden" name="case_id"  value="{{$id}}">
              <input type="submit" name="save" value="Save" class="btn btn-primary"/>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>

<script type="text/javascript">

var index_url = "{{url('admin/document_request/getDataDocument')}}/{{$id}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'did', name: 'did'},
        { data: 'case_id', name: 'case_id'},
        { data: 'name', name: 'name'},
        { data: 'document_type', name: 'document_type'},
        { data: 'expiration_date', name: 'expiration_date'},
        // { data: 'created_at', name: 'created_at'},
        { data: 'dstatus', name: 'dstatus'},
        { data: null,
          render: function(data){
            console.log('============',data.document_type); 
            var view_button = '<a href="#" data-id="'+data.did+'" data-case_id="'+data.case_id+'" data-status="'+data.dstatus1+'" data-files="'+data.document+'" class="btn btn-primary viewdocbtn"><i class="fa fa-eye"></i></a>';
            
            if(data.quote == 3 && data.dstatus1 == 3) {
                // view_button += ' <a href="#" data-id="'+data.did+'" data-case_id="'+data.case_id+'" data-status="'+data.dstatus1+'" data-files="'+data.document+'" class="btn btn-primary viewselftuploadbtn" title="Upload Translated Document"><i class="fas fa-file-upload"></i></i></a>'; 
            } 
            return view_button;

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
 });
$(document).on('click', '.convert_client_act', function(e){
  e.preventDefault();
  var file_type = $('select[name="file_type"]').val();
  var client_id = $('input[name="client_id"]').val();
  var case_id = $('input[name="case_id"]').val();
  var expiration_date = $('input[name="expiration_date"]').val();
  var csrf1 = $('input[name="_token"]').val();
  if(file_type != '') {
    $.ajax({
      url: "{{route('admin.document_request.setDataDocument')}}",
      data: {file_type: file_type, client_id: client_id, case_id: case_id, _token: csrf1, expiration_date: expiration_date},
      dataType: 'json',
      type: 'post',
      async: false,
      success: function (data) {
        console.log('success===========',data);
        alert('Document request successfully!');
        window.location.href = window.location.href;
      },
      error: function (data) {
        alert('Document request successfully!');
        window.location.href = window.location.href;
      }
    });
  }
  else {
    alert('Please select document type');
  }
 });
$(document).ready(function(){
  $('.rkselect').selectpicker();
  $('.completeform').on('click', function(){
      var v = $('.doc_id').val();
      var status = $('.select_status').val();
      var case_id = $('.case_id').val();
      $.ajax({
       type:"get",
       url:"{{ url('admin/document_request/completeDocument') }}/"+v,
       data: { status: status, case_id: case_id },
       success:function(res)
       {       
        
        window.location.href = window.location.href;
        
      }

    });
    });
    $('#fire-modal-2').on('click', function(){
      $('.daterangepicker.dropdown-menu').css('z-index', 99999);
      $('.modalformpart > form').show();
      $('.uploadFileswrap').hide();
    });
    $('.client_Cases').on('change', function(){
      var v = $(this).find(':selected').data('user_id');
      $.ajax({
       type:"get",
       url:"{{ url('admin/document_request/client_Cases') }}/"+v,
       success:function(res)
       {       
        if(res)
        {
          $(".CaseNumber").empty();
          $(".CaseNumber").append('<option>Select One</option>');
          $.each(res,function(key,value){
            $(".CaseNumber").append('<option value="'+key+'">'+value+'</option>');
          });
        }
      }

    });
    });
    });
 $("#fire-modal-2").fireModal({title: 'Request Document', body: $("#modal-form-part"), center: true});  
 // $("#fire-modal-4").fireModal({title: 'Request Document', body: $("#modal-form-part1"), center: true}); 
 $(document).on('click', '.viewdocbtn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('.doc_id').val(id);
    var files = $(this).data('files');
    var status = $(this).data('status');
    var case_id = $(this).data('case_id');
    $('.case_id').val(case_id);
    $('.select_status').val(status);
    $('.uploadFiles').empty();
    if(files) {
        for (var i = 0; i < files.length; i++) {
            var f = files[i];
            var url = "{{asset('storage/app')}}/"+f; 
            f = f.replace('client_doc/', '');
            var li = '<li><a href="'+url+'"  target="_blank">'+f+'</a></li>';
            $('.uploadFiles').append(li);
        }
      $('#fire-modal-2').trigger('click');
      $('.daterangepicker.dropdown-menu').css('z-index',0);
      $('.modalformpart > form').hide();
      $('.uploadFileswrap').show();  
    }
    else {
      alert('Document not Submitted');
    }
    
});
 $(document).on('click', '.viewselftuploadbtn', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      $('.uploaddoc_id').val(id);
      $('.uploadFiles').empty();
      $("#UploadTranslatedDocument").modal('show');
    });
</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 
