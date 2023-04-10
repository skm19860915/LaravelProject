@extends('firmlayouts.admin-master')

@section('title')
Create client
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
        <a href="{{route('firm.client')}}">Client</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Add</a>
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
                    <p class="lead">Did user want to create First case?</p>
                    <div class="mt-4">
                      <a href="{{ url('firm/case/create') }}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i>Yes</a>
                      
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
