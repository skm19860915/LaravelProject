@extends('firmlayouts.client-family')

@section('title')
Family Invoice List
@endsection

@section('content')
<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <h1>Family Invoice List</h1>
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
                                        <th>Task</th>
                                        <th>Case Type</th>
                                        <th>Alien/Client</th>
                                        <th>Status</th>
                                        <th>Create Date</th>
                                        <th>Action</th>
                                    </tr>

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