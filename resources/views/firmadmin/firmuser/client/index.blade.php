@extends('firmlayouts.admin-master')

@section('title')
Manage Client
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
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> TID</th>
                   <th> Name </th>
                   <th> Email</th>
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

var index_url = "{{route('firm.firmclients.getData')}}";
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
        { data: 'created_at', name: 'created_at'},
       
        { data: null,
          render: function(data){

            var text = "'Are You Sure to delete this record?'";
            var view_button = ' <a href="{{url('firm/firmclient/show')}}/'+data.id+'" class="action_btn"><img src="{{url('assets/images/icon')}}/useradd@2x.png" /></a>';
            var edit_button = ' <a href="{{url('firm/firmclient/edit')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
            var delete_button = ' <a href="{{url('firm/firmclient/delete')}}/'+data.id+'" class="btn btn-danger" onclick="return window.confirm('+text+');"><i class="fa fa-trash"></i></a>';
            
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
