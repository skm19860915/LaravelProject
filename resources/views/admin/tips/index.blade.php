@extends('layouts.admin-master')

@section('title')
Manage Tips
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage Tips</h1>
    <div class="section-header-breadcrumb">
      <a href="{{ url('admin/helpfull_tips/create') }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">

          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped" id="table" >
                <thead>
                  <tr>
                   <th> Id</th>
                   <th> Title </th>
                   <th> Message</th>
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



var index_url = "{{route('admin.helpfull_tips.getData')}}";
var srn = 0;

$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      
      columns: [
        { data: 'id', name: 'id'},
        { data: 'title', name: 'title'},
        { data: 'message', name: 'message'},
        { data: 'stat', name: 'stat'},
        
        { data: null, 
          render: function(data){

            var delete_button = ' <a href="{{url('admin/helpfull_tips/delete')}}/'+data.id+'" class="action_btn"><img src="{{url('assets/images')}}/icons/case-icon3.svg"></a>';  

            var edit_button = ' <a href="{{url('admin/helpfull_tips/tips_edit')}}/'+data.id+'" class="action_btn"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
              return  edit_button + delete_button;

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
