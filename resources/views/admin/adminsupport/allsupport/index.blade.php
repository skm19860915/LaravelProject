@extends('layouts.admin-master')

@section('title')
All Ticket
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>All Ticket</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('admin.supportdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('admin.allsupport')}}">Allsupport</a>
      </div>
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>All Ticket</h4>
            <div class="card-header-action">
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th> Id</th>
                   <th> User type</th>
                   <th> User Name</th>
                   <th> Message</th>
                   <th> Priority</th>
                   <th> Support By</th>
                   <th> Status</th>
                   <th> Created</th>
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
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">

var index_url = "{{route('admin.allsupport.getData')}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      
      columns: [
        { data: 'id', name: 'id'},
        { data: 'role_name', name: 'role_name'},
        { data: 'username', name: 'username'},
        { data: 'message', name: 'message'},
        { data: 'priority', name: 'priority'},
        { data: 'supportername', name: 'supportername'},
        { data: 'status', name: 'status'},  
        { data: 'created_at', name: 'created_at'},
        
        { data: null,
          render: function(data){

            var view_button = ' <a href="{{url('admin/allsupport/show')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
            if (data.supporter_id == 0) {
              var accept_button = ' <a href="{{url('admin/allsupport/accept')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-handshake"></i></a>';
              return view_button + accept_button;
            }else{
              return view_button;
            }

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
 });

//================ Edit user ============//

</script>

@endpush 
