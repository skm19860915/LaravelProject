@extends('layouts.admin-master')

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
<section class="section">
    <div class="section-header">
        <h1>Integration</h1>
        <div class="section-header-breadcrumb">

        </div>
    </div>
    <div class="section-body">
        @if(Auth::user()->id == 1)
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
        @endif
        <div class="row">
          <div class="col-md-12">
            <form action="{{ url('admin/setting/app_setting_update') }}" method="post" class="needs-validation" enctype="multipart/form-data">
              <!-- google calendar api settings -->
              <div class="card">
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
