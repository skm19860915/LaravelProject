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
  #table tbody tr td:nth-child(1) {
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
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/case/allcase') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
            <div class="profile-new-client">
             <h2>Documents</h2>
             <?php 
             if(empty($case->VP_Assistance)) { ?>
              <a href="#" class="add-task-link" id="fire-modal-2">Add a Document</a>
             <?php } ?>
             <div class="documents-list-box">
             
             <div class="task-tabbtn-box">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Case Documents</a>
                 </li>
                <li class="nav-item">
                  <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Requested Document</a>
                </li> 
                <li class="nav-item">
                  <a class="nav-link" id="ClientSpecific-tab" data-toggle="tab" href="#ClientSpecific" role="tab" aria-controls="ClientSpecific" aria-selected="true">General Documents</a>
                </li>     
              </ul>
             </div>
             
             <div class="task-tabcontent-box">
              <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="table-responsive table-invoice table-width-1">
                  <select class="form-control family_arr" style="width: 220px;">
                    <option value="">All</option>
                    <?php
                    if(!empty($client)) {
                      $cn = $client->first_name.' '.$client->middle_name.' '.$client->last_name;
                      $cuid = $client->user_id;
                      echo "<option value='$cuid'>$cn</option>"; 
                    }
                    $farr = array();
                    if(!empty($family_alllist)) {
                      foreach ($family_alllist as $key => $value) {
                        if(!in_array($value->uid, $farr)) {
                          $farr[] = $value->uid;
                          echo '<option value="'.$value->uid.'">'.$value->name.'</option>';
                        }
                      }
                    }
                    ?>
                  </select>
                  <table class="table table table-bordered table-striped"  id="table" >
                    <thead>
                      <tr>
                       <th style="display: none;"> Id</th>
                       <!-- <th> Case ID </th> -->
                       <th> Type</th>
                       <th> Due Date</th>
                       <th> Assign To </th>
                       <th> Status</th>
                       <th style="width: 135px;"> Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>    
              <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                  <div class="col-md-12">
                    <div class="family-main-border-box">
                      <div class="table-responsive table-invoice all-case-table table-width-2" style="overflow-x: hidden;">
                        <table class="table table table-bordered"  id="tablefamily" >
                          <thead>
                            <tr>
                              <th>Document</th>
                              <th> Due Date</th>
                              <th style="width: 200px;">Family</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            if(!empty($CaseTypes[0]->Required_Documentation_en)) {
                              foreach ($CaseTypes[0]->Required_Documentation_en as $key => $v) { ?>
                                <tr>
                                  <td>{{$v}}</td>
                                  <td>
                                    <input type="text" class="form-control datepicker duedate" placeholder="Due Date">
                                  </td>
                                  <td>
                                    <select class="selectpicker existing_members" multiple data-live-search="true">
                                      <!-- <option value="">Select</option> -->
                                    <?php 
                                    if(!empty($client)) {
                                        $cn = $client->first_name.' '.$client->middle_name.' '.$client->last_name;
                                        $cuid = $client->user_id;
                                        echo "<option value='$cuid'>$cn</option>"; 
                                      }
                                      if(!empty($existing_members)) {
                                        foreach ($existing_members as $k => $f) {
                                          echo '<option value="'.$f->id.'">'.$f->name.'</option>';
                                        } 
                                      } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <a href="#" class="btn btn-primary request_doc" data-case_id="{{$case->id}}" data-doc="{{$v}}">Request</a>
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
              <div class="tab-pane fade" id="ClientSpecific" role="tabpanel" aria-labelledby="ClientSpecific-tab">
                <div class="row">
                  <div class="col-md-2 col-sm-2 col-xs-6">
                    <div class="documents-pdf-box">
                     <div class="documents-pdf-icon">
                      <button href="#" class="btn btn-primary clientdocument">Browse
                      </button>
                     </div>
                   </div>
                 </div>
                 <?php
                  if(!empty($client_doc)) {
                    foreach ($client_doc as $k => $v) { 
                      $dlink = $v->document;
                      ?>
                     <div class="col-md-2 col-sm-2 col-xs-6">
                      <div class="documents-pdf-box">
                       <div class="documents-pdf-icon">
                        <a href="{{asset('storage/app')}}/<?php echo $dlink; ?>" download style="position: static;" data-toggle="tooltip" title="Click here to download">
                          <img src="{{ url('/') }}/assets/images/icon/files-and-folders-con.svg">
                        </a>
                        <a href="#" data-toggle="tooltip" title="<?php echo $v->description; ?>">
                          <i class="fa fa-info"></i>
                        </a>
                       </div>
                       
                        <a href="{{asset('storage/app')}}/<?php echo $dlink; ?>" download>
                          <div class="name-pdf text-center"><?php echo $v->title; ?></div>
                        </a>
                        
                      </div>
                     </div>
                    <?php  
                    }
                  }
                  ?>               
                </div>              
              </div>        
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

<div class="modalformpart" id="modal-form-part" style="display: none;">
  <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">
      <div class="col-md-4">
        Type * 
      </div>
      <div class="col-md-8">
        <input name="file_type" class="form-control" required="required">
          <!-- <option value="">Select One</option> -->
          <?php 
          if(!empty($CaseTypes[0]->Required_Documentation_en)) {
            foreach ($CaseTypes[0]->Required_Documentation_en as $key => $v) {
              // if(in_array(trim($v), $already_requested) && false) {
              //   echo "<option value='$v' disabled='disabled'>$v</option>"; 
              // }
              // else {
                //echo "<option value='$v'>$v</option>"; 
              // }
               
            } 
          }
          ?>
        <!-- </select> -->
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-4">
        Due Date * 
      </div>
      <div class="col-md-8">
        <input type="text" placeholder="Due Date" name="expiration_date" class="form-control datepicker">
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-4">
        Request to
      </div>
      <div class="col-md-8">
        <select name="family_id" class="selectpicker rkselect" multiple="multiple" required="required" data-live-search="true">
          <?php 
          $farr = array();
          if(!empty($clientrr)) {
            $cn = $clientrr->first_name.' '.$clientrr->middle_name.' '.$clientrr->last_name;
            $cuid = $clientrr->user_id;
            echo "<option value='$cuid'>$cn</option>"; 
          }
          if(!empty($family_alllist)) {
            foreach ($family_alllist as $key => $v) {
              if(!in_array($v->uid, $farr)) {
                $farr[] = $v->uid;
                echo "<option value='".$v->uid."'>".$v->name."</option>";
              } 
            } 
          }
          ?>
        </select>
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-12 text-right">
        @csrf
        <input name="case_id" type="hidden" value="{{$case->id}}" required="required">
        <input name="clientr_id" type="hidden" value="{{$client->id}}" required="required">
        <input type="submit" name="save" value="Save" class="btn btn-primary convert_client_act"/>
      </div>
    </div>
  </form>
  <div class="row uploadFileswrap" >
    <div class="col-md-12">
        <ul class="uploadFiles"></ul>
        <input type="hidden" name="id" class="doc_id" value="">
        <?php 
        if(empty($case->VP_Assistance)) { ?>
        <div class="row">
          <div class="col-md-4">
            Status
          </div>
          <div class="col-md-8">
            <select class="form-control select_status">
              <option value="0" disabled="disabled">Requested</option>
              <option value="1">Submitted</option>
              <option value="2">Accepted</option>
              <!-- <option value="3">Requires Translation</option> -->
              <option value="4">Rejected</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-right">
            <br>
            <input type="hidden" name="case_id" class="case_id" value="{{$case->id}}">
            <input type="button" name="Complete" value="Update" class="btn btn-primary completeform"/>
          </div>
        </div>
        <?php } ?>
    </div>
  </div>
</div>
<!-- Modal -->
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
        <h4 class="modal-title">Pay For Translation</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('firm/case/pay_case_translation') }}/{{$case->client_id}}" method="get" id="payment-form" enctype="multipart/form-data"> 
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
              </div>
              <div class="row">  
                <div class="col-md-12 text-right">
                  @csrf
                  <label class="payment-errors text-warning"></label><br>
                  <input type="hidden" name="paydocid"  value="{{$case->client_id}}">
                  <input type="submit" name="save" value="Pay" class="submit btn btn-primary"/>
                </div>
              </div>
            </div>
        </form>
      </div>
    </div>

  </div>
