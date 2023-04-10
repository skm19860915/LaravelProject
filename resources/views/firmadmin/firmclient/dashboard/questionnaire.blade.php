@extends('firmlayouts.admin-master')
@section('title')
Profile
@endsection

@push('header_styles')
<style type="text/css">
tr.que_cls_es {
  display: none;
}
</style>
@endpush

@section('content')
<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <!-- <h1>Profile  ddd</h1> -->
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">

            </div>
        </div>
    </div>
    <div class="client-header-new">
   <div class="clent-profle-box">
    <div class="row">
     <div class="col-md-7">
      <div class="client-main-box-profile">
      <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
      <div class="client-right-text">
       <div class="clent-info"><span>Name</span>:<span>{{$data->name}}
       </span></div>
       <div class="clent-info"><span>Email </span>:<span>{{ $data->email }}</span></div>
       <div class="clent-info"><span>Phone Number </span>:<span>{{ $data->contact_number }}</span></div>
      </div>  
      </div>    
     </div>
     <div class="col-md-5">
      <div class="client-right-profile">
        <div class="clent-info"><span>Status</span>:<span>Active</span></div>
       <div class="clent-info"><span>Created On</span>:<span>{{ $case->created_at }}</span>
       </div>
       <div class="clent-info"><span>Case Type</span>:<span>{{ $case->case_type }}</span>
       </div>
       
      </div>
     </div>
    </div>
   </div>
   <div class="client-menu-box">
 
   </div>
  </div>
    <div class="section-body">
        <!-- <h4>
            Questionnaire for {{$data->name}} (Me)
        </h4> -->
        <div class="row">
          <div class="col-md-12">

            <div class="card">        
              <div class="card-header">
                <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
                  <a href="{{ url('firm/clientdashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
                 </div>
               </div>
              <div class="card-body">
                <div class="profile-new-client">
                 <h2>Questionnaire</h2>
                 <div class="documents-list-box">
                  <div style="width: 220px;">
                    <select class="form-control que_lang" name="que_lang">
                      <option value="en" selected>English</option>
                      <option value="es">Spanish</option>
                    </select>
                  </div>
                 <div class="task-tabbtn-box">
                  <div class="table-responsive table-invoice table-width1" style="overflow-x: hidden;">
                      <table class="table table-striped">
                        <tbody>
                          <tr>
                          <th style="width: auto !important;">Type</th>
                          <th style="width: auto !important;">Name</th>
                          <th>Form Type</th>
                          <th>Status</th>
                          <th>Action</th>
                          </tr>
                          <tr>
                              <td>
                                Petitioner
                              </td>
                              <td>
                                {{$data->name}}
                              </td>
                              <td>
                                Questionnaire
                              </td>
                              <td class="font-weight-600">
                                Incomplete
                              </td>
                              <td>
                                <a href="{{ url('edit_questionnaire/Petitioner/en') }}/{{$case->client_id}}" target="_blank" class="action_btn que_Petitioner" title="Edit" data-toggle="tooltip">
                                  <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                                </a>
                                <a href="{{asset('storage/app/Questionnaire for Petitioner - English.pdf')}}" class="action_btn doclinkp" download title="Download" data-toggle="tooltip">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                    <g>
                                      <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"></path>
                                    </g>
                                  </svg>
                                </a>
                                <a href="{{asset('storage/app/Questionnaire for Petitioner - English.pdf')}}" class="action_btn doclink2 doclinkp" title="Print" data-toggle="tooltip">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                    <g>
                                      <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z">
                                      </path>
                                    </g>
                                  </svg>
                                </a>
                              </td>
                          </tr>
                          <!-- <tr>
                              <td>
                                Beneficiary
                              </td>
                              <td>
                                <?php 
                               if(!empty($beneficiary_list)) {
                                  echo $beneficiary_list->name;
                               }
                               ?>
                              </td>
                              <td>
                                Questionnaire
                              </td>
                              <td class="font-weight-600">
                                Incomplete
                              </td>
                              <td>
                                <a href="{{ url('edit_questionnaire/Beneficiary/en') }}/{{$case->client_id}}" target="_blank" class="action_btn que_Beneficiary" title="Edit" data-toggle="tooltip">
                                  <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                                </a>
                                <a href="{{asset('storage/app/Questionnaire for Beneficiary - English.pdf')}}" class="action_btn doclinkb" download title="Download" data-toggle="tooltip">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                    <g>
                                      <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"></path>
                                    </g>
                                  </svg>
                                </a>
                                <a href="{{asset('storage/app/Questionnaire for Beneficiary - English.pdf')}}" class="action_btn doclink2 doclinkb" title="Print" data-toggle="tooltip">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                    <g>
                                      <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z">
                                      </path>
                                    </g>
                                  </svg>
                                </a>
                              </td>
                          </tr> -->
                          <?php 
                        if(!empty($ques)) {
                          foreach ($ques as $k => $v) { 
                            $cls1 = 'doclinkb';
                            if($v->type == 'Petitioner') {
                              $cls1 = 'doclinkp';
                            }
                            ?>
                            <tr>
                              <td>
                                {{$v->type}}
                              </td>
                              <td>
                                {{$v->name}}
                              </td>
                              <td>
                                Questionnaire
                              </td>
                              <td class="font-weight-600">
                                Incomplete
                              </td>
                              <td>
                                <a href="{{ url('edit_questionnaire') }}/{{$v->type}}/{{$v->id}}/{{$v->client_id}}" target="_blank" class="action_btn" title="Edit" data-toggle="tooltip">
                                  <img src="{{url('assets/images/icon/pencil(1)@2x.png')}}">
                                </a>
                                <a href="{{asset('storage/app')}}/{{$v->file}}" class="action_btn" download title="Download" data-toggle="tooltip">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                    <g>
                                      <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"></path>
                                    </g>
                                  </svg>
                                </a>
                                <a href="{{asset('storage/app')}}/{{$v->file}}" class="action_btn doclink2" title="Print" data-toggle="tooltip">
                                  <svg viewBox="0 0 24 24" preserveAspectRatio="xMidYMid meet" focusable="false" style="height: 85%; fill: #949DB2;">
                                    <g>
                                      <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z">
                                      </path>
                                    </g>
                                  </svg>
                                </a>
                              </td>
                          </tr>
                        <?php }
                        } ?>
                          <!-- <tr>
                              
                            <td>
                              <a href="#" class="add_questionnaire">
                                Add Questionnaire
                              </a>
                             </td>
                          </tr>  -->
                        </tbody>
                      </table>
                    </div>
                 
                  
                 </div>
                               
               </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" style="display: none;">
            <div class="col-md-5">
                <div class="general-info-left">
                    <?php
                    
                     $d = DB::table("new_client")->where("user_id", $data->id)->first();
                      //pre($d); die;
                    GetDataByClientInPDFForms($d->id);
                    ?>




                </div>
            </div>
            <div class="col-md-7">
                <div class="card profile-ne-height">       

                    <div class="card-body">

                        <div class="profile-new-client profile-new-test PDFProfileDetails">
                            <div class="profle-text-section">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="profle-text-user">
                                            <i class="fa fa-spin fa-spinner"></i> 
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('footer_script')
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
$(document).ready(function () {
  $('.que_lang').on('change', function(){
    var v = $(this).val();
    var l1 = "{{ url('edit_questionnaire/Beneficiary') }}/"+v+"/{{$case->client_id}}";
    var l2 = "{{ url('edit_questionnaire/Petitioner') }}/"+v+"/{{$case->client_id}}";
    $('.que_Beneficiary').attr('href', l1);
    $('.que_Petitioner').attr('href', l2);
    var doclink1 = "{{asset('storage/app')}}/Questionnaire for Petitioner - English.pdf";
    var doclink2 = "{{asset('storage/app')}}/Questionnaire for Beneficiary - English.pdf";
    if(v == 'es') {
      var doclink1 = "{{asset('storage/app')}}/Cuestionario del Peticionario - Spanish.pdf";
      var doclink2 = "{{asset('storage/app')}}/Cuestionario para Beneficiario - Spanish.pdf";
      $('.que_cls_es').show();
      $('.que_cls_en').hide();
    }
    else {
      $('.que_cls_es').hide();
      $('.que_cls_en').show();
    }
    $('.doclinkp').attr('href', doclink1);
    $('.doclinkb').attr('href', doclink2);
  });
    $('.TabBoxByPDF:nth-child(1)').trigger('click');
    $('.phone_us').mask('(000) 000-0000');
    $('.updateuser').on('click', function (e) {
        var p = $('.password').val();
        var cp = $('.c_password').val();
        if (p != '') {
            $('.c_password').prop('required', true);
            $('.c_password').next().hide();
            if (p != cp) {
                $('.c_password').next().show();
                e.preventDefault();
            }

        } else {
            $('.c_password').prop('required', false);
        }
    });
    $('.editimg').on('click', function (e) {
        e.preventDefault();
        $(this).closest('label').trigger('click');
    });
    $('#upload').change(function () {
        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg"))
        {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.user-upload-file label').css('background-image', 'url(' + e.target.result + ')');
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
});
</script>
@endpush 