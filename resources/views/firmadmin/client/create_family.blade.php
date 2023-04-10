@extends('firmlayouts.admin-master')

@section('title')
Create client family
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
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
        <form action="{{ url('firm/client/add_family') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">          
            <div class="back-btn-new">
              <a href="{{ url('firm/client/view_family') }}/{{$id}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div> 
          </div>
          <div class="card-body">
            <h4 style="margin-bottom: 20px;">Add Member
              <div class="clent-info text-right" style="float: right;width: 50%;margin-bottom: 0px; display: none;"><span>Provide this client with Client Portal access</span>:
                <span>
                  <label class="custom-switch mt-2" style="padding-left: 0;">
                    <input type="checkbox" name="is_portal_access" class="custom-switch-input is_portal_access" value="1">
                    <span class="custom-switch-indicator" style="width: 48px;"></span>
                    <span class="custom-switch-description"></span>
                  </label>
                </span>
               </div>
            </h4>
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>First Name <span style="color: red"> *</span></label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="First Name" name="first_name" class="form-control" required=""> 
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
                <input type="text" placeholder="Middle Name" name="middle_name" class="form-control">                
               </div>
              </div>
              </div>
             </div>
             
            </div>
            
            
            <div class="row">
            
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Last Name <span style="color: red"> *</span></label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="Last Name" name="last_name" class="form-control" required>                 
               </div>
              </div>
              </div>
             </div>
             
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Email <span style="color: red; display: none;" class="emailerr"> *</span></label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="email" placeholder="Contact Email" name="email" class="form-control"> 
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
                <input type="text" placeholder="Cell Phone" name="phon_number" class="form-control phone_no">                 
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
            
            
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-md-2 col-sm-2">Address L1
                  </label> 
                  <div class="col-sm-10 col-md-10">
                    <input type="text" placeholder="Address L1" name="residence_address[address]" class="form-control" > 
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-md-2 col-sm-2">Address L2
                  </label> 
                  <div class="col-sm-10 col-md-10">
                    <input type="text" placeholder="Address L2" name="residence_address[address_l2]" class="form-control" > 
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
                <input class="form-control" name="residence_address[city]" placeholder="City" />
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
                 <div class="col-md-4 col-sm-4 col-xs-12"><label>Zipcode</label></div>
                 <div class="col-md-8 col-sm-8 col-xs-12">
                  <input class="form-control" name="residence_address[zipcode]" placeholder="Client Zipcode">
                 </div>
                </div>
                </div>
               </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
               <div class="form-client-edit-input">
                <div class="row">
                 <div class="col-md-4 col-sm-4 col-xs-12"><label>Country</label></div>
                 <div class="col-md-8 col-sm-8 col-xs-12">
                  <select class="selectpicker" name="residence_address[country]" id="r_country" data-live-search="true"Client >
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
              </div>
            
            
            <div class="row">
             <div class="col-md-6 col-sm-6 col-xs-12">
             <div class="form-client-edit-input">
              <div class="row">
               <div class="col-md-4 col-sm-4 col-xs-12"><label>Date Of Birth</label></div>
               <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" placeholder="MM/DD/YYYY" name="dob" class="form-control dob" value="">                 
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
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
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
                    <label class="col-md-4 col-sm-4 col-xs-12"> Relationship <span style="color: red"> *</span>
                    </label> 
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <select class="form-control" name="relationship" required="required">
                        <option selected="selected" value="">Select Relationship</option>
                        <option value="Mother">Mother </option>
                        <option value="Father">Father </option>
                        <option value="Brother">Brother </option>
                        <option value="Sister">Sister </option>
                        <option value="Son">Son </option>
                        <option value="Daughter">Daughter</option>
                        <option value="Grand Mother">Grand Mother</option>
                        <option value="Grand Father">Grand Father</option>
                        <option value="Husband">Husband</option>
                        <option value="Wife">Wife</option>
                      </select> 
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="form-client-edit-input">
                <div class="row">
                 <div class="col-md-4 col-sm-4 col-xs-12"><label>Type <span style="color: red"> *</span></label></div>
                 <div class="col-md-8 col-sm-8 col-xs-12">
                  <select class="form-control" name="type" required>
                    <option value="">Select</option>
                    <option value="Principal Beneficiary">Principal Beneficiary</option>
                    <option value="Beneficiary">Beneficiary</option>
                    <option value="Derivative Beneficiary">Derivative Beneficiary</option>
                    <option value="Qualifying Family Member">Qualifying Family Member</option>
                    <option value="Applicant/Beneficiary">Applicant/Beneficiary</option>
                  </select>             
                 </div>
                </div>
              </div>
             </div>
            </div>
            <input type="hidden" name="client_id" value="{{$id}}">

            <div class="form-group row mb-4">
              <!-- <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
              </label>  -->
              <div class="col-sm-6 col-md-6">
                @csrf
                <button class="btn btn-primary" type="submit" name="create_firm_user">
                <span>Save</span>
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
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>
@endsection
@push('footer_script')
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
            $('#r_state').selectpicker('refresh');
          }
        }

      });
  }
});
$(document).ready(function(){
  $('#r_country').val(230).change();
  $('.datepicker1').daterangepicker({
    locale: {format: 'MM/DD/YYYY'},
    singleDatePicker: true,
    timePicker: false,
    timePicker24Hour: false,
    maxDate: new Date()
  });
  // $('.phone_us').mask('(000) 000-0000');
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