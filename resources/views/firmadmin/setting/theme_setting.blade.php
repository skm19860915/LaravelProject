@extends('firmlayouts.admin-master')

@section('title')
Theme Settings
@endsection

@push('header_styles')
<link  href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
<style type="text/css">
  .colorpickerinput {
      color: #fff !important;
  }
</style>
@endpush 
@section('content')
<section class="section">
  <div class="section-header">
    <h1>Theme Settings</h1>
    <div class="section-header-breadcrumb">

    </div>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-md-12">
        <form action="{{ url('firm/setting/update_theme_setting') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          

          <div class="card">
            <div class="card-header">
              <!-- <h4>Lawpay Api Settings</h4> -->
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-2">
                  <?php
                  $data = Auth::User();
                  $firm = DB::table('firms')->where('id', $data->firm_id)->first();
                  $firmadmin = DB::table('users')->where('firm_id', $data->firm_id)->where('role_id', 4)->first();
                  $theme_logo = get_user_meta($firmadmin->id, 'theme_logo');
                  $theme_color = get_user_meta($firmadmin->id, 'theme_color');
                  if(!empty($theme_logo) && $theme_logo != '[]') { ?>
                      <img src="{{asset('storage/app')}}/{{$theme_logo}}" alt="logo" width="100" class="mb-5 mt-2" >
                  <?php } else { ?>
                      <img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="100" class="mb-5 mt-2" >
                  <?php } ?>
                </div>
                <div class="col-md-8">
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-12 col-lg-12"> Firm Name
                    </label> 
                    <div class="col-sm-12 col-md-12">
                      {{$firm->firm_name}}
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-12 col-lg-12"> Firm Email
                    </label> 
                    <div class="col-sm-12 col-md-12">
                      {{$firm->email}}
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-12 col-lg-12"> Account Type
                    </label> 
                    <div class="col-sm-12 col-md-12">
                      {{$firm->account_type}}
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-12 col-lg-12"> Theme Logo
                    </label> 
                    <div class="col-sm-12 col-md-12">
                      <input type="file" name="theme_logo" value="{!! \get_user_meta($id, 'theme_logo'); !!}" class="form-control"> 
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-12 col-lg-12"> Theme
                    </label> 
                    <div class="col-sm-12 col-md-12">
                      <div class="row gutters-xs">
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="" class="colorinput-input" <?php if(empty($theme_color) || $theme_color == '[]') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #013E41;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme1" class="colorinput-input" <?php if($theme_color == 'theme1') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #ff9c00;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme2" class="colorinput-input" <?php if($theme_color == 'theme2') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #ff2f00;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme3" class="colorinput-input" <?php if($theme_color == 'theme3') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #ff0000;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme4" class="colorinput-input" <?php if($theme_color == 'theme4') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #ff0063;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme5" class="colorinput-input" <?php if($theme_color == 'theme5') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #ff00fc;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme6" class="colorinput-input" <?php if($theme_color == 'theme6') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #4a59e4;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme7" class="colorinput-input" <?php if($theme_color == 'theme7') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #6771ff;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme8" class="colorinput-input" <?php if($theme_color == 'theme8') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #00b3ff;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <!-- <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme9" class="colorinput-input" <?php if($theme_color == 'theme9') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #00cce3;" class="colorinput-color"></span>
                          </label>
                        </div> -->
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme10" class="colorinput-input" <?php if($theme_color == 'theme10') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #00a794;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <!-- <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme11" class="colorinput-input" <?php if($theme_color == 'theme11') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #00c555;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme12" class="colorinput-input" <?php if($theme_color == 'theme12') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #00b02c;" class="colorinput-color"></span>
                          </label>
                        </div> -->
                        <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme13" class="colorinput-input" <?php if($theme_color == 'theme13') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #767280;" class="colorinput-color"></span>
                          </label>
                        </div>
                        <!-- <div class="col-auto">
                          <label class="colorinput">
                            <input name="theme_color" type="radio" value="theme14" class="colorinput-input" <?php if($theme_color == 'theme14') { echo 'checked="checked"'; } ?>>
                            <span style="background-color: #202020;" class="colorinput-color"></span>
                          </label>
                        </div> -->
                      </div>
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-12 col-lg-12">
                    </label> 
                    <div class="col-sm-12 col-md-12">
                      <button class="btn btn-primary" type="submit" name="create_firm_user">
                        <span>Save</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
                  
            </div>
          </div>

          @csrf
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
@push('footer_script')
<script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
<script>
  
  $(document).ready(function(){
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