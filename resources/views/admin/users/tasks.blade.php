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
select#case_status {
    width: 200px;
    margin: 15px 0;
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
    Tasks
  </h1>
  <div class="section-header-breadcrumb">
    <a href="{{ url('admin/users/newtasks') }}/{{$user->id}}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> New Task
    </a>
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
            <!-- <div style="float: left;" class="country-left-select"> -->
                <select class="form-control" name="case_status" id="case_status">
                    <option value="">All</option>
                    <option value="week">Due this week</option>
                    <option value="15days">Due in 15 days</option>
                    <option value="30days">Due in 30 days</option> 
                </select>               
            <!-- </div> -->
            <div class="task-tabbtn-box">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
               <li class="nav-item">
                <a class="nav-link active" data-status="0" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Open Task</a>
               </li>
               <li class="nav-item">
                <a class="nav-link" data-status="1" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Complete Task</a>
               </li>              
              </ul>
             </div>
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> Id</th>
                   <th> Client Name</th>
                   <th> Task</th>
                   <th> Created Date</th>
                   <th> Status</th>
                   <th> Due Date</th>
                   <th> Priority</th>
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

var index_url = "{{route('admin.users.getTaskData')}}";
var s = 0;
var due_date = '';
$(window).on('load', function() {
  function getTaskData(s, due_date) {
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
      "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "s": s,
                "due_date": due_date,
                "vpuser": "{{$user->id}}",
            }
      },
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        // { data: 'clientname', name: 'clientname'},
        { data: null,
          render: function(data){

            var view_button = ' <a href="'+data.clink+'" data-toggle="tooltip" title="View details">'+data.clientname+'</a>';
              return view_button;

          }, orderable: "false"
        },
        { data: 'mytask', name: 'mytask'},
        { data: 'created_at', name: 'created_at'},
        { data: 'stat', name: 'stat'},
        { data: 'due_date', name: 'due_date'},
        { data: 'priority', name: 'priority'},
      ],
    });
  }
  getTaskData(s, due_date);
  $('#case_status').on('change', function(){
    s = $('.nav-link.active').data('status');
    due_date = $(this).val();
    getTaskData(s, due_date);
  });
  $('.nav-link').on('click', function(){
    s = $(this).data('status');
    due_date = $('#case_status').val();
    getTaskData(s, due_date);
  });
 });
</script>

@endpush 