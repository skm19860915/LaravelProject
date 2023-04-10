@extends('firmlayouts.admin-master')

@section('title')
Manage Case
@endsection

@push('header_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
  #table tbody tr td:nth-child(1) {
    display: none;
  }
  #table tbody tr td:nth-child(2) {
    display: none;
  }
  body div#table_filter {
     display: block !important; 
  }
  .dataTables_scrollBody thead {
    opacity: 0;
  }
  .card-body .table-responsive .dataTables_scrollBody table thead tr th {
    padding: 0 !important;
  }
  .table {
    margin-bottom: 0 !important;
  } 
  .dropdown-menu.show {
    min-height: 250px !important;
    /*max-height: 250px !important;*/
  }
</style>
@endpush  

@section('content')
<section class="section case-new-header">
  <div class="section-header">
    <h1>Cases <?php if(Request::route()->getName() == 'firm.case.mycase') 
    {
      //echo 'My Cases';
    }
    else {
     // echo 'All Cases';
    } ?>
    </h1>
    <div class="section-header-breadcrumb">
     <?php 
     $is_add = true;
     $custom_role = get_user_meta($data->id, 'custom_role');
     if($firm->account_type == 'VP Services' && $custom_role == '' && $data->role_id == 5) {
      $is_add = false;
     }
     if($is_add) { ?>
     <a href="{{ url('firm/case/create') }}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> Add New</a>
     <?php } ?>
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          
          <div class="card-body">
            <?php if($firm->account_type == 'CMS') { ?>
            <div class="task-tabbtn-box">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link" data-status="firm.case.mycase" id="mytask-tab" data-toggle="tab" href="#mytask" role="tab" aria-controls="mytask" aria-selected="true">My Case</a>
                </li>
                
                <li class="nav-item">
                  <a class="nav-link active" data-status="firm.case.allcase" id="casetask-tab" data-toggle="tab" href="#casetask" role="tab" aria-controls="casetask" aria-selected="false">Firm Case</a>
                </li> 
              </ul>
            </div>
            <?php } ?>

            <div class="table-responsive table-invoice all-case-table new-case_table" style="min-height: 400px;">
              <br>
              <?php if($firm->account_type == 'CMS') { ?>
                <div class="case_filter1_wrapper" style="float: left; margin-right: 15px;">
                  <select class="case_filter1 selectpicker" data-live-search="true">
                    <option value="">All Cases</option> 
                    <option value="Attorney">All Cases - By Attorney of Record</option>
                    <option value="Paralegal">All Cases - By Assigned Paralegal</option>
                    <option value="VP">All Cases - Assigned to VP</option>
                  </select>
                </div>
                <div class="case_filter2_wrapper" style="float: left; display: none;">
                  <select class="case_filter2 selectpicker" data-live-search="true">
                    <option value="">All</option> 
                    <?php foreach ($user as $key => $value) { ?>
                    <option value="{{$value->id}}">{{$value->name}}</option>
                    <?php } ?>
                  </select>
                </div>
              <?php } else { ?>
                <div class="case_filter3_wrapper" style="float: left;">
                  <select class="selectpicker case_filter3" data-live-search="true">
                    <option value="">All</option>
                    <option value="Open">Open Case</option>
                    <option value="Working">On Hold Case</option>
                    <option value="InReview">In Review Case</option> 
                    <option value="Complete">Complete Case</option>
                    <option value="InComplete">In Complete Case</option>
                  </select>
                </div>
              <?php } ?>
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                    <th style="display: none;"> Id</th>
                    <?php //if($firm->account_type == 'CMS') { ?>
                      <th style="display: none;">Client Name </th>
                      <th>Client Name </th>
                    <?php //} ?>
                    <th>Case Type</th>
                    <th>Case Category</th>
                    <?php if($firm->account_type == 'CMS') { ?>
                      <th>Client Number</th>
                    <?php } ?>
                    <!-- <th> Case Cost</th> -->
                    <th>Attorney of Record</th>
                    <?php if($firm->account_type == 'CMS') { ?>
                      <th>Assigned Paralegal</th>
                      <th>Court Date </th>
                    <?php } ?>
                    
                    <th>VP Assigned</th>
                    <th>Status</th>
                    <th style="min-width: 250px;">Actions</th>
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
<!-- Modal -->
<div id="CourtDateModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Pay Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Court Date</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('firm/case/update_court_date') }}" method="post" id="payment-form" enctype="multipart/form-data"> 
          <div class="payment-form-card" id="card-element">
               <div class="row">
                <div class="col-md-12">
                  <div class="">
                  <input type="text" placeholder="Court Date" size="20" name="court_date" class="form-control datepicker" required="required" />
                </div>
                </div>
               </div>
          </div>
          <div class="row">  
            <div class="col-md-12 text-right">
              @csrf
              <input type="hidden" class="case_id" name="case_id"  value="">
              <input type="submit" name="save" value="Update" class="submit btn btn-primary"/>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection

