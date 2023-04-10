@extends('firmlayouts.admin-master')

@section('title')
Create client family
@endsection

@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('firmadmin.case.case_header')
<!--new-header Close-->
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/case/create_case_family') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="back-btn-new">
            <a href="{{ url('firm/case/case_family') }}/{{$case->id}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-header">          
            <h4>Member : {{$family->name}}</h4>
          </div>
          <div class="card-body">
            <div class="profile-new-client">
             <h2>Forms</h2>
             <a href="{{ url('firm/case/add_case_family_forms/')}}/{{$id}}/{{$family->id}}" class="add-task-link">Add New Form</a>
             
             <div class="documents-list-box">
             
             <div class="task-tabbtn-box">
              <div class="table-responsive table-invoice">
                  <table class="table table-striped">
                    <tbody>
                      <tr>
                        <!-- <th>Case ID</th> -->
                        <th>Form Type</th>
                        <th>Client Name</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      @if ($family_information_forms->isEmpty())

                      @else
                      @foreach ($family_information_forms as $form)
                      <tr>
                          <!-- <td>
                            #<?php echo $form->case_id; ?>
                          </td> -->
                          <td style="text-transform: capitalize;">
                            <?php echo str_replace('_', ' ', $form->file_type); ?>
                          </td>
                          <td>
                            {{$form->name}}
                          </td>
                          <td class="font-weight-600">
                              @if($form->status1 == 0)
                                  Incomplete
                              @elseif($form->status1 == 1)
                                  Complete
                              @endif
                          </td>
                          <td>
                              <?php 
                              //pre($form);
                              $rform = $form; 
                              unset($rform->birth_address);
                              unset($rform->client_aliases);
                              ?>
                              <?php // if($case->status != 6) { ?>
                              @if($form->status1 == 1)
                                  <a href="#form_editor" class="action_btn document_file" data-file="{{asset('storage/app')}}/{{$form->file}}" data-id="{{$form->info_id}}" onclick="load_document(this, '{{$form->information}}', '{{$rform}}')">
                                  <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                              </a>
                              <a href="#" class="action_btn update_form_status" data-id="{{$form->info_id}}" data-status="0">
                                    <img src="{{url('assets/images/icon/gray-right.png')}}">
                                  </a>
                              @endif
                              @if($form->status1 == 0)
                              <?php 
                              unset($rform->information);
                              unset($rform->residence_address);
                              ?>
                              <a href="#form_editor" class="action_btn document_file" data-file="{{asset('storage/app')}}/{{$form->file}}" data-id="{{$form->info_id}}" onclick="load_document(this, null, '{{$rform}}')">
                                  <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                              </a>
                              <a href="#" class="action_btn update_form_status" data-id="{{$form->info_id}}" data-status="1">
                                <img src="{{url('assets/images/icon/right-green-sign.svg')}}">
                              </a>
                              @endif
                            <?php // } ?>
                          </td>
                      </tr>
                      @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
             
              
             </div>
                           
           </div>
          </div>
          </div>
        </form>
      </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>
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
<script src="{{ asset('assets/WebViewer/lib/webviewer.min.js')}}"></script>
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
$(document).ready(function(){
  $('.phone_us').mask('(000) 000-0000');
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
</script>
@endpush