@extends('firmlayouts.admin-master')
@section('title')
Notification
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')

<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <h1>All Notification</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">

            </div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body"> 
                        <div class="profile-new-client">
                        	<div class="task-tabbtn-box">
				              <ul class="nav nav-tabs" id="myTab" role="tablist">
				               <li class="nav-item">
				                <a class="nav-link active" data-read="1" id="home-tab" data-toggle="tab" href="#myTabContent" role="tab" aria-controls="home" aria-selected="true">View All</a>
				               </li>
				               <li class="nav-item">
				                <a class="nav-link" data-read="0" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Unread Notification</a>
				               </li>              
				              </ul>
				             </div>
				            
                            <div class="table-responsive table-invoice all-case-table">
				                <table class="table table table-bordered table-striped"  id="table" >
				                  <thead>
				                    <tr>
				                      <th> Title</th>
				                      <th> Message</th>
				                      <!-- <th> User</th> -->
				                      <th> Create Date/Time </th>
				                      <th> Action</th>
				                    </tr>
				                  </thead>
				                </table>
				            </div>
		                           
		                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('footer_script')
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
	var r = 1;
	getNotifydata(r);
	function getNotifydata(r) {
	index_url = "{{url('messages/view_unread_notification')}}";
	$('#table').DataTable({
	  processing: true,
	  serverSide: true,
	  destroy: true,
	    "ajax": {
	        "url": index_url,
	        "type": "GET",
	        "data": {
	            _token: ' {{ csrf_token()}}',
	            "isread": r,
	        }
	  },
	  "order": [[ 2, "desc" ]],
	  columns: [
	    { data: 'title', name: 'title'},
	    { data: 'body', name: 'body'},
	    { data: 'created_at', name: 'created_at'},
	    { data: null,
	      render: function(data){
	          view_button = ' <a href="'+data.link+'" lass="action_btn" title="View" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/Group 557.svg" /></a>'; 
	        
	          return view_button;

	      }, orderable: "false"
	    },
	  ],
	});
	}
  $('#myTab .nav-link').on('click', function(e){
  	e.preventDefault();
    r = $(this).data('read');
    getNotifydata(r);
  });
});
</script>
@endpush 