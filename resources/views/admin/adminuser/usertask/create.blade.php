@extends('layouts.admin-master')

@section('title')
Create Firm
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Create Firm Account</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('admin.userdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('admin.usertask')}}">User task</a>
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
        <form action="{{ url('admin/firm/create_firm') }}" method="post" class="needs-validation" novalidate="">
          <div class="card-header">
            <h4>Create Firm Account</h4>
          </div>
          <div class="card-body">
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Name
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="text" placeholder="Firm Name" name="firm_name" class="form-control" required=""> 
          			<div class="invalid-feedback">Firm Name is required!</div>
          		</div>
          	</div> 
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Account Type
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<select class="form-control" required="" name="account_type">
          				<option value="">Select</option>
          				<option value="Monthly">Monthly</option>
          				<option value="VA Services Only">VA Services Only</option>
          			</select> 
          			<div class="invalid-feedback">Please select Account Type!</div>
          		</div>
          	</div> 
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Admin User's Email
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="email" placeholder="Firm Admin User's Email." name="firm_admin_email" class="form-control" required="">  
          			<div class="invalid-feedback">Firm Admin User's Email is required!</div>
          		</div>
          	</div> 
          	
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Admin User's Name
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="text"  name="firm_admin_name" placeholder="Firm Admin User's Name" class="form-control" required=""> 
          			<div class="invalid-feedback">Firm Admin User's Name is required!</div>
          		</div>
          	</div> 
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			@csrf
          			<button class="btn btn-primary" type="submit" name="create_firm_Account">
          				<span>Create Firm Account</span>
          			</button>
          		</div>
          	</div>
          </div>
        </form>
      </div>
  			</div>
  		</div>
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>
@endsection
