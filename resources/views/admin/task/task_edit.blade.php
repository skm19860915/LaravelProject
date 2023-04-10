@extends('layouts.admin-master')

@section('title')
{{$admintask->task}}
@endsection

@section('content')
<section class="section client-edit-box client-add-box">
  <div class="section-header">
    <h1><a href="{{url('admin/task')}}"><span>Task/</span></a> {{$admintask->task}}</h1>
    <div class="section-header-breadcrumb">

    </div>

  </div>
  <div class="section-body">
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
        <form action="{{ url('admin/task/update_task') }}" method="post" class="" enctype="multipart/form-data">
          <div class="back-btn-new">
            <a href="{{ url('admin/task') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-body">


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Task Priority 
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="" name="priority">
                  <option value="1" <?php if($admintask->priority == 1){ echo "selected"; } ?>>Urgent</option>
                  <option value="2" <?php if($admintask->priority == 2){ echo "selected"; } ?>>High</option>
                  <option value="3" <?php if($admintask->priority == 3){ echo "selected"; } ?>>Medium</option>
                  <option value="4" <?php if($admintask->priority == 4){ echo "selected"; } ?>>Low</option>
                </select>
                <div class="invalid-feedback">Please select Priority Type!</div>
              </div>
            </div> 
          	
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">VP User
          		</label> 
          		<div class="col-sm-12 col-md-7">
                <?php 
                //  || $admintask->task_type == 'Assign_Case'
                if(empty($admintask->allot_user_id)) { ?>
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
              <?php } else { $van = '';  ?>
                <?php foreach ($vauser as $key => $value) { 
                  if ($value->id == $admintask->allot_user_id) {
                    $van = $value->name;
                  }
              } ?>
              <input type="text" class="form-control" value="{{$van}}" readonly="readonly">
              <input type="hidden" name="vauser" class="form-control" value="{{$admintask->allot_user_id}}">
              <?php } ?>
          			<div class="invalid-feedback">Please select User Type!</div>
              </div>
          	</div> 
            <input type="hidden" name="mytask" value="">
            <input type="hidden" name="client_task" value="">
            <?php if($admintask->task_type == 'provide_a_quote') { ?>
             <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Provide a quote
              </label> 
              <div class="col-sm-12 col-md-7">
                <span class="curruncy_symbol">$</span>
                <input class="form-control case_cost" type="text" required="required" name="case_cost" value="{{$quote_cost}}">
                <div class="invalid-feedback">Please Provide a quote</div>
              </div>
            </div>
          <?php } ?>
          <?php if($admintask->task_type == 'upload_translated_document') { ?>
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Old document
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php 
                $document = DB::table('document_request')->where('id', $admintask->case_id)->first();
                $client_file = json_decode($document->document);
                $link = asset('storage/app/'.$client_file[0]);
                echo '<p><a href="'.$link.'" download>'.$document->document_type.'</a></p>';
                ?>
                <label style="color: blue;">Successfully paid for document translation.</label>
              </div>
            </div>
             <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Upload translated document
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="file" name="file[]" value="" required>
                <div class="invalid-feedback fileupload">Upload translated document</div>
              </div>
            </div>
          <?php } ?>
          <?php if($admintask->task_type == 'Leave_Application') {
            // pre($leave_event);
           ?>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Select
              </label> 
              <div class="col-sm-12 col-md-7">
                <div>
                <label class="col-form-label">Date : <?php echo $leave_event->s_date; ?> - <?php echo $leave_event->e_date; ?></label></div>
                <div>
                <label class="col-form-label">Reason : <?php echo $admintask->task; ?></label></div>
                <select class="form-control leave_act" required="required" name="leave_act">
                  <option value="">Select</option>
                  <option value="Approved">Approved</option>
                  <option value="Unapproved">Unapproved</option>
                </select>
                <br>
                <textarea class="form-control leave_comment" name="leave_comment" style="display: none;" placeholder="Write here..."></textarea>
              </div>
            </div>
          <?php } ?>
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
