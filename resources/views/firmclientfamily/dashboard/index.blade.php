@extends('firmlayouts.client-family')

@section('title')
Dashboard
@endsection

@section('content')
<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <h1>Dashboard</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <!-- <a href="{{route('firm.admindashboard')}}">Dashboard</a> -->
            </div>
        </div>
    </div>

    <div class="section-body">

        <div class="row">
            <div class="col-md-3">
                <div class="card card-statistic-1">

                    <div class="card-icon shadow-primary bg-primary d_card1">
                        <img src="{{url('assets/images/icon/Group 15@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['clients']}}
                        </div>
                        <div class="card-body">
                            TOTAL MESAGES
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">

                    <div class="card-icon shadow-primary bg-primary d_card4">
                        <img src="{{url('assets/images/icon/useradd@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['total_case']}}
                        </div>
                        <div class="card-body">
                            TOTAL CASES
                        </div>
                    </div>
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
                            CASE OPEN

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-statistic-1">

                    <div class="card-icon shadow-primary bg-primary d_card3">
                        <img src="{{url('assets/images/icon/portfolio(1)@2x.png')}}" />
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            {{$count['case_complete']}}
                        </div>
                        <div class="card-body">
                            CASE COMPLETED
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-12">
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
                                <tbody>
                                    <tr>
                                        <th>Case Number</th>
                                        <th>Case Type</th>
                                        <th>Create Date</th>
                                        <th>Courte Date</th>
                                        <th>Status</th>
                                    </tr>
                                    <?php foreach($cases as $c){ ?>
                                    <tr>
                                        <td>CASE-<?php echo $c->case_id; ?></td>
                                        <td><?php echo $c->case_type; ?></td>
                                        <td><?php echo $c->case_created_at; ?></td>
                                        <td><?php echo $c->CourtDates; ?></td>
                                        <td>Open</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php #pre($cases); ?>
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
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('footer_script')



@endpush 