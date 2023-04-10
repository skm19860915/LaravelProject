@extends('firmlayouts.admin-master')
@section('title')
Profile
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
    .user-upload-file label {
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        position: relative;
    }   
    .editimg {
        background: #013E41;
        width: 45px;
        border-radius: 50%;
        display: block;
        height: 45px;
        padding: 11px;
        position: absolute;
        bottom: 0;
        right: 0;
    }
    .user-upload-file label .editimg img {
        width: 100%;
    }
    .apps {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        grid-column-gap: 1em;
        grid-row-gap: 1em;
        list-style: none !important;
        padding-left: 15px;
    }
    .apps li {
        position: relative;
    }
    .apps li a { 

    }
    .apps li a span{
        color:#fff; 
        position: absolute; 
        right: 6px; 
        top: 0px;
    }
    .apps li img { 
        width: 100%;
    }
</style>
@endpush 
@section('content')
<?php 
$theme_color = get_user_meta($data->id, 'theme_color'); 
$info_viewable_me = get_user_meta($data->id, 'info_viewable_me'); 
$info_viewable_firm = get_user_meta($data->id, 'info_viewable_firm'); 
$info_viewable_hr = get_user_meta($data->id, 'info_viewable_hr'); 
$usertimezone = get_user_meta($data->id, 'usertimezone'); 
?>
<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <h1>Settings</h1>
        <div class="section-header-breadcrumb">
            @if(Auth::user()->role_id == 4)
                <!-- <a href="{{url('firm/request_to_delete')}}" class="btn btn-primary" style="width: auto; padding: 0px 18px;">Request to delete my account</a> -->
            @endif
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body"> 
                        <div class="profile-new-client">
                            <form action="{{url('updateprofile')}}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
                                <h2>Profile</h2>
                                <div class="row form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4"></label> 
                                            <div class="col-sm-8 col-md-8">
                                                <div class="user-upload-img">
                                                    <div class="user-upload-file">
                                                        <?php
                                                        $img = asset('storage/app') . '/' . $data->avatar;
                                                        if (empty($data->avatar)) {
                                                            $img = '{{ url(' / ') }}/assets/img/avatar/avatar-1.png';
                                                        }
                                                        ?>
                                                        <label style="background-image: url('<?php echo $img; ?>');">
                                                            <input type="file" style="display:none;" name="file" id="upload" />
                                                            <a href="#" class="editimg"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4">Name <!-- <span style="color: red"> *</span> -->
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" placeholder="Name" name="name" class="form-control" required="required" value="{{$data->name}}"> 
                                                <div class="invalid-feedback">Name is required!</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label tcol-md-4 col-sm-4">Email <span style="color: red"> *</span>
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" placeholder="Email" name="email" class="form-control" value="{{$data->email}}" readonly="readonly"> 
                                                <div class="invalid-feedback">Email is required!</div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4">Role <!-- <span style="color: red"> *</span> -->
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" placeholder="Name" name="role" class="form-control" value="{{$roles->name}}" readonly="readonly"> 
                                                <div class="invalid-feedback">Role is required!</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label tcol-md-4 col-sm-4">Contact Number <!-- <span style="color: red"> </span> -->
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" placeholder="Contact Number" name="contact_number" class="form-control phone_no" value="{{$data->contact_number}}"> 
                                                <div class="invalid-feedback">Contact Number is required!</div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4">Timezone <!-- <span style="color: red"> *</span> -->
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <?php getTimeZoneDropdown($usertimezone); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(Auth::user()->role_id == 6)
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4">Select Language <span style="color: red"> </span>
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <a id="google_translate_element"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif 
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <input class="btn btn-primary" type="submit" name="update_user" value="Update"> 
                                    </div>
                                </div>
                                <br>
                                <br>
                                <h2>Password</h2>
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4">Current Password <span style="color: red"> </span>
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="password" placeholder="Current Password" name="password" class="form-control current_password" value=""> 
                                                <div class="invalid-feedback">Current Password is required!</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label tcol-md-4 col-sm-4">New Password <span style="color: red"> </span>
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="password" placeholder="New Password" name="new_password" class="form-control new_password"> 
                                                <div class="invalid-feedback">New Password is required!</div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        @csrf
                                        <input class="btn btn-primary updateuser" type="submit" name="rest_password" value="Reset Password"> 
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label tcol-md-4 col-sm-4">Confirm Password <span style="color: red"> </span>
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="password" placeholder="Confirm Password" name="confirm_password" class="form-control confirm_password"> 
                                                <div class="invalid-feedback">Password is mismatch!</div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <?php 
                                if($firm->account_type == 'VP Services' && false) { ?>
                                <br>
                                <br>
                                <h2>Add other Attorney in your office that will be assigned as Attorney of record</h2>
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4">First Name <span style="color: red"> </span>
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" placeholder="First Name" name="attorney_fname" class="form-control attorney_fname" value=""> 
                                                <div class="invalid-feedback">First Name is required!</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4">Middle Name <span style="color: red"> </span>
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" placeholder="Middle Name" name="attorney_mname" class="form-control attorney_mname" value=""> 
                                                <div class="invalid-feedback">Middle Name is required!</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4">Last Name <span style="color: red"> </span>
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" placeholder="Last Name" name="attorney_lname" class="form-control attorney_lname" value=""> 
                                                <div class="invalid-feedback">Last Name is required!</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        @csrf
                                        <input class="btn btn-primary add_attorney" type="submit" name="add_attorney" value="Add Attorney"> 
                                    </div>
                                </div>
                                <?php } ?>
                                <br>
                                <br>
                                <h2 style="float: none;">Personalizations</h2>
                                <?php if(Auth::user()->role_id == 4) { ?>
                                <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> Theme Logo
                                        </label> 
                                        <div class="col-sm-4 col-md-4">
                                          <input type="file" name="theme_logo" value="" class="form-control"> 
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> Color Theme
                                        </label> 
                                        <div class="col-sm-10 col-md-10">
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
                                </div>

                                <?php if(!empty($card)) { ?>
                                <br>
                                <br>
                                <h2 style="float: none;">Payment Methods</h2>
                                  <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> 
                                        </label> 
                                        <div class="col-sm-8 col-md-8">
                                            <div class="row">
                                            <?php foreach ($card as $k => $v) { ?>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                      <div class="card-body">
                                                        <div class="media">
                                                          <img class="mr-3" src="{{asset('assets/img/visa-in.png')}}" alt="{{$v->brand}}">
                                                          <div class="media-body">
                                                            <h5 class="mt-0">
                                                                ***********{{$v->last4}}
                                                            </h5>
                                                            <p class="mb-0">
                                                                Expires {{$v->exp_month}}/{{$v->exp_year}}
                                                            </p>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      <div class="card-footer text-right" style="border-top: 1px solid #f9f9f9;">
                                                          <a href="{{url('delete_card')}}/{{ base64_encode($v->id) }}" onclick="return confirm('Are you sure you want to delete?')">Remove</a>
                                                          <a href="#" style="margin-left: 40px;" class="editcarddetails" data-card='{{ json_encode($v) }}'>Edit</a>
                                                      </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                                <div class="col-md-6">
                                                    <div class="card">
                                                      <div class="card-body">
                                                        <div class="media text-center">
                                                          <a href="#" class="addcarddetails" style="margin: 0 auto; padding: 55px 0px;">
                                                              <i class="fa fa-plus"></i> Add payment method
                                                          </a>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>
                                         </div>
                                     </div>
                                 </div>

                                 <?php } if($firm->account_type == 'CMS') { ?>
                                <br>
                                <br>
                                <h2 style="float: none;">Integrations</h2>
                                <?php 
                                if(Auth::user()->role_id == 4 || Auth::user()->role_id == 5) { ?>
                                <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> Calendar
                                        </label> 
                                        <div class="col-sm-4 col-md-4">
                                            <?php 
                                            if($firm->account_type == 'CMS') { 
                                                if(empty($access_token)) {
                                                  echo '<a href="'.$authUrl.'" class="google_btn" data-toggle="tooltip"  title="Sync with Google Calendar">
                                                  <img src="'.url('/assets/images/google_logo.png').'">
                                                  </a>';
                                                }
                                                else { ?>
                                                    <a href="{{ url('logout_google') }}" class="btn btn-primary">
                                                        Logout form Google Calendar
                                                    </a>
                                            <?php } } else { ?>
                                                <a href="{{url('firm/upgradetocms')}}" class="google_btn" data-toggle="tooltip"  title="Sync with Google Calendar" style="opacity: 0.5;margin-right: 15px;"> <br>
                                                  <img src="{{url('/assets/images/google_logo.png')}}">
                                                  </a>
                                                  <a href="{{ url('firm/upgradetocms') }}" class="btn btn-primary" style="opacity: 0.5;">
                                                        Upgrade to CMS
                                                    </a>
                                            <?php } ?> 
                                        </div>
                                    </div>
                                </div>
                                <?php } 
                                if(Auth::user()->role_id == 4 && $firm->email == Auth::user()->email) { ?>
                                <br>
                                <br>
                                <h2>QuickBook Integration</h2>
                                <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> 
                                        </label> 
                                        <div class="col-sm-10 col-md-10 table-responsive table-invoice">
                                          <ul class="apps" >
                                                <?php 
                                                $lock='fa-unlock';
                                                $conn='NotConnected';
                                                if($QuickBookUrl['QBConnect']==1){
                                                    $QuickBookUrl['TokenUrl']='#';
                                                    $lock='fa-lock';
                                                    $conn='Connected';
                                                }
                                                if($firm->account_type == 'CMS') {
                                                ?>
                                                <li>
                                                    <a href="<?php echo $QuickBookUrl['TokenUrl']; ?>">
                                                        <span><?php echo $conn; ?> <i class="fa <?php echo $lock; ?>"></i></span><img src="/QuickBook/qb.png">
                                                    </a>
                                                </li>
                                                <?php } else { ?>
                                                    <li>
                                                        <a href="{{url('firm/upgradetocms')}}" style="opacity: 0.5;">
                                                            <span><?php echo $conn; ?> <i class="fa <?php echo $lock; ?>"></i></span><img src="/QuickBook/qb.png">
                                                        </a><br>
                                                        <a href="{{ url('firm/upgradetocms') }}" class="btn btn-primary" style="opacity: 0.5; margin-top: 15px;">
                                                            Upgrade to CMS
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <h2>Lawpay Integration</h2>
                                <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> Operating/Business Account
                                        </label> 
                                        <div class="col-sm-4 col-md-4">
                                            <?php if($firm->account_type == 'CMS') { ?>
                                                <input type="text" name="account_id" value="{!! \get_user_meta($data->id, 'account_id'); !!}" class="form-control">
                                            <?php } else { ?>
                                                <a href="{{ url('firm/upgradetocms') }}" class="btn btn-primary" style="opacity: 0.5;">
                                                    Upgrade to CMS
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> Trust Account
                                        </label> 
                                        <div class="col-sm-4 col-md-4">
                                            <?php if($firm->account_type == 'CMS') { ?>
                                                <input type="text" name="trust_account_id" value="{!! \get_user_meta($data->id, 'trust_account_id'); !!}" class="form-control">
                                            <?php } else { ?>
                                                <a href="{{ url('firm/upgradetocms') }}" class="btn btn-primary" style="opacity: 0.5;">
                                                    Upgrade to CMS
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> E-Check
                                        </label> 
                                        <div class="col-sm-4 col-md-4">
                                            <?php if($firm->account_type == 'CMS') { ?>
                                                <input type="text" name="echeck" value="{!! \get_user_meta($data->id, 'echeck'); !!}" class="form-control">
                                            <?php } else { ?>
                                                <a href="{{ url('firm/upgradetocms') }}" class="btn btn-primary" style="opacity: 0.5;">
                                                    Upgrade to CMS
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> Secret Key
                                        </label> 
                                        <div class="col-sm-4 col-md-4">
                                            <?php if($firm->account_type == 'CMS') { ?>
                                                <input type="text" name="SECRET_KEY" value="{!! \get_user_meta($data->id, 'SECRET_KEY'); !!}" class="form-control">
                                            <?php } else { ?>
                                                <a href="{{ url('firm/upgradetocms') }}" class="btn btn-primary" style="opacity: 0.5;">
                                                    Upgrade to CMS
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php } ?>
                                @if(Auth::user()->role_id == 15)
                                <br>
                                <br>
                                <h2>Privacy Settings</h2>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-form-label col-md-3 col-sm-3">Information viewable only to me
                                        </label> 
                                        <div class="col-sm-1 col-md-1">
                                            <div class="col-auto">
                                              <label class="colorinput">
                                                <input name="info_viewable_me" type="checkbox" value="1" class="colorinput-input" <?php if($info_viewable_me == 1) { echo 'checked="checked"'; } ?>>
                                                <span style="background-color: #013E41;" class="colorinput-color"></span>
                                              </label>
                                            </div>
                                            <!-- <input type="checkbox" name="info_viewable" class="" value="1">  -->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-form-label col-md-3 col-sm-3">Information viewable to Firm Admin
                                        </label> 
                                        <div class="col-sm-1 col-md-1">
                                            <div class="col-auto">
                                              <label class="colorinput">
                                                <input name="info_viewable_firm" type="checkbox" value="1" class="colorinput-input" <?php if($info_viewable_firm == 1) { echo 'checked="checked"'; } ?>>
                                                <span style="background-color: #013E41;" class="colorinput-color"></span>
                                              </label>
                                            </div>
                                            <!-- <input type="checkbox" name="info_viewable" class="" value="1">  -->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-form-label col-md-3 col-sm-3">Information viewable to HR
                                        </label> 
                                        <div class="col-sm-1 col-md-1">
                                            <div class="col-auto">
                                              <label class="colorinput">
                                                <input name="info_viewable_hr" type="checkbox" value="1" class="colorinput-input" <?php if($info_viewable_hr == 1) { echo 'checked="checked"'; } ?>>
                                                <span style="background-color: #013E41;" class="colorinput-color"></span>
                                              </label>
                                            </div>
                                            <!-- <input type="checkbox" name="info_viewable" class="" value="1">  -->
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <input class="btn btn-primary" type="submit" name="update_setting" value="Update"> 
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
<!-- Modal -->
<div id="UpdateCardDetails" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="float: right;
                position: absolute;
                right: 22px;
                top: 15px;
                ">&times;</button>
                <h5 class="modal-title">Payment Methods > Edit Payment Method</h5>
            </div>
            <div class="modal-body">
                <form action="{{ url('firm/case') }}" method="get" id="payment-form" enctype="multipart/form-data"> 
                    <div class="payment-form-card" id="card-element">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="payment-input">
                                    <input type="text" placeholder="Card Number" size="20" data-stripe="number" name="cardnumber" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="payment-input">
                                    <input type="text" placeholder="Expiring Month" data-stripe="exp_month" name="exp_month"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="payment-input">
                                    <input type="text" placeholder="Expiring Year" size="2" data-stripe="exp_year" name="exp_year">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 cvcwrap">
                                <div class="payment-input">
                                    <input type="text" placeholder="CVV Code" size="4" data-stripe="cvc" name="cvc"/>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="payment-input">
                                    <input type="text" placeholder="Postal Code" size="6" data-stripe="address_zip" name="address_zip"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">  
                        <div class="col-md-12 text-right">
                            @csrf
                            <input type="hidden" name="cusid"  value="">
                            <input type="hidden" name="cardid"  value="">
                            <input type="hidden" name="new_card"  value="">
                            <label class="payment-errors text-warning"></label><br>
                            <input type="submit" name="save" value="Save" class="submit btn btn-primary savestripecard"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
    var rk_change = false;
    Stripe.setPublishableKey("{{ env('SRTIPE_PUBLIC_KEY') }}");
    $(document).ready(function () {
        $('.timezonedropdown').selectpicker();
        $('.phone_us').mask('(000) 000-0000');
        $('.updateuser').on('click', function (e) {
            var p = $('.current_password').val();
            var np = $('.new_password').val();
            var cp = $('.confirm_password').val();
            if (p == '') {
                $('.current_password').prop('required', true);
                $('.current_password').next().show();
                e.preventDefault();
            }
            else {
                $('.current_password').next().hide();
            }

            if (np == '') {
                var t = 'New Password is required!';
                $('.new_password').prop('required', true);
                $('.new_password').next().text(t).show();
                e.preventDefault();
            }
            else if(np.length < 6) {
                var t = 'New password must be at least 6 characters!';
                $('.new_password').next().text(t).show();
                e.preventDefault();
            }
            else {
                $('.new_password').next().hide();
            }

            if (np != cp) {
                $('.confirm_password').next().show();
                e.preventDefault();
            }
        });
        $('.editimg').on('click', function (e) {
            e.preventDefault();
            $(this).closest('label').trigger('click');
        });
        $('#upload').change(function () {
            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg"))
            {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.user-upload-file label').css('background-image', 'url(' + e.target.result + ')');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
        $('.add_attorney').on('click', function (e) {
            var f = $('.attorney_fname').val();
            var m = $('.attorney_mname').val();
            var l = $('.attorney_lname').val();
            if(f == '') {
                $('.attorney_fname').next().show();
                e.preventDefault();
            }
            else {
                $('.attorney_fname').next().hide();
            }

            if(m == '') {
                $('.attorney_mname').next().show();
                e.preventDefault();
            }
            else {
                $('.attorney_mname').next().hide();
            }

            if(l == '') {
                $('.attorney_lname').next().show();
                e.preventDefault();
            }
            else {
                $('.attorney_lname').next().hide();
            }
        });
        $('.profile-new-client > form.needs-validation input').click(function(e){
            rk_change = true;
        });
        $('.profile-new-client > form.needs-validation').submit(function(e){
            rk_change = false;
        });
        $('.editcarddetails').on('click', function(e){
          e.preventDefault();
          $('#UpdateCardDetails input[name="cardnumber"]').attr('readonly', true);
          $('.cvcwrap').hide();
          var card = $(this).data('card');
          console.log(card);
          var cardnumber = '***********'+card.last4;
          var exp_month = card.exp_month;
          var exp_year = card.exp_year;
          var cvc = '';
          var address_zip = card.address_zip;
          //var cusid = card.customer;
          var cardid = card.id;

          $('#UpdateCardDetails input[name="cardnumber"]').val(cardnumber);
          $('#UpdateCardDetails input[name="new_card"]').val('0');
          // $('#UpdateCardDetails input[name="cvc"]').hide();
          $('#UpdateCardDetails input[name="exp_month"]').val(exp_month);
          $('#UpdateCardDetails input[name="exp_year"]').val(exp_year);
          $('#UpdateCardDetails input[name="address_zip"]').val(address_zip);
          //$('#UpdateCardDetails input[name="cusid"]').val(cusid);
          $('#UpdateCardDetails input[name="cardid"]').val(cardid);
          $("#UpdateCardDetails").modal('show');
        });


        $('.savestripecard').on('click', function(e){
          e.preventDefault();
          var new_card = $('#UpdateCardDetails input[name="new_card"]').val();
         if(new_card == '1'){
            var $form = $('#payment-form');
            $form.find('.payment-errors').text('');
            tokens = Stripe.card.createToken($form, stripeResponseHandler);            
           
         }else{
            updateCardDetails();
         }

         
        });
        $('.addcarddetails').on('click', function(e){
          e.preventDefault();
         
          $('#UpdateCardDetails input[name="cardnumber"]').attr('readonly', false);
          $('.cvcwrap').show();
          $('#payment-form')[0].reset();
          $('#UpdateCardDetails .payment-errors').text('');
          $('#UpdateCardDetails input[name="new_card"]').val('1');
          $("#UpdateCardDetails").modal('show');
        });
    });
    
    window.addEventListener('beforeunload', (event) => {
        if(rk_change) {
            event.returnValue = 'Would you like to save these changes?';
        }
    });

    function updateCardDetails(){
        var stripe_token = '';
          var exp_month = $('#UpdateCardDetails input[name="exp_month"]').val();
          var exp_year = $('#UpdateCardDetails input[name="exp_year"]').val();
          var address_zip = $('#UpdateCardDetails input[name="address_zip"]').val();
          var cardid = $('#UpdateCardDetails input[name="cardid"]').val();
          var new_card = $('#UpdateCardDetails input[name="new_card"]').val();
          if(new_card == '1'){
            var stripe_token = $('#UpdateCardDetails input[name="stripeToken"]').val();
          }

          var csrf1 = $('input[name="_token"]').val();
          $.ajax({
            type:"post",
            url:"{{ url('update_stripe_card') }}",
            data: {
                _token: csrf1, 
                exp_month: exp_month, 
                exp_year: exp_year, 
                address_zip: address_zip, 
                cardid: cardid,
                new_card: new_card,
                stripe_token: stripe_token
            },
            success:function(res)
            {   
                res = JSON.parse(res);
                console.log(res); 
                alert(res.msg);
                if(res.status) {
                    window.location.href = window.location.href;
                }  
            }
          });

    }

    function stripeResponseHandler(status, response) {
        // Grab the form:
        var $form = $('#payment-form');

        if (response.error) { // Problem!
        // $('.paywith_existing').show();
        // $('.submit-login input').show();
        // $('.payment_proccessing_msg').hide();
        // Show the errors on the form:
        $form.find('.payment-errors').text(response.error.message);
            $form.find('.submit').prop('disabled', false); // Re-enable submission
            return 'error';
        } else { // Token was created!
            // Get the token ID:
            var token = response.id;

            // Insert the token ID into the form so it gets submitted to the server:
            // $form1 = $('#payment-form-res');
            $form.append($('<input type="hidden" name="stripeToken">').val(token));
            updateCardDetails(); 
            // Submit the form:
           // $form.get(0).submit();
           return 'success';
        }
    };
</script>
@endpush 