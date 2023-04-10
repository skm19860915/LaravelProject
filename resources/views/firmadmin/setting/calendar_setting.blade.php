@extends('firmlayouts.admin-master')

@section('title')
Calendar Setting
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Calendar Setting</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.setting.calendar_setting')}}">Calendar setting</a>
      </div>
    </div>
  </div>
  <div class="section-body">
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Calendar Setting</h4>
          </div>
          
          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                    <th> Id</th>
                    <th> Title</th>
                    <th> Message</th>
                    <th> Status</th>
                    <th> Create At</th>
                    <th> Action</th>
                  </tr>
                </thead>
              </table>
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

var index_url = "{{route('firm.setting.sms.getData')}}";

//console.log(index_url);
var srn = 0;
// $( window ).load(function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      
      columns: [
        { data: 'id', name: srn},
        { data: 'title', name: 'title'},
        { data: 'message', name: 'message'},
        { data: 'stat', name: 'stat'},
        { data: 'created_at', name: 'created_at'},

        { data: null,
          render: function(data){

            
            var edit_button = ' <a href="{{url('firm/setting/update')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
            
            return edit_button;
          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
 // });

//================ Edit user ============//

</script>

@endpush 
