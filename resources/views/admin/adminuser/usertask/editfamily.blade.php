@extends('layouts.admin-master')

@section('title')
Edit family
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
.addexistingmember {
  right: 220px;
}
.form-control.cls_readonly {
  background: transparent;
  border:0;
}
.cls_readonly button.btn.dropdown-toggle.btn-light {
  border : 0 !important;
}
.cls_readonly .dropdown-menu {
  display: none !important;
}  
</style>
@endpush

@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('admin.adminuser.usertask.task_header')
<!--new-header Close-->
<?php
$readonly = ''; 
if(!empty($_GET['view'])) {
  $readonly = 'readonly';
}
?>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('UpdateFamily') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="back-btn-new">
            <a href="{{ url('admin/usertask/casefamily') }}/{{$id}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-header">          
            <h4>Edit Family Member</h4>
          </div>
          <div class="card-body">
            <div class="addnewmemberform">
              <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>First Name</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="First Name" name="first_name" class="form-control cls_{{$readonly}}" required="" value="{{$FamilyMember->first_name}}" {{$readonly}}> 
                <div class="invalid-feedback">First Name is required!</div>
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Middle Name</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Middle Name" name="middle_name" class="form-control cls_{{$readonly}}" value="{{$FamilyMember->middle_name}}" {{$readonly}}>                
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Last Name</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Last Name" name="last_name" class="form-control cls_{{$readonly}}" value="{{$FamilyMember->last_name}}" {{$readonly}}>                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Email <span style="color: red">*</span></label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="email" placeholder="Contact Email" name="email" class="form-control cls_{{$readonly}}" required="required" value="{{$FamilyMember->email}}" readonly="readonly"> 
                <div class="invalid-feedback">Contact Email is required!</div>               
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Phone No.</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Cell Phone" name="phon_number" class="form-control cls_{{$readonly}}" value="{{$FamilyMember->phon_number}}" {{$readonly}}>                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Language</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="selectpicker cls_{{$readonly}}" name="language" data-live-search="true" {{$readonly}}>
                      <option value="">Select One</option>
                      <option value="Afrikaans" <?php if($FamilyMember->language == 'Afrikaans') { echo 'selected="selected"'; } ?>>Afrikaans</option>
                      <option value="Albanian" <?php if($FamilyMember->language == 'Albanian') { echo 'selected="selected"'; } ?>>Albanian</option>
                      <option value="Arabic" <?php if($FamilyMember->language == 'Arabic') { echo 'selected="selected"'; } ?>>Arabic</option>
                      <option value="Armenian" <?php if($FamilyMember->language == 'Armenian') { echo 'selected="selected"'; } ?>>Armenian</option>
                      <option value="Basque" <?php if($FamilyMember->language == 'Basque') { echo 'selected="selected"'; } ?>>Basque</option>
                      <option value="Bengali" <?php if($FamilyMember->language == 'Bengali') { echo 'selected="selected"'; } ?>>Bengali</option>
                      <option value="Bulgarian" <?php if($FamilyMember->language == 'Bulgarian') { echo 'selected="selected"'; } ?>>Bulgarian</option>
                      <option value="Catalan" <?php if($FamilyMember->language == 'Catalan') { echo 'selected="selected"'; } ?>>Catalan</option>
                      <option value="Cambodian" <?php if($FamilyMember->language == 'Cambodian') { echo 'selected="selected"'; } ?>>Cambodian</option>
                      <option value="Chinese (Mandarin)" <?php if($FamilyMember->language == 'Chinese (Mandarin)') { echo 'selected="selected"'; } ?>>Chinese (Mandarin)</option>
                      <option value="Croatian" <?php if($FamilyMember->language == 'Croatian') { echo 'selected="selected"'; } ?>>Croatian</option>
                      <option value="Czech" <?php if($FamilyMember->language == 'Czech') { echo 'selected="selected"'; } ?>>Czech</option>
                      <option value="Danish" <?php if($FamilyMember->language == 'Danish') { echo 'selected="selected"'; } ?>>Danish</option>
                      <option value="Dutch" <?php if($FamilyMember->language == 'Dutch') { echo 'selected="selected"'; } ?>>Dutch</option>
                      <option value="English" <?php if($FamilyMember->language == 'English') { echo 'selected="selected"'; } ?>>English</option>
                      <option value="Estonian" <?php if($FamilyMember->language == 'Estonian') { echo 'selected="selected"'; } ?>>Estonian</option>
                      <option value="Fiji" <?php if($FamilyMember->language == 'Fiji') { echo 'selected="selected"'; } ?>>Fiji</option>
                      <option value="Finnish" <?php if($FamilyMember->language == 'Finnish') { echo 'selected="selected"'; } ?>>Finnish</option>
                      <option value="French" <?php if($FamilyMember->language == 'French') { echo 'selected="selected"'; } ?>>French</option>
                      <option value="Georgian" <?php if($FamilyMember->language == 'Georgian') { echo 'selected="selected"'; } ?>>Georgian</option>
                      <option value="German" <?php if($FamilyMember->language == 'German') { echo 'selected="selected"'; } ?>>German</option>
                      <option value="Greek" <?php if($FamilyMember->language == 'Greek') { echo 'selected="selected"'; } ?>>Greek</option>
                      <option value="Gujarati" <?php if($FamilyMember->language == 'Gujarati') { echo 'selected="selected"'; } ?>>Gujarati</option>
                      <option value="Hebrew" <?php if($FamilyMember->language == 'Hebrew') { echo 'selected="selected"'; } ?>>Hebrew</option>
                      <option value="Hindi" <?php if($FamilyMember->language == 'Hindi') { echo 'selected="selected"'; } ?>>Hindi</option>
                      <option value="Hungarian" <?php if($FamilyMember->language == 'Hungarian') { echo 'selected="selected"'; } ?>>Hungarian</option>
                      <option value="Icelandic" <?php if($FamilyMember->language == 'Icelandic') { echo 'selected="selected"'; } ?>>Icelandic</option>
                      <option value="Indonesian" <?php if($FamilyMember->language == 'Indonesian') { echo 'selected="selected"'; } ?>>Indonesian</option>
                      <option value="Irish" <?php if($FamilyMember->language == 'Irish') { echo 'selected="selected"'; } ?>>Irish</option>
                      <option value="Italian" <?php if($FamilyMember->language == 'Italian') { echo 'selected="selected"'; } ?>>Italian</option>
                      <option value="Japanese" <?php if($FamilyMember->language == 'Japanese') { echo 'selected="selected"'; } ?>>Japanese</option>
                      <option value="Javanese" <?php if($FamilyMember->language == 'Javanese') { echo 'selected="selected"'; } ?>>Javanese</option>
                      <option value="Korean" <?php if($FamilyMember->language == 'Korean') { echo 'selected="selected"'; } ?>>Korean</option>
                      <option value="Latin" <?php if($FamilyMember->language == 'Latin') { echo 'selected="selected"'; } ?>>Latin</option>
                      <option value="Latvian" <?php if($FamilyMember->language == 'Latvian') { echo 'selected="selected"'; } ?>>Latvian</option>
                      <option value="Lithuanian" <?php if($FamilyMember->language == 'Lithuanian') { echo 'selected="selected"'; } ?>>Lithuanian</option>
                      <option value="Macedonian" <?php if($FamilyMember->language == 'Macedonian') { echo 'selected="selected"'; } ?>>Macedonian</option>
                      <option value="Malay" <?php if($FamilyMember->language == 'Malay') { echo 'selected="selected"'; } ?>>Malay</option>
                      <option value="Malayalam" <?php if($FamilyMember->language == 'Malayalam') { echo 'selected="selected"'; } ?>>Malayalam</option>
                      <option value="Maltese" <?php if($FamilyMember->language == 'Maltese') { echo 'selected="selected"'; } ?>>Maltese</option>
                      <option value="Maori" <?php if($FamilyMember->language == 'Maori') { echo 'selected="selected"'; } ?>>Maori</option>
                      <option value="Marathi" <?php if($FamilyMember->language == 'Marathi') { echo 'selected="selected"'; } ?>>Marathi</option>
                      <option value="Mongolian" <?php if($FamilyMember->language == 'Mongolian') { echo 'selected="selected"'; } ?>>Mongolian</option>
                      <option value="Nepali" <?php if($FamilyMember->language == 'Nepali') { echo 'selected="selected"'; } ?>>Nepali</option>
                      <option value="Norwegian" <?php if($FamilyMember->language == 'Norwegian') { echo 'selected="selected"'; } ?>>Norwegian</option>
                      <option value="Persian" <?php if($FamilyMember->language == 'Persian') { echo 'selected="selected"'; } ?>>Persian</option>
                      <option value="Polish" <?php if($FamilyMember->language == 'Polish') { echo 'selected="selected"'; } ?>>Polish</option>
                      <option value="Portuguese" <?php if($FamilyMember->language == 'Portuguese') { echo 'selected="selected"'; } ?>>Portuguese</option>
                      <option value="Punjabi" <?php if($FamilyMember->language == 'Punjabi') { echo 'selected="selected"'; } ?>>Punjabi</option>
                      <option value="Quechua" <?php if($FamilyMember->language == 'Quechua') { echo 'selected="selected"'; } ?>>Quechua</option>
                      <option value="Romanian" <?php if($FamilyMember->language == 'Romanian') { echo 'selected="selected"'; } ?>>Romanian</option>
                      <option value="Russian" <?php if($FamilyMember->language == 'Russian') { echo 'selected="selected"'; } ?>>Russian</option>
                      <option value="Samoan" <?php if($FamilyMember->language == 'Samoan') { echo 'selected="selected"'; } ?>>Samoan</option>
                      <option value="Serbian" <?php if($FamilyMember->language == 'Serbian') { echo 'selected="selected"'; } ?>>Serbian</option>
                      <option value="Slovak" <?php if($FamilyMember->language == 'Slovak') { echo 'selected="selected"'; } ?>>Slovak</option>
                      <option value="Slovenian" <?php if($FamilyMember->language == 'Slovenian') { echo 'selected="selected"'; } ?>>Slovenian</option>
                      <option value="Spanish" <?php if($FamilyMember->language == 'Spanish') { echo 'selected="selected"'; } ?>>Spanish</option>
                      <option value="Swahili" <?php if($FamilyMember->language == 'Swahili') { echo 'selected="selected"'; } ?>>Swahili</option>
                      <option value="Swedish " <?php if($FamilyMember->language == 'Swedish ') { echo 'selected="selected"'; } ?>>Swedish </option>
                      <option value="Tamil" <?php if($FamilyMember->language == 'Tamil') { echo 'selected="selected"'; } ?>>Tamil</option>
                      <option value="Tatar" <?php if($FamilyMember->language == 'Tatar') { echo 'selected="selected"'; } ?>>Tatar</option>
                      <option value="Telugu" <?php if($FamilyMember->language == 'Telugu') { echo 'selected="selected"'; } ?>>Telugu</option>
                      <option value="Thai" <?php if($FamilyMember->language == 'Thai') { echo 'selected="selected"'; } ?>>Thai</option>
                      <option value="Tibetan" <?php if($FamilyMember->language == 'Tibetan') { echo 'selected="selected"'; } ?>>Tibetan</option>
                      <option value="Tonga" <?php if($FamilyMember->language == 'Tonga') { echo 'selected="selected"'; } ?>>Tonga</option>
                      <option value="Turkish" <?php if($FamilyMember->language == 'Turkish') { echo 'selected="selected"'; } ?>>Turkish</option>
                      <option value="Ukrainian" <?php if($FamilyMember->language == 'Ukrainian') { echo 'selected="selected"'; } ?>>Ukrainian</option>
                      <option value="Urdu" <?php if($FamilyMember->language == 'Urdu') { echo 'selected="selected"'; } ?>>Urdu</option>
                      <option value="Uzbek" <?php if($FamilyMember->language == 'Uzbek') { echo 'selected="selected"'; } ?>>Uzbek</option>
                      <option value="Vietnamese" <?php if($FamilyMember->language == 'Vietnamese') { echo 'selected="selected"'; } ?>>Vietnamese</option>
                      <option value="Welsh" <?php if($FamilyMember->language == 'Welsh') { echo 'selected="selected"'; } ?>>Welsh</option>
                      <option value="Xhosa" <?php if($FamilyMember->language == 'Xhosa') { echo 'selected="selected"'; } ?>>Xhosa</option>
                    </select>                               
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row" style="display: none;">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Type</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="type">
                  <option value="Petitioner" <?php if($FamilyMember->type == 'Petitioner') { echo 'selected="selected"'; } ?>>Petitioner</option>
                  <option value="Principal Beneficiary" <?php if($FamilyMember->type == 'Principal Beneficiary') { echo 'selected="selected"'; } ?>>Principal Beneficiary</option>
                </select>                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Portal Access ?</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="is_portal_access">
                  <option value="1">YES</option>
                  <option value="0">NO</option>
                </select>                               
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            <div class="row">
             <div class="col-md-12 col-sm-12 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-2 col-sm-2 col-xs-12"><label>Address</label></div>
               <div class="col-md-10 col-sm-10 col-xs-12">
                <input type="text" name="residence_address[address]" class="form-control cls_{{$readonly}}" placeholder="Address" value="{{$FamilyMember->residence_address->address}}" {{$readonly}}>                               
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Country</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="selectpicker cls_{{$readonly}}" name="residence_address[country]" id="r_country" data-live-search="true" {{$readonly}}>
                  <option value="">Select Country</option>
                  @if($countries) 
                  @foreach ($countries as $country) 
                  <option value="{{$country->id}}" <?php if($FamilyMember->residence_address->country == $country->id) { echo 'selected="selected"'; } ?>>
                   {{$country->name}}
                 </option>
                 @endforeach
                 @endif
               </select>                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>State</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control cls_{{$readonly}}" name="residence_address[state]" id="r_state" {{$readonly}}>
                  <option value="">Select State</option>     
                  @if($r_states) 
                  @foreach ($r_states as $key => $state) 
                  <option value="{{$key}}" 
                    <?php if(!empty($FamilyMember->residence_address->state)) { ?>
                    @if($FamilyMember->residence_address->state == $key)
                    selected="selected"
                    @endif
                    <?php } ?>
                    >
                   {{$state}}
                 </option>
                 @endforeach
                 @endif             
                </select>                              
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>City</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input class="form-control cls_{{$readonly}}" name="residence_address[city]" placeholder="City" value="{{$FamilyMember->residence_address->city}}" {{$readonly}}/>
               </div>
              </div>
              </div>
             </div>

             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Zipcode</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input class="form-control cls_{{$readonly}}" name="residence_address[zipcode]" placeholder="Client Zipcode" value="{{$FamilyMember->residence_address->zipcode}}" {{$readonly}}>
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row">
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Date Of Birth</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="mm/dd/yyyy" name="dob" class="form-control dob cls_{{$readonly}}" value="{{$FamilyMember->dob}}" {{$readonly}}>                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Gender</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control cls_{{$readonly}}" name="gender" {{$readonly}}>
                  <option value="Male" <?php if($FamilyMember->gender == 'Male'){echo "selected"; }?>>Male</option>
                  <option value="Female" <?php if($FamilyMember->gender == 'Female'){echo "selected"; }?>>Female</option>
                </select>                
               </div>
              </div>
              </div>
             </div>
            </div>

            <div class="row">
             <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-client-edit-input">
                  <div class="row">
                    <label class="col-md-4 col-sm-4 col-xs-12"> Relationship
                    </label> 
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <select class="form-control cls_{{$readonly}}" name="relationship" {{$readonly}}>
                        <option selected="selected" value="">Select Relationship</option>
                        <option value="Mother" <?php if($FamilyMember->relationship == 'Mother') { echo 'selected="selected"'; } ?>>Mother </option>
                        <option value="Father" <?php if($FamilyMember->relationship == 'Father') { echo 'selected="selected"'; } ?>>Father </option>
                        <option value="Brother" <?php if($FamilyMember->relationship == 'Brother') { echo 'selected="selected"'; } ?>>Brother </option>
                        <option value="Sister" <?php if($FamilyMember->relationship == 'Sister') { echo 'selected="selected"'; } ?>>Sister </option>
                        <option value="Son" <?php if($FamilyMember->relationship == 'Son') { echo 'selected="selected"'; } ?>>Son </option>
                        <option value="Daughter" <?php if($FamilyMember->relationship == 'Daughter') { echo 'selected="selected"'; } ?>>Daughter</option>
                        <option value="Grand Mother" <?php if($FamilyMember->relationship == 'Grand Mother') { echo 'selected="selected"'; } ?>>Grand Mother</option>
                        <option value="Grand Father" <?php if($FamilyMember->relationship == 'Grand Father') { echo 'selected="selected"'; } ?>>Grand Father</option>
                        <option value="Husband" <?php if($FamilyMember->relationship == 'Husband') { echo 'selected="selected"'; } ?>>Husband</option>
                        <option value="Wife" <?php if($FamilyMember->relationship == 'Wife') { echo 'selected="selected"'; } ?>>Wife</option>
                      </select> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
              <?php 
              if($firm->account_type == 'CMS') { ?>
              <input type="hidden" name="client_id" value="{{$client->id}}">
              <?php } else { ?>
                <input type="hidden" name="client_id" value="0">
              <?php } ?>
              <input type="hidden" name="redirecturl" value="{{ url('admin/usertask/casefamily') }}/{{$id}}">
              <input type="hidden" name="case_id" value="{{$id}}">
              <input type="hidden" name="fid" value="{{$fid}}">
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                </label> 
                <div class="col-sm-12 col-md-7">
                  @csrf
                  <?php 
                  if(empty($_GET['view'])) { ?>
                  <button class="btn btn-primary" type="submit" name="create_firm_user">
                  <span>Save</span>
                  </button>
                  <?php } ?>
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
@endsection
@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$('#r_country').change(function(){
  var cid = $(this).val();
  if(cid){
    $.ajax({
     type:"get",
         url:"{{ url('getStates') }}/"+cid,
         success:function(res)
         {       
          if(res)
          {
            $("#r_state").empty();
            $("#r_city").empty();
            $("#r_state").append('<option>Select State</option>');
            $.each(res,function(key,value){
              $("#r_state").append('<option value="'+key+'">'+value+'</option>');
            });
          }
        }

      });
  }
});
$(document).ready(function(){
  $('.addnewmemberbtn').on('click', function(){
    $('.addnewmemberform').slideToggle();
  })
  $('.phone_us').mask('(000) 000-0000');
  $('.dob').mask('00/00/0000');
  $(document).on('click', '.addexistingmember', function(e){
    e.preventDefault();
    $("#PayForTranslation").modal('show');
  });
  $('.addexistingmember').on('click', function(){
    var v = $('select[name="family_id[]"]').val();
    if(v != '') {
      console.log(v);
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/case/add_family_incase') }}",
        data: { family_id: v, _token:_token, case_id:  "{{$id}}"},
        success:function(res)
        {       
          window.location.href = "{{ url('firm/case/case_family') }}/{{$id}}";
        }
      });
    }
    else {
      alert('Please select member');
    }
  });
  $('.datepicker1').daterangepicker({
    locale: {format: 'MM/DD/YYYY'},
    singleDatePicker: true,
    timePicker: false,
    timePicker24Hour: false,
    maxDate: new Date()
  });
});  
</script>
@endpush