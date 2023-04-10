@extends('firmlayouts.admin-master')

@section('title')
Convert Lead TO Client
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.lead')}}">Lead</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">create client</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/lead/convert_client') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">
            <h4>Convert Lead TO Client</h4>
          </div>
          <div class="card-body">
            

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client first Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" name="first_name" value="{{$lead->name}}" class="form-control" placeholder="Client First Name" required=""> 
                <div class="invalid-feedback">Client First Name is required!</div>
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Middle Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Middle Name" name="middle_name" class="form-control"> 
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Last Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Last Name" value="{{$lead->last_name}}" name="last_name" class="form-control"> 
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Contact Email
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="email" value="{{$lead->email}}"  name="email" class="form-control" > 
                <div class="invalid-feedback">Contact Email is required!</div>
              </div>
            </div>

            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Cell Phone
              </label> 
              <div class="col-sm-4 col-md-3">
                <select class="form-control" name="codes">
                  <option value="">Please Select</option>
                <?php
                $codes = countryDialCodes();
                // pre($codes);
                foreach ($codes as $k => $v) {
                  extract($v);
                  echo "<option value='$code'>$name</option>";
                }
                ?>                
                </select>
              </div>
              <div class="col-sm-8 col-md-4">
                <input type="text" value="{{$lead->cell_phone}}" name="cell_phone" class="form-control"> 
              </div>
            </div> 

            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Language
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control"  name="language">
                  <option value="">Select One</option>
                  <option <?php if($lead->language == "English"){echo "selected"; }?> value="English">English</option>
                  <option <?php if($lead->language == "Hindi"){echo "selected"; }?> value="Hindi">Hindi</option>
                  <option <?php if($lead->language == "Franch"){echo "selected"; }?> value="Franch">Franch</option>
                  <option <?php if($lead->language == "Italian"){echo "selected"; }?> value="Italian">Italian</option>
                </select> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Client Type
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="type">
                  <option value="">Select One</option>
                  <option value="Type 1">Type 1</option>
                  <option  value="Type 2">Type 2</option>
                  <option  value="Type 3">Type 3</option>
                  <option  value="Type 4">Type 4</option>
                </select> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Portal Access ?
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="is_portal_access">
                  <option value="">Select One</option>
                  <option  value="1">YES</option>
                  <option  value="0">NO</option>
                </select> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Detained ?
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="is_detained">
                  <option value="">Select One</option>
                  <option  value="1">YES</option>
                  <option  value="0">NO</option>
                </select> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Deported ?
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="is_deported">
                  <option value="">Select One</option>
                  <option  value="1">YES</option>
                  <option  value="0">NO</option>
                </select> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Outside Of Us ?
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="is_outside_us">
                  <option value="">Select One</option>
                  <option  value="1">YES</option>
                  <option  value="0">NO</option>
                </select> 
              </div>
            </div>


            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Residence Address
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" value="{{$lead->Current_address}}" name="residence_address" class="form-control"> 
              </div>
            </div> 

            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Mailing Address
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" value="{{$lead->Current_address}}" name="mailing_address" class="form-control"> 
              </div>
            </div> 

            
            <div class="form-group row mb-4" style="display: none;">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Full Legal Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" value="{{$lead->name}}" name="full_legal_name" class="form-control"> 
              </div>
            </div> 

            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Date Of Birth
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="date" value="{{$lead->dob}}" name="dob" class="form-control"> 
              </div>
            </div> 

            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Previous Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Previous Name" name="previous_name" class="form-control"> 
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Maiden Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Maiden Name" name="maiden_name" class="form-control"> 
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Alien Number
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Alien Number" name="alien_number" class="form-control"> 
              </div>
            </div> 
            

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Social Security Number
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Social Security Number" name="Social_security_number" class="form-control"> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Birth Address
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Birth Address" name="birth_address" class="form-control"> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Client Gender
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="gender">
                  <option value="">Select One</option>
                  <option  value="1">Male</option>
                  <option  value="2">Femele</option>
                  <option  value="3">Trans-Gender</option>
                </select> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Eye Color
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Eye Color" name="eye_color" class="form-control"> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Hair Color
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Hair Color" name="hair_color" class="form-control"> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client height
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client height" name="height" class="form-control"> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client weight
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Weight" name="weight" class="form-control"> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client race
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client race" name="race" class="form-control"> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client ethnicity
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client ethnicity" name="ethnicity" class="form-control"> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Religion
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Client Religion" name="religion" class="form-control"> 
              </div>
            </div>


            <input type="hidden" id="" value={{$id}} name="lead_id" class="form-control" >
            <input type="hidden" id="" value="{{$lead->document_path}}" name="image_path" class="form-control" > 
            

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <button class="btn btn-primary" type="submit" name="convert_lead_to_client">
                <span>Convert Lead To Client</span>
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
