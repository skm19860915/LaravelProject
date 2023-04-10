@extends('firmlayouts.client-family')

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
  @include('firmclientfamily.cases.include.case_header')
<!--new-header Close-->
   
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">        

          <div class="card-body">
            <div class="profile-new-client">
             <h2>Documents</h2>
             <!-- <a href="#" class="add-task-link" id="fire-modal-2">+ Add New</a> -->
             
             <div class="documents-list-box">
             
             <div class="task-tabbtn-box">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Requested Document</a>
              </li>      
              <!-- <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Case Documents</a>
               </li>    -->   
             </ul>
             </div>
             
             <div class="task-tabcontent-box">
              <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="table-responsive table-invoice">
                  <table class="table table table-bordered table-striped"  id="table" >
                    <thead>
                      <tr>
                       <th style="display: none;"> Id</th>
                       <!-- <th> Case ID </th> -->
                       <th> Type</th>
                       <th> Due Date</th>
                       <th> Status</th>
                       <th style="width: 135px;"> Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>    
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-6">
                  <div class="documents-pdf-box">
                   <div class="documents-pdf-icon">
                    <button href="#" class="btn btn-primary clientdocument">Browse
                    </button>
                   </div>
                 </div>
               </div>
               <?php
                if(!empty($client_doc)) {
                  foreach ($client_doc as $k => $v) { 
                    $dlink = $v->document;
                    ?>
                   <div class="col-md-2 col-sm-2 col-xs-6">
                    <div class="documents-pdf-box">
                     <div class="documents-pdf-icon"><img src="{{ url('/') }}/assets/images/icon/files-and-folders-con.svg">
                      <a href="#" data-toggle="tooltip" title="<?php echo $v->description; ?>">
                        <i class="fa fa-info"></i>
                      </a>
                     </div>
                     
                      <a href="{{asset('storage/app')}}/<?php echo $dlink; ?>" download>
                        <div class="name-pdf text-center"><?php echo $v->title; ?></div>
                      </a>
                      
                    </div>
                   </div>
                  <?php  
                  }
                }
                ?>               
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
<div class="modalformpart modal-document_request" id="modal-form-part" style="display: none;">
  <form action="{{url('firm/clientfamilydashboard/setCaseFamilyDocument')}}" method="post" enctype="multipart/form-data" class="dropzone1" id="mydropzone1">
    <div class="row">
      <!-- <div class="col-md-12">
        File * 
      </div> -->
      <div class="col-md-12 fallback1">
        <input class="form-control" name="file[]" type="file" multiple required="required"/>
      </div>
      <div class="col-md-12">
          <ul class="uploadFiles"></ul>
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-12 text-right">
        @csrf
        <input type="hidden" name="id" class="doc_id" value="">
        <input type="submit" name="save" value="Save" class="btn btn-primary convert_client_act"/>
      </div>
    </div>
  </form>
</div>
<button id="fire-modal-2" class="btn btn-primary trigger--fire-modal-2 trigger--fire-modal-1" style="display: none;"></button>
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
        <form action="{{url('firm/firmclient/document_request/setCaseDocument4')}}" method="post" enctype="multipart/form-data">
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
              <input type="hidden" name="case_id"  value="{{$case->case_id}}">
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
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
var index_url = "{{url('firm/clientfamilydashboard/getCaseFamilyDataDocument')}}/{{$case->case_id}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        // { data: 'case_id', name: 'case_id'},
        { data: 'document_type', name: 'document_type'},
        { data: 'expiration_date', name: 'expiration_date'},
        { data: 'status', name: 'status'},
        { data: null,
          render: function(data){

            var view_button = ' <a href="#" data-id="'+data.id+'" data-files="'+data.document+'" class="action_btn viewdocbtn"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>';
              
              return view_button;

          }, orderable: "false"
        },
      ],
    });
    $("#fire-modal-2").fireModal({title: 'Request Document', body: $("#modal-form-part"), center: true});
 });  
$(document).on('click', '.viewdocbtn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var files = $(this).data('files');
    $('.doc_id').val(id);
    $('.uploadFiles').empty();
    if(files) {
        for (var i = 0; i < files.length; i++) {
            var f = files[i];
            var url = "{{asset('storage/app')}}/"+f; 
            f = f.replace('client_doc/', '');
            var li = '<li><a href="'+url+'"  target="_blank">'+f+'</a></li>';
            $('.uploadFiles').append(li);
        }
    }
    $('#fire-modal-2').trigger('click');
})
$(document).on('click', '.clientdocument', function(e){
    e.preventDefault();
    $("#UploadClinetDocument").modal('show');
});
</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 