@push('footer_script')
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">

var filter1 = '';
var filter2 = '';
var s = '';
var index_url = "{{route('firm.case.getData')}}?case=firm.case.allcase";

function getData(index_url, filter1, filter2, s) {
  var srn = 0;
  srn++;
  var taable = $('#table').DataTable({
    processing: true,
    serverSide: true,
    destroy: true,
    scrollX: true,
    "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "filter1": filter1,
                "filter2": filter2,
                "status": s
            }
    },
    // "order": [[ 0, "desc" ]],
    columns: [
      // { data: 'id', name: srn},
      { data: null,
        render: function(data) {
            return srn;
        },orderable: "false"
      },
      { data: 'client_name', name: 'client_name'},
      { data: null,
        render: function(data) {
            var cl = '<a href="'+data.clink+'">'+data.client_name+'</a>';
            return cl;
        },orderable: "false"
      },
      { data: null,
        render: function(data) {
            return data.case_category;
        },orderable: "false"
      },
      {
       data: null,
        render: function(data) {
            return data.case_type;
        },orderable: "false"
      },
      <?php if($firm->account_type == 'CMS') { ?>
      { data: 'clientid', name: 'clientid'},
      <?php } ?>
      // { data: 'case_type', name: 'case_type' },
      
      // { data: 'case_cost', name: 'case_cost'},
      // { data: null,
      //   render: function(data) {
      //       return data.case_cost;
      //   },orderable: "false"
      // },
      { data: null,
        render: function(data) {
            return data.user_name;
        },orderable: "false"
      },
      <?php if($firm->account_type == 'CMS') { ?>
      { data: null,
        render: function(data) {
            return data.paralegal_name;
        },orderable: "false"
      },
      { data: null,
        render: function(data) {
            return data.CourtDates;
        },orderable: "false"
      },
      <?php } ?>
      // { data: 'CourtDates', name: 'CourtDates'},
      
      { data: null,
        render: function(data) {
            return data.vpuser;
        },orderable: "false"
      },
      { data: null,
        render: function(data) {
            return data.case_status;
        },orderable: "false"
      },
      { data: null,
        render: function(data){

          var text = "'Are You Sure to delete this record?'";
          var create_task = ' <a href="{{url('firm/case/create_task')}}/'+data.id+'" class="action_btn"><img src="{{ url('/') }}/assets/images/icon/notepad@2x.png"></a>';
          create_task = '';
          var view_button = ' <a href="{{url('firm/case/show')}}/'+data.id+'" class="action_btn" title="Show Details" data-toggle="tooltip"><img src="{{ url('/') }}/assets/images/icon/Group 557.svg"></a>';
          var delete_button = ' <a href="{{url('firm/case/delete')}}/'+data.id+'" class="action_btn" onclick="return window.confirm('+text+');"><img src="{{ url('/') }}/assets/images/icons/case-icon3.svg"></a>';
          var edit_button = '';
          if(data.case_cost == 'Self managed' || data.status == -1) {
            edit_button = ' <a href="{{url('firm/case/edit')}}/'+data.id+'" class="action_btn"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>';
          }

          var event_button = '';

          if (data.event == "") {

            event_button = ' <a href="{{url('firm/case/create_event')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="Schedule event"><img src="{{ url('/') }}/assets/images/icons/case-icon1.svg"></a>';

          }else{

            if(data.oldevent < data.todaytime)
            {

             event_button = ' <a href="{{url('firm/case/create_event')}}/'+data.id+'?reschedule=1" class="action_btn" data-toggle="tooltip" data-placement="top" title="Re-Schedule event"><img src="{{ url('/') }}/assets/images/icons/case-icon5.svg"></a>';

           }  
         }
         event_button = ' <a href="{{url('firm/case/create_event')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="Schedule event"><img src="{{ url('/') }}/assets/images/icons/case-icon1.svg"></a>';
         var completebtn = '';
         <?php if($firm->account_type == 'CMS') { ?>
         if(data.status == 9) {
            completebtn = ' <a href="{{url('firm/case/case_incomplete')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="Mark as In-complete"><img src="{{ url('/') }}/assets/images/icons/case-icon5.svg"></a>';
         }

         if(data.status == 6) {
            completebtn = ' <a href="{{url('firm/case/case_complete')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" data-placement="top" title="Mark as complete"><img src="{{ url('/') }}/assets/images/icons/case-icon4.svg"></a>';
         }
         <?php } ?>
         var courtdatebtn = '<a href="#" class="action_btn courtdatebtn" data-toggle="tooltip" data-id="'+data.id+'" data-placement="top" title="Add Court Date"><img src="{{ url('/') }}/assets/images/icons/case-icon1.svg"></a>';
         var case_doc = '';
         var client_btn = '';
         <?php if($firm->account_type != 'CMS') { ?>
          event_button = '';
          courtdatebtn = '';
          case_doc = '<a href="{{url('firm/case/case_documents')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" data-id="'+data.id+'" data-placement="top" title="Document Request"><img src="{{ url('/') }}/assets/images/icons/case-icon5.svg"></a>';
          <?php } if($firm->account_type == 'CMS') { ?>
          client_btn = '<a href="{{url('firm/client/client_case')}}/'+data.clientid+'" class="action_btn" data-toggle="tooltip" data-id="'+data.id+'" data-placement="top" title="View Client Details"><img src="{{ url('/') }}/assets/images/icon/Group 16@2x.png"></a>';
         <?php } ?>
         delete_button = '';
         // edit_button = '';
         if(data.status == -2) {
          return '';
         }
         else {
          var chatbtn = '';
          if(data.vpid) {
            case_doc = '<a href="#" class="action_btn vpchatbtn" data-toggle="tooltip" data-vpid="'+data.vpid+'" data-placement="top" title="Send Message to VP User"><img src="{{ url('/') }}/assets/images/icon/chat(1)@2x.png"></a>';
          }
          event_button = '';
          return create_task + view_button + edit_button + delete_button + event_button + courtdatebtn + completebtn + case_doc + client_btn + chatbtn;
        }
        }, orderable: "false"
      },
    ],
    /*rowCallback: function(row, data) {
        $(row).attr('data-user_id', data['id']);
    }*/
  });
}

