@extends('firmlayouts.admin-master')

@section('title')
Manage Case
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage Case</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.firmuserdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.usercase')}}">Case</a>
      </div>
    </div>
  </div>
  <div class="section-body">
       
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Case</h4>
            <div class="card-header-action">
              <!-- <a href="{{ url('firm/case/create') }}" class="btn btn-primary">Add <i class="fas fa-plus"></i></a> -->
            </div>
          </div>
          
          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                    <th> Id</th>
                    <th> Client name </th>
                    <th> Case type</th>
                    <th> Case cost</th>
                    <th> Alloted use</th>
                    <th> create date </th>
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

var index_url = "{{route('firm.usercase.getData')}}?case={{ Request::route()->getName() }}";
console.log(index_url);
var srn = 0;
// $( window ).load(function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      
      columns: [
        { data: 'id', name: srn},
        { data: 'client_name', name: 'client_name'},
        { data: 'case_type', name: 'case_type'},
        { data: 'case_cost', name: 'case_cost'},
        { data: 'user_name', name: 'user_name'},
        { data: 'created_at', name: 'created_at'},

        { data: null,
          render: function(data){

            var text = "'Are You Sure to delete this record?'";
            var view_button = ' <a href="{{url('firm/usercase/show')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
            var delete_button = ' <a href="{{url('firm/usercase/delete')}}/'+data.id+'" class="btn btn-danger" onclick="return window.confirm('+text+');"><i class="fa fa-trash"></i></a>';
            
            return view_button;
          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
 // });

//================ Edit user ============//

</script>

@endpush 
