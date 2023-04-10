@extends('firmlayouts.admin-master')

@section('title')
Firm Calendar Setting
@endsection

@push('header_styles')
<link  href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
<style type="text/css">
.modalformpart .row {
  margin-bottom: 5px;
}
.modalformpart .colorpickerinput {
    padding: 5px 10px !important;
    height: auto !important;
    color: #fff;
}
.dropdown.bootstrap-select {
  width: 160px !important;
}
</style>
@endpush 

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Firm Calendar Setting</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        
          <div class="card-header">
            <a href="{{ url('firm/calendar') }}" style="margin-right: 20px;"><i class="fas fa-long-arrow-alt-left"></i></a>
            <h4></h4>
            <div class="card-header-action">
              
            </div>
          </div>
          <div class="card-body">
            <div class="modalformpart" id="modal-form-part">
              <form action="{{ url('firm/setting/calendar_setting') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
              @if($users->isEmpty())  

              @else
              <div class="row">
                <div class="col-md-2">
                  <select class="selectpicker" name="user1" data-live-search="true">
                    <option value="">Select One</option>
                    @foreach ($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2">
                  <input type="text" placeholder="Select Color" name="setting1" class="form-control colorpickerinput" value="" style="">
                </div>
              </div>
              @foreach ($users as $user)
              
              <div class="row">  
                <div class="col-md-2">
                  <label>{{$user->name}}</label>
                </div>
                <div class="col-md-2">
                  <input type="text" placeholder="Select Color" name="setting[]" data-key="{{$user->id}}" class="form-control colorpickerinput" value="{{$user->value}}" style="background: {{$user->value}};">
                </div>
              </div>
              @endforeach
              @endif
              <div class="row">  
                <div class="col-md-12">
                  <input type="hidden" name="lead_id" value="" >  
                  @csrf
                  <input type="button" name="save" value="Save" class="btn btn-primary saveclientinfo_form"/>
                </div>
              </div>
              </form>
            </div>
          </div>
        
      </div>
        </div>
      </div>
  </div>
</section>


@endsection

@push('footer_script')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script>
  
  $(document).ready(function(){
    $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var inps = document.getElementsByName('setting[]');
      var s = {};
      for (var i = 0; i <inps.length; i++) {
        var inp=inps[i];
        var key = inp.getAttribute('data-key');
        s[key] = inp.value;
      }
      var u1 = $('select[name="user1"]').val();
      if(u1 != '') {
        s[u1] = $('input[name="setting1"]').val();
      }
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/setting/calendar_setting') }}",
        data: { setting: s, _token:_token },
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = window.location.href;
          }
          else {
            alert('Mendatory fields are required!')
          }
        }
      });
    });
    setTimeout(function(){
      $(".colorpickerinput").colorpicker({
        format: 'hex',
        component: '.input-group-append',
      });
    },1000);
    $('.colorpickerinput').on('change', function(){
      var v = $(this).val();
      $(this).css('background', v);
    });
  });

</script>
@endpush