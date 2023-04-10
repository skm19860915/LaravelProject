@extends('layouts.admin-master')

@section('title')
Invoice
@endsection

@push('header_styles')
<style type="text/css">
  .curruncy_symbol {
    position: absolute;
    left: 25px;
    top: 34px;
  }  
  .case_cost {
    padding-left: 25px !important;
  }
</style>
@endpush  

@section('content')
<section class="section invoice_client client-listing-details client-listing-task">
<!--new-header open-->
  @include('admin.adminuser.userclient.client_header') 
<!--new-header Close-->
  <div class="section-body invoice-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
        <div class="back-btn-new">
            <a href="{{ url('admin/userclient/clienttasks') }}/{{$client->id}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-header">            
            <h4>Edit Task</h4>
          </div>
          
          <div class="card-body">
          	<form action="{{ url('admin/userclient/updateclienttask') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          		
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Task Type *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                    <select name="type" class="form-control" required>
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
                   <input type="text" placeholder="Event date" name="date" class="form-control datepicker1" required value="<?php echo date('m/d/Y', strtotime($task->e_date)); ?>">
                  </div>
                </div>
          		
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Status *
                  </label> 
                  <div class="col-sm-12 col-md-7">
                    <select name="status" class="form-control" required>
                      <option value="0" <?php if($task->status == 0) { echo 'selected'; } ?>>Open</option>
                      <option value="1" <?php if($task->status == 1) { echo 'selected'; } ?>>Closed</option>
                    </select>
                  </div>
                </div>

                <div class="form-group row mb-4">
                <!--<label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                </label>--> 
                <div class="col-sm-12 col-md-7">
                  <input type="hidden" name="client_id" value="{{ $id }}" >
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
</section>
@endsection

@push('footer_script')
<script type="text/javascript">
$(document).on('keyup', 'input[name="invoice_items[item_cost][]"], input[name="invoice_items[item_qty][]"]', function(){
  var v = 0;
  $('table.table tr').each(function(){
    var a = $(this).find('input[name="invoice_items[item_cost][]"]').val();
    var q = $(this).find('input[name="invoice_items[item_qty][]"]').val();
    var s = a*q;
    if(s) {  
        v = v+s;
        $(this).find('.trtotal').html('$'+s);
    }
  });
  $('.casecost').val(v);
  $('.totlaamount').html('$'+v);
});
$(document).on('click', '.removetr', function(e){
  e.preventDefault();
  $(this).closest('tr').remove();
  var v = 0;
  $('table.table tr').each(function(){
    var a = $(this).find('input[name="invoice_items[item_cost][]"]').val();
    var q = $(this).find('input[name="invoice_items[item_qty][]"]').val();
    var s = a*q;
    if(s) {  
        v = v+s;
        $(this).find('.trtotal').html('$'+s);
    }
  });
  $('.casecost').val(v);
  $('.totlaamount').html('$'+v);
});
$(document).ready(function(){
  $('.datepicker1').daterangepicker({
    locale: {format: 'MM/DD/YYYY'},
    singleDatePicker: true,
    timePicker: false,
    timePicker24Hour: false,
    minDate: new Date()
  });
  $('.addnewitem').on('click', function(e){
    e.preventDefault();
    var n = $(this).prev('table').find('tbody tr').length;
    var tr = '<tr><td>'+n+'</td>';
      tr += '<td><input type="text" name="invoice_items[item_name][]" class="form-control" value="" required="required"></td>';
      tr += '<td class="text-center" style="position: relative;">';
      tr += '<span class="curruncy_symbol" style="top:22px;">$</span>';
      tr += '<input type="number" name="invoice_items[item_cost][]" class="form-control case_cost" value="" required="required">';
      tr += '</td><td class="text-center">';
      tr += '<input type="number" name="invoice_items[item_qty][]" class="form-control" value="1" required="required"></td>';
      tr += '<td class="text-right trtotal">$0</td><td>';
      tr += '<a href="#" class="btn btn-primary removetr"><i class="fa fa-times"></i></a></td></tr>';
    $(this).prev('table').find('tbody').append(tr);
  });
  $('select[name="type"]').on('change', function(){
    var v = $(this).val();
    if(v == 'CustomText') {
      $('.CustomText').attr('type', 'text');
      $('.CustomText').attr('name', 'type');
      $('.CustomText').prop('required', true);
    }
    else {
      $('.CustomText').attr('type', 'hidden');
      $('.CustomText').attr('name', '');
      $('.CustomText').prop('required', false);
    }
  });
});  
</script>
@endpush 
