@extends('layouts.admin-master')

@section('title')
Edit Firm
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Firm Account</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Payment success</a>
      </div>
    </div>
  </div>
  <div class="section-body">
   
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
            <div class="row">
              <div class="col-12 mb-4">
                <div class="hero text-white hero-bg-image hero-bg-parallax" style="background-image: url('{{ url('/') }}/assets/img/unsplash/andre-benz-1214056-unsplash.jpg');">
                  <div class="hero-inner">
                    <h2>Welcome, TILA</h2>
                    <p class="lead">Did you want to add first client?</p>
                    <div class="mt-4">
                      <a href="{{ url('firm/client/create') }}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i>Yes</a>
                      
                      <a href="{{ url('firm/schedule_training') }}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i>No</a>
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
