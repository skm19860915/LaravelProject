@extends('layouts.admin-master')

@section('title')
Create User
@endsection

@section('content')
<section class="section client-edit-box client-add-box">
  <div class="section-header">
    <h1><a href="{{url('admin/users')}}"><span>Users /</span></a> Add New User</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
 
  <div class="section-body">

  	<div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('admin/users/create_user') }}" method="POST" class="needs-validation" novalidate="">
          <div class="back-btn-new">
            <a href="{{ url('admin/users') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-body">
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Full Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Name" name="name" class="form-control" required="required" value="<?php if(isset(Session::get('data')['name'])) { echo Session::get('data')['name']; }?>"> 
                <div class="invalid-feedback">User Name is required!</div>
              </div>
            </div> 
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="email" placeholder="Email" name="email" class="form-control" required="" value="<?php if(isset(Session::get('data')['email'])) { echo Session::get('data')['email']; }?>"> 
                <div class="invalid-feedback">User Email is required!</div> 
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="" name="role_id">
                  <option value="1" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == 1) { echo "selected='selected'";} ?>>TILA Admin</option>
                  <option value="2" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == 2) { echo "selected='selected'";} ?>>TILA VP</option>
                  <!-- <option value="3" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == 3) { echo "selected='selected'";} ?>>Tila Support</option> -->
                </select>
                <div class="invalid-feedback">Please select Role Type!</div> 
              </div>
            </div>
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <button class="btn btn-primary" type="submit" name="create_user">
                  <span>Create User</span>
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
