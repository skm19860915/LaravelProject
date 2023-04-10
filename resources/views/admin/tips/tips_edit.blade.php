@extends('layouts.admin-master')

@section('title')
Edit Tips
@endsection

@section('content')
<section class="section client-edit-box client-add-box">
  <div class="section-header">
    <h1><a href="{{url('admin/helpfull_tips')}}"><span>Helpfull Tips/</span></a> Edit Tips</h1>
    <div class="section-header-breadcrumb">
    </div>
  </div>
  <div class="section-body">
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
        <form action="{{ url('admin/helpfull_tips/update_tips') }}" method="post" class="needs-validation" novalidate="">
          <div class="card-body">


           <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tips Title
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Tips Title" name="title" class="form-control" value="{{$tips->title}}" required=""> 
                <div class="invalid-feedback">Tips Title is required!</div>
              </div>
            </div> 
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tips message
              </label> 
              <div class="col-sm-12 col-md-7">               
                <textarea name="message" class="summernote-simple" required="">{{$tips->message}}</textarea> 
                <div class="invalid-feedback">Tips Message is required!</div>
              </div>

            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Manage Status
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="status">
                  <option  value="1">Active</option>
                  <option value="0">Inactive</option>
                </select> 
              </div>
            </div> 
          
          	
          	
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			@csrf
                <input type="hidden" name="tips_id" class="form-control" value="{{$tips->id}}" /> 
          			<button class="btn btn-primary" type="submit" name="">
          				<span>Update Tips</span>
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
