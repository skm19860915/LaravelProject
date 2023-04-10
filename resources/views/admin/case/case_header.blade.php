<!-- new-header open--> 
<div class="section-header">
  <h1><a href="{{ url('admin/allcases') }}"><span>Case /</span></a> Detail</h1>
  <div class="section-header-breadcrumb">
    <?php if($case->status == 6) { ?>
     <!-- <span>In Review&nbsp;&nbsp;</span> <a href="{{ url('admin/allcases/case_complete1') }}/{{$case->id}}" class="btn btn-primary" style="width: auto; padding: 0 15px;">Mark as Complete</a> -->
    <?php }
    if($case->status != 9 && empty($case->VP_Assistance)) { ?>
      <!-- <a href="{{ url('admin/allcases/case_complete1') }}/{{$case->id}}" class="btn btn-primary" style="width: auto; padding: 0 15px;">Mark as Complete</a> -->
    <?php
    }
    ?>
    <?php if($case->status == 9) { ?>
      <!-- <a href="#" class="btn btn-primary" style="width: auto; padding: 0 15px;">Completed</a> -->
    <?php } ?>
    </div>
</div>
<div class="client-header-new">
  <div class="clent-profle-box">
      <div class="row">
        <div class="col-md-6">
          <div class="client-right-profile">
            <div class="clent-info"><span>Case ID</span>:<span> #{{ $case->id }}</span></div>
            <div class="clent-info"><span>Case Type</span>:<span style="width: 55%;">{{ $case->case_category }}</span></div>
            <div class="clent-info"><span>Case Category</span>:<span>{{ $case->case_type }}</span></div>      
          </div>    
        </div>
        <div class="col-md-6">
          <div class="client-right-profile"> 
            <div class="clent-info"><span>Case Complete</span>:
              <span>
                <label class="custom-switch mt-2" style="padding-left: 0;">
                  <input type="checkbox" name="is_complete" class="custom-switch-input is_complete" value="1" <?php echo $retVal = ($case->status == 9) ? "checked" : ""; ?> data-status="{{$case->status}}" data-cid="{{$case->id}}" data-vp="{{$case->VP_Assistance}}">
                  <span class="custom-switch-indicator" style="width: 55px;"></span>
                  <span class="custom-switch-description"></span>
                </label>
            </div>      
            <div class="clent-info"><span>Firm Name</span>:<span> {{ $firm->firm_name }}</span></div>
            <div class="clent-info"><span>Email Address</span>:<span> {{ $firm->email }}</span></div>
          </div>
        </div>
      </div>
  </div>
  <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'admin.allcases.show' ? 'active-menu' : '' }}" href="{{ url('admin/allcases/show') }}/{{ $case->id }}">Overview</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.allcases.profile' ? 'active-menu' : '' }}" href="{{ url('admin/allcases/profile') }}/{{ $case->id }}">Questionnaire</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.allcases.casefamily' ? 'active-menu' : '' }}" href="{{ url('admin/allcases/casefamily') }}/{{ $case->id }}">Family</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.allcases.caseforms' ? 'active-menu' : '' }}" href="{{ url('admin/allcases/caseforms') }}/{{ $case->id }}">Forms</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.allcases.casedocuments' ? 'active-menu' : '' }}" href="{{ url('admin/allcases/casedocuments') }}/{{ $case->id }}">Documents</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.allcases.casenotes' ? 'active-menu' : '' }}" href="{{ url('admin/allcases/casenotes') }}/{{ $case->id }}">Notes</a></li>
      <!-- <li><a class="{{ Request::route()->getName() == 'admin.allcases.casetask' ? 'active-menu' : '' }}" href="{{ url('admin/allcases/casetask') }}/{{ $case->id }}">Tasks</a></li> -->
      <?php 
      if($firm->account_type == 'CMS') { ?>
      <!-- <li><a class="{{ Request::route()->getName() == 'admin.allcases.caseevent' ? 'active-menu' : '' }}" href="{{ url('admin/allcases/caseevent') }}/{{ $case->id }}">Event</a></li> -->
      <?php } ?>
      
      
      
      <?php 
      if(IsCaseAdditionalService($case->case_category, $case->case_type)) { ?>
      <li><a class="{{ Request::route()->getName() == 'admin.allcases.additionalservice' ? 'active-menu' : '' }}" href="{{ url('admin/allcases/additionalservice') }}/{{ $case->id }}">Additional Service</a></li>
      <!-- <li><a class="{{ Request::route()->getName() == 'admin.allcases.affidavit' ? 'active-menu' : '' }}" href="{{ url('admin/allcases/affidavit') }}/{{ $case->id }}">Affidavit /Declaration</a></li> -->
      <?php } ?>
      <!-- <li>
        <?php $u= base64_encode(url('admin/allcases/casefamily/'.$case->id)); ?>
        <a href="#" data-shortcode="282505ebbb" data-userid="{{Auth::User()->id}}" data-case_id="{{ $case->id }}" data-return="<?php echo $u; ?>" class="OpenQuestionsByType">Firm Questions</a>
      </li>  -->
    </ul>
  </div>
</div>

<!--new-header Close