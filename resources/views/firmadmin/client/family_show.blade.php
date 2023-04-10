@extends('firmlayouts.admin-master')

@section('title')
Show client family
@endsection
@push('header_styles')
<style type="text/css">
  .family-header-address h3 {
    width: 75%;
  }
</style>
@endpush
@section('content')
<section class="section client-listing-details">

<!--new-header open-->

 @include('firmadmin.client.client_header')
    
<!--new-header Close-->

  
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/client/add_family') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/client') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
          
          <div class="profile-new-client">
           <h2>Family</h2>
           <a href="{{ url('firm/client/family/')}}/{{$client->id}}" class="add-task-link">Add a New Family Member</a>
           <div class="family-main-box">
            <div class="row">
            
            <?php foreach ($family_list as $key => $value) { ?>
             <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="family-main-border-box">
               <div class="family-header-address">
                <h3>{{$value->name}}</h3>
                <p>
                  <?php 
                    if (strpos($value->email, 'dummy') !== false) {
                        echo ' ';
                    }
                    else {
                      echo $value->email;
                    }
                  ?>
                </p>
                <!-- <a href="{{url('firm/client/edit_family')}}/{{$client->id}}/{{$value->uid}}?view=1" class="action_btn customedit_btn" title="View Member Details" style="right: 75px;"><img src="{{url('assets/images/icon')}}/Group 557.svg" /></a> -->
                <a href="{{url('firm/client/edit_family')}}/{{$client->id}}/{{$value->uid}}" class="action_btn customedit_btn" title="Edit Member" style="right: 75px;"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>
                <a href="{{url('firm/client/delete_family')}}/{{$client->id}}/{{$value->uid}}" class="action_btn customedit_btn" title="Delete Member" onclick="return window.confirm('Are you sure you want to delete this record?');"><img src="{{url('assets/images/icons')}}/case-icon3.svg" /></a>
               </div>
               <div class="family-info-text-general"><span>Gender</span> {{$value->gender}}</div>
               <div class="family-info-text-general"><span>Phone Number</span> {{$value->phon_number}}</div>
               <div class="family-info-text-general"><span>Date Of Birth</span> {{$value->dob}}</div>               
               <div class="family-info-text-general"><span>Relationship</span> {{$value->relationship}}</div>
               <div class="family-info-text-general"><span>Type</span> 
                 {{$value->type}}
               </div>
              </div>
             </div>
             
           <?php } ?>  
             
            </div>
           </div>
           
          </div>
            
           
          </div>
        </form>
      </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>
@endsection
