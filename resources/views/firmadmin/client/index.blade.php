@extends('firmlayouts.admin-master')

@section('title')
Manage Clients
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
    display: none;
}    
body div#table_filter {
     display: block !important; 
}
</style>
@endpush  

@section('content')
<section class="section client-listing">
  <div class="section-header">
    <h1>Clients</h1>
    <div class="section-header-breadcrumb">
      <a href="{{ url('firm/client/create') }}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> Add Client</a>      
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">         

          <div class="card-body">
            <div class="table-responsive table-invoice client-ng-table">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> TID</th>
                   <th> Client Name </th>
                   <th> Phone Number</th>
                   <th> Client Number</th>
                   <th> Create Date </th>
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

var index_url = "{{route('firm.client.getData')}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    var table = $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: srn},
        { data: 'name', name: name},
        { data: 'cell_phone', name : 'cell_phone'},
        // { data: 'cell_phone', name: 'cell_phone'},
        { data: 'id', name: 'id'},
        { data: null,
          render: function(data) {
              return data.created_at;
          },orderable: "false"
        },
        // { data: 'created_at', name: 'created_at'},
       
        { data: null,
          render: function(data){

            var text = "'Are You Sure to delete this record?'";
            var view_button = ' <a href="{{url('firm/client/show')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" title="Client File"><img src="{{ url('/') }}/assets/images/icon/Group 16@2x.png"></a>';
            var edit_button = ' <a href="{{url('firm/client/edit')}}/'+data.id+'" class="action_btn" title="Edit Client"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
            var undo_lead = '';
            if(data.lead_id && data.cases == 0) {
              var text1 = 'If you revert client to lead you will lost all data of this client. Are you sure you want to revert client to lead';
             // undo_lead = ' <a href="{{url('firm/lead/undo_lead')}}/'+data.lead_id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="Revert client to lead" onclick="return window.confirm(\''+text1+'\');"><img src="{{url('assets/images/icon')}}/arrows.svg" /></a>';
            }

            var event_button = '';
            if (data.event == "") {

              event_button = ' <a href="{{url('firm/client/create_event')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="Schedule"><img src="{{url('assets/images/icon')}}/calendar(3)act@2x.png" /></a>';

            }else{

              if(data.oldevent < data.todaytime)
              {

               event_button = ' <a href="{{url('firm/client/create_event')}}/'+data.id+'?reschedule=1" class="action_btn" data-toggle="tooltip" data-placement="top" title="Re-Schedule"><img src="{{url('assets/images/icon')}}/calendar(3)act@2x.png" /></a>';

             }  
           }
           event_button = ' <a href="{{url('firm/client/create_event')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="Schedule"><img src="{{url('assets/images/icon')}}/calendar(3)act@2x.png" /></a>';

           invoice_button = ' <a href="{{url('firm/client/add_new_invoice')}}/'+data.user_id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="Create Invoice"><img src="{{url('assets/images/icon')}}/ticket@2x.png" /></a>';
           var case_button = '';
           <?php 
           $is_add = true;
           $custom_role = get_user_meta(Auth::User()->id, 'custom_role');
           if($firm->account_type == 'VP Services' && $custom_role == '' && Auth::User()->role_id == 5) {
            $is_add = false;
           }
           if($is_add) { ?>
           case_button = ' <a href="{{url('firm/client/add_new_case')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="+ Add a Case"><img src="{{url('assets/images/icon')}}/portfolio(1)@2x.png" /></a>';
           <?php } ?>
           edit_button = '';
           <?php if($firm->account_type == 'VP Services') { ?>
           event_button = '';
           invoice_button = '';
           <?php } ?>
           return edit_button + view_button + case_button + invoice_button + undo_lead + event_button;
            
            //return view_button + edit_button + undo_lead/* + delete_button*/;
          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
    // $('#table_filter input').on('keyup', function() {
    //   table
    //   .column(1)
    //   .search(this.value)
    //   .draw();
    // });
 });

//================ Edit user ============//

</script>

@endpush 
