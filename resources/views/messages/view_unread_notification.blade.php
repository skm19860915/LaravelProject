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
        <h1>Unread Notification</h1>
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
				                  <tbody>
				                    <?php 
				                if(!empty($Notif)) {
				                  foreach ($Notif as $k => $v) { 
				                  	$v1 = json_decode($v->data)->message;
				                  	?>
				                    <tr>
				                      <td>{{$v1->title}}</td>
				                      <td>{{$v1->body}}</td>
				                      <!-- <td></td> -->
				                      <td>
				                        <?php echo date('Y-m-d ', strtotime($v->created_at)); ?>
				                        <span class="text-gray"><?php echo date('h:i A', strtotime($v->created_at)); ?></span>
				                      </td>
				                      <td>
				                        <?php 
				                      	$nlink = '#';
				                      	if(!empty($v1->link)) {
				                      		$nlink = $v1->link;
				                      	}
				                      	?>
				                        <a href="{{$nlink}}" class="action_btn" data-toggle="tooltip" title="View"><img src="{{ url('/') }}/assets/images/icon/Group 557.svg"></a>
				                      </td>
				                    </tr>
				                    <?php    
				                  }
				                }
				                ?>
				                  </tbody>
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
	  // "order": [[ 0, "desc" ]],
	  columns: [
	    { data: 'title', name: 'title'},
	    { data: 'body', name: 'body'},
	    { data: 'created_at', name: 'created_at'},
	    { data: null,
	      render: function(data){
	          view_button = ' <a href="" lass="action_btn" title="View" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/Group 557.svg" /></a>'; 
	        
	          return view_button;

	      }, orderable: "false"
	    },
	  ],
	});
	}
  $('.family_arr').on('change', function(){
    r = $(this).val();
    getNotifydata(r);
  });
});
</script>
@endpush 