@extends('firmlayouts.admin-master')

@section('title')
Create Firm
@endsection

@push('header_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
@endpush 

@section('content')
<section class="section">
  <div class="section-header">
    <h1><a href="{{ url('firm/task') }}"><span>Task /</span></a> Create Task</h1>
    <div class="section-header-breadcrumb">
      
    </div>

  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/task/create_task') }}" method="post" class="needs-validation" novalidate="">
          <div class="back-btn-new">
            <a href="{{ url('firm/task') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-body">
            <?php if($firm->account_type == 'CMS') { ?>
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Title" name="task" class="form-control" required=""> 
                <div class="invalid-feedback">Title is required!</div>
              </div>
            </div> 
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Description
              </label> 
              <div class="col-sm-12 col-md-7">
                <textarea placeholder="Type details of your task" name="description" class="form-control" required=""></textarea>
                <div class="invalid-feedback">Description is required!</div>
              </div>
            </div> 
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Task Priority 
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
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                <?php 
                if($firm->account_type == 'VP Services') {
                  echo 'Client';
                }
                else {
                  echo 'User';
                }
                ?>
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php 
                if(empty($admintask->allot_user_id)) { ?>
                <select class="selectpicker" required="required" name="vauser" data-live-search="true">
                  <option value="">Select</option>
                  <?php foreach ($vauser as $key => $value) { ?>
                    
                  <option value="{{$value->id}}">{{$value->name}}</option>
                  <?php } ?>
                </select> 
              <?php } ?>
                <div class="invalid-feedback err1">Please select User!</div>
              </div>
            </div> 
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <button class="btn btn-primary create_task" type="submit" name="create_firm_Account">
                  <span>Create Task</span>
                </button>
              </div>
            </div>
            <?php } else { ?>
              <div class="row">
                <div class="col-md-6 offset-md-3">
                    <br><br>
                    <form action="{{url('firm/pay_for_cms')}}" method="post" id="payment-form" enctype="multipart/form-data">
                        <div class="card card-info text-center">
                          <br>
                          <div class="card-body">
                            <h6>
                                <i class="fa fa-exclamation-triangle"></i> 
                                This feature is for case management software users
                            </h6>
                            <h5 style="max-width: 320px;margin: 15px auto;">
                                Get full CMS access for your Firm we are all using it.
                            </h5>
                            <h5>
                                $<span class="annual_payment_cycletext">{{$firm->usercost}} a month</span> <br> per user
                            </h5>

                            <label class="custom-switch mt-2">
                                <span class="custom-switch-description" style="margin: 0 .5rem 0 0;">Bill Annually</span> 
                                <input type="checkbox" name="payment_cycle" class="custom-switch-input annual_payment_cycle" value="1" checked data-monthly_amount="{{$firm->usercost}}">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Bill Monthly</span>
                            </label>
                            <div class="saved_amount_text"></div>
                          </div>
                          <div class="card-footer">
                            @csrf
                            <input type="hidden" name="amount" value="55">
                            <!-- <button type="button" name="payforcms" class="btn btn-primary payforcms">Get Started</button> -->
                            <a href="{{url('firm/upgradetocms')}}" class="btn btn-primary">Upgrade</a>
                          </div>
                          <div class="payment-form-card" id="card-element" style="display: none;">
                             <h2 class="provided_cost"></h2>
                             <?php if(!empty($card)) {
                              echo '<div class="row card-payno-tx"><div class="col-md-12 text center">Pay with existing card</div></div>';
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
                             <div class="row card-payno">
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
                             <div class="submit-login">
                              @csrf
                              <input value="Upgrade" type="submit" class="submit">
                             </div>
                             
                            </div>
                        </div>
                    </form>
                </div>
            </div>
          <?php } ?>
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
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.create_task').on('click', function(){
    var c = $('select[name="vauser"]').val();
    if(c == '') {
      $('.err1').show();
    }
    else {
      $('.err1').hide();
    }
  })
});
</script>
@endpush