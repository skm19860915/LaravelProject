@extends('firmlayouts.admin-master')

@section('title')
View client
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Show Firm Client</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.firmuserdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.firmclients')}}">Client</a>
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
            <h4>Show Firm Client</h4>
          </div>
          <div class="card-body">

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client first Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->first_name }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Middle Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->middle_name }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Last Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->last_name }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Contact Email
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->email }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Cell Phone
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->cell_phone }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Language
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->language }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Type
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->type }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Portal Access
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo $retVal = ($client->is_portal_access == 1) ? "YES" : "NO" ; ?>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Detained
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo $retVal = ($client->is_detained == 1) ? "YES" : "NO" ; ?>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Deported 
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo $retVal = ($client->is_deported == 1) ? "YES" : "NO" ; ?>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Outside Of Us ?
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo $retVal = ($client->is_outside_us == 1) ? "YES" : "NO" ; ?>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Residence Address
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->residence_address }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Mailing Address
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->mailing_address }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Full Legal Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->full_legal_name }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Date Of Birth
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->dob }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Previous Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->previous_name }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Alien Number
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->alien_number }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Social Security Number
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->Social_security_number }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Birth Address
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->birth_address }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Gender
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->gender }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Eye Color
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->eye_color }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Hair Color
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->hair_color }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client height
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->height }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client weight
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->weight }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client race
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->race }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client ethnicity
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->ethnicity }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Image
              </label> 
              <div class="col-sm-12 col-md-7">
                <img src="{{asset('storage/app')}}/{{$client->image_path}}" width="200px" height="200px">
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
