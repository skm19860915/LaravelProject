@extends('layouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details task-new-header">
<!--new-header open-->
  @include('admin.case.case_header')
<!--new-header Close-->

   <div class="section-body task-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="profile-new-client">
              <form action="{{ url('admin/allcases/updatecasetask') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
              
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Task Type *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                    <select name="type" class="selectpicker form-control" required>
                      <option value="">Select One</option>
                      <option value="Reminder" <?php if($task->type == 'Reminder') { echo 'selected'; } ?>>Reminder</option>
                      <option value="Consultation" <?php if($task->type == 'Consultation') { echo 'selected'; } ?>>Consultation</option>
                      <option value="Court Date" <?php if($task->type == 'Court Date') { echo 'selected'; } ?>>Court Date</option>
                      <option value="Other" <?php if($task->type == 'Other') { echo 'selected'; } ?>>Other</option>
                      <option value="CustomText" <?php
                    if(!in_array($task->type, array('Reminder', 'Consultation', 'Court Date', 'Other'))) {
                      echo 'selected';
                    } ?>>Custom Text</option>
                    </select>
                    <?php
                    if(in_array($task->type, array('Reminder', 'Consultation', 'Court Date', 'Other'))) {
                      echo '<input type="hidden" name="" placeholder="Write Here..." class="form-control CustomText" value="" style="margin-top: 15px;">';
                    }
                    else {
                      echo '<input type="text" name="type" placeholder="Write Here..." class="form-control CustomText" value="'.$task->type.'" style="margin-top: 15px;" required>';
                    }
                    ?>
                  </div>
                </div>
                
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Title *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                   <input type="text" placeholder="Event Title" name="title" class="form-control" value="{{$task->title}}" required>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Description *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                    <textarea placeholder="Write here...." name="description" class="form-control" required>{{$task->description}}</textarea>
                  </div>
                </div>
                
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Due Date *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                   <input type="text" placeholder="Event date" name="date" class="form-control datepicker" required value="{{$task->e_date}}">
                  </div>
                </div>
              
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Status *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                    <select name="status" class="selectpicker form-control" required>
                      <option value="0" <?php if($task->status == 0) { echo 'selected'; } ?>>Open</option>
                      <option value="1" <?php if($task->status == 1) { echo 'selected'; } ?>>Closed</option>
                    </select>
                  </div>
                </div>

                <div class="form-group row mb-4">
                <!--<label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                </label>--> 
                <div class="col-sm-12 col-md-7">
                  <input type="hidden" name="case_id" value="{{ $case->id }}" > 
                  <input type="hidden" name="tid" value="{{ $task->id }}" >  
                 @csrf
                <input type="submit" name="save" value="Update" class="btn btn-primary saveclientinfo_form"/>
                </div>
              </div>
                
            </form>
            </div>   
          </div>
         </div>
      </div>
     </div>
  </div>
  
</section>
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">

//================ Edit user ============//

</script>

@endpush 
