<?php
$data = Auth::User();
$theme_logo = get_user_meta($data->id, 'theme_logo');
?>
<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}">
          <?php 
          if(!empty($theme_logo) && $theme_logo != '[]') { ?>
              <img src="{{asset('storage/app')}}/{{$theme_logo}}" alt="logo" width="100" class="mb-5 mt-2" >
          <?php } else { ?>
              <img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="100" class="mb-5 mt-2" >
          <?php } ?>
        </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('admin.dashboard') }}">
            <?php 
          if(!empty($theme_logo) && $theme_logo != '[]') { ?>
              <img src="{{asset('storage/app')}}/{{$theme_logo}}" alt="logo" width="100" class="mb-5 mt-2" >
          <?php } else { ?>
              <img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="100" class="mb-5 mt-2" >
          <?php } ?>
        </a>
    </div>
    <ul class="sidebar-menu">
        @if(Auth::user()->role_id == 1)
        <li class="{{ Request::route()->getName() == 'admin.dashboard' ? ' active' : '' }} dashboard_link"><a class="nav-link" href="{{ route('admin.dashboard') }}">
                <span class="show"><img src="{{url('assets/images/icon/dashboard@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/dashboard@2x_white.png')}}"/></span>
                <span>Dashboard</span></a></li>
        @endif

        @if(Auth::user()->role_id == 1)
        <li class="{{ Request::route()->getName() == 'admin.users' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.users') }}">
                <span class="show"><img src="{{url('assets/images/icon/user(6)@2x.png')}}" /></span>
                <span class="hide"><img src="{{url('assets/images/icon/user(6)1@2x_white.png')}}" /></span>
                <span>Team</span></a></li>
        @endif

        @if(Auth::user()->role_id == 1)
        <li class="{{ Request::route()->getName() == 'admin.firm' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.firm') }}">
                <span class="show"><img src="{{url('assets/images/icon/Group 15@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/Group 15@2x_white.png')}}"/></span><span>Firm</span></a></li>
        <li class="{{ Request::route()->getName() == 'admin.allcases' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.allcases') }}">
                <span class="show"><img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/portfolio(1)@2x_white.png')}}"/></span><span>Cases</span></a></li>

        <li class="{{ Request::route()->getName() == 'admin.calendar' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.calendar') }}">
                <span class="show"><img src="{{url('assets/images/icon/calendar(3)@2x.png')}}" /></span>
                <span class="hide"><img src="{{url('assets/images/icon/calendar(3).svg')}}" /></span>
                <span>Calendar</span></a></li>
        @endif

        @if(Auth::user()->role_id == 1)
        <!-- <li class="menu-header">Firm</li> -->
        <li class="{{ Request::route()->getName() == 'admin.task' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.task') }}">
                <span class="show"><img src="{{url('assets/images/icon/list@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/list@2x_white.png')}}"/></span>
                <span>Tasks</span></a></li>
        @endif

        @if(Auth::user()->role_id == 1)
        <!-- <li class="menu-header">Firm</li> -->
        <!-- <li class="{{ Request::route()->getName() == 'admin.helpfull_tips' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.helpfull_tips') }}"><i class="fa fa-tasks"></i> <span>Helpfull-Tips</span></a></li> -->
        @endif




        @if(Auth::user()->role_id == 1)
        <!-- <li class="menu-header">Firm</li> -->
        <li class="dropdown ">
            <a href="#" class="nav-link has-dropdown">
                <span class="show"><img src="{{url('assets/images/icon/stats@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/stats@2x_white.png')}}"/></span>
                <span>Reports</span></a>
            <ul class="dropdown-menu">          
                <li class=""><a class="nav-link" href="{{ route('admin.report.firmDetails') }}"><span>Firm details</span></a></li>
                <li class=""><a class="nav-link" href="{{ route('admin.report.firmUse') }}"><span>Firm Use</span></a></li>
                <li class=""><a class="nav-link" href="{{ route('admin.report.FinancialRP') }}"><span>Financial</span></a></li>
                <li class=""><a class="nav-link" href="{{ route('admin.report.FirmCaseReport') }}"><span>Firm Case Report</span></a></li>
                <li class=""><a class="nav-link" href="{{ route('admin.report.VaCaseReport') }}"><span>VP Case Report</span></a></li>

            </ul>
        </li>
        @endif


        @if(Auth::user()->role_id == 1)
        
        <li class="dropdown {{ Request::route()->getName() == 'admin.adminbilling' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('admin.adminbilling') }}">
                <span class="show"><img src="{{url('assets/images/icon/ticket@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/ticket@2x_white.png')}}"/></span>
                <span>Transactions</span>
            </a>
        </li>
        <li class="dropdown ">
            <a href="#" class="nav-link has-dropdown">
                <span class="show"><img src="{{url('assets/images/icon/gear(2)@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/gear(2)@2x_white.png')}}"/></span>
                <span>Setting</span></a>
            <ul class="dropdown-menu">
                @if(Auth::user()->id == 1)
                <li class=""><a class="nav-link" href="{{ route('admin.pdfform') }}"><span>PDF Forms</span></a></li>
                <li class=""><a class="nav-link" href="{{ route('admin.setting.casetypes') }}"><span>Pricing Defaults</span></a></li>
                @endif
                <li class=""><a class="nav-link" href="{{ route('admin.setting.email') }}"><span>Email Notifications</span></a></li>
                <!-- <li class=""><a class="nav-link" href="{{ route('admin.setting.appsetting') }}"><span>Integration</span></a></li> -->
                <li class="{{ Request::route()->getName() == 'messages.view_all_notification' ? ' active' : '' }}"><a class="nav-link" href="{{ url('messages/view_all_notification') }}"><span>Notification</span></a></li>
                <li class="{{ Request::route()->getName() == 'profile' ? ' active' : '' }}"><a class="nav-link" href="{{ url('profile') }}"><span>Profile</span></a></li>
            </ul>
        </li>
        @endif

        @if(Auth::user()->role_id == 1)
        <!-- <li class="{{ Request::route()->getName() == 'admin.chat.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.chat.index') }}">
          <span class="show"><img src="{{url('assets/images/icon/chat(1)@2x.png')}}" /></span>
          <span class="hide"><img src="{{url('assets/images/icon/chat(1)@2x_white.png')}}" /></span>
          <span>Chat</span></a></li> -->
        @endif


        @if(Auth::user()->role_id == 2)
        <li class="{{ Request::route()->getName() == 'admin.userdashboard' ? ' active' : '' }} dashboard_link"><a class="nav-link" href="{{ route('admin.userdashboard') }}">
                <span class="show"><img src="{{url('assets/images/icon/dashboard@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/dashboard@2x_white.png')}}"/></span><span>Dashboard</span></a></li>
        @endif



        @if(Auth::user()->role_id == 2)
        <li class="{{ Request::route()->getName() == 'admin.all_case' ? ' active' : '' }}">
            <a href="{{ route('admin.all_case') }}" class="nav-link">
                <span class="show"><img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/portfolio(1)@2x_white.png')}}"/></span>
                <span>My Cases</span></a>
        </li>
        <li class="{{ Request::route()->getName() == 'admin.new_assignments' ? ' active' : '' }}">
            <a href="{{ route('admin.new_assignments') }}" class="nav-link">
                <span class="show"><img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/portfolio(1)@2x_white.png')}}"/></span>
                <span>New Assignments</span></a>
        </li>
        @endif

        @if(Auth::user()->role_id == 2)
        <li class="{{ Request::route()->getName() == 'admin.usercalendar' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.usercalendar') }}">
            <span class="show"><img src="{{url('assets/images/icon/calendar(3)@2x.png')}}" /></span>
            <span class="hide"><img src="{{url('assets/images/icon/calendar(3).svg')}}" /></span>
            <span>Calendar</span></a></li>
        @endif

        @if(Auth::user()->role_id == 2)
        <!-- <li class="menu-header">Firm</li> -->
        <li class="{{ Request::route()->getName() == 'admin.usertask' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.usertask') }}">
                <span class="show"><img src="{{url('assets/images/icon/list@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/list@2x_white.png')}}"/></span>
                <span>Tasks</span></a></li>
        @endif

        @if(Auth::user()->role_id == 2)
        <!-- <li>
          <a class="nav-link" href="#"><i class="fa fa-columns"></i> <span>Money</span></a>
        </li> -->
        @endif

        @if(Auth::user()->role_id == 2)
        <!-- <li class="nav-item dropdown">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>HR</span></a>
          <ul class="dropdown-menu">
            <li><a class="nav-link" href="#">Paychecks and Statements</a></li>
            <li><a class="nav-link" href="#">Submit Issue</a></li>
            <li><a class="nav-link" href="#">Request W2 or 1099</a></li>
            <li><a class="nav-link" href="#">Shedule & TIme Off</a></li>
            <li><a class="nav-link" href="#">Forms & Policies</a></li>
            <li><a class="nav-link" href="#">Contact</a></li>
          </ul>
        </li> -->
        @endif

        @if(Auth::user()->role_id == 2)
        <!-- <li>
          <a class="nav-link" href="#"><i class="fa fa-columns"></i> <span>Helpful Tips</span></a>
        </li> -->
        @endif

        @if(Auth::user()->role_id == 2)
        <li class="dropdown ">
            <a href="#" class="nav-link has-dropdown">
                <span class="show"><img src="{{url('assets/images/icon/gear(2)@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/gear(2)@2x_white.png')}}"/></span>
                <span>Settings</span></a>
            <ul class="dropdown-menu">
                <!-- <li class=""><a class="nav-link" href="{{ route('admin.leave_application') }}"><span>Vacation Request</span></a></li> -->
                <li class="{{ Request::route()->getName() == 'messages.view_all_notification' ? ' active' : '' }}"><a class="nav-link" href="{{ url('messages/view_all_notification') }}"><span>Notification</span></a></li>
                <li class="{{ Request::route()->getName() == 'profile' ? ' active' : '' }}"><a class="nav-link" href="{{ url('profile') }}"><span>Profile</span></a></li>
            </ul>
        </li>
        @endif


        @if(Auth::user()->role_id == 2)
        <!-- <li class="menu-header">Firm</li> -->
        <!-- <li class="{{ Request::route()->getName() == 'admin.usertips' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.usertips') }}"><i class="fa fa-tasks"></i> <span>Helpfull-Tips</span></a></li> -->
        @endif

        @if(Auth::user()->role_id == 2)
        <!-- <li class="{{ Request::route()->getName() == 'admin.userchat.index' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.userchat.index') }}">
                <span class="show"><img src="{{url('assets/images/icon/chat(1)@2x.png')}}" /></span>
                <span class="hide"><img src="{{url('assets/images/icon/chat(1)@2x_white.png')}}" /></span>
                <span>Chat</span></a></li> -->
        @endif


        @if(Auth::user()->role_id == 3)
        <li class="{{ Request::route()->getName() == 'admin.supportdashboard' ? ' active' : '' }} dashboard_link"><a class="nav-link" href="{{ route('admin.supportdashboard') }}">
                <span class="show"><img src="{{url('assets/images/icon/dashboard@2x.png')}}"/></span>
                <span class="hide"><img src="{{url('assets/images/icon/dashboard@2x_white.png')}}"/></span>
                <span>Dashboard</span></a></li>
        @endif

        @if(Auth::user()->role_id == 3)
        <li class="{{ Request::route()->getName() == 'admin.allsupport' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.allsupport') }}"><i class="fa fa-columns"></i> <span>All ticket</span></a></li>
        @endif

        @if(Auth::user()->role_id == 3)
        <li class="{{ Request::route()->getName() == 'admin.mysupport' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin.mysupport') }}"><i class="fa fa-columns"></i> <span>My ticket</span></a></li>
        @endif
        <li class="">
            <a href="#" class="nav-link supportbtn">
              <span class="show"><img src="{{url('assets/images/icon/Union 8.svg')}}" /></span>
              <span class="hide"><img src="{{url('assets/images/icon/Union 8.svg')}}" /></span>
              <span>TILA Support</span>
            </a>
          </li>

    </ul>
</aside>
