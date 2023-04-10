@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection
@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
  display: none;
}  
</style>
@endpush 
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Dashboard</h1>
        <div class="section-header-breadcrumb">
        </div>
    </div>
    <div class="section-body">
        
        <div class="row">
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <!-- <a href="#"> -->
                    <div class="card-icon shadow-primary bg-primary d_card2">
                        <img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['working_case']}}
                        </div>
                        <div class="card-body">
                              Active
                        </div>
                    </div>
                    <!-- </a> -->
                </div>
            </div>
            <!-- <div class="col-md-3">
                <div class="card card-statistic-1">
                    <div class="card-icon shadow-primary bg-primary d_card1">
                        <img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['open_case']}}
                        </div>
                        <div class="card-body">
                            Waiting
                        </div>
                    </div>
                </div>
            </div> -->
            
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <!-- <a href="#"> -->
                    <div class="card-icon shadow-primary bg-primary d_card3">
                        <img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['in_review']}}
                        </div>
                        <div class="card-body">
                            Review
                        </div>
                    </div>
                    <!-- </a> -->
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <!-- <a href="#"> -->
                    <div class="card-icon shadow-primary bg-primary d_card4">
                        <img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['completed']}}
                        </div>
                        <div class="card-body">
                              Completed
                        </div>
                    </div>
                    <!-- </a> -->
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <!-- <a href="#"> -->
                    <div class="card-icon shadow-primary bg-primary d_card4">
                        <img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['total_case']}}
                        </div>
                        <div class="card-body">
                              Total Cases
                        </div>
                    </div>
                    <!-- </a> -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- <div class="card-header">
                        
                        <div class="card-header-action">
                            <a href="{{ route('admin.task') }}" class="btn btn-primary">View All...</a>
                        </div>
                    </div> -->

                    <div class="card-body">
                        <br>
                        <h4 class="theme_text">Today's Task</h4>
                        <div class="table-responsive table-invoice">
                            <table class="table table table-bordered table-striped"  id="table1" >
                                <thead>
                                  <tr>
                                   <th> Task</th>
                                   <th> Client Name</th>
                                   <th> Status</th>
                                   <th> Created Date</th>
                                   <th> Due Date</th>
                                   <th> Priority</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($today_task)) {
                                        foreach ($today_task as $k => $task) { ?>
                                         <tr>
                                             <td>
                                                 {{$task->task}}
                                             </td>
                                             <td>
                                                <a href="{{$task->clink}}">
                                                 {{$task->clientname}}
                                                </a>
                                             </td>
                                             <td>
                                                 {{$task->status}}
                                             </td>
                                             <td>
                                                 {{$task->created_at}}
                                             </td>
                                             <td>
                                                 {{$task->due_date}}
                                             </td>
                                             <td>
                                                 {{$task->priority}}
                                             </td>
                                         </tr>   
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <h4 class="theme_text">My Case</h4>
                        <div style="float: left;" class="country-left-select">
                            <select class="form-control" name="case_status" id="case_status">
                                <option value="">All</option>
                                <option value="Open">Open Case</option>
                                <option value="Working">Working Case</option>
                                <option value="InReview">In Review Case</option> 
                                <option value="Complete">Complete Case</option>
                                <option value="InComplete">In Complete Case</option>
                            </select>               
                        </div>
                        <div class="table-responsive table-invoice">
                            <table class="table table table-bordered table-striped"  id="table" >
                                <thead>
                                  <tr>
                                   <th style="display: none;"> Id</th>
                                   <th> Client Name</th>
                                   <th> Case Type</th>
                                   <th> Firm name </th>
                                   <th> Status</th>
                                   <th> Action</th>
                                  </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4" style="display: none;">
                <div class="card card-hero">
                    <div class="card-header">
                        <div class="card-icon theme_text">
                            <i class="far fa-comments"></i>
                        </div>
                        <h4 class="theme_text">14</h4>
                        <div class="card-description theme_text">Client Updates</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="tickets-list">
                            <a href="#" class="ticket-item">
                                <div class="ticket-title">
                                    <h4 class="theme_text">My order hasn't arrived yet</h4>
                                </div>
                                <div class="ticket-info">
                                    <div>Laila Tazkiah</div>
                                    <div class="bullet"></div>
                                    <div class="text-primary theme_text">1 min ago</div>
                                </div>
                            </a>
                            <a href="#" class="ticket-item">
                                <div class="ticket-title">
                                    <h4 class="theme_text">Please cancel my order</h4>
                                </div>
                                <div class="ticket-info">
                                    <div>Rizal Fakhri</div>
                                    <div class="bullet"></div>
                                    <div class="text-primary theme_text">2 hours ago</div>
                                </div>
                            </a>
                            <a href="#" class="ticket-item">
                                <div class="ticket-title">
                                    <h4 class="theme_text">Do you see my mother?</h4>
                                </div>
                                <div class="ticket-info">
                                    <div>Syahdan Ubaidillah</div>
                                    <div class="bullet"></div>
                                    <div class="text-primary theme_text">6 hours ago</div>
                                </div>
                            </a>
                            <a href="#" class="ticket-item ticket-more theme_text">
                                View All <i class="fas fa-chevron-right"></i>
                            </a>
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

var index_url = "{{route('admin.all_case.getDataAll')}}";
$(window).on('load', function() {
  function getCaseData(s = '') {
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
      "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "status": s
            }
      },
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        // { data: 'clientname', name: 'clientname'},
        { data: null,
          render: function(data){
            var link = '<a href="'+data.clink+'" data-toggle="tooltip" title="View details">'+data.clientname+'</a>';
            return link;
            }, orderable: "false"
        },
        { data: 'case_type', name: 'case_type'},
        { data: 'firm_name', name: 'firm_name'},
        { data: 'stat', name: 'stat'},
        // { data: 'ccreated_at', name: 'ccreated_at'},
        { data: null,
          render: function(data){

            var view_button = ' <a href="{{url('admin/usertask/overview')}}/'+data.tid+'" class="action_btn" data-toggle="tooltip" title="View Case"><img src="{{url('assets/images/icon')}}/Group 16@2x.png" /></a>';
              return view_button;

          }, orderable: "false"
        },
      ],
    });
  }
  getCaseData();
  $('#case_status').on('change', function(){
    var s = $(this).val();
    getCaseData(s);
  });
 });

//================ Edit user ============//

</script>

@endpush 