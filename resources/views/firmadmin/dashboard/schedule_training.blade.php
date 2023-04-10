@extends('layouts.admin-master')

@section('title')
Edit Firm
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Schedule Training</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Training schedule</a>
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
                    <h2>Welcome, {{$user->name}}</h2>
                    <p class="lead">Did you want to Create TILA Admin Task to Schedule Training?</p>
                    <div class="mt-4">
                      <a href="{{ url('firm/training_scheduled') }}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i>Yes</a>
                      
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
