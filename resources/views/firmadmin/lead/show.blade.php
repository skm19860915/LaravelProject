@extends('firmlayouts.admin-master')

@section('title')
View Lead
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
  .leadupdateform input.form-control,
  .leadupdateform textarea.form-control {
    display: inline-block;
    width: calc(100% - 200px);
  }
  .leadupdateform input.form-control:read-only,
  .leadupdateform textarea.form-control:read-only {
    background: transparent;
    border: 0;
    padding: 0;
    height: auto;
    color: #949DB2;
    font-size: 13px;
    font-weight: 500;
  }
  .client-listing-details .dropdown.bootstrap-select {
    width: calc(100% - 200px) !important;
  }
  .selectedfiles {
    padding: 0;
    margin: 0;
  }  
  .selectedfiles li {
    display: inline-block;
    vertical-align: top;
    margin-bottom: 15px;
    position: relative;
    width: 90px;
    margin-right: 10px;
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
  a.add_file {
    padding: 0 !important;
    width: 110px;
    height: 35px;
    line-height: 35px;
    color: #ffffff;
    margin-top: 23px;
  }
  .client-listing-details .readonlyselect button.btn.dropdown-toggle.btn-light {
    border: none !important;
    padding: 0;
    color: #949DB2;
    font-size: 13px;
    font-weight: 500;
  }
  .client-listing-details .readonlyselect .dropdown-menu {
    display: none !important;
  }
  /*.selectedfiles li.addfileli {
    display: none;
  }*/
  .selectedfiles li label {
  display: block;
  text-align: center;
}
</style>
@endpush 

@section('content')
<section class="section client-listing-details">
  <div class="section-header">
    <h1><a href="{{route('firm.lead')}}"><span>Lead /</span></a> Detail</h1>
    <div class="section-header-breadcrumb">
      <a href="{{ url('firm/lead/edit') }}/{{$lead->id}}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> Convert to client</a>      
    </div>
  </div>
  <div class="client-header-new">
   <div class="clent-profle-box">
    <div class="row">
     <div class="col-md-8">
      <div class="client-main-box-profile">
      <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
      <div class="client-right-text">
       <h3>
         <?php 
         echo $lead->name.' '.$lead->last_name;
         ?>
         <a href="#" class="action_btn customedit_btn" title="Edit Lead" data-toggle="tooltip" style="position: static;" data-id="{{$lead->id}}"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" style="width: 13px;" /></a>
       </h3>
       <p>{{ $lead->email }}<br />{{ $lead->cell_phone }}<br />
        Create Date : {{ date('M d, Y', strtotime($lead->created_at)) }}</p>
      </div>  
      </div>    
     </div>
     <div class="col-md-4">
      <div class="client-right-profile">
       <div class="clent-info"><span>Lead ID</span>:<span>#{{ $lead->id }}</span></div>
       <div class="clent-info"><span>Deported</span>:
        <span>
          <label class="custom-switch mt-2" style="padding-left: 0;">
            <input type="checkbox" name="is_deported" class="custom-switch-input is_deported" value="1" <?php echo $retVal = ($lead->is_deported == 1) ? "checked" : ""; ?>>
            <span class="custom-switch-indicator" style="width: 48px;"></span>
            <span class="custom-switch-description"></span>
          </label>
        </span>
      </div>
       <div class="clent-info"><span>Detained</span>:
        <span>
          <label class="custom-switch mt-2" style="padding-left: 0;">
            <input type="checkbox" name="is_detained" class="custom-switch-input is_detained" value="1" <?php echo $retVal = ($lead->is_detained == 1) ? "checked" : ""; ?>>
            <span class="custom-switch-indicator" style="width: 48px;"></span>
            <span class="custom-switch-description"></span>
          </label>
        </span>
       </div>
      </div>
     </div>
    </div>
   </div>
   <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'firm.lead.show' ? 'active-menu' : '' }}" href="{{ url('firm/lead/show') }}/{{ $lead->id }}">Overview</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.lead.billing' ? 'active-menu' : '' }}" href="{{ url('firm/lead/billing') }}/{{ $lead->id }}">Billing</a></li>
    </ul>
   </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card"> 
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/lead') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>      
          <div class="card-body">
            <div class="profile-new-client">
              <div class="profle-text-section">
                <form action="{{ url('firm/lead/update_lead') }}" method="post" class="leadupdateform needs-validation" enctype="multipart/form-data" novalidate="">
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <h4>General Information</h4> 
                      <div class="info-text-general"><span>First Name</span> 
                        <input type="text" class="form-control" name="name" value="<?php if(!empty($lead->name)) { echo $lead->name; } else { echo 'NA'; } ?>"></div>
                      <div class="info-text-general"><span>Last Name</span> 
                        <input type="text" class="form-control" name="last_name" value="<?php if(!empty($lead->last_name)) { echo $lead->last_name; } else { echo 'NA'; } ?>"></div>
                      <div class="info-text-general"><span>Email</span> 
                        <input type="text" class="form-control" name="email" value="<?php if(!empty($lead->email)) { echo $lead->email; } else { echo 'NA'; } ?>"></div>
                      <div class="info-text-general"><span>Cell Phone</span> 
                        <input type="text" class="form-control phone_us" name="cell_phone" value="<?php if(!empty($lead->cell_phone)) { echo $lead->cell_phone; } else { echo 'NA'; } ?>"></div>
                      <div class="info-text-general"><span>Home Phone</span> 
                        <input type="text" class="form-control phone_us" name="home_phone" value="<?php if(!empty($lead->home_phone)) { echo $lead->home_phone; } else { echo ''; } ?>"></div>
                      <div class="info-text-general"><span>DOB</span> 
                        <input type="text" class="form-control dob" name="dob" value="<?php if(!empty($lead->dob)) { echo $lead->dob; } else { echo 'NA'; } ?>" placeholder="mm/dd/yyyy"></div>
                      <div class="info-text-general">
                        <span>Address L1</span>
                          <input type="text" class="form-control" name="Current_address" value="<?php if(!empty($lead->Current_address)) { echo $lead->Current_address; } else { echo 'NA'; } ?>">
                      </div> 
                      <div class="info-text-general">
                        <span>Address L2</span>
                          <input type="text" class="form-control" name="birth_address1[address_l2]" value="<?php if(!empty($lead->birth_address->address_l2)) { echo ($lead->birth_address->address_l2); } else { echo 'NA'; } ?>">
                      </div> 
                       <div class="info-text-general"><span>City</span>
                        <input type="text" class="form-control" name="birth_address1[city]" value="<?php if(!empty($lead->birth_address->city)) { echo ($lead->birth_address->city); } else { echo 'NA'; } ?>"> </div> 
                       <div class="info-text-general"><span>State</span>
                        <select class="selectpicker" name="birth_address1[state]" id="state" data-live-search="true">
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
                       <div class="info-text-general"><span>Country</span>
                        <select class="selectpicker" name="birth_address1[country]" id="country" data-live-search="true">
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
                      <div class="info-text-general"><span>Zipcode</span>
                       <input type="text" class="form-control" name="birth_address1[zipcode]" value="<?php if(!empty($lead->birth_address->zipcode)) { echo ($lead->birth_address->zipcode); } else { echo 'NA'; } ?>"> </div> 
                      <div class="info-text-general"><span>Language</span> 
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
                      <div class="info-text-general"><span style="vertical-align: top;">Note</span> <textarea name="lead_note" class="summernote-simple form-control" style="height: 150px;">{{$lead->lead_note}}</textarea> </div>
                      <?php 
                       if ($lead->status != 2) { ?>
                          <!-- <a href="{{url('firm/lead/create_client')}}/{{ $lead->id }}"> 
                            <button class="btn btn-primary">Convert to Client</button>
                          </a> -->
                      <?php } ?>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="text-right">
                        <?php 
                        if(empty($event)) { ?>
                        <a href="{{url('firm/lead/create_event')}}/{{$lead->id}}" class="btn btn-primary">Schedule Event</a>
                        <?php } else { ?>
                          <a href="{{url('firm/lead/create_event')}}/{{$lead->id}}?reschedule=1" class="btn btn-primary">Re-Schedule Event</a>
                        <?php } ?>
                      </div>

                        <br><br>
                      <?php 
                      if(!empty($event)) { ?>
                        
                        <div class="info-text-general"><span>Event</span> <?php echo $event->event_type; ?> - <?php echo $event->event_title; ?></div>
                        <div class="info-text-general"><span>Date & Time</span> <?php echo date('m/d/Y', strtotime($event->s_date)).' '.$event->s_time; ?></div>
                        <div class="info-text-general"><span>Attorney</span> <?php echo $attorney_users; ?></div>
                      <?php } ?>
                      <div class="info-text-general"><span>Documents</span></div>
                      <div class="info-text-general">
                        <ul class="selectedfiles">
                      <?php 
                      if($lead->document_path) {
                        $document_path = json_decode($lead->document_path);
                        
                        foreach ($document_path as $k => $v) { 
                          $fileurl = asset('storage/app/'.$v);
                          $name = basename($fileurl); 
                          $ext = pathinfo($fileurl, PATHINFO_EXTENSION); 
                          $name2 =pathinfo($fileurl, PATHINFO_FILENAME); 
                          $ext = strtolower($ext);
                          $img = asset('assets/images/icon').'/'.$ext.'.png';
                          if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                            $img = $fileurl;
                          }
                          ?>
                          <li>
                          <a href="{{$fileurl}}" download>
                            <input type="text" name="document_path1[]" value="{{$v}}"/>
                            <img src="{{$img}}" alt="{{$name2}}" width="100px">
                            <label>{{$name}}</label>
                          </a>
                          </li>
                        <?php } } ?>
                      
                      <li class="addfileli">
                        <a href="#" class="add_file btn btn-primary">
                          <i class="fa fa-plus"></i> Upload File
                        </a>
                      </li>
                      </ul>
                        
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 text-right">
                      @csrf
                      <input type="hidden" name="is_deported" value="{{$lead->is_deported}}">
                      <input type="hidden" name="is_detained" value="{{$lead->is_detained}}">
                      <input type="hidden" id="" name="lead_id"  value="{{$lead->id}}" class="form-control" >
                      <input type="submit" name="update_lead" value="Save" class="btn btn-primary update_lead" style="display: none;">
                      <a href="{{url('firm/lead')}}" class="btn btn-primary">Exit</a>
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
</section>
@endsection
@push('footer_script')
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
  function readURL(input, id) {
    var imgname = input.files[0].name.split('.');
    var ext = imgname[imgname.length-1];
    ext = ext.toLowerCase();
      if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            var src = "{{ asset('assets/images/icon') }}/"+ext+".png";
            if(ext == 'png' || ext == 'jpg' || ext == 'jpeg') {
              src = e.target.result;
            }
            $(document).find('#fileimg_'+id)
                .attr('src', src)
                .width(90)
                .height(90).css('display', 'block');
            var lb = '<label>'+input.files[0].name+'</label>';
            $(document).find('#fileimg_'+id).after(lb);
          };

          reader.readAsDataURL(input.files[0]);
      }
  }
  $(document).ready(function(){
    $('.phone_us').mask('(000) 000-0000');
    $('.dob').mask('00/00/0000');
    $('.leadupdateform input,.leadupdateform textarea,.leadupdateform select').on('change', function(){
      $('.update_lead').show();
    });
    $('.customedit_btn').on('click', function(e){
      e.preventDefault();
      $('.leadupdateform input.form-control').prop('readonly', false);
      $('.leadupdateform textarea.form-control').prop('readonly', false);
      // $('.update_lead').show();
      $('.readonlyselect').removeClass('readonlyselect');
      $('.selectedfiles li.addfileli').show();
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
    $('.is_deported').on('click', function(){
      if ($('input.is_deported').is(':checked')) {
        $('input[name="is_deported"]').val(1);
      }
      else {
        $('input[name="is_deported"]').val(0);
      }
    });
    $('.is_detained').on('click', function(){
      if ($('input.is_detained').is(':checked')) {
        $('input[name="is_detained"]').val(1);
      }
      else {
        $('input[name="is_detained"]').val(0);
      }
    });
  });
  $(document).on('click', '.add_file', function(e){
    e.preventDefault();
    $('.nofile').hide();
    var n = $('.selectedfiles li:not(.addfileli)').length;
    var li = '<li><a href="#"><input type="file" name="document_path[]" onchange="readURL(this, '+n+');" id="file_'+n+'"/><img src="" id="fileimg_'+n+'" style="display:none;"/></a><a href="#" class="remove_file">x</a></li>';
    //var doc_repeater = $(this).closest('.row').prev('.doc_repeater').clone();
    $('.addfileli').before(li);
    $(document).find('#file_'+n).trigger('click');
  });
  $(document).on('click', '.remove_file', function(e){
    e.preventDefault();
    $(this).closest('li').remove();
  });
</script>
@endpush