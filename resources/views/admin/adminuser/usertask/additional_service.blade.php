@extends('layouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<style type="text/css">
.add_more_wrapper a {
  color: #fff !important;
  top: -24px;
}  
.add_more_wrapper a.remove_more_btn, .add_more_wrapper a.remove_quantity_btn {
  top: 2px;
}
input[name="nvc_packet_quantity_new"].form-control {
  width:120px;
}
select[name="declaration_new[]"] {
  width: 35%;
  display: inline-block;
}
input[name="declaration_other_new[]"] {
  width:60%;
  display: none;
}
.main-wrapper .main-content .section-body .card .card-body .table tbody tr td a.action_btn {
  margin-left: 35px;
}
</style>
@endpush  

@section('content')
<section class="section client-listing-details task-new-header-document">
<!--new-header open-->
  @include('admin.adminuser.usertask.task_header')
<!--new-header Close-->
   
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">        
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('admin/all_case') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
            <div class="profile-new-client">
             <h2>Additional Service</h2>
             <!-- <a href="#" class="add-task-link" id="fire-modal-2">+ Add New</a> -->
             
             <div class="documents-list-box">
                <form action="{{url('admin/usertask/request_additional_service')}}" method="post">
                  <div class="table-responsive table-invoice">
                    <table class="table table table-bordered table-striped"  id="table" style="width: 99%;">
                      <thead>
                        <tr>
                         <th> Case Category</th>
                         <th> Case Type </th>
                         <th> Service</th>
                         <th> Cost</th>
                         <th> Status</th>
                         <th> Action</th>
                        </tr>
                      </thead>
                      <?php 
                      $additional_service = json_decode($case->additional_service);
                      if($case->case_category == 'NVC Packet/Consular Process') { ?>
                        <tr>
                          <td colspan="6">
                            <strong>NVC Packet/Consular Process</strong>
                          </td>
                        </tr>
                        <tr>
                           <td> <?php echo $case->case_category; ?></td>
                           <td> <?php echo $case->case_type; ?></td>
                           <td> <?php echo $additional_service->nvc_packet; ?></td>
                           <td> $<?php echo $additional_service->nvc_packet_quantity*$I_DS260_Cost; ?></td>
                           <td> <input type="number" name="nvc_packet_quantity_new" min="<?php echo $additional_service->nvc_packet_quantity; ?>" class="form-control" value="<?php echo $additional_service->nvc_packet_quantity; ?>"></td>
                           <td>
                             <div class="col-md-2 add_more_wrapper">
                              <a href="#" class="add_quantity_btn">
                                <i class="fa fa-plus"></i>
                              </a>
                              <a href="#" class="remove_quantity_btn">
                                <i class="fa fa-minus"></i>
                              </a>
                            </div>
                           </td>
                         </tr>
                      <?php 
                      
                      if(!empty($additional_service->nvc_packet_quantity_new) && ($additional_service->nvc_packet_quantity_new-$additional_service->nvc_packet_quantity)) {
                       // foreach ($additional_service->nvc_packet_quantity_new as $key => $value) { ?>
                          <tr>
                           <td> <?php echo $case->case_category; ?></td>
                           <td> <?php echo $case->case_type; ?></td>
                           <td> <?php echo $additional_service->nvc_packet; ?></td>
                           <td> $<?php echo (($additional_service->nvc_packet_quantity_new)-($additional_service->nvc_packet_quantity))*$I_DS260_Cost; ?></td>
                           <td> <?php echo (($additional_service->nvc_packet_quantity_new)-($additional_service->nvc_packet_quantity)); ?></td>
                           <td>
                             Requested
                           </td>
                         </tr>
                         <?php 
                        //}
                      }
                    }
                      ?>
                      <tr>
                        <td colspan="6">
                          <strong>Affidavit of Support</strong>
                        </td>
                      </tr>
                      <?php 
                      $service_exist = array();
                      if(!empty($additional_service->additional_service->additional_service)) {
                        foreach ($additional_service->additional_service->additional_service as $k1 => $v1) {
                        $service_exist[] = $v1; ?>
                          <tr>
                           <td> <?php echo $case->case_category; ?></td>
                           <td> <?php echo $case->case_type; ?></td>
                           <td> <?php echo $v1; ?></td>
                           <td>
                            <?php
                            if($v1 == 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor') {
                              echo '$'.$I_864_Cost;
                            }
                            else {
                              echo '$'.$I_864A_Cost;
                            } ?>
                           </td>
                           <td> Selected</th>
                           <td>
                           	<?php 
                             $kk = 'additional_service_doc'.'_'.$k1;
                             ?>
                           </td>
                          </tr>
                        <?php }
                      }
                      if(!empty($additional_service->additional_service_new)) {
                        foreach ($additional_service->additional_service_new as $k1 => $v1) {
                        $service_exist[] = $v1; ?>
                          <tr>
                           <td> <?php echo $case->case_category; ?></td>
                           <td> <?php echo $case->case_type; ?></td>
                           <td> <?php echo $v1; ?></td>
                           <td> 
                            <?php 
                            if($v1 == 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor') {
                              echo '$'.$I_864_Cost;
                            }
                            else {
                              echo '$'.$I_864A_Cost;
                            } ?>
                           </td>
                           <td> Requested</th>
                           <td></td>
                          </tr>
                        <?php }
                      }
                      ?>
                      <?php if(!in_array('I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor', $service_exist)) { ?>
                      <tr>
                        <td> <?php echo $case->case_category; ?></td>
                        <td> <?php echo $case->case_type; ?></td>
                        <td> I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor</td> 
                        <td> ${{$I_864_Cost}}</td> 
                        <td> Request</td>
                        <td> <input type="checkbox" name="additional_service_new[]" data-cost="{{$I_864_Cost}}" value="I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor"></td>
                      </tr>
                      <?php } ?>
                      <?php if(!in_array('I-864A, Contract Between Sponsor and Household Member', $service_exist)) { ?>
                      <tr>
                       <td> <?php echo $case->case_category; ?></td>
                       <td> <?php echo $case->case_type; ?></td>
                       <td> I-864A, Contract Between Sponsor and Household Member</td>
                       <td> ${{$I_864A_Cost}}</td>
                       <td> Request</th>
                       <td> <input type="checkbox" name="additional_service_new[]" data-cost="{{$I_864A_Cost}}" value="I-864A, Contract Between Sponsor and Household Member"></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="6">
                          <strong>Affidavit / Declaration</strong>
                        </td>
                      </tr>
                      <?php 
                      $service_exist1 = '';
                      ?>
                      <?php 
                      if(!empty($additional_service->declaration->declaration)) {
                        foreach ($additional_service->declaration->declaration as $k1 => $v1) { ?>
                          <tr>
                           <td> <?php echo $case->case_category; ?></td>
                           <td> <?php echo $case->case_type; ?></td>
                           <td> 
                            <?php echo $v1; 
                            if($v1 == 'Other') {
                              echo ' - '.$additional_service->declaration->declaration_other[$k1];
                            }
                            ?>
                              
                            </td>
                           <td> ${{$I_Affidavit_Cost}}</td>
                           <td> 
                            <?php 
                                if($additional_service->declaration->status) {
                                  echo 'Selected';
                                }
                                else {
                                  echo '';
                                }
                              ?>
                           </td>
                           <td> 
                            <div class="add_more_wrapper">
                              <input type="checkbox" name="additional_service1" data-cost="{{$I_Affidavit_Cost}}" value="Affidavit Draft  - (a conferrence call or video available)">
                              <a href="#" class="add_more_btn">
                                <i class="fa fa-plus"></i>
                              </a>
                              <a href="#" class="remove_more_btn">
                                <i class="fa fa-minus"></i>
                              </a>
                            </div>
                            <?php 
                            $kk1 = 'declaration_doc'.'_'.$k1;
                            if(!empty($additional_service->$kk1)) { ?>
                              <a href="#" class="action_btn viewdocbtn" data-type="declaration_doc" data-index="{{$k1}}" title="View/Edit" data-toggle="tooltip" data-files="{{$additional_service->$kk1}}">
                                <img src="{{url('assets/images/icon')}}/Group 557.svg" />
                              </a>
                            <?php } else { ?>
                            <a href="#" class="action_btn viewdocbtn" data-type="declaration_doc" data-index="{{$k1}}" title="Upload" data-toggle="tooltip" data-files="">
                              <i class="fas fa-upload"></i>
                            </a>
                            <?php } ?>
                           </td>
                          </tr>
                        <?php 
                      }
                      } ?>
                      <?php 
                      if(!empty($additional_service->declaration->declaration_new)) {
                        foreach ($additional_service->declaration->declaration_new as $k1 => $v1) { ?>
                          <tr>
                           <td> <?php echo $case->case_category; ?></td>
                           <td> <?php echo $case->case_type; ?></td>
                           <td> 
                            <?php echo $v1; 
                            if($v1 == 'Other') {
                              echo ' - '.$additional_service->declaration->declaration_other_new[$k1];
                            }
                            ?>
                              
                            </td>
                           <td> ${{$I_Affidavit_Cost}}</td>
                           <td> 
                            Requested
                           </th>
                           <td>
                             
                           </td>
                          </tr>
                        <?php 
                      }
                      } ?>
                      <tr>
                        <td colspan="6">
                          <div class="text-right">
                            @csrf
                            <input type="hidden" name="case_id" value="{{$case->id}}">
                            <input type="hidden" name="task_id" value="{{$admintask->id}}">
                            <input type="hidden" name="firm_admin_id" value="{{$admintask->firm_admin_id}}" />
                            <input type="submit" name="Request" value="Request" class="btn btn-primary req_submit" style="display: none;">
                          </div>
                        </td>
                      </tr>
                      
                    </table>
                  </div>         
                </form>
             </div>
           </div>
          </div>
        </div>
      </div>
     </div>
  </div>
</section>
<!-- Modal -->
<div id="UploadAdditionalDocument" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Upload Document</h4>
      </div>
      <div class="modal-body">
        <form action="{{url('setAdditionalDocument')}}" method="post" enctype="multipart/form-data">
          <!-- <div class="row">
            <div class="col-md-12 fallback1">
              <input name="title" type="text" class="form-control" placeholder="Title" required="required"/>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12 fallback1">
              <textarea name="description" placeholder="Description" class="form-control"></textarea>
            </div>
          </div>
          <br> -->
          <div class="row">
            <div class="col-md-12 fallback1">
              <input name="file" type="file" required="required"/>
            </div>
            <div class="col-md-12">
                <ul class="uploadFiles"></ul>
            </div>
          </div>
          <br>
          <div class="row">  
            <div class="col-md-12 text-right">
              @csrf
              <input type="hidden" name="doc_index"  value="">
              <input type="hidden" name="doc_type"  value="">
              <input type="hidden" name="case_id"  value="{{$case->id}}">
              <input type="submit" name="save" value="Save" class="btn btn-primary"/>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
<input type="hidden" name="case_id" value="{{$case->id}}" />
<input type="hidden" name="firm_admin_id" value="{{$admintask->firm_admin_id}}" />
@endsection
@push('footer_script')
<script type="text/javascript">
   $(document).ready(function(){
    $(document).on('click', '.viewdocbtn', function(e){
      e.preventDefault();
      var id = $(this).data('index');
      var type = $(this).data('type');
      $('input[name="doc_index"]').val(id);
      $('input[name="doc_type"]').val(type);
      var files = $(this).data('files');
      $('.uploadFiles').empty();
      if(files) {
        var f = files;
        var url = "{{asset('storage/app')}}/"+f; 
        f = f.replace('client_doc/', '');
        var li = '<li><a href="'+url+'"  target="_blank">'+f+'</a></li>';
        $('.uploadFiles').append(li);
        $('.modalformpart > form').hide();
        $('.uploadFileswrap').show();  
      }
      else {
        //alert('Document not Submitted');
      }
      $("#UploadAdditionalDocument").modal('show');
    });
  });
   $(document).on('click', '.add_quantity_btn', function(e){
      e.preventDefault();
      $('.req_submit').show();
      var q = $('input[name=nvc_packet_quantity_new]').val();
      q++;
      $('input[name=nvc_packet_quantity_new]').val(q);
      // costn = q*99;
      // var c1 = cost1+cost2+costc+costd+costn;
      // c1 = '$'+c1.toString();
      // $(document).find('.casecost1').attr('value', c1);
  });
  $(document).on('click', '.remove_quantity_btn', function(e){
      e.preventDefault();
      var q = $('input[name=nvc_packet_quantity_new]').val();
      var q1 = $('input[name=nvc_packet_quantity_new]').attr('min');
      q--;
      if(q >= q1) {
        $('input[name=nvc_packet_quantity_new]').val(q);
      //   costn = q*99;
      // var c1 = cost1+cost2+costc+costd+costn;
      // c1 = '$'+c1.toString();
      // $(document).find('.casecost1').attr('value', c1);
      }
  });
    var r = '<tr class="rmrow"><td><?php echo $case->case_category; ?></td>';
      r += '<td><?php echo $case->case_type; ?></td><td><select class="form-control" name="declaration_new[]">';
      r += '<option value="Beneficiary">Beneficiary</option><option value="Petitioner">Petitioner</option>';
      r += '<option value="Other">Other</option></select>';
      r += '<input type="text" name="declaration_other_new[]" placeholder="Write here..." class="form-control" value="">';
      r += '</td><td>${{$I_Affidavit_Cost}}</td><td>Requested</td><td>';
      r += '<div class="add_more_wrapper">';
      r += '<a href="#" class="add_more_btn"><i class="fa fa-plus"></i></a>';
      r += '<a href="#" class="remove_more_btn"><i class="fa fa-minus"></i></a></div></td>';
      r += '</tr>';
      //
  $(document).on('click', '.add_more_btn', function(e){
      e.preventDefault();
      $('.req_submit').show();
      $('#table tbody tr:last-child').before(r);
      // costd = $('select[name*=declaration]').length*180;
      // costn = $('input[name="nvc_packet_quantity"]').val()*99;
      // var c1 = cost1+cost2+costc+costd+costn;
      // c1 = '$'+c1.toString();
      // $(document).find('.casecost1').attr('value', c1);
  });
  $(document).on('click', '.remove_more_btn', function(e){
    e.preventDefault();
    $(this).closest('.rmrow').remove();
    // costd = $('select[name*=declaration]').length*180;
    // costn = $('input[name="nvc_packet_quantity"]').val()*99;
    //   var c1 = cost1+cost2+costc+costd+costn;
    //   c1 = '$'+c1.toString();
    //   $(document).find('.casecost1').attr('value', c1);
  });
  $(document).on('change', 'select[name="declaration_new[]"]', function(){
    var v = $(this).val();
    if(v == 'Other') {
      $(this).closest('tr').find('input[name="declaration_other_new[]"]').show();
    }
    else {
      $(this).closest('tr').find('input[name="declaration_other_new[]"]').hide();
    }
  });
  $(document).on('change', '.documents-list-box input', function(){
    $('.req_submit').show();
  });
//================ Edit user ============//

</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 