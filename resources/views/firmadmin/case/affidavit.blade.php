@extends('firmlayouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
<style type="text/css">
  #PayForTranslation form input[type="checkbox"] {
    display: none;
  }
</style>
@endpush  

@section('content')
<section class="section client-listing-details task-new-header-document">
<!--new-header open-->
  @include('firmadmin.case.case_header')
<!--new-header Close-->
   
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">        

          <div class="card-body">
            <div class="profile-new-client">
             <h2>Affidavit /Declaration</h2>
             <!-- <a href="#" class="add-task-link" id="fire-modal-2">+ Add New</a> -->
             
             <div class="documents-list-box">
             
                <div class="table-responsive table-invoice">
                  <table class="table table table-bordered table-striped"  id="table" >
                    <thead>
                      <tr>
                       <th> Case Category</th>
                       <th> Case Type </th>
                       <th> Service</th>
                       <th> Cost</th>
                       <th> Status</th>
                       <th> Action</th>
                      </tr>
                    </thead>
                    <?php 
                    $additional_service = json_decode($case->additional_service);
                    if(!empty($additional_service->declaration->declaration)) {
                      foreach ($additional_service->declaration->declaration as $k1 => $v1) { ?>
                        <tr>
                         <td> <?php echo $case->case_category; ?></td>
                         <td> <?php echo $case->case_type; ?></td>
                         <td> 
                          <?php echo $v1; 
                          if($v1 == 'Other') {
                            echo ' - '.$additional_service->declaration->declaration_other[$k1];
                          }
                          ?>
                            
                          </td>
                         <td> <?php if(empty($case->VP_Assistance)) { 
                          echo 'Self managed'; 
                        }
                        else {
                          echo '$180';
                        } ?></td>
                         <td> 
                          <?php 
                              if(!empty($Affidavitdoc[$k1])) {
                                echo 'Uploaded';
                              }
                              else {
                                echo 'Not uploaded';
                              }
                            ?>
                         </th>
                         <td>
                           <?php if(!$case->VP_Assistance) { ?>
                            <a href="#" class="action_btn payfortranlation" data-index="<?php echo $k1; ?>">
                              <img src="{{ url('') }}/assets/images/icon/pencil(1)@2x.png">
                            </a>
                           <?php } 
                           if(!empty($Affidavitdoc[$k1])) {
                           ?> 
                            <a href="{{url('storage/app')}}/<?php echo $Affidavitdoc[$k1]->document; ?>" class="action_btn" download>
                              <img src="{{ url('/') }}/assets/images/icon/Group 557.svg">
                            </a>
                          <?php } ?>
                           
                         </td>
                        </tr>
                      <?php 
                    }
                    } ?>
                  </table>
                </div>         
             </div>
           </div>
          </div>
        </div>
      </div>
     </div>
  </div>
</section>
<div id="PayForTranslation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Pay Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Upload Document</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('firm/case/upload_affidavit_documents') }}" method="post" id="payment-form" enctype="multipart/form-data"> 
          <div class="row">
            <div class="col-md-12 fallback1">
              <input name="file[]" type="file" required="required"/>
            </div>
            <div class="col-md-12">
                <ul class="uploadFiles"></ul>
            </div>
          </div>
          <div class="row">  
            <div class="col-md-12 text-right">
              @csrf
              <input type="hidden" name="case_id"  value="{{$case->id}}">
              <input type="hidden" name="service_index" class="service_index" value="">
              <input type="submit" name="save" value="Save" class="btn btn-primary"/>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection
@push('footer_script')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript"> 
$(document).ready(function(){
 $(document).on('click', '.payfortranlation', function(e){
    e.preventDefault();
    var index = $(this).data('index');
    console.log(index);
    $('.service_index').val(index);
    $("#PayForTranslation").modal('show');
  });
});
//================ Edit user ============//

</script>
<style type="text/css">
  .daterangepicker.dropdown-menu {
    z-index: 99999;
  }
</style>
@endpush 