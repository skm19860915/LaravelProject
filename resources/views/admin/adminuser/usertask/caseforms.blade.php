@extends('layouts.admin-master')

@section('title')
Client Questionnaire Form
@endsection
@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
@endpush 
@section('content')
<section class="section client-listing-details task-new-header-family">
<!--new-header open-->
  @include('admin.adminuser.usertask.task_header')
<!--new-header Close-->
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('admin/all_case') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
            <div class="card-body">

              <div class="profile-new-client">
                <h2>Forms</h2>
                <div style="width: 220px;">
             <select class="family_arr" name="client_id" data-live-search="true">
                  <option value="">All</option>
                  <?php
                  if(!empty($client)) {
                    $cname = $client->first_name;
                    if(!empty($client->middle_name)) {
                      $cname .= ' '.$client->middle_name;
                    }
                    if(!empty($client->last_name)) {
                      $cname .= ' '.$client->last_name;
                    }
                    $sl = '';
                    if($uid == $client->user_id) {
                      $sl = 'selected="selected"';
                    }
                    echo '<option value="'.$client->user_id.'" '.$sl.'>'.$cname.'</option>';
                  } 
                  $farr = array();
                  if(!empty($family_alllist)) {
                    foreach ($family_alllist as $key => $value) {
                      if(!in_array($value->uid, $farr)) {
                        $farr[] = $value->uid;
                        $sl = '';
                        if($uid == $value->uid) {
                          $sl = 'selected="selected"';
                        }
                        echo '<option value="'.$value->uid.'" '.$sl.'>'.$value->name.'</option>';
                      }
                    }
                  }
                  ?>
                </select>
              </div>
                  <div class="table-responsive table-invoice">
                       <table class="table table-striped">
                          <tbody><tr>
                              <!-- <th>Case ID</th> -->
                              <th>Form Type</th>
                              <!-- <th>Client Name</th> -->
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
                          <?php if($case->case_category == 'NVC Packet/Consular Process') { ?>
                          <tr>
                            <td>DS-260, Immigrant Visa and Alien Registration Application (Online Only)</td>
                            <!-- <td>{{$cname}}</td> -->
                            <td>{{ GetCaseStatus($case->status) }}</td>
                            <td>
                              <a href="https://ceac.state.gov/IV/login.aspx" target="_blank" class="action_btn">
                                <img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" />
                              </a>
                            </td>
                          </tr>
                          <?php } ?>
                          @if ($client_information_forms->isEmpty())

                          @else
                          @foreach ($client_information_forms as $form)
                          <?php if($form->file_type != 'DS-260, Immigrant Visa and Alien Registration Application (Online Only)') { ?>
                          <tr>
                              <!-- <td>
                                <?php echo $form->case_id; ?>
                              </td> -->
                              <td style="text-transform: capitalize;">
                                <?php echo str_replace('_', ' ', $form->file_type); ?>
                              </td>
                              <!-- <td>
                                {{$form->name}}
                              </td> -->
                              <td>{{ GetCaseStatus($case->status) }}</td>
                              <td>
                                  <?php 
                                  $rform = $form; 
                                  unset($rform->birth_address);
                                  unset($rform->client_aliases);
                                  ?>
                                  <?php //if($case->status != 6) { ?>
                                  @if($form->status1 == 1)
                                      <a href="{{ url('editpdf') }}/{{$form->info_id}}" target="_blank" class="action_btn document_file" data-file="{{asset('storage/app')}}/{{$form->file}}" data-id="{{$form->info_id}}">
                                      <img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" />
                                  </a>
                                  <!-- <a href="#" class="action_btn update_form_status" data-id="{{$form->info_id}}" data-status="0">
                                    <img src="{{url('assets/images/icon/gray-right.png')}}"> -->
                                  </a>
                                  @endif
                                  @if($form->status1 == 0)
                                  <?php 
                                  unset($rform->information);
                                  unset($rform->residence_address);
                                  ?>
                                  <a href="{{ url('editpdf') }}/{{$form->info_id}}" target="_blank" class="action_btn document_file" data-file="{{asset('storage/app')}}/{{$form->file}}" data-id="{{$form->info_id}}">
                                      <img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" />
                                  </a>
                                  <!-- <a href="#" class="action_btn update_form_status" data-id="{{$form->info_id}}" data-status="1">
                                <img src="{{url('assets/images/icon/right-green-sign.svg')}}">
                              </a> -->
                                  @endif
                                <?php //} ?>
                              </td>
                          </tr>
                          <?php } ?>
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
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<?php if($uid) { ?>
<script src="{{ asset('assets/WebViewer/lib/webviewer.min1.js')}}"></script>
<?php } else { ?>
<script src="{{ asset('assets/WebViewer/lib/webviewer.min.js')}}"></script>
<?php } ?>
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
  $(document).on('click', '.update_form_status', function(e){
    e.preventDefault();
    var doc_id = $(this).data('id');
    var status = $(this).data('status');
    var csrf1 = $('input[name="_token"]').val();
    $.ajax({
      url: "{{url('admin/usertask/updateformstatus')}}",
      data: {doc_id: doc_id, status: status, _token: csrf1},
      dataType: 'json',
      type: 'post',
      async: false,
      success: function (data) {
        console.log('success===========',data);
        if(status) {
          alert('Form mark as complete successfully!');  
        }
        else {
          alert('Form mark as incomplete successfully!');  
        }
        window.location.href = window.location.href;
      },
      error: function (data) {
        if(status) {
          alert('Form mark as complete successfully!');  
        }
        else {
          alert('Form mark as incomplete successfully!');  
        }
        window.location.href = window.location.href;
      }
    });
  });
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
      $(document).ready(function(){
      $('.family_arr').selectpicker();
      $('select.family_arr').on('change', function() {
        var v = $(this).val();
        var cid = "{{$admintask->id}}";
        if(v == '') {
          var url = "{{ url('admin/usertask/caseforms') }}/"+cid;
        }
        else {
          var url = "{{ url('admin/usertask/caseforms') }}/"+cid+'/'+v;
        }
        window.location.href = url;
      });
      })
    </script>

    <script async defer src="https://apis.google.com/js/api.js"
      onload="this.onload=function(){};handleClientLoad()"
      onreadystatechange="if (this.readyState === 'complete') this.onload()">
    </script>

@endpush 
