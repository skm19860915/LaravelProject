<?php
$data = Auth::User();
$firm = DB::table('firms')->where('id', $data->firm_id)->first();
?>
  <div class="section-header">
    <h1>
      <a href="{{route('firm.client')}}"><span>Client / </span></a> 
      <a><span>
        <?php
         echo $client->first_name;
         if(!empty($client->middle_name)) {
            echo " $client->middle_name";
         }
         if(!empty($client->last_name)) {
            echo " $client->last_name";
         }
         ?> / 
      </span></a>
    Detail</h1>
    <div class="section-header-breadcrumb">
      <a href="" class="btn btn-primary addnotebtn" data-client_id="{{$client->id}}" style="width: auto; padding: 0 15px; margin-right: 15px;">Add Note</a>
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
    <div class="row">
     <div class="col-md-8">
      <div class="client-main-box-profile">
      <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
      <div class="client-right-text">
       <h3>
         <?php CalenderRedirectSessionSave();
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
       <p>
        <?php 
        if(strpos($client->email, 'dummy') !== false) { } else { ?>
        {{ $client->email }}<br/>
        <?php } ?>
        {{ $client->cell_phone }}<br />
        Create Date : {{ date('m/d/Y', strtotime($client->created_at)) }}</p>
        <!-- <p>Client Type : {{$client->type}}</p> -->
      </div>  
      </div>    
     </div>
     <div class="col-md-4">
      <div class="client-right-profile">
       <div class="clent-info"><span>Client Number</span>:<span>#{{ $client->id }}</span></div>
       
              <div class="clent-info"><span>Portal Access</span>:
        <span>
          <label class="custom-switch mt-2" style="padding-left: 0;">
            <input type="checkbox" name="is_portal_access" class="custom-switch-input is_portal_access_btn" value="1" <?php echo $retVal = ($client->is_portal_access == 1) ? "checked" : ""; ?> data-cid="{{$client->user_id}}">
            <span class="custom-switch-indicator" style="width: 48px;"></span>
            <span class="custom-switch-description"></span>
          </label>
        </div>
        <div class="clent-info" style="display: none;"><span>Deported</span>:
          <span>
            <label class="custom-switch mt-2" style="padding-left: 0;">
              <input type="checkbox" name="is_deported" class="custom-switch-input is_deported" value="1" <?php echo $retVal = ($client->is_deported == 1) ? "checked" : ""; ?>>
              <span class="custom-switch-indicator" style="width: 48px;"></span>
              <span class="custom-switch-description"></span>
            </label>
        </div>
        <div class="clent-info" style="display: none;"><span>Detained</span>:
          <span>
            <label class="custom-switch mt-2" style="padding-left: 0;">
              <input type="checkbox" name="is_detained" class="custom-switch-input is_detained" value="1" <?php echo $retVal = ($client->is_detained == 1) ? "checked" : ""; ?>>
              <span class="custom-switch-indicator" style="width: 48px;"></span>
              <span class="custom-switch-description"></span>
            </label>
         </div>


      </div>
     </div>
    </div>
   </div>
   <div class="client-menu-box">
   	  <ul>
	  	<li><a class="{{ Request::route()->getName() == 'firm.client.show' ? 'active-menu' : '' }}" href="{{url('firm/client/show')}}/{{ $client->id }}">Overview</a></li>
	  	<li><a class="{{ Request::route()->getName() == 'firm.client.profile' ? 'active-menu' : '' }}" href="{{url('firm/client/profile')}}/{{ $client->id }}">Questionnaire</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.client.client_case' ? 'active-menu' : '' }}" href="{{url('firm/client/client_case')}}/{{ $client->id }}">Cases</a></li>
	  	<li><a class="{{ Request::route()->getName() == 'firm.client.view_family' ? 'active-menu' : '' }}" href="{{url('firm/client/view_family')}}/{{ $client->id }}">Family</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.client.document' ? 'active-menu' : '' }}" href="{{url('firm/client/document')}}/{{ $client->id }}">Documents</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.client.view_notes' ? 'active-menu' : '' }}" href="{{url('firm/client/view_notes')}}/{{ $client->id }}">Notes</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.client.text_message' ? 'active-menu' : '' }}" href="{{url('firm/client/text_message')}}/{{ $client->user_id }}">Messages</a></li>
	  	<li><a class="{{ Request::route()->getName() == 'firm.client.client_task' ? 'active-menu' : '' }}" href="{{url('firm/client/client_task')}}/{{ $client->id }}">Tasks</a></li>
      <?php if($firm->account_type == 'CMS') { ?>
	  	<li><a class="{{ Request::route()->getName() == 'firm.client.client_event' ? 'active-menu' : '' }}" href="{{url('firm/client/client_event')}}/{{ $client->id }}">Events</a></li>
      <?php } else { ?>
        <!-- <li style="opacity: 0.5;"><a class="{{ Request::route()->getName() == 'firm.client.client_event' ? 'active-menu' : '' }}" href="{{url('firm/client/client_event')}}/{{ $client->id }}">Events</a></li> -->
      <?php } ?>
      <?php if($firm->account_type == 'CMS') { ?>
	  	<li><a class="{{ Request::route()->getName() == 'firm.client.client_billing' ? 'active-menu' : '' }} {{ Request::route()->getName() == 'firm.client.client_invoice' ? 'active-menu' : '' }} {{ Request::route()->getName() == 'firm.client.client_scheduled' ? 'active-menu' : '' }} {{ Request::route()->getName() == 'firm.client.client_acceptpayment' ? 'active-menu' : '' }} {{ Request::route()->getName() == 'firm.client.client_schedule_history' ? 'active-menu' : '' }} {{ Request::route()->getName() == 'firm.client.add_new_invoice' ? 'active-menu' : '' }}" href="{{url('firm/client/client_billing')}}/{{ $client->id }}">Billing</a></li>
      <?php } ?>
      <!-- <li><a class="{{ Request::route()->getName() == 'firm.forms' ? 'active-menu' : '' }}" href="{{ url('firm/forms') }}/{{ $client->id }}">Forms</a></li> -->
	  	<!-- <li><a href="javascript:void(0)" class="trigger--fire-modal-2" id="fire-modal-2">Add Notes</a></li>
	  	<li><a href="{{url('firm/client/client_files')}}/{{ $client->id }}">Client Files</a></li>
	  	<li><a href="{{ url('firm/forms') }}/{{ $client->id }}">Forms</a></li>
	  	<li><a href="{{url('firm/client/text_message')}}/{{ $client->user_id }}">Text Message</a></li>
	  	<li><a href="{{url('firm/client/client_document')}}/{{ $client->id }}">Client Document</a></li> -->     
	  </ul>
   </div>
  </div>

