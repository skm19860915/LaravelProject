@extends('firmlayouts.admin-master')

@section('title')
Edit Profile
@endsection

@section('content')
<section class="section">
  <div class="section-header">

    <h1>Edit Profile</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.profile')}}">Profile</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">{{$user->name}}</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Edit</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/update_profile') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">
            <h4>Edit Profile</h4>
          </div>
          <div class="card-body">
            

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" name="name" value="{{$user->name}}" class="form-control" readonly=""> 
              </div>
            </div>

            

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Contact Email  <span style="color: red"></span>
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="email" readonly="" name="email" value="{{$user->email}}" class="form-control"> 
              </div>
            </div>

             
 
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Contact number
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="current number" value="{{$user->contact_number}}" name="contact_number" class="form-control" > 
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Country
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="birth_address1[country]" id="country">
                  <option value="">Select Country</option>
                  @foreach ($countries as $country) 
                  <option value="{{$country->id}}">
                   {{$country->name}}
                 </option>
                 @endforeach
               </select>               
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">State
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="birth_address1[state]" id="state">
                </select>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">City
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="birth_address1[city]" id="city">
                </select> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <button class="btn btn-primary" type="submit" name="create_firm_lead">
                <span>Update user profile</span>
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

@push('footer_script')
<script type="text/javascript">

$('#country').change(function(){
  var cid = $(this).val();
  if(cid){
    $.ajax({
     type:"get",
         url:"{{ url('getStates') }}/"+cid,
         success:function(res)
         {       
          if(res)
          {
            $("#state").empty();
            $("#city").empty();
            $("#state").append('<option>Select State</option>');
            $.each(res,function(key,value){
              $("#state").append('<option value="'+key+'">'+value+'</option>');
            });
          }
        }

      });
  }
});
$('#state').change(function(){
  var sid = $(this).val();
  if(sid){
    $.ajax({
     type:"get",
         url:"{{ url('getCities') }}/"+sid, 
         success:function(res)
         {       
          if(res)
          {
            $("#city").empty();
            $("#city").append('<option>Select City</option>');
            $.each(res,function(key,value){
              $("#city").append('<option value="'+key+'">'+value+'</option>');
            });
          }
        }

      });
  }
});


</script>
@endpush 