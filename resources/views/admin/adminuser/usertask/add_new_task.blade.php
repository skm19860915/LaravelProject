@extends('layouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details task-new-header-tasks">
<!--new-header open-->
  @include('admin.adminuser.usertask.task_header')
<!--new-header Close-->
  
   <div class="section-body task-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('admin/usertask/tasks') }}/{{$admintask->id}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
            <div class="profile-new-client">
              <form action="{{ url('admin/usertask/insert_task') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
                
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
                      <option value="CustomText">Custom Text</option>
                    </select>
                    <input type="hidden" name="" placeholder="Write Here..." class="form-control CustomText" value="" style="margin-top: 15px;">
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
                     <input type="text" placeholder="Event date" name="date" class="form-control datepicker1" required="" value="">
                    </div>
                  </div>
                
                  
                  <div class="form-group row mb-4">
                  <!--<label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                  </label>--> 
                  <div class="col-sm-12 col-md-7">
                    <input type="hidden" name="id" value="{{ $admintask->id }}" > 
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

var index_url = "{{route('admin.usertask.getData')}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'firm_name', name: 'firm_name'},
        { data: 'task_type', name: 'task_type'},
        { data: 'task', name: 'task'},
        { data: 'case_id', name: 'case_id'},
        { data: 'allot_user_id', name: 'allot_user_id'},
        { data: 'priority', name: 'priority'},
        { data: 'stat', name: 'stat'},
        
        { data: null,
          render: function(data){
            var view_button = '';
            if(data.case_id) {
              view_button = ' <a href="{{url('admin/document_request')}}/'+data.case_id+'" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
            }
              var time_button = ' <a href="{{url('admin/task/timeline')}}/'+data.case_id+'" class="btn btn-primary"><i class="fa fa-clock"></i></a>';
              var edit_button = ' <a href="{{url('admin/task/edit')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
              return view_button;

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
 });
$(document).ready(function(){
  $('.datepicker1').daterangepicker({
    locale: {format: 'MM/DD/YYYY'},
    singleDatePicker: true,
    timePicker: false,
    timePicker24Hour: false,
    minDate: new Date()
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
//================ Edit user ============//

</script>

@endpush 
