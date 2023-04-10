@extends('layouts.admin-master')

@section('title')
Task Detail
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Show Task Detail</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('admin.dashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('admin.task')}}">Task</a>
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
            <h4>Show Task Detail</h4>
          </div>

          <div class="card-body">

            <?php if(!empty($admintask)){ ?>

              <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Task Id
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$admintask->id}}
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Case Id
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$admintask->case_id}}
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Firm Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$admintask->firm_name}}
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Id
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$admintask->clientid}}
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$admintask->clientname}}
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Email
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$admintask->clientemail}}
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">VA User Id
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$admintask->vauserid}}
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">VA User Name
              </label> 
              <div class="col-sm-12 col-md-7">

                {{$admintask->vausername}}
                
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">VA User Email
              </label> 
              <div class="col-sm-12 col-md-7">
                {{$admintask->vauseremail}}
              </div>
            </div> 

            <?php }else{ ?>

              <div class="">
                Please allot VA user first
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