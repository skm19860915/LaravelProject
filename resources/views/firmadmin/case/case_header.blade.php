
<div class="section-header">
  <h1>
    <a href="{{url('firm/client')}}">
      <span>Client /</span>
    </a>
    <a href="{{url('firm/client/client_case')}}/{{$client->id}}">
      <span>
        <?php 
           echo $client->first_name;
           if(!empty($client->middle_name)) {
              echo " $client->middle_name";
           }
           if(!empty($client->last_name)) {
              echo " $client->last_name";
           }
           ?> /
         </span>
    </a>
    <a href="{{url('firm/case')}}">
      <span>Case /</span>
    </a> 
    <span>{{$case->id}}-{{$case->case_type}}</span>
    <span> / 
      {{ Request::route()->getName() == 'firm.case.show' ? 'Overview' : '' }}
      {{ Request::route()->getName() == 'firm.case.profile' ? 'Questionnaire' : '' }}
      {{ Request::route()->getName() == 'firm.case.case_family' ? 'Family' : '' }}
      {{ Request::route()->getName() == 'firm.case.add_case_interpreter' ? 'Add Interpreter' : '' }}
      {{ Request::route()->getName() == 'firm.case.case_forms' ? 'Forms' : '' }}
      {{ Request::route()->getName() == 'firm.case.add_forms' ? 'Add Forms' : '' }}
      {{ Request::route()->getName() == 'firm.case.case_documents' ? 'Documents' : '' }}
      {{ Request::route()->getName() == 'firm.case.upload_documents' ? 'Uploads Documents' : '' }}
      {{ Request::route()->getName() == 'firm.case.case_notes' ? 'Notes' : '' }}
      {{ Request::route()->getName() == 'firm.case.case_inbox' ? 'Messages' : '' }}
      {{ Request::route()->getName() == 'firm.case.case_tasks' ? 'Tasks' : '' }}
      {{ Request::route()->getName() == 'firm.case.add_case_tasks' ? 'Create Task' : '' }}
      {{ Request::route()->getName() == 'firm.case.additional_service' ? 'Additional Service' : '' }}
      {{ Request::route()->getName() == 'firm.case.affidavit' ? 'Affidavit /Declaration' : '' }}
    </span>
    <?php 
    if(Request::route()->getName() == 'firm.case.edit') { 
      //echo 'Edit'; 
    } 
    else { 
      //echo 'Detail'; 
    } ?>
  </h1>
  <div class="section-header-breadcrumb">
    <?php if($case->status == 6) { ?>
     <!-- <span>In Review&nbsp;&nbsp;</span> <a href="{{ url('firm/case/case_complete1') }}/{{$case->id}}" class="btn btn-primary" style="width: auto; padding: 0 15px;">Mark as Complete</a> -->
    <?php }
    if($case->status != 9 && empty($case->VP_Assistance)) { ?>
      <!-- <a href="{{ url('firm/case/case_complete1') }}/{{$case->id}}" class="btn btn-primary" style="width: auto; padding: 0 15px;">Mark as Complete</a> -->
    <?php
    }
    ?>
    <?php if($case->status == 9) { ?>
      <a href="#" class="btn btn-primary" style="width: auto; padding: 0 15px; margin-right: 15px;">Completed</a>
    <?php } ?>
    <a href="" class="btn btn-primary addcasenotebtn" data-case_id="{{$case->id}}" style="width: auto; padding: 0 15px; margin-right: 15px;">Add Note</a>
    <?php if($firm->account_type == 'CMS') { ?>
      <a href="" class="btn btn-primary sendmsgbtn" 
        data-to="{{$client->user_id}}" 
        data-name="{{$client->first_name}} {{$client->middle_name}} {{$client->last_name}}"
        data-phone_no="{{$client->cell_phone}}"
        data-email="{{$client->email}}" style="width: auto; padding: 0 15px;">Send Message</a>
      <?php } else { ?>
        <!-- <a href="{{url('firm/contacts')}}" class="btn btn-primary" style="width: auto; padding: 0 15px;">Send Message</a> -->
      <?php } ?>
    </div>
