@extends('layouts.admin-master')

@section('title')
View Case
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
<section class="section client-listing-details task-new-header">
<!-- new-header open--> 
<div class="section-header">
  <h1>
    <a href="{{ url('admin/dashboard') }}"><span>Dashboard /</span></a>
    <a href="{{ url('admin/users') }}"><span>Team /</span></a> 
    <a href="{{ url('admin/users/show') }}/{{$user->id}}"><span>View Details / </span></a>
    Cases
  </h1>
  <div class="section-header-breadcrumb">
    
  </div>
</div>
<div class="client-header-new">
  <div class="clent-profle-box">
    <div class="row">
     <div class="col-md-8">
      <div class="client-main-box-profile">
      <?php 
          $img = asset('storage/app').'/'.$user->avatar;
          if(empty(Auth::user()->avatar)) { 
              $img = url('/assets/img/avatar/avatar-1.png');
          }
      ?>
      <div class="client-left-img" style="background-image: url({{ $img }});"></div>
      <div class="client-right-text">
        <div class="clent-info">
          <span>Full Name</span>:<span>{{ $user->name }}</span>
        </div>
        <div class="clent-info">
          <span>Email</span>:<span>{{ $user->email }}</span>
        </div>
        <div class="clent-info">
          <span>Phone No.</span>:<span>{{ $user->contact_number }}</span>
        </div>
        <div class="clent-info">
          <span>Role</span>:<span>
            <?php 
            if($user->role_id == 2) {
              echo 'TILA VP';
            }
            else {
              echo 'TILA Admin';
            }
            ?>
          </span>
        </div>
      </div>  
         
     </div>
     <div class="col-md-4">
      <div class="client-right-profile">
       


      </div>
     </div>
    </div>
  </div>
  </div>
  <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'admin.users.show' ? 'active-menu' : '' }}" href="{{ url('admin/users/show') }}/{{ $user->id }}">Overview</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.users.assigned' ? 'active-menu' : '' }}" href="{{ url('admin/users/assigned') }}/{{ $user->id }}">Assigned</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.users.cases' ? 'active-menu' : '' }}" href="{{ url('admin/users/cases') }}/{{ $user->id }}">Cases</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.users.tasks' ? 'active-menu' : '' }}" href="{{ url('admin/users/tasks') }}/{{ $user->id }}">Tasks</a></li>
    </ul>
  </div>

<!--new-header Close -->

  <div class="section-body">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div style="float: left;" class="country-left-select">
                <select class="form-control" name="case_status" id="case_status">
                    <option value="">All</option>
                    <option value="Open">Open Case</option>
                    <option value="OnHold">Working Case</option>
                    <option value="InReview">In Review Case</option> 
                    <option value="Complete">Complete Case</option>
                    <option value="InComplete">In Complete Case</option>
                </select>               
            </div>
            <div class="table-responsive table-invoice user-case-table">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> Id</th>
                   <th> Client name </th>
                   <th> Case Type</th>
                   <th> Firm name </th>
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

var index_url = "{{route('admin.get_cases_data')}}";
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
                "s1": s,
                "vpuser": "{{$user->id}}",
            }
      },
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'client_name', name: 'client_name'},
        { data: 'case_type', name: 'case_type'},
        { data: 'firm_name', name: 'firm_name'},
        { data: 'case_status', name: 'case_status'},
        
        { data: null,
          render: function(data){

            var view_button = ' <a href="{{url('admin/allcases/show')}}/'+data.case_id+'" class="action_btn" data-toggle="tooltip" title="View details"><img src="{{url('assets/images/icon')}}/Group 557.svg" /></a>';
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
</script>

@endpush 