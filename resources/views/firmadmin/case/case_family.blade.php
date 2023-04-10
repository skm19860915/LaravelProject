@extends('firmlayouts.admin-master')

@section('title')
Show Case family
@endsection

@push('header_styles')

@endpush 

@section('content')
<section class="section client-listing-details">
<!--new-header open-->
 @include('firmadmin.case.case_header')
<!--new-header Close-->
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/case/allcase') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
             <h4></h4>
             <!-- <a href="{{ url('firm/case/add_case_interpreter/')}}/{{$case->id}}" class="btn btn-primary card-header-action">
              Add Interpreter
             </a> -->
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
               <a href="{{ url('firm/case/case_forms/')}}/{{$case->id}}" class="btn btn-primary">View Forms</a>
               <a href="{{ url('firm/case/view_family_documents/')}}/{{$case->id}}/{{$client->user_id}}" class="btn btn-primary">View Documents</a>
               <?php $u= base64_encode(url('firm/case/case_family/'.$case->id)); ?>
               <a href="{{ url('firm/case/profile/')}}/{{$case->id}}"  class="btn btn-primary">Questions</a>
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
               <a href="{{ url('firm/case/case_forms/')}}/{{$case->id}}" class="btn btn-primary">View Forms</a>
               <a href="{{ url('firm/case/view_family_documents/')}}/{{$case->id}}/{{$value->uid}}" class="btn btn-primary">View Documents</a>
               <?php $u= base64_encode(url('firm/case/case_family/'.$case->id)); ?>
               <a href="{{ url('firm/case/profile/')}}/{{$case->id}}"  class="btn btn-primary">Questions</a>
              </div>
             </div>
             
           <?php } ?>
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
      url:"{{ url('firm/case/add_derivative_incase') }}",
      data: { family_id: v, _token:_token, case_id:  "{{$case->id}}", type : type, checked: checked},
      success:function(res)
      {       
        if(checked) {
          alert('Member added successfully in case');
        }
        else {
          alert('Member remove successfully from case');
        }
        window.location.href = "{{ url('firm/case/case_family') }}/{{$case->id}}";
      }
    });
  });
});
</script>
@endpush