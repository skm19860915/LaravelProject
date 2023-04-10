@extends('firmlayouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details task-new-header-tasks">
<!--new-header open-->
  @include('firmadmin.case.case_header')
<!--new-header Close-->
  
   <div class="section-body task-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/case/case_tasks') }}/{{$case->id}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
          <div class="card-body">
            <div class="profile-new-client">
              <?php if($firm->account_type == 'CMS') { ?>
              <form action="{{ url('firm/case/insert_new_task') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
                
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Task Type *
                    </label> 
                    <div class="col-sm-12 col-md-7">
                     <select name="type" class="selectpicker form-control" required="required">
                  <option value="">Select One</option>
                  <option value="Reminder">Reminder</option>
                  <option value="Consultation">Consultation</option>
                  <option value="Court Date">Court Date</option>
                  <option value="Other">Other</option>
                   </select>
                    </div>
                  </div>
                  
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Title *
                    </label> 
                    <div class="col-sm-12 col-md-7">
                     <input type="text" placeholder="Event Title" name="title" class="form-control" value="">
                    </div>
                  </div>
                  <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Description *
                    </label> 
                    <div class="col-sm-12 col-md-7">
                      <textarea placeholder="Write here...." name="description" class="form-control"></textarea>
                    </div>
                  </div>
                  
                <div class="form-group row mb-4">
                    <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Dates *
                    </label> 
                    <div class="col-sm-12 col-md-7">
                     <input type="text" placeholder="Event date" name="date" class="form-control datepicker" required="" value="">
                    </div>
                  </div>
                
                  
                  <div class="form-group row mb-4">
                  <!--<label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">
                  </label>--> 
                  <div class="col-sm-12 col-md-7">
                    <input type="hidden" name="id" value="{{ $case->id }}" > 
                    <input type="hidden" name="case_id" value="{{ $case->id }}" >  
                   @csrf
                  <input type="submit" name="save" value="Create Task" class="btn btn-primary saveclientinfo_form"/>
                  </div>
                </div>
              </form>
              <?php } else { ?>
              <div class="row">
                  <div class="col-md-6 offset-md-3">
                      <br><br>
                      <form action="{{url('firm/pay_for_cms')}}" method="post" id="payment-form" enctype="multipart/form-data">
                          <div class="card card-info text-center">
                            <br>
                            <div class="card-body">
                              <h6>
                                  <i class="fa fa-exclamation-triangle"></i> 
                                  This feature is for case management software users
                              </h6>
                              <h5 style="max-width: 320px;margin: 15px auto;">
                                  Get full CMS access for your Firm we are all using it.
                              </h5>
                              <h5>
                                  $<span class="annual_payment_cycletext">{{$firm->usercost}} a month</span> <br> per user
                              </h5>

                              <label class="custom-switch mt-2">
                                  <span class="custom-switch-description" style="margin: 0 .5rem 0 0;">Bill Annually</span> 
                                  <input type="checkbox" name="payment_cycle" class="custom-switch-input annual_payment_cycle" value="1" checked data-monthly_amount="{{$firm->usercost}}">
                                  <span class="custom-switch-indicator"></span>
                                  <span class="custom-switch-description">Bill Monthly</span>
                              </label>
                              <div class="saved_amount_text"></div>
                            </div>
                            <div class="card-footer">
                              @csrf
                              <input type="hidden" name="amount" value="55">
                              <!-- <button type="button" name="payforcms" class="btn btn-primary payforcms">Get Started</button> -->
                              <a href="{{url('firm/upgradetocms')}}" class="btn btn-primary">Upgrade</a>
                            </div>
                            <div class="payment-form-card" id="card-element" style="display: none;">
                               <h2 class="provided_cost"></h2>
                               <?php if(!empty($card)) {
                                echo '<div class="row card-payno-tx"><div class="col-md-12 text center">Pay with existing card</div></div>';
                                foreach ($card as $k => $v) {
                                ?>
                               <div class="row">
                                 <div class="col-md-8">
                                   <label>
                                     <input type="text" value="***********<?php echo $v->last4; ?>" class="form-control" readonly />
                                     <input type="checkbox" value="<?php echo $v->id; ?>" name="card_source" style="display: none;"/>
                                   </label>
                                 </div>
                                 <div class="col-md-4">
                                   <input value="Pay" type="submit" class="paywith_existing btn btn-primary">
                                 </div>
                               </div>
                               <?php }
                               echo '<div class="row"><div class="col-md-12 text center">OR, Pay with new card</div></div>';
                               } ?>
                               <div class="row card-payno">
                                  <div class="col-md-12"><div class="payment-input">
                                    <input type="text" placeholder="Card Number" size="20" data-stripe="number"/></div></div>
                                 </div>
                                 <div class="row">
                                  <div class="col-md-6 col-sm-6"><div class="payment-input">
                                    <input type="text" placeholder="Expiring Month" data-stripe="exp_month"/>
                                  </div>
                                </div>
                                  <div class="col-md-6 col-sm-6"><div class="payment-input">
                                    <input type="text" placeholder="Expiring Year" size="2" data-stripe="exp_year">
                                  </div>
                                </div>
                                  
                                 </div>
                                 <div class="row">
                                  <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="CVV Code" size="4" data-stripe="cvc"/></div></div>
                                  <div class="col-md-6 col-sm-6"><div class="payment-input"><input type="text" placeholder="Postal Code" size="6" data-stripe="address_zip"/></div></div>
                                  
                                 </div>              
                               <div class="submit-login">
                                @csrf
                                <input value="Upgrade" type="submit" class="submit">
                               </div>
                               
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
          <?php } ?>
            </div>
          </div>
        </div>
      </div>
     </div>
  </div>
  
</section>
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">

var index_url = "{{route('admin.usertask.getData')}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'firm_name', name: 'firm_name'},
        { data: 'task_type', name: 'task_type'},
        { data: 'task', name: 'task'},
        { data: 'case_id', name: 'case_id'},
        { data: 'allot_user_id', name: 'allot_user_id'},
        { data: 'priority', name: 'priority'},
        { data: 'stat', name: 'stat'},
        
        { data: null,
          render: function(data){
            var view_button = '';
            if(data.case_id) {
              view_button = ' <a href="{{url('admin/document_request')}}/'+data.case_id+'" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
            }
              var time_button = ' <a href="{{url('admin/task/timeline')}}/'+data.case_id+'" class="btn btn-primary"><i class="fa fa-clock"></i></a>';
              var edit_button = ' <a href="{{url('admin/task/edit')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
              return view_button;

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
 });

//================ Edit user ============//

</script>

@endpush 
