@extends('firmlayouts.admin-master')

@section('title')
View Case
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Show Firm Case</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.firmuserdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.usercase')}}">Case</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Show</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
       
          <div class="card-header">
            <h4>Show Firm Case</h4>
          </div>
          <div class="card-body">


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $casedata->client_name }}
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">case type
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php if($casedata->case_type == 1){
                  echo "Monthly";
                }else{
                  echo "Self Managed";
                }?>
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Alloted user Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $casedata->user_name }}
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Case cost
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $casedata->case_cost }}
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Document image
              </label> 
              <div class="col-sm-12 col-md-7">
                <img src="{{asset('storage/app')}}/{{$casedata->case_file_path}}" width="200px" height="200px">
              </div>
            </div> 
 
          </div>
      </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>
@endsection
