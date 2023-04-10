@extends('firmlayouts.admin-master')

@section('title')
Clients
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
<section class="section">
  <div class="section-header">
    <h1>Clients</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.client')}}">Client</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">{{$client_name->first_name}} {{$client_name->middle_name}} {{$client_name->last_name}}</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Client Document</a>
      </div>
    </div>

  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Clients</h4>
            <div class="card-header-action">
              <button href="#" id="fire-modal-2" class="btn btn-primary viewselftuploadbtn">Add <i class="fas fa-plus"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> Id</th>
                   <th> Name </th>
                   <th> Title </th>
                   <th> Description </th>
                   <th> Uploaded By</th>
                   <th> Created</th>
                   <th style="width: 135px;"> Action</th>
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
        <form action="{{url('firm/client/setClientDocument')}}" method="post" enctype="multipart/form-data">
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
              <input type="hidden" name="client_id"  value="{{$id}}">
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
<script type="text/javascript">

var index_url = "{{url('firm/client/getClienDocument')}}/{{$id}}";
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
        { data: 'name', name: 'name'},
        { data: 'title', name: 'title'},
        { data: 'description', name: 'description'},
        { data: 'uploaded_name', name: 'uploaded_name'},
        { data: 'created_at', name: 'created_at'},
        { data: null,
          render: function(data){
            var view_button = '<a href="{{asset('storage/app')}}/'+data.document+'" class="action_btn" title="View" target="_blank"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
            
              
              return view_button;

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
 });
 $(document).on('click', '.viewselftuploadbtn', function(e){
    e.preventDefault();
    $("#UploadTranslatedDocument").modal('show');
  });
</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 
