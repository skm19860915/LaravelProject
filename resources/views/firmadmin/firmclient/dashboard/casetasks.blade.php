@extends('firmlayouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details task-new-header-tasks">
<!--new-header open-->
  @include('firmadmin.firmclient.dashboard.client_header')
<!--new-header Close-->

   <div class="section-body task-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/clientcase') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
          </div>
           <div class="card-body">
             <div class="profile-new-client">
               <h2>Tasks</h2>
               <a href="{{ url('firm/clientcase/add_casetasks')}}/{{$case->case_id}}" class="add-task-link">Add a New Task</a>
               <div class="table-box-task">
                <div class="table-responsive table-invoice">
                   <table class="table table-striped">
                     <thead>
                       <tr>
                         <!-- <th>Task ID</th> -->
                         <th>Tasks</th>
                         <th>Description</th>
                         <th>Create Date</th>
                         <th>Due Date</th>
                         <th>Status</th>
                       </tr>
                     </thead>
                     <tbody>
                      <?php 
                      if(!empty($task)) {
                        foreach ($task as $k => $v) { ?>
                       <tr>
                         <!-- <td><a href="#">#<?php echo $v->id; ?></a></td> -->
                         <td><?php echo $v->title; ?></td>
                         <td><?php echo $v->description; ?></td>
                         <td><?php echo date('Y-m-d', strtotime($v->created_at)); ?> <span class="text-gray"><?php echo date('h:i A', strtotime($v->created_at)); ?></span></td>
                         <td><?php echo $v->e_date; ?> <span class="text-gray"><?php echo $v->e_time; ?></span></td>
                         <td>
                          <?php 
                            if($v->status) {
                              echo '<div class="Active">Completed</div>';
                            }
                            else {
                              echo '<div class="Closed - Lost">Incompleted</div>';
                            }
                          ?>
                          
                        </td>
                       </tr>
                     <?php } } ?>
                     </tbody>
                   </table>
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

var index_url = "{{route('admin.usertask.getData')}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'firm_name', name: 'firm_name'},
        { data: 'task_type', name: 'task_type'},
        { data: 'task', name: 'task'},
        { data: 'case_id', name: 'case_id'},
        { data: 'allot_user_id', name: 'allot_user_id'},
        { data: 'priority', name: 'priority'},
        { data: 'stat', name: 'stat'},
        
        { data: null,
          render: function(data){
            var view_button = '';
            if(data.case_id) {
              view_button = ' <a href="{{url('admin/document_request')}}/'+data.case_id+'" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
            }
              var time_button = ' <a href="{{url('admin/task/timeline')}}/'+data.case_id+'" class="btn btn-primary"><i class="fa fa-clock"></i></a>';
              var edit_button = ' <a href="{{url('admin/task/edit')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
              return view_button;

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
 });

//================ Edit user ============//

</script>

@endpush 
