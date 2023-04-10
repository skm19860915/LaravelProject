@extends('layouts.auth-master')

@section('content')
<div class="card card-primary">
  <div class="card-header"><h4>Set a New Password</h4></div>

  <div class="card-body">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" tabindex="1" value="{{ old('email') }}" autofocus>
        <div class="invalid-feedback">
          {{ $errors->first('email') }}
        </div>
      </div>
      <div class="form-group password_field viewpasswordpadding">
        <label for="password" class="control-label">Password</label>
        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}" name="password" tabindex="2">
        <div class="invalid-feedback">
          {{ $errors->first('password') }}
        </div>
      </div>
      <div class="form-group">
        <label for="password_confirmation" class="control-label">Confirm Password</label>
        <input id="password_confirmation" type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid': '' }}" name="password_confirmation" tabindex="2">
        <div class="invalid-feedback">
          {{ $errors->first('password_confirmation') }}
        </div>
      </div>
      <div class="form-group">
        <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
          Set a New Password
        </button>
      </div>
    </form>
  </div>
</div>
<div class="mt-5 text-muted text-center">
  Recalled your login info? <a href="{{ route('login') }}">Sign In</a>
</div>

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
    enterPass: emojione.unicodeToImage('Type your password 🔜'),
    shortPass: emojione.unicodeToImage('You can do it better, dude! 🤕'),
    badPass: emojione.unicodeToImage('Still needs improvement! 😷'),
    goodPass: emojione.unicodeToImage('Yeah! That\'s better! 👍'),
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