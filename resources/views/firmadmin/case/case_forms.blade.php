@extends('firmlayouts.admin-master')

@section('title')
Manage Forms
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
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
             <h2>Forms</h2>
             <?php if($firm->account_type == 'CMS') { ?>
             <a href="{{ url('firm/case/add_forms/')}}/{{$case->id}}" class="add-task-link">Add New Form</a>
             <?php } ?>
             <div class="documents-list-box">
              <div style="width: 220px; display: none;">
             <select class="family_arr" name="client_id" data-live-search="true">
                  <option value="">All</option>
                  <?php
                  if(!empty($client)) {
                    $cname = $client->first_name;
                    if(!empty($client->middle_name)) {
                      $cname .= ' '.$client->middle_name;
                    }
                    if(!empty($client->last_name)) {
                      $cname .= ' '.$client->last_name;
                    }
                    $sl = '';
                    if($uid == $client->user_id) {
                      $sl = 'selected="selected"';
                    }
                    echo '<option value="'.$client->user_id.'" '.$sl.'>'.$cname.'</option>';
                  } 
                  $farr = array();
                  if(!empty($family_alllist)) {
                    foreach ($family_alllist as $key => $value) {
                      if(!in_array($value->uid, $farr)) {
                        $farr[] = $value->uid;
                        $sl = '';
                        if($uid == $value->uid) {
                          $sl = 'selected="selected"';
                        }
                        echo '<option value="'.$value->uid.'" '.$sl.'>'.$value->name.'</option>';
                      }
                    }
                  }
                  ?>
                </select>
              </div>
             <div class="task-tabbtn-box">
              <div class="table-responsive table-invoice table-width1" style="overflow-x: hidden;">
                  <table class="table table-striped">
                    <tbody>
                      <tr>
                        <!-- <th>Case ID</th> -->
                        <th>Form Type</th>
                        <!-- <th>Client Name</th> -->
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      <?php 
                      if($case->case_category == 'NVC Packet/Consular Process') { ?>
                      <tr>
                        <td>DS-260, Immigrant Visa and Alien Registration Application (Online Only)</td>
                        <!-- <td>{{$cname}}</td> -->
                        <td>{{ GetCaseStatus($case->status) }}</td>
                        <td>
                          <?php if($case->status == 9 || empty($case->VP_Assistance)) { ?>
                          <a href="https://ceac.state.gov/IV/login.aspx" target="_blank" class="action_btn">
                              <img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" />
                            </a>
                            <?php } else { ?>
                              <a href="javascript:void(0);" class="action_btn" title="Edit" data-toggle="tooltip" onclick="alert('You can not edit form until TILA VP complete the case');">
                                  <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                              </a>
                            <?php } ?>
                        </td>
                      </tr>
                      <?php } ?>
                      @if ($client_information_forms->isEmpty())

                      @else
                      @foreach ($client_information_forms as $form)
                      <?php if($form->file_type != 'DS-260, Immigrant Visa and Alien Registration Application (Online Only)') { ?>
                      <tr>
                          <!-- <td>
                            #<?php echo $form->case_id; ?>
                          </td> -->
                          <td style="text-transform: capitalize;">
                            <?php echo str_replace('_', ' ', $form->file_type); ?>
                          </td>
                          <!-- <td>
                            {{$form->name}}
                          </td> -->
                          <td>{{ GetCaseStatus($case->status) }}</td>
                          <td>
                              <?php 
                              //pre($form);
                              $rform = $form; 
                              unset($rform->birth_address);
                              unset($rform->client_aliases);
                              ?>
                              <?php if($case->status == 9 || empty($case->VP_Assistance)) { ?>
                              @if($form->status1 == 1)
                                  <a href="{{ url('editpdf') }}/{{$form->info_id}}" target="_blank" class="action_btn document_file" data-file="{{asset('storage/app')}}/{{$form->file}}" data-id="{{$form->info_id}}" title="Edit" data-toggle="tooltip">
                                  <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                                  </a>
                                  <!-- <a href="#" class="action_btn update_form_status" data-id="{{$form->info_id}}" data-status="0">
                                    <img src="{{url('assets/images/icon/gray-right.png')}}">
                                  </a> -->
                              @endif
                              @if($form->status1 == 0)
                              <?php
                              
                              //unset($rform->information);
                              unset($rform->residence_address);
                              ?>
                              <a href="{{ url('editpdf') }}/{{$form->info_id}}" target="_blank" class="action_btn document_file" data-file="{{asset('storage/app')}}/{{$form->file}}" data-id="{{$form->info_id}}" title="Edit" data-toggle="tooltip">
                                  <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                              </a>
                              @endif
                            <?php } else { ?>
                              <a href="javascript:void(0);" class="action_btn" title="Edit" data-toggle="tooltip" onclick="alert('You can not edit form until TILA VP complete the case');">
                                  <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                              </a>
                            <?php } ?>
                            <?php if($case->status == 9 || empty($case->VP_Assistance)) { ?>
                            <a href="{{asset('storage/app')}}/{{$form->file}}" class="action_btn" download title="Download" data-toggle="tooltip">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;"><g>
                                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"></path>
                                    </g>
                                  </svg>
                            </a>
                            <a href="javascript:void(0);" class="action_btn" title="Print" data-toggle="tooltip" onclick="printJS('{{asset('storage/app')}}/{{$form->file}}')">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;"><g>
                                    <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z">
                                    </path>
                                    </g>
                                  </svg>
                            </a>
                            <?php } else { ?>
                              <a href="javascript:void(0);" class="action_btn" title="Download" data-toggle="tooltip" onclick="alert('You can not download form until TILA VP complete the case');">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;"><g>
                                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"></path>
                                    </g>
                                  </svg>
                            </a>
                            <a href="javascript:void(0);" class="action_btn" title="Print" data-toggle="tooltip" onclick="alert('You can not print form until TILA VP complete the case');">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;"><g>
                                    <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z">
                                    </path>
                                    </g>
                                  </svg>
                            </a>
                            <?php } ?>
                            

                          </td>
                      </tr>
                    <?php } ?>
                      @endforeach
                      @endif
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
  <form action="" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">
      <div class="col-md-4">
        Type * 
      </div>
      <div class="col-md-8">
        <select name="file_type" class="form-control" required="required">
          <option value="">Select One</option>
          <option value="biographic_information">Biographic Information</option>
          <option value="address_history">Address History</option>
          <option value="last_address_outside_the_US">Last address outside the US</option>
          <option value="family_history">Family History</option>
          <option value="information_about_your_parents">Information about your parents</option>
          <option value="information_about_your_children">Information about your children</option>
          <option value="employment_history">Employment History</option>
          <option value="immigration_history">Immigration History</option>
          <option value="criminal_history">Criminal History</option>
          <option value="miscellaneous">Miscellaneous</option>
        </select>
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-4">
        Owner *
      </div>
      <div class="col-md-8">
        <select name="client_id" class="form-control client_Cases" required="required">
          <option value="">Select One</option>
          
        </select>
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-4">
        Case * 
      </div>
      <div class="col-md-8">
        <select name="case_id" class="form-control CaseNumber" required="required">
          <option value="">Select One</option>
        </select>
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
      <div class="col-md-12 text-right">
        @csrf
        <input type="submit" name="save" value="Save" class="btn btn-primary convert_client_act"/>
      </div>
    </div>
  </form>
  <div class="row uploadFileswrap" >
    <div class="col-md-12">
        <ul class="uploadFiles"></ul>
        <input type="hidden" name="id" class="doc_id" value="">
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
              <input type="hidden" name="paydocid"  value="{{$case->client_id}}">
              <input type="submit" name="save" value="Pay" class="submit btn btn-primary"/>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
