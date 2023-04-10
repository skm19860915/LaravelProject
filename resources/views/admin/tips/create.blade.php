@extends('layouts.admin-master')

@section('title')
Create Tips
@endsection

@section('content')
<section class="section client-edit-box client-add-box">
  <div class="section-header">
    <h1><a href="{{url('admin/helpfull_tips')}}"><span>Helpfull Tips/</span></a> Create Tips</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
        <form action="{{ url('admin/helpfull_tips/create_tips') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">

          <div class="card-body">
          	
            <div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tips Title
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			<input type="text" placeholder="Tips Title" name="title" class="form-control" required=""> 
          			<div class="invalid-feedback">Tips Title is required!</div>
          		</div>
          	</div> 
          	
          	<div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tips message
              </label> 
              <div class="col-sm-12 col-md-7">               
                <textarea name="message" class="summernote-simple" required=""></textarea> 
                <div class="invalid-feedback">Tips Message is required!</div>
              </div>

            </div>
          	
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			@csrf
          			<button class="btn btn-primary" type="submit">
          				<span>Create Tips</span>
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
