@extends('layouts.admin-master')
@section('title')
Profile
@endsection
<?php 
$theme_color = get_user_meta($data->id, 'theme_color'); 
?>
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
$usertimezone = get_user_meta($data->id, 'usertimezone'); 
?>
<section data-dashboard="1" class="section dashboard-new-design">
	<div class="section-header">
		<h1>Profile</h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item">

			</div>
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
											<div class="col-md-8 col-sm-8">
												<div class="user-upload-img">
													<div class="user-upload-file">
														<?php 
															$img = asset('storage/app').'/'.$data->avatar;
															if(empty($data->avatar)) { 
																$img = '{{ url('/') }}/assets/img/avatar/avatar-1.png';
															}
														?>
														<label style="background-image: url('<?php echo $img; ?>');">
															<input type="file" style="display:none;" name="file" id="upload"/>
															
															<!-- <img src="{{$img}}"> -->
															<!-- <br /> -->
															<!-- <span>Upload Profile Image</span> -->
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
											<label class="col-form-label col-md-4 col-sm-4">Name <span style="color: red"> *</span>
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
											<label class="col-form-label col-md-4 col-sm-4">Role <span style="color: red"> *</span>
											</label> 
											<div class="col-sm-8 col-md-8">
												<input type="text" placeholder="Name" name="role" class="form-control" value="{{$roles->name}}" readonly="readonly"> 
												<div class="invalid-feedback">Role is required!</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 col-md-6">
										<div class="row">
											<label class="col-form-label tcol-md-4 col-sm-4">Contact Number <span style="color: red"> </span>
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
                                    @if(Auth::user()->role_id == 2)
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4">Date of Birth <!-- <span style="color: red"> *</span> -->
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" name="dob" value="<?php if(!empty(get_user_meta($data->id, 'dob'))) { echo get_user_meta($data->id, 'dob'); } else { echo date('m/d/Y'); } ?>" class="form-control dob" placeholder="MM/DD/YYYY">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @endif
                                </div>
                                @if(Auth::user()->role_id == 2)
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="row">
                                            <label class="col-form-label col-md-4 col-sm-4">Mailing Address <!-- <span style="color: red"> *</span> -->
                                            </label> 
                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" name="mailing_address" value="{!! \get_user_meta($data->id, 'mailing_address'); !!}" class="form-control" placeholder="Enter Mailing Address">
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
                                @if(Auth::user()->role_id == 2)
                                <br>
                                <br>
                                <h2 style="float: none;">Integrations</h2>
                                <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> Calendar
                                        </label> 
                                        <div class="col-sm-4 col-md-4">
                                            <?php 
                                            if(empty($access_token)) {
                                              echo '<a href="'.$authUrl.'" class="google_btn" data-toggle="tooltip"  title="Sync with Google Calendar">
                                              <img src="'.url('/assets/images/google_logo.png').'">
                                              </a>';
                                            }
                                            else { ?>
                                                <a href="{{ url('logout_google') }}" class="btn btn-primary">
                                                    Logout form Google Calendar
                                                </a>
                                            <?php } ?> 
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> Theme Logo
                                        </label> 
                                        <div class="col-sm-4 col-md-4">
                                          <input type="file" name="theme_logo" value="" class="form-control"> 
                                        </div>
                                    </div>
                                </div> -->
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
                                @endif
                                <?php if(Auth::user()->role_id == 1) { ?>
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
                                                ?>
                                                <li>
                                                    <a href="<?php echo $QuickBookUrl['TokenUrl']; ?>">
                                                        <span><?php echo $conn; ?> <i class="fa <?php echo $lock; ?>"></i></span><img src="/QuickBook/qb.png">
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <h2>Annual Pricing Discount</h2>
                                <div class="form-group">
                                    <div class="row mb-4">
                                        <label class="col-form-label col-md-2 col-sm-2"> 
                                            Annual Pricing Discount
                                        </label> 
                                        <div class="col-sm-4 col-md-4 table-responsive table-invoice">
                                          <input type="number" placeholder="Enter Amount" name="annual_amount" class="form-control" value="{!! \get_user_meta($data->id, 'annual_amount'); !!}" min="0">
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <input class="btn btn-primary" type="submit" name="vp_update_setting" value="Update"> 
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
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
    var rk_change = false;
	$(document).ready(function(){
		$('.timezonedropdown').selectpicker();
		$('.phone_us').mask('(000) 000-0000');
        $('.dob').mask('00/00/0000');
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
		$('.editimg').on('click', function(e){
			e.preventDefault();
			$(this).closest('label').trigger('click');
		});
		$('#upload').change(function(){
		    var input = this;
		    var url = $(this).val();
		    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
		    if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) 
		     {
		        var reader = new FileReader();

		        reader.onload = function (e) {
		           $('.user-upload-file label').css('background-image', 'url('+e.target.result+')');
		        }
		       reader.readAsDataURL(input.files[0]);
		    }
		});
        $('.profile-new-client > form.needs-validation input').click(function(e){
            rk_change = true;
        });
        $('.profile-new-client > form.needs-validation').submit(function(e){
            rk_change = false;
        });
	});
    window.addEventListener('beforeunload', (event) => {
        if(rk_change) {
            event.returnValue = 'Would you like to save these changes?';
        }
    });
</script>
@endpush 