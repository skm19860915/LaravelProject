@extends('layouts.admin-master')

@section('title')
Vacation Request
@endsection

@push('header_styles')

@endpush  

@section('content')
<section class="section client-listing-details task-new-header-tasks">
  <div class="section-header">
    <h1>Vacation Request</h1>
    <div class="section-header-breadcrumb">

    </div>
  </div>
  <div class="section-body task-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="profile-new-client">
              <form action="{{ url('admin/send_leave_application') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
                  
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Description *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                    <textarea placeholder="Write here...." name="description" class="form-control" required="required"></textarea>
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
                  <div class="col-sm-12 col-md-7">
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
<script type="text/javascript">
$(document).ready(function(){
    setTimeout(function(){
      $('.datepicker').daterangepicker({
          timePicker: true,
          endDate: moment().startOf('hour').add(32, 'hour'),
          locale: {
            format: 'MM/DD/YYYY hh:mm A'
          },
          minDate: new Date()
      });
    }, 1000);
});
</script>

@endpush 
