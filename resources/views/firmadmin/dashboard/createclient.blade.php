@extends('firmlayouts.admin-master')

@section('title')
Create Client
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Create Client</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Client Create</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/users/create_user') }}" method="post" class="needs-validation" novalidate="">
          <div class="card-header">
            <h4>Create Client</h4>
          </div>
          <div class="card-body">
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="User Name" name="user_name" class="form-control" required=""> 
                <div class="invalid-feedback">Firm User Name is required!</div>
              </div>
            </div> 
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Date of Birth
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="User Name" name="user_name" class="form-control datepicker" required=""> 
                <div class="invalid-feedback">Firm User Name is required!</div>
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">User Email
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="email" placeholder="User Email." name="user_email" class="form-control" required=""> 
                <div class="invalid-feedback">Firm User Email is required!</div> 
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Account Type
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="" name="Role_type">
                  <option value="4">Firm Admin</option>
                  <option value="5">Firm User</option>
                  <option value="6">Firm Client</option>
                </select>
                <div class="invalid-feedback">Please select Role Type!</div> 
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">User password
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="password" placeholder="User Password." name="user_password" class="form-control" required=""> 
                <div class="invalid-feedback">Firm User password is required!</div> 
              </div>
            </div> 
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <button class="btn btn-primary" type="submit" name="create_firm_user">
                  <span>Create Firm user</span>
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