</div>
<!-- Modal -->
<div id="UploadClinetDocument" class="modal fade" role="dialog">
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
        <form action="{{url('firm/case/setCaseDocument')}}" method="post" enctype="multipart/form-data">
          <div class="row">
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
            <br>
          <div class="row">
            <div class="col-md-12 fallback1">
              <input name="file[]" type="file" required="required"/>
            </div>
            <div class="col-md-12">
                <ul class="uploadFiles"></ul>
            </div>
          </div>
          <br>
          <div class="row">  
            <div class="col-md-12 text-right">
              @csrf
              <input type="hidden" name="case_id"  value="{{$case->id}}">
              <input type="submit" name="save" value="Save" class="btn btn-primary"/>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
<button href="#" id="fire-modal-2" class="btn btn-primary trigger--fire-modal-2 trigger--fire-modal-1" style="display: none;">Request Document <i class="fas fa-plus"></i></button>
@endsection
@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
var index_url = "{{url('firm/case/getCaseDataDocument')}}/{{$case->id}}";
var srn = 0;
$(window).on('load', function() {
  var fid = 0;
  getdocdata(fid);
  function getdocdata(fid) {
    if(fid) {
      index_url = "{{url('firm/case/getFamilyDataDocument')}}/{{$case->id}}/"+fid;
    }
    else {
      index_url = "{{url('firm/case/getCaseDataDocument')}}/{{$case->id}}";
    }
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
        "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "family_id": fid,
            }
      },
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        // { data: 'case_id', name: 'case_id'},
        { data: 'document_type', name: 'document_type'},
        { data: 'expiration_date', name: 'expiration_date'},
        { data: 'name', name: 'name'},
        { data: 'status', name: 'status'},
        { data: null,
          render: function(data){
            var view_button='';
            if(data.dstatus1 == 0) {
              view_button += ' <a href="{{url('firm/case/upload_documents')}}/'+data.case_id+'/'+data.family_id+'" data-id="'+data.id+'" data-case_id="'+data.case_id+'" data-status="'+data.dstatus1+'" data-files="'+data.document+'" class="action_btn" title="Upload" data-toggle="tooltip"><i class="fas fa-upload"></i></a>'; 
            }
            else {
            view_button += ' <a href="{{url('firm/case/upload_documents')}}/'+data.case_id+'/'+data.family_id+'" data-id="'+data.id+'" data-case_id="'+data.case_id+'" data-status="'+data.dstatus1+'" data-files="'+data.document+'" class="action_btn" title="Upload" data-toggle="tooltip"><i class="fas fa-upload"></i></a>';
             var f = $("<textarea/>").html(data.document).text();
             f = JSON.parse(f);
             var url = "{{asset('storage/app')}}/"+f[0];
             view_button += '<a href="'+url+'" data-id="'+data.id+'" data-case_id="'+data.case_id+'" data-status="'+data.dstatus1+'" data-files="'+data.document+'" class="action_btn viewdocbtn4" title="View" data-toggle="tooltip" download><img src="{{url('assets/images/icon')}}/Group 557.svg" /></a>';
            }
              if(data.dstatus1 == 3) {
                if(data.quote == 0) {
                view_button += ' <a href="{{url('firm/case/Case_Request_Quote')}}/'+data.id+'" data-id="'+data.id+'" data-case_id="'+data.case_id+'" data-status="'+data.dstatus1+'" data-files="'+data.document+'" class="action_btn" title="Request a Quote" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/ticket@2x.png" /></a>';


                view_button += ' <a href="{{url('firm/case/upload_documents')}}/'+data.case_id+'/'+data.family_id+'" data-id="'+data.id+'" data-case_id="'+data.case_id+'" data-status="'+data.dstatus1+'" data-files="'+data.document+'" class="action_btn" title="Upload" data-toggle="tooltip"><i class="fas fa-upload"></i></a>'; 
                }
                if(data.quote == 2) {
                  view_button += ' <a href="#" data-id="'+data.id+'" data-case_id="'+data.case_id+'" data-status="'+data.dstatus1+'" data-files="'+data.document+'" data-quote_cost="'+data.quote_cost+'" class="action_btn payfortranlation" title="Pay For Translation" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/ticket@2x.png" /></a>'; 
                }
              }
              return view_button;

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
  }
  $('.family_arr').on('change', function(){
    fid = $(this).val();
    getdocdata(fid);
  });
 });  
