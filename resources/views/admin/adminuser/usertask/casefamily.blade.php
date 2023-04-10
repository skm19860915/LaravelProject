@extends('layouts.admin-master')

@section('title')
Show Case family
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
#Addmembersmodal {
  position: fixed !important;
}  
#Addmembersmodal .modal-dialog {
  max-width: 800px !important;
}
#Addmembersmodal .form-client-edit-input label {
  padding-top: 0;
}
</style>
@endpush 

@section('content')
<section class="section client-listing-details">
<!--new-header open-->
  @include('admin.adminuser.usertask.task_header')
<!--new-header Close-->
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('admin/all_case') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
          
          <div class="profile-new-client">
           <h2>Family</h2>
           
           <div class="family-main-box">


            <div class="row">

              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="family-main-border-box">
                  <div class="table-responsive table-invoice all-case-table">
                    <table class="table table table-bordered"  id="tablefamily" >
                      <thead>
                        <tr>
                          <th>Family Member</th>
                          <th>Relation</th>
                          <th>Type</th>
                          <th>Add</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if(!empty($family_alllist)) {
                          foreach ($family_alllist as $key => $value) { ?>
                            <tr>
                              <td>{{$value->name}}</td>
                              <td>{{$value->relationship}}</td>
                              <td>
                                {{$value->type}}
                              </td>
                              <td>
                                <label class="custom-switch mt-2" style="padding-left: 0;">
                                  <input type="checkbox" name="is_added_access" class="custom-switch-input is_added_access" value="1" data-uid="{{$value->uid}}" data-type="{{$value->type}}" <?php if(in_array($value->uid, $em)) { echo 'checked'; } ?>>
                                  <span class="custom-switch-indicator"></span>
                                  <span class="custom-switch-description"></span>
                                </label>
                              </td>
                            </tr>
                        <?php } } ?>
                        
                      </tbody>
                    </table>
                  </div>
                </div>
               </div>

            <?php 
            $additional_service = json_decode($case->additional_service);
            if($client) { ?>
              <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="family-main-border-box">
               <div class="family-header-address">
                <h3>{{$client->first_name}} {{$client->middle_name}} {{$client->last_name}}</h3>
                <p>{{$client->email}} </p>
               </div>
               <div class="family-info-text-general"><span>Gender</span>
                {{$client->gender}}
               </div>
               <div class="family-info-text-general"><span>Phone Number</span> {{$client->cell_phone}}</div>
               <div class="family-info-text-general"><span>Date Of Birth</span> {{$client->dob}}</div>               
               <div class="family-info-text-general"><span>Type</span> Petitioner</div>
               <div class="family-info-text-general"><span></span> 
                  
                </div>
               <a href="{{ url('admin/usertask/caseforms/')}}/{{$admintask->id}}" class="btn btn-primary">View Forms</a>
               <a href="{{ url('admin/usertask/familydocuments') }}/{{$admintask->id}}/{{$client->user_id}}" class="btn btn-primary">View Documents</a>
               <?php $u= base64_encode(url('firm/case/case_family/'.$case->id)); ?>
               <a href="{{ url('admin/usertask/profile/')}}/{{$admintask->id}}" class="btn btn-primary">Questions</a>
              </div>
             </div>
            <?php }
             $family_arr = array();
              foreach ($family_list as $key => $value) { 
                $family_arr[] = $value->uid; ?>
             <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="family-main-border-box">
               <div class="family-header-address">
                <h3>{{$value->name}}</h3>
                <p>
                  <?php 
                    if (strpos($value->email, 'dummy') !== false) {
                        echo ' ';
                    }
                    else {
                      echo $value->email;
                    }
                  ?>
                </p>
                <!-- <a href="{{url('firm/case/edit_family')}}/{{$case->id}}/{{$value->uid}}?view=1" class="action_btn customedit_btn" title="View Member Details" style="right: 75px;"><img src="{{url('assets/images/icon')}}/Group 557.svg" /></a>
                <a href="{{url('firm/case/edit_family')}}/{{$case->id}}/{{$value->uid}}" class="action_btn customedit_btn" title="Edit Member"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a> -->
               </div>
               <div class="family-info-text-general"><span>Gender</span> {{$value->gender}}</div>
               <div class="family-info-text-general"><span>Phone Number</span> {{$value->phon_number}}</div>
               <div class="family-info-text-general"><span>Date Of Birth</span> {{$value->dob}}</div>            
               <!-- <div class="family-info-text-general"><span>Relationship</span> {{$value->relationship}}</div> -->
               <div class="family-info-text-general"><span>Type</span> {{$value->type}}</div>
               <a href="{{ url('admin/usertask/caseforms/')}}/{{$admintask->id}}" class="btn btn-primary">View Forms</a>
               <a href="{{ url('admin/usertask/familydocuments')}}/{{$admintask->id}}/{{$value->uid}}" class="btn btn-primary">View Documents</a>
               <?php $u= base64_encode(url('firm/case/case_family/'.$case->id)); ?>
               <a href="{{ url('admin/usertask/profile/')}}/{{$admintask->id}}" class="btn btn-primary">Questions</a>
              </div>
             </div>
             
           <?php } ?>
            </div>




            
           </div>
           
          </div>
            
           
          </div>
      </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>

