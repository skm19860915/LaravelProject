@extends('layouts.admin-master')

@section('title')
Create Firm
@endsection
@push('header_styles')
<style type="text/css">
  .curruncy_symbol {
    position: absolute;
    left: 32px;
    top: 11px;
  }  
  .firm_cost {
    padding-left: 25px !important;
  }
</style>
@endpush
@section('content')
<section class="section client-edit-box client-add-box">
  <div class="section-header">
    <h1><a href="{{url('admin/firm')}}"><span>Firm /</span></a> Create Firm Account</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
    
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('admin/firm/create_firm') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="back-btn-new">
            <a href="{{ url('admin/firm') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-body">
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Firm Name" name="firm_name" class="form-control" required="" value="<?php if(isset(Session::get('data')['firm_name'])) { echo Session::get('data')['firm_name']; }?>"> 
                <div class="invalid-feedback">Firm Name is required!</div>
              </div>
            </div> 
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Account Type
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="" name="account_type">
                  <option value="CMS" <?php if(isset(Session::get('data')['account_type']) && Session::get('data')['account_type'] == 'CMS') { echo 'selected="selected"'; }?>>CMS</option>
                  <option value="VP Services" <?php if(isset(Session::get('data')['account_type']) && Session::get('data')['account_type'] == 'VP Services') { echo 'selected="selected"'; }?>>VP Services</option>
                </select> 
                <div class="invalid-feedback">Please select Account Type!</div>
              </div>
            </div> 
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Admin User's Email
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="email" placeholder="Firm Admin User's Email." name="email" class="form-control" required="" value="<?php if(isset(Session::get('data')['email'])) { echo Session::get('data')['email']; }?>">  
                <div class="invalid-feedback">Firm Admin User's Email is required!</div>
              </div>
            </div> 
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Admin User's Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text"  name="firm_admin_name" placeholder="Firm Admin User's Name" class="form-control" required="" value="<?php if(isset(Session::get('data')['firm_admin_name'])) { echo Session::get('data')['firm_admin_name']; }?>"> 
                <div class="invalid-feedback">Firm Admin User's Name is required!</div>
              </div>
            </div>

            <div class="form-group row mb-4 specific_cost_wrapper1">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">User Cost
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="" name="cost_type">
                  <option value="Default" <?php if(isset(Session::get('data')['cost_type']) && Session::get('data')['cost_type'] == 'Default') { echo 'selected="selected"'; }?>>Default</option>
                  <option value="Specific Cost" <?php if(isset(Session::get('data')['cost_type']) && Session::get('data')['cost_type'] == 'Specific Cost') { echo 'selected="selected"'; }?>>Specific Cost</option>
                </select> 
                <div class="invalid-feedback">Cost type is required!</div>
                <small><i>The default cost is $65 per user every month.</i></small>
              </div>
            </div> 

            <div class="form-group row mb-4 specific_cost_wrapper" <?php if(isset(Session::get('data')['cost_type']) && Session::get('data')['cost_type'] == 'Specific Cost') { echo 'style="display: flex;"'; } else { echo 'style="display: none;"'; } ?>>
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                <span class="curruncy_symbol">$</span>
                <input type="text" name="usercost" class="form-control firm_cost" value="<?php if(isset(Session::get('data')['usercost'])) { echo Session::get('data')['usercost']; }?>" placeholder="Enter Specific Cost"> 
                <div class="invalid-feedback">Specific Cost is required!</div>
              </div>
            </div>

           <!--  <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Logo
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="file" name="firm_logo_path" class="form-control"> 
              </div>
            </div> -->

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <button class="btn btn-primary" type="submit" name="create_firm_Account">
                  <span>Create Firm Account</span>
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
<script type="text/javascript">
  $(document).ready(function(){
    $('select[name="account_type"]').on('change', function(){
      var v = $(this).val();
      if(v == 'CMS') {
        $('.specific_cost_wrapper1').show();
      }
      else {
        $('.specific_cost_wrapper1').hide();
      }
      $('select[name="cost_type"]').val('Default');
      $('.specific_cost_wrapper').hide();
      $('.specific_cost_wrapper input').prop('required', false);
    });
    $('select[name="cost_type"]').on('change', function(){
      var v = $(this).val();
      if(v == 'Default') {
        $('.specific_cost_wrapper').hide();
        $('.specific_cost_wrapper input').prop('required', false);
      }
      else {
        $('.specific_cost_wrapper').show();
        $('.specific_cost_wrapper input').prop('required', true);
      }
    });
  });
</script>
@endpush
