@extends('firmlayouts.client-family')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details task-new-header-tasks">
<!--new-header open-->
  @include('firmclientfamily.cases.include.case_header')
<!--new-header Close-->

   <div class="section-body task-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
           <div class="card-body">
           
             <div class="profile-new-client">
               <h2>Tasks</h2>
               <a href="{{ url('firm/clientfamilydashboard/addfamilytask')}}/{{$case->case_id}}" class="add-task-link">Add a New Task</a>
               <div class="table-box-task">
                <div class="table-responsive table-invoice">
                   <table class="table table-striped">
                     <thead>
                       <tr>
                         <!-- <th>Task ID</th> -->
                         <th>Tasks</th>
                         <th>Description</th>
                         <!-- <th>Create Date</th> -->
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
                         <!-- <td><?php echo date('Y-m-d', strtotime($v->created_at)); ?> <span class="text-gray"><?php echo date('h:i A', strtotime($v->created_at)); ?></span></td> -->
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


//================ Edit user ============//

</script>

@endpush 
