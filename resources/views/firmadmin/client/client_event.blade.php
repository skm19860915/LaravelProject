@extends('firmlayouts.admin-master')

@section('title')
View client
@endsection
<?php
$data = Auth::User();
$firm = DB::table('firms')->where('id', $data->firm_id)->first();
?>
@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('firmadmin.client.client_header')
<!--new-header Close-->
  
   <div class="section-body events-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/client') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
           <div class="card-body">
           
             <div class="profile-new-client">
               <h2>Events</h2>
               <?php if($firm->account_type == 'CMS') { ?>
               <a href="{{ url('firm/client/create_event/')}}/{{$client->id}}" class="add-task-link">Create Event</a>
               <div class="table-box-task">
                <div class="table-responsive table-invoice">
                   <table class="table table-striped">
                     <thead>
                       <tr>
                         <!-- <th>ID</th> -->
                         <th>Title</th>
                         <th>Description</th>
                         <th>Event Type</th>
                         <th>Start Date/Time</th>
                         <th>End Date/Time</th>
                         <th>Action</th>
                       </tr>
                     </thead>
                     <tbody>
                      <?php
                      if(!empty($event)) {
                        foreach ($event as $k => $v) {
                          echo '<tr>
                             
                             <td>'.$v->event_title.'</td>
                             <td>'.$v->event_description.'</td>
                             <td>'.$v->event_type.'</td>
                             <td>'.$v->s_date.' <span class="text-gray">'.$v->s_time.'</span></td>
                             <td>'.$v->e_date.' <span class="text-gray">'.$v->e_time.'</span></td>
                             <td><a href="'.url('firm/client/client_edit_event/'.$client->id.'/'.$v->id).'" data-toggle="tooltip" title="Edit" class="action_btn"><img src="'.url('/').'/assets/images/icon/pencil(1)@2x.png"></a>';
                            // if($v->status) {
                            //   echo '<td><div class="Active">Completed</div></td>';
                            // } 
                            // else {
                            //   echo '<td><div class="Closed - Lost">Incompleted</div></td>';
                            // }
                          echo '</tr>';
                        }
                      }
                      ?>
                      <!-- <div class="Closed - Lost">Open</div> -->
                     </tbody>
                   </table>
                 </div>
               </div>
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


<div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">  
      <div class="col-md-12">
        Note 
        <br>
      </div>
      <div class="col-md-12">
        <textarea name="note" class="form-control" style="height: 150px;"></textarea>
      </div>
    </div>
    <div class="row">  
      <div class="col-md-12 text-right">
        <input type="hidden" name="client_id" value="{{ $client->id }}" >  
        @csrf
        <input type="submit" name="save" value="Create Client Note" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>
@endsection


@push('footer_script')

<script type="text/javascript">
$(document).ready(function(){
  $("#fire-modal-2").fireModal({title: 'Add Client Notes', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var client_id = $('input[name="client_id"]').val();
      var note = $('textarea[name="note"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/client/add_notes') }}",
        data: {_token:_token, note:note, client_id:client_id},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/client/view_notes') }}/{{ $client->id }}";
          }
          else {
            alert('Mendatory fields are required!')
          }
          console.log(res);
        }
      });
    });
});


//================ Edit user ============//

</script>
<style type="text/css">
  .card .card-header .btn {
    margin-top: 1px;
    padding: 2px 12px;
  }
</style>
@endpush 