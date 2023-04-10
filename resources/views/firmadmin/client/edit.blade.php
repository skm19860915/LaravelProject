@extends('firmlayouts.admin-master')

@section('title')
Edit Client
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
@endpush

@section('content')
<section class="section client-listing-details client-edit-box">
  <div class="section-header">
    <h1><a href="{{route('firm.client')}}"><span>Client/</span></a> Edit Client</h1>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/client/update') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/client') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
          <h4>Client Information</h4>
           <div class="col-md-12 col-sm-12 col-xs-12" style="display:none;">
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
                <input type="text" name="first_name" class="form-control" value="{{$client->first_name}}" required=""> 
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
                <input type="text" value="{{$client->middle_name}}" name="middle_name" class="form-control">                
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
                <input type="text" value="{{$client->last_name}}" name="last_name" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Email</label> <span style="color: red;">*</span></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="email" value="<?php 
        if(strpos($client->email, 'dummy') !== false) { } else { echo $client->email; } ?>"  disabled="" class="form-control" > 
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
                <input type="text" value="{{$client->cell_phone}}" name="cell_phone" class="form-control phone_no">                
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
                      <option value="Afrikaans" <?php if($client->language == 'Afrikaans') { echo 'selected="selected"'; } ?>>Afrikaans</option>
                      <option value="Albanian" <?php if($client->language == 'Albanian') { echo 'selected="selected"'; } ?>>Albanian</option>
                      <option value="Arabic" <?php if($client->language == 'Arabic') { echo 'selected="selected"'; } ?>>Arabic</option>
                      <option value="Armenian" <?php if($client->language == 'Armenian') { echo 'selected="selected"'; } ?>>Armenian</option>
                      <option value="Basque" <?php if($client->language == 'Basque') { echo 'selected="selected"'; } ?>>Basque</option>
                      <option value="Bengali" <?php if($client->language == 'Bengali') { echo 'selected="selected"'; } ?>>Bengali</option>
                      <option value="Bulgarian" <?php if($client->language == 'Bulgarian') { echo 'selected="selected"'; } ?>>Bulgarian</option>
                      <option value="Catalan" <?php if($client->language == 'Catalan') { echo 'selected="selected"'; } ?>>Catalan</option>
                      <option value="Cambodian" <?php if($client->language == 'Cambodian') { echo 'selected="selected"'; } ?>>Cambodian</option>
                      <option value="Chinese (Mandarin)" <?php if($client->language == 'Chinese (Mandarin)') { echo 'selected="selected"'; } ?>>Chinese (Mandarin)</option>
                      <option value="Croatian" <?php if($client->language == 'Croatian') { echo 'selected="selected"'; } ?>>Croatian</option>
                      <option value="Czech" <?php if($client->language == 'Czech') { echo 'selected="selected"'; } ?>>Czech</option>
                      <option value="Danish" <?php if($client->language == 'Danish') { echo 'selected="selected"'; } ?>>Danish</option>
                      <option value="Dutch" <?php if($client->language == 'Dutch') { echo 'selected="selected"'; } ?>>Dutch</option>
                      <option value="English" <?php if($client->language == 'English') { echo 'selected="selected"'; } ?>>English</option>
                      <option value="Estonian" <?php if($client->language == 'Estonian') { echo 'selected="selected"'; } ?>>Estonian</option>
                      <option value="Fiji" <?php if($client->language == 'Fiji') { echo 'selected="selected"'; } ?>>Fiji</option>
                      <option value="Finnish" <?php if($client->language == 'Finnish') { echo 'selected="selected"'; } ?>>Finnish</option>
                      <option value="French" <?php if($client->language == 'French') { echo 'selected="selected"'; } ?>>French</option>
                      <option value="Georgian" <?php if($client->language == 'Georgian') { echo 'selected="selected"'; } ?>>Georgian</option>
                      <option value="German" <?php if($client->language == 'German') { echo 'selected="selected"'; } ?>>German</option>
                      <option value="Greek" <?php if($client->language == 'Greek') { echo 'selected="selected"'; } ?>>Greek</option>
                      <option value="Gujarati" <?php if($client->language == 'Gujarati') { echo 'selected="selected"'; } ?>>Gujarati</option>
                      <option value="Hebrew" <?php if($client->language == 'Hebrew') { echo 'selected="selected"'; } ?>>Hebrew</option>
                      <option value="Hindi" <?php if($client->language == 'Hindi') { echo 'selected="selected"'; } ?>>Hindi</option>
                      <option value="Hungarian" <?php if($client->language == 'Hungarian') { echo 'selected="selected"'; } ?>>Hungarian</option>
                      <option value="Icelandic" <?php if($client->language == 'Icelandic') { echo 'selected="selected"'; } ?>>Icelandic</option>
                      <option value="Indonesian" <?php if($client->language == 'Indonesian') { echo 'selected="selected"'; } ?>>Indonesian</option>
                      <option value="Irish" <?php if($client->language == 'Irish') { echo 'selected="selected"'; } ?>>Irish</option>
                      <option value="Italian" <?php if($client->language == 'Italian') { echo 'selected="selected"'; } ?>>Italian</option>
                      <option value="Japanese" <?php if($client->language == 'Japanese') { echo 'selected="selected"'; } ?>>Japanese</option>
                      <option value="Javanese" <?php if($client->language == 'Javanese') { echo 'selected="selected"'; } ?>>Javanese</option>
                      <option value="Korean" <?php if($client->language == 'Korean') { echo 'selected="selected"'; } ?>>Korean</option>
                      <option value="Latin" <?php if($client->language == 'Latin') { echo 'selected="selected"'; } ?>>Latin</option>
                      <option value="Latvian" <?php if($client->language == 'Latvian') { echo 'selected="selected"'; } ?>>Latvian</option>
                      <option value="Lithuanian" <?php if($client->language == 'Lithuanian') { echo 'selected="selected"'; } ?>>Lithuanian</option>
                      <option value="Macedonian" <?php if($client->language == 'Macedonian') { echo 'selected="selected"'; } ?>>Macedonian</option>
                      <option value="Malay" <?php if($client->language == 'Malay') { echo 'selected="selected"'; } ?>>Malay</option>
                      <option value="Malayalam" <?php if($client->language == 'Malayalam') { echo 'selected="selected"'; } ?>>Malayalam</option>
                      <option value="Maltese" <?php if($client->language == 'Maltese') { echo 'selected="selected"'; } ?>>Maltese</option>
                      <option value="Maori" <?php if($client->language == 'Maori') { echo 'selected="selected"'; } ?>>Maori</option>
                      <option value="Marathi" <?php if($client->language == 'Marathi') { echo 'selected="selected"'; } ?>>Marathi</option>
                      <option value="Mongolian" <?php if($client->language == 'Mongolian') { echo 'selected="selected"'; } ?>>Mongolian</option>
                      <option value="Nepali" <?php if($client->language == 'Nepali') { echo 'selected="selected"'; } ?>>Nepali</option>
                      <option value="Norwegian" <?php if($client->language == 'Norwegian') { echo 'selected="selected"'; } ?>>Norwegian</option>
                      <option value="Persian" <?php if($client->language == 'Persian') { echo 'selected="selected"'; } ?>>Persian</option>
                      <option value="Polish" <?php if($client->language == 'Polish') { echo 'selected="selected"'; } ?>>Polish</option>
                      <option value="Portuguese" <?php if($client->language == 'Portuguese') { echo 'selected="selected"'; } ?>>Portuguese</option>
                      <option value="Punjabi" <?php if($client->language == 'Punjabi') { echo 'selected="selected"'; } ?>>Punjabi</option>
                      <option value="Quechua" <?php if($client->language == 'Quechua') { echo 'selected="selected"'; } ?>>Quechua</option>
                      <option value="Romanian" <?php if($client->language == 'Romanian') { echo 'selected="selected"'; } ?>>Romanian</option>
                      <option value="Russian" <?php if($client->language == 'Russian') { echo 'selected="selected"'; } ?>>Russian</option>
                      <option value="Samoan" <?php if($client->language == 'Samoan') { echo 'selected="selected"'; } ?>>Samoan</option>
                      <option value="Serbian" <?php if($client->language == 'Serbian') { echo 'selected="selected"'; } ?>>Serbian</option>
                      <option value="Slovak" <?php if($client->language == 'Slovak') { echo 'selected="selected"'; } ?>>Slovak</option>
                      <option value="Slovenian" <?php if($client->language == 'Slovenian') { echo 'selected="selected"'; } ?>>Slovenian</option>
                      <option value="Spanish" <?php if($client->language == 'Spanish') { echo 'selected="selected"'; } ?>>Spanish</option>
                      <option value="Swahili" <?php if($client->language == 'Swahili') { echo 'selected="selected"'; } ?>>Swahili</option>
                      <option value="Swedish " <?php if($client->language == 'Swedish ') { echo 'selected="selected"'; } ?>>Swedish </option>
                      <option value="Tamil" <?php if($client->language == 'Tamil') { echo 'selected="selected"'; } ?>>Tamil</option>
                      <option value="Tatar" <?php if($client->language == 'Tatar') { echo 'selected="selected"'; } ?>>Tatar</option>
                      <option value="Telugu" <?php if($client->language == 'Telugu') { echo 'selected="selected"'; } ?>>Telugu</option>
                      <option value="Thai" <?php if($client->language == 'Thai') { echo 'selected="selected"'; } ?>>Thai</option>
                      <option value="Tibetan" <?php if($client->language == 'Tibetan') { echo 'selected="selected"'; } ?>>Tibetan</option>
                      <option value="Tonga" <?php if($client->language == 'Tonga') { echo 'selected="selected"'; } ?>>Tonga</option>
                      <option value="Turkish" <?php if($client->language == 'Turkish') { echo 'selected="selected"'; } ?>>Turkish</option>
                      <option value="Ukrainian" <?php if($client->language == 'Ukrainian') { echo 'selected="selected"'; } ?>>Ukrainian</option>
                      <option value="Urdu" <?php if($client->language == 'Urdu') { echo 'selected="selected"'; } ?>>Urdu</option>
                      <option value="Uzbek" <?php if($client->language == 'Uzbek') { echo 'selected="selected"'; } ?>>Uzbek</option>
                      <option value="Vietnamese" <?php if($client->language == 'Vietnamese') { echo 'selected="selected"'; } ?>>Vietnamese</option>
                      <option value="Welsh" <?php if($client->language == 'Welsh') { echo 'selected="selected"'; } ?>>Welsh</option>
                      <option value="Xhosa" <?php if($client->language == 'Xhosa') { echo 'selected="selected"'; } ?>>Xhosa</option>
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
                  <option <?php if($client->is_detained == 1){echo "selected"; }?> value="1">YES</option>
                  <option <?php if($client->is_detained == 0){echo "selected"; }?> value="0">NO</option>
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
                  <option <?php if($client->is_deported == 1){echo "selected"; }?> value="1">YES</option>
                  <option <?php if($client->is_deported == 0){echo "selected"; }?> value="0">NO</option>
                </select>                
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            <div class="row" >
            
             <div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Outside Of Us ?</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="is_outside_us">
                  <option <?php if($client->is_outside_us == 1){echo "selected"; }?> value="1">YES</option>
                  <option <?php if($client->is_outside_us == 0){echo "selected"; }?> value="0">NO</option>
                </select>                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-12 col-sm-12 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-2 col-sm-2 col-xs-12"><label>Address</label></div>
               <div class="col-md-10 col-sm-10 col-xs-12">
                <input type="text" value="<?php if(!empty($client->residence_address->address)) echo $client->residence_address->address; ?>" name="residence_address[address]" class="form-control">                
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
                  <option value="{{$country->id}}" 
                    <?php if(!empty($client->residence_address->country)) { ?>
                    @if($client->residence_address->country == $country->id)
                    selected="select"
                    @endif
                    <?php } ?>
                    >
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
                <select class="form-control" name="residence_address[state]" id="r_state">
                  <option value="">Select State</option>
                  @if($r_states) 
                  @foreach ($r_states as $key => $state) 
                  <option value="{{$key}}" 
                    <?php if(!empty($client->residence_address->state)) { ?>
                    @if($client->residence_address->state == $key)
                    selected="select"
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
                <input class="form-control" name="residence_address[city]" placeholder="City" value="<?php if(!empty($client->residence_address->city)) { echo $client->residence_address->city; } ?>" />
               </div>
              </div>
              </div>
             </div>
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Zipcode</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="<?php if(!empty($client->residence_address->zipcode)) { echo $client->residence_address->zipcode; } ?>" name="residence_address[zipcode]" class="form-control" placeholder="Zipcode" >                
               </div>
              </div>
              </div>
             </div>
             <div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Mailing Address</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="{{$client->mailing_address}}" name="mailing_address" class="form-control">                
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
                <input type="text" value="{{$client->dob}}" placeholder="MM/DD/YYYY" name="dob" class="form-control dob">                
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
                  <option <?php if($client->gender == 'Male'){echo "selected"; }?> value="Male">Male</option>
                  <option <?php if($client->gender == 'Female'){echo "selected"; }?> value="Female">Female</option>
                </select>             
               </div>
              </div>
              </div>
             </div>
             <div class="col-md-6 col-sm-6 col-xs-12" style="display: none;">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Full Legal Name</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="{{$client->full_legal_name}}" name="full_legal_name" class="form-control">               
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            
           <div class="client_aliases" style="display: none;">            
            <div class="col-md-12 col-sm-12 col-xs-12">             
             <div class="form-client-edit-input">
              <div class="row">
               <?php if($client->client_aliases) { ?> 
              @foreach($client->client_aliases as $client_aliases)
                <div class="form-group row mb-4 doc_repeater">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Aliases
                </label> 
                <div class="col-sm-12 col-md-4">
                  <input type="text" value="{{$client_aliases}}" name="client_aliases[]" class="form-control">
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
              @endforeach
              <?php } else { ?>
                <div class="form-group row mb-4 doc_repeater">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Aliases
                </label> 
                <div class="col-sm-12 col-md-4">
                  <input type="text" value="" name="client_aliases[]" class="form-control">
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

              <?php } ?>
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
                <input type="text" value="{{$client->previous_name}}" name="previous_name" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Maiden Name</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="{{$client->maiden_name}}" name="maiden_name" class="form-control">               
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
                <input type="text" value="{{$client->alien_number}}" name="alien_number" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Security Number</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="{{$client->Social_security_number}}" name="Social_security_number" class="form-control">              
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
                <select class="form-control" name="birth_address[country]" id="country">
                  <option value="">Select Country</option>
                  @if($countries) 
                  @foreach ($countries as $country) 
                  <option value="{{$country->id}}" 
                    <?php if(!empty($client->birth_address->country)) { ?>
                    @if($client->birth_address->country == $country->id)
                    selected="select"
                    @endif
                    <?php } ?>
                    >
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
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Birth State</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="birth_address[state]" id="state">
                  <option value="">Select State</option>
                  <?php if($states) { ?> 
                    @foreach ($states as $key => $state)
                    <option value="{{$key}}"
                    <?php if(!empty($client->birth_address->state)) { ?>
                      @if($client->birth_address->state == $key)
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
             
            </div>
            
            
            <div class="row" style="display: none;">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Birth City</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="birth_address[city]" id="city">
                  <option value="">Select State</option>
                  <?php if($cities) { ?> 
                    @foreach ($cities as $key => $city)
                    <option value="{{$key}}"
                    <?php if(!empty($client->birth_address->city)) { ?>
                      @if($client->birth_address->city == $key)
                    selected="select"
                    @endif
                  <?php } ?>
                    >
                     {{$city}}
                   </option>
                  @endforeach
                 <?php } ?>
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
                <input type="text" value="{{$client->eye_color}}" name="eye_color" class="form-control">                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Hair Color</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="{{$client->hair_color}}" name="hair_color" class="form-control">             
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row" style="display: none;">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>height</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="{{$client->height}}" name="height" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>weight</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="{{$client->weight}}" name="weight" class="form-control">             
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
             <div class="row" style="display: none;">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>race</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="{{$client->race}}" name="race" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>ethnicity</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="{{$client->ethnicity}}" name="ethnicity" class="form-control">             
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
                <input type="text" value="{{$client->religion}}" name="religion" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>ethnicity</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" value="{{$client->ethnicity}}" name="ethnicity" class="form-control">             
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
                  <option <?php if($client->type == "Petitioner"){echo "selected"; }?> value="Petitioner">Petitioner</option>
                  <option <?php if($client->type == "Self-Petitioner"){echo "selected"; }?> value="Self-Petitioner">Self-Petitioner</option>
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
                  <option <?php if($client->is_portal_access == 1){echo "selected"; }?> value="1">YES</option>
                  <option <?php if($client->is_portal_access == 0){echo "selected"; }?> value="0">NO</option>
                </select>                
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
             <div class="row">
             <input type="hidden" value="{{$client->user_id}}" name="user_id">
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12">&nbsp;</div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                 @csrf
                <button class="btn btn-primary" type="submit" name="create_firm_user">
                  <span>Update Client</span>
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
  setTimeout(function(){
    $('.datepicker1').daterangepicker({
      locale: {format: 'MM/DD/YYYY'},
      singleDatePicker: true,
      timePicker: false,
      timePicker24Hour: false,
      maxDate: new Date()
    });
  }, 1000);
});
</script>
@endpush 