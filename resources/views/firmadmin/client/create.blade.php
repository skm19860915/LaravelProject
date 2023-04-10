@extends('firmlayouts.admin-master')

@section('title')
Create client
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
@endpush

@section('content')
<section class="section client-listing-details client-edit-box client-add-box">
  <div class="section-header">
    <h1><a href="{{route('firm.client')}}"><span>Client/</span></a> Add New Client</h1>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/client/create_client') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/client') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
             
           </div>
          <div class="card-body">
            <h4 style="margin-bottom: 20px;">Client Information
              <div class="clent-info text-right" style="float: right;width: 50%;margin-bottom: 0px;"><span>Portal Access</span>:
                <span>
                  <label class="custom-switch mt-2" style="padding-left: 0;">
                    <input type="checkbox" name="is_portal_access" class="custom-switch-input is_portal_access" value="1" checked>
                    <span class="custom-switch-indicator" style="width: 48px;"></span>
                    <span class="custom-switch-description"></span>
                  </label>
                </span>
               </div>
            </h4>
            <div class="col-md-12 col-sm-12 col-xs-12" style="display: none;">
            <div class="user-upload-img">
             <div class="user-upload-file">
              <label>
               <input type="file" style="display:none;"/>
               <img src="{{ url('/') }}/assets/images/icon/photograph-icon.svg">
               <br />
               <span>Upload Client's Image</span>
              </label>
             </div>
            </div>
           </div>
           
           <div class="edit-my-form-box">
            
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>First Name</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="First Name" name="first_name" class="form-control" required="" value="<?php if(isset(Session::get('data')['first_name'])) { echo Session::get('data')['first_name']; }?>"> 
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
                <input type="text" placeholder="Middle Name" name="middle_name" class="form-control" value="<?php if(isset(Session::get('data')['middle_name'])) { echo Session::get('data')['middle_name']; }?>">                
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
                <input type="text" placeholder="Last Name" name="last_name" class="form-control" value="<?php if(isset(Session::get('data')['last_name'])) { echo Session::get('data')['last_name']; }?>">                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Email <span style="color: red;" class="">*</span></label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="email" placeholder="Contact Email" name="email" class="form-control" required="required" value="<?php if(isset(Session::get('data')['email'])) { echo Session::get('data')['email']; }?>"> 
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
                <input type="text" placeholder="Cell Phone" name="cell_phone" class="form-control phone_no" value="<?php if(isset(Session::get('data')['cell_phone'])) { echo Session::get('data')['cell_phone']; }?>">                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Language</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="selectpicker" name="language" data-live-search="true">
                      <option value="">Select One</option>
                      <option value="Afrikaans">Afrikaans</option>
                      <option value="Albanian">Albanian</option>
                      <option value="Arabic">Arabic</option>
                      <option value="Armenian">Armenian</option>
                      <option value="Basque">Basque</option>
                      <option value="Bengali">Bengali</option>
                      <option value="Bulgarian">Bulgarian</option>
                      <option value="Catalan">Catalan</option>
                      <option value="Cambodian">Cambodian</option>
                      <option value="Chinese (Mandarin)">Chinese (Mandarin)</option>
                      <option value="Croatian">Croatian</option>
                      <option value="Czech">Czech</option>
                      <option value="Danish">Danish</option>
                      <option value="Dutch">Dutch</option>
                      <option value="English">English</option>
                      <option value="Estonian">Estonian</option>
                      <option value="Fiji">Fiji</option>
                      <option value="Finnish">Finnish</option>
                      <option value="French">French</option>
                      <option value="Georgian">Georgian</option>
                      <option value="German">German</option>
                      <option value="Greek">Greek</option>
                      <option value="Gujarati">Gujarati</option>
                      <option value="Hebrew">Hebrew</option>
                      <option value="Hindi">Hindi</option>
                      <option value="Hungarian">Hungarian</option>
                      <option value="Icelandic">Icelandic</option>
                      <option value="Indonesian">Indonesian</option>
                      <option value="Irish">Irish</option>
                      <option value="Italian">Italian</option>
                      <option value="Japanese">Japanese</option>
                      <option value="Javanese">Javanese</option>
                      <option value="Korean">Korean</option>
                      <option value="Latin">Latin</option>
                      <option value="Latvian">Latvian</option>
                      <option value="Lithuanian">Lithuanian</option>
                      <option value="Macedonian">Macedonian</option>
                      <option value="Malay">Malay</option>
                      <option value="Malayalam">Malayalam</option>
                      <option value="Maltese">Maltese</option>
                      <option value="Maori">Maori</option>
                      <option value="Marathi">Marathi</option>
                      <option value="Mongolian">Mongolian</option>
                      <option value="Nepali">Nepali</option>
                      <option value="Norwegian">Norwegian</option>
                      <option value="Persian">Persian</option>
                      <option value="Polish">Polish</option>
                      <option value="Portuguese">Portuguese</option>
                      <option value="Punjabi">Punjabi</option>
                      <option value="Quechua">Quechua</option>
                      <option value="Romanian">Romanian</option>
                      <option value="Russian">Russian</option>
                      <option value="Samoan">Samoan</option>
                      <option value="Serbian">Serbian</option>
                      <option value="Slovak">Slovak</option>
                      <option value="Slovenian">Slovenian</option>
                      <option value="Spanish">Spanish</option>
                      <option value="Swahili">Swahili</option>
                      <option value="Swedish ">Swedish </option>
                      <option value="Tamil">Tamil</option>
                      <option value="Tatar">Tatar</option>
                      <option value="Telugu">Telugu</option>
                      <option value="Thai">Thai</option>
                      <option value="Tibetan">Tibetan</option>
                      <option value="Tonga">Tonga</option>
                      <option value="Turkish">Turkish</option>
                      <option value="Ukrainian">Ukrainian</option>
                      <option value="Urdu">Urdu</option>
                      <option value="Uzbek">Uzbek</option>
                      <option value="Vietnamese">Vietnamese</option>
                      <option value="Welsh">Welsh</option>
                      <option value="Xhosa">Xhosa</option>
                    </select>                                
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row">
            
             
             <div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Portal Access ?</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="is_portal_access">
                  <option value="1" selected>YES</option>
                  <option value="0">NO</option>
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
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Detained ?</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="is_detained">
                  <option value="0">NO</option>
                  <option value="1">YES</option>
                  
                </select>                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Deported ?</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="is_deported">
                  <option value="0">NO</option>
                  <option value="1">YES</option>
                  
                </select>                               
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Outside Of Us ?</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="is_outside_us">
                  <option value="1">YES</option>
                  <option value="0">NO</option>
                </select>                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-12 col-sm-12 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row form-group">
               <div class="col-md-2 col-sm-2 col-xs-12"><label>Address L1</label></div>
               <div class="col-md-10 col-sm-10 col-xs-12">
                <input type="text" name="residence_address[address]" class="form-control" placeholder="Address L1" value="<?php if(isset(Session::get('data')['residence_address']['address'])) { echo Session::get('data')['residence_address']['address']; }?>">                               
               </div>
              </div>
              <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-md-2 col-sm-2 col-xs-12">Address L2
                  </label> 
                  <div class="col-sm-10 col-md-10">
                    <input type="text" placeholder="Address L2" name="residence_address[address_l2]" class="form-control" value="<?php if(isset(Session::get('data')['residence_address']['address_l2'])) { echo Session::get('data')['residence_address']['address_l2']; }?>"> 
                  </div>
                </div>
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
                <input class="form-control" name="residence_address[city]" placeholder="City" value="<?php if(isset(Session::get('data')['residence_address']['city'])) { echo Session::get('data')['residence_address']['city']; }?>" />
               </div>
              </div>
              </div>
             </div>
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>State</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="selectpicker" name="residence_address[state]" id="r_state" data-live-search="true">
                  <option value="">Select State</option>                  
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
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Country</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="selectpicker" name="residence_address[country]" id="r_country" data-live-search="true">
                  <option value="">Select Country</option>
                  @if($countries) 
                  @foreach ($countries as $country) 
                  <option value="{{$country->id}}">
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
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Zipcode</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input class="form-control" name="residence_address[zipcode]" placeholder="Zipcode" value="<?php if(isset(Session::get('data')['residence_address']['zipcode'])) { echo Session::get('data')['residence_address']['zipcode']; }?>">
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>E-Mail</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Mailing Address" name="mailing_address" class="form-control">                 
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Name</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Full Legal Name" name="full_legal_name" class="form-control">               
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Date Of Birth</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="MM/DD/YYYY" name="dob" class="form-control dob" value="<?php if(isset(Session::get('data')['dob'])) { echo Session::get('data')['dob']; } else {  } ?>">                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Gender</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="gender">
                  <option value="Male" <?php if(isset(Session::get('data')['gender']) && Session::get('data')['gender'] == 'Male') { echo 'selected'; }?>>Male</option>
                  <option value="Female" <?php if(isset(Session::get('data')['gender']) && Session::get('data')['gender'] == 'Female') { echo 'selected'; }?>>Female</option>
                </select>                
               </div>
              </div>
              </div>
             </div>
            </div>
            
            
            <div class="client_aliases" style="display: none;">            
            <div class="col-md-12 col-sm-12 col-xs-12">             
             <div class="form-client-edit-input">
              <div class="row">
            <div class="form-group row mb-4 doc_repeater">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Aliases
              </label> 
              <div class="col-sm-12 col-md-4">
                <input type="text" name="client_aliases1[]" class="form-control" placeholder="Aliases" /> 
              </div>
              <div class="col-sm-12 col-md-3">
                <a href="#" class="btn btn-primary add_file">
                  <i class="fa fa-plus"></i>
                </a>
                <a href="#" class="btn btn-primary remove_file">
                  <i class="fa fa-minus"></i>
                </a>
              </div>
            </div> 
            </div>              
              </div>
             </div>                          
            </div>
            
            
            <div class="row" style="display: none;">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Previous Name</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Previous Name" name="previous_name" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Maiden Name</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Maiden Name" name="maiden_name" class="form-control">               
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            
            <div class="row" style="display: none;">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Alien Number</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Alien Number" name="alien_number" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Security Number</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Social Security Number" name="Social_security_number" class="form-control">               
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row" style="display: none;">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Birth Country</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="birth_address1[country]" id="country">
                  <option value="">Select Country</option>
                  @foreach ($countries as $country) 
                  <option value="{{$country->id}}">
                   {{$country->name}}
                 </option>
                 @endforeach
               </select>                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Birth State</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="birth_address1[state]" id="state">
                </select>               
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12"  style="display: none;">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Birth City</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="birth_address1[city]" id="city">
                  <option value="">Select City</option>
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
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Eye Color</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Eye Color" name="eye_color" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Hair Color</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Hair Color" name="hair_color" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row" style="display: none;">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Height</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="height" name="height" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Weight</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Weight" name="weight" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row" style="display: none;">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Race</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Race" name="race" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Ethnicity</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Ethnicity" name="ethnicity" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row" style="display: none;">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Religion</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Religion" name="religion" class="form-control">                
               </div>
              </div>
              </div>
             </div>             
             
            </div>
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Type</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="type">
                  <option value="Petitioner">Petitioner</option>
                  <option value="Self-Petitioner">Self-Petitioner</option>
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
               <div class="col-md-4 col-sm-4 col-xs-12">&nbsp;</div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                  @csrf
                <button class="btn btn-primary" type="submit" name="create_firm_user">
                  <span>Create Client</span>
                </button>              
               </div>
              </div>
              </div>
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
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('#r_country').val(230).change();
  // $('input[name="cell_phone"]').mask('(000) 000-0000');
  $('.dob').mask('00/00/0000');
});
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
$('.is_portal_access').on('click', function(){
    if ($('input.is_portal_access').is(':checked')) {
     // $('input[name="email"]').prop('required', true);
      $('select[name="is_portal_access"]').val(1);
      $('.emailerr').show();
    }
    else {
     // $('input[name="email"]').prop('required', false);
      $('select[name="is_portal_access"]').val(0);
      $('.emailerr').hide();
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
            $('#r_state').selectpicker('refresh');
          }
        }

      });
  }
});
// $('#r_state').change(function(){
//   var sid = $(this).val();
//   if(sid){
//     $.ajax({
//      type:"get",
//          url:"{{ url('getCities') }}/"+sid, 
//          success:function(res)
//          {       
//           if(res)
//           {
//             $("#r_city").empty();
//             $("#r_city").append('<option>Select City</option>');
//             $.each(res,function(key,value){
//               $("#r_city").append('<option value="'+key+'">'+value+'</option>');
//             });
//           }
//         }