<button href="#" id="fire-modal-2" class="btn btn-primary trigger--fire-modal-2 trigger--fire-modal-1" style="display: none;">Request Document <i class="fas fa-plus"></i></button>
<div id="form_editor" style="display: none;"></div>
@endsection
@push('footer_script')
<style type="text/css">
      #form_editor {
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 9999999999;
      }
    </style>
<script src="{{ asset('assets/js/print.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<?php if($uid) { ?>
<script src="{{ asset('assets/WebViewer/lib/webviewer.min1.js')}}"></script>
<?php } else { ?>
<script src="{{ asset('assets/WebViewer/lib/webviewer.min.js')}}"></script>
<?php } ?>
<script src="{{ asset('assets/WebViewer/samples/old-browser-checker.js')}}"></script>
<script src="{{ asset('assets/WebViewer/samples/forms/form-fields/form-fields.js')}}?v=<?php echo rand(); ?>"></script>

<script type="text/javascript">
  function load_document(e, pdf_data, cdata) {
    window.cdata = cdata;
    window.PDFDATA = pdf_data;
    window.pdfUrl = e.getAttribute('data-file');
    window.id = e.getAttribute('data-id');
    window.token = document.querySelector('input[name="_token"]').value;
    document.getElementById('form_editor').style.display = 'block';
    WebViewer.getInstance().loadDocument(pdfUrl);   
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
$(document).on('click', '.update_form_status', function(e){
  e.preventDefault();
  var doc_id = $(this).data('id');
  var status = $(this).data('status');
  var csrf1 = $('input[name="_token"]').val();
  $.ajax({
    url: "{{url('firm/forms/update_form_status')}}",
    data: {doc_id: doc_id, status: status, _token: csrf1},
    dataType: 'json',
    type: 'post',
    async: false,
    success: function (data) {
      console.log('success===========',data);
      if(status) {
        alert('Form mark as complete successfully!');  
      }
      else {
        alert('Form mark as incomplete successfully!');  
      }
      window.location.href = window.location.href;
    },
    error: function (data) {
      if(status) {
        alert('Form mark as complete successfully!');  
      }
      else {
        alert('Form mark as incomplete successfully!');  
      }
      window.location.href = window.location.href;
    }
  });
});
$(document).ready(function(){
  $('.completeform').on('click', function(){
      var v = $('.doc_id').val();
      var status = $('.select_status').val();
      var case_id = $('.case_id').val();
      $.ajax({
       type:"get",
       url:"{{ url('firm/document_request/completeDocument') }}/"+v,
       data: { status: status, case_id: case_id },
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
    $('.family_arr').selectpicker();
    $('select.family_arr').on('change', function() {
      var v = $(this).val();
      var cid = "{{$case->id}}";
      if(v == '') {
        var url = "{{ url('firm/case/case_forms') }}/"+cid;
      }
      else {
        var url = "{{ url('firm/case/case_forms') }}/"+cid+'/'+v;
      }
      window.location.href = url;
    })
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