@extends('layouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
#table tbody tr td:nth-child(1) {
  display: none;
}  
</style>
@endpush  

@section('content')
<section class="section client-listing-details task-new-header-document">
<!--new-header open-->
  @include('admin.adminuser.usertask.task_header')
<!--new-header Close-->
   
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">        

          <div class="card-body">
            <div class="profile-new-client">
             <h2>Documents</h2>
             <a href="#" class="add-task-link" id="fire-modal-2">+ Request</a>
             
             <div class="documents-list-box">
             
             <div class="task-tabbtn-box">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Requested Documents</a>
              </li>
             </ul>
             </div>
             
             <div class="task-tabcontent-box">
              <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="table-responsive table-invoice all-case-table">
                  <table class="table table table-bordered table-striped"  id="table" >
                    <thead>
                      <tr>
                       <th style="display: none;"> Id</th>
                       <!-- <th> Case ID </th> -->
                       
                       <th> Type</th>
                       <th> Due Date</th>
                       <th> Assign To </th>
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
          if(!empty($CaseTypes[0]->Required_Documentation_en)) {
            foreach ($CaseTypes[0]->Required_Documentation_en as $key => $v) {
              if(in_array(trim($v), $already_requested) && false) {
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
      <div class="col-md-4">
        Request to
      </div>
      <div class="col-md-8">
        <select name="family_id" class="selectpicker rkselect" multiple="multiple" required="required" data-live-search="true">
          <?php 
          if(!empty($client)) {
            $cn = $client->first_name.' '.$client->middle_name.' '.$client->last_name;
            echo "<option value='$client_id'>$cn</option>"; 
          }
          if(!empty($family_alllist)) {
            foreach ($family_alllist as $key => $v) {
               echo "<option value='".$v->uid."'>".$v->name."</option>"; 
            } 
          }
          ?>
        </select>
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-12 text-right">
        @csrf
        <input name="client_id" type="hidden" value="{{$client_id}}" required="required">
        <input name="case_id" type="hidden" value="{{$case->id}}" required="required">
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
<div id="UploadClinetDocument" class="modal fade" role="dialog">
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
        <form action="{{url('admin/usertask/setCaseDocument5')}}" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-12 fallback1">
              <input name="title" type="text" class="form-control" placeholder="Title" required="required"/>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12 fallback1">
              <textarea name="description" placeholder="Description" class="form-control"></textarea>
            </div>
          </div>
            <br>
          <div class="row">
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
              <input type="hidden" name="id"  value="{{$admintask->id}}">
              <input type="hidden" name="case_id"  value="{{$case->id}}">
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

var index_url = "{{url('admin/document_request/getFamilyDocument')}}/{{$case->id}}/{{$fid}}";
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
        // { data: 'case_id', name: 'case_id'},
        { data: 'document_type', name: 'document_type'},
        { data: 'expiration_date', name: 'expiration_date'},
        // { data: 'created_at', name: 'created_at'},
        { data: 'name', name: 'name'},
        { data: 'dstatus', name: 'dstatus'},
        { data: null,
          render: function(data){
            console.log('============',data.document_type); 
            var view_button = '<a href="#" data-id="'+data.did+'" data-case_id="'+data.case_id+'" data-status="'+data.dstatus1+'" data-files="'+data.document+'" class="action_btn viewdocbtn"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>';
            
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
  var family_id = $('select[name="family_id"]').val();
  if(file_type != '') {
    if(family_id != '') {
      $.ajax({
        url: "{{route('admin.document_request.setDataDocument')}}",
        data: {file_type: file_type, client_id: client_id, case_id: case_id, _token: csrf1, expiration_date: expiration_date, family_id:family_id},
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
      alert('Please select request to');
    }
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
        var url = window.location.href;
        url = url.replace('#', '');
        window.location.href = url;
        
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
$(document).on('click', '.clientdocument', function(e){
    e.preventDefault();
    $("#UploadClinetDocument").modal('show');
});
//================ Edit user ============//

</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 
