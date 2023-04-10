@extends('layouts.admin-master')

@section('title')
First Reset Password
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>First Reset Password</h1>
    <div class="section-header-breadcrumb">
          
    </div>
  </div>
  <div class="section-body">
   
  		<div class="row">
  			<div class="col-md-12">
  				<div class="card">
        <form action="{{ url('firm/update_first_client_password') }}" method="post" class="needs-validation" novalidate="">
          <div class="card-header">
            <h4></h4>
          </div>
          <div class="card-body">

          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">New Password <span style="color: red"> * </span>
          		</label> 
          		<div class="col-sm-12 col-md-7 password_field">
          			<input type="password" id="password" placeholder="Set New Password" name="password" class="form-control" required="" /> 
          			<div class="invalid-feedback">Password is required!</div>
          		</div>
          	</div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Confirm Password <span style="color: red"> * </span>
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="password" placeholder="Confirm Password" name="conform_password" class="form-control" required="" /> 
                <div class="invalid-feedback">Confirm Password is required!</div>
              </div>
            </div> 
          	
          	<div class="form-group row mb-4">
          		<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
          		</label> 
          		<div class="col-sm-12 col-md-7">
          			@csrf
          			<button class="btn btn-primary" id="submit" type="submit" name="first_reset_password">
          				<span>Reset Password</span>
          			</button>
                <i>
                  <small>
                    <h6 style="margin-top: 15px;">Password must contain the following:</h6>
                    <ul style="padding: 0;">
                      <li style="list-style-type: none;">
                        <ul style="padding-left: 10px;">
                          <li id="eightreq">At least eight characters</li>
                          <li id="threeoffour">At least three of the four:
                            <ul>
                              <li id="lcasereq">At least one lowercase character (a-z)</li>
                              <li id="ucasereq">At least one uppercase character (A-Z)</li>
                              <li id="numreq">At least one digit (0-9)</li>
                              <li id="charreq">At least one symbol (? . , ! _ - ~ $ % + =)</li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </small>
                </i>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojione/2.2.7/lib/js/emojione.min.js"></script>
<script src="{{ asset('assets/js/password.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  // emojis 😁! See #password5 for more details
  emojione.imageType = 'svg';
  emojione.sprints = true;
  emojione.imagePathSVGSprites = 'https://github.com/Ranks/emojione/raw/master/assets/sprites/emojione.sprites.svg';
  $('#password').password({
    animate: false,
    minimumLength: 6,
    enterPass: emojione.unicodeToImage('Type your password'),
    shortPass: emojione.unicodeToImage('You can do it better, dude! 🤕'),
    badPass: emojione.unicodeToImage('Still needs improvement! 😷'),
    goodPass: emojione.unicodeToImage('Yeah! That\'s better, but not there yet. Try again! 👍'),
    strongPass: emojione.unicodeToImage('Yup, you made it 🙃'),
  }).on('password.score', function (e, score) {
    if (score > 75) {
      $('#submit').removeAttr('disabled');
    } else {
      $('#submit').attr('disabled', true);
    }
  });
});
</script>
@endpush