@extends('firmlayouts.admin-master')

@section('title')
Integration
@endsection

@push('header_styles')
<style>
    .apps{display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    grid-column-gap: 1em;
    grid-row-gap: 1em;
    list-style: none !important;}
    .apps li{position: relative;}
    .apps li a{ }
    .apps li a span{color:#fff; position: absolute; right: 6px; top: 0px;}
    .apps li img{ width: 100%;}
</style>
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section connection-app-box">
    <div class="section-header">
        <h1>Integration</h1>
        <div class="section-header-breadcrumb">
           
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <div class="table-responsive table-invoice">
                            
                            <?php #pre($QuickBookUrl); ?>


                         
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
            </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <form action="{{ url('firm/setting/update_app_setting') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
              
              <!-- dropbox api settings -->
              <div class="card" style="display: none;">
                <div class="card-header">
                  <h4>Dropbox Api Settings</h4>
                </div>
                <div class="card-body">
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Dropbox App Key
                    </label> 
                    <div class="col-sm-12 col-md-7">
                      <input type="text" name="usermeta[DROPBOX_APP_KEY]" value="{!! \get_user_meta($id, 'DROPBOX_APP_KEY'); !!}" class="form-control"> 
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Dropbox App Secret
                    </label> 
                    <div class="col-sm-12 col-md-7">
                      <input type="text" name="usermeta[DROPBOX_APP_SECRET]" value="{!! \get_user_meta($id, 'DROPBOX_APP_SECRET'); !!}" class="form-control"> 
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Dropbox App Uri
                    </label> 
                    <div class="col-sm-12 col-md-7">
                      <input type="text" name="usermeta[DROPBOX_REDIRECT_URI]" value="{{ url('/') }}/firm/forms/addform" class="form-control" readonly="readonly">  
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

              <!-- google calendar api settings -->
              <div class="card" style="display: none;">
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
              <div class="card" style="display: none;">
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

              <!-- lawpay api settings -->
              <div class="card">
                <div class="card-header">
                  <h4>Lawpay Api Settings</h4>
                </div>
                <div class="card-body">
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Account ID
                    </label> 
                    <div class="col-sm-12 col-md-7">
                      <input type="text" name="usermeta[account_id]" value="{!! \get_user_meta($id, 'account_id'); !!}" class="form-control"> 
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Secret Key
                    </label> 
                    <div class="col-sm-12 col-md-7">
                      <input type="text" name="usermeta[SECRET_KEY]" value="{!! \get_user_meta($id, 'SECRET_KEY'); !!}" class="form-control"> 
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

              <!-- quickbook api settings -->
              <div class="card" style="display: none;">
                <div class="card-header">
                  <h4>Quickbook Api Settings</h4>
                </div>
                <div class="card-body">
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> Company ID
                    </label> 
                    <div class="col-sm-12 col-md-7">
                      <input type="text" name="usermeta[QBcompanyID]" value="{!! \get_user_meta($id, 'QBcompanyID'); !!}" class="form-control"> 
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

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">

    var index_url = "{{route('firm.setting.sms.getData')}}";
//console.log(index_url);
    var srn = 0;
// $( window ).load(function() {
    srn++;
    $('#table').DataTable({
    processing: true,
            serverSide: true,
            ajax: index_url,
            columns: [
            { data: 'id', name: srn},
            { data: 'title', name: 'title'},
            { data: 'message', name: 'message'},
            { data: 'stat', name: 'stat'},
            { data: 'created_at', name: 'created_at'},
            { data: null,
                    render: function(data){


                    var edit_button = ' <a href="{{url('firm / setting / update')}}/' + data.id + '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                    return edit_button;
                    }, orderable: "false"
            },
            ],
            /*rowCallback: function(row, data) {
             $(row).attr('data-user_id', data['id']);
             }*/
    });
    // });

//================ Edit user ============//

</script>

@endpush 
