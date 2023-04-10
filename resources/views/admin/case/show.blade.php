@extends('layouts.admin-master')

@section('title')
View Case
@endsection

@section('content')
<section class="section client-listing-details task-new-header">
<!--new-header open-->
  @include('admin.case.case_header')
<!--new-header Close-->

  <div class="section-body">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
           <div class="card-body">
            <div class="form-overview-new">
               <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                   <div class="overview-numbers">
                    <div class="overview-border-number">
                       <h3>{{ $data['totla_tasks'] }}</h3>
                       <p>Total Tasks</p>
                     </div>
                    <div class="overview-border-number">
                       <h3>{{ $data['totla_notes'] }}</h3>
                       <p>Total Notes</p>
                     </div>
                    <div class="overview-border-number margin-right-task0">
                       <h3>{{ $data['totla_documents'] }}</h3>
                       <p>Total Documents</p>
                     </div>
                  </div>
                   <div class="overview-table-box">
                    <h3>Recent Tasks</h3>
                    <div class="tabel-box-recent">
                       <div class="table-responsive table-invoice">
                        <table class="table table-striped">
                           <thead>
                            <tr>
                               <!-- <th style="min-width: 75px;">Task ID</th> -->
                               <th>Tasks</th>
                               <th>Due Date</th>
                               <!-- <th>Status</th> -->
                             </tr>
                          </thead>
                           <tbody>
                            <?php
                            if(!empty($task)) {
                            foreach ($task as $k => $v) { ?>
                           <tr>
                             <!-- <td><a href="#">#<?php echo $v->id; ?></a></td> -->
                             <td><?php echo $v->description; ?></td>
                             <td><?php echo $v->e_date; ?> <span class="text-gray"><?php echo $v->e_time; ?></span></td>
                             <!-- <td>
                              <?php 
                                if($v->status) {
                                  echo '<div class="Active">Completed</div>';
                                }
                                else {
                                  echo '<div class="Closed - Lost">Incompleted</div>';
                                }
                              ?>
                              
                            </td> -->
                           </tr>
                         <?php } } ?>
                          </tbody>
                         </table>
                      </div>
                     </div>
                  </div>
                 </div>
                <!-- <div class="col-md-3 col-sm-3 col-xs-12">
                   <div class="overview-table-box upcoming-hearings">
                    <h3>Upcoming Hearings</h3>
                  </div>
                 </div> -->
              </div>
             </div>
          </div>
         </div>
      </div>
     </div>
  </div>
</section>
@endsection
