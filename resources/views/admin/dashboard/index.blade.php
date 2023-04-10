@extends('layouts.admin-master')

@section('title')
Dashboard
@endsection

@section('content')
<section data-dashboard="1" class="section dashboard-new-design">
  <div class="section-header">
  	<h1 class="adminheading">Dashboard</h1>
  </div>

    <div class="section-body">
        
        <div class="row">
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <a href="{{ route('admin.firm') }}">
                    <div class="card-icon shadow-primary bg-primary d_card1">
                        <img src="{{url('assets/images/icon/Group 15@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['firm']}}
                        </div>
                        <div class="card-body">
                            Total Firm
                        </div>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <a href="{{ route('admin.firm') }}">
                    <div class="card-icon shadow-primary bg-primary d_card1">
                        <img src="{{url('assets/images/icon/Group 15@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['cmsfirm']}}
                        </div>
                        <div class="card-body">
                            Total CMS Firm
                        </div>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <a href="{{ route('admin.firm') }}">
                    <div class="card-icon shadow-primary bg-primary d_card1">
                        <img src="{{url('assets/images/icon/Group 15@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['vpfirm']}}
                        </div>
                        <div class="card-body">
                            Total VP Firm
                        </div>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <a href="{{ route('admin.users') }}">
                    <div class="card-icon shadow-primary bg-primary d_card2">
                        <img src="{{url('assets/images/icon/user(6)@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['vauser']}}
                        </div>
                        <div class="card-body">
                             Total VP Users
                        </div>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <a href="{{ route('admin.task') }}">
                    <div class="card-icon shadow-primary bg-primary d_card4">
                        <img src="{{url('assets/images/icon/list@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['task']}}
                        </div>
                        <div class="card-body">
                             Open Task
                        </div>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <a href="{{ route('admin.report.FinancialRP') }}">
                    <div class="card-icon shadow-primary bg-primary d_card3">
                        <img src="{{url('assets/images/icon/ticket@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            $<?php echo number_format($count['revenue'], 2); ?>
                        </div>
                        <div class="card-body">
                             Total Revenue
                        </div>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <a href="{{ route('admin.report.FinancialRP') }}">
                    <div class="card-icon shadow-primary bg-primary d_card3">
                        <img src="{{url('assets/images/icon/ticket@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            $<?php echo number_format($count['cmsrevenue'], 2); ?>
                        </div>
                        <div class="card-body">
                             Total CMS Revenue
                        </div>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">
                    <a href="{{ route('admin.report.FinancialRP') }}">
                    <div class="card-icon shadow-primary bg-primary d_card3">
                        <img src="{{url('assets/images/icon/ticket@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            $<?php echo number_format($count['vprevenue'], 2); ?>
                        </div>
                        <div class="card-body">
                             Total VP Revenue
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="theme_text">My Tasks</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.task') }}" class="btn btn-primary">All Tasks...</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-invoice admin-table-dash">
                            <table class="table table-striped">
                                <tbody><tr>
                                    <th>Task</th>
                                    <th>Case Type</th>
                                    <th>Firm name</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                @if ($admintask->isEmpty())

                                @else
                                @foreach ($admintask as $task)
                                <tr>
                                    <td>
                                        {{$task->task}}
                                    </td>
                                    <td>
                                        {{$task->case_type}}
                                    </td>
                                    <td class="font-weight-600">
                                        {{$task->firm_name}}
                                    <td>
                                        {{$task->priority}}
                                    </td>
                                    <td>{{$task->stat}}</td>
                                    <td>
                                        <a href="{{url('admin/task/edit')}}/{{$task->id}}" class="action_btn" data-toggle="tooltip" title="Edit task">
                                            <img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" />
                                        </a>
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
            <div class="col-md-4" style="display: none;">
                <div class="card card-hero">
                    <div class="card-header">
                        <div class="card-icon theme_text">
                            <img src="{{url('assets/images/icon/chatdark@2x.png')}}" />
                        </div>
                        <h4 class="theme_text">Chat</h4>
                        <div class="card-description theme_text">
                            <a href="#" class="btn btn-primary">New Client Message</a>
                        </div>
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

<script type="text/javascript">
    $(document).ready(function () {
        $.get("/admin/setting/QBautoconnect", function (data, status) {
            //alert("Data: " + data + "\nStatus: " + status);
        });
    })
</script>

@endpush 