<div class="modal fade" id="Addmembersmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form action="{{ url('admin/usertask/createderivativeincase') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add member</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
       <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
         <div class="form-client-edit-input">
          <div class="row">
           <div class="col-md-4 col-sm-4 col-xs-12"><label>First Name</label></div>
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
           <div class="col-md-4 col-sm-4 col-xs-12"><label>Last Name</label></div>
           <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" placeholder="Last Name" name="last_name" class="form-control">                 
           </div>
          </div>
          </div>
         </div>
         
         <div class="col-md-6 col-sm-6 col-xs-12">
         <div class="form-client-edit-input">
          <div class="row">
           <div class="col-md-4 col-sm-4 col-xs-12"><label>Email <span style="color: red">*</span></label></div>
           <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="email" placeholder="Contact Email" name="email" class="form-control" required="required"> 
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
            <input type="text" placeholder="Cell Phone" name="phon_number" class="form-control phone_us">                 
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
        
        
        <div class="row" style="display: none;">
        
         <div class="col-md-6 col-sm-6 col-xs-12">
         <div class="form-client-edit-input">
          <div class="row">
           <div class="col-md-4 col-sm-4 col-xs-12"><label>Type</label></div>
           <div class="col-md-8 col-sm-8 col-xs-12">
            <select class="form-control" name="type">
              <option value="Petitioner">Petitioner</option>
              <option value="Principal Beneficiary">Principal Beneficiary</option>
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
            <input type="text" value="" name="residence_address[address]" class="form-control" placeholder="Address">                               
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
         
         <div class="col-md-6 col-sm-6 col-xs-12">
         <div class="form-client-edit-input">
          <div class="row">
           <div class="col-md-4 col-sm-4 col-xs-12"><label>State</label></div>
           <div class="col-md-8 col-sm-8 col-xs-12">
            <select class="form-control" name="residence_address[state]" id="r_state">
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
           <div class="col-md-4 col-sm-4 col-xs-12"><label>Zipcode</label></div>
           <div class="col-md-8 col-sm-8 col-xs-12">
            <input class="form-control" name="residence_address[zipcode]" placeholder="Client Zipcode">
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
            <input type="text" placeholder="mm/dd/yyyy" name="dob" class="form-control dob">                 
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
      </div>
      <div class="modal-footer">
        <input type="hidden" name="relationship" value="">
        <input type="hidden" name="type" value="">
        <input type="hidden" name="derivative" value="derivative">
        <input type="hidden" name="case_id" value="{{$case->id}}">
        <input type="hidden" name="task_id" value="{{$admintask->id}}">
        <?php 
        if($client) { ?>
        <input type="hidden" name="client_id" value="{{$client->id}}">
        <?php } else { ?>
          <input type="hidden" name="client_id" value="0">
        <?php } ?>
        @csrf
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
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
          }
        }

      });
  }
});
$(document).ready(function(){
  $('.phone_us').mask('(000) 000-0000');
  $('.dob').mask('00/00/0000');
  $('.addexistingmember').on('click', function(e){
    e.preventDefault();
    var v = $(this).closest('.col-md-6').find('select[name="family_id[]"]').val();
    var type = $(this).data('type');
    if(v != '') {
      console.log(v);
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('admin/usertask/addderivativeincase') }}",
        data: { family_id: v, _token:_token, case_id:  "{{$case->id}}", type : type},
        success:function(res)
        {       
          window.location.href = "{{ url('admin/usertask/casefamily') }}/{{$admintask->id}}";
        }
      });
    }
    else {
      alert('Please select member');
    }
  });
  $('.showmemberform').on('click', function(e){
    e.preventDefault();
    var type = $(this).data('type');
    $('#Addmembersmodal').modal('show');
    $('#Addmembersmodal input[name="type"]').val(type);
  });
  $('.datepicker1').daterangepicker({
    locale: {format: 'MM/DD/YYYY'},
    singleDatePicker: true,
    timePicker: false,
    timePicker24Hour: false,
    maxDate: new Date()
  });
  $('.is_added_access').on('click', function(){
    var checked = 0;
    if ($(this).is(':checked')) {
      checked = 1;
    }
    var v = $(this).data('uid');
    var type = $(this).data('type');
    var _token = $('input[name="_token"]').val();
    $.ajax({
      type:"post",
      url:"{{ url('admin/usertask/addderivativeincase') }}",
      data: { family_id: v, _token:_token, case_id:  "{{$case->id}}", type : type, checked: checked},
      success:function(res)
      {       
        if(checked) {
          alert('Member added successfully in case');
        }
        else {
          alert('Member remove successfully from case');
        }
        window.location.href = "{{ url('admin/usertask/casefamily') }}/{{$admintask->id}}";
      }
    });
  });
});
</script>
@endpush