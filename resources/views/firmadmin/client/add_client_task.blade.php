@extends('firmlayouts.admin-master')

@section('title')
Invoice
@endsection

@push('header_styles')
<style type="text/css">
  .curruncy_symbol {
    position: absolute;
    left: 25px;
    top: 34px;
  }  
  .case_cost {
    padding-left: 25px !important;
  }
</style>
@endpush  

@section('content')
<section class="section invoice_client client-listing-details client-listing-task">
<!--new-header open-->
  @include('firmadmin.client.client_header')
<!--new-header Close-->
  <div class="section-body invoice-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
        <div class="back-btn-new">
            <a href="{{ url('firm/client/client_task') }}/{{$client->id}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-header">            
            <h4>Add New Task</h4>
          </div>
          
          <div class="card-body">
            <?php if($firm->account_type == 'CMS') { ?>
          	<form action="{{ url('firm/client/insert_client_task') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          		
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Task Type *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                    <select name="type" class="selectpicker form-control" required>
                			<option value="">Select One</option>
                			<option value="Reminder">Reminder</option>
                			<option value="Consultation">Consultation</option>
                			<option value="Court Date">Court Date</option>
                			<option value="Other">Other</option>
                      <option value="CustomText">Custom Text</option>
            		    </select>
                    <input type="hidden" name="" placeholder="Write Here..." class="form-control CustomText" value="" style="margin-top: 15px;">
                  </div>
                </div>
                
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Title *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                   <input type="text" placeholder="Title" name="title" class="form-control" value="" required>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Description *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                    <textarea placeholder="Write here...." name="description" class="form-control" required></textarea>
                  </div>
                </div>
                
              <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Due Date *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                   <input type="text" placeholder="Event date" name="date" class="form-control datepicker" required value="">
                  </div>
                </div>
          		
                
                <div class="form-group row mb-4">
                <!--<label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                </label>--> 
                <div class="col-sm-12 col-md-7">
                  <input type="hidden" name="client_id" value="{{ $id }}" >  
          		   @csrf
          		  <input type="submit" name="save" value="Create Client Task" class="btn btn-primary saveclientinfo_form"/>
                </div>
              </div>
                
          	</form>
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
        </div>
      </div>
     </div>
  </div>
</section>
@endsection

@push('footer_script')
<script type="text/javascript">
$(document).on('keyup', 'input[name="invoice_items[item_cost][]"], input[name="invoice_items[item_qty][]"]', function(){
  var v = 0;
  $('table.table tr').each(function(){
    var a = $(this).find('input[name="invoice_items[item_cost][]"]').val();
    var q = $(this).find('input[name="invoice_items[item_qty][]"]').val();
    var s = a*q;
    if(s) {  
        v = v+s;
        $(this).find('.trtotal').html('$'+s);
    }
  });
  $('.casecost').val(v);
  $('.totlaamount').html('$'+v);
});
$(document).on('click', '.removetr', function(e){
  e.preventDefault();
  $(this).closest('tr').remove();
  var v = 0;
  $('table.table tr').each(function(){
    var a = $(this).find('input[name="invoice_items[item_cost][]"]').val();
    var q = $(this).find('input[name="invoice_items[item_qty][]"]').val();
    var s = a*q;
    if(s) {  
        v = v+s;
        $(this).find('.trtotal').html('$'+s);
    }
  });
  $('.casecost').val(v);
  $('.totlaamount').html('$'+v);
});
$(document).ready(function(){
  
  $('.addnewitem').on('click', function(e){
    e.preventDefault();
    var n = $(this).prev('table').find('tbody tr').length;
    var tr = '<tr><td>'+n+'</td>';
      tr += '<td><input type="text" name="invoice_items[item_name][]" class="form-control" value="" required="required"></td>';
      tr += '<td class="text-center" style="position: relative;">';
      tr += '<span class="curruncy_symbol" style="top:22px;">$</span>';
      tr += '<input type="number" name="invoice_items[item_cost][]" class="form-control case_cost" value="" required="required">';
      tr += '</td><td class="text-center">';
      tr += '<input type="number" name="invoice_items[item_qty][]" class="form-control" value="1" required="required"></td>';
      tr += '<td class="text-right trtotal">$0</td><td>';
      tr += '<a href="#" class="btn btn-primary removetr"><i class="fa fa-times"></i></a></td></tr>';
    $(this).prev('table').find('tbody').append(tr);
  });
  $('select[name="type"]').on('change', function(){
    var v = $(this).val();
    if(v == 'CustomText') {
      $('.CustomText').attr('type', 'text');
      $('.CustomText').attr('name', 'type');
      $('.CustomText').prop('required', true);
    }
    else {
      $('.CustomText').attr('type', 'hidden');
      $('.CustomText').attr('name', '');
      $('.CustomText').prop('required', false);
    }
  });
});  
</script>
@endpush 
