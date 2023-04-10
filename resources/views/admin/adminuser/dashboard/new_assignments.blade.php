@extends('layouts.admin-master')

@section('title')
New Assignments
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
  display: none;
} 
.action_btn {
  border-color: transparent !important; 
} 
.action_btn img {
  width: 35px !important;
  margin-top: -6px;
  margin-left: -1px;
}
</style>
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>New Assignments</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">

     <div class="row">
      <div class="col-md-12">
        <div class="card">

          <div class="card-body">
            <div style="float: left;" class="country-left-select">
                <select class="form-control" name="case_status" id="case_status">
                    <option value="">All</option>
                    <option value="2">Pending</option>
                    <option value="1">Accept</option>
                    <option value="-1">Denied</option> 
                  </select>               
            </div>
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> Id</th>
                   <th> Case Type</th>
                   <th> Firm name </th>
                   <th> Status</th>
                   <th> Created Date</th>
                   <th> Date Assigned</th>
                   <th> Actions</th>
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

var index_url = "{{route('admin.getNewAssignments')}}";
$(window).on('load', function() {
  function getCaseData(s = '') {
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
      "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "status": s
            }
      },
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'case_type', name: 'case_type'},
        { data: 'firm_name', name: 'firm_name'},
        { data: 'stat', name: 'stat'},
        { data: 'created_date', name: 'created_date'},
        { data: 'assigned_date', name: 'assigned_date'},
        { data: null,
          render: function(data){
            var accept_button = '';
            var denied_button = '';
            if(data.is_edit) {
              accept_button = ' <a href="{{url('admin/accept_assignment')}}/'+data.tid+'" class="action_btn" data-toggle="tooltip" title="Accept"><img src="{{url('assets/images/icon')}}/accept.png" /></a>';
              denied_button = ' <a href="{{url('admin/denied_assignment')}}/'+data.tid+'" class="action_btn" data-toggle="tooltip" title="Denied"><img src="{{url('assets/images/icon')}}/dislike.png" /></a>';
            }
              return accept_button + denied_button;

          }, orderable: "false"
        },
      ],
    });
  }
  getCaseData();
  $('#case_status').on('change', function(){
    var s = $(this).val();
    getCaseData(s);
  });
 });

//================ Edit user ============//

</script>

@endpush 
