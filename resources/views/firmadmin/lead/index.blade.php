@extends('firmlayouts.admin-master')

@section('title')
Manage Lead
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
    display: none;
}  
body div#table_filter {
     display: block !important; 
}  
</style>
@endpush  

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Leads</h1>
        <div class="section-header-breadcrumb">
            <?php if($firm->account_type == 'CMS') { ?>
            <a href="{{ url('firm/lead/create') }}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> Add Lead</a>
            <?php } ?>
        </div>
    </div>
    <div class="section-body">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
                        <a href="{{ url('firm/admindashboard') }}">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
                       </div>
                    </div>
                    <div class="card-body">
                        <?php if($firm->account_type == 'CMS') { ?>
                        <div class="table-responsive table-invoice">
                            <table class="table table table-bordered table-striped"  id="table" >
                                <thead>
                                    <tr>
                                        <th style="display: none;"> TID </th>
                                        <th> Name </th>
                                        <!-- <th> Last Name </th> -->
                                        <th> Phone Number </th>
                                        <th> Status </th>
                                        <!-- <th> create date </th> -->
                                        <!-- <th> Consult Date/Time </th> -->
                                        <th> Action</th>
                                    </tr>
                                </thead>
                            </table>
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
</section>
<div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/lead/create_lead_event') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
        <div class="row">
            <div class="col-md-4">
                First Name : 
            </div>
            <div class="col-md-8">
                <input type="text" placeholder="First Name" name="first_name" class="form-control" required="">
            </div>
        </div>
        <br>
        <div class="row">  
            <div class="col-md-4">
                Last Name : 
            </div>
            <div class="col-md-8">
                <input type="text" placeholder="Last Name" name="last_name" class="form-control">
            </div>
        </div>
        <br>
        <div class="row">  
            <div class="col-md-4">
                Enable Portal Access :
            </div>
            <div class="col-md-8">
                <label class="custom-switch mt-2" style="padding-left: 0;">
                    <input type="checkbox" name="is_portal_access" class="custom-switch-input" value="1">
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description"></span>
                </label>
            </div>
        </div>
        <br>
        <div class="row" style="display: none;">  
            <div class="col-md-4">
                Email Address : 
            </div>
            <div class="col-md-8">
                <input type="email" placeholder="Email Address" name="email_address" class="form-control">
            </div>
            <div class="somerequiredInfo col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        Phone Number :
                    </div>
                    <div class="col-md-8">
                        <input type="text" placeholder="Phone" name="phone" class="form-control">
                    </div> 
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        Address :
                    </div>
                    <div class="col-md-8">
                        <input type="text" placeholder="Address" name="Address" class="form-control">
                    </div> 
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        Note :
                    </div>
                    <div class="col-md-8">   
                        <input type="textarea" placeholder="Note" name="note" class="form-control">
                    </div>
                </div>
            </div>

        </div>
        <br>
        <div class="row">  
            <div class="col-md-12 text-right">
                <input type="hidden" name="lead_id" value="">  
                @csrf
                <input type="submit" name="save" value="Convert to Client" class="btn btn-primary convert_client_act"/>
            </div>
        </div>
    </form>
</div>
<button id="fire-modal-2" class="trigger--fire-modal-2" style="display: none;"></button>
<!-- Add Note Modal -->
<div id="AddUserWrapper" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Pay Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="float: right;
          position: absolute;
          right: 22px;
          top: 15px;
          ">&times;</button>
          <h4 class="modal-title">Add New User</h4>
        </div>
        <div class="modal-body">
          <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" placeholder="Name" name="user_name" class="form-control" required="" value="<?php if(isset(Session::get('data')['name'])) { echo Session::get('data')['name']; }?>"> 
                <div class="invalid-feedback">Name is required!</div>
              </div>
            </div> 
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email Address
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="email" placeholder="Email Address" name="email" class="form-control" required="" value="<?php if(isset(Session::get('data')['email'])) { echo Session::get('data')['email']; }?>"> 
                <div class="invalid-feedback">Email Address is required!</div> 
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="" name="Role_type">
                  <?php if($firm->account_type == 'CMS') { ?>
                    <option value="4" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == '4') { echo 'selected="selected"'; }?>>Firm Admin</option>
                    <option value="5" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == '5') { echo 'selected="selected"'; }?>>Firm User</option>
                  <?php } else { ?>
                    <option value="5" <?php if(isset(Session::get('data')['role_id']) && Session::get('data')['role_id'] == '5') { echo 'selected="selected"'; }?>>Attorney</option>
                  <?php } ?>
                </select>
                <div class="invalid-feedback">Please select Role!</div> 
              </div>
            </div>
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7 text-right">
                @csrf
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary createfirmuserbtn" type="submit" name="create_firm_user" value="Create and Add More">
                  <span>Create</span>
                </button>
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

    var index_url = "{{route('firm.lead.getData')}}";
    var srn = 0;
    $(window).on('load', function() {
    srn++;
    $('#table').DataTable({
        language : {
            sLengthMenu: "Show _MENU_"
        },
        processing: true,
        serverSide: true,
        ajax: index_url,
        "order": [[ 0, "desc" ]],
        columns: [
        { data: 'id', name: srn},
        { data: 'name', name: 'name'},
        // { data: 'last_name', name: 'last_name'},
        { data: 'cell_phone', name: 'cell_phone'},
        // { data: 'status', name: 'status'},
        { data : null,
            render: function(data){ 
                return '<div class="rkhidedatepicker '+data.status+'">'+data.status+'</div>';
            }, orderable: "false"
        },
        // { data: 'created', name: 'created'},
        // { data: 'event', name: 'event'},
        { data: null,
                render: function(data){ 
                var text = "'Are You Sure to delete this record?'";
                var view_button = ' <a href="{{url('firm/lead/show')}}/' + data.id + '" class="action_btn" data-toggle="tooltip" data-placement="top" title="Show details"><img src="{{url('assets/images/icon')}}/Group 557.svg" /></a>';
                //view_button = '';
                /*var delete_button = ' <a href="{{url('firm/lead/delete')}}/'+data.id+'" class="btn btn-danger" onclick="return window.confirm('+text+');" data-toggle="tooltip" data-placement="top" title="Delete Lead"><i class="fa fa-trash"></i></a>';*/
                //console.log(data);
                if (data.status == "Active" || data.status == "Aging") {
                var arr = {"first_name":data.name, "last_name":data.last_name, "id":data.id, "email":data.email, "note":data.lead_note, "phone":data.cell_phone, "dob":data.dob, "Address":data.Current_address};
                var user_button = ' <a href="{{url('firm/lead/edit')}}/' + data.id + '" class="action_btn" data-toggle="tooltip" data-placement="top" title="Convert to Client"><img src="{{url('assets/images/icon')}}/Group 16@2x.png" /></a>';
                var user_lost = ' <a href="{{url('firm/lead/lost')}}/' + data.id + '" class="action_btn" data-toggle="tooltip" data-placement="top" title="Lead Lost"><img src="{{url('assets/images/icons')}}/case-icon3.svg" /></a>';
                var edit_button = ' <a href="{{url('firm/lead/edit')}}/' + data.id + '" class="action_btn" data-toggle="tooltip" data-placement="top" title="Edit lead"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
                var event_button = ' <a href="{{url('firm/lead/create_event')}}/' + data.id + '" class="action_btn" data-toggle="tooltip" data-placement="top" title="Schedule a consult"><img src="{{url('assets/images/icon')}}/calendar(3)act@2x.png" /></a>';
                var lead_nots = ' <a href="{{url('firm/lead/notes')}}/' + data.id + '" class="action_btn" data-toggle="tooltip" data-placement="top" title="Lead notes"><img src="{{url('assets/images/icon')}}/notepad@2x.png" /></a>';
                lead_nots = '';
                edit_button = '';
                user_lost = '';

                invoice_button = ' <a href="{{url('firm/lead/add_invoice')}}/' + data.id + '" class="action_btn" data-toggle="tooltip" data-placement="top" title="Create Invoice"><img src="{{url('assets/images/icon')}}/ticket@2x.png" /></a>';
                // return view_button + edit_button + event_button + user_button + user_lost;Edit

                if (data.event == "") {

                var event_button = ' <a href="{{url('firm/lead/create_event')}}/' + data.id + '" class="action_btn" data-toggle="tooltip" data-placement="top" title="Schedule event"><img src="{{url('assets/images/icon')}}/calendar(3)act@2x.png" /></a>';
                return view_button + event_button + invoice_button + edit_button + user_button + user_lost + lead_nots;
                } 
                else{
                // var event_button = '';
                // if (data.oldevent < data.todaytime)
                // {

                event_button = ' <a href="{{url('firm/lead/create_event')}}/' + data.id + '?reschedule=1" class="action_btn" data-toggle="tooltip" data-placement="top" title="Re-Schedule event"><img src="{{url('assets/images/icon')}}/calendar(3)act@2x.png" /></a>';
                //}
                return view_button + event_button + invoice_button + edit_button + user_button + user_lost + lead_nots;
                }
                } else{

                var lead_nots = ' <a href="{{url('firm/lead/notes')}}/' + data.id + '" class="action_btn" data-toggle="tooltip" data-placement="top" title="Lead notes"><img src="{{url('assets/images/icon')}}/notepad@2x.png" /></a>';
                return view_button;
                }


                }, orderable: "false"
        },
        ],
        initComplete: function(row, data) {
        $(row).attr('data-user_id', data['id']);
        }
    });
    $("#fire-modal-2").fireModal({title: 'Convert To Client', body: $("#modal-form-part"), center: true}); });
    $(document).on('click', '.convert_client_btn', function(e){
        e.preventDefault();
        var data = $(this).data('data');
        $("#fire-modal-1 input[name='first_name']").val(data.first_name);
        $("#fire-modal-1 input[name='last_name']").val(data.last_name);
        $("#fire-modal-1 input[name='lead_id']").val(data.id);
        $("#fire-modal-1 input[name='phone']").val(data.phone);
        $("#fire-modal-1 input[name='Address']").val(data.Address);
        $("#fire-modal-1 input[name='note']").val(data.note);
        $("#fire-modal-1 input[name='email_address']").val(data.email);
        $("#fire-modal-2").trigger('click');
    });
    $(document).on('change', 'input[name="is_portal_access"]', function(){
    var c = $(this).is(':checked');
    if (c) {
  //  console.log(c);
    $(this).closest('.row').next().next().show();
    $(this).closest('.row').next().next().find('input[name="email_address"]').attr('required', 'required');
    }
    else {
    $(this).closest('.row').next().next().hide();
    $(this).closest('.row').next().next().find('input[name="email_address"]').attr('required', '');
    }
    });
    $(document).on('click', '.convert_client_act', function(e){
    e.preventDefault();
    
    var c = $('input[name="is_portal_access"]').is(':checked');
    if (c && $('input[name="email_address"]').val() == '') {
    alert('Email Address is required!');
    return false;
    }
    var txt = $(this).val();
    var $this = $(this);
    $(this).attr('disabled', true);
    $(this).val('please wait...');
    var first_name = $('input[name="first_name"').val();
    var last_name = $('input[name="last_name"').val();
    var lead_id = $('input[name="lead_id"').val();
    var _token = $('input[name="_token"').val();
    var email = $('input[name="email_address"').val();
    var phone = $('input[name="phone"').val();
    var address = $('input[name="Address"').val();
    var note = $('input[name="note"').val();
    $.ajax({
    type:"post",
            url:"{{ url('firm/lead/convert_client') }}",
            data: {first_name:first_name, last_name:last_name, lead_id:lead_id, _token:_token, email:email, is_portal_access: c,phone:phone,add:address,Note:note},
            success:function(res)
            {
            res = JSON.parse(res);
            if (res.status) {
            window.location.href = "{{ url('firm/lead') }}";
            }
            else {
                $this.attr('disabled', false);
                $this.val(txt);
                alert(res.msg)
            }
            //console.log(res);
            }
    });
    });
