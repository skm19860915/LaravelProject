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
              
              <h2>Edit Document Request</h2>
               <!-- <a href="#" class="add-task-link" id="fire-modal-2">+ Request Documents</a> -->
              <div class="documents-list-box">
                <div class="row">
                  <div class="col-md-6 col-sm-6">
                    <form action="{{url('admin/userclient/updaterequestdocuments')}}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
                      <div class="row">
                        <div class="col-md-4">
                          Type * 
                        </div>
                        <div class="col-md-8">
                          <?php 
                          
                          if(!empty($CaseTypes[0]->Required_Documentation_en)) {
                            $CaseTypes[0]->Required_Documentation_en = array_map('trim', $CaseTypes[0]->Required_Documentation_en);
                            if(in_array($requested_doc->document_type, $CaseTypes[0]->Required_Documentation_en)) { ?>
                          <select name="file_type" class="selectpicker1" required="required" data-live-search="true">
                            <!-- <option value="">Select One</option> -->
                            <?php 
                              foreach ($CaseTypes[0]->Required_Documentation_en as $key => $v) {
                                $sl = '';
                                if(trim($v) == trim($requested_doc->document_type)) {
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
                            if(!empty($client)) {
                              $cn = $client->first_name.' '.$client->middle_name.' '.$client->last_name;
                              $cuid = $client->user_id;
                              
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
                          <input name="client_id" type="hidden" value="{{$client->user_id}}" required="required">
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
</section>
@endsection
@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
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
  $('.selectpicker1').selectpicker();
});
</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 