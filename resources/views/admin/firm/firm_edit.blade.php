@extends('layouts.admin-master')

@section('title')
Edit Firm
@endsection

@section('content')
<section class="section client-listing-details">
  <div class="section-header">
    <h1><a href="{{ url('admin/firm') }}"><span>Firm /</span></a> Edit</h1>
  </div>
  <div class="client-header-new">
   <div class="clent-profle-box">
    <div class="row">
     <div class="col-md-8">
      <div class="client-main-box-profile">
      <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
      <div class="client-right-text">
       <h3>
         {{$firm->firm_name}}
       </h3>
       <p>{{ $firm->email }}<br />
        Join {{ date('M d, Y', strtotime($firm->created_at)) }}</p>
      </div>  
      </div>    
     </div>
     <div class="col-md-4">
      <div class="client-right-profile">
       <div class="clent-info"><span>Firm ID</span>:<span>#{{ $firm->id }}</span></div>
       <div class="clent-info"><span>Admin User Name</span>:<span>{{ $firm->firm_admin_name }}</span></div>
       <div class="clent-info"><span>Account Type</span>:<span>
         {{ $firm->account_type }}
       </span></div>
       <div class="clent-info"><span>Status</span>:<span><?php echo isset($firm->status) ? 'Active' : 'In-active'; ?></span></div>
      </div>
     </div>
    </div>
   </div>
  </div>

  <div class="section-body">
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
        <form action="{{ url('admin/firm/update_firm') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="back-btn-new">
            <a href="{{ url('admin/firm') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-header">
            <h4>Edit Firm Account</h4>
          </div>
          <div class="card-body">

          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Name
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="text" placeholder="Firm Name" name="firm_name" class="form-control" required="" value="<?php if(isset(Session::get('data')['firm_name'])) { echo Session::get('data')['firm_name']; } else { echo $firm->firm_name; } ?>" /> 
          			<div class="invalid-feedback">Firm Name is required!</div>
          		</div>
          	</div> 
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Account Type
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="text" class="form-control" required="" name="account_type" value="{{$firm->account_type}}" readonly="readonly">
          			<div class="invalid-feedback">Please select Account Type!</div>
          		</div>
          	</div> 
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Admin User's Email
          		</label> 
          		<div class="col-sm-12 col-md-7">
                <input type="hidden" name="oldemail" value="{{$firm->email}}" />  
          			<input type="email" placeholder="Firm Admin User's Email." name="email" class="form-control" value="<?php if(isset(Session::get('data')['email'])) { echo Session::get('data')['email']; } else { echo $firm->email; } ?>" required="required">  
          			<div class="invalid-feedback">Firm Admin User's Email is required!</div>
          		</div>
          	</div> 
          	
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Admin User's Name
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="text"  name="firm_admin_name" placeholder="Firm Admin User's Name" class="form-control" value="<?php if(isset(Session::get('data')['firm_admin_name'])) { echo Session::get('data')['firm_admin_name']; } else { echo $firm->firm_admin_name; } ?>" required="required"> 
          			<div class="invalid-feedback">Firm Admin User's Name is required!</div>
          		</div>
          	</div>
            <?php if($firm->account_type == 'CMS') { ?>
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">User Cost
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" name="" class="form-control" value="{{$firm->usercost}}" readonly="readonly">
                  
              </div>
            </div>
            <?php } ?>
            <input type="hidden" name="usercost" value="{{$firm->usercost}}">
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			@csrf
                <input type="hidden" name="id" class="form-control" value="{{$firm->id}}" /> 
          			<button class="btn btn-primary" type="submit" name="create_firm_Account">
          				<span>Update Firm Account</span>
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
