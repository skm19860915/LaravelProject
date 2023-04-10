@extends('firmlayouts.admin-master')

@section('title')
Create Lead
@endsection

@push('header_styles')
<link  href="{{ asset('assets/css/daterangepicker.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
.selectedfiles {
  padding: 0;
  margin: 0;
}  
.selectedfiles li {
  display: inline-block;
  vertical-align: top;
  margin-bottom: 15px;
  position: relative;
  width: 90px;
  margin-right: 10px;
}
.selectedfiles li input {
  display: none;
}
.selectedfiles li a.remove_file {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 99;
    padding: 1px;
    color: #fff;
    background: rgba(0,0,0,0.5);
}
.selectedfiles li label {
  display: block;
  text-align: center;
}
</style>
@endpush 

@section('content')
<section class="section">
  <div class="section-header">
    <div class="breadcrumb-item">
      <a href="{{route('firm.lead')}}">Lead</a>
    </div>
    <div class="breadcrumb-item">
      <h1>Create New Lead</h1>
    </div>
    <div class="section-header-breadcrumb">
      <!-- <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.lead')}}">Lead</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Add</a>
      </div> -->
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/lead/create_lead') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <!-- <div class="card-header">
            <h4>Create Firm Lead</h4>
          </div> -->
          <div class="card-body">

            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">First Name <span style="color: red"> *</span>
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="First Name" name="name" class="form-control" required="required"> 
                    <div class="invalid-feedback">First Name is required!</div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label tcol-md-4 col-sm-4">Last Name <span style="color: red"> *</span>
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Last Name" name="last_name" class="form-control" required="required"> 
                    <div class="invalid-feedback">Last Name is required!</div>
                  </div>
                </div> 
              </div>
            </div>

            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Contact Email <span style="color: red"></span>
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="email" placeholder="Contact Email" name="email" class="form-control"> 
                    <div class="invalid-feedback">Contact Email is required!</div>
                  </div>
                </div>
              </div>
              
            </div>

            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Cell Phone <span style="color: red"> *</span>
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Cell Phone" name="cell_phone" class="form-control phone_us" required="required"> 
                    <div class="invalid-feedback">Cell Phone is required!</div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Home Phone
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Home Phone" name="home_phone" class="form-control phone_us" > 
                  </div>
                </div>
              </div>
              
            </div>

            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">DOB
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="mm/dd/yyyy" name="dob" class="form-control dob" > 
                    
                  </div>
                </div> 
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4"> Language
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <select class="selectpicker" name="language" data-live-search="true">
                      <option value="">Select One</option>
                      <option value="Afrikaans">Afrikaans</option>
                      <option value="Albanian">Albanian</option>
                      <option value="Arabic">Arabic</option>
                      <option value="Armenian">Armenian</option>
                      <option value="Basque">Basque</option>
                      <option value="Bengali">Bengali</option>
                      <option value="Bulgarian">Bulgarian</option>
                      <option value="Catalan">Catalan</option>
                      <option value="Cambodian">Cambodian</option>
                      <option value="Chinese (Mandarin)">Chinese (Mandarin)</option>
                      <option value="Croatian">Croatian</option>
                      <option value="Czech">Czech</option>
                      <option value="Danish">Danish</option>
                      <option value="Dutch">Dutch</option>
                      <option value="English">English</option>
                      <option value="Estonian">Estonian</option>
                      <option value="Fiji">Fiji</option>
                      <option value="Finnish">Finnish</option>
                      <option value="French">French</option>
                      <option value="Georgian">Georgian</option>
                      <option value="German">German</option>
                      <option value="Greek">Greek</option>
                      <option value="Gujarati">Gujarati</option>
                      <option value="Hebrew">Hebrew</option>
                      <option value="Hindi">Hindi</option>
                      <option value="Hungarian">Hungarian</option>
                      <option value="Icelandic">Icelandic</option>
                      <option value="Indonesian">Indonesian</option>
                      <option value="Irish">Irish</option>
                      <option value="Italian">Italian</option>
                      <option value="Japanese">Japanese</option>
                      <option value="Javanese">Javanese</option>
                      <option value="Korean">Korean</option>
                      <option value="Latin">Latin</option>
                      <option value="Latvian">Latvian</option>
                      <option value="Lithuanian">Lithuanian</option>
                      <option value="Macedonian">Macedonian</option>
                      <option value="Malay">Malay</option>
                      <option value="Malayalam">Malayalam</option>
                      <option value="Maltese">Maltese</option>
                      <option value="Maori">Maori</option>
                      <option value="Marathi">Marathi</option>
                      <option value="Mongolian">Mongolian</option>
                      <option value="Nepali">Nepali</option>
                      <option value="Norwegian">Norwegian</option>
                      <option value="Persian">Persian</option>
                      <option value="Polish">Polish</option>
                      <option value="Portuguese">Portuguese</option>
                      <option value="Punjabi">Punjabi</option>
                      <option value="Quechua">Quechua</option>
                      <option value="Romanian">Romanian</option>
                      <option value="Russian">Russian</option>
                      <option value="Samoan">Samoan</option>
                      <option value="Serbian">Serbian</option>
                      <option value="Slovak">Slovak</option>
                      <option value="Slovenian">Slovenian</option>
                      <option value="Spanish">Spanish</option>
                      <option value="Swahili">Swahili</option>
                      <option value="Swedish ">Swedish </option>
                      <option value="Tamil">Tamil</option>
                      <option value="Tatar">Tatar</option>
                      <option value="Telugu">Telugu</option>
                      <option value="Thai">Thai</option>
                      <option value="Tibetan">Tibetan</option>
                      <option value="Tonga">Tonga</option>
                      <option value="Turkish">Turkish</option>
                      <option value="Ukrainian">Ukrainian</option>
                      <option value="Urdu">Urdu</option>
                      <option value="Uzbek">Uzbek</option>
                      <option value="Vietnamese">Vietnamese</option>
                      <option value="Welsh">Welsh</option>
                      <option value="Xhosa">Xhosa</option>
                    </select> 
                  </div>
                </div>
              </div>    
            </div>

            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-2 col-sm-2">Address L1
                  </label> 
                  <div class="col-sm-10 col-md-10">
                    <input type="text" placeholder="Address L1" name="Current_address" class="form-control" > 
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-12 col-md-12">
                <div class="row">
                  <label class="col-form-label col-md-2 col-sm-2">Address L2
                  </label> 
                  <div class="col-sm-10 col-md-10">
                    <input type="text" placeholder="Address L2" name="birth_address1[address_l2]" class="form-control" > 
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">City
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Add City" name="birth_address1[city]" class="form-control" >                 
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">State
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <select class="selectpicker" name="birth_address1[state]" id="state" data-live-search="true">
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Zipcode
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <input type="text" placeholder="Zipcode" name="birth_address1[zipcode]" class="form-control" >                 
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-6">
                <div class="row">
                  <label class="col-form-label col-md-4 col-sm-4">Country
                  </label> 
                  <div class="col-sm-8 col-md-8">
                    <select class="selectpicker" name="birth_address1[country]" id="country" data-live-search="true">
                      <option value="">Select Country</option>
                      @foreach ($countries as $country) 
                      <option value="{{$country->id}}">
                       {{$country->name}}
                     </option>
                     @endforeach
                   </select>               
                  </div>
                </div>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-form-label col-md-2 col-sm-2">Note
              </label> 
              <div class="col-sm-10 col-md-10">
                <textarea name="lead_note" class="summernote-simple form-control" style="height: 150px;"></textarea> 
              </div>
            </div>

            <div class="row doc_repeater form-group">
              <label class="col-form-label col-md-2 col-sm-2">Document
              </label> 
              <div class="col-sm-10 col-md-10">
                <span class="nofile">No file selected</span>
                <ul class="selectedfiles">
                  
                </ul>
              </div>
              <!-- <div class="col-sm-2 col-md-2">
                <a href="#" class="remove_file">
                  x
                </a>
              </div> -->
            </div>

            <div class="row form-group">
              <label class="col-form-label col-md-2 col-sm-2"></label>
              <div class="col-sm-10 col-md-10">
                <a href="#" class="add_file">
                  <i class="fa fa-plus"></i> Upload File
                </a>
              </div>
            </div>

            <div class="row form-group"> 
              <div class="col-sm-12 col-md-12">
                <input type="hidden" id="" name="current_lat" class="form-control" > 
                <input type="hidden" id="" name="current_long" class="form-control" >
                @csrf
                <button class="btn btn-primary" value="1" type="submit" name="create_firm_lead">
                <span>Create</span>
                </button>

                <button class="btn btn-primary" value="1" type="submit" name="create_firm_lead_event">
                <span>Create and Schedule</span>
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
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
function readURL(input, id) {
  var imgname = input.files[0].name.split('.');
  var ext = imgname[imgname.length-1];
  ext = ext.toLowerCase();
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          var src = "{{ asset('assets/images/icon') }}/"+ext+".png";
          if(ext == 'png' || ext == 'jpg' || ext == 'jpeg') {
            src = e.target.result;
          }
          $(document).find('#fileimg_'+id)
              .attr('src', src)
              .width(90)
              .height(90).css('display', 'block');
          var lb = '<label>'+input.files[0].name+'</label>';
          $(document).find('#fileimg_'+id).after(lb);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function(){
  $('.datepicker').val('');
  $('#country').val(230).change();
  setTimeout(function(){
    $('.datepicker').daterangepicker({
      locale: {format: 'MM/DD/YYYY'},
      singleDatePicker: true,
      timePicker: false,
      timePicker24Hour: false,
      maxDate: new Date()
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
            $('#state').selectpicker('refresh');
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
  $('.nofile').hide();
  var n = $('.selectedfiles li').length;
  var li = '<li><input type="file" name="document_path[]" onchange="readURL(this, '+n+');" id="file_'+n+'"/><img src="" id="fileimg_'+n+'" style="display:none;"/><a href="#" class="remove_file">x</a></li>';
  //var doc_repeater = $(this).closest('.row').prev('.doc_repeater').clone();
  $('.selectedfiles').append(li);
  $(document).find('#file_'+n).trigger('click');
});
// $(document).on('chnage', '.selectedfiles li input', function(){
//   var v = $(this).val();

// });
$(document).on('click', '.remove_file', function(e){
  e.preventDefault();
  $(this).closest('li').remove();
});

$('.phone_us').mask('(000) 000-0000');
$('.dob').mask('00/00/0000');

</script>
@endpush 
