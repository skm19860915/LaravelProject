@extends('layouts.admin-master')

@section('title')
Client Questionnaire Form
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Client Questionnaire Form</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Client Questionnaire Form</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <h4 class="theme_text">Information Form</h4>
                <div class="card-header-action">
              <!-- <a href="{{ url('firm/forms/addform') }}" class="btn btn-primary">Add <i class="fas fa-plus"></i></a> -->
            </div>
            </div>
            <div class="card-body p-0">
                <!-- <button id="authorize_button" style="display: none;">Authorize</button>
                <button id="signout_button" style="display: none;">Sign Out</button>

                <pre id="content" style="white-space: pre-wrap;"></pre> -->

    
                <div class="table-responsive table-invoice">
                     <table class="table table-striped">
                        <tbody><tr>
                            <th>Form Type</th>
                            <th>Client Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        @if ($client_information_forms->isEmpty())

                        @else
                        @foreach ($client_information_forms as $form)
                        <tr>
                            <td style="text-transform: capitalize;">
                              <?php echo str_replace('_', ' ', $form->file_type); ?>
                            </td>
                            <td>
                              {{$form->first_name.' '.$form->middle_name.' '.$form->last_name}}
                            </td>
                            <td class="font-weight-600">
                                @if($form->status1 == 0)
                                    Incomplete
                                @elseif($form->status1 == 1)
                                    Complete
                                @endif

                            </td>
                            <td>
                                <?php 
                                $rform = $form; 
                                unset($rform->birth_address);
                                unset($rform->client_aliases);
                                ?>
                                <?php  if($form->VP_Assistance == 1) { ?>
                                @if($form->status1 == 1)
                                    <a href="#form_editor" class="btn btn-primary document_file" data-file="{{asset('storage/app')}}/{{$form->file}}" data-id="{{$form->info_id}}" onclick="load_document(this, '{{$form->information}}', '{{$rform}}')">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endif
                                @if($form->status1 == 0)
                                <?php 
                                unset($rform->information);
                                unset($rform->residence_address);
                                ?>
                                <a href="#form_editor" class="btn btn-primary document_file" data-file="{{asset('storage/app')}}/{{$form->file}}" data-id="{{$form->info_id}}" onclick="load_document(this, null, '{{$rform}}')">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endif
                              <?php } ?>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                @csrf
                </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</section>
<div id="form_editor" style="display: none;"></div>
@endsection
@push('footer_script')
<style type="text/css">
      #form_editor {
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 9999999999;
      }
    </style>
<!-- <link rel="stylesheet" href="{{ asset('assets/pspdf/pwa-example/src/index.css')}}" />
<script src="{{ asset('assets/pspdf/pwa-example/vendor/pspdfkit.js')}}"></script>
<script src="{{ asset('assets/pspdf/pwa-example/src/app.js')}}"></script> -->
<script src="{{ asset('assets/WebViewer/lib/webviewer.min.js')}}"></script>
<script src="{{ asset('assets/WebViewer/samples/old-browser-checker.js')}}"></script>
<script src="{{ asset('assets/WebViewer/samples/forms/form-fields/form-fields1.js')}}"></script>
<script type="text/javascript">
  function load_document(e, pdf_data, cdata) {
    window.cdata = cdata;
    window.PDFDATA = pdf_data;
    window.pdfUrl = e.getAttribute('data-file');
    window.id = e.getAttribute('data-id');
    window.token = document.querySelector('input[name="_token"]').value;
    document.getElementById('form_editor').style.display = 'block';
    WebViewer.getInstance().loadDocument(pdfUrl);   
  }
      // Client ID and API key from the Developer Console
      var CLIENT_ID = '405239130663-8rvdb1a1ik6ovj5dolsqfavh3lihm5k3.apps.googleusercontent.com';
      var API_KEY = 'AIzaSyAuCgPEfd2PxSXr_3yAgFXi-VT1_71OkPc';

      // Array of API discovery doc URLs for APIs used by the quickstart
      var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];

      // Authorization scopes required by the API; multiple scopes can be
      // included, separated by spaces.
      var SCOPES = "https://www.googleapis.com/auth/calendar.readonly";

      var authorizeButton = document.getElementById('authorize_button');
      var signoutButton = document.getElementById('signout_button');

      /**
       *  On load, called to load the auth2 library and API client library.
       */
      function handleClientLoad() {
        gapi.load('client:auth2', initClient);
      }

      /**
       *  Initializes the API client library and sets up sign-in state
       *  listeners.
       */
      function initClient() {
        gapi.client.init({
          apiKey: API_KEY,
          clientId: CLIENT_ID,
          discoveryDocs: DISCOVERY_DOCS,
          scope: SCOPES
        }).then(function () {
          // Listen for sign-in state changes.
          gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);

          // Handle the initial sign-in state.
          updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
          authorizeButton.onclick = handleAuthClick;
          signoutButton.onclick = handleSignoutClick;
        }, function(error) {
          appendPre(JSON.stringify(error, null, 2));
        });
      }

      /**
       *  Called when the signed in status changes, to update the UI
       *  appropriately. After a sign-in, the API is called.
       */
      function updateSigninStatus(isSignedIn) {
        if (isSignedIn) {
          authorizeButton.style.display = 'none';
          signoutButton.style.display = 'block';
          listUpcomingEvents();
        } else {
          authorizeButton.style.display = 'block';
          signoutButton.style.display = 'none';
        }
      }

      /**
       *  Sign in the user upon button click.
       */
      function handleAuthClick(event) {
        gapi.auth2.getAuthInstance().signIn();
      }

      /**
       *  Sign out the user upon button click.
       */
      function handleSignoutClick(event) {
        gapi.auth2.getAuthInstance().signOut();
      }

      /**
       * Append a pre element to the body containing the given message
       * as its text node. Used to display the results of the API call.
       *
       * @param {string} message Text to be placed in pre element.
       */
      function appendPre(message) {
        var pre = document.getElementById('content');
        var textContent = document.createTextNode(message + '\n');
        // pre.appendChild(textContent);
      }

      /**
       * Print the summary and start datetime/date of the next ten events in
       * the authorized user's calendar. If no events are found an
       * appropriate message is printed.
       */
      function listUpcomingEvents() {
        gapi.client.calendar.events.list({
          'calendarId': 'primary',
          'timeMin': (new Date()).toISOString(),
          'showDeleted': false,
          'singleEvents': true,
          'maxResults': 10,
          'orderBy': 'startTime'
        }).then(function(response) {
          var events = response.result.items;
          appendPre('Upcoming events:');

          if (events.length > 0) {
            for (i = 0; i < events.length; i++) {
              var event = events[i];
              var when = event.start.dateTime;
              if (!when) {
                when = event.start.date;
              }
              appendPre(event.summary + ' (' + when + ')')
            }
          } else {
            appendPre('No upcoming events found.');
          }
        });
      }

    </script>

    <script async defer src="https://apis.google.com/js/api.js"
      onload="this.onload=function(){};handleClientLoad()"
      onreadystatechange="if (this.readyState === 'complete') this.onload()">
    </script>

@endpush 
