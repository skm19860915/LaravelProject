@extends('firmlayouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage Task</h1>
    @include('layouts.breadcrumb')
  </div>
  <div class="section-body">
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Tasks</h4>
            <!-- <div class="card-header-action">
              <a href="{{ url('admin/task/create') }}" class="btn btn-primary">Add <i class="fas fa-plus"></i></a>
            </div> -->
          </div>

          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th><img src="https://mayur.snvdev.website/StandardTrack/master_theme/images/icon9.png" class="arrow-table"/> Id</th>
                   <th><img src="https://mayur.snvdev.website/StandardTrack/master_theme/images/icon9.png" class="arrow-table"/> Task </th>
                   <th><img src="https://mayur.snvdev.website/StandardTrack/master_theme/images/icon9.png" class="arrow-table"/> Status</th>
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
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script type="text/javascript">

var index_url = "{{route('firm.task.getData')}}";
var srn = 0;
// $( window ).load(function() {
    srn++;
    var table = $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      dom: 'lBfrtip',
      buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
      columns: [
        { data: 'id', name: srn},
        { data: 'task', name: 'task'},
        { data: 'status', name: 'status'},
        { data: 'created_at', name: 'created_at'},
       
        { data: null,
          render: function(data){

            var text = "'Are You Sure to delete this record?'";
            var view_button = ' <a href="{{url('firm/task/show')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
            var delete_button = ' <a href="{{url('firm/task/delete')}}/'+data.id+'" class="btn btn-danger" onclick="return window.confirm('+text+');"><i class="fa fa-trash"></i></a>';
            
            return view_button + delete_button;
          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
    table.buttons().container().appendTo($('.report-icon-box'));
 // });

//================ Edit user ============//

</script>

@endpush 
