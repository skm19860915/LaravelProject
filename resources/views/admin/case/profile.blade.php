@extends('layouts.admin-master')

@section('title')
View Case
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
@endpush

@section('content')
<section class="section client-listing-details task-new-header">
<!--new-header open-->
  @include('admin.case.case_header')
<!--new-header Close-->

  <div class="section-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card">        
            <div class="card-header">
              <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
                <a href="{{ url('firm/case') }}">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
               </div>
             </div>
            <div class="card-body">
              <div class="profile-new-client">
               <h2>Questionnaire</h2>
               <div class="documents-list-box">
                <div style="width: 220px;">
                  <select class="form-control que_lang" name="que_lang">
                    <option value="en" selected>English</option>
                    <option value="es">Spanish</option>
                  </select>
                </div>
               <div class="task-tabbtn-box">
                <div class="table-responsive table-invoice table-width1" style="overflow-x: hidden;">
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <th style="width: auto !important;">Type</th>
                          <th style="width: auto !important;">Name</th>
                          <th>Form Type</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                        <tr>
                            <td>
                              Petitioner
                            </td>
                            <td>
                              <?php 
                               echo $client->first_name;
                               if(!empty($client->middle_name)) {
                                  echo " $client->middle_name";
                               }
                               if(!empty($client->last_name)) {
                                  echo " $client->last_name";
                               }
                               ?>
                            </td>
                            <td>
                              Questionnaire
                            </td>
                            <td class="font-weight-600">
                              Incomplete
                            </td>
                            <td>
                              <a href="{{ url('edit_questionnaire/Petitioner/en') }}/{{$case->client_id}}" target="_blank" class="action_btn que_Petitioner" title="Edit" data-toggle="tooltip">
                                <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                              </a>
                              <a href="{{ url('edit_questionnaire/Petitioner/en') }}/{{$case->client_id}}?action=download" target="_blank" class="action_btn doclinkp doclinkpdd" title="Download" data-toggle="tooltip">
                                <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                  <g>
                                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"></path>
                                  </g>
                                </svg>
                              </a>
                              <a href="{{ url('edit_questionnaire/Petitioner/en') }}/{{$case->client_id}}?action=print" class="action_btn doclink2 doclinkp doclinkppp" title="Print" data-toggle="tooltip" target="_blank">
                                <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                  <g>
                                    <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z">
                                    </path>
                                  </g>
                                </svg>
                              </a>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td>
                              Beneficiary
                            </td>
                            <td>
                              <?php 
                               if(!empty($beneficiary_list)) {
                                  echo $beneficiary_list->name;
                               }
                               ?>
                            </td>
                            <td>
                              Questionnaire
                            </td>
                            <td class="font-weight-600">
                              Incomplete
                            </td>
                            <td>
                              <a href="{{ url('edit_questionnaire/Beneficiary/en') }}/{{$case->client_id}}" target="_blank" class="action_btn que_Beneficiary" title="Edit" data-toggle="tooltip">
                                <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                              </a>
                              <a href="{{asset('storage/app/Questionnaire for Beneficiary - English.pdf')}}" class="action_btn doclinkb" download title="Download" data-toggle="tooltip">
                                <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                  <g>
                                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"></path>
                                  </g>
                                </svg>
                              </a>
                              <a href="{{asset('storage/app/Questionnaire for Beneficiary - English.pdf')}}" class="action_btn doclink2 doclinkb" title="Print" data-toggle="tooltip">
                                <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                  <g>
                                    <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z">
                                    </path>
                                  </g>
                                </svg>
                              </a>
                            </td>
                        </tr> -->
                        <?php 
                        if(!empty($ques)) {
                          foreach ($ques as $k => $v) { 
                            $cls1 = 'doclinkb';
                            if($v->type == 'Petitioner') {
                              $cls1 = 'doclinkp';
                            }
                            ?>
                            <tr>
                              <td>
                                {{$v->type}}
                              </td>
                              <td>
                                {{$v->name}}
                              </td>
                              <td>
                                Questionnaire
                              </td>
                              <td class="font-weight-600">
                                Incomplete
                              </td>
                              <td>
                                <a href="{{ url('edit_questionnaire') }}/{{$v->type}}/{{$v->id}}/{{$v->client_id}}" target="_blank" class="action_btn" title="Edit" data-toggle="tooltip">
                                  <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                                </a>
                                <a href="{{ url('edit_questionnaire') }}/{{$v->type}}/{{$v->id}}/{{$v->client_id}}?action=download" class="action_btn" target="_blank" title="Download" data-toggle="tooltip">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                    <g>
                                      <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"></path>
                                    </g>
                                  </svg>
                                </a>
                                <a href="{{ url('edit_questionnaire') }}/{{$v->type}}/{{$v->id}}/{{$v->client_id}}?action=print" target="_blank" class="action_btn doclink2" title="Print" data-toggle="tooltip">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                    <g>
                                      <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z">
                                      </path>
                                    </g>
                                  </svg>
                                </a>
                              </td>
                          </tr>
                        <?php }
                        } ?>
                        <tr>
                            
                          <td>
                            <a href="#" class="add_questionnaire">
                              Add Questionnaire
                            </a>
                           </td>
                        </tr>
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
<!-- Add Questionnaire Modal -->
<div id="AddQuestionnaire" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Add Questionnaire</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('add_questionnaire_fn') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="row">  
            <div class="col-md-6">
              <label>Add Questionnaire For</label>
            </div>
            <div class="col-md-6">
              <select type="text" class="form-control" name="Questionnaire" required> 
                <option value="Beneficiary">Beneficiary</option>
                <!-- <option value="Petitioner">Petitioner</option> -->
              </select>
            </div>
          </div>
          <br>
          <div class="row">  
            <div class="col-md-6">
              <label>Member</label>
            </div>
            <div class="col-md-6">
              <select class="selectpicker" name="membername" data-live-search="true">
                <?php
                if(!empty($family_alllist)) {
                  foreach ($family_alllist as $key => $value) {
                    echo '<option value="'.$value->name.'">'.$value->name.'</option>';
                  }
                }
                ?>
              </select>
            </div>
          </div>
          <br>
          <div class="row">  
            <div class="col-md-6">
              <label>Language</label>
            </div>
            <div class="col-md-6">
              <select type="text" class="form-control" name="que_lang" required>
                <option value="en" selected>English</option>
                <option value="es">Spanish</option>
              </select>
            </div>
          </div>
          <!-- <br>
          <div class="row">  
            <div class="col-md-6">
              <label>File</label>
            </div>
            <div class="col-md-6">
              <input type="file" class="form-control" name="file" required accept="application/pdf,application/vnd.ms-excel"/>
            </div>
          </div> -->
          <br>
          <div class="row">  
            <div class="col-md-12 text-right">
              <input type="hidden" name="index_id" value="0"> 
              <input type="hidden" name="client_id" value="{{$case->client_id}}" >  
              @csrf
              <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('footer_script')
