@extends('firmlayouts.admin-master')

@section('title')
View Case
@endsection

@section('content')
<section class="section client-listing-details task-new-header">
<!--new-header open-->
  @include('firmadmin.case.case_header')
<!--new-header Close-->

  <div class="section-body">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/case/allcase') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
           <div class="card-body">
            <div class="form-overview-new">
               <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                   <div class="overview-numbers">
                    <div class="overview-border-number" style="width: 140px;">
                       <h3>{{ $data['totla_tasks'] }}</h3>
                       <p>Total Tasks</p>
                     </div>
                    <div class="overview-border-number" style="width: 140px;">
                       <h3>{{ $data['totla_notes'] }}</h3>
                       <p>Total Notes</p>
                     </div>
                     <div class="overview-border-number" style="width: 140px;">
                       <h3>{{ $data['totla_forms'] }}</h3>
                       <p>Total Forms</p>
                     </div>
                    <div class="overview-border-number margin-right-task0" style="width: 140px;">
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
