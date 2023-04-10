<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; TILA Case Prep</title>
  <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" sizes="32x32" />
  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- Template CSS -->
  <!-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"> -->
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}?v=<?php echo rand() ?>">
   <link rel="stylesheet" href="{{ asset('assets/css/CustomLoad.css')}}?v=<?php echo rand(); ?>">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="d-flex flex-wrap align-items-stretch">
        <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
          <div class="p-4 m-3">
            <img src="{{ asset('assets/img/tila-logo.svg') }}" alt="logo" width="200" class="mb-5 mt-2" >
            @if(session()->has('info'))
            <div class="alert alert-primary">
                {{ session()->get('info') }}
            </div>
            @endif
            @if(session()->has('status'))
            <div class="alert alert-info">
                {{ session()->get('status') }}
            </div>
            @endif
            @yield('content')
            <div class="text-center mt-5 text-small">
              Copyright {{ date('Y') }} &copy; {{ config('app.name') }}
              <div class="mt-2">
                <a href="https://tilacaseprep.com/privacy-policy/" class="privacy_policy_btn" target="_blank">Privacy Policy</a>
                <div class="bullet"></div>
                <a href="https://tilacaseprep.com/terms-of-service/" class="terms_serveices_btn" target="_blank">Terms of Service</a>
              </div>
              
            </div>
          </div>
        </div>
        <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom" data-background="{{ asset('assets/img/unsplash/login-bg.jpg') }}">
          <div class="absolute-bottom-left index-2">
            <div class="text-light p-5 pb-2">
              <div class="mb-5 pb-3">
                <h1 class="mb-2 display-4 font-weight-bold" id="greeting">Good Morning</h1>
                <h5 class="font-weight-normal text-muted-transparent">Bali, Indonesia</h5>
              </div>
              Photo by <a class="text-light bb" target="_blank" href="https://unsplash.com/photos/a8lTjWJJgLA">Justin Kauffman</a> on <a class="text-light bb" target="_blank" href="https://unsplash.com">Unsplash</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- Modal -->
  <div id="Privacy_Policy_Wrapper" class="modal fade" role="dialog">
    <div class="modal-dialog" style="max-width: 70%;">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="float: right;
          position: absolute;
          right: 22px;
          top: 15px;
          ">&times;</button>
          <!-- <h4 class="modal-title">Privacy Policy</h4> -->
        </div>
        <div class="modal-body">
          <div id="policy" width="640" height="480" data-policy-key="Y0dNMVNIaFRXVE5pTlhWUFpXYzlQUT09" data-extra="table-style=accordion" ></div>
        </div>
      </div>
    </div>
  </div>
  <div id="Terms_of_Service_Wrapper" class="modal fade" role="dialog">
    <div class="modal-dialog" style="max-width: 70%;">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="float: right;
          position: absolute;
          right: 22px;
          top: 15px;
          ">&times;</button>
          <!-- <h4 class="modal-title">Privacy Policy</h4> -->
        </div>
        <div class="modal-body">
          <div id="policy" width="640" height="480" data-policy-key="VjJSWVJWaHFPVVJIU2pGc1RVRTlQUT09" data-extra="table-style=accordion"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- <script src="https://app.termageddon.com/js/termageddon.js"></script> -->
  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="{{ asset('assets/js/stisla.js') }}"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <script src="{{ asset('assets/js/custom.js') }}"></script>

  <!-- Page Specific JS File -->
  @stack('footer_script')
  <script type="text/javascript">
    $(document).ready(function(){
      // $('.privacy_policy_btn').on('click', function(e){
      //   e.preventDefault();
      //   $('#Privacy_Policy_Wrapper').modal('show');
      // });
      // $('.terms_serveices_btn').on('click', function(e){
      //   e.preventDefault();
      //   $('#Terms_of_Service_Wrapper').modal('show');
      // });
    })
  </script>
</body>
</html>
