<form class="form-inline mr-auto" method="get" action="{{ url('search') }}">
    <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
    </ul>
    <div class="search-element">
        <input class="form-control" name="search" id="searchname" type="search" placeholder="Search" aria-label="Search" data-width="250" value="{{ !empty($_GET['search']) ? $_GET['search'] : '' }}">
        <button class="btn" type="submit"><i class="fas fa-search"></i></button>  
    </div>
</form>
<!-- <form class="form-inline mr-auto">
<input class="form-control" name="search" id="searchname" type="search" placeholder="Search" aria-label="Search" data-width="250">
  
</form> -->

<div id="search-name-list"></div>
<ul class="navbar-nav navbar-right chatingBoxshowValue">
    <li class="">
        <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg supportbtn">
            <img src="{{url('assets/images/icon/Union 8.svg')}}" style="width:28px;" />
        </a>
    </li>
    <li   class="loaderes dropdown dropdown-list-toggle">
        <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
            <i class="fa fa-spin fa-spinner"></i>
        </a>
    </li>
           
    <li style="display:none;"  class="notitask dropdown dropdown-list-toggle">
        <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg{{ Auth::user()->unreadNotifications->count() ? ' beep' : '' }}">
            <img src="{{url('assets/images/icon/bell@2x.png')}}" />
        </a>
        <div class="dropdown-menu dropdown-list dropdown-menu-right">
            <div class="dropdown-header clearfix">
                <div class="float-left">
                    <a href="{{url('messages/view_all_notification')}}" style="display: block;line-height: 18px;">View All Notification</a>
                </div>
                <div class="float-right">
                    <a href="#" ng-click="isRead(1,<?php echo Auth::user()->id; ?>)">Mark all as read</a>
                </div>
            </div>
            <div class="dropdown-list-content dropdown-list-icons">
                @if(Auth::user()->unreadNotifications->count())
                @foreach(Auth::user()->unreadNotifications as $notification)
                <a href="#" class="dropdown-item dropdown-item-unread">
                    <div class="dropdown-item-icon bg-primary text-white">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        {{$notification->data['message']['body']}}
                    </div>
                </a>
                @endforeach
                @else
                <p class="text-muted p-2 text-center">No notifications found!</p>
                @endif
            </div>
    </li> 
    <li style="display:none;" class="notitextmsg dropdown dropdown-list-toggle">
        <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg{{ Auth::user()->unreadNotifications->count() ? ' beep' : '' }}">
            <img src="{{url('assets/images/icon/send@2x.png')}}" />
        </a>
        <div class="dropdown-menu dropdown-list dropdown-menu-right">
            <div class="dropdown-header">Notifications
                <div class="float-right">
                    <a  ng-click="isRead(2,<?php echo Auth::user()->id; ?>)">Mark all as read</a>
                </div>
            </div>
            <div class="dropdown-list-content dropdown-list-icons">
                @if(Auth::user()->unreadNotifications->count())
                @foreach(Auth::user()->unreadNotifications as $notification)
                <a href="#" class="dropdown-item dropdown-item-unread">
                    <div class="dropdown-item-icon bg-primary text-white">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        {{$notification->data['message']['body']}}
                    </div>
                </a>
                @endforeach
                @else
                <p class="text-muted p-2 text-center">No notifications found!</p>
                @endif
            </div>

    </li>
    <li   class="notitextchat" style="display: none;">
        <a  href="#"  class="nav-link notification-toggle nav-link-lg{{ Auth::user()->unreadNotifications->count() ? ' beep' : '' }}">
            <img src="{{url('assets/images/icon/chat(1)1@2x.png')}}" />
        </a>
        
   </li>
    
    <li class="dropdown">
        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <?php 
                $img = asset('storage/app').'/'.Auth::user()->avatar;
                if(empty(Auth::user()->avatar)) { 
                    $img = url('/assets/img/avatar/avatar-1.png');
                }
            ?>
            <img alt="image" src="{{ $img }}" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div></a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-title">Welcome, {{ Auth::user()->name }}</div>
            <!-- <a href="{{route('firm.profile')}}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile Settings
            </a> -->
            <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </li>
</ul>
