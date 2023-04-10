<?php
$data = Auth::User();
$firm = DB::table('firms')->where('id', $data->firm_id)->first();
$firmadmin = DB::table('users')->where('firm_id', $data->firm_id)->where('role_id', 4)->first();
$theme_logo = get_user_meta($firmadmin->id, 'theme_logo');
?>
<aside id="sidebar-wrapper">
  <div class="sidebar-brand">
    <a href="{{ route('firm.admindashboard') }}">
      <?php 
      if(!empty($theme_logo) && $theme_logo != '[]') { ?>
          <img src="{{asset('storage/app')}}/{{$theme_logo}}" alt="logo" width="100" class="mb-5 mt-2" >
      <?php } else { ?>
          <img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="100" class="mb-5 mt-2" >
      <?php } ?>
    </a>
  </div>
  <div class="sidebar-brand sidebar-brand-sm">
    <a href="{{ route('firm.admindashboard') }}">
      <?php 
      if(!empty($theme_logo) && $theme_logo != '[]') { ?>
          <img src="{{asset('storage/app')}}/{{$theme_logo}}" alt="logo" width="100" class="mb-5 mt-2" >
      <?php } else { ?>
          <img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="100" class="mb-5 mt-2" >
      <?php } ?>
    </a>
  </div>
  <ul class="sidebar-menu">
      @if(Auth::user()->role_id == 4 || Auth::user()->role_id == 5)
      <li class="{{ Request::route()->getName() == 'firm.admindashboard' ? ' active' : '' }} dashboard_link"><a class="nav-link" href="{{ route('firm.admindashboard') }}">
        <span class="show"><img src="{{url('assets/images/icon/dashboard@2x.png')}}"/></span>
        <span class="hide"><img src="{{url('assets/images/icon/dashboard@2x_white.png')}}"/></span>
        <span>Dashboard</span></a></li>
      
      <li class="menu-header">CASES</li>
       
      <li class="<?php if($firm->account_type == 'VP Services') { echo 'disabled'; } ?> {{ Request::route()->getName() == 'firm.lead' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.lead') }}">
        <span class="show"><img src="{{url('assets/images/icon/useradd@2x.png')}}"/></span>
        <span class="hide"><img src="{{url('assets/images/icon/user(6)@2x_white.png')}}"/></span>
        <span>Leads</span></a></li>
      <li class="{{ Request::route()->getName() == 'firm.client' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.client') }}">
        <span class="show"><img src="{{url('assets/images/icon/Group 15@2x.png')}}"/></span>
        <span class="hide"><img src="{{url('assets/images/icon/Group 15@2x_white.png')}}"/></span>
        <span>Clients</span></a>
      </li>
      <?php
      
      if($firm->account_type != 'CMS' && false) {
      ?>
      <li class="dropdown {{ Request::route()->getName() == 'firm.case.create' ? ' active' : '' }}">
        <a href="{{ route('firm.case.create') }}" class="nav-link">
          <span class="show">
            <i class="fa fa-plus" style="margin-right: 0;"></i>
          </span>
          <span class="hide">
            <i class="fa fa-plus" style="margin-right: 0; margin-top: 8px; color: #fff;"></i>
          </span>
          <span>New Case</span></a>
      </li>
      <?php } ?>
      <li class="dropdown {{ Request::route()->getName() == 'firm.case' ? ' active' : '' }}">
        <a href="{{ route('firm.case') }}" class="nav-link">
          <span class="show"><img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}"/></span>
          <span class="hide"><img src="{{url('assets/images/icon/portfolio(1)@2x_white.png')}}"/></span>
          <span>Cases</span></a>
      </li>


      <li class="{{ Request::route()->getName() == 'firm.task' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.task') }}">
        <span class="show"><img src="{{url('assets/images/icon/list@2x.png')}}"/></span>
        <span class="hide"><img src="{{url('assets/images/icon/list@2x_white.png')}}"/></span>
        <span>Tasks</span></a></li>
      
      <?php //if($firm->account_type == 'CMS') { ?>
      <li class="<?php if($firm->account_type == 'VP Services') { echo 'disabled'; } ?> {{ Request::route()->getName() == 'firm.calendar' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.calendar') }}">
        <span class="show"><img src="{{url('assets/images/icon/calendar(3)@2x.png')}}" /></span>
        <span class="hide"><img src="{{url('assets/images/icon/calendar(3).svg')}}" /></span>
        <span>Firm Calendar</span></a>
      </li>
      
      <li class="<?php if($firm->account_type == 'VP Services') { echo 'disabled'; } ?> {{ Request::route()->getName() == 'firm.contacts' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.contacts') }}">
        <span class="show"><img src="{{url('assets/images/icon/book(3)@2x.png')}}" /></span>
        <span class="hide"><img src="{{url('assets/images/icon/book(3)@2x_white.png')}}" /></span>
        <span>Send Messages</span></a>
      </li>

      <li class="{{ Request::route()->getName() == 'firm.users' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.users') }}">
        <span class="show"><img src="{{url('assets/images/icon/user(6)@2x.png')}}" /></span>
        <span class="hide"><img src="{{url('assets/images/icon/user(6)1@2x_white.png')}}" /></span>
        <span>Users</span></a>
      </li>

      <?php if($firm->account_type == 'CMS') { ?>
      <li class="dropdown">
        <a href="#" class="nav-link has-dropdown">
          <span class="show"><img src="{{url('assets/images/icon/stats@2x.png')}}"/></span>
          <span class="hide"><img src="{{url('assets/images/icon/stats@2x_white.png')}}"/></span>
          <span>Reports</span></a>
        <ul class="dropdown-menu">
          <li class=""><a class="nav-link" href="{{route('firm.report.deadline_report')}}"><span>Deadline</span></a></li>
          <li class=""><a class="nav-link" href="{{route('firm.report.expirationDates_report')}}"><span>Expiration Dates</span></a></li>
          <li class=""><a class="nav-link" href="{{route('firm.report.openedCases_report')}}"><span>Opened Cases</span></a></li>
          <li class=""><a class="nav-link" href="{{route('firm.report.closedCases_report')}}"><span>Closed Cases</span></a></li>
          <li class=""><a class="nav-link" href="{{route('firm.report.courtDates_report')}}"><span>Court Dates</span></a></li>
          <li class=""><a class="nav-link" href="{{route('firm.report.nationality_report')}}"><span>Nationality</span></a></li>
          <li class=""><a class="nav-link" href="{{route('firm.report.submittedCases_report')}}"><span>Submitted cases</span></a></li>
          <li style="display:none;"><a class="nav-link" href="{{route('firm.report.nextStageCase_report')}}"><span>Next Stage Case</span></a></li>
          <li class=""><a class="nav-link" href="{{route('firm.report.incompleteCases_report')}}"><span>Incomplete Cases</span></a></li>
          <li class=""><a class="nav-link" href="{{route('firm.report.leads_report')}}"><span>Leads</span></a></li>
        </ul>
      </li>
      <?php } else { ?>
      <li class="disabled">
        <a href="{{route('firm.report.deadline_report')}}" class="nav-link">
          <span class="show"><img src="{{url('assets/images/icon/stats@2x.png')}}"/></span>
          <span class="hide"><img src="{{url('assets/images/icon/stats@2x_white.png')}}"/></span>
          <span>Reports</span></a>
      </li>
      <?php } ?>
      @if(Auth::user()->role_id == 4)
      <li class="{{ Request::route()->getName() == 'firm.transactions' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.billing' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.billing.invoice' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.billing.scheduled' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.billing.acceptpayment' ? ' active' : '' }}">
        <a href="{{ route('firm.transactions') }}" class="nav-link">
          <span class="show"><img src="{{url('assets/images/icon/ticket@2x.png')}}"/></span>
          <span class="hide"><img src="{{url('assets/images/icon/ticket@2x_white.png')}}"/></span>
          <span>Billing</span></a>
      </li>
      @endif
      <?php //} ?>

      <li class="dropdown {{ Request::route()->getName() == 'firm.setting.email' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.setting.sms' ? ' active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
          <span class="show"><img src="{{url('assets/images/icon/gear(2)@2x.png')}}"/></span>
          <span class="hide"><img src="{{url('assets/images/icon/gear(2)@2x_white.png')}}"/></span>
          <span>Settings</span></a>
        <ul class="dropdown-menu">
          <?php if($firm->account_type == 'CMS') { ?>
            @if(Auth::user()->role_id == 4)
          <li class="{{ Request::route()->getName() == 'firm.setting.sms' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.setting.sms') }}"><span>Sms Notifications</span></a></li>

          <li class="{{ Request::route()->getName() == 'firm.setting.email' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.setting.email') }}"><span>Email Notifications</span></a></li>
          @endif
         <?php } ?>
         <li class="{{ Request::route()->getName() == 'messages.view_all_notification' ? ' active' : '' }}"><a class="nav-link" href="{{ url('messages/view_all_notification') }}"><span>Notification</span></a></li>
          <li class="{{ Request::route()->getName() == 'profile' ? ' active' : '' }}"><a class="nav-link" href="{{ url('profile') }}"><span>Profile</span></a></li>
        </ul>
      </li>
      @endif
      @if(Auth::user()->role_id == 5 && false)
      <li class="{{ Request::route()->getName() == 'firm.firmuserdashboard' ? ' active' : '' }} dashboard_link"><a class="nav-link" href="{{ route('firm.firmuserdashboard') }}">
        <span class="show"><img src="{{url('assets/images/icon/dashboard@2x.png')}}"/></span>
        <span class="hide"><img src="{{url('assets/images/icon/dashboard@2x_white.png')}}"/></span>
        <span>Dashboard</span></a></li>

      <li class="menu-header">CASES</li>

      <li class="{{ Request::route()->getName() == 'firm.firmclients' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.firmclients') }}">
        <span class="show"><img src="{{url('assets/images/icon/Group 15@2x.png')}}"/></span>
        <span class="hide"><img src="{{url('assets/images/icon/Group 15@2x_white.png')}}"/></span>
        <span>Client</span></a>
      </li>

      <li class="dropdown {{ Request::route()->getName() == 'firm.usercase.mycase' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.usercase.allcase' ? ' active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
          <span class="show"><img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}"/></span>
          <span class="hide"><img src="{{url('assets/images/icon/portfolio(1)@2x_white.png')}}"/></span>
          <span>Cases</span></a>
        <ul class="dropdown-menu">
          <li class="{{ Request::route()->getName() == 'firm.usercase.allcase' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.usercase.allcase') }}"><span>All Cases</span></a></li>
        </ul>
      </li>


      <li class="{{ Request::route()->getName() == 'firm.usertask' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.usertask') }}">
        <span class="show"><img src="{{url('assets/images/icon/list@2x.png')}}"/></span>
        <span class="hide"><img src="{{url('assets/images/icon/list@2x_white.png')}}"/></span>
        <span>Tasks</span></a></li>
  
      <li class="{{ Request::route()->getName() == 'firm.firmcontacts' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.firmcontacts') }}">
        <span class="show"><img src="{{url('assets/images/icon/book(3)@2x.png')}}" /></span>
        <span class="hide"><img src="{{url('assets/images/icon/book(3)@2x_white.png')}}" /></span>
        <span>Send Message</span></a></li>

      <li class="dropdown {{ Request::route()->getName() == 'firm.setting.email' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.setting.sms' ? ' active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
          <span class="show"><img src="{{url('assets/images/icon/gear(2)@2x.png')}}"/></span>
          <span class="hide"><img src="{{url('assets/images/icon/gear(2)@2x_white.png')}}"/></span>
          <span>Settings</span></a>
        <ul class="dropdown-menu">     
          <li class="{{ Request::route()->getName() == 'messages.view_all_notification' ? ' active' : '' }}"><a class="nav-link" href="{{ url('messages/view_all_notification') }}"><span>Notification</span></a></li>    
          <li class="{{ Request::route()->getName() == 'profile' ? ' active' : '' }}"><a class="nav-link" href="{{ url('profile') }}"><span>Profile</span></a></li>
        </ul>
      </li> 
      @endif     

      @if(Auth::user()->role_id == 6)
      <li class="{{ Request::route()->getName() == 'firm.clientdashboard' ? ' active' : '' }} dashboard_link"><a class="nav-link" href="{{ route('firm.clientdashboard') }}">
        <span class="show"><img src="{{url('assets/images/icon/dashboard@2x.png')}}"/></span>
        <span class="hide"><img src="{{url('assets/images/icon/dashboard@2x_white.png')}}"/></span>
        <span>Home</span></a>
      </li>
      <li class="{{ Request::route()->getName() == 'firm.mymessages' ? ' active' : '' }}">
        <a href="{{ route('firm.mymessages') }}" class="nav-link">
          <span class="show"><img src="{{url('assets/images/icon/chat(1)@2x.png')}}"/></span>
          <span class="hide"><img src="{{url('assets/images/icon/chat(1)1@2x.png')}}"/></span>
          <span>My Messages</span>
        </a>
      </li>
      <li class="dropdown {{ Request::route()->getName() == 'firm.clientcase' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.clientcase.questionnaire' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.clientcase.case_documents' ? ' active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
          <span class="show"><img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}"/></span>
          <span class="hide"><img src="{{url('assets/images/icon/portfolio(1)@2x_white.png')}}"/></span>
          <span>My Cases</span>
        </a>
        <ul class="dropdown-menu">   
          <li style="padding-left: 25px;"><span>{{Auth::User()->name}} (Me)</span></li>      
          <li><a class="nav-link" href="{{ url('firm/clientcase/questionnaire') }}"><span>Questionnaire</span></a></li>
          <li><a class="nav-link" href="{{ url('firm/clientcase/case_documents') }}"><span>Documents</span></a></li>
        </ul>
      </li>
      <!-- <li class="{{ Request::route()->getName() == 'firm.mybalance' ? ' active' : '' }}">
        <a href="{{ route('firm.mybalance') }}" class="nav-link">
          <span class="show"><img src="{{url('assets/images/icon/ticket@2x.png')}}" /></span>
          <span class="hide"><img src="{{url('assets/images/icon/ticket@2x_white.png')}}" /></span>
          <span>My Balance</span>
        </a>
      </li> -->
      <!-- <li class="{{ Request::route()->getName() == 'firm.firmclient.billing.invoice' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.firmclient.billing.invoice') }}">
        <span class="show"><img src="{{url('assets/images/icon/ticket@2x.png')}}" /></span>
        <span class="hide"><img src="{{url('assets/images/icon/ticket@2x_white.png')}}" /></span>
        <span>Invoice</span></a></li> -->
      <li class="dropdown {{ Request::route()->getName() == 'firm.setting.email' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.setting.sms' ? ' active' : '' }}">
          <a href="#" class="nav-link has-dropdown">
            <span class="show"><img src="{{url('assets/images/icon/gear(2)@2x.png')}}"/></span>
            <span class="hide"><img src="{{url('assets/images/icon/gear(2)@2x_white.png')}}"/></span>
            <span>Settings</span></a>
          <ul class="dropdown-menu">         
            <!-- <li class="{{ Request::route()->getName() == 'messages.view_all_notification' ? ' active' : '' }}"><a class="nav-link" href="{{ url('messages/view_all_notification') }}"><span>Notification</span></a></li> -->
            <li class="{{ Request::route()->getName() == 'profile' ? ' active' : '' }}"><a class="nav-link" href="{{ url('profile') }}"><span>Profile</span></a></li>
          </ul>
        </li> 
      @endif
      <?php if($firm->account_type == 'VP Services' && Auth::user()->role_id == 4) { ?>
        <!-- <li class="{{ Request::route()->getName() == 'firm.upgradetocms' ? ' active' : '' }}">
          <a href="{{ route('firm.upgradetocms') }}" class="nav-link">
            <span class="show"><img src="{{url('assets/images/icon/ticket@2x.png')}}" /></span>
            <span class="hide"><img src="{{url('assets/images/icon/ticket@2x_white.png')}}" /></span>
            <span>Upgrade to CMS</span>
          </a>
        </li> -->
      <?php } ?>
      <li class="">
        <a href="#" class="nav-link supportbtn">
          <span class="show"><img src="{{url('assets/images/icon/Union 8.svg')}}" /></span>
          <span class="hide"><img src="{{url('assets/images/icon/Union 8.svg')}}" /></span>
          <span>TILA Support</span>
        </a>
      </li>
    </ul>
</aside>

