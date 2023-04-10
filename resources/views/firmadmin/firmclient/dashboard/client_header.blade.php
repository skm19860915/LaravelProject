<!--new-header open-->
<div class="section-header">
  <h1><a href="{{url('firm/clientcase')}}"><span>Case / </span></a> Detail</h1>
</div>
<div class="client-header-new">
  <div class="clent-profle-box">
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
  </div>
  <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'firm.show' ? 'active-menu' : '' }}" href="{{ url('firm/clientcase/show') }}/{{ $case->case_id }}">Overview</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.casefamily' ? 'active-menu' : '' }}" href="{{ url('firm/clientcase/casefamily') }}/{{ $case->case_id }}">Family</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.casetasks' ? 'active-menu' : '' }}" href="{{ url('firm/clientcase/casetasks') }}/{{ $case->case_id }}">Tasks</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.firmclient.document_requests' ? 'active-menu' : '' }}" href="{{ url('firm/firmclient/document_requests') }}/{{ $case->case_id }}">Documents</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.casenotes' ? 'active-menu' : '' }}" href="{{ url('firm/clientcase/casenotes') }}/{{ $case->case_id }}">Notes</a></li>  
      <li><a class="{{ Request::route()->getName() == 'firm.textmessage' ? 'active-menu' : '' }}" href="{{ url('firm/textmessage') }}/{{ $case->case_id }}">Inbox</a></li>   
    </ul>
  </div>
</div>

<!--new-header Close-->