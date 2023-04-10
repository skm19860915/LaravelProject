@extends('layouts.admin-master')

@section('title')
Manage Case
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
  display: none;
}
#table thead tr th:nth-child(9),
#table thead tr th:nth-child(10),
#table thead tr th:nth-child(11),
#table tbody tr td:nth-child(9),
#table tbody tr td:nth-child(10),
#table tbody tr td:nth-child(11) {
  display: none;
}
.assignbtn {
  border-color: transparent !important; 
} 
.assignbtn img {
  width: 35px !important;
  margin-top: -6px;
  margin-left: -1px;
}
</style>
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage Cases</h1>
    <div class="section-header-breadcrumb">
    </div>

  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="task-tabbtn-box">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" data-status="0" id="Unassigned-tab" data-toggle="tab" href="#Unassigned" role="tab" aria-controls="Unassigned" aria-selected="false">Unassigned</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-status="1" id="Assigned-tab" data-toggle="tab" href="#Assigned" role="tab" aria-controls="Assigned" aria-selected="true">Assigned</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-status="2" id="MyCase-tab" data-toggle="tab" href="#MyCase" role="tab" aria-controls="MyCase" aria-selected="true">My Case</a>
                </li>     
                <li class="nav-item">
                  <a class="nav-link" data-status="3" id="Completed-tab" data-toggle="tab" href="#Completed" role="tab" aria-controls="Completed" aria-selected="true">Completed</a>
                </li>        
              </ul>
             </div>
            <div style="float: left; width: 230px; margin-top: 15px; display: none;" class="country-left-select vpusers_wrap">
              <label>TILA VP</label>
              <select class="selectpicker vpusers" style="width: 220px; display: inline-block;" data-live-search="true" id="vpusers">
                <option value="">All</option>
                <?php 
                if(!empty($vpuser)) {
                  foreach ($vpuser as $k => $v) {
                    echo '<option value="'.$v->id.'">'.$v->name.'</option>';
                  }
                }
                ?>
              </select>              
            </div>
            <div style="float: left; width: 230px; margin-top: 15px;" class="country-left-select firms_wrap">
              <label>Firm Admin</label>
              <select class="selectpicker firms" style="width: 220px; display: inline-block;" data-live-search="true" id="firms">
                <option value="">All</option>
                <?php 
                if(!empty($firms)) {
                  foreach ($firms as $k => $v) {
                    echo '<option value="'.$v->id.'">'.$v->firm_name.'</option>';
                  }
                }
                ?>
              </select>              
            </div>
            <div style="float: left; width: 230px; margin-top: 15px;" class="country-left-select">
              <label>Case Type</label>
              <select class="selectpicker case_type" style="width: 220px; display: inline-block;" data-live-search="true" id="case_type">
                <option value="">All</option>
                <?php 
                if(!empty($case_type)) {
                  foreach ($case_type as $k => $v) {
                    echo '<option value="'.$v->Case_Type.'">'.$v->Case_Type.'</option>';
                  }
                }
                ?>
              </select>              
            </div>
            <div class="table-responsive table-invoice case-admin-table">
              <table class="table table table-bordered table-striped" id="table" >
                <thead>
                  <tr>
                   <th style="display: none;"> Id</th>
                   <th> Date Received</th>
                   <th> Client Name </th>
                   <th> Case type</th>
                   <th> Firm Name</th>
                   <th> Case Owner </th>
                   <th> Role</th>
                   <th> Email</th>
                   <th> Assigned VP</th>
                   <th> Date Assigned</th>
                   <th> Date Completed</th>
                   <th> Status</th>
                   <th> Case Status</th>
                   <th style="min-width: 100px;"> Action</th>
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



