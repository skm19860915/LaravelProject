@extends('firmlayouts.admin-master')

@section('title')
View client
@endsection

@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('firmadmin.client.client_header')
<!--new-header Close-->
  
   <div class="section-body task-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
           <div class="card-body">
           
             <div class="profile-new-client">
               <h2>Tasks</h2>
               <?php if($firm->account_type == 'CMS') { ?>
               <a href="{{ url('firm/client/add_client_task/')}}/{{$client->id}}" class="add-task-link">Create Tasks</a>
               <?php } else { ?>
                <!-- <a href="{{ url('firm/client/add_client_task')}}/{{$client->id}}" class="add-task-link">Upgrade to create a task</a> -->
                <?php } ?>
               <div class="table-box-task">
                <div class="table-responsive table-invoice">
                   <table class="table table-striped">
                     <thead>
                       <tr>
                         <th>Task ID</th>
                         <th>Task Type</th>
                         <th>Title</th>
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
                          if($firm->account_type == 'CMS') {
                            $eurl = url('firm/case/edit_case_tasks').'/'.$case->id.'/'.$v->id;
                          }
                          ?>
                       <tr>
                         <td><a href="{{$eurl}}">#<?php echo $v->id; ?></a></td>
                         <td><?php echo $v->type; ?></td>
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
                          <?php 
                          if($firm->account_type == 'CMS') { ?>
                           <a href="{{url('firm/client/edit_client_task')}}/{{ $client->id }}/{{$v->id}}" class="action_btn" title="Edit" data-toggle="tooltip"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>
                           <?php } ?>
                         </td>
                       </tr>
                      <?php } } ?>
                      <?php 
                      if(!empty($atask)) {
                        foreach ($atask as $k => $v) { ?>
                       <tr>
                         <td><a href="#">#<?php echo $v->id; ?></a></td>
                         <td>Task</td>
                         <td><?php echo $v->task; ?></td>
                         <td><?php echo $v->mytask; ?></td>
                         <td><?php echo date('Y-m-d', strtotime($v->created_at)); ?> <span class="text-gray"><?php echo date('h:i A', strtotime($v->created_at)); ?></span></td>
                         <td><?php echo $v->due_date; ?></td>
                         <td>
                            <?php
                            $lu1 = getUserName($v->allot_user_id);
                            if($lu1) {
                              echo $lu1->name;
                            }
                            ?>
                         </td>
                         <td>
                          <?php if($v->status) {
                                echo 'Closed';
                            }
                            else {
                                echo 'Open';
                            } ?>
                         </td>
                         <td>
                           
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


<div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">  
      <div class="col-md-12">
        Note 
        <br>
      </div>
      <div class="col-md-12">
        <textarea name="note" class="form-control" style="height: 150px;"></textarea>
      </div>
    </div>
    <div class="row">  
      <div class="col-md-12 text-right">
        <input type="hidden" name="client_id" value="{{ $client->id }}" >  
        @csrf
        <input type="submit" name="save" value="Create Client Note" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>
@endsection


@push('footer_script')

<script type="text/javascript">
$(document).ready(function(){
  $("#fire-modal-2").fireModal({title: 'Add Client Notes', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var client_id = $('input[name="client_id"]').val();
      var note = $('textarea[name="note"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/client/add_notes') }}",
        data: {_token:_token, note:note, client_id:client_id},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/client/view_notes') }}/{{ $client->id }}";
          }
          else {
            alert('Mendatory fields are required!')
          }
          console.log(res);
        }
      });
    });
});


//================ Edit user ============//

</script>
<style type="text/css">
  .card .card-header .btn {
    margin-top: 1px;
    padding: 2px 12px;
  }
</style>
@endpush 