@extends('layouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details task-new-header-family">
<!--new-header open-->
  @include('admin.adminuser.usertask.task_header')
<!--new-header Close-->

  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/client/add_family') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          
          <div class="card-body">
          
          <div class="profile-new-client">
           <h2>Family</h2>
           
           <div class="family-main-box">
            <div class="row">
            
            <?php foreach ($family_list as $key => $value) { ?>
             <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="family-main-border-box">
               <div class="family-header-address">
                <h3>{{$value->name}}</h3>
                <p>{{$value->email}} </p>
               </div>
               <div class="family-info-text-general"><span>Gender</span> {{$value->gender}}</div>
               <div class="family-info-text-general"><span>Phone Number</span> {{$value->phon_number}}</div>
               <div class="family-info-text-general"><span>Date Of Birth</span> {{$value->dob}}</div>               
               <div class="family-info-text-general"><span>Relationship</span> {{$value->relationship}}</div>
              </div>
             </div>
             
           <?php } ?>  
             
            </div>
           </div>
           
          </div>
            
           
          </div>
        </form>
      </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
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
