@extends('firmlayouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
  #PayForTranslation form input[type="checkbox"] {
    display: none;
  }
  .main-wrapper .main-content .section-body .card .card-body .table tbody tr td a.action_btn i {
    vertical-align: inherit;
  }
  .selectedfiles {
  padding: 0;
  margin: 30px 0 0 0;
}  
.selectedfiles li {
  display: inline-block;
  vertical-align: top;
  margin-bottom: 15px;
  position: relative;
  margin-right: 15px;
}
.selectedfiles li input {
  display: none;
}
.selectedfiles li img {
    width: 90px;
    height: 90px;
    margin: 0 auto;
    display: block;
}
.selectedfiles li label {
    display: block;
    width: 210px;
    text-align: center;
}
.selectedfiles li a.remove_file {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 99;
    padding: 1px;
    color: #fff;
    background: rgba(0,0,0,0.5);
}
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
</style>
@endpush  

@section('content')
<section class="section client-listing-details task-new-header-document">
<!--new-header open-->
  @include('firmadmin.case.case_header')
<!--new-header Close-->
   
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">        

          <div class="card-body">
            <div class="profile-new-client">
             <h2>Additional Service</h2>
             <!-- <a href="#" class="add-task-link" id="fire-modal-2">+ Add New</a> -->
             
             <div class="documents-list-box">
               <form action="{{url('firm/case/requestadditionalservice')}}" method="post">
                <div class="table-responsive table-invoice">
                  <table class="table table table-bordered table-striped"  id="table" >
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
                          <strong>NVC Packet</strong>
                        </td>
                      </tr>
                      <?php // if($additional_service->nvc_packet_quantity) { ?>
                      <tr>
                         <td> <?php echo $case->case_category; ?></td>
                         <td> <?php echo $case->case_type; ?></td>
                         <td> <?php echo $additional_service->nvc_packet; ?></td>
                         <td> <?php 
                         if(empty($case->VP_Assistance)) { 
                          echo 'Self managed'; 
                        }
                        else { 
                          echo '$'.$additional_service->nvc_packet_quantity*$I_DS260_Cost;
                        } ?></td>
                         <td><input type="number" name="nvc_packet_quantity_new" min="<?php echo $additional_service->nvc_packet_quantity; ?>" class="form-control" value="<?php echo $additional_service->nvc_packet_quantity; ?>"></td>
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
                       <?php //}
                      if(!empty($additional_service->nvc_packet_quantity_new) && ($additional_service->nvc_packet_quantity_new-$additional_service->nvc_packet_quantity)) { ?>
                          <tr>
                           <td> <?php echo $case->case_category; ?></td>
                           <td> <?php echo $case->case_type; ?></td>
                           <td> <?php echo $additional_service->nvc_packet; ?></td>
                           <td> <?php 
                           if(empty($case->VP_Assistance)) { 
                          echo 'Self managed'; 
                        }
                        else {
                          echo '$'.($additional_service->nvc_packet_quantity_new-$additional_service->nvc_packet_quantity)*$I_DS260_Cost; 
                        } ?></td>
                           <td> Need to pay</td>
                           <td>
                             <input type="checkbox" name="nvc_packet_quantity_new" value="<?php echo ($additional_service->nvc_packet_quantity_new-$additional_service->nvc_packet_quantity)*"$I_DS260_Cost"; ?>" class="needtopay">
                           </td>
                         </tr>
                         <?php 
                      }
                     }
                    ?>
                    <tr>
                      <td colspan="6">
                        <strong>Affidavit of Support</strong>
                      </td>
                    </tr>
                    <?php 
                    $service_existx = array();
                    if(!empty($additional_service->additional_service->additional_service)) {
                      foreach ($additional_service->additional_service->additional_service as $k1 => $v1) {
                        $service_existx[] = $v1; ?>
                        <tr>
                         <td> <?php echo $case->case_category; ?></td>
                         <td> <?php echo $case->case_type; ?></td>
                         <td> <?php echo $v1; ?></td>
                         <td> <?php if(empty($case->VP_Assistance)) { 
                          echo 'Self managed'; 
                        }
                        else {
                          if($v1 == 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor') {
                            echo '$'.$I_864_Cost;
                          }
                          else {
                            echo '$'.$I_864A_Cost;
                          }
                        } ?></td>
                         <td> Selected</th>
                         <td>
                           <?php 
                           $kk = 'additional_service_doc'.'_'.$k1; ?>
                         </td>
                        </tr>
                      <?php }
                    }
                    if(!empty($additional_service->additional_service_new)) {
                        foreach ($additional_service->additional_service_new as $k1 => $v1) {
                        //$service_exist[] = $v1;
                        $service_existx[] = $v1; ?>
                          <tr>
                           <td> <?php echo $case->case_category; ?></td>
                           <td> <?php echo $case->case_type; ?></td>
                           <td> <?php echo $v1; ?></td>
                           <td> <?php if(empty($case->VP_Assistance)) { 
                          echo 'Self managed'; 
                        }
                        else {
                          if($v1 == 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor') {
                            echo '$'.$I_864_Cost;
                          }
                          else {
                            echo '$'.$I_864A_Cost;
                          }
                        } ?></td>
                           <td> Need to pay</th>
                           <td>
                              <?php 
                              if($v1 == 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor') {
                                echo '<input type="checkbox" name="additional_service_new[]" value="'.$I_864_Cost.'" class="needtopay">';
                              }
                              else {
                                echo '<input type="checkbox" name="additional_service_new[]" value="'.$I_864A_Cost.'" class="needtopay">';
                              }
                              ?>
                           </td>
                          </tr>
                        <?php }
                      }
                    ?>
                    <?php if(!in_array('I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor', $service_existx)) { ?>
                      <tr>
                        <td> <?php echo $case->case_category; ?></td>
                        <td> <?php echo $case->case_type; ?></td>
                        <td> I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor</td> 
                        <td> ${{$I_864_Cost}}</td> 
                        <td> Request</td>
                        <td> <input type="checkbox" name="additional_service_new[]" data-cost="{{$I_864_Cost}}" value="I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor"></td>
                      </tr>
                      <?php } ?>
                      <?php if(!in_array('I-864A, Contract Between Sponsor and Household Member', $service_existx)) { ?>
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
                        <strong>Affidavit /Declaration</strong>
                      </td>
                    </tr>
                    <?php 
                    $service_exist1 = ''; 
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
                         <td> <?php if(empty($case->VP_Assistance)) { 
                          echo 'Self managed'; 
                        }
                        else { echo '$'.$I_Affidavit_Cost; 
                      } ?></td>
                         <td> 
                          <?php 
                              if($additional_service->declaration->status) {
                                echo 'Selected';
                              }
                              else {
                                echo 'Need to pay';
                              }
                            ?>
                         </th>
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
                            <a href="#" class="action_btn viewdocbtn" data-type="declaration_doc" data-index="{{$k1}}" title="View/Edit" data-toggle="tooltip" data-files="{{$additional_service->$kk1}}" style="margin-left: 40px;">
                              <img src="{{url('assets/images/icon')}}/Group 557.svg" />
                            </a>
                          <?php } else { ?>
                          <a href="#" class="action_btn viewdocbtn" data-type="declaration_doc" data-index="{{$k1}}" title="Upload" data-toggle="tooltip" data-files="" style="margin-left: 40px;">
                            <i class="fas fa-upload"></i>
                          </a>
                          <?php } ?>
                         </td>
                        </tr>
                      <?php 
                    }
                    } else { ?>
                      <tr>
                         <td> <?php echo $case->case_category; ?></td>
                         <td> <?php echo $case->case_type; ?></td>
                         <td> 
                         </td>
                         <td></td>
                         <td> 
                          
                         </th>
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
                          
                         </td>
                        </tr>
                    <?php }
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
                            Need to pay
                           </th>
                           <td>
                             <input type="checkbox" name="declaration_new[]" value="{{$I_Affidavit_Cost}}" class="needtopay">
                           </td>
                          </tr>
                        <?php 
                      }
                      } ?>
                      <tr>
                        <td colspan="6" class="text-right">
                          @csrf
                          <input type="hidden" name="case_id" value="{{$case->id}}">
                          <input type="submit" name="Request" value="Request" class="btn btn-primary req_submit" style="display: none;">
                          <a href="#" class="btn btn-primary payfortranlation" style="display: none;">Pay</a>
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
<div id="PayForTranslation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Pay Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Pay For Additional Service</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('firm/case/pay_additional_service') }}" method="post" id="payment-form" enctype="multipart/form-data"> 
          <div class="payment-form-card" id="card-element">
               <h2 class="provided_cost"></h2>
               <?php $pst = ''; if(!empty($card)) {
                $pst = 'display:none;';
                echo '<div class="row"><div class="col-md-12 text center">Pay with existing card</div></div>';
                foreach ($card as $k => $v) {
                ?>
               <div class="row">
                 <div class="col-md-8">
                   <label>
                     <input type="text" value="***********<?php echo $v->last4; ?>" class="form-control" readonly />
                     <input type="checkbox" value="<?php echo $v->id; ?>" name="card_source" style="display: none;"/>
                   </label>
                 </div>
                 <div class="col-md-4">
                   <input value="Pay" type="submit" class="paywith_existing btn btn-primary">
                 </div>
               </div>
               <?php }
               echo '<br><div class="row"><div class="col-md-12 text center"><a href="#" class="btn btn-primary paywithnewbtn">Pay with new card</a></div></div>';
               } ?>
               <div class="newcardwrapper" style="{{$pst}}">
                 <div class="row">
                  <div class="col-md-12"><div class="payment-input">
                    <input type="text" placeholder="Card Number" size="20" data-stripe="number"/></div></div>
                 </div>
                 <div class="row">
                  <div class="col-md-6 col-sm-6"><div class="payment-input">
                    <input type="text" placeholder="Expiring Month" data-stripe="exp_month"/>
                  </div>
                  </div>
                  <div class="col-md-6 col-sm-6"><div class="payment-input">
                    <input type="text" placeholder="Expiring Year" size="2" data-stripe="exp_year">
                  </div>
                </div>
                  
                 </div>
                 <div class="row">
                  <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="CVV Code" size="4" data-stripe="cvc"/></div></div>
                  <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="Postal Code" size="6" data-stripe="address_zip"/></div></div>
                  
                 </div>
                 <div class="row">
                  <div class="col-md-12 col-sm-12"><div class="payment-input1"><input type="checkbox" name="savecard" value="1" style="display: inline-block;" />
                    <label>do you want to save this card?</label>
                  </div></div>
                 </div>
                <div class="row">  
                  <div class="col-md-12 text-right">
                    @csrf
                    <label class="payment-errors text-warning"></label><br>
                    <input type="hidden" name="case_id"  value="{{$case->id}}">
                    <input type="hidden" name="service_name"  value="">
                    <input type="submit" name="save" value="Pay" class="submit btn btn-primary"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

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
              <input name="file" type="file" required="required" onchange="readURL(this);"/>
            </div>
            <div class="col-md-12">
                <ul class="selectedfiles"></ul>
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
@endsection
@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript"> 
function readURL(input) {
  console.log(input,'==============',input.files.length)
  for (var i = 0; i < input.files.length; i++) {
    var imgname = input.files[i].name.split('.');
    var ext = imgname[imgname.length-1];
    var src = "{{ asset('assets/images/icon') }}/"+ext+".png";
    var li = '<li><img src="'+src+'"/>';
      li += '<input name="filename[]" value="'+input.files[i].name+'" type="hidden"/>';
      li += '<label>'+input.files[i].name+'</label>';
      //li += '<a href="#" class="remove_file">x</a></li>';
    // var li = '<input type="file" name="rkfile[]" value="'+input.files[i]+'" />';
    $('.selectedfiles').html(li);
    //$('.rselectpicer').selectpicker();
    
  }
  
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            // $(document).find('#fileimg_')
            //     .attr('src', "{{ asset('assets/images/icon') }}/"+ext+".png")
            //     .width(90)
            //     .height(90).css('display', 'block');
        };

        reader.readAsDataURL(input.files[0]);
    }
}
$(document).on('click', '.convert_client_act', function(e){
  e.preventDefault();
  var file_type = $('select[name="file_type"]').val();
  var client_id = $('select[name="client_id"]').val();
  var case_id = $('select[name="case_id"]').val();
  var expiration_date = $('input[name="expiration_date"]').val();
  var csrf1 = $('input[name="_token"]').val();
  if(file_type != '') {
    $.ajax({
      url: "{{url('firm/document_request/setDataDocument1')}}",
      data: {file_type: file_type, client_id: client_id, case_id: case_id, _token: csrf1, expiration_date: expiration_date},
      dataType: 'json',
      type: 'post',
      async: false,
      success: function (data) {
        console.log('success===========',data);
        alert('Document request successfully!');
        window.location.href = window.location.href;
      },
      error: function (data) {
        alert('Document request successfully!');
        window.location.href = window.location.href;
      }
    });
  }
  else {
    alert('Please select document type');
  }
});
$(document).ready(function(){
  $(".paywithnewbtn").on('click', function(e){
      e.preventDefault();
      $('.newcardwrapper').slideToggle();
    });
  $('.paywith_existing').on('click', function() {
    console.log('1');
    $('input[name="card_source"]').prop('checked', false);
    $(this).closest('.row').find('input[type="checkbox"]').prop('checked', true);
  });
  $(document).on('click', '.viewdocbtn', function(e){
    e.preventDefault();
    var id = $(this).data('index');
    var type = $(this).data('type');
    $('input[name="doc_index"]').val(id);
    $('input[name="doc_type"]').val(type);
    var files = $(this).data('files');
    $('.selectedfiles').empty();
    if(files) {
      var f = files;
      var url = "{{asset('storage/app')}}/"+f; 
      f = f.replace('client_doc/', '');
      var imgname = url.split('.');
      var ext = imgname[imgname.length-1];
      var src = "{{ asset('assets/images/icon') }}/"+ext+".png";
      if(ext == 'png' || ext == 'jpg' || ext == 'jpeg') {
        src = url;
      }
      var li = '<li><a href="'+url+'"  target="_blank"><img src="'+src+'"/>'+f+'</a></li>';
      $('.selectedfiles').append(li);
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
  });
  $(document).on('click', '.remove_quantity_btn', function(e){
      e.preventDefault();
      var q = $('input[name=nvc_packet_quantity_new]').val();
      var q1 = $('input[name=nvc_packet_quantity_new]').attr('min');
      q--;
      if(q >= q1) {
        $('input[name=nvc_packet_quantity_new]').val(q);
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
  $(document).on('click', '.add_more_btn', function(e){
      e.preventDefault();
      $('.req_submit').show();
      $('#table tbody tr:last-child').before(r);
  });
  $(document).on('click', '.remove_more_btn', function(e){
    e.preventDefault();
    $(this).closest('.rmrow').remove();
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
  $(document).on('change', '.documents-list-box input:not(.needtopay)', function(){
    var v = $(this).attr('type');
    //if(v != 'checkbox') {
        $('.req_submit').show();
    //}  
  });
 $(document).on('click', '.payfortranlation', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var quote_cost = 0;
    $('#PayForTranslation form .needtopay').remove();
    $('.needtopay:checked').each(function() {
      quote_cost = quote_cost+parseInt($(this).val());
      $('#PayForTranslation form').append($(this).clone())
        // values.push($(this).val());
    });
    // var quote_cost = $(this).data('quote_cost');
    //$('input[name="paydocid"]').val(id);
    // $('input[name="service_name"]').val($(this).data('service_name'));
    $('.provided_cost').text('Transaction Cost : $'+quote_cost);
    $("#PayForTranslation").modal('show');
  });

  Stripe.setPublishableKey("{{ env('SRTIPE_PUBLIC_KEY') }}");

  $(function() {
    var $form = $('#payment-form');
    $form.submit(function(event) {
      if(!$('input[name="card_source"]').is(':checked')) {
        // Disable the submit button to prevent repeated clicks:
        $form.find('.submit').prop('disabled', true);

        // Request a token from Stripe:
        Stripe.card.createToken($form, stripeResponseHandler);

        // Prevent the form from being submitted:
        return false;
      }
    });
  });

  function stripeResponseHandler(status, response) {
    // Grab the form:
    var $form = $('#payment-form');

    if (response.error) { // Problem!

      // Show the errors on the form:
      $form.find('.payment-errors').text(response.error.message);
      $form.find('.submit').prop('disabled', false); // Re-enable submission

    } else { // Token was created!

      // Get the token ID:
      var token = response.id;

      // Insert the token ID into the form so it gets submitted to the server:
      // $form1 = $('#payment-form-res');
      // alert(token);
      $form.append($('<input type="hidden" name="stripeToken">').val(token));

      // Submit the form:
      $form.get(0).submit();
    }
  };
  $(document).on('change', '.documents-list-box input[type="checkbox"].needtopay', function(){
    if($('.documents-list-box input[type="checkbox"].needtopay').is(':checked')) {
      $('.payfortranlation').show();
    }
    else {
      $('.payfortranlation').hide();
    }
  });
//================ Edit user ============//

</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 