</div>
<div class="client-header-new">
  <div class="clent-profle-box">
    <?php if(!empty($client) && false) { ?>
    <div class="row">
      <div class="col-md-7">
        <div class="client-main-box-profile">
          <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
          <div class="client-right-text">
            <h3>
            <?php 
             echo $client->first_name;
             if(!empty($client->middle_name)) {
                echo " $client->middle_name";
             }
             if(!empty($client->last_name)) {
                echo " $client->last_name";
             }
             ?>
             <a href="{{url('firm/client/edit')}}/{{$client->id}}" class="action_btn customedit_btn" title="Edit Client" data-toggle="tooltip" style="position: static;"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" style="width: 13px;" /></a>
            </h3>
            <p>{{ $client->email }}<br />{{ $client->cell_phone }}<br />
        Since {{ date('M d, Y', strtotime($client->created_at)) }}</p>
          </div>  
        </div>    
      </div>
      <div class="col-md-5">
        <div class="client-right-profile">
          <div class="clent-info"><span>Case Complete</span>:
            <span>
              <label class="custom-switch mt-2" style="padding-left: 0;">
                <input type="checkbox" name="is_complete" class="custom-switch-input is_complete" value="1" <?php echo $retVal = ($case->status == 9) ? "checked" : ""; ?> data-status="{{$case->status}}" data-cid="{{$case->id}}" data-vp="{{$case->VP_Assistance}}">
                <span class="custom-switch-indicator" style="width: 55px;"></span>
                <span class="custom-switch-description"></span>
              </label>
          </div>
          <div class="clent-info"><span>Client ID</span>:<span>#{{ $client->id }}</span></div>
          <div class="clent-info"><span>Case ID</span>:<span>#{{ $case->id }}</span></div>
          <div class="clent-info"><span>Case Type</span>:<span>{{ $case->case_category }}</span></div>
          <div class="clent-info"><span>Case Category</span>:<span>{{ $case->case_type }}</span></div>
          <?php
            if($case->VP_Assistance == 1) {
              if(!empty($admintask)) {
             ?>
              <div class="clent-info"><span>VP Assigned</span>:<span> {{ $admintask->name }}</span></div>
              <div class="clent-info"><span>VP Email</span>:<span> {{ $admintask->email }}</span></div>
            <?php }
            else { ?>
              <div class="clent-info"><span>VP Assigned</span>:<span> N/A</span></div>
              <div class="clent-info"><span>VP Email</span>:<span> N/A</span></div>
            <?php } } ?>
        </div>
      </div>
    </div>
    <?php } else { ?>
      <div class="row">
        <div class="col-md-6">
          <div class="client-right-profile">
            <?php if(!empty($client)) { ?>
            <div class="clent-info"><span>Client Name</span>:<span>
              <a href="{{url('firm/client/client_case')}}/{{$client->id}}" class="" title="View Client" data-toggle="tooltip">
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
              </span>
            </div>
            <?php } ?>
            <div class="clent-info"><span>Case ID</span>:<span> #{{ $case->id }}</span></div>
            <div class="clent-info"><span>Case Type</span>:<span>{{ $case->case_category }}</span></div>
            <div class="clent-info"><span>Case Category</span>:<span>{{ $case->case_type }}</span></div>     
          </div>    
        </div>
        <div class="col-md-6">
          <div class="client-right-profile"> 
            <?php if($firm->account_type == 'CMS') { ?>
            <div class="clent-info"><span>Case Complete</span>:
              <span>
                <label class="custom-switch mt-2" style="padding-left: 0;">
                  <input type="checkbox" name="is_complete" class="custom-switch-input is_complete" value="1" <?php echo $retVal = ($case->status == 9) ? "checked" : ""; ?> data-status="{{$case->status}}" data-cid="{{$case->id}}" data-vp="{{$case->VP_Assistance}}">
                  <span class="custom-switch-indicator" style="width: 55px;"></span>
                  <span class="custom-switch-description"></span>
                </label>
            </div>      
            <?php }
            if($case->VP_Assistance == 1) {
              if(!empty($admintask)) {
             ?>
              <div class="clent-info"><span>VP Assigned</span>:<span> {{ $admintask->name }}</span></div>
              <div class="clent-info"><span>VP Email</span>:<span> {{ $admintask->email }}</span></div>
            <?php }
            else { ?>
              <div class="clent-info"><span>VP Assigned</span>:<span> N/A</span></div>
              <div class="clent-info"><span>VP Email</span>:<span> N/A</span></div>
            <?php } } ?>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
  <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'firm.case.show' ? 'active-menu' : '' }}" href="{{ url('firm/case/show') }}/{{ $case->id }}">Overview</a></li>
      <?php 
      //if($firm->account_type == 'CMS') { ?>
      <li><a class="{{ Request::route()->getName() == 'firm.case.profile' ? 'active-menu' : '' }}" href="{{url('firm/case/profile')}}/{{ $case->id }}">Questionnaire</a></li>
      <?php //} ?>
      <li><a class="{{ Request::route()->getName() == 'firm.case.case_family' ? 'active-menu' : '' }}" href="{{ url('firm/case/case_family') }}/{{ $case->id }}">Family</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.case.case_forms' ? 'active-menu' : '' }}" href="{{ url('firm/case/case_forms') }}/{{ $case->id }}">Forms</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.case.case_documents' ? 'active-menu' : '' }}" href="{{ url('firm/case/case_documents') }}/{{ $case->id }}">Documents</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.case.case_notes' ? 'active-menu' : '' }}" href="{{ url('firm/case/case_notes') }}/{{ $case->id }}">Notes</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.case.case_inbox' ? 'active-menu' : '' }}" href="{{ url('firm/case/case_inbox') }}/{{ $case->id }}">Messages</a></li>
      <!-- <li><a class="{{ Request::route()->getName() == 'firm.case.case_tasks' ? 'active-menu' : '' }}" href="{{ url('firm/case/case_tasks') }}/{{ $case->id }}">Tasks</a></li> -->
      <?php if($firm->account_type == 'CMS') { ?>
      <!-- <li><a class="{{ Request::route()->getName() == 'firm.case.case_event' ? 'active-menu' : '' }}" href="{{ url('firm/case/case_event') }}/{{ $case->id }}">Event</a></li> -->
      <?php } ?>
      
      
      
      <?php 
      if($firm->account_type == 'VP Services') { ?>
      <!-- <li><a class="{{ Request::route()->getName() == 'firm.case.case_inbox' ? 'active-menu' : '' }}" href="{{ url('firm/case/case_inbox') }}/{{ $case->id }}">Inbox</a></li> -->
      <?php } ?>
      <?php 
      if(IsCaseAdditionalService($case->case_category, $case->case_type)) { ?>
      <li><a class="{{ Request::route()->getName() == 'firm.case.additional_service' ? 'active-menu' : '' }}" href="{{ url('firm/case/additional_service') }}/{{ $case->id }}">Additional Service</a></li>
      <!-- <li><a class="{{ Request::route()->getName() == 'firm.case.affidavit' ? 'active-menu' : '' }}" href="{{ url('firm/case/affidavit') }}/{{ $case->id }}">Affidavit /Declaration</a></li> -->
      <?php } ?>
      <!-- <li>
        <?php $u= base64_encode(url('firm/case/case_family/'.$case->id)); ?>
        <a href="#" data-shortcode="282505ebbb" data-userid="{{Auth::User()->id}}" data-case_id="{{ $case->id }}" data-return="<?php echo $u; ?>" class="OpenQuestionsByType">Firm Questions</a>
      </li> -->
    </ul>
  </div>
</div>
<!--new-header Close