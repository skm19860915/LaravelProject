<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ route('firm.admindashboard') }}">
            <img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="100" class="mb-5 mt-2" >
        </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="index.html">
            <img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="50" class="mb-5 mt-2" >
        </a>
    </div>
    <ul class="sidebar-menu">
        <li class="{{ Request::route()->getName() == 'firm.clientfamilydashboard' ? ' active' : '' }} dashboard_link"><a class="nav-link" href="{{ route('firm.clientfamilydashboard') }}">
            <span class="show"><img src="{{url('assets/images/icon/dashboard@2x.png')}}"/></span>
            <span class="hide"><img src="{{url('assets/images/icon/dashboard@2x_white.png')}}"/></span>
            <span>Dashboard</span></a>
        </li>
        <li class="{{ Request::route()->getName() == 'firm.clientfamily.familycases' ? ' active' : '' }}">
            <a href="{{ route('firm.clientfamily.familycases') }}" class="nav-link">
              <span class="show"><img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}"/></span>
              <span class="hide"><img src="{{url('assets/images/icon/portfolio(1)@2x_white.png')}}"/></span>
              <span>Cases</span></a>
          </li>
        <li class="dropdown {{ Request::route()->getName() == 'firm.setting.email' ? ' active' : '' }} {{ Request::route()->getName() == 'firm.setting.sms' ? ' active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
              <span class="show"><img src="{{url('assets/images/icon/gear(2)@2x.png')}}"/></span>
              <span class="hide"><img src="{{url('assets/images/icon/gear(2)@2x_white.png')}}"/></span>
              <span>Settings</span></a>
            <ul class="dropdown-menu">         
              <li class="{{ Request::route()->getName() == 'profile' ? ' active' : '' }}"><a class="nav-link" href="{{ url('profile') }}"><span>Profile</span></a></li>
              
            </ul>
          </li>



    </ul>
</aside>
