@extends('layouts.admin-master')

@section('title')
View client
@endsection
@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
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
  margin-right: 15px;
}
.selectedfiles li input {
  display: none;
}
.selectedfiles li img {
    width: 90px;
    height: 90px;
    margin: 0 auto;
    display: block;
}
.selectedfiles li label {
    display: block;
    width: 210px;
    text-align: center;
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
</style>
@endpush 
@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('admin.adminuser.userclient.client_header') 
<!--new-header Close-->
  
  <div class="section-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/client') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
           
            <div class="profile-new-client">
              
              <h2 style="float: none;">Upload Documents</h2>
             <form action="{{url('admin/userclient/upload_req_doc')}}" method="post" enctype="multipart/form-data" class="dropzone1" id="mydropzone1">
              <div class="row">
                <div class="col-md-6 fallback1">
                <label class="borwse-btn-box">
                  <input name="file[]" type="file" class="fileupload form-control" required="required" onchange="readURL(this);" />
                 <span class="border-btn-browse"><span>Browse</span></span>
                </label>
                </div>
                <div class="col-md-12">
                  <ul class="selectedfiles">
                    
                  </ul>
                </div>
              </div>
              <br>
              <div class="row">  
                <div class="col-md-12 text-left upload-btn-save">
                  @csrf
                  <input type="hidden" name="id" class="uploaddoc_id" value="">
                  <input type="hidden" name="case_id"  value="{{$case->id}}">
                  <input type="hidden" name="family_id"  value="{{$requested_doc->family_id}}">
                  <input type="submit" name="save" value="Save" class="btn btn-primary"/>
                </div>
              </div>
            </form>
                      
            </div>
          </div>
      </div>
    </div>
  </div>
</section>
<select class="requesteddoc" style="display: none;">
  <option value="">Select One</option>
<?php 
if(!empty($docs)) {
  foreach ($docs as $key => $doc) {
    $document_type = $doc->document_type;
    echo '<option value="'.$document_type.'">'.$document_type.'</option>';
  }
} 
?>
</select>
@endsection
@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
function readURL(input) {
  for (var i = 0; i < input.files.length; i++) {
    var imgname = input.files[i].name.split('.');
    var ext = imgname[imgname.length-1];
    var opt = $('.requesteddoc').html();
    var src =  "{{ asset('assets/images/icon') }}/"+ext+".png";
    var li = '<li><img src="'+src+'"/>';
      li += '<input name="filename[]" value="'+input.files[i].name+'" type="hidden"/>';
      li += '<label>'+input.files[i].name+'</label>';
      li += '<select class="rselectpicer" name="filetype[]" required multiple>'+opt+'</select>';
      li += '<a href="#" class="remove_file">x</a></li>';
    // var li = '<input type="file" name="rkfile[]" value="'+input.files[i]+'" />';
    $('.selectedfiles').append(li);
    $('.rselectpicer').selectpicker();
    
  }
  
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            // $(document).find('#fileimg_')
            //     .attr('src', "{{ asset('assets/images/icon') }}/"+ext+".png")
            //     .width(90)
            //     .height(90).css('display', 'block');
        };

        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function(){
  $('.fileupload1').on('change', function(e){
    var input = $(this);
    console.log(input,'==============',input.files)
    var n = $('.selectedfiles li').length;
  });
});
$(document).on('click', '.remove_file', function(e){
  e.preventDefault();
  
  $(this).closest('li').remove();
});
</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 