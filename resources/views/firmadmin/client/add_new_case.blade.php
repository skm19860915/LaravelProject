@extends('firmlayouts.admin-master')

@section('title')
Create client Case
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
  .AffidavitDeclaration,
  .additional_service864,
  .additional_service864A,
  .additional_service_864 {
    display: none;
  }
</style>
@endpush 

@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('firmadmin.client.client_header')
<!--new-header Close-->
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
          <div class="back-btn-new">
            <a href="{{ url('firm/client/client_case') }}/{{$client->id}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-header">            
            <h4>Add A New Case</h4>
          </div>
          <div class="card-body">
            <form action="{{ url('firm/client/create_client_case') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="" id="payment-form" >
              <?php 
              //if($firm->account_type == 'CMS') {
                $cname = $client->first_name;
                if(!empty($client->middle_name)) {
                  $cname .= ' '.$client->middle_name;
                }
                if(!empty($client->last_name)) {
                  $cname .= ' '.$client->last_name;
                }
               ?>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Firm Client
                  </label> 
                  <div class="col-sm-12 col-md-7">
                    <input type="hidden" name="firmclient" value="{{$client->user_id}}">
                    <input type="hidden" name="client_id" value="{{$client->id}}">
                    <input class="form-control" type="text" value="{{$cname}}" readonly="readonly">
                  </div>
                </div>
              <?php// } else { ?>  
                <!-- <input type="hidden" name="firmclient" value="0">
                <input type="checkbox" name="VP_Assistance" class="custom-switch-input VP_Assistance" value="1" checked="checked"> -->
              <?php// } ?>  
              <div class="form-group row mb-4 accout_cms">
                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Case Type
                </label> 
                <div class="col-sm-12 col-md-7">
                  <select class="selectpicker case_category" required="" name="case_category" data-live-search="true"></select>
                  <div class="invalid-feedback">Please select case category!</div>
                </div>
              </div>

              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Case Category
                </label> 
                <div class="col-sm-12 col-md-7">
                  <select class="selectpicker rkselect casetype" required="" name="casetype" data-live-search="true">
                    <!-- <option value="Self Managed">Self Managed</option> -->
                  </select>
                  <div class="invalid-feedback">Please select case type!</div>
                </div>
              </div>

              
              <?php 
             // if($firm->account_type == 'CMS') { ?>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Attorney of Record
                </label> 
                <div class="col-sm-12 col-md-7">
                  <select class="selectpicker rkselect" name="firmuser" data-live-search="true">
                    
                    <?php foreach ($user as $key => $value) { ?>
                      <option value="{{$value->id}}">{{$value->name}}</option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback">Please select user Type!</div> 
                </div>
              </div>
              <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Assigned Paralegal
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="selectpicker rkselect" name="assign_paralegal" data-live-search="true">
                  
                  <?php foreach ($user as $key => $value) { ?>
                    <option value="{{$value->id}}">{{$value->name}}</option>
                  <?php } ?>
                </select>
                <div class="invalid-feedback">Please select user Type!</div> 
              </div>
            </div>
            <div class="row form-group">  
              <label class="col-form-label text-md-left col-12 col-md-2 col-lg-2">
                Assigned case to a TILA VP
              </label>
              <div class="col-sm-12 col-md-7">
                <div class="selectgroup w-100">
                  <label class="selectgroup-item">
                    <input type="radio" name="VP_Assistance" value="1" class="selectgroup-input" <?php if($firm->account_type == 'VP Services') { echo 'checked'; } ?>>
                    <span class="selectgroup-button">Yes</span>
                  </label>
                  <label class="selectgroup-item">
                    <?php if($firm->account_type == 'CMS') { ?>
                    <input type="radio" name="VP_Assistance" value="0" class="selectgroup-input">
                    <span class="selectgroup-button">No, Skip for now</span>
                    <?php } ?>
                  </label>
                </div>
                <!-- <label class="custom-switch mt-2" style="padding-left: 0;">
                  <input type="checkbox" name="VP_Assistance" class="custom-switch-input VP_Assistance" value="1">
                  <span class="custom-switch-indicator"></span>
                  <span class="custom-switch-description"></span>
                </label> -->
                <?php if($currunt_user->role_id == 5) { ?>
                <i class="vp_case_notification" style="display: none;">A notification of authorization will be send to {{$firm->firm_admin_name}} for payment. You will receive an update when approved.</i>
                <?php } ?>
              </div>
            </div>
            <?php //} else { ?>
            <!-- <input name="firmuser" type="hidden" value="{{$currunt_user->id}}">
            <input name="assign_paralegal" type="hidden" value="{{$currunt_user->id}}"> -->
          <?php //} ?>
              <div class="form-group row mb-4 additional_service" style="display: none;">
                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Additional Services</label> 
                <div class="col-sm-12 col-md-7">
                  <div class="row nvc_packet" style="margin-bottom: 15px; display: none;">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-6">
                      <strong>NVC Packet/Consular Process</strong>
                    </div>
                    <div class="col-md-3">
                      
                    </div>
                  </div>
                  <div class="row nvc_packet" style="margin-bottom: 15px; display: none;">
                    <div class="col-md-1"></div>
                    <div class="col-md-6">
                      DS-260 for Additional Derivative Beneficiary (online only)
                    </div>
                    <div class="col-md-3">
                      <input type="hidden" name="nvc_packet" class="form-control" value="DS-260 for Additional Derivative Beneficiary (online only)">
                      <input type="number" name="nvc_packet_quantity" min="0" class="form-control" value="0">
                    </div>
                    <div class="col-md-2 add_more_wrapper">
                      <a href="#" class="add_quantity_btn">
                        <i class="fa fa-plus"></i>
                      </a>
                      <a href="#" class="remove_quantity_btn">
                        <i class="fa fa-minus"></i>
                      </a>
                    </div>
                  </div>
                  <div class="row nvc_packet" style="margin-bottom: 15px; display: none;">
                    <div class="col-md-1"></div>
                    <div class="col-md-6 text-md-right">
                      Total
                    </div>
                    <div class="col-md-3">
                      <span class="nvc_total">$0</span>
                    </div>
                  </div>
                  <div class="row additional_service_864" style="margin-bottom: 15px;">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-6">
                      <strong>Affidavit of Support</strong>
                    </div>
                    <div class="col-md-3">
                      
                    </div>
                  </div>
                  <div class="row additional_service864" style="margin-bottom: 15px;">
                    <div class="col-md-1">
                      <input type="checkbox" name="additional_service[]" data-cost="{{$I_864_Cost}}" value="I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor">
                    </div>
                    <div class="col-md-6">
                      I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor
                    </div>
                    <div class="col-md-3">
                      <input type="text" name="extra_cost" class="form-control" value="${{$I_864_Cost}}" readonly="readonly">
                    </div>
                  </div>
                  <div class="row additional_service864A" style="margin-bottom: 15px;">
                    <div class="col-md-1">
                      <input type="checkbox" name="additional_service[]" data-cost="{{$I_864A_Cost}}" value="I-864A, Contract Between Sponsor and Household Member">
                    </div>
                    <div class="col-md-6">
                      I-864A, Contract Between Sponsor and Household Member
                    </div>
                    <div class="col-md-3">
                      <input type="text" name="extra_cost" class="form-control" value="${{$I_864A_Cost}}" readonly="readonly">
                    </div>
                  </div>
                  <div class="row additional_service_864" style="margin-bottom: 15px;">
                    <div class="col-md-1"></div>
                    <div class="col-md-6 text-md-right">
                      Total
                    </div>
                    <div class="col-md-3">
                      <span class="add_serv_total">$0</span>
                    </div>
                  </div>
                  <div class="row AffidavitDeclaration" style="margin-bottom: 15px;">
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-6">
                      <strong>Affidavit /Declaration</strong>
                    </div>
                    <div class="col-md-3">
                      
                    </div>
                  </div>
                  <div class="row AffidavitDeclaration" style="margin-bottom: 35px;">
                    <div class="col-md-1">
                      <!-- <input type="checkbox" name="additional_service1" data-cost="180" value="Affidavit Draft  - (a conferrence call or video available)"> -->
                      <input type="checkbox" name="additional_service1" data-cost="180" value="Draft a Letter/Affidavit" checked="checked" style="display: none;">
                    </div>
                    <div class="col-md-6">
                      Draft a Letter/Affidavit
                    </div>
                    <div class="col-md-3">
                      <!-- <input type="text" name="extra_cost1" class="form-control" value="$180" readonly="readonly"> -->
                    </div>
                    <div class="col-md-2 add_more_wrapper">
                      <a href="#" class="add_more_btn">
                        <i class="fa fa-plus"></i>
                      </a>
                      <a href="#" class="remove_more_btn">
                        <i class="fa fa-minus"></i>
                      </a>
                    </div>
                  </div>
                  <div class="row totalcostx AffidavitDeclaration" style="margin-bottom: 15px; display: none;">
                    <div class="col-md-1"></div>
                    <div class="col-md-6 text-md-right">
                      Total
                    </div>
                    <div class="col-md-3">
                      <span class="add_serv1_total">$0</span>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="additional_service_cost" value="0">
                <input type="hidden" name="additional_service1_cost" value="0">
              </div>
              <div class="form-group row mb-4 rkcasecost" <?php 
              if($firm->account_type == 'CMS') { ?> style="display: none;" <?php } ?>>
                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Case Cost
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="text" placeholder="Case cost" name="casecost" class="form-control casecost1" value="" readonly="readonly"> 
                </div>
              </div> 


              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Case File
                </label> 
                <div class="col-sm-12 col-md-7">
                  <label class="borwse-btn-box">
                  <input type="file" name="case_file[]" class="form-control" multiple>
                   <span class="border-btn-browse"><span>Browse</span></span>
                  </label>
                </div>
              </div>

              <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Comments 
              </label> 
              <div class="col-sm-12 col-md-7">
                <textarea class="form-control" name="case_comment" placeholder="Enter any comments about this case...."></textarea>
              </div>
            </div>

              <div class="form-group row mb-4 paymentwrapper" style="display: none;">
                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                </label> 
                  <div class="col-sm-12 col-md-7 payment-form-card" id="card-element">
                   <h2>Payment details</h2>
                   <?php $pst = ''; if(!empty($card)) {
                    $pst = 'display:none;';
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
                   echo '<div class="row"><div class="col-md-12 text center">OR, <br><a href="#" class="btn btn-primary paywithnewbtn">Pay with new card</a></div></div>';
                   } ?>
                   <div class="newcardwrapper" style="{{$pst}}">
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
                   <div class="row">
                    <div class="col-md-12 col-sm-12"><div class="payment-input1"><input type="checkbox" name="savecard" value="1" />
                      <label>do you want to save this card?</label>
                    </div></div>
                   </div>
                   <div class="submit-login">
                    <label class="payment-errors text-warning"></label><br>
                    <input value="Create" type="submit" class="submit">
                   </div>
                   </div>
                  </div>
              </div> 

              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                </label>
                <div class="col-sm-12 col-md-7">
                  @csrf
                  <button class="btn btn-primary create_firm_case" type="submit" name="create_firm_user">
                    <span>Create Case</span>
                  </button>
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                </label> 
                <div class="col-sm-12 col-md-7">
                  <div class="payment_proccessing_msg alert alert-success text-center" style="display: none;">
                    <strong>
                      <i class="fa fa-spin fa-spinner"></i> &nbsp;&nbsp;Your payment is being processed. Please wait....
                    </strong>
                  </div>
                </div>
              </div>

          </form>
        </div>
      </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>
@endsection

@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
  var is_additional_service = 0;
  var cost1 = 0;
  var cost2 = 0;
  var costc = 0;
  var costd = 0;
  var costn = 0;
  $(document).ready(function(){
    $(".paywithnewbtn").on('click', function(e){
      e.preventDefault();
      $('.newcardwrapper').slideToggle();
    });
    $('.create_firm_case').on('click', function() {
      if($('select.case_category').val() == '') {
        $('div.case_category').next('.invalid-feedback').show();
        return false;
      }
      else {
        $('div.case_category').next('.invalid-feedback').hide();
      }
      if($('select.casetype').val() == '') {
        $('div.casetype').next('.invalid-feedback').show();
        return false;
      }
      else {
        $('div.casetype').next('.invalid-feedback').hide();
      }
      if($('input[name="VP_Assistance"]:checked').val() == 1 && "{{$currunt_user->role_id}}" == "4") {
        $(this).hide();
        $('.paymentwrapper').slideDown();
      }
    });
    $('.paywith_existing').on('click', function() {
      if($('select.case_category').val() == '') {
        $('div.case_category').next('.invalid-feedback').show();
        return false;
      }
      else {
        $('div.case_category').next('.invalid-feedback').hide();
      }
      if($('select.casetype').val() == '') {
        $('div.casetype').next('.invalid-feedback').show();
        return false;
      }
      else {
        $('div.casetype').next('.invalid-feedback').hide();
      }
      $('input[name="card_source"]').prop('checked', false);
      $(this).closest('.row').find('input[type="checkbox"]').prop('checked', true);
      $('.paywith_existing').hide();
      $('.submit-login input').hide();
      $('.payment_proccessing_msg').show();
      // alert('Your payment is being processed');
    });
    window.case_type = $('<textarea />').html('{{ $case_type }}').text();
    case_type = JSON.parse(case_type);
    var i;
    var Case_Category = '<option value="">Select</option>';
    var Case_Category1 = [];
    var caseType = [];
    var casecost = [];
    var additional_service = [];
    var additional_servicex = [];
    for (i = 0; i < case_type.length; i++) {
      var cat = case_type[i].Case_Category;
      var cat1;
      var n = Case_Category1.includes(cat);
      if(!n) {
        Case_Category += '<option value="'+cat+'">'+cat+'</option>';
        Case_Category1.push(cat);
        cat1 = cat.replace(/ /g, '_');
        caseType[cat1] = [];
        casecost[cat1] = [];
        additional_service[cat1] = [];
        additional_servicex[cat1] = [];
      }

      if(!caseType[cat1].includes(case_type[i].Case_Type)) {
        caseType[cat1].push(case_type[i].Case_Type);
        casecost[cat1].push(case_type[i].VP_Pricing);
        additional_service[cat1].push(case_type[i].is_additional_service);
        additional_servicex[cat1].push(JSON.stringify(case_type[i].additional_services));
      }
      
    }
    $('.rkselect').selectpicker();
    $('.case_category').append(Case_Category).selectpicker('refresh');

    $('select.case_category').on('change', function(){
        var v = $(this).val();
        cost1 = 0;
        cost2 = 0;
        costc = 0;
        costd = 0;
        costn = 0;
        if(v == 'NVC Packet/Consular Process') {
          $('.nvc_packet').show();
        }
        else {
          $('.nvc_packet').hide(); 
        }
        if($('input[name="VP_Assistance"]:checked').val() == 1) {
          $('.totalcost').show();
          if(v == 'NVC Packet/Consular Process') {
            $('.nvc_packettotal').show();
          }
          else {
            $('.nvc_packettotal').hide();
          }
        }
        else {
          $('.totalcost').hide();
          $('.nvc_packettotal').hide();
        }
        $('.rmrow').remove();
        $('input[name="additional_service[]"]').prop('checked', false);
        $('input[name="nvc_packet_quantity"]').val(0)
        cat2 = v.replace(/ /g, '_');
        var type1 = '<option value="" data-cost="0">Select</option>';
        if(v != '') {
          for (i = 0; i < caseType[cat2].length; i++) {
            type1 += '<option value="'+caseType[cat2][i]+'" data-cost="'+casecost[cat2][i]+'" data-is_additional_service="'+additional_service[cat2][i]+'" data-additional_services=\''+additional_servicex[cat2][i]+'\'>'+caseType[cat2][i]+'</option>';
          }
        }
        // if($('.VP_Assistance').is(':checked')) {
          $('select.casetype').html(type1);
          $('select.casetype').selectpicker('refresh');
          $('.casecost1').val('');
        // }
    });
    $('select.casetype').on('change', function(){
        var v = $(this).val();
        costc = $('select.casetype option[value="'+v+'"]').data('cost');
        costd = $('select[name*=declaration]').length*"{{$I_DS260_Cost}}";
        costn = $('input[name="nvc_packet_quantity"]').val()*99;
        is_additional_service = $('select.casetype option[value="'+v+'"]').data('is_additional_service');
        additional_services = $('select.casetype option[value="'+v+'"]').data('additional_services');
        $('.additional_service').hide();
        // alert(is_additional_service);
        if(is_additional_service && is_additional_service != 'undefined') {
          $('.additional_service').show();
          $('.AffidavitDeclaration').hide();
          $('.additional_service864').hide();
          $('.additional_service864A').hide();
          $('.additional_service_864').hide();
          $('.additional_service').show();
          for (var i = 0; i < additional_services.length; i++) {
            var v4 = additional_services[i];
            if(v4 == 'I-864, Affidavit of Support Under Section 213A of the INA of Co-sponsor') {
              $('.additional_service_864').css('display', 'flex');
              $('.additional_service864').css('display', 'flex');
            }
            if(v4 == 'I-864A, Contract Between Sponsor and Household Member') {
              $('.additional_service_864').css('display', 'flex');
              $('.additional_service864A').css('display', 'flex');
            }
            if(v4 == 'Draft a Letter/Affidavit') {
              $('.AffidavitDeclaration').css('display', 'flex');
            }
          }
        }
        var c1 = cost1+cost2+costc+costd+costn;
        c1 = '$'+c1.toString();
        $('.casecost1').val(c1);
    });
    $('input[name="VP_Assistance"]').on('click', function(){
      if($('input[name="VP_Assistance"]:checked').val() == 1) {
        $('.rkcasecost').show();
        $('.totalcost').show();
        if(is_additional_service && is_additional_service != 'undefined') {
          $('.additional_service').show();
        }
        if($('select.case_category').val() == 'NVC Packet/Consular Process') {
          $('.nvc_packettotal').show();
        }
        // $('.paymentwrapper').slideDown();
      }
      else {
        // $('.additional_service').hide();
        $('.totalcost').hide();
        $('.nvc_packettotal').hide();
        $('.paymentwrapper').slideUp();
        $('.rkcasecost').hide();
        $('.create_firm_case').show(); 
        // $('select.casetype').html('<option value="Self Managed" selected="selected">Self Managed</option>');
        // $('.casetype').selectpicker('refresh');
        //$(document).find('.casecost1').attr('value', '');
      }
    });
    $('input[name="additional_service[]"]').on('click', function(){
      var v = $(this).val();
      cost1 = 0;
      $('input[name="additional_service[]"]:checked').each(function(){
        var cc = $(this).data('cost');
        cost1 = cost1+cc;
      })
      //cost1 = $('input[name="additional_service[]"]:checked').length*99;
      $('input[name="additional_service_cost"]').val(cost1);
      costd = $('select[name*=declaration]').length*"{{$I_Affidavit_Cost}}";
      costn = $('input[name="nvc_packet_quantity"]').val()*"{{$I_DS260_Cost}}";
      var c1 = cost1+cost2+costc+costd+costn;
      c1 = '$'+c1.toString();
      $('.add_serv_total').text('$'+cost1);
      $('.casecost1').val(c1);
      console.log(c1);
    });
    $('input[name="additional_service1"]').on('click', function(){
      var v = $(this).val();
      cost2 = 0;
      $('input[name="additional_service1_cost"]').val(cost2);
      costd = $('select[name*=declaration]').length*"{{$I_Affidavit_Cost}}";
      costn = $('input[name="nvc_packet_quantity"]').val()*"{{$I_DS260_Cost}}";
      var c1 = cost1+cost2+costc+costd+costn;
      c1 = '$'+c1.toString();
      $('.casecost1').val(c1);
      console.log(c1);
    });
  });

  $(document).on('click', '.add_more_btn', function(e){
      e.preventDefault();
      var r = '<div class="row rmrow" style="margin-bottom: 15px;">';
      r += '<div class="col-md-6 offset-md-1"><select class="form-control" name="declaration[]">';
      r += '<option value="Beneficiary">Beneficiary</option><option value="Petitioner">Petitioner</option>';
      r += '<option value="Other">Other</option></select></div><div class="col-md-3">';
      if($('input[name="VP_Assistance"]:checked').val() == 1) {
        r += '<input type="text" class="form-control totalcost" value="${{$I_Affidavit_Cost}}" readonly="readonly">';
      }
      else {
        r += '<input type="text" class="form-control totalcost" value="${{$I_Affidavit_Cost}}" readonly="readonly" style="display:none;">';
      }
      r += '</div>';
      r += '<div class="col-md-2 add_more_wrapper">';
      r += '<a href="#" class="add_more_btn"><i class="fa fa-plus"></i></a>';
      r += '<a href="#" class="remove_more_btn"><i class="fa fa-minus"></i></a></div>';
      r += '<div class="col-md-9 offset-md-1" style="display:none; margin-top:15px;">';
      r += '<input type="text" name="declaration_other[]" placeholder="Write here..." class="form-control" value="">';
      r += '</div></div>';
      $(this).closest('.row').after(r);
      costd = $('select[name*=declaration]').length*"{{$I_Affidavit_Cost}}";
      costn = $('input[name="nvc_packet_quantity"]').val()*"{{$I_DS260_Cost}}";
      var c1 = cost1+cost2+costc+costd+costn;
      c1 = '$'+c1.toString();
      $('.add_serv1_total').text('$'+costd);
      $('.casecost1').val(c1);
  });
  $(document).on('click', '.remove_more_btn', function(e){
    e.preventDefault();
    $(this).closest('.rmrow').remove();
    costd = $('select[name*=declaration]').length*"{{$I_Affidavit_Cost}}";
    costn = $('input[name="nvc_packet_quantity"]').val()*"{{$I_DS260_Cost}}";
      var c1 = cost1+cost2+costc+costd+costn;
      c1 = '$'+c1.toString();
      $('.casecost1').val(c1);
      $('.add_serv1_total').text('$'+costd);
  });
  $(document).on('click', '.add_quantity_btn', function(e){
      e.preventDefault();
      var q = $('input[name=nvc_packet_quantity]').val();
      q++;
      $('input[name=nvc_packet_quantity]').val(q);
      costn = q*"{{$I_DS260_Cost}}";
      $('.nvc_total').text('$'+costn);
      var c1 = cost1+cost2+costc+costd+costn;
      c1 = '$'+c1.toString();
      $('.casecost1').val(c1);
  });
  $(document).on('click', '.remove_quantity_btn', function(e){
      e.preventDefault();
      var q = $('input[name=nvc_packet_quantity]').val();
      q--;
      if(q >= 0) {
        $('input[name=nvc_packet_quantity]').val(q);
        costn = q*"{{$I_DS260_Cost}}";
        $('.nvc_total').text('$'+costn);
      var c1 = cost1+cost2+costc+costd+costn;
      c1 = '$'+c1.toString();
      $('.casecost1').val(c1);
      }
  });
  $(document).on('change', 'select[name="declaration[]"]', function(){
    var v = $(this).val();
    if(v == 'Other') {
      $(this).closest('.row').find('.col-md-9.offset-md-1').show();
    }
    else {
      $(this).closest('.row').find('.col-md-9.offset-md-1').hide();
    }
  });

  Stripe.setPublishableKey("{{ env('SRTIPE_PUBLIC_KEY') }}");

  $(function() {
    var $form = $('#payment-form');
    $form.submit(function(event) {
      console.log('2');
      if($('input[name="VP_Assistance"]:checked').val() == 1 && !$('input[name="card_source"]').is(':checked') && "{{$currunt_user->role_id}}" == "4") {
        // Disable the submit button to prevent repeated clicks:
        $form.find('.submit').prop('disabled', true);
        $('.paywith_existing').hide();
        $('.submit-login input').hide();
        $('.payment_proccessing_msg').show();
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
      $('.paywith_existing').show();
      $('.submit-login input').show();
      $('.payment_proccessing_msg').hide();
      // Show the errors on the form:
      $form.find('.payment-errors').text(response.error.message);
      $form.find('.submit').prop('disabled', false); // Re-enable submission

    } else { // Token was created!

      // Get the token ID:
      var token = response.id;

      // Insert the token ID into the form so it gets submitted to the server:
      // $form1 = $('#payment-form-res');
      $form.append($('<input type="hidden" name="stripeToken">').val(token));

      // Submit the form:
      $form.get(0).submit();
    }
  };
</script>
@endpush