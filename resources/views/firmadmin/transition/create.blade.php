@extends('firmlayouts.admin-master')

@section('title')
Create Translation
@endsection

@push('header_styles')
<link  href="{{ asset('assets/css/daterangepicker.css') }}" rel="stylesheet">
@endpush 

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Create Translation</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{url('firm/transition')}}/{{$id}}">Translation</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Add</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/transition/create_transition') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">
            <h4>Create Translation</h4>
          </div>
          <div class="card-body">
            

            <div class="form-group row mb-4 doc_repeater">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Documents to be translated
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="file" name="document" class="form-control" required="required" /> 
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Language
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="language" required="required">
                  <option value="">Select One</option>
                  <option value="English">English</option>
                  <option value="Hindi">Hindi</option>
                  <option value="Franch">Franch</option>
                  <option value="Italian">Italian</option>
                  <option value="Spanish">Spanish</option>
                  <option value="German">German</option>
                </select> 
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Can TILA Contact Client Directly?<span style="color: red"> *</span>
              </label> 
              <div class="col-sm-12 col-md-7">
                <div class="custom-switches-stacked mt-2">
                  <label class="custom-switch">
                    <input type="radio" name="can_tila_contact" value="1" class="custom-switch-input" checked="checked">
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description">Yes</span>
                  </label>
                  <label class="custom-switch">
                    <input type="radio" name="can_tila_contact" value="0" class="custom-switch-input">
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description">No</span>
                  </label>
                </div>
              </div>
            </div> 

             

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <input type="hidden" name="client_id" value="{{$id}}" />
                <button class="btn btn-primary" value="1" type="submit" name="create_firm_lead">
                <span>Create Translation</span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
        </div>
      </div>
  </div>
</section>
@endsection
@push('footer_script')
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.datepicker').val('');
  setTimeout(function(){
    $('.datepicker').daterangepicker({
      locale: {format: 'YYYY-MM-DD'},
      singleDatePicker: true,
      timePicker: false,
      timePicker24Hour: false
    });
    $('.datepicker').val('');
  }, 1000);
});
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
$(document).on('click', '.add_file', function(e){
  e.preventDefault();
  var doc_repeater = $(this).closest('.doc_repeater').clone();
  $(this).closest('.doc_repeater').after(doc_repeater);
});
$(document).on('click', '.remove_file', function(e){
  e.preventDefault();
  var doc_repeater = $('.doc_repeater').length;
  if(doc_repeater > 1) {
    $(this).closest('.doc_repeater').remove();
  }
});

$('.phone_us').mask('(000) 000-0000');


</script>
@endpush 
