@extends('layouts.admin-master')

@section('title')
Edit Massage
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1><a href="{{route('admin.setting.email')}}"><span>Email Notifications /</span></a> Edit</h1>
    <div class="section-header-breadcrumb">
      
    </div>  
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('admin/setting/update_message') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          
          <div class="card-body">

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Title
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" readonly="" value="{{$record->title}}"  class="form-control"> 
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Subtitle
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" readonly="" value="{{$record->subtitle}}"  class="form-control"> 
              </div>
            </div>

           	<div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Message
              </label> 
              <div class="col-sm-12 col-md-7">               
                <textarea name="message" rows="10" cols="63" class="summernote-simple" required="">{{$record->massage}}</textarea> 
                <div class="invalid-feedback"> Message is required !</div>
              </div>

            </div>

            <input type="hidden" name="category" value="{{$record->category}}"> 
            <input type="hidden" name="setting_id" value="{{$record->id}}"> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <button class="btn btn-primary" type="submit" name="Update_message">
                  <span>Update Message</span>
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
