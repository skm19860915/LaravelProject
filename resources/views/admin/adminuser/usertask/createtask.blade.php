@extends('layouts.admin-master')

@section('title')
Create Task
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
@endpush 

@section('content')
<section class="section client-listing-details task-new-header">
  <div class="section-header">
    <h1><a href="{{ url('admin/task') }}"><span>Task /</span></a> Create Task</h1>
    <div class="section-header-breadcrumb">
      
    </div>

  </div>
  <div class="section-body">
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
        <form action="{{ url('admin/usertask/insertusertask') }}" method="post" class="needs-validation insertusertask" novalidate="">
          <div class="back-btn-new">
            <a href="{{ url('admin/usertask') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-body">
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Assign Task to Self or Client
                </label> 
                <div class="col-sm-12 col-md-7">
                  <!-- <select class="selectpicker" required="required" name="firmadmins" data-live-search="true">
                    <option value="">Select</option>
                    <option value="{{$current_user->id}}" data-firm_id="0">{{$current_user->name}}</option>
                    <?php 
                    if(!empty($firmadmins)) {
                      foreach ($firmadmins as $key => $value) { ?>
                      <option value="{{$value->id}}" data-firm_id="{{$value->firm_id}}">{{$value->name}}</option>
                      <?php } 
                    } 
                    ?>
                  </select>  -->
                  <label class="custom-switch mt-2" style="padding-left: 0px;">
                    <input type="checkbox" name="firmadminsx" value="{{$current_user->id}}" class="custom-switch-input" checked> 
                    <span class="custom-switch-indicator"></span> 
                    <span class="custom-switch-description"></span>
                  </label>
                  <input type="hidden" name="firmadmins" value="{{$current_user->id}}">
                  <div class="invalid-feedback">Please select User!</div>
                </div>
              </div>
              <div class="form-group row mb-4 clientselecter" style="display: none;">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Assign Task to Client
                </label> 
                <div class="col-sm-12 col-md-7">
                  <select class="selectpicker" name="firmclient" data-live-search="true">
                    <option value="">Select</option>
                    <?php 
                    if(!empty($firmclient)) {
                      foreach ($firmclient as $key => $value) { ?>
                        <option value="{{$value->id}}" data-firm_id="{{$value->firm_id}}">{{$value->name}}</option>
                      <?php }
                    } 
                    ?>
                    </select> 

                  <div class="invalid-feedback">Please select Client!</div>
                </div>
              </div> 
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Subject
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="text" placeholder="Subject" name="task" class="form-control" required=""> 
                  <div class="invalid-feedback">Subject is required!</div>
                </div>
              </div> 
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Priority 
                </label> 
                <div class="col-sm-12 col-md-7">
                  <select class="form-control" required="" name="priority">
                    <option value="1">Urgent</option>
                    <option value="2">High</option>
                    <option value="3">Medium</option>
                    <option value="4">Low</option>
                  </select> 
                  <div class="invalid-feedback">Please select Priority Type!</div>
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Description
                </label> 
                <div class="col-sm-12 col-md-7">
                  <textarea placeholder="Description" name="description" class="form-control" required=""></textarea>
                  <div class="invalid-feedback">Description is required!</div>
                </div>
              </div> 
              
               
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Due Date
                </label> 
                <div class="col-sm-4 col-md-2 input-group">
                  <input type="text" placeholder="Due Date" name="due_date" class="form-control datepicker1" required="" value="" style="
                      z-index: 1;
                      background: transparent;
                  ">
                  <div class="input-group-append" style="position: absolute; right: 15px;">
                    <div class="input-group-text" style="
                        border-top-right-radius: 21px;
                        border-bottom-right-radius: 21px;
                    ">
                      <i class="fas fa-calendar"></i>
                    </div>
                  </div>
                  <div class="invalid-feedback">Please select Due Date!</div>
                </div>
              </div> 
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="hidden" name="redirect_url" value="{{ url('admin/usertask') }}">
                  @csrf
                  <button class="btn btn-primary" type="submit" name="create_firm_Account">
                    <span>Create</span>
                  </button>
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
@endsection
@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.datepicker1').daterangepicker({
    locale: {format: 'MM/DD/YYYY'},
    singleDatePicker: true,
    timePicker: false,
    timePicker24Hour: false,
    minDate: new Date()
  });
  // $('select[name="firmadmins"]').on('change', function(){
  //   var v = $(this).val();
  //   var fid = $('select[name="firmadmins"] option[value="'+v+'"]').data('firm_id');
  //   if(fid) {
  //     $('select[name="firmclient"] option').hide();
  //     $('select[name="firmclient"] option[data-firm_id="'+fid+'"]').show();
  //   }
  //   else {
  //     $('select[name="firmclient"] option').show();
  //   }
  //   $('select[name="firmclient"]').val('');
  //   jQuery('.selectpicker').selectpicker('refresh');
  // });
  $('input[name="firmadminsx"]').on('change', function(){
    if($(this).is(':checked')) {
      $('.clientselecter').hide();
      $('select[name="firmclient"]').val('');
    }
    else {
      $('.clientselecter').show();
    }
    $('select[name="firmclient"]').val('');
    jQuery('.selectpicker').selectpicker('refresh');
  });
  $('.insertusertask').on('submit', function(e){
      if(!$('input[name="firmadminsx"]').is(':checked') && $('select[name="firmclient"]').val() == '') {
        e.preventDefault();
        alert('Please select client');
      }
  });
});
</script>
@endpush 