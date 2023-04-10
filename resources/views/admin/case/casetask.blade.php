@extends('layouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details task-new-header">
<!--new-header open-->
  @include('admin.case.case_header')
<!--new-header Close-->

   <div class="section-body task-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
           <div class="card-body">
             <div class="profile-new-client">
               <h2>Tasks</h2>
               <?php if(!empty($admintask)) { ?>
               <a href="{{ url('admin/allcases/addnewtask')}}/{{$case->id}}" class="add-task-link">Add a New Task</a>
               <?php } ?>
               <div class="table-box-task">
                <div class="table-responsive table-invoice">
                   <table class="table table-striped">
                     <thead>
                       <tr>
                         <th>Task ID</th>
                         <th>Tasks</th>
                         <th>Description</th>
                         <th>Created Date/Time</th>
                         <th>Due Date/Time</th>
                         <th>Created By</th>
                         <th>Status</th>
                         <th>Actions</th>
                       </tr>
                     </thead>
                     <tbody>
                      <?php 
                      if(!empty($task)) {
                        foreach ($task as $k => $v) { 
                          $eurl = '#';
                          if(!empty($admintask)) {
                            $eurl = url('admin/allcases/editcasetask').'/'.$case->id.'/'.$v->id;
                          }
                          ?>
                       <tr>
                         <td><a href="{{$eurl}}">#<?php echo $v->id; ?></a></td>
                         <td><?php echo $v->title; ?></td>
                         <td><?php echo $v->description; ?></td>
                         <td><?php echo date('Y-m-d', strtotime($v->created_at)); ?> <span class="text-gray"><?php echo date('h:i A', strtotime($v->created_at)); ?></span></td>
                         <td><?php echo $v->e_date; ?> <span class="text-gray"><?php echo $v->e_time; ?></span></td>
                         <td>{{$v->name}}</td>
                         <td>
                          <?php if($v->status) {
                                echo 'Closed';
                            }
                            else {
                                echo 'Open';
                            } ?>
                         </td>
                         <td>
                           <a href="{{$eurl}}" class="action_btn" title="Edit" data-toggle="tooltip"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>
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
