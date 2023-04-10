@extends('layouts.admin-master')

@section('title')
First Reset Password
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>First Reset Password</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.client')}}">client</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">{{$client_name->first_name}} {{$client_name->middle_name}} {{$client_name->last_name}}</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Reset Password</a>
      </div>
    </div>
  </div>
  <div class="section-body">
   
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
        <form action="{{ url('firmclient/update_first_password') }}" method="post" class="needs-validation" novalidate="">
          <div class="card-header">
            <h4>First Reset Password</h4>
          </div>
          <div class="card-body">

          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">New Password <span style="color: red"> * </span>
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="password" placeholder="Set New Password" name="password" class="form-control" required="" /> 
          			<div class="invalid-feedback">Password is required!</div>
          		</div>
          	</div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Conform Password <span style="color: red"> * </span>
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="password" placeholder="Conform Password" name="conform_password" class="form-control" required="" /> 
                <div class="invalid-feedback">Conform Password is required!</div>
              </div>
            </div> 
          	
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			@csrf
          			<button class="btn btn-primary" type="submit" name="first_reset_password">
          				<span>Reset Password</span>
          			</button>
          		</div>
          	</div>
          </div>
        </form>
      </div>
  			</div>
  		</div>
  </div>
</section>
@endsection
