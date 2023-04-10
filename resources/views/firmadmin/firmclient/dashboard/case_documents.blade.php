@extends('firmlayouts.admin-master')
@section('title')
Profile
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/dropzone.css') }}" rel="stylesheet">
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
  #table tbody tr td:nth-child(1) {
    display: none;
  }
  .user-upload-file label {
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
      position: relative;
  } 
  .editimg {
      background: #013E41;
      width: 45px;
      border-radius: 50%;
      display: block;
      height: 45px;
      padding: 11px;
      position: absolute;
      bottom: 0;
      right: 0;
  }
  .user-upload-file label .editimg img {
      width: 100%;
  }
</style>
@endpush

@section('content')
<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <!-- <h1>Profile  ddd</h1> -->
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">

            </div>
        </div>
    </div>
    <div class="client-header-new">
   <div class="clent-profle-box">
    <div class="row">
     <div class="col-md-7">
      <div class="client-main-box-profile">
      <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
      <div class="client-right-text">
       <div class="clent-info"><span>Name</span>:<span>{{$data->name}}
       </span></div>
       <div class="clent-info"><span>Email </span>:<span>{{ $data->email }}</span></div>
       <div class="clent-info"><span>Phone Number </span>:<span>{{ $data->contact_number }}</span></div>
      </div>  
      </div>    
     </div>
     <div class="col-md-5">
      <div class="client-right-profile">
        <div class="clent-info"><span>Status</span>:<span>Active</span></div>
       <div class="clent-info"><span>Created On</span>:<span>{{ $case->created_at }}</span>
       </div>
       <div class="clent-info"><span>Case Type</span>:<span>{{ $case->case_type }}</span>
       </div>
       
      </div>
     </div>
    </div>
   </div>
   <div class="client-menu-box">
 
   </div>
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
                        <h2>Case Documents</h2>
                        <!-- <a href="#" class="add-task-link" id="fire-modal-2">+ Request</a> -->
                         
                        <div class="documents-list-box" style="display: none;">
                          <div class="task-tabbtn-box">
                          <ul class="nav nav-tabs" id="myTab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Requested Documents</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Case Documents</a>
                          </li>              
                         </ul>
                         </div>
                        </div>
                      </div>
                      <div class="task-tabcontent-box">
                        <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="table-responsive table-invoice all-case-table">
                              <table class="table table table-bordered table-striped"  id="table" >
                                <thead>
                                  <tr>
                                     <th style="display: none;"> Id</th>
                                     <th> Type</th>
                                     <th> Status</th>
                                     <th> Name </th>
                                     <th> Due Date</th>
                                     <th> Document</th>
                                     <th> Action</th>
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
                               if(!empty($case->case_file_path)) { ?>
                                  <div class="col-md-2 col-sm-2 col-xs-6">
                                    <div class="documents-pdf-box">
                                     <div class="documents-pdf-icon">
                                      <a href="{{asset('storage/app')}}/<?php echo $case->case_file_path; ?>" download style="position: static;" data-toggle="tooltip" title="Click here to download">
                                        <img src="{{ url('/') }}/assets/images/icon/files-and-folders-con.svg">
                                      </a>
                                      <a href="#" data-toggle="tooltip" title="">
                                        <i class="fa fa-info"></i>
                                      </a>
                                     </div>
                                     
                                      <a href="{{asset('storage/app')}}/<?php echo $case->case_file_path; ?>" download>
                                        <div class="name-pdf text-center">Case Document</div>
                                      </a>
                                      
                                    </div>
                                   </div>
                               <?php }
                                if(!empty($client_doc)) {
                                  foreach ($client_doc as $k => $v) { 
                                    $dlink = $v->document;
                                    ?>
                                   <div class="col-md-2 col-sm-2 col-xs-6">
                                    <div class="documents-pdf-box">
                                     <div class="documents-pdf-icon">
                                      <a href="{{asset('storage/app')}}/<?php echo $dlink; ?>" download style="position: static;" data-toggle="tooltip" title="Click here to download">
                                        <img src="{{ url('/') }}/assets/images/icon/files-and-folders-con.svg">
                                      </a>
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
</section>
<div class="modalformpart modal-document_request" id="modal-form-part" style="display: none;">
  <form action="{{url('firm/firmclient/document_request/setDataDocument')}}" method="post" enctype="multipart/form-data" class="dropzone1" id="mydropzone1">
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
<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">

var index_url = "{{url('firm/firmclient/document_request/getDataDocument1')}}/{{$client->id}}";
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
        { data: 'document_type', name: 'document_type'},
        { data: 'dstatus', name: 'dstatus'},
        { data: 'name', name: 'name'},
        { data: 'expiration_date', name: 'expiration_date'},
        // { data: 'document', name: 'document'},
        { data: null,
          render: function(data){
            var doc = '';
            if(data.document) {
              f = data.doc_name.replace('client_doc/', '');
              var doc = '<a href="'+data.doclink+'" download style="position: static;" data-toggle="tooltip" title="Click here to download">'+f+'</a>';
            }
            else {
              var doc = '';
            }
            return doc;
          }, orderable: "false"
        },
        { data: null,
          render: function(data){

            var view_button = ' <a href="#" data-id="'+data.did+'" data-files="'+data.document+'" class="action_btn viewdocbtn"><i class="fas fa-upload"></i></a>';
              
              return view_button;

          }, orderable: "false"
        },
      ],
    });
    $("#fire-modal-2").fireModal({title: 'Upload Document', body: $("#modal-form-part"), center: true});
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
@endpush 