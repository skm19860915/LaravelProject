@extends('layouts.admin-master')

@section('title')
View Case
@endsection

@section('content')
<section class="section client-listing-details task-new-header">
<!--new-header open-->
  @include('admin.case.case_header')
<!--new-header Close-->
 
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            
            <div class="profile-new-client">
             <h2>Family</h2>
             <div class="family-main-box"> 
              <div class="row">
                <?php if(!empty($admintask)) { ?>
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

              <?php }
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
                 <a href="{{ url('admin/allcases/caseforms') }}/{{$case->id}}" class="btn btn-primary">View Forms</a>
                 <a href="{{ url('admin/allcases/casedocuments') }}/{{$case->id}}" class="btn btn-primary">View Documents</a>
                 <?php $u= base64_encode(url('firm/case/case_family/'.$case->id)); ?>
                 <a href="{{ url('admin/allcases/profile') }}/{{$case->id}}" class="btn btn-primary">Questions</a>
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
                 <a href="{{ url('admin/allcases/caseforms')}}/{{$case->id}}" class="btn btn-primary">View Forms</a>
                 <a href="{{ url('admin/allcases/casedocuments')}}/{{$case->id}}" class="btn btn-primary">View Documents</a>
                 <?php $u= base64_encode(url('firm/case/case_family/'.$case->id)); ?>
                 <a href="{{ url('admin/allcases/profile') }}/{{$case->id}}" class="btn btn-primary">Questions</a>
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
      url:"{{ url('admin/allcases/addderivativeincase1') }}",
      data: { family_id: v, _token:_token, case_id:  "{{$case->id}}", type : type, checked: checked},
      success:function(res)
      {       
        if(checked) {
          alert('Member added successfully in case');
        }
        else {
          alert('Member remove successfully from case');
        }
        window.location.href = "{{ url('admin/allcases/casefamily') }}/{{$case->id}}";
      }
    });
  });
});

//================ Edit user ============//

</script>

@endpush 