$(document).on('click', '.convert_client_act', function(e){
  e.preventDefault();
  var file_type = $('input[name="file_type"]').val();
  var client_id = $('input[name="clientr_id"]').val();
  var case_id = $('input[name="case_id"]').val();
  var expiration_date = $('input[name="expiration_date"]').val();
  var csrf1 = $('input[name="_token"]').val();
  var family_id = $('select[name="family_id"]').val();
  if(file_type != '') {
    if(family_id != '') {
      $.ajax({
        url: "{{url('firm/document_request/setDataDocument1')}}",
        data: {file_type: file_type, client_id: client_id, case_id: case_id, _token: csrf1, expiration_date: expiration_date, family_id:family_id},
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
      alert('Please select request to');
    }
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
  $('.datepicker').daterangepicker({
      timePicker: false,
      singleDatePicker: true,
      endDate: moment().startOf('hour').add(32, 'hour'),
      locale: {
        format: 'MM/DD/YYYY'
      },
      minDate: new Date()
  })
  $('.completeform').on('click', function(){
      var v = $('.doc_id').val();
      var status = $('.select_status').val();
      var case_id = $('.case_id').val();
      $.ajax({
       type:"get",
       url:"{{ url('firm/document_request/completeDocument') }}/"+v,
       data: { status: status, case_id: case_id},
       success:function(res)
       {       

        window.location.href = window.location.href;
        
      }

    });
    });
    $('#fire-modal-2').on('click', function(){
      $('.daterangepicker.dropdown-menu').css('z-index', 99999);
      $('.modalformpart > form').show();
      $('.uploadFileswrap').hide();
    });
    $('.client_Cases').on('change', function(){
      var v = $(this).find(':selected').data('user_id');
      $.ajax({
       type:"get",
       url:"{{ url('firm/document_request/client_Cases') }}/"+v,
       success:function(res)
       {       
        if(res)
        {
          $(".CaseNumber").empty();
          $(".CaseNumber").append('<option>Select One</option>');
          $.each(res,function(key,value){
            $(".CaseNumber").append('<option value="'+key+'">'+value+'</option>');
          });
        }
      }

    });
    });
    $('.paywith_existing').on('click', function() {
      console.log('1');
      $('input[name="card_source"]').prop('checked', false);
      $(this).closest('.row').find('input[type="checkbox"]').prop('checked', true);
    });
    $('.request_doc').on('click', function(e){
      e.preventDefault();
      var family_id = $(this).closest('tr').find('select.existing_members').val();
      var case_id = $(this).data('case_id');
      var doc = $(this).data('doc');
      var duedate = $(this).closest('tr').find('.duedate').val();
      var csrf1 = $('input[name="_token"]').val();
      if(family_id == '') {
        alert('Please select family');
        return false;
      }
      $.ajax({
        type:"post",
        url:"{{ url('firm/case/rquest_blueprint_documents') }}",
        data: {_token: csrf1, family_id: family_id, case_id: case_id, doc: doc, duedate: duedate},
        success:function(res)
        {       
          alert('Document requested successfully');
          window.location.href = window.location.href;
        }
      });
    });
  });
 $("#fire-modal-2").fireModal({title: 'Request Document', body: $("#modal-form-part"), center: true});  
 // $("#fire-modal-4").fireModal({title: 'Request Document', body: $("#modal-form-part1"), center: true}); 
 $(document).on('click', '.viewdocbtn', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('.doc_id').val(id);
    var files = $(this).data('files');
    var status = $(this).data('status');
    var case_id = $(this).data('case_id');
    $('.case_id').val(case_id);
    $('.select_status').val(status);
    $('.uploadFiles').empty();
    if(files) {
        for (var i = 0; i < files.length; i++) {
            var f = files[i];
            var url = "{{asset('storage/app')}}/"+f; 
            f = f.replace('client_doc/', '');
            var li = '<li><a href="'+url+'"  target="_blank">'+f+'</a></li>';
            $('.uploadFiles').append(li);
        }
      $('#fire-modal-2').trigger('click');
      $('.daterangepicker.dropdown-menu').css('z-index',0);
      $('.modalformpart > form').hide();
      $('.uploadFileswrap').show();  
    }
    else {
      alert('Document not Submitted');
    }
    
  });
   $(document).on('click', '.viewselftuploadbtn', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      $('.uploaddoc_id').val(id);
      $('.uploadFiles').empty();
      $("#UploadTranslatedDocument").modal('show');
    });
   $(document).on('click', '.payfortranlation', function(e){
      e.preventDefault();
      var id = $(this).data('id');
      var quote_cost = $(this).data('quote_cost');
      $('input[name="paydocid"]').val(id);
      $('input[name="client_id"]').val($(this).data('client_id'));
      $('.provided_cost').text('Translation Cost : $'+quote_cost);
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
 $(document).on('click', '.clientdocument', function(e){
    e.preventDefault();
    $("#UploadClinetDocument").modal('show');
  });

//================ Edit user ============//

</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 