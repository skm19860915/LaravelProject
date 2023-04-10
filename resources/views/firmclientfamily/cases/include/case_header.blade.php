<!--new-header open-->
<div class="section-header">
  <h1><a href="#"><span>Case/</span></a> Detail</h1>
</div>
<div class="client-header-new">
  <div class="clent-profle-box">
    <?php if(!empty($client)) { ?>
    <div class="row">
      <div class="col-md-7">
        <div class="client-main-box-profile">
          <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
          <div class="client-right-text">
            <h3>
            <?php 
             echo $client->first_name;
             if(!empty($client->middle_name)) {
                echo " $client->middle_name";
             }
             if(!empty($client->last_name)) {
                echo " $client->last_name";
             }
             ?>
            </h3>
            <p>{{ $client->email }}<br />{{ $client->cell_phone }}<br />
        Since {{ date('M d, Y', strtotime($client->created_at)) }}</p>
          </div>  
        </div>    
      </div>
      <div class="col-md-5">
        <div class="client-right-profile">
          <div class="clent-info"><span>Client ID</span>:<span>#{{ $client->id }}</span></div>
          <div class="clent-info"><span>Case ID</span>:<span>#{{ $case->case_id }}</span></div>
          <div class="clent-info"><span>Case Type</span>:<span>{{ $case->case_type }}</span></div>
          <div class="clent-info"><span>Firm Name</span>:<span>{{ $firm->firm_name }}</span></div>
          <div class="clent-info"><span>Email Address</span>:<span> {{ $firm->email }}</span></div>
        </div>
      </div>
    </div>
    <?php } else { ?>
      <div class="row">
        <div class="col-md-6">
          <div class="client-right-profile">
            <div class="clent-info"><span>Case ID</span>:<span> #{{ $case->case_id }}</span></div>
            <div class="clent-info"><span>Case Type</span>:<span> {{ $case->case_type }}</span></div>      
          </div>    
        </div>
        <div class="col-md-6">
          <div class="client-right-profile">       
            <div class="clent-info"><span>Firm Name</span>:<span> {{ $firm->firm_name }}</span></div>
            <div class="clent-info"><span>Email Address</span>:<span> {{ $firm->email }}</span></div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
  <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'firm.clientfamily.show' ? 'active-menu' : '' }}" href="{{ url('firm/clientfamilydashboard/show') }}/{{ $case->case_id }}">Overview</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.clientfamily.familytask' ? 'active-menu' : '' }}" href="{{ url('firm/clientfamilydashboard/familytask') }}/{{ $case->case_id }}">Tasks</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.clientfamily.familydocuments' ? 'active-menu' : '' }}" href="{{ url('firm/clientfamilydashboard/familydocuments') }}/{{ $case->case_id }}">Documents</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.clientfamily.familynotes' ? 'active-menu' : '' }}" href="{{ url('firm/clientfamilydashboard/familynotes') }}/{{ $case->case_id }}">Notes</a></li>
    </ul> 
  </div>
</div>

<!--new-header Close-->