@extends('firmlayouts.client-family')

@section('title')
Add Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details task-new-header-tasks">
<!--new-header open-->
  @include('firmclientfamily.cases.include.case_header')
<!--new-header Close-->
  
   <div class="section-body task-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="profile-new-client">
              <form action="{{ url('firm/clientfamilydashboard/insert_family_task') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
                
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Task Type *
                    </label> 
                    <div class="col-sm-12 col-md-7">
                     <select name="type" class="selectpicker form-control" required="required">
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
                     <input type="text" placeholder="Due date" name="date" class="form-control datepicker" required="" value="">
                    </div>
                  </div>
                
                  
                  <div class="form-group row mb-4">
                  <!--<label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                  </label>--> 
                  <div class="col-sm-12 col-md-7">
                    <input type="hidden" name="case_id" value="{{ $case->case_id }}" >  
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

//================ Edit user ============//

</script>

@endpush 
