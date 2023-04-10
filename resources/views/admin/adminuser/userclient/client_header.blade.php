<div class="section-header">
  <h1>
    <a href="{{ url('admin/userdashboard') }}"><span>Dashboard /</span></a>
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
      </span></a> Details</h1>
      <div class="section-header-breadcrumb">
        <a href="" class="btn btn-primary addcasenotebtn" data-related_id="{{$client->id}}" data-ntype="CLIENT" style="width: auto; padding: 0 15px; margin-right: 15px; margin-left: 15px;">Add Note</a>
        <a href="" class="btn btn-primary sendmsgbtn" 
        data-to="{{$client->user_id}}" 
        data-name="{{$client->first_name}} {{$client->middle_name}} {{$client->last_name}}"
        data-phone_no="{{$client->cell_phone}}"
        data-email="{{$client->email}}" style="width: auto; padding: 0 15px;">Send Message</a>
      </div>
</div>
<div class="client-header-new">
  <div class="clent-profle-box">
    <div class="row">
     <div class="col-md-8">
      <div class="client-main-box-profile">
      <?php 
          $img = asset('storage/app').'/'.$client->avatar;
          if(empty(Auth::user()->avatar)) { 
              $img = url('/assets/img/avatar/avatar-1.png');
          }
      ?>
      <div class="client-left-img" style="background-image: url({{ $img }});"></div>
      <div class="client-right-text">
        <div class="clent-info">
          <span>Full Name</span>:<span>{{ $client->name }}</span>
        </div>
        <div class="clent-info">
          <span>Email</span>:<span>{{ $client->email }}</span>
        </div>
        <div class="clent-info">
          <span>Phone No.</span>:<span>{{ $client->contact_number }}</span>
        </div>
        <div class="clent-info">
          <span>Role</span>:<span>
            Client
          </span>
        </div>
      </div>  
         
     </div>
     <div class="col-md-4">
      <div class="client-right-profile">
       


      </div>
     </div>
    </div>
  </div>
  </div>
  <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'admin.userclient.viewclient' ? 'active-menu' : '' }}" href="{{url('admin/userclient/viewclient')}}/{{ $client->user_id }}">Overview</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.userclient.profile' ? 'active-menu' : '' }}" href="{{url('admin/userclient/profile')}}/{{ $client->user_id }}">Questionnaire</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.userclient.clientcases' ? 'active-menu' : '' }}" href="{{url('admin/userclient/clientcases')}}/{{ $client->user_id }}">Cases</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.userclient.viewfamily' ? 'active-menu' : '' }}" href="{{url('admin/userclient/viewfamily')}}/{{ $client->user_id }}">Family</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.userclient.clientdocument' ? 'active-menu' : '' }}" href="{{url('admin/userclient/clientdocument')}}/{{ $client->user_id }}">Documents</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.userclient.viewnotes' ? 'active-menu' : '' }}" href="{{url('admin/userclient/viewnotes')}}/{{ $client->user_id }}">Notes</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.userclient.viewinbox' ? 'active-menu' : '' }}" href="{{url('admin/userclient/viewinbox')}}/{{ $client->user_id }}">Messages</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.userclient.clienttasks' ? 'active-menu' : '' }}" href="{{url('admin/userclient/clienttasks')}}/{{ $client->user_id }}">Tasks</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.userclient.clientsevents' ? 'active-menu' : '' }}" href="{{url('admin/userclient/clientsevents')}}/{{ $client->user_id }}">Events</a></li>
    </ul>
  </div>
</div>