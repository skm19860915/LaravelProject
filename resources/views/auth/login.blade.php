@extends('layouts.auth-master')

@section('content')

  <h4 class="text-dark font-weight-normal">Welcome to <span class="font-weight-bold">TILA Case Prep</span></h4>
  <p class="text-muted">Before you get started, you must login.</p>

  <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
      @csrf
    <div class="form-group">
      <label for="email">Email</label>
      <input aria-describedby="emailHelpBlock" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Registered email address" tabindex="1" value="{{ old('email') }}" required>
      <div class="invalid-feedback">
        {{ $errors->first('email') }}
      </div>
      @if(App::environment('demo'))
      <small id="emailHelpBlock" class="form-text text-muted">
          Demo Email: admin@example.com
      </small>
      @endif
    </div>

    <div class="form-group password_field viewpasswordpadding">
      <div class="d-block">
          <label for="password" class="control-label">Password</label>
      </div>
      <input aria-describedby="passwordHelpBlock" id="password" type="password" placeholder="Your account password" class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}" name="password" tabindex="2" required>
      <!-- <meter max="4" id="password-strength-meter"></meter>
      <p id="password-strength-text"></p> -->
      <div class="invalid-feedback">
        {{ $errors->first('password') }} 
      </div>
      @if(App::environment('demo'))
      <small id="passwordHelpBlock" class="form-text text-muted">
          Demo Password: 1234
      </small>
      @endif
    </div>

    <div class="form-group">
      <div class="custom-control custom-checkbox">
        <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember"{{ old('remember') ? ' checked': '' }}>
        <label class="custom-control-label" for="remember">Remember Me</label>
      </div>
    </div>

    <div class="form-group text-right">
      <a href="{{ route('password.request') }}" class="float-left mt-3">
        Forgot Password?
      </a>
      <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
        Login
      </button>
    </div>
  </form>





<style type="text/css">
meter {
    /* Reset the default appearance */
    -webkit-appearance: none;
       -moz-appearance: none;
            appearance: none;
            
    margin: 0 auto 1em;
    width: 100%;
    height: .5em;
    
    /* Applicable only to Firefox */
    background: none;
    background-color: rgba(0,0,0,0.1);
}

meter::-webkit-meter-bar {
    background: none;
    background-color: rgba(0,0,0,0.1);
}

meter[value="1"]::-webkit-meter-optimum-value { background: red; }
meter[value="2"]::-webkit-meter-optimum-value { background: yellow; }
meter[value="3"]::-webkit-meter-optimum-value { background: orange; }
meter[value="4"]::-webkit-meter-optimum-value { background: green; }

meter[value="1"]::-moz-meter-bar { background: red; }
meter[value="2"]::-moz-meter-bar { background: yellow; }
meter[value="3"]::-moz-meter-bar { background: orange; }
meter[value="4"]::-moz-meter-bar { background: green; }

.feedback {
    color: #9ab;
    font-size: 90%;
    padding: 0 .25em;
    font-family: Courgette, cursive;
    margin-top: 1em;
}  
</style>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
<script type="text/javascript">
 var strength = {
        0: "Worst ☹",
        1: "Bad ☹",
        2: "Weak ☹",
        3: "Good ☺",
        4: "Strong ☻"
}

var password = document.getElementById('password');
var meter = document.getElementById('password-strength-meter');
var text = document.getElementById('password-strength-text');

password.addEventListener('input', function()
{
    var val = password.value;
    var result = zxcvbn(val);
    
    // Update the password strength meter
    meter.value = result.score;
   
    // Update the text indicator
    if(val !== "") {
        text.innerHTML = "Strength: " + "<strong>" + strength[result.score] + "</strong>"; 
    }
    else {
        text.innerHTML = "";
    }
}); 
</script> -->
@endsection