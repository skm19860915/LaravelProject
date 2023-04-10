@extends('layouts.admin-master')

@section('title')
Firm Detail
@endsection

@push('header_styles')
<style type="text/css">
  .overview-border-number {
    padding-right: 15px;
    text-align: center;
    margin-right: 11px;
  }
</style>
@endpush 

@section('content')
<section class="section client-listing-details">
  <div class="section-header">
    <h1><a href="{{ url('admin/firm') }}"><span>Firm /</span></a> Detail</h1>
  </div>
  <!--new-header open-->
  @include('admin.firm.firm_header')
  <!--new-header Close-->
  
  <div class="section-body">
    <div class="row">
     <div class="col-md-12">
      <div class="card">
        <div class="back-btn-new">
          <a href="{{ url('admin/firm') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
         </div>
       <div class="card-body">
        <div class="form-overview-new">

         <div class="overview-numbers">

          <div class="overview-border-number">
           <h3>{{$data['total_case']}}</h3>
           <p>Firm Cases</p>
         </div>
         <div class="overview-border-number">
           <h3>{{$data['vp_case']}}</h3>
           <p>VP Cases</p>
         </div>
         <?php if($firm->account_type == 'CMS') { ?>
         
         <div class="overview-border-number">
           <h3>{{$data['total_user']}}</h3>
           <p>Firm Users</p>
         </div>

         <div class="overview-border-number">
          <h3>{{$data['total_client']}}</h3>
           <p>Firm Clients</p>
         </div>

         <div class="overview-border-number">
           <h3>{{$data['total_lead']}}</h3>
           <p>Firm Leads</p>
         </div>

         <div class="overview-border-number" style="width: auto;">
           <h3>{{$data['self_case']}}</h3>
           <p>Self Managed Cases</p>
         </div>

         <?php } ?>
         <div class="overview-border-number width-new-billing">
           <h3>$<?php echo number_format($data['total_billing'], 2); ?></h3>
           <p>Firm Total Value</p>
         </div>

         <?php if($firm->account_type == 'CMS') { ?>
         <!-- <div class="overview-border-number width-new-billing">
           <h3>$<?php echo number_format($data['total_billing'], 2); ?></h3>
           <p>CMS Total Value</p>
         </div> -->
         <?php } ?>

         <!-- <div class="overview-border-number width-new-billing">
           <h3>$<?php echo number_format($data['total_billing'], 2); ?></h3>
           <p>VP Total Value</p>
         </div> -->

       </div>

       <div class="overview-table-box">
        <h3>Tila Tasks</h3>
        <div class="tabel-box-recent">
          <div class="table-responsive table-invoice">
            <table class="table table-striped">
              <thead>
                <tr>
                  <!-- <th style="display: none;">Task ID</th> -->
                  <th>Tasks</th>
                  <th>Case Type</th>
                  <th>Create Date</th>
                  <th>Status</th> 
                  <th>Action</th>                         
                </tr>
              </thead>
              <tbody>
                <?php
                if(!empty($task)) {
                  foreach ($task as $k => $v) { ?>
                   <tr>
                     <!-- <td><a href="#">#<?php echo $v->id; ?></a></td> -->
                     <td><?php echo $v->task; ?></td>
                     <td><?php echo $v->case_type; ?></td>
                     <td><?php echo date('Y-m-d', strtotime($v->created_at)); ?> <span class="text-gray"><?php echo date('h:i A', strtotime($v->created_at)); ?></span></td>
                     <td>
                      <?php 
                      if($v->status) {
                        echo '<div class="Active">Completed</div>';
                      }
                      else {
                        echo '<div class="Closed - Lost">Open</div>';
                      }
                      ?>

                    </td>
                    <td>
                        <a href="{{url('admin/task/edit')}}/{{$v->id}}" class="action_btn">
                          <?php
                          if($v->task_type == 'Assign_Case') { ?>
                            <img src="{{url('assets/images/icon')}}/Group 16@2x.png" />
                          <?php } else { ?>
                            <img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" />
                          <?php } ?>
                          
                        </a>
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
</div>
</section>
@endsection