Stripe.setPublishableKey("{{ env('SRTIPE_PUBLIC_KEY') }}");
$(document).ready(function(){
    $(".removeuserbtn").click(function(e) {
        e.preventDefault();
        var $this = $(this);
        var msg = $(this).data('msg');
        var id = $(this).data('id');
        if(msg != '') {
            var confirm=window.confirm(msg);
            if (confirm==true) {
                $.ajax({
                    type:"get",
                    url:"{{ url('firm/users/delete') }}/"+id,
                    data: {},
                    success:function(res) {       
                        alert('Firm User deleted successfully!');
                        $this.closest('tr').fadeOut();
                    }

                });
                
            }
        }
    });
    $('.adduserbtn').on('click', function(e){
        e.preventDefault();
        var client_id = $(this).data('client_id');
        $('#AddUserWrapper').modal('show');
    });
    $(".createfirmuserbtn").click(function(e) {
        e.preventDefault();
        var $this = $(this);
        var user_name = $('#AddUserWrapper form input[name="user_name"]').val();
        var email = $('#AddUserWrapper form input[name="email"]').val();
        var Role_type = $('#AddUserWrapper form select[name="Role_type"]').val();
        var _token = $('input[name="_token"]').val();

        $.ajax({
            type:"post",
            url:"{{ url('firm/users/createuser') }}",
            data: {
                _token:_token, 
                user_name:user_name, 
                email:email,
                Role_type:Role_type
            },
            success:function(res) { 
                res = JSON.parse(res);
                if(res.status) {
                    alert(res.msg);
                    window.location.href = "{{ url('firm/lead') }}";
                }
                else {
                    alert(res.msg);
                }
            }

        });        
    });
    $(".paywithnewbtn").on('click', function(e){
      e.preventDefault();
      $('.newcardwrapper').slideToggle();
    });
    $('.paywith_existing').on('click', function() {
        console.log('1');
        $('input[name="card_source"]').prop('checked', false);
        $(this).closest('.row').find('input[type="checkbox"]').prop('checked', true);
    });
    $('.payforcms').on('click', function(e){
        e.preventDefault();
        $(this).hide();
        $('#card-element').slideDown();
    })
});
$(function() {
var $form = $('#payment-form');
$form.submit(function(event) {
  if(!$('input[name="card_source"]').is(':checked')) {
    // Disable the submit button to prevent repeated clicks:
    $form.find('.submit').prop('disabled', true);

    // Request a token from Stripe:
    Stripe.card.createToken($form, stripeResponseHandler);

    // Prevent the form from being submitted:
    return false;
  }
});
});

function stripeResponseHandler(status, response) {
// Grab the form:
var $form = $('#payment-form');

if (response.error) { // Problem!

  // Show the errors on the form:
  $form.find('.payment-errors').text(response.error.message);
  $form.find('.submit').prop('disabled', false); // Re-enable submission

} else { // Token was created!

  // Get the token ID:
  var token = response.id;

  // Insert the token ID into the form so it gets submitted to the server:
  // $form1 = $('#payment-form-res');
  $form.append($('<input type="hidden" name="stripeToken">').val(token));

  // Submit the form:
  $form.get(0).submit();
}
};
//================ Edit user ============//

</script>
<style>
    .somerequiredInfo{border: 1px solid #ccc;
                      padding: 1em 1em;
                      margin-top: 1em;
                      border-radius: 7px;}
                      .rkhidedatepicker .ui-datepicker {
                        display: none !important;
                      }
</style>
@endpush 
