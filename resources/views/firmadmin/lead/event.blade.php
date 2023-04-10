@extends('firmlayouts.admin-master')

@section('title')
Set Event
@endsection

@push('header_styles')
<link  href="{{ asset('assets/css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
<link  href="{{ asset('assets/css/daterangepicker.css') }}" rel="stylesheet">
@endpush 

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Event Setup</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.lead')}}">Lead</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">{{$lead->name}} {{$lead->last_name}}</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Event</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/lead/create_lead_event') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">
            <h4>Event setup</h4>
          </div>
          <div class="card-body">
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Event Date <span style="color: red"> *</span>
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Date" name="date" class="form-control datepicker" required="required"> 
                <div class="invalid-feedback">Event Date is required!</div>
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Event Time <span style="color: red"> *</span>
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Time" name="time" class="form-control timepicker" required="required"> 
                <div class="invalid-feedback">Event Time is required!</div>
              </div>
            </div> 

            <input type="hidden" name="lead_id" value="{{$lead_id}}" class="form-control" >  

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Who Consult With <span style="color: red"> *</span>
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" size="5" multiple name="who_consult_with[]" style="height: 150px;" required="required">
                  @foreach ($user as $value) 
                  <option value="{{$value->id}}"  <?php if ($user_id == $value->id) {
  echo 'selected="selected"';
} ?>>
                   {{$value->name}}
                 </option>
                 @endforeach
               </select>               
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <button class="btn btn-primary" value="1" type="submit" name="create_lead_with_event">
                <span>Submit Event</span>
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
<script src="{{ asset('assets/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  setTimeout(function(){
      $('.datepicker').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        singleDatePicker: true,
        timePicker: false,
        timePicker24Hour: false,
      });
      $(".timepicker").timepicker({
        icons: {
          up: 'fas fa-chevron-up',
          down: 'fas fa-chevron-down'
        }
      });
    },1000);
});


$('.phone_us').mask('(000) 000-0000');


</script>
@endpush 
