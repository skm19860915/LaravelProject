@extends('layouts.admin-master')

@section('title')
Tips Detail
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Show Tips</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('admin.userdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('admin.usertips')}}">User tips</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Detail</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
       
          <div class="card-header">
            <h4>Show Tips Detail</h4>
          </div>

          <div class="card-body">

            <?php if(!empty($tips)){ ?>

              <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tips Id
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$tips->id}}
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$tips->title}}
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Message
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$tips->message}}
              </div>
            </div> 

            <?php }else{ ?>

              <div class="">
                 first
              </div> 

            <?php } ?>

            
 
          </div>
      </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>
@endsection