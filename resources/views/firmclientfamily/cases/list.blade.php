@extends('firmlayouts.client-family')

@section('title')
Family Cases List
@endsection

@section('content')
<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <h1>Family Cases List</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <!-- <a href="{{route('firm.admindashboard')}}">Dashboard</a> -->
            </div>
        </div>
    </div>

    <div class="section-body">

        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    
                    <div class="card-body">
                        <div class="table-responsive table-invoice">
                            <table class="table table-striped">
                                <tbody><tr>
                                        <th>Case Number</th>
                                        <th>Case Type</th>
                                        <th>Create Date</th>
                                        <th>Courte Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    @if ($cases->isEmpty())

                                @else
                                @foreach ($cases as $case)
                                <tr>
                                    <td>
                                        CASE-{{$case->case_id}}
                                    </td>
                                    <td class="font-weight-600">
                                        {{$case->case_type}}
                                    </td>
                                    <!-- <td>
                                        {{$case->case_cost}}
                                    </td> -->
                                    <td>{{$case->case_created_at}}</td>
                                    <td>{{$case->CourtDates}}</td>
                                    <td>
                                        {{$case->case_status}}
                                    </td>
                                    <td><a href="{{url('firm/clientfamilydashboard/show/')}}/{{$case->case_id}}" class="btn btn-primary">Detail</a></td>
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