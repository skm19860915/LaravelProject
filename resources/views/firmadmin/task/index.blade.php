@extends('firmlayouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
  #table tbody tr td:nth-child(1) {
    display: none;
  }
  #table1 tbody tr td:nth-child(1) {
    display: none;
  }
  div#table_filter {
    display: block;
  }
</style>
@endpush  

@section('content')
<section class="section task-blade-table">
  <div class="section-header">
    <h1>Tasks</h1>
    <div class="section-header-breadcrumb">
      <?php if($firm->account_type == 'CMS') { ?>
      <a href="{{ url('firm/task/create') }}" class="btn btn-primary" style="width: auto; padding: 0 18px;">Create New Task</a>
      <?php } else { ?>
      <!-- <a href="{{ url('firm/task/create')}}" class="add-task-link">Upgrade to create a task</a> -->
      <?php } ?>
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
                    <a class="nav-link active" data-status="0" id="mytask-tab" data-toggle="tab" href="#mytask" role="tab" aria-controls="mytask" aria-selected="true">Task List</a>
                  </li>
                  <?php if($users->account_type == 'CMS') { ?>
                  <li class="nav-item">
                    <a class="nav-link" data-status="1" id="casetask-tab" data-toggle="tab" href="#casetask" role="tab" aria-controls="casetask" aria-selected="false">My Task</a>
                  </li> 
                  <?php } ?>
                </ul>
              </div> 
            <div class="task-tabcontent-box">
              <div class="tab-content" id="myTabContent">
              
                <div class="tab-pane fade show active" id="mytask" role="tabpanel" aria-labelledby="home-tab">
                 <div class="task-blade">
                  <div class="table-responsive table-invoice task-ng-table">
                    <div class="country-left-select" style="float: left;">
                      <select class="form-control">
                        <option value="0">Open Task</option> 
                        <option value="1">Complete Task</option>
                      </select>
                    </div>
                    <table class="table table table-bordered table-striped"  id="table" >
                      <thead>
                        <tr>
                         <th style="display: none;">Task ID</th>
                         <th>Task</th>
                         <th>Case Type </th>
                         <?php //if($users->account_type == 'CMS') { ?>
                         <th>Client Name</th>
                         <?php //} ?>
                         <th>Status</th>
                         <th>Created date </th>
                         <th>Action</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                 </div>  
                </div>
                <div class="tab-pane fade " id="casetask" role="tabpanel" aria-labelledby="home-tab">
                 <div class="task-blade">
                  <div class="table-responsive table-invoice task-table-width">
                    <div class="country-left-select" style="float: left;">
                      <select class="form-control">
                        <option value="0">Open Task</option> 
                        <option value="1">Complete Task</option>
                      </select>
                    </div>
                    <table class="table table table-bordered table-striped"  id="table1" >
                      <thead>
                        <tr>
                         <th style="display: none;">Task ID</th>
                         <th>Task</th>
                         <th>Case Type </th>
                         <th>Client Name</th>
                         
                         <th>Status</th>
                         <th>Created Date </th>
                         <th>Action</th>
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
      </div>
     </div>
  </div>
</section>
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
var status = 0;
gettabledata(status);
function gettabledata(status) {
  var index_url = "{{route('firm.task.getData')}}";
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
            }
    },
    "order": [[ 0, "desc" ]],
    columns: [
      { data: 'id', name: 'id'},
      { data: 'task', name: 'task'},
      { data: 'case_type', name: 'case_type'},
      <?php //if($users->account_type == 'CMS') { ?>
      // { data: 'client', name: 'client'},
      { data: null,
        render: function(data) {
            var cl = '<a href="'+data.clink+'">'+data.client+'</a>';
            return cl;
        },orderable: "false"
      },
      <?php //} ?>
      { data: 'status', name: 'status'},
      { data: 'created_at', name: 'created_at'},
      { data: null,
        render: function(data){

          var text = "'Are You Sure to delete this record?'";
          if(data.task_type == 'upload_translated_document'|| data.task_type == 'Required_Document_Request' || data.task_type == 'provide_a_quote' || data.task_type == 'Upload_Required_Document' || data.task_type == 'Document_Action') {
            var view_button = ' <a href="{{url('firm/case/case_documents')}}/'+data.case_id+'" class="btn btn-primary">Detail</a>';
          }
          else {
            if(data.task_type == 'Additional_Service') {
                var view_button = ' <a href="{{url('firm/case/additional_service/')}}/'+data.case_id+'" class="btn btn-primary">Detail</a>';
              }
             else {
                var view_button = ' <a href="{{url('firm/case/show')}}/'+data.case_id+'" class="btn btn-primary">Detail</a>';
                if(data.task_type == 'FIRM_TASK' || data.task_type == 'ADMIN_TASK') {
                  view_button = '';
                }
              }
            
          }
          return view_button;
        }, orderable: "false"
      },
    ],
    /*rowCallback: function(row, data) {
        $(row).attr('data-user_id', data['id']);
    }*/
  });
}

var status1 = 0;
gettable1data(status1);
function gettable1data(status1) {
  var index_url1 = "{{route('firm.task.getCasetask')}}";
  $('#table1').DataTable({
    processing: true,
    serverSide: true,
    destroy: true,
        "ajax": {
            "url": index_url1,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "status": status1,
            }
    },
    "order": [[ 0, "desc" ]],
    columns: [
      { data: 'id', name: 'id'},
      { data: 'task', name: 'task'},
      { data: 'case_type', name: 'case_type'},
      // { data: 'client', name: 'client'},
      { data: null,
        render: function(data) {
            var cl = '<a href="'+data.clink+'">'+data.client+'</a>';
            return cl;
        },orderable: "false"
      },
      { data: 'status', name: 'status'},
      { data: 'created_at', name: 'created_at'},
      { data: null,
        render: function(data){

          var text = "'Are You Sure to delete this record?'";
          if(data.task_type == 'upload_translated_document'|| data.task_type == 'Required_Document_Request' || data.task_type == 'provide_a_quote' || data.task_type == 'Upload_Required_Document' || data.task_type == 'Document_Action') {
            var view_button = ' <a href="{{url('firm/case/case_documents')}}/'+data.case_id+'" class="btn btn-primary">Detail</a>';
          }
          else {
            if(data.task_type == 'Additional_Service') {
                var view_button = ' <a href="{{url('firm/case/additional_service/')}}/'+data.case_id+'" class="btn btn-primary">Detail</a>';
              }
             else {
                var view_button = ' <a href="{{url('firm/case/show')}}/'+data.case_id+'" class="btn btn-primary">Detail</a>';
              }
            
          }
          return view_button;
        }, orderable: "false"
      },
    ],
    /*rowCallback: function(row, data) {
        $(row).attr('data-user_id', data['id']);
    }*/
  });
}

$(document).on('change', '#mytask select', function(){
   status = $(this).val();
   gettabledata(status)
});
$(document).on('change', '#casetask select', function(){
   status1 = $(this).val();
   gettable1data(status1)
})
//================ Edit user ============//

</script>

@endpush 
