@extends('firmlayouts.admin-master')

@section('title')
Edit Profile ({{ $user->name }})
@endsection

@push('header_styles')
<style type="text/css">
#table tbody tr td:nth-child(1) {
    display: none;
}    
</style>
@endpush  
@section('content')
<section class="section">
  <div class="section-header">
    <h1><a href="{{route('firm.users')}}"><span>Users / </span></a>Edit</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-header">
                <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
                <a href="{{ url('firm/users') }}">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
               </div>
             </div>
              <div class="card-body">
                <form action="{{url('firm/users/update')}}" method="post">
                  <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name</label>
                      <div class="col-sm-12 col-md-7">
                          <input type="text" class="form-control" name="name" value="<?php if(isset(Session::get('data')['name'])) { echo Session::get('data')['name']; } else { echo $user->name;} ?>" required="required">
                          <div class="invalid-feedback">Name is required!</div>
                      </div>
                  </div>
                  <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                      <div class="col-sm-12 col-md-7">
                        <input type="hidden" class="form-control" name="oldemail" value="{{$user->email}}" required="required">
                          <input type="text" class="form-control" name="email" value="<?php if(isset(Session::get('data')['email'])) { echo Session::get('data')['email']; } else { echo $user->email;} ?>" required="required">
                          <div class="invalid-feedback">Email Address is required!</div>
                      </div>
                  </div>
                  <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                      <div class="col-sm-12 col-md-7"> 
                        <?php if($user->role_id == '4') { ?>
                          <input type="text" class="form-control" value="Firm Admin" readonly="readonly">
                        <?php } else { ?>
                          <input type="text" class="form-control" value="Firm User" readonly="readonly">
                        <?php } ?>
                      </div>
                  </div>
                  <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">New Password</label>
                      <div class="col-sm-12 col-md-7">
                          <input type="password" class="form-control" name="new_password" placeholder="New password (Only if you want to change the password)">
                      </div>
                  </div>
                  <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                      <div class="col-sm-12 col-md-7">
                        @csrf
                          <input type="hidden" name="id" value="{{$user->id}}">
                          <button class="btn btn-primary" type="submit" name="update">Update</button>
                      </div>
                  </div>
                </form>
              </div>
          </div>
      </div>
  </div>
      <!-- <profile-component user='{!! $user->toJson() !!}'></profile-component> -->
  </div>
</section>
@endsection
