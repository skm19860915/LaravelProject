@extends('firmlayouts.admin-master')

@section('title')
Manage Users
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  


@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage users</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.firmuserdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.firmusers')}}">User</a>
      </div>
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Users </h4>
            <div class="card-header-action">
              <!-- <a href="{{ url('firm/users/create') }}" class="btn btn-primary">Add <i class="fas fa-plus"></i></a> -->
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th><img src="https://mayur.snvdev.website/StandardTrack/master_theme/images/icon9.png" class="arrow-table"/> Id</th>
                   <th><img src="https://mayur.snvdev.website/StandardTrack/master_theme/images/icon9.png" class="arrow-table"/> Name </th>
                   <th><img src="https://mayur.snvdev.website/StandardTrack/master_theme/images/icon9.png" class="arrow-table"/> Email</th>
                   <th><img src="https://mayur.snvdev.website/StandardTrack/master_theme/images/icon9.png" class="arrow-table"/> Role</th>
                   <th><img src="https://mayur.snvdev.website/StandardTrack/master_theme/images/icon9.png" class="arrow-table"/> create date </th>
                   <th><img src="https://mayur.snvdev.website/StandardTrack/master_theme/images/icon9.png" class="arrow-table"/> Action</th>
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

var index_url = "{{route('firm.firmusers.getData')}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      
      columns: [
        { data: 'id', name: srn},
        { data: 'name', name: 'name'},
        { data: 'email', name: 'email'},
        { data: 'role_name', name: 'role_name'},
        { data: 'created_at', name: 'created_at'},
      
        { data: null,
          render: function(data){

            var text = "'Are You Sure to delete this record?'";
            var delete_button = ' <a href="{{url('firm/users/delete')}}/'+data.id+'" class="btn btn-danger" onclick="return window.confirm('+text+');"><i class="fa fa-trash"></i></a>';
            
            //return /*edit_button + */delete_button;
            return '';
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
