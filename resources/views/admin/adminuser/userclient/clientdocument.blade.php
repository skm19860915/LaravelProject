@extends('layouts.admin-master')

@section('title')
View client
@endsection
@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
    display: none;
}    
</style>
@endpush 
@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('admin.adminuser.userclient.client_header') 
<!--new-header Close-->
  
  <div class="section-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/client') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
           
            <div class="profile-new-client">
              <h2>Documents</h2>
              <div class="documents-list-box">
              
             <div class="task-tabbtn-box">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
               <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Requested Documents</a>
               </li>
               <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Client Documents</a>
               </li>              
              </ul>
             </div>
             
             <div class="task-tabcontent-box">
              <div class="tab-content" id="myTabContent">
              
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="table-responsive table-invoice">
                  <table class="table table table-bordered table-striped"  id="table" >
                    <thead>
                      <tr>
                       <th style="display: none;"> Id</th>
                       <!-- <th> Case ID </th> -->
                       <!-- <th> Name </th> -->
                       <th> Type</th>
                       <th> Due Date</th>
                       <!-- <th> Created</th> -->
                       <th> Status</th>
                       <th style="width: 135px;"> Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
               <div class="row">
               
                </div>
              </div>
              
              
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
                    $dlink = $v->document; ?>
                   <div class="col-md-2 col-sm-2 col-xs-6">
                    <div class="documents-pdf-box">
                     <a href="{{url('admin/userclient/deletedoc')}}/{{$client->user_id}}/{{$v->id}}" class="action_btn customedit_btn" onclick="return window.confirm('Are You Sure to delete this document?');" data-toggle="tooltip" title="Delete Document" style="right: 20px;top: 5px;padding-top: 1px;">
                       <img src="{{ url('/') }}/assets/images/icons/case-icon3.svg">
                     </a>
                     <div class="documents-pdf-icon">
                      <a href="{{asset('storage/app')}}/<?php echo $dlink; ?>" download style="position: static;">
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
              if(in_array(trim($v), $already_requested) && false) {
                // echo "<option value='$v' disabled='disabled'>$v</option>"; 
              }
              else {
                // echo "<option value='$v'>$v</option>"; 
              }
               
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
          if(!empty($client)) {
            $cn = $client->first_name.' '.$client->middle_name.' '.$client->last_name;
            $cuid = $client->user_id;
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
        <input name="client_id" type="hidden" value="{{$client->user_id}}" required="required">
        <input name="case_id" type="hidden" value="" required="required">
        <input type="submit" name="save" value="Save" class="btn btn-primary convert_client_act"/>
      </div>
    </div>
  </form>
  <div class="row uploadFileswrap" >
    <div class="col-md-12">
        <ul class="uploadFiles"></ul>
        <input type="hidden" name="id" class="doc_id" value="">
        <input type="hidden" name="id" class="doc_id" value="">
        <div class="row">
          <div class="col-md-4">
            Status
          </div>
          <div class="col-md-8">
            <select class="form-control select_status">
              <option value="0" disabled="disabled">Requested</option>
              <option value="1">Submitted</option>
              <option value="2">Accepted</option>
              <option value="3">Requires Translation</option>
              <option value="4">Rejected</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-right">
            <br>
            <input type="hidden" name="case_id" class="case_id">
            <input type="button" name="Complete" value="Update" class="btn btn-primary completeform"/>
          </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="UploadTranslatedDocument" class="modal fade" role="dialog">
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
        <form action="{{url('firm/document_request/setDataDocument1')}}" method="post" enctype="multipart/form-data" class="dropzone1" id="mydropzone1">
          <div class="row">
            <!-- <div class="col-md-12">
              File * 
            </div> -->
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
              <input type="hidden" name="id" class="uploaddoc_id" value="">
              <input type="hidden" name="client_id"  value="{{$client->id}}">
              <input type="submit" name="save" value="Save" class="btn btn-primary"/>
            </div>
          </div>
        </form>
      </div>
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
        <form action="{{ url('firm/document_request/pay_for_translation') }}/{{$client->id}}" method="get" id="payment-form" enctype="multipart/form-data"> 
          <div class="payment-form-card" id="card-element">
               <h2 class="provided_cost"></h2>
               <?php if(!empty($card)) {
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
               echo '<div class="row"><div class="col-md-12 text center">OR, Pay with new card</div></div>';
               } ?>
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
               

               
              </div>
          <div class="row">  
            <div class="col-md-12 text-right">
              @csrf
              <input type="hidden" name="paydocid"  value="{{$client->id}}">
              <input type="submit" name="save" value="Pay" class="submit btn btn-primary"/>
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
        <form action="{{url('admin/userclient/Set_Client_Document')}}" method="post" enctype="multipart/form-data">
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
              <input type="hidden" name="client_id"  value="{{$client->id}}">
              <input type="hidden" name="uid"  value="{{$client->user_id}}">
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
<script type="text/javascript">
var index_url = "{{url('admin/userclient/getDataClientDocument')}}/{{$client->id}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'did', name: 'did'},
        // { data: 'case_id', name: 'case_id'},
        // { data: 'name', name: 'name'},
        { data: 'document_type', name: 'document_type'},
        { data: 'expiration_date', name: 'expiration_date'},
        // { data: 'created_at', name: 'created_at'},
        { data: 'dstatus', name: 'dstatus'},
        { data: null,
          render: function(data){
            
            var view_button = '<a href="#" data-id="'+data.did+'" data-case_id="'+data.case_id+'" data-status="'+data.dstatus1+'" data-files="'+data.document+'" class="action_btn viewdocbtn" title="View" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/Group 557.svg" /></a>';

            if(data.dstatus1 != 0) {
              var f = $("<textarea/>").html(data.document).text();
              f = JSON.parse(f);
              var url = "{{asset('storage/app')}}/"+f[0]; 
              //view_button += ' <a href="'+url+'" class="action_btn viewdocbtn4" title="View" data-toggle="tooltip" download><img src="{{url('assets/images/icon')}}/Group 557.svg" /></a>';
            }
            view_button += ' <a href="{{url('admin/userclient/editclientdocument')}}/{{$client->user_id}}/'+data.did+'" class="action_btn" title="Edit Request" data-toggle="tooltip"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>';
 
            view_button += ' <a href="{{url('admin/userclient/uploadclientdocument')}}/{{$client->user_id}}/'+data.did+'" class="action_btn" data-toggle="tooltip" title="Upload Document"><i class="fas fa-upload"></i></i></a>';
            return view_button;
              return view_button;

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
 });  
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
  $('.completeform').on('click', function(){
      var v = $('.doc_id').val();
      var status = $('.select_status').val();
      var case_id = $('.case_id').val();
      $.ajax({
       type:"get",
       url:"{{ url('admin/document_request/completeDocument') }}/"+v,
       data: { status: status, case_id: case_id },
       success:function(res)
       {       
        var url = window.location.href;
        url = url.replace('#', '');
        window.location.href = url;
        
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
  });
  $("#fire-modal-2").fireModal({title: 'Request Document', body: $("#modal-form-part"), center: true});
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