<script src="{{ asset('assets/js/print.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.que_lang').on('change', function(){
    var v = $(this).val();
    var l1 = "{{ url('edit_questionnaire/Beneficiary') }}/"+v+"/{{$case->client_id}}";
    var l2 = "{{ url('edit_questionnaire/Petitioner') }}/"+v+"/{{$case->client_id}}";
    $('.que_Beneficiary').attr('href', l1);
    $('.que_Petitioner').attr('href', l2);
    var doclinkpdd = "{{ url('edit_questionnaire/Petitioner') }}/"+v+"/{{$case->client_id}}?action=download";
    var doclinkppp = "{{ url('edit_questionnaire/Petitioner') }}/"+v+"/{{$case->client_id}}?action=print";
    if(v == 'es') {
      // var doclink1 = "{{asset('storage/app')}}/Cuestionario del Peticionario - Spanish.pdf";
      // var doclink2 = "{{asset('storage/app')}}/Cuestionario para Beneficiario - Spanish.pdf";
    }
    $('.doclinkpdd').attr('href', doclinkpdd);
    $('.doclinkppp').attr('href', doclinkppp);
  })
  // $('.doclink2').on('click', function(e){
  //   e.preventDefault();
  //   var v = $(this).attr('href');
  //   printJS(v);
  // });
  $('.add_questionnaire').on('click', function(e){
    e.preventDefault();
    $('#AddQuestionnaire').modal('show');
  });
  $('#AddQuestionnaire').on('submit', function(e){
    var v = $('select.selectpicker').val();
    if(v == undefined || v == '') {
      alert('Please select Beneficiary');
      e.preventDefault();
    }
  });
});
</script>
<style type="text/css">
  tr.que_cls_es {
    display: none;
  }
  .card .card-header .btn {
    margin-top: 1px;
    padding: 2px 12px;
  }
</style>
@endpush