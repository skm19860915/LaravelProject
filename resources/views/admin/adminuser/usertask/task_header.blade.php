<div class="section-header">
  <h1><a href="{{ url('admin/all_case') }}"><span>Case / </span></a> Detail</h1>
  <div class="section-header-breadcrumb">
    <?php if($case->status != 6 && $case->status != 9) { ?>
    <a href="{{ url('admin/usertask/readytoreview') }}/{{$admintask->id}}" class="btn btn-primary" style="width: 140px;">Ready To Review</a>
    <?php } ?>
    <?php if($case->status == 6) { ?>
    <a href="#" class="btn btn-primary" style="width: 140px;">In Review</a>
    <?php } ?>
    <?php if($case->status == 9) { ?>
      <a href="#" class="btn btn-primary" style="width: auto; padding: 0 15px;">Completed</a>
    <?php } ?>
    <a href="" class="btn btn-primary addcasenotebtn" data-related_id="{{$case->id}}" data-ntype="CASE" style="width: auto; padding: 0 15px; margin-right: 15px; margin-left: 15px;">Add Note</a>
    <a href="" class="btn btn-primary sendmsgbtn" 
        data-to="{{$client->user_id}}" 
        data-name="{{$client->first_name}} {{$client->middle_name}} {{$client->last_name}}"
        data-phone_no="{{$client->cell_phone}}"
        data-email="{{$client->email}}" style="width: auto; padding: 0 15px;">Send Message</a>
  </div>
</div>
<div class="client-header-new">
  <div class="clent-profle-box">
    <?php if(!empty($client)) { ?>
    <div class="row">
      <div class="col-md-7">
        <div class="client-main-box-profile">
          <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
          <div class="client-right-text">
            <a href="{{url('admin/userclient/clientcases')}}/{{$client->user_id}}" class="" title="View Client" data-toggle="tooltip">
            <?php 
             echo $client->first_name;
             if(!empty($client->middle_name)) {
                echo " $client->middle_name";
             }
             if(!empty($client->last_name)) {
                echo " $client->last_name";
             }
             ?>
            </a>
            <p>{{ $client->email }}<br />{{ $client->cell_phone }}<br />
        Join Date : {{ date('M d, Y', strtotime($client->created_at)) }}</p>
          </div>  
        </div>    
      </div>
      <div class="col-md-5">
        <div class="client-right-profile">
          <div class="clent-info"><span>Client ID</span>:<span>#{{ $client->id }}</span></div>
          <div class="clent-info"><span>Case ID</span>:<span>#{{ $case->id }}</span></div>
          <div class="clent-info"><span>Case Type</span>:<span style="width: 55%;">{{ $case->case_category }}</span></div>
          <div class="clent-info"><span>Case Category</span>:<span>{{ $case->case_type }}</span></div> 
          <div class="clent-info"><span>Firm Name</span>:<span>{{ $admintask->firm_name }}</span></div>
          <!-- <div class="clent-info"><span>Email Address</span>:<span> {{ $admintask->email }}</span></div> -->
        </div>
      </div>
    </div>
    
    <?php } else { ?>
      <div class="row">
        <div class="col-md-6">
          <div class="client-right-profile">
            <div class="clent-info"><span>Case ID</span>:<span> #{{ $case->id }}</span></div>
            <div class="clent-info"><span>Case Type</span>:<span> {{ $case->case_type }}</span></div>      
          </div>    
        </div>
        <div class="col-md-6">
          <div class="client-right-profile">       
            <div class="clent-info"><span>Firm Name</span>:<span> {{ $admintask->firm_name }}</span></div>
            <div class="clent-info"><span>Email Address</span>:<span> {{ $admintask->email }}</span></div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
  <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'admin.usertask.overview' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/overview') }}/{{ $admintask->id }}">Overview</a></li>
      <?php if(!empty($client) && false) { ?>
      
      <li><a class="{{ Request::route()->getName() == 'admin.usertask.family' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/family') }}/{{ $admintask->id }}">Family</a></li>
      <?php } ?>
      <li><a class="{{ Request::route()->getName() == 'admin.usertask.profile' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/profile') }}/{{ $admintask->id }}">Questionnaire</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.usertask.casefamily' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/casefamily') }}/{{ $admintask->id }}">Family</a></li>
      <!-- <li><a class="{{ Request::route()->getName() == 'admin.usertask.tasks' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/tasks') }}/{{ $admintask->id }}">Tasks</a></li> -->
      <li><a class="{{ Request::route()->getName() == 'admin.usertask.documents' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/documents') }}/{{ $admintask->id }}">Documents</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.usertask.caseforms' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/caseforms') }}/{{ $admintask->id }}">Forms</a></li>  
      <li><a class="{{ Request::route()->getName() == 'admin.usertask.notes' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/notes') }}/{{ $admintask->id }}">Notes</a></li>
      <?php 
      //if($admintask->account_type == 'VP Services') { ?>
      <li><a class="{{ Request::route()->getName() == 'admin.usertask.caseinbox' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/caseinbox') }}/{{ $admintask->id }}">Messages</a></li>
      <?php // } ?> 
      <?php 
      if(IsCaseAdditionalService($case->case_category, $case->case_type)) { ?>
      <li><a class="{{ Request::route()->getName() == 'admin.usertask.additional_service' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/additional_service') }}/{{ $admintask->id }}">Additional Service</a></li>
      <!-- <li><a class="{{ Request::route()->getName() == 'admin.usertask.case_affidavit' ? 'active-menu' : '' }}" href="{{ url('admin/usertask/case_affidavit') }}/{{ $admintask->id }}">Affidavit /Declaration</a></li>      -->
      <?php } ?> 
      <!-- <li>
        <?php $u= base64_encode(url('usertask/casefamily/'.$admintask->id)); ?>
        <a href="#" data-shortcode="282505ebbb" data-userid="{{Auth::User()->id}}" data-case_id="{{ $case->id }}" data-return="<?php echo $u; ?>" class="OpenQuestionsByType">Firm Questions</a>
      </li> -->
    </ul>
  </div>
</div>

<!--new-header Close