getData(index_url, filter1, filter2, s);
//================ Edit user ============//
$(document).on('click', '.courtdatebtn', function(e){
  e.preventDefault();
  var id = $(this).data('id');
  $('.case_id').val(id);
  $("#CourtDateModal").modal('show');
});
$(document).ready(function(){
    $('.datepicker').daterangepicker({
      locale: {format: 'DD-MM-YYYY'},
      singleDatePicker: true,
      timePicker: false,
      timePicker24Hour: false,
      minDate: '<?php echo date('d-m-Y'); ?>',
      startDate: '<?php echo date('d-m-Y'); ?>'
    });
    $('#myTab a').on('click', function(){
      var v = $(this).data('status');
      $('select.case_filter1').val('');
      $('select.case_filter2').val('');
      $('select.case_filter1').selectpicker('refresh');
      if(v == 'firm.case.mycase') {
        $('.case_filter1_wrapper').hide();
        $('.case_filter2_wrapper').hide();
      }
      else {
        $('.case_filter1_wrapper').show();
      }
      index_url = "{{route('firm.case.getData')}}?case="+v;
      getData(index_url, filter1, filter2, s);
    });
    $('select.case_filter1').on('change', function(){
      $('select.case_filter2').val('');
      $('select.case_filter2').selectpicker('refresh');
      filter1 = $('select.case_filter1').val();
      filter2 = '';
      if(filter1 == 'Attorney' || filter1 == 'Paralegal') {
        $('.case_filter2_wrapper').show();
      }
      else {
        $('.case_filter2_wrapper').hide();
      } 
      getData(index_url, filter1, filter2, s);
    });
    $('select.case_filter2').on('change', function(){
      filter1 = $('select.case_filter1').val();
      filter2 = $('select.case_filter2').val();
      getData(index_url, filter1, filter2, s);
    });
    $('select.case_filter3').on('change', function(){
      s = $(this).val();
      getData(index_url, filter1, filter2, s);
    });
});
$(document).on('click', '.vpchatbtn', function(e){
  e.preventDefault();
  var vpid = $(this).data('vpid');
  $('.chatwindows li[data-ids="'+vpid+'"]').trigger('click');
})
</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 
