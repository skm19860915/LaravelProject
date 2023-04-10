@extends('firmlayouts.admin-master')

@section('title')
Contacts
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Contacts</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.firmuserdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.firmcontacts')}}">Contact</a>
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
                    <th> Name </th>
                    <th> Phone Number</th>
                    <th> Client Number</th>
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

var index_url = "{{route('firm.firmcontacts.getData')}}?case={{ Request::route()->getName() }}";
var srn = 0;
// $( window ).load(function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      
      columns: [
        { data: 'id', name: srn},
        { data: 'name', name: 'name'},
        { data: 'cell_phone', name: 'cell_phone'},
        { data: 'id', name: srn},
        { data: 'created_at', name: 'created_at'},
        { data: null,
          render: function(data){

            var text = "'Are You Sure to delete this record?'";
            var view_button = ' <a href="{{url('firm/firmcontacts/show')}}/'+data.user_id+'" class="btn btn-primary"><i class="fa fa-eye"></i></a>';            
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
