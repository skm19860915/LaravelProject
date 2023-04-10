@extends('layouts.admin-master')

@section('title')
View Details
@endsection
@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">

</style>
@endpush
@section('content')
<section class="section client-listing-details task-new-header">
<!-- new-header open--> 
<div class="section-header">
  <h1><a href="{{ url('admin/userdashboard') }}"><span>Dashboard /</span></a> Client Details</h1>
</div>
<div class="client-header-new">
  <div class="clent-profle-box">
    <div class="row">
     <div class="col-md-8">
      <div class="client-main-box-profile">
      <?php 
          $img = asset('storage/app').'/'.$client->avatar;
          if(empty(Auth::user()->avatar)) { 
              $img = url('/assets/img/avatar/avatar-1.png');
          }
      ?>
      <div class="client-left-img" style="background-image: url({{ $img }});"></div>
      <div class="client-right-text">
        <div class="clent-info">
          <span>Full Name</span>:<span>{{ $client->name }}</span>
        </div>
        <div class="clent-info">
          <span>Email</span>:<span>{{ $client->email }}</span>
        </div>
        <div class="clent-info">
          <span>Phone No.</span>:<span>{{ $client->contact_number }}</span>
        </div>
        <div class="clent-info">
          <span>Role</span>:<span>
            Client
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
      <li><a class="{{ Request::route()->getName() == 'admin.userdashboard.viewclient' ? 'active-menu' : '' }}" href="{{url('admin/userdashboard/viewclient')}}/{{ $client->user_id }}">Overview</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.userdashboard.clientcases' ? 'active-menu' : '' }}" href="{{url('admin/userdashboard/clientcases')}}/{{ $client->user_id }}">Cases</a></li>
    </ul>
  </div>

<!--new-header Close -->

  <div class="section-body">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="back-btn-new">
            <a href="{{ url('admin/userdashboard') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-body">
            <div class="table-responsive table-invoice all-case-table new-case_table">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                    <!-- <th style="display: none;"> Id</th>
                    <th style="display: none;">Client Name </th> -->
                    <th>Client Name </th>
                    <th>Case Type</th>
                    <!-- <th>Case Cost</th> -->
                    <th>Attorney of Record</th>
                    <th>Assigned Paralegal</th>
                    <th>Court Date </th>
                    <th>Status</th>
                    <th style="min-width: 250px;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($cases)) {
                    foreach ($cases as $k => $case) { ?>
                      <tr>
                        <td>
                          <a href="{{url('admin/userdashboard/viewclient')}}/{{ $client->user_id }}">
                            {{$case->client_name}}
                          </a>
                        </td>
                        <td>{{$case->case_type}}</td>
                        <td>{{$case->user_name}}</td>
                        <td>
                          <?php 
                          if(!empty($case->paralegal_name)) {
                            echo $case->paralegal_name;
                          }
                          else {
                            echo 'N/A';
                          } 
                          ?>
                        </td>
                        <td>
                          <?php 
                          if(!empty($case->CourtDates)) {
                            echo $case->CourtDates;
                          }
                          else {
                            echo 'N/A';
                          } 
                          ?>
                        </td>
                        <td>{{GetCaseStatus($case->status)}}</td>
                        <td>
                          <a class="action_btn" href="{{url('admin/usertask/overview')}}/{{ $case->aid }}">
                            <img src="{{url('assets/images/icon')}}/Group 557.svg" />
                          </a>
                        </td>
                      </tr>
                    <?php } } ?>
                </tbody>
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

@endpush 