@extends('layouts.admin-master')

@section('title')
Edit Firm
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Firm Account Set Up</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
   
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
        <form action="{{ url('firm/FirmAccountUpdate') }}" method="post" class="needs-validation" novalidate="">
          <div class="card-header">
            <h4>Firm Account Set Up</h4>
          </div>
          <div class="card-body">

          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Name
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="text" placeholder="Firm Name" name="firm_name" class="form-control" required="" value="{{$firm->firm_name}}" /> 
          			<div class="invalid-feedback">Firm Name is required!</div>
          		</div>
          	</div> 
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Account Type
          		</label> 
          		<div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Firm Name" name="account_type" class="form-control" required="" value="{{$firm->account_type}}" readonly="readonly" />
          			<div class="invalid-feedback">Please select Account Type!</div>
          		</div>
          	</div> 
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Admin User's Email
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="email" placeholder="Firm Admin User's Email." name="firm_admin_email" class="form-control" readonly="" value="{{$firm->email}}">  
          			<div class="invalid-feedback">Firm Admin User's Email is required!</div>
          		</div>
          	</div> 
          	
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Admin User's Name
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="text"  name="firm_admin_name" placeholder="Firm Admin User's Name" class="form-control" value="{{$firm->firm_admin_name}}" readonly="readonly"> 
          			<div class="invalid-feedback">Firm Admin User's Name is required!</div>
          		</div>
          	</div> 
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			@csrf
                <input type="hidden" name="firm_id" class="form-control" value="{{$firm->id}}" /> 
                <input type="hidden" name="user_id" class="form-control" value="{{$user->id}}" /> 
          			<button class="btn btn-primary" type="submit" name="create_firm_Account">
          				<span>Confirm</span>
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
