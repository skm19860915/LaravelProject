@extends('layouts.admin-master')

@section('title')
Edit Firm
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Firm Account</h1>
    <div class="section-header-breadcrumb">
      
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
                    <div class="row">
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <p class="lead">Welcome {{Auth::User()->name}}, Do you want to add more users at this time? If you want to skip this step, that's okay. You can always add users later. </p>
                        <div class="mt-4">
                          <a href="{{ url('firm/users/addnewuser') }}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i>Yes</a>
                          
                          @if ($firm->account_type == 'CMS')
                          <a href="{{ url('firm/payment_method2') }}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i>No</a>
                          @endif

                          @if ($firm->account_type == 'VP Services')
                          <a href="{{ url('firm/payment_method2') }}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="far fa-user"></i>No</a>
                          @endif
                        </div>
                      </div>
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
