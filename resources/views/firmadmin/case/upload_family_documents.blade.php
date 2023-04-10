@extends('firmlayouts.admin-master')

@section('title')
Clients
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
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
<section class="section client-listing-details task-new-header-document uploadducument-new">
<!--new-header open-->
  @include('firmadmin.case.case_header')
<!--new-header Close-->
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
           <form action="{{url('firm/case/setFamilyDocument4')}}" method="post" enctype="multipart/form-data" class="dropzone1" id="mydropzone1">
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
                <input type="hidden" name="fid" class="fid" value="{{$fid}}">
                <input type="hidden" name="case_id"  value="{{$id}}">
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
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
function readURL(input) {
  console.log(input,'==============',input.files.length)
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
    // e.preventDefault();
    var input = $(this);
    console.log(input,'==============',input.files)
    var n = $('.selectedfiles li').length;
    
    // $('.selectedfiles').append(li);
    // $(document).find('#file_'+n).trigger('click');
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
