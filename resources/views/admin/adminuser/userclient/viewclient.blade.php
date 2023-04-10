@extends('layouts.admin-master')

@section('title')
View Details
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
<section class="section client-listing-details">
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
            <div class="form-overview-new">
            
             <div class="overview-numbers">
             
              <div class="overview-border-number">
               <h3>{{$data['total_case']}}</h3>
               <p>Total Cases</p>
              </div>
              
              <div class="overview-border-number">
               <h3>{{$data['total_task']}}</h3>
               <p>Total Tasks</p>
              </div>
            
              <div class="overview-border-number">
               <h3>{{$data['totla_forms']}}</h3>
               <p>Total Forms</p>
              </div>

              <div class="overview-border-number">
               <h3>{{$data['total_document']}}</h3>
               <p>Total Documents</p>
              </div>
              
              <div class="overview-border-number">
               <h3>{{$data['total_event']}}</h3>
               <p>Total Events</p>
              </div>

              <div class="overview-border-number width-new-billing">
               <h3>$<?php echo number_format($data['total_billing'], 2); ?></h3>
               <p>Life Time Value</p>
              </div>
             </div>
             
             <div class="overview-table-box">
              <h3>Recent Tasks</h3>
              <div class="tabel-box-recent">
                <div class="table-responsive table-invoice">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <!-- <th style="display: none;">Task ID</th> -->
                          <th>Tasks</th>
                          <th>Due Date</th>
                          <th>Status</th>                          
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(!empty($task)) {
                            foreach ($task as $k => $v) { ?>
                           <tr>
                             <!-- <td><a href="#">#<?php echo $v->id; ?></a></td> -->
                             <td><?php echo $v->description; ?></td>
                             <td><?php echo $v->e_date; ?> <span class="text-gray"><?php echo $v->e_time; ?></span></td>
                             <td>
                              <?php 
                                if($v->status) {
                                  echo '<div class="Active">Completed</div>';
                                }
                                else {
                                  echo '<div class="Closed - Lost">Incompleted</div>';
                                }
                              ?>
                              
                            </td>
                           </tr>
                         <?php } } ?>
                      </tbody>
                    </table>
                  </div>
              </div>
             </div>
            </div>
            <div class="profile-new-client" style="display: none;">
              <h2>Profile</h2>
             <div class="profle-text-section">
              <div class="row">
              
               <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="profle-text-user">
                 <h4>General Information</h4>               
                 <div class="info-text-general"><span>First Name</span> {{ $client->first_name }}</div>
                 <div class="info-text-general"><span>Middle Name</span> {{ $client->middle_name }}</div>
                 <div class="info-text-general"><span>Last Name</span> {{ $client->last_name }}</div>
                 <div class="info-text-general"><span>Contact Email</span> {{ $client->mailing_address }}</div>
                 <div class="info-text-general"><span>Phone Number</span> {{ $client->cell_phone }}</div>
                 <div class="info-text-general"><span>Language</span> {{ $client->language }}</div>               
                 <div class="info-text-general"><span>Date Of Birth</span> {{ $client->dob }}</div>
                 <div class="info-text-general"><span>Gender</span>
                  {{$client->gender}}
                 </div>
                 <div class="info-text-general"><span>Portal Access</span> <?php echo $retVal = ($client->is_portal_access == 1) ? "YES" : "NO" ; ?></div>
                </div>
               </div>
               
               <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="profle-text-user address-user">
                 <h4>Address</h4>       
                 <?php $residence_address = json_decode($client->residence_address); ?>        
                 <div class="info-text-general"><span>Street Name</span>
                 <?php if(!empty($residence_address->address)) { echo $residence_address->address; } ?></div> 
                 <div class="info-text-general"><span>City Name</span>
                 <?php if(!empty($residence_address->city)) { echo getCityName($residence_address->city); } ?> </div> 
                 <div class="info-text-general"><span>State</span>
                 <?php if(!empty($residence_address->state)) { echo getStateName($residence_address->state); } ?> </div>
                 <div class="info-text-general"><span>Country</span>
                 <?php if(!empty($residence_address->country)) { echo getCountryName($residence_address->country); } ?> </div>              
                </div>
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
@push('footer_script')

@endpush 