@extends('layouts.admin-master')

@section('title')
User Details
@endsection
@push('header_styles')
<style type="text/css">
.task-new-header .overview-border-number {
  width: auto;
  text-align: center;
  padding-right: 15px;
}
</style>
@endpush 
@section('content')
<section class="section client-listing-details task-new-header">
<!-- new-header open--> 
<div class="section-header">
  <h1>
    <a href="{{ url('admin/dashboard') }}"><span>Dashboard /</span></a>
    <a href="{{ url('admin/users') }}"><span>Team /</span></a> User Details
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
            <div class="form-overview-new">
               <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                   <div class="overview-numbers">
                    <div class="overview-border-number">
                       <h3>{{ $data['onhold_case'] }}</h3>
                       <p>Active</p>
                     </div>
                    
                    <div class="overview-border-number">
                       <h3>{{ $data['open_case'] }}</h3>
                       <p>Waiting</p>
                     </div>
                     <div class="overview-border-number">
                       <h3>{{ $data['inreview_case'] }}</h3>
                       <p>Review</p>
                     </div>
                    <div class="overview-border-number">
                       <h3>{{ $data['complete_case'] }}</h3>
                       <p>Completed</p>
                     </div>
                     <div class="overview-border-number">
                       <h3>{{ $data['total_case'] }}</h3>
                       <p>Total Case</p>
                     </div>
                  </div>
                   
                 </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <h4>Today's Tasks</h4>
                  <div class="table-responsive table-invoice">
                    <table class="table"  id="usertable" >
                      <thead>
                        <tr>
                         <th> Task </th>
                         <th> Client Name </th>
                         <th> Status</th>
                         <th> Created Date </th>
                         <th> Due Date </th>
                         <th> Priority </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        foreach ($admintask as $k => $v) { ?>
                        <tr>
                          <td>{{$v->task}}</td>
                          <td>{{$v->client}}</td>
                          <td>{{$v->status}}</td>
                          <td>{{$v->created_at}}</td>
                          <td>{{$v->created_at}}</td>
                          <td>{{$v->priority}}</td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
             </div>
          </div>
         </div>
      </div>
     </div>
  </div>
</section>
@endsection
