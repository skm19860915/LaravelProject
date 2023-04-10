<aside id="sidebar-wrapper">
  <div class="sidebar-brand">
    <a href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="100" class="mb-5 mt-2" >
    </a>
  </div>
  <div class="sidebar-brand sidebar-brand-sm">
    <a href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="50" class="mb-5 mt-2" >
    </a>
  </div>
  <ul class="sidebar-menu">
      <li class="menu-header">Users</li>
      @include('messages.shared.users')
    </ul>
</aside>
