@extends('layouts.admin-master')

@section('title')
Manage Firm
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
  display: none;
}
div#table_filter {
  display: block !important;
}
</style>
@endpush  

@section('content')
<section class="section client-listing">
  <div class="section-header">
    <h1><a href="{{ url('admin/dashboard') }}"><span>Dashboard /</span></a> Manage Firm</h1>
    
    <div class="section-header-breadcrumb">
      <a href="{{ url('admin/firm/create') }}" class="btn btn-primary" style="width: auto; padding: 0 18px;">
        <i class="fas fa-plus"></i> Add New
      </a>
    </div>

  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div style="float: left;" class="country-left-select">
                <select class="form-control" name="firmtype" id="firmtype" style="display: inline-block;">
                    <option value="">All Account Type</option>
                    <option value="CMS">CMS</option>
                    <option value="VP Services">VP Services</option>
                </select>      
                <select class="form-control" name="firmstatus" id="firmstatus" style="display: inline-block;">
                    <option value="">All Status</option>
                    <option value="1" selected="selected">Active</option>
                    <option value="3">Pending</option>
                    <option value="2">Inactive</option>
                </select>            
            </div>
            <div class="table-responsive table-invoice admin-table-firm">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                    <th style="display: none;">Firm Id</th>
                    <th>Firm Name</th>
                    <th>Account Type</th>
                    <th>Firm Admin Email</th>
                    <th>Admin Name</th>
                    <th>Status</th>
                    <th>Action</th>
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



var index_url = "{{route('admin.firm.getData')}}";

$(window).on('load', function() {
  var t = $('#firmtype').val();
  var s = $('#firmstatus').val();
  function getFirmData(t = '', s = '') {
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
      "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "firmtype": t,
                "firmstatus": s
            }
        },
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'firm_name', name: 'firm_name'},
        { data: 'account_type', name: 'account_type'},
        { data: 'email', name: 'email'},
        { data: 'firm_admin_name', name: 'firm_admin_name'},
        { data: 'stat', name: 'stat'},
        { data: null,
          render: function(data){

            if(data.status == 1){
              var delete_button = ' <a href="{{url('admin/firm/delete')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" title="Deativate Firm Account" onclick="return confirm(\'Are you sure?\')"><img src="{{url('assets/images')}}/icons/case-icon3.svg"></a>';  
            }else{
              var delete_button = ' <a href="{{url('admin/firm/reactive')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" title="Activate Firm Account"><img src="{{url('assets/images')}}/icon/Group 16@2x.png"></a>';
            }
            var view_button = ' <a href="{{url('admin/firm/firm_details')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" title="View Firm Details"><img src="{{url('assets/images/icon')}}/Group 16@2x.png" /></a>';
              var time_button = ' <a href="{{url('admin/firm/timeline')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" title="View Firm Activity"><img src="{{url('assets/images')}}/icons/clock(3).svg"></a>';
              var edit_button = ' <a href="{{url('admin/firm/firm_edit')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" title="Edit Firm Details"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
              return edit_button + view_button + time_button + delete_button;

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
  }
  getFirmData(t, s);
  $('#firmtype').on('change', function(){
    var t = $('#firmtype').val();
    var s = $('#firmstatus').val();
    getFirmData(t, s);
  });
  $('#firmstatus').on('change', function(){
    var t = $('#firmtype').val();
    var s = $('#firmstatus').val();
    getFirmData(t, s);
  });
 });

//================ Edit user ============//

</script>

@endpush 