//       });
//   }
// });  
$(document).on('click', '.add_file', function(e){
  e.preventDefault();
  var doc_repeater = $(this).closest('.doc_repeater').clone();
  $(doc_repeater).find('input').val('');
  $(this).closest('.doc_repeater').after(doc_repeater);
});
$(document).on('click', '.remove_file', function(e){
  e.preventDefault();
  var doc_repeater = $('.doc_repeater').length;
  if(doc_repeater > 1) {
    $(this).closest('.doc_repeater').remove();
  }
});
$(document).ready(function(){
  $('.datepicker1').daterangepicker({
    locale: {format: 'MM/DD/YYYY'},
    singleDatePicker: true,
    timePicker: false,
    timePicker24Hour: false,
    maxDate: new Date()
  });
  <?php if(isset(Session::get('data')['language'])) { ?>
    $('select[name="language"]').val("<?php echo Session::get('data')['language']; ?>").selectpicker('refresh');
  <?php } ?>
  <?php if(isset(Session::get('data')['residence_address']['country'])) { ?>
    setTimeout(function(){
      $('select[name="residence_address[country]"]').val("<?php echo Session::get('data')['residence_address']['country']; ?>").selectpicker('refresh');
      cid = $('select[name="residence_address[country]"]').val();
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
            $('#r_state').val("<?php echo Session::get('data')['residence_address']['state']; ?>").selectpicker('refresh');
          }
        }
      });
    },1000);
  <?php } ?>
});
</script>
@endpush 