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
    <h1><a href="{{ url('admin/dashboard') }}"><span>Dashboard /</span></a> Manage Task</h1>
    <div class="section-header-breadcrumb">
      <a href="{{ url('admin/task/create') }}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> New Task</a>
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
                <a class="nav-link active" data-status="0" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Open Task</a>
               </li>
               <li class="nav-item">
                <a class="nav-link" data-status="1" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Complete Task</a>
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
                <select class="selectpicker vpusers" style="width: 220px; display: inline-block;" data-live-search="true" id="vpusers">
                <option value="">All</option>
                <?php 
                if(!empty($vpuser)) {
                  foreach ($vpuser as $k => $v) {
                    echo '<option value="'.$v->id.'">'.$v->name.'</option>';
                  }
                }
                ?>
              </select>              
            </div>
            <div class="table-responsive table-invoice task-admin-table">
              <table class="table table table-bordered table-striped" id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> Id</th>
                   <th> Task</th>
                   <th> Description</th>
                   <th> Priority</th>
                   <th> Client Name </th>
                   <th> Assigned VP</th>
                   <th> Created Date/Time</th>
                   <th> Status</th>
                   <th> Due Date</th>
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



var index_url = "{{route('admin.task.getData')}}";
$(window).on('load', function() {
    var status = 0;
    var due_date = '';
    var vpuser = '';
    gettabledata(status, due_date, vpuser);
    function gettabledata(status, due_date, vpuser) {
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
                "vpuser": vpuser
            }
        },
        "order": [[ 0, "desc" ]],
        columns: [
          { data: 'id', name: 'id'},
          { data: 'task', name: 'task'},
          { data: 'mytask', name: 'mytask'},
          { data: 'priority', name: 'priority'},
          // { data: 'cname', name: 'cname'},
          { data: null,
            render: function(data){

              var view_button = ' <a href="'+data.clink+'" data-toggle="tooltip" title="View details">'+data.cname+'</a>';
                return view_button;

            }, orderable: "false"
          },
          { data: 'allot_user_id', name: 'allot_user_id'},
          { data: 'created_at', name: 'created_at'},
          { data: 'stat', name: 'stat'},
          { data: 'due_date', name: 'due_date'},
          { data: null,
            render: function(data){
                var time_button = ' <a href="{{url('admin/task/timeline')}}/'+data.case_id+'" class="action_btn" title="View Timeline" data-toggle="tooltip"><img src="{{url('assets/images')}}/icons/clock(3).svg"></a>';
                var edit_button = '';
                if(data.is_edit) {
                  if(data.task_type == 'ADMIN_TASK') {
                    edit_button = ' <a href="{{url('admin/task/edit1')}}/'+data.id+'" class="action_btn" title="Edit Task" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
                  }
                  else if(data.task_type == 'Assign_Case') {
                    edit_button = ' <a href="{{url('admin/task/edit')}}/'+data.id+'" class="action_btn" title="Edit Task" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/Group 16@2x.png" /></a>';
                  }
                  else {
                    edit_button = ' <a href="{{url('admin/task/edit')}}/'+data.id+'" class="action_btn" title="Edit Task" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>'; 
                  }
                }
                if(data.task_type == 'DELETE_ACCOUNT') {
                  var delete_button = ' <a href="{{url('admin/task/delete_firm_account')}}/'+data.id+'" class="action_btn" title="Deativate Firm Account" data-toggle="tooltip"><img src="{{url('assets/images/icons')}}/case-icon3.svg" /></a>';
                  return  delete_button;
                }
                else {
                  return  edit_button + time_button;
                }
                

            }, orderable: "false"
          },
        ],
      });
    }
    $('.nav-link').on('click', function(){
      var st = $(this).data('status');
      due_date = $('select#due_date').val();
      vpuser = $('select#vpusers').val();
      if(st) {
        $('.due_date_wrapper').hide();
        due_date = '';
      }
      else {
        $('.due_date_wrapper').show();
      }
      gettabledata(st, due_date, vpuser);
    });
    $('select#due_date').on('change', function(){
      var st = $('.nav-link.active').data('status');
      vpuser = $('select#vpusers').val();
      due_date = $('select#due_date').val();
      gettabledata(st, due_date, vpuser);
    });
    $('select#vpusers').on('change', function(){
      var st = $('.nav-link.active').data('status');
      vpuser = $('select#vpusers').val();
      case_type = $('select#case_type').val();
      gettabledata(st, due_date, vpuser);
    });
 });
  $(document).ready(function(){
    if (window.history && window.history.pushState) {

      $(window).on('popstate', function() {
        var hashLocation = location.hash;
        var hashSplit = hashLocation.split("#!/");
        var hashName = hashSplit[1];

        if (hashName !== '') {
          var hash = window.location.hash;
          if (hash === '') {
            var u = '{{url("admin/task")}}';
            // alert('Back button was pressed.'+ u);
            window.location.href = u;
            return false;
          }
        }
      });

      window.history.pushState('forward', null, '');
  }
 });
//================ Edit user ============//

</script>

@endpush 
