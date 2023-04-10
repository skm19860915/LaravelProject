@extends('layouts.admin-master')
@section('title')
Search Results
@endsection

@push('header_styles')

@endpush 
@section('content')
<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <h1>Search Results</h1>
        <div class="section-header-breadcrumb">

        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-md-6">
                                <div class="profile-new-client">
                                    <?php 
                                    $rkcheck = true;
                                    if(!empty($result['users'])) {
                                        $rkcheck = false;
                                        echo '<h4>Team</h4><br><ul class="list-unstyled list-unstyled-border list-unstyled-noborder">';
                                        foreach ($result['users'] as $key => $user) { ?>
                                    
                                        <li class="media">
                                            <img alt="image" class="mr-3 rounded-circle" width="70" src="{{ url('assets/img/avatar/avatar-1.png') }}">
                                            <div class="media-body">
                                                <div class="media-right">
                                                    <div class="text-primary"></div>
                                                </div>
                                                <div class="media-title mb-1">
                                                    {{ $user->name }}
                                                </div>
                                                <div class="text-time">{{$user->email}}</div>
                                                <div class="media-description text-muted">
                                                    Create Date : {{ date('m/d/Y', strtotime($user->created_at)) }}
                                                </div>
                                                <div class="media-links">
                                                    <a href="{{url('admin/users/show')}}/{{$user->id}}">View</a>
                                                    <div class="bullet"></div>
                                                    <a href="{{url('admin/users')}}/{{$user->id}}/edit">Edit</a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } echo '</ul>'; } ?>
                                    <?php 
                                    if(!empty($result['firms'])) {
                                        $rkcheck = false;
                                        echo '<br><br><h4>Firms</h4><br><ul class="list-unstyled list-unstyled-border list-unstyled-noborder">';
                                        foreach ($result['firms'] as $key => $f) { ?>
                                    
                                        <li class="media">
                                            <img alt="image" class="mr-3 rounded-circle" width="70" src="{{ url('assets/img/avatar/avatar-1.png') }}">
                                            <div class="media-body">
                                                <div class="media-right">
                                                    <div class="text-primary"></div>
                                                </div>
                                                <div class="media-title mb-1">
                                                    {{ $f->firm_name }}
                                                </div>
                                                <div class="text-time">{{$f->email}}</div>
                                                <div class="media-description text-muted">
                                                    Create Date : {{ date('m/d/Y', strtotime($f->created_at)) }}
                                                </div>
                                                <div class="media-links">
                                                    <a href="{{url('admin/firm/firm_details')}}/{{$f->id}}">View</a>
                                                    <div class="bullet"></div>
                                                    <a href="{{url('admin/firm/firm_edit')}}/{{$f->id}}">Edit</a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } echo '</ul>'; } ?>
                                    <?php 
                                    if(!empty($result['clients'])) {
                                        $rkcheck = false;
                                        echo '<br><br><h4>Clients</h4><br><ul class="list-unstyled list-unstyled-border list-unstyled-noborder">';
                                        foreach ($result['clients'] as $key => $client) { ?>
                                    
                                        <li class="media">
                                            <img alt="image" class="mr-3 rounded-circle" width="70" src="{{ url('assets/img/avatar/avatar-1.png') }}">
                                            <div class="media-body">
                                                <div class="media-right">
                                                    <div class="text-primary"></div>
                                                </div>
                                                <div class="media-title mb-1">
                                                    <?php
                                                     echo $client->first_name;
                                                     if(!empty($client->middle_name)) {
                                                        echo " $client->middle_name";
                                                     }
                                                     if(!empty($client->last_name)) {
                                                        echo " $client->last_name";
                                                     }
                                                     ?>
                                                </div>
                                                <div class="text-time">{{$client->email}}</div>
                                                <div class="media-description text-muted">
                                                    Create Date : {{ date('m/d/Y', strtotime($client->created_at)) }}
                                                </div>
                                                <div class="media-links">
                                                    <a href="{{url('admin/users/viewclient/')}}/{{$client->user_id}}">View</a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } echo '</ul>'; } ?>
                                    
                                    <?php 
                                    if(!empty($result['cases'])) {
                                        $rkcheck = false;
                                        echo '<br><br><h4>Cases</h4><br><ul class="list-unstyled list-unstyled-border list-unstyled-noborder">';
                                        foreach ($result['cases'] as $key => $case) { ?>
                                            <li class="media">
                                                <div class="media-body">
                                                  <div class="media-title">
                                                    {{$case->case_type}}
                                                  </div>
                                                  <div class="text-job text-muted">{{$case->case_category}}</div>
                                                </div>
                                                <div class="media-progressbar">
                                                  <div class="progress-text">
                                                    Status : {{ GetCaseStatus($case->status) }}
                                                  </div>
                                                </div>
                                                <div class="media-cta">
                                                  <a href="{{url('admin/allcases/show')}}/{{$case->id}}" class="btn btn-outline-primary">Detail</a>
                                                </div>
                                              </li>
                                    <?php } echo '</ul>'; } ?>

                                    <?php if($rkcheck) {
                                        echo '<div>No result found!</div>';
                                    }
                                    ?>
                                </div>
                            </div>
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