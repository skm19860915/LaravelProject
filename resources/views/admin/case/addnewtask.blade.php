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
              <form action="{{ url('admin/allcases/inserttask') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
                
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Task Type *
                    </label> 
                    <div class="col-sm-12 col-md-7">
                     <select name="type" class="form-control" required="required">
                  <option value="">Select One</option>
                  <option value="Reminder">Reminder</option>
                  <option value="Consultation">Consultation</option>
                  <option value="Court Date">Court Date</option>
                  <option value="Other">Other</option>
                   </select>
                    </div>
                  </div>
                  
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Title *
                    </label> 
                    <div class="col-sm-12 col-md-7">
                     <input type="text" placeholder="Event Title" name="title" class="form-control" value="">
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Description *
                    </label> 
                    <div class="col-sm-12 col-md-7">
                      <textarea placeholder="Write here...." name="description" class="form-control"></textarea>
                    </div>
                  </div>
                  
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Dates *
                    </label> 
                    <div class="col-sm-12 col-md-7">
                     <input type="text" placeholder="Event date" name="date" class="form-control datepicker" required="" value="">
                    </div>
                  </div>
                
                  
                  <div class="form-group row mb-4">
                  <!--<label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                  </label>--> 
                  <div class="col-sm-12 col-md-7">
                    <input type="hidden" name="case_id" value="{{ $case->id }}" >  
                   @csrf
                  <input type="submit" name="save" value="Create Task" class="btn btn-primary saveclientinfo_form"/>
                  </div>
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

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">

//================ Edit user ============//

</script>

@endpush 
