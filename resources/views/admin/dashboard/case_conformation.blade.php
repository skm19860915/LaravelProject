@extends('layouts.admin-master')

@section('title')
Case Conformation
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Case Conformation</h1>
    @include('layouts.breadcrumb')
  </div>
  <div class="section-body">
   
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
            <div class="row">
              <div class="col-12 mb-4">
                <div class="hero text-white hero-bg-image hero-bg-parallax" style="background-image: url('{{ url('/') }}/assets/img/unsplash/andre-benz-1214056-unsplash.jpg');">
                  <div class="hero-inner">
                    <h2>Welcome, {{$user->name}}</h2>
                    <p class="lead">Do you want to reactivate Old Cases?</p>
                    <div class="mt-4">
                      <a href="{{ url('admin/firm/caseIsConform')}}/{{$firm_id}}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i>Yes</a>
                      
                      <a href="{{ url('/') }}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i>Go to Dashboard</a>
                    </div>
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
