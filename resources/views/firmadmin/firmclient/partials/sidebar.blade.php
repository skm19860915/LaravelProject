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
    <li class="menu-header">DASHBOARD</li>
    <li class="{{ Request::route()->getName() == 'firm.clientdashboard' ? ' active' : '' }}"><a class="nav-link" href="{{ route('firm.clientdashboard') }}"><i class="fa fa-tint"></i> <span>Dashboard</span></a></li>

    <li class="{{ Request::route()->getName() == 'firm.clientcase' ? ' active' : '' }}">
      <a href="{{ route('firm.clientcase') }}" class="nav-link"><i class="fa fa-archive"></i> <span>Cases</span></a>
    </li>
    <li class="{{ Request::route()->getName() == 'firm.caseuser' ? ' active' : '' }}">
      <a href="{{ route('firm.caseuser') }}" class="nav-link"><i class="fa fa-archive"></i> <span>Users</span></a>
    </li>
    <li class="{{ Request::route()->getName() == 'firm.client_information' ? ' active' : '' }}">
      <a href="{{ route('firm.client_information') }}" class="nav-link"><i class="fa fa-archive"></i> <span>Client Questionnaire Form</span></a>
    </li>
    <li class=""><a class="nav-link" href="#"><i class="fa fa-comments"></i><span>Messages</span></a></li>
  </ul>
</aside>
