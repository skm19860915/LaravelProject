@extends('layouts.admin-master')

@section('title')
{{$admintask->task}}
@endsection

@section('content')
<section class="section client-edit-box client-add-box">
  <div class="section-header">
    <h1><a href="{{url('admin/allcases')}}"><span>Case/</span></a> {{$admintask->task}}</h1>
    <div class="section-header-breadcrumb">

    </div>

  </div>
  <div class="section-body">
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
        <form action="{{ url('admin/allcases/update') }}" method="post" class="" enctype="multipart/form-data">
          <div class="back-btn-new">
            <a href="{{ url('admin/allcases') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-body">


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Task Priority 
              </label> 
              <div class="col-sm-12 col-md-7">
                  <select class="form-control" required="" name="priority">
                  <option value="1">Urgent</option>
                  <option value="2">High</option>
                  <option value="3">Medium</option>
                  <option value="4">Low</option>
                </select>
                <div class="invalid-feedback">Please select Priority Type!</div>
              </div>
            </div> 
          	
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">VP User
          		</label> 
          		<div class="col-sm-12 col-md-7">
                <?php 
                //if(empty($admintask->allot_user_id)) { ?>
          			<select class="form-control" required="" name="vauser">
          				<option value="0">Select</option>
                  <?php foreach ($vauser as $key => $value) { ?>
                    
                  <option value="{{$value->id}}"  
                  @if ($value->id == $admintask->allot_user_id)
                    selected="selected"
                  @endif
                  >{{$value->name}} ({{$value->role_name}})</option>
                  <?php } ?>
          			</select> 
              <?php // } ?>
          			<div class="invalid-feedback">Please select User Type!</div>
              </div>
          	</div> 
            <input type="hidden" name="mytask" value="">
            <input type="hidden" name="client_task" value="">
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			@csrf
                <input type="hidden" name="task_id" class="form-control" value="{{$admintask->id}}" /> 
          			<button class="btn btn-primary" type="submit" name="">
          				<span>Save</span>
          			</button>
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
<style type="text/css">
.curruncy_symbol {
position: absolute;
left: 25px;
top: 12px;
}  
.case_cost {
  padding-left: 20px !important;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
  $('.leave_act').on('change', function(){
    var v = $(this).val();
    if(v == 'Unapproved') {
      $('.leave_comment').show();
    }
    else {
      $('.leave_comment').hide();
    }
  });
});  
</script>
@endpush
