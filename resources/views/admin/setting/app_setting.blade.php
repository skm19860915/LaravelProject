@extends('layouts.admin-master')

@section('title')
App Setting
@endsection
@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>App Setting</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <form action="{{ url('admin/setting/app_setting_update') }}" method="post" class="needs-validation" enctype="multipart/form-data">
          <!-- google calendar api settings -->
          <div class="card">
            <div class="card-header">
              <h4>Google Calendar Api Settings</h4>
            </div>
            <div class="card-body">
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Client ID
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="usermeta[CLIENT_ID]" value="{!! \get_user_meta($id, 'CLIENT_ID'); !!}" class="form-control"> 
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Client Secret
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="usermeta[CLIENT_SECRET]" value="{!! \get_user_meta($id, 'CLIENT_SECRET'); !!}" class="form-control"> 
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Client Redirect Url
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="usermeta[CLIENT_REDIRECT_URL]" value="{{ url('/') }}/firm/lead" class="form-control" readonly="readonly">  
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                </label> 
                <div class="col-sm-12 col-md-7">
                  <button class="btn btn-primary" type="submit" name="create_firm_user">
                    <span>Save</span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- twilio chat api settings -->
          <div class="card">
            <div class="card-header">
              <h4>Twilio Chat Api Settings</h4>
            </div>
            <div class="card-body">
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Twilio Auth SID
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="usermeta[TWILIO_AUTH_SID]" value="{!! \get_user_meta($id, 'TWILIO_AUTH_SID'); !!}" class="form-control"> 
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Twilio Auth Token
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="usermeta[TWILIO_AUTH_TOKEN]" value="{!! \get_user_meta($id, 'TWILIO_AUTH_TOKEN'); !!}" class="form-control"> 
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Twilio Service SID
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="usermeta[TWILIO_SERVICE_SID]" value="{!! \get_user_meta($id, 'TWILIO_SERVICE_SID'); !!}" class="form-control"> 
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Twilio Api Secret
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="usermeta[TWILIO_API_SECRET]" value="{!! \get_user_meta($id, 'TWILIO_API_SECRET'); !!}" class="form-control"> 
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Twilio Api SID
                </label> 
                <div class="col-sm-12 col-md-7">
                  <input type="text" name="usermeta[TWILIO_API_SID]" value="{!! \get_user_meta($id, 'TWILIO_API_SID'); !!}" class="form-control"> 
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                </label> 
                <div class="col-sm-12 col-md-7">
                  <button class="btn btn-primary" type="submit" name="create_firm_user">
                    <span>Save</span>
                  </button>
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

@endpush 
