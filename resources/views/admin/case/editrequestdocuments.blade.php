@extends('layouts.admin-master')

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
<section class="section client-listing-details task-new-header">
  <!--new-header open-->
  @include('admin.case.case_header')
  <!--new-header Close-->

  <div class="section-body">

   <div class="row">
    <div class="col-md-12">
      <div class="card">        

        <div class="card-body">
          <div class="profile-new-client">
           <h2>Edit Document Request</h2>

           <div class="documents-list-box">
                <div class="row">
                  <div class="col-md-6 col-sm-6">
                    <form action="{{url('admin/allcases/updaterequestdocuments')}}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
                      <div class="row">
                        <div class="col-md-4">
                          Type * 
                        </div>
                        <div class="col-md-8">
                          <?php 
                          
                          if(!empty($CaseTypes[0]->Required_Documentation_en)) {
                            $CaseTypes[0]->Required_Documentation_en = array_map('trim', $CaseTypes[0]->Required_Documentation_en);
                            if(in_array($requested_doc->document_type, $CaseTypes[0]->Required_Documentation_en)) { ?>
                          <select name="file_type" class="selectpicker" required="required" data-live-search="true">
                            <!-- <option value="">Select One</option> -->
                            <?php
                            foreach ($CaseTypes[0]->Required_Documentation_en as $key => $v) {
                                $sl = '';
                                if($v == $requested_doc->document_type) {
                                  $sl = 'selected';
                                }
                                echo "<option value='$v' $sl>$v</option>";
                                 
                              } 
                            ?>
                          </select>
                          <?php } else { ?>
                          <input type="text" placeholder="Due Date" name="file_type" class="form-control" value="{{$requested_doc->document_type}}">
                          <?php } } ?>
                        </div>
                      </div>
                      <br>
                      <div class="row">  
                        <div class="col-md-4">
                          Due Date * 
                        </div>
                        <div class="col-md-8">
                          <input type="text" placeholder="Due Date" name="expiration_date" class="form-control datepicker2" value="{{$requested_doc->expiration_date}}">
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
                            $sl1 = '';
                            if(!empty($clientrr)) {
                              $cn = $clientrr->first_name.' '.$clientrr->middle_name.' '.$clientrr->last_name;
                              $cuid = $clientrr->user_id;
                              
                              if($requested_doc->family_id == $cuid) {
                                $sl1 = 'selected';
                              }
                              echo "<option value='$cuid' $sl1>$cn</option>"; 
                            }
                            if(!empty($family_alllist)) {
                              $sl2 = '';
                              foreach ($family_alllist as $key => $v) {
                                if(!in_array($v->uid, $farr)) {
                                  $farr[] = $v->uid;
                                  if($requested_doc->family_id == $v->uid) {
                                    $sl2 = 'selected';
                                  }
                                  echo "<option value='".$v->uid."' $sl2>".$v->name."</option>";
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
                          <input name="client_id" type="hidden" value="{{$clientrr->id}}" required="required">
                          <input name="case_id" type="hidden" value="{{$case->id}}" required="required">
                          <input name="did" type="hidden" value="{{$requested_doc->id}}" required="required">
                          
                          <input type="submit" name="save" value="Update" class="btn btn-primary convert_client_act"/>
                        </div>
                      </div>
                    </form>
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
@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.datepicker2').daterangepicker({
        timePicker: false,
        singleDatePicker: true,
        //endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
          format: 'MM/DD/YYYY'
        },
        minDate: new Date()
    });
  $('.rkselect').selectpicker();
});
</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 