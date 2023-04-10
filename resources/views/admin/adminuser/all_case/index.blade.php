@extends('layouts.admin-master')

@section('title')
Manage All case
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
    <h1>My Cases</h1>
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
                    <option value="Open">Open Case</option>
                    <option value="Working">Working Case</option>
                    <option value="InReview">In Review Case</option> 
                    <option value="Complete">Complete Case</option>
                    <option value="InComplete">In Complete Case</option>
                </select>               
            </div>
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> Id</th>
                   <th> Client Name</th>
                   <th> Case Type</th>
                   <th> Firm name </th>
                   <th> Assigned Date </th>
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

var index_url = "{{route('admin.all_case.getDataAll')}}";
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
        { data: null,
          render: function(data){
            var link = '<a href="'+data.clink+'" data-toggle="tooltip" title="View details">'+data.clientname+'</a>';
            return link;
            }, orderable: "false"
        },
        { data: 'case_type', name: 'case_type'},
        { data: 'firm_name', name: 'firm_name'},
        { data: 'assigned_date', name: 'assigned_date'},
        { data: 'stat', name: 'stat'},
        { data: null,
          render: function(data){

            var view_button = ' <a href="{{url('admin/usertask/overview')}}/'+data.tid+'" class="action_btn" data-toggle="tooltip" title="View Case"><img src="{{url('assets/images/icon')}}/Group 16@2x.png" /></a>';
              return view_button;

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
