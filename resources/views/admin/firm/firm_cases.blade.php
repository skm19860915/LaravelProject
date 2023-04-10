@extends('layouts.admin-master')

@section('title')
Firm Detail
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
  display: none;
}
body div#table_filter {
  display: block;
}
.customboxes {
  float: right;
  text-align: center;
  padding: 10px;
  width: auto;
  margin-right: 0;
  margin-left: 10px;
}
</style>
@endpush

@section('content')
<section class="section client-listing-details">
  <div class="section-header">
    <h1><a href="{{ url('admin/firm') }}"><span>Firm /</span></a> Detail</h1>
  </div>
  <!--new-header open-->
  @include('admin.firm.firm_header')
  <!--new-header Close-->
  
  <div class="section-body">
    <div class="row">
     <div class="col-md-12">
      <div class="card">
        <div class="back-btn-new">
          <a href="{{ url('admin/firm') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
         </div>
         <div class="card-body">
            <div style="float: left; width: 230px; margin-top: 15px;" class="country-left-select">
                <select class="form-control case_status" style="width: 220px; display: inline-block;">
                  <option value="">All</option>
                  <option value="1">Self managed</option>
                  <option value="2">VP Assigned</option>
                </select>              
            </div>
            
            <div class="overview-border-number customboxes">
             <h3>$<?php echo number_format($data['total_billing'], 2); ?></h3>
             <p>Total Value</p>
           </div>
           <div class="overview-border-number customboxes">
              <h3>{{$data['total_client']}}</h3>
              <p>Clients</p>
            </div>
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped" id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> Id</th>
                   <th> Client Name</th>
                   <th> Case Type</th>
                   <th> Cost</th>
                   <th> Status</th>
                   <th> Created Date</th>
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



var index_url = "{{route('admin.firm.getFirmCaseData')}}";
$(window).on('load', function() {
    var status = 0;
    var firm_id = "{{$firm->id}}";
    gettabledata(status, firm_id);
    function gettabledata(status, firm_id) {
      $('#table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "status": status,
                "firm_id": firm_id
            }
        },
        "order": [[ 0, "desc" ]],
        columns: [
          { data: 'id', name: 'id'},
          // { data: 'client_name', name: 'client_name'},
          { data: null,
          render: function(data){
            var view_button = ' <a href="'+data.clink+'" data-toggle="tooltip" title="View details">'+data.client_name+'</a>';
              return view_button;

            }, orderable: "false"
          },
          { data: 'case_type', name: 'case_type'},
          { data: 'case_cost', name: 'case_cost'},
          { data: 'case_status', name: 'case_status'},
          { data: 'created_at', name: 'created_at'}
        ],
      });
    }
    $('select.case_status').on('change', function(){
      status = $(this).val();
      gettabledata(status, firm_id);
    });
 });
//================ Edit user ============//

</script>

@endpush 