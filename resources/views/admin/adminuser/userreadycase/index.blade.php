@extends('layouts.admin-master')

@section('title')
Manage Ready Case
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage Ready Case</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('admin.userdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('admin.readycase')}}">Ready case</a>
      </div>
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Ready Case</h4>
            <div class="card-header-action">
              <!-- <a href="{{ url('firm/client/create') }}" class="btn btn-primary">Add <i class="fas fa-plus"></i></a> -->
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th> Id</th>
                   <th> Firm name </th>
                   <th> Task type</th>
                   <th> Task</th>
                   <th> Case id </th>
                   <th> VA User Allot status</th>
                   <th> Priority</th>
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
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">

var index_url = "{{route('admin.readycase.getDataReady')}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      
      columns: [
        { data: 'id', name: 'id'},
        { data: 'firm_name', name: 'firm_name'},
        { data: 'task_type', name: 'task_type'},
        { data: 'task', name: 'task'},
        { data: 'case_id', name: 'case_id'},
        { data: 'allot_user_id', name: 'allot_user_id'},
        { data: 'priority', name: 'priority'},
        { data: 'stat', name: 'stat'},
        
        { data: null,
          render: function(data){

            var view_button = ' <a href="{{url('admin/task/show')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
              
              return view_button;

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
