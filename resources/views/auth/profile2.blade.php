@extends('firmlayouts.client-family')
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
								<h2>Profile Settings</h2>
								<div class="row form-group">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<div class="user-upload-img">
											<div class="user-upload-file">
												<?php 
													$img = asset('storage/app').'/'.$data->avatar;
													if(empty($data->avatar)) { 
														$img = '{{ url('/') }}/assets/img/avatar/avatar-1.png';
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
                                </div>
								<div class="row form-group">
                                    <div class="col-sm-6 col-md-6">
                                        <input class="btn btn-primary" type="submit" name="update_user" value="Update"> 
                                    </div>
                                </div>
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
<script src="{{ url('assets/js/jquery.mask.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
	var rk_change = false;
	$(document).ready(function(){
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
	});
	window.addEventListener('beforeunload', (event) => {
        if(rk_change) {
            event.returnValue = 'Would you like to save these changes?';
        }
    });
</script>
@endpush 