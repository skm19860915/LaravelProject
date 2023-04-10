@extends('firmlayouts.admin-master')

@section('title')
Dashboard
@endsection
@push('header_styles')
<style type="text/css">
.span1,
.span2 {
    display: inline-block;
    vertical-align: top;
    width: 25px;
}
.span2 {
    width: calc(100% - 30px);
}
</style>
@endpush 
@section('content')
<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <h1>Dashboard</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="{{ url('firm/client/create') }}" class="btn btn-primary" style="width: auto; padding: 0 18px; color: #fff;"><i class="fas fa-plus"></i> Create a new client</a> 
            </div>
        </div>
    </div>

    <div class="section-body">

        <div class="row">
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <div class="card-icon shadow-primary bg-primary d_card3">
                        <img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['case_closed']}}
                        </div>
                        <div class="card-body">
                            Cases Completed
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">

                    
                    <?php if($firm->account_type == 'CMS') { ?>
                    <div class="card-icon shadow-primary bg-primary d_card4">
                        <img src="{{url('assets/images/icon/useradd@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['total_lead']}}
                        </div>
                        <div class="card-body">
                            Total Leads
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="card-icon shadow-primary bg-primary d_card4">
                        <img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['open_case']}}
                        </div>
                        <div class="card-body">
                            Opened Cases
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <?php if($firm->account_type == 'CMS') { ?>
                    <div class="card-icon shadow-primary bg-primary d_card1">
                        <img src="{{url('assets/images/icon/Group 15@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['clients']}}
                        </div>
                        <div class="card-body">
                            Total Clients
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="card-icon shadow-primary bg-primary d_card1">
                        <img src="{{url('assets/images/icon/list@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['clients']}}
                        </div>
                        <div class="card-body">
                            Total Clients
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">

                    <div class="card-icon shadow-primary bg-primary d_card2">
                        <img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['cases']}}
                        </div>
                        <div class="card-body">
                            Total Cases
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="theme_text">10 Upcoming Tasks</h4>
                        <div class="card-header-action">
                            <a href="{{ route('firm.task') }}" class="btn btn-primary">View All...</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-invoice">
                            <table class="table table-striped">
                                <tbody><tr>
                                        <th>Task</th>
                                        <!-- <th>Case Type</th> -->
                                        <th>Client Name</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Priority</th>
                                    </tr>
                                    @if ($admintask->isEmpty())

                                    @else
                                    @foreach ($admintask as $task)
                                    <tr>
                                        <td>
                                            {{$task->task}}
                                        </td>
                                        <!-- <td>
                                            {{$task->case_type}}
                                        </td> -->
                                        <td class="font-weight-600">
                                            <?php 
                                            if(empty($task->clientname)) {
                                                echo 'N/A';
                                            }
                                            else { ?>
                                                <a href="{{url('firm/client/client_task')}}/{{$task->cid}}">{{$task->clientname}}
                                                </a>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            @if($task->status) 
                                            <div class="badge badge-warning">Complete</div>			
                                            @else
                                            <div class="badge badge-primary">Open</div>		
                                            @endif	
                                        </td>
                                        <td>{{$task->created_at}}</td>
                                        <td>
                                            <?php
                                            $result = '';
                                            switch ($task->priority) {
                                                case 1:
                                                    $result = "Urgent";
                                                    break;
                                                case 2:
                                                    $result = "High";
                                                    break;
                                                case 3:
                                                    $result = "Medium";
                                                    break;
                                                case 4:
                                                    $result = "Low";
                                                    break;
                                                default:
                                                    $result = "Normal";
                                            }
                                            echo $result;
                                            ?>
                                            <!-- <a href="{{url('firm/case/show')}}/{{$task->case_id}}" class="btn btn-primary">Detail</a> -->
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-hero">
                    <div class="card-header">
                        <div class="card-icon theme_text">
                            <img src="{{url('assets/images/icon/bell@2x.png')}}" />
                        </div>
                        <h4 class="theme_text">Recent Activity</h4>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <!-- <tr>
                                <td><i class="fa fa-tasks"></i>&nbsp;&nbsp; My Tasks</td>
                                <td>2 Today</td>
                            </tr> -->
                            <?php 
                            if(!empty($Notif)) {
                              foreach ($Notif as $k => $v) { 
                                $v1 = json_decode($v->data)->message; ?>
                                <tr>
                                    <td>
                                        <span class="span1">
                                            <i class="fa fa-circle"></i>
                                        </span>
                                        <span class="span2">{{$v1->body}}</span>
                                        <div>
                                            <i class="text-secondary">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sent On {{$v->created_at}}
                                            </i>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{url('messages/readnotification')}}/<?php echo $v->id; ?>" class="btn btn-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                }
                            } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('footer_script')

<script type="text/javascript">
    $(document).ready(function () {
        // $.get("/firm/setting/QBautoconnect", function (data, status) {
        //     //alert("Data: " + data + "\nStatus: " + status);
        // });
    })
</script>

@endpush 