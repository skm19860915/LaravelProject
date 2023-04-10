@extends('layouts.admin-master')

@section('title')
Firm Detail
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#usertable tbody tr td:nth-child(1) {
  display: none;
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
            <h3>Firm Users</h3>
            <div style="float: left;" class="country-left-select">
              <select class="form-control" name="role" id="role">
                  <option value="">All</option>
                  <option value="4">Firm Admin  </option>
                  <option value="5">Firm User</option> 
              </select>               
            </div>
            <div class="table-responsive table-invoice">
              <table class="table"  id="usertable" >
                <thead>
                  <tr>
                   <th style="display: none;"> Id </th>
                   <th> User Name </th>
                   <th> Role</th>
                   <th> Email </th>
                   <?php if($firm->account_type == 'CMS') { ?>
                   <th> CMS User Cost </th>
                   <?php } ?>
                   <th> Status </th>
                   <th> Created Date </th>
                   <!-- <th> Action </th> -->
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

<script>
$(document).ready(function(){
  var r = "{{$firm->id}}";
  var role = '';
  function getUserdata(r, role) {
    var index_url = "{{ url('admin/firm/get_firmuser_data') }}";
    var table = $('#usertable').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
        "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "firm_id": r,
                "role": role,
            }
        },
      order: [ [0, 'desc'] ],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'name', name: 'name'},
        { data: 'role_name', name: 'role_name'},
        { data: 'email', name: 'email'},
        // { data: r, name: 'role_name'},
        <?php if($firm->account_type == 'CMS') { ?>
        { data: null,
          render: function(data){
            var cost = "${{ number_format($firm->usercost,2) }}";
            return cost;
            }, orderable: "false"
        },
        <?php } ?>
        { data: 'stat', name: 'stat'},
        { data: 'created_at', name: 'created_at'},
      ], 
    });
  }
  getUserdata(r, role);
  $('#role').on('change', function(){
    var role = $(this).val();
    getUserdata(r, role);
  });
});

</script>

@endpush 