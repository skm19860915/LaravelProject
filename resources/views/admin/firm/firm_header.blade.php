  <div class="client-header-new">
   <div class="clent-profle-box">
    <div class="row">
     <div class="col-md-8">
      <div class="client-main-box-profile">
      <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
      <div class="client-right-text">
       <div class="clent-info"><span>Firm Name</span>:<span style="width: auto;">{{$firm->firm_name}} <a href="{{url('admin/firm/firm_edit')}}/{{$firm->id}}" class="action_btn customedit_btn" title="Edit Client" data-toggle="tooltip" style="position: static;"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" style="width: 13px;" /></a>
       </span></div>
       <div class="clent-info"><span>Account Owner Name</span>:<span>{{ $firm->firm_admin_name }}</span></div>
       <div class="clent-info"><span>Account Email </span>:<span>{{ $firm->email }}</span></div>
       <div class="clent-info"><span>Joined Date </span>:<span>{{ date('M d, Y', strtotime($firm->created_at)) }}</span></div>
      </div>  
      </div>    
     </div>
     <div class="col-md-4">
      <div class="client-right-profile">
       <div class="clent-info"><span>Firm ID</span>:<span>#{{ $firm->id }}</span></div>
       <div class="clent-info"><span>Account Type</span>:<span>
         {{ $firm->account_type }}
       </span></div>
       <div class="clent-info"><span>Status</span>:<span><?php echo ($firm->status == 1) ? 'Active' : 'Inactive'; ?></span></div>
      </div>
     </div>
    </div>
   </div>
   <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'admin.firm.firm_details' ? 'active-menu' : '' }}" href="{{ url('admin/firm/firm_details') }}/{{ $firm->id }}">Overview</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.firm.firm_cases' ? 'active-menu' : '' }}" href="{{ url('admin/firm/firm_cases') }}/{{ $firm->id }}">Firm Cases</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.firm.firm_vpcases' ? 'active-menu' : '' }}" href="{{ url('admin/firm/firm_vpcases') }}/{{ $firm->id }}">VP Cases</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.firm.firm_users' ? 'active-menu' : '' }}" href="{{ url('admin/firm/firm_users') }}/{{ $firm->id }}">Firm Users</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.firm.firm_notes' ? 'active-menu' : '' }}" href="{{ url('admin/firm/firm_notes') }}/{{ $firm->id }}">Notes</a></li>
      <li><a class="{{ Request::route()->getName() == 'admin.firm.firm_billing' ? 'active-menu' : '' }}" href="{{ url('admin/firm/firm_billing') }}/{{ $firm->id }}">Billing</a></li>
    </ul>
   </div>
  </div>