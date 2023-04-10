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
  <!--new-header open-->
  @include('admin.adminuser.userclient.client_header')
  <!--new-header Close-->
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
                    <!-- <th>Client Name </th> -->
                    <th>Case Type</th>
                    <th>Case Category</th>
                    <th>Attorney of Record</th>
                    <th>Assigned Paralegal</th>
                    <!-- <th>Court Date </th> -->
                    <th>Status</th>
                    <th style="min-width: 250px;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($cases)) {
                    foreach ($cases as $k => $case) { ?>
                      <tr>
                        <!-- <td>
                          <a href="{{url('admin/userdashboard/viewclient')}}/{{ $client->user_id }}">
                            {{$case->client_name}}
                          </a>
                        </td> -->
                        <td>{{$case->case_category}}</td>
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
                        <!-- <td>
                          <?php 
                          if(!empty($case->CourtDates)) {
                            echo $case->CourtDates;
                          }
                          else {
                            echo 'N/A';
                          } 
                          ?>
                        </td> -->
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