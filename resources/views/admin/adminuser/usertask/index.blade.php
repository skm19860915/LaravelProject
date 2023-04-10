@extends('layouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
  display: none;
}  
</style>
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Tasks</h1>
    <div class="section-header-breadcrumb">
      <a href="{{ url('admin/usertask/createtask') }}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> New Task</a>
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">

          <div class="card-body">
            <div class="task-tabbtn-box">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
               <li class="nav-item">
                <a class="nav-link active" data-status="0" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Open</a>
               </li>
               <li class="nav-item">
                <a class="nav-link" data-status="1" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Completed</a>
               </li>              
              </ul>
             </div>
             <div style="float: left; width: 230px; margin-top: 15px;" class="country-left-select due_date_wrapper">
                <select class="selectpicker" name="due_date" id="due_date">
                    <option value="">All</option>
                    <option value="today">Today</option>
                    <option value="15days">Due in next 15 days</option>
                    <option value="30days">Due in next 30 days</option> 
                </select>               
            </div>
            <div style="float: left; width: 230px; margin-top: 15px;" class="country-left-select">
                <select class="selectpicker" name="case_status" id="case_status">
                    <option value="">All</option>
                    <option value="Open">Open Case</option>
                    <option value="Working">Working Case</option>
                    <option value="InReview">In Review Case</option> 
                    <option value="Complete">Complete Case</option>
                    <option value="InComplete">In Complete Case</option>
                </select>               
            </div>
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> Id</th>
                   <th> Task</th>
                   <th> Description</th>
                   <th> Priority</th>
                   <th> Client Name</th>
                   <th> Created Date/Time</th>
                   <th> Due Date</th>
                   <th> Status</th>
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
</section>
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">

var index_url = "{{route('admin.usertask.getData')}}";

$(window).on('load', function() {
  var status = 0;
  var due_date = '';
  var case_status = '';
  gettabledata(status, due_date, case_status);
  function gettabledata(status, due_date, case_status) {
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
      "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "status": status,
                "due_date": due_date,
                "case_status": case_status
            }
      },
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'task', name: 'task'},
        { data: 'mytask', name: 'mytask'},
        { data: 'priority', name: 'priority'},
        // { data: 'clientname', name: 'clientname'},
        { data: null,
          render: function(data){
            var link = '<a href="'+data.clink+'" data-toggle="tooltip" title="View details">'+data.clientname+'</a>';
            return link;
            }, orderable: "false"
        },
        { data: 'created_at', name: 'created_at'},
        { data: 'due_date', name: 'due_date'},
        { data: 'stat', name: 'stat'},
        
        { data: null,
          render: function(data){
            var view_button = '';
            if(data.case_id) {
              if(data.task_type == 'upload_translated_document'|| data.task_type == 'Required_Document_Request' || data.task_type == 'provide_a_quote' || data.task_type == 'Upload_Required_Document' || data.task_type == 'Document_Action') {
              // if(data.task_type == 'Required_Document_Request') {
                  view_button = ' <a href="{{url('admin/usertask/documents/')}}/'+data.id+'" class="action_btn" title="Edit Task" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
              } 
              else {
                 if(data.task_type == 'Additional_Service') {
                    view_button = ' <a href="{{url('admin/usertask/additional_service/')}}/'+data.id+'" class="action_btn" title="Edit Task" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
                  }
                 else if(data.task_type != 'Leave_Application_ACT' && data.task_type != 'ADMIN_TASK') {
                    view_button = ' <a href="{{url('admin/usertask/overview/')}}/'+data.id+'" class="action_btn" title="Edit Task" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
                  }
              }
              
            }
              var time_button = ' <a href="{{url('admin/task/timeline')}}/'+data.case_id+'" class="btn btn-primary"><i class="fa fa-clock"></i></a>';
              var edit_button = '';

              if(data.task_type == 'ADMIN_TASK' && <?php echo Auth::User()->id; ?> == data.allotuserid) {
                edit_button = ' <a href="{{url('admin/usertask/edittask')}}/'+data.id+'" class="action_btn" title="Edit Task" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
              }
              return view_button + edit_button;

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
  }
  $('.nav-link').on('click', function(){
    status = $(this).data('status');
    due_date = $('select#due_date').val();
    case_status = $('select#case_status').val();
    if(status) {
      $('.due_date_wrapper').hide();
      due_date = '';
    }
    else {
      $('.due_date_wrapper').show();
    }
    gettabledata(status, due_date, case_status);
  });
  $('select#due_date').on('change', function(){
    status = $('.nav-link.active').data('status');
    due_date = $('select#due_date').val();
    case_status = $('select#case_status').val();
    gettabledata(status, due_date, case_status);
  });
  $('select#case_status').on('change', function(){
    status = $('.nav-link.active').data('status');
    due_date = $('select#due_date').val();
    case_status = $('select#case_status').val();
    gettabledata(status, due_date, case_status);
  });
});

//================ Edit user ============//

</script>

@endpush 
