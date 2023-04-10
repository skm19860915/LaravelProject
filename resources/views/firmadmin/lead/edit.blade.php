@extends('firmlayouts.admin-master')

@section('title')
COnvert to CLient
@endsection

@push('header_styles')
<link  href="{{ asset('assets/css/daterangepicker.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
.selectedfiles {
  padding: 0;
  margin: 0;
}  
.selectedfiles li {
  display: inline-block;
  vertical-align: top;
  margin-bottom: 15px;
  position: relative;
}
.selectedfiles li input {
  display: none;
}
.selectedfiles li img {
  width: 90px;
  height: 90px;
}
.selectedfiles li a.remove_file {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 99;
    padding: 1px;
    color: #fff;
    background: rgba(0,0,0,0.5);
}
</style>
@endpush 

@section('content')
<section class="section">
  <div class="section-header">
    <h1>
      <a href="{{route('firm.lead')}}"><span>Lead / </span></a>
      <a><span>{{$lead->name}} {{$lead->last_name}} / </span></a>
        Convert to Client
    </h1>
    <div class="section-header-breadcrumb">
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/lead/convert_client') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/lead') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
            <h4 style="margin-bottom: 20px;">Client Information

              <div class="clent-info text-right" style="float: right;width: 50%;margin-bottom: 0px;"><span>Portal Access</span>:
                <span>
                  <label class="custom-switch mt-2" style="padding-left: 0;">
                    <input type="checkbox" name="is_portal_access" class="custom-switch-input is_portal_access" value="1">
                    <span class="custom-switch-indicator" style="width: 48px;"></span>
                    <span class="custom-switch-description"></span>
                  </label>
                </span>
               </div>
            </h4>

            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">First Name <span style="color: red"> *</span>
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="First Name" name="first_name" value="{{$lead->name}}" class="form-control" required="required"> 
                    <div class="invalid-feedback">First Name is required!</div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label tcol-md-4 col-sm-4">Middle Name
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Middle Name" name="middle_name" value="" class="form-control"> 
                    <div class="invalid-feedback">Middle Name is required!</div>
                  </div>
                </div> 
              </div>
            </div>

            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label tcol-md-4 col-sm-4">Last Name <span style="color: red"> *</span>
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Last Name" name="last_name" value="{{$lead->last_name}}" class="form-control" required="required"> 
                    <div class="invalid-feedback">Last Name is required!</div>
                  </div>
                </div> 
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Contact Email <span style="color: red; display: none;" class="emailerr"> *</span>
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="email" placeholder="Contact Email" name="email" value="{{$lead->email}}" class="form-control"> 
                    <div class="invalid-feedback">Contact Email is required!</div>
                  </div>
                </div>
              </div>
              
            </div>

            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Cell Phone <span style="color: red"> *</span>
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Cell phone" name="cell_phone" value="{{$lead->cell_phone}}" class="form-control phone_us" required="required"> 
                    <div class="invalid-feedback">Cell Phone is required!</div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4"> Language
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <select class="selectpicker" name="language" data-live-search="true">
                      <option value="">Select One</option>
                      <option value="Afrikaans" <?php if($lead->language == 'Afrikaans') { echo 'selected="selected"'; } ?>>Afrikaans</option>
                      <option value="Albanian" <?php if($lead->language == 'Albanian') { echo 'selected="selected"'; } ?>>Albanian</option>
                      <option value="Arabic" <?php if($lead->language == 'Arabic') { echo 'selected="selected"'; } ?>>Arabic</option>
                      <option value="Armenian" <?php if($lead->language == 'Armenian') { echo 'selected="selected"'; } ?>>Armenian</option>
                      <option value="Basque" <?php if($lead->language == 'Basque') { echo 'selected="selected"'; } ?>>Basque</option>
                      <option value="Bengali" <?php if($lead->language == 'Bengali') { echo 'selected="selected"'; } ?>>Bengali</option>
                      <option value="Bulgarian" <?php if($lead->language == 'Bulgarian') { echo 'selected="selected"'; } ?>>Bulgarian</option>
                      <option value="Catalan" <?php if($lead->language == 'Catalan') { echo 'selected="selected"'; } ?>>Catalan</option>
                      <option value="Cambodian" <?php if($lead->language == 'Cambodian') { echo 'selected="selected"'; } ?>>Cambodian</option>
                      <option value="Chinese (Mandarin)" <?php if($lead->language == 'Chinese (Mandarin)') { echo 'selected="selected"'; } ?>>Chinese (Mandarin)</option>
                      <option value="Croatian" <?php if($lead->language == 'Croatian') { echo 'selected="selected"'; } ?>>Croatian</option>
                      <option value="Czech" <?php if($lead->language == 'Czech') { echo 'selected="selected"'; } ?>>Czech</option>
                      <option value="Danish" <?php if($lead->language == 'Danish') { echo 'selected="selected"'; } ?>>Danish</option>
                      <option value="Dutch" <?php if($lead->language == 'Dutch') { echo 'selected="selected"'; } ?>>Dutch</option>
                      <option value="English" <?php if($lead->language == 'English') { echo 'selected="selected"'; } ?>>English</option>
                      <option value="Estonian" <?php if($lead->language == 'Estonian') { echo 'selected="selected"'; } ?>>Estonian</option>
                      <option value="Fiji" <?php if($lead->language == 'Fiji') { echo 'selected="selected"'; } ?>>Fiji</option>
                      <option value="Finnish" <?php if($lead->language == 'Finnish') { echo 'selected="selected"'; } ?>>Finnish</option>
                      <option value="French" <?php if($lead->language == 'French') { echo 'selected="selected"'; } ?>>French</option>
                      <option value="Georgian" <?php if($lead->language == 'Georgian') { echo 'selected="selected"'; } ?>>Georgian</option>
                      <option value="German" <?php if($lead->language == 'German') { echo 'selected="selected"'; } ?>>German</option>
                      <option value="Greek" <?php if($lead->language == 'Greek') { echo 'selected="selected"'; } ?>>Greek</option>
                      <option value="Gujarati" <?php if($lead->language == 'Gujarati') { echo 'selected="selected"'; } ?>>Gujarati</option>
                      <option value="Hebrew" <?php if($lead->language == 'Hebrew') { echo 'selected="selected"'; } ?>>Hebrew</option>
                      <option value="Hindi" <?php if($lead->language == 'Hindi') { echo 'selected="selected"'; } ?>>Hindi</option>
                      <option value="Hungarian" <?php if($lead->language == 'Hungarian') { echo 'selected="selected"'; } ?>>Hungarian</option>
                      <option value="Icelandic" <?php if($lead->language == 'Icelandic') { echo 'selected="selected"'; } ?>>Icelandic</option>
                      <option value="Indonesian" <?php if($lead->language == 'Indonesian') { echo 'selected="selected"'; } ?>>Indonesian</option>
                      <option value="Irish" <?php if($lead->language == 'Irish') { echo 'selected="selected"'; } ?>>Irish</option>
                      <option value="Italian" <?php if($lead->language == 'Italian') { echo 'selected="selected"'; } ?>>Italian</option>
                      <option value="Japanese" <?php if($lead->language == 'Japanese') { echo 'selected="selected"'; } ?>>Japanese</option>
                      <option value="Javanese" <?php if($lead->language == 'Javanese') { echo 'selected="selected"'; } ?>>Javanese</option>
                      <option value="Korean" <?php if($lead->language == 'Korean') { echo 'selected="selected"'; } ?>>Korean</option>
                      <option value="Latin" <?php if($lead->language == 'Latin') { echo 'selected="selected"'; } ?>>Latin</option>
                      <option value="Latvian" <?php if($lead->language == 'Latvian') { echo 'selected="selected"'; } ?>>Latvian</option>
                      <option value="Lithuanian" <?php if($lead->language == 'Lithuanian') { echo 'selected="selected"'; } ?>>Lithuanian</option>
                      <option value="Macedonian" <?php if($lead->language == 'Macedonian') { echo 'selected="selected"'; } ?>>Macedonian</option>
                      <option value="Malay" <?php if($lead->language == 'Malay') { echo 'selected="selected"'; } ?>>Malay</option>
                      <option value="Malayalam" <?php if($lead->language == 'Malayalam') { echo 'selected="selected"'; } ?>>Malayalam</option>
                      <option value="Maltese" <?php if($lead->language == 'Maltese') { echo 'selected="selected"'; } ?>>Maltese</option>
                      <option value="Maori" <?php if($lead->language == 'Maori') { echo 'selected="selected"'; } ?>>Maori</option>
                      <option value="Marathi" <?php if($lead->language == 'Marathi') { echo 'selected="selected"'; } ?>>Marathi</option>
                      <option value="Mongolian" <?php if($lead->language == 'Mongolian') { echo 'selected="selected"'; } ?>>Mongolian</option>
                      <option value="Nepali" <?php if($lead->language == 'Nepali') { echo 'selected="selected"'; } ?>>Nepali</option>
                      <option value="Norwegian" <?php if($lead->language == 'Norwegian') { echo 'selected="selected"'; } ?>>Norwegian</option>
                      <option value="Persian" <?php if($lead->language == 'Persian') { echo 'selected="selected"'; } ?>>Persian</option>
                      <option value="Polish" <?php if($lead->language == 'Polish') { echo 'selected="selected"'; } ?>>Polish</option>
                      <option value="Portuguese" <?php if($lead->language == 'Portuguese') { echo 'selected="selected"'; } ?>>Portuguese</option>
                      <option value="Punjabi" <?php if($lead->language == 'Punjabi') { echo 'selected="selected"'; } ?>>Punjabi</option>
                      <option value="Quechua" <?php if($lead->language == 'Quechua') { echo 'selected="selected"'; } ?>>Quechua</option>
                      <option value="Romanian" <?php if($lead->language == 'Romanian') { echo 'selected="selected"'; } ?>>Romanian</option>
                      <option value="Russian" <?php if($lead->language == 'Russian') { echo 'selected="selected"'; } ?>>Russian</option>
                      <option value="Samoan" <?php if($lead->language == 'Samoan') { echo 'selected="selected"'; } ?>>Samoan</option>
                      <option value="Serbian" <?php if($lead->language == 'Serbian') { echo 'selected="selected"'; } ?>>Serbian</option>
                      <option value="Slovak" <?php if($lead->language == 'Slovak') { echo 'selected="selected"'; } ?>>Slovak</option>
                      <option value="Slovenian" <?php if($lead->language == 'Slovenian') { echo 'selected="selected"'; } ?>>Slovenian</option>
                      <option value="Spanish" <?php if($lead->language == 'Spanish') { echo 'selected="selected"'; } ?>>Spanish</option>
                      <option value="Swahili" <?php if($lead->language == 'Swahili') { echo 'selected="selected"'; } ?>>Swahili</option>
                      <option value="Swedish " <?php if($lead->language == 'Swedish ') { echo 'selected="selected"'; } ?>>Swedish </option>
                      <option value="Tamil" <?php if($lead->language == 'Tamil') { echo 'selected="selected"'; } ?>>Tamil</option>
                      <option value="Tatar" <?php if($lead->language == 'Tatar') { echo 'selected="selected"'; } ?>>Tatar</option>
                      <option value="Telugu" <?php if($lead->language == 'Telugu') { echo 'selected="selected"'; } ?>>Telugu</option>
                      <option value="Thai" <?php if($lead->language == 'Thai') { echo 'selected="selected"'; } ?>>Thai</option>
                      <option value="Tibetan" <?php if($lead->language == 'Tibetan') { echo 'selected="selected"'; } ?>>Tibetan</option>
                      <option value="Tonga" <?php if($lead->language == 'Tonga') { echo 'selected="selected"'; } ?>>Tonga</option>
                      <option value="Turkish" <?php if($lead->language == 'Turkish') { echo 'selected="selected"'; } ?>>Turkish</option>
                      <option value="Ukrainian" <?php if($lead->language == 'Ukrainian') { echo 'selected="selected"'; } ?>>Ukrainian</option>
                      <option value="Urdu" <?php if($lead->language == 'Urdu') { echo 'selected="selected"'; } ?>>Urdu</option>
                      <option value="Uzbek" <?php if($lead->language == 'Uzbek') { echo 'selected="selected"'; } ?>>Uzbek</option>
                      <option value="Vietnamese" <?php if($lead->language == 'Vietnamese') { echo 'selected="selected"'; } ?>>Vietnamese</option>
                      <option value="Welsh" <?php if($lead->language == 'Welsh') { echo 'selected="selected"'; } ?>>Welsh</option>
                      <option value="Xhosa" <?php if($lead->language == 'Xhosa') { echo 'selected="selected"'; } ?>>Xhosa</option>
                    </select> 
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-2 col-sm-2">Address L1
                  </label> 
                  <div class="col-sm-10 col-md-10">
                    <input type="text" placeholder="Address L1" name="residence_address[address]" class="form-control" value="{{$lead->Current_address}}"> 
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-2 col-sm-2">Address L2
                  </label> 
                  <div class="col-sm-10 col-md-10">
                    <input type="text" placeholder="Address L2" name="residence_address[address_l2]" class="form-control" value="<?php if(!empty($lead->birth_address->address_l2)) { echo $lead->birth_address->address_l2; } ?>"> 
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">City
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Add City" name="residence_address[city]" class="form-control" value="<?php if(!empty($lead->birth_address->city)) { echo $lead->birth_address->city; } ?>">                 
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">State
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <select class="selectpicker" name="residence_address[state]" id="state" data-live-search="true">
                      <?php if($states) { ?> 
                        @foreach ($states as $key => $state)
                        <option value="{{$key}}"
                        <?php if(!empty($lead->birth_address->state)) { ?>
                          @if($lead->birth_address->state == $key)
                        selected="select"
                        @endif
                      <?php } ?>
                        >
                         {{$state}}
                       </option>
                      @endforeach
                     <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Country
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <select class="selectpicker" name="residence_address[country]" id="country" data-live-search="true">
                      <option value="">Select Country</option>
                      @foreach ($countries as $country) 
                      <option value="{{$country->id}}" <?php if(!empty($lead->birth_address->country)) { ?>
                    @if($lead->birth_address->country == $country->id)
                    selected="select"
                    @endif
                    <?php } ?>>
                       {{$country->name}}
                     </option>
                     @endforeach
                   </select>               
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Zipcode
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Zipcode" name="residence_address[zipcode]" class="form-control" value="<?php if(!empty($lead->birth_address->zipcode)) { echo $lead->birth_address->zipcode; } ?>">                 
                  </div>
                </div>
              </div>
            </div>

            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Lead DOB
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Lead DOB" name="dob" value="{{$lead->dob}}" class="form-control dob" > 
                    
                  </div>
                </div> 
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4"> Gender
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <select class="form-control" name="gender">
                      <option value="">Select One</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select> 
                  </div>
                </div>
              </div>
            </div> 
            <div class="form-group row">
              <div class="col-sm-6 col-md-6">
                @csrf
                <input type="hidden" id="" name="lead_id"  value="{{$lead->id}}" class="form-control" >
                <button class="btn btn-primary" type="submit" name="create_firm_lead">
                <span>Create Client</span>
                </button>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="back-btn-new" style="padding: 0; text-align: right;">
                  <a class="btn btn-primary" href="#" style="color: #fff; margin-right: 0;">Cancel</a>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
        </div>
      </div>
  </div>
</section>
@endsection
@push('footer_script')
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('#country').change(function(){
    var cid = $(this).val();
    if(cid){
      $.ajax({
       type:"get",
           url:"{{ url('getStates') }}/"+cid,
           success:function(res)
           {       
            if(res)
            {
              $("#state").empty();
              $("#city").empty();
              $("#state").append('<option>Select State</option>');
              $.each(res,function(key,value){
                $("#state").append('<option value="'+key+'">'+value+'</option>');
              });
              $('#state').selectpicker('refresh');
            }
          }

        });
    }
  });
  $('#state').change(function(){
    var sid = $(this).val();
    if(sid){
      $.ajax({
       type:"get",
           url:"{{ url('getCities') }}/"+sid, 
           success:function(res)
           {       
            if(res)
            {
              $("#city").empty();
              $("#city").append('<option>Select City</option>');
              $.each(res,function(key,value){
                $("#city").append('<option value="'+key+'">'+value+'</option>');
              });
            }
          }

        });
    }
  });


  $('.phone_us').mask('(000) 000-0000');
  $('.dob').mask('00/00/0000');

  $('.is_portal_access').on('click', function(){
    if ($('input.is_portal_access').is(':checked')) {
      $('input[name="email"]').prop('required', true);
      $('.emailerr').show();
    }
    else {
      $('input[name="email"]').prop('required', false);
      $('.emailerr').hide();
    }
  });
});
</script>
@endpush 