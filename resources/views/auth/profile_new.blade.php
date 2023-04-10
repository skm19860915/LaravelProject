@extends('firmlayouts.admin-master')
@section('title')
Profile
@endsection
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
@section('content')
<section data-dashboard="1" class="section dashboard-new-design">
    <div class="section-header">
        <h1>Profile  ddd</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">

            </div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
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