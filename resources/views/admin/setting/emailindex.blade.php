@extends('layouts.admin-master')

@section('title')
Manage Email Notifications
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Email Notifications</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          
          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped email_index_table"  id="table" >
                <thead>
                  <tr>
                    <!-- <th> Id</th> -->
                    <th> Title</th>
                    <th> Subtitle</th>
                    <th> Message</th>
                    <!-- <th> Status</th> -->
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

var index_url = "{{route('admin.setting.email.getData')}}";

//console.log(index_url);
var srn = 0;
// $( window ).load(function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      
      columns: [
        // { data: 'id', name: srn},
        { data: 'title', name: 'title'},
        { data: 'subtitle', name: 'subtitle'}, 
        { data: 'massage', name: 'massage'},
        // { data: 'stat', name: 'stat'},
        { data: 'created_at', name: 'created_at'},

        { data: null,
          render: function(data){

            var edit_button = ' <a href="{{url('admin/setting/update')}}/'+data.id+'" class="action_btn"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
            if(data.is_undo) {
              edit_button += ' <a href="{{url('admin/setting/undo_message')}}/'+data.id+'" class="action_btn" onclick="return confirm(\'Are you sure want to undo this message?\');"><img src="{{url('assets/images/icons')}}/undo.svg" /></a>';
            }
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
