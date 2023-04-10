@extends('firmlayouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details new_task_notes">
<!--new-header open-->
  @include('firmadmin.firmclient.dashboard.client_header')
<!--new-header Close-->
 
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/clientcase') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
          </div>
          <div class="card-body">
            
            <div class="profile-new-client">
             <h2>Family</h2>
             <div class="family-main-box"> 
              <div class="row">
                <?php 
                $QuestionsArr=array(
                    'Petitioner'=>'58bd6f6e02',
                    'Principal Beneficiary'=>'c190d60db9',
                    'Firm'=>'282505ebbb',
                    'Derivative Beneficiary'=>'3cc1ec0e1f',
                    'Household Member'=>'3dcc61d98e',
                    'Co Sponsor'=>'a013381c7e',
                );
                $u= base64_encode(url('firm/firmclient/document_requests/'.$case->case_id));
            $additional_service = json_decode($case->additional_service);
            if($client) { ?>
              <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="family-main-border-box">
               <div class="family-header-address">
                <h3>{{$client->first_name}} {{$client->middle_name}} {{$client->last_name}}</h3>
                <p>{{$client->email}} </p>
               </div>
               <div class="family-info-text-general"><span>Gender</span>
                <?php 
                if($client->gender == 1) {
                  echo 'Male';
                }
                else if($client->gender == 2) {
                  echo 'Female';
                }
                else {
                  echo '';
                }
                ?>
               </div>
               <div class="family-info-text-general"><span>Phone Number</span> {{$client->cell_phone}}</div>
               <div class="family-info-text-general"><span>Date Of Birth</span> {{$client->dob}}</div>               
               <div class="family-info-text-general"><span>Type</span> Petitioner</div>
               <div class="family-info-text-general"><span></span> 
                  
                </div>
               <!-- <a href="{{ url('firm/case/add_case_family/')}}/{{$case->id}}" class="btn btn-primary">Add Beneficiary</a> -->
               <!-- <a href="{{ url('admin/usertask/familyforms')}}/{{$case->case_id}}/{{$client->user_id}}" class="btn btn-primary">View Forms</a> -->
               <a href="{{ url('firm/firmclient/family_document_requests')}}/{{$case->case_id}}/{{$client->user_id}}" class="btn btn-primary">View Documents</a>
               <a href="#" data-shortcode="{{$QuestionsArr['Petitioner']}}" data-userID="{{$client->user_id}}" data-case_id="{{$case->case_id}}" data-return="<?php echo $u; ?>" class="btn btn-primary OpenQuestionsByType">Questions</a>
              </div>
             </div>
            <?php }
            if(!empty($petitioner_list)) {
            foreach ($petitioner_list as $key => $value) { 
               ?>
             <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="family-main-border-box">
               <div class="family-header-address">
                <h3>{{$value->name}}</h3>
                <p>{{$value->email}} </p>
               </div>
               <div class="family-info-text-general"><span>Gender</span> {{$value->gender}}</div>
               <div class="family-info-text-general"><span>Phone Number</span> {{$value->phon_number}}</div>
               <div class="family-info-text-general"><span>Date Of Birth</span> {{$value->dob}}</div> 
                <div class="family-info-text-general"><span>Type</span> 
                  Petitioner
                </div>
                <div class="family-info-text-general"><span></span> 
                  
                </div>
               <!-- <a href="{{ url('admin/usertask/familyforms')}}/{{$case->case_id}}/{{$value->uid}}" class="btn btn-primary">View Forms</a> -->
               <a href="{{ url('firm/firmclient/family_document_requests')}}/{{$case->case_id}}/{{$value->uid}}" class="btn btn-primary">View Documents</a>
               <a href="#" data-shortcode="{{$QuestionsArr['Petitioner']}}" data-userID="{{$value->uid}}" data-case_id="{{$case->case_id}}" data-return="<?php echo $u; ?>" class="btn btn-primary OpenQuestionsByType">Questions</a>
              </div>
             </div>
             
           <?php } } 
             $family_arr = array();
             $beneficiary_check = true;
           if(!empty($beneficiary_list)) {
            
            foreach ($beneficiary_list as $key => $value) { 
              $beneficiary_check = false;
               ?>
             <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="family-main-border-box">
               <div class="family-header-address">
                <h3>{{$value->name}}</h3>
                <p>{{$value->email}} </p>
               </div>
               <div class="family-info-text-general"><span>Gender</span>
                <?php 
                if($value->gender == 1) {
                  echo 'Male';
                }
                else if($value->gender == 2) {
                  echo 'Female';
                }
                ?>
               </div>
               <div class="family-info-text-general"><span>Phone Number</span> {{$value->cell_phone}}</div>
               <div class="family-info-text-general"><span>Date Of Birth</span> {{$value->dob}}</div> 
                <div class="family-info-text-general"><span>Type</span> 
                  Beneficiary
                </div>
                <div class="family-info-text-general"><span></span> 
                  
                </div>
               <a href="{{ url('firm/firmclient/family_document_requests')}}/{{$case->case_id}}/{{$value->uid}}" class="btn btn-primary">View Documents</a>
               <a href="#" data-shortcode="{{$QuestionsArr['Principal Beneficiary']}}" data-userID="{{$value->uid}}" data-case_id="{{$case->case_id}}" data-return="<?php echo $u; ?>" class="btn btn-primary OpenQuestionsByType">Questions</a>
              </div>
             </div>
             
           <?php } }
              foreach ($family_list as $key => $value) { 
                $family_arr[] = $value->uid; ?>
             <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="family-main-border-box">
               <div class="family-header-address">
                <h3>{{$value->name}}</h3>
                <p>{{$value->email}} </p>
               </div>
               <div class="family-info-text-general"><span>Gender</span> {{$value->gender}}</div>
               <div class="family-info-text-general"><span>Phone Number</span> {{$value->phon_number}}</div>
               <div class="family-info-text-general"><span>Date Of Birth</span> {{$value->dob}}</div> 
               <?php 
               $beneficiary = get_user_meta($value->uid, 'beneficiary', 1);
                $CID=array();   
               if(!empty($beneficiary)) {
                 foreach($beneficiary as $v)
                 {
                    $CID[]=$v->meta_value;
                 }
               }
               $qbtncheck = false;
               if(in_array($case->id, $CID)) {
               $beneficiary_check = false;
               $qbtncheck = true; ?>            
                <div class="family-info-text-general"><span>Type</span> 
                  Beneficiary
                </div>
                <div class="family-info-text-general"><span>Relationship</span> {{$value->relationship}}</div>
                <?php }
                $kk = 'memberof_'.$case->id;
                $memberof = get_user_meta($value->uid, $kk);
                $memberof = json_decode($memberof);
                if(!empty($memberof)) {
                   ?>
                  <div class="family-info-text-general"><span>Member Of</span> 
                    <?php echo getUserName($memberof->memberof)->name; ?>
                  </div>
                  <div class="family-info-text-general"><span>Relationship</span> 
                    {{ $memberof->relationship }}</div>
                <?php   
                }
                ?>
               <!-- <a href="{{ url('admin/usertask/familyforms')}}/{{$case->case_id}}/{{$value->uid}}" class="btn btn-primary">View Forms</a> -->
               <a href="{{ url('firm/firmclient/family_document_requests')}}/{{$case->case_id}}/{{$value->uid}}" class="btn btn-primary">View Documents</a>
               <?php 
               if($qbtncheck) { ?>
               <a href="#" data-shortcode="{{$QuestionsArr['Principal Beneficiary']}}" data-userID="{{$value->uid}}" data-case_id="{{$case->case_id}}" data-return="<?php echo $u; ?>" class="btn btn-primary OpenQuestionsByType">Questions</a>
                <?php } ?>
              </div>
             </div>
             
           <?php }

          if(!empty($derivative_list)) {
            foreach ($derivative_list as $key => $value) { 
                $family_arr[] = $value->uid; ?>
             <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="family-main-border-box">
               <div class="family-header-address">
                <h3>{{$value->name}}</h3>
                <p>{{$value->email}} </p>
               </div>
               <div class="family-info-text-general"><span>Gender</span> 
                <?php 
                if($value->gender == 1) {
                  echo 'Male';
                }
                else if($value->gender == 2) {
                  echo 'Female';
                }
                else {
                  echo '';
                }
                ?>
               </div>
               <div class="family-info-text-general"><span>Phone Number</span> {{$value->phon_number}}</div>
               <div class="family-info-text-general"><span>Date Of Birth</span> {{$value->dob}}</div> 
                <div class="family-info-text-general"><span>Type</span> 
                  Derivative beneficiary with own DS-260 only
                </div>
                <div class="family-info-text-general"><span></span> 
                  
                </div>
               <!-- <a href="{{ url('admin/usertask/familyforms')}}/{{$case->case_id}}/{{$value->uid}}" class="btn btn-primary">View Forms</a> -->
               <a href="{{ url('firm/firmclient/family_document_requests')}}/{{$case->case_id}}/{{$value->uid}}" class="btn btn-primary">View Documents</a>
               <a href="#" data-shortcode="{{$QuestionsArr['Derivative Beneficiary']}}" data-userID="{{$value->uid}}" data-case_id="{{$case->case_id}}" data-return="<?php echo $u; ?>" class="btn btn-primary OpenQuestionsByType">Questions</a>
              </div>
             </div>
             
           <?php } }
          $interpreter_listC = true;
          if(!empty($interpreter_list)) {
            foreach ($interpreter_list as $key => $value) { 
              $interpreter_listC = false;;
                $family_arr[] = $value->uid; ?>
             <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="family-main-border-box">
               <div class="family-header-address">
                <h3>{{$value->name}}</h3>
                <p>{{$value->email}} </p>
               </div>
               <div class="family-info-text-general"><span>Gender</span> {{$value->gender}}</div>
               <div class="family-info-text-general"><span>Phone Number</span> {{$value->phon_number}}</div>
               <div class="family-info-text-general"><span>Date Of Birth</span> {{$value->dob}}</div> 
                <div class="family-info-text-general"><span>Type</span> 
                  Interpreter
                </div>
                <div class="family-info-text-general"><span></span> 
                  
                </div>

              </div>
             </div>
             
           <?php } } 
           if($case->case_category == 'Adjustment of Status' || $case->case_category == 'NVC Packet') { 

              
              if(!empty($additional_service->additional_service->additional_service)) {
                foreach ($additional_service->additional_service->additional_service as $k1 => $v1) {
                    if($v1 == 'I-864, Affidavit of Support (Co Sponsor)') {
                        $f_type="Co_Sponsor";
                    }
                    else {
                        $f_type="Household_Member";
                    }

               if($f_type == "Co_Sponsor" && !empty($Co_Sponsor)) { ?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="family-main-border-box">
                   <div class="family-header-address">
                    <h3>{{$Co_Sponsor->name}}</h3>
                    <p>{{$Co_Sponsor->email}} </p>
                   </div>
                   <div class="family-info-text-general"><span>Gender</span> 
                    <?php 
                if($Co_Sponsor->gender == 1) {
                  echo 'Male';
                }
                else if($Co_Sponsor->gender == 2) {
                  echo 'Female';
                }
                else {
                  echo '';
                }
                ?></div>
                   <div class="family-info-text-general"><span>Phone Number</span> {{$Co_Sponsor->phon_number}}</div>
                   <div class="family-info-text-general"><span>Date Of Birth</span> {{$Co_Sponsor->dob}}</div> 
                    <div class="family-info-text-general"><span>Type</span> 
                      {{$v1}}
                    </div>
                    <div class="family-info-text-general"><span></span> 
                      
                    </div>
                   <!-- <a href="{{ url('admin/usertask/familyforms')}}/{{$case->case_id}}/{{$Co_Sponsor->uid}}" class="btn btn-primary">View Forms</a> -->
                   <a href="{{ url('firm/firmclient/family_document_requests')}}/{{$case->case_id}}/{{$Co_Sponsor->uid}}" class="btn btn-primary">View Documents</a>
                   <a href="#" data-shortcode="{{$QuestionsArr['Co Sponsor']}}" data-userID="{{$Co_Sponsor->uid}}" data-case_id="{{$case->case_id}}" data-return="<?php echo $u; ?>" class="btn btn-primary OpenQuestionsByType">Questions</a>
                  </div>
                 </div>
               <?php }
               else if($f_type == "Household_Member" && !empty($Household_Member)) { ?>
                 <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="family-main-border-box">
                     <div class="family-header-address">
                      <h3>{{$Household_Member->name}}</h3>
                      <p>{{$Household_Member->email}} </p>
                     </div>
                     <div class="family-info-text-general"><span>Gender</span> 
                      <?php 
                      if($Household_Member->gender == 1) {
                        echo 'Male';
                      }
                      else if($Household_Member->gender == 2) {
                        echo 'Female';
                      }
                      else {
                        echo '';
                      }
                      ?></div>
                     <div class="family-info-text-general"><span>Phone Number</span> {{$Household_Member->phon_number}}</div>
                     <div class="family-info-text-general"><span>Date Of Birth</span> {{$Household_Member->dob}}</div> 
                      <div class="family-info-text-general"><span>Type</span> 
                        {{$v1}}
                      </div>
                      <div class="family-info-text-general"><span></span> 
                        
                      </div>
                     <!-- <a href="{{ url('admin/usertask/familyforms')}}/{{$case->case_id}}/{{$Household_Member->uid}}" class="btn btn-primary">View Forms</a> -->
                     <a href="{{ url('firm/firmclient/family_document_requests')}}/{{$case->case_id}}/{{$Household_Member->uid}}" class="btn btn-primary">View Documents</a>
                     <a href="#" data-shortcode="{{$QuestionsArr['Household Member']}}" data-userID="{{$Household_Member->uid}}" data-return="<?php echo $u; ?>" data-case_id="{{$case->case_id}}" class="btn btn-primary OpenQuestionsByType">Questions</a>
                    </div>
                   </div>
               <?php }
                } } } ?>
               </div>
              </div>
                            
           </div>
            
          </div>
        </div>
      </div>
     </div>
  </div>
</section>
<div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">  
      <div class="col-md-12">
        <textarea name="note" class="form-control" placeholder="Write your note here..." style="height: 150px;"></textarea>
      </div>
    </div>
    <div class="row">  
      <div class="col-md-12 text-right">
        <input type="hidden" name="case_id" value="{{ $case->id }}" >  
        @csrf
        <input type="submit" name="save" value="Create Client Note" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $("#fire-modal-2").fireModal({title: 'Add A New Note', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var case_id = $('input[name="case_id"]').val();
      var note = $('textarea[name="note"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/case/add_case_notes') }}",
        data: {_token:_token, note:note, case_id:case_id},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/case/case_notes') }}/{{ $case->id }}";
          }
          else {
            alert('Mendatory fields are required!')
          }
          console.log(res);
        }
      });
    });
});

//================ Edit user ============//

</script>

@endpush 