var index_url = "{{route('admin.allcases.getCaseData')}}";
$(window).on('load', function() {
    var tab = 0;
    var vpuser = 0;
    var firm = 0;
    var case_type = 0;
    gettabledata(tab, vpuser, firm, case_type);
    function gettabledata(tab, vpuser, firm, case_type) {
      $('#table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "tab": tab,
                "vpuser": vpuser,
                "firm": firm,
                "case_type": case_type
            }
        },
        "order": [[ 0, "desc" ]],
        columns: [
          { data: 'id', name: 'id'},
          { data: 'created_at', name: 'created_at'},
          { data: 'clientname', name: 'clientname'},
          { data: 'case_type', name: 'case_type'},
          { data: 'firmname', name: 'firmname'},
          { data: 'ownername', name: 'ownername'},
          { data: 'role', name: 'role'},
          { data: 'owneremail', name: 'owneremail'},
          { data: 'vpuser', name: 'vpuser'},
          { data: 'assigned_date', name: 'assigned_date'},
          { data: 'compled_date', name: 'compled_date'},
          { data: 'case_status', name: 'case_status'},
          { data: 'casestatus', name: 'casestatus'},
          { data: null,
            render: function(data){

                var view_button = ' <a href="{{url('admin/allcases/show')}}/'+data.id+'" class="action_btn" data-toggle="tooltip" title="View Case Details"><img src="{{url('assets/images')}}/icon/Group 557.svg"></a>';
                var edit_button = '';
                if(data.is_edit) {
                  edit_button = ' <a href="{{url('admin/allcases/edit')}}/'+data.aid+'" class="action_btn assignbtn" data-toggle="tooltip" title="Assign to VP"><img src="{{url('assets/images/icon')}}/assign.png" /></a>';
                }
                var vpbtn = '';
                if(data.vpid) {
                  var vpbtn = ' <a href="{{url('admin/users/show')}}/'+data.vpid+'" class="action_btn" data-toggle="tooltip" title="View VP Details"><img src="{{url('assets/images')}}/icon/user(6)@2x.png"></a>';
                }

                if(tab == 0) {
                  $('#table thead tr th:nth-child(9)').hide();
                  $('#table tbody tr td:nth-child(9)').hide();
                } 
                else {
                  $('#table thead tr th:nth-child(9)').show();
                  $('#table tbody tr td:nth-child(9)').show();
                }

                if(tab == 3) {
                  $('#table thead tr th:nth-child(10)').show();
                  $('#table thead tr th:nth-child(11)').show();
                  $('#table tbody tr td:nth-child(10)').show();
                  $('#table tbody tr td:nth-child(11)').show();
                }
                else {
                  $('#table thead tr th:nth-child(10)').hide();
                  $('#table thead tr th:nth-child(11)').hide();
                  $('#table tbody tr td:nth-child(10)').hide();
                  $('#table tbody tr td:nth-child(11)').hide();
                }
                return  edit_button + view_button + vpbtn;

            }, orderable: "false"
          },
        ],
      });
    }
    $('.nav-link').on('click', function(){
      tab = $(this).data('status');
      vpuser = $('select#vpusers').val();
      firm = $('select#firms').val();
      case_type = $('select#case_type').val();
      if(tab) {
        $('.vpusers_wrap').show();
      }
      else {
        $('.vpusers_wrap').hide();
        vpuser = 0;
      }

      gettabledata(tab, vpuser, firm, case_type);
    });
    $('select#vpusers').on('change', function(){
      tab = $('.nav-link.active').data('status');
      vpuser = $('select#vpusers').val();
      firm = $('select#firms').val();
      case_type = $('select#case_type').val();
      gettabledata(tab, vpuser, firm, case_type);
    });
    $('select#firms').on('change', function(){
      tab = $('.nav-link.active').data('status');
      vpuser = $('select#vpusers').val();
      firm = $('select#firms').val();
      case_type = $('select#case_type').val();
      gettabledata(tab, vpuser, firm, case_type);
    });
    $('select#case_type').on('change', function(){
      tab = $('.nav-link.active').data('status');
      vpuser = $('select#vpusers').val();
      firm = $('select#firms').val();
      case_type = $('select#case_type').val();
      gettabledata(tab, vpuser, firm, case_type);
    });
 });
//================ Edit user ============//

</script>

@endpush 
