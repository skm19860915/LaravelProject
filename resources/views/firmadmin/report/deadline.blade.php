@extends('firmlayouts.admin-master')

@section('title')
Case Deadline report
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section report-seaction-box Deadline Report">
    <div class="section-header">
        <h1>Deadline Report</h1>
        <div class="section-header-breadcrumb">
          <div class="report-icon-box"></div> 
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
                    <div class="card-body form-box-report">
                        <?php if($firm->account_type == 'CMS') { ?>
                        <div class="calender-box-new"><label for="from">From</label> 
                        <input type="text" class="from" value="<?php echo date('m/d/Y', strtotime("-6 month")); ?>" name="from">
                        <label for="to">to</label>
                        <input type="text" class="to" value="<?php echo date('m/d/Y', strtotime("+6 month")); ?>" name="to"> 
                        <button class="filter btn btn-primary"  value="courtdate" ><img src="{{ url('/') }}/assets/images/icons/right-arrow-white.svg"></button>
                       </div>
                        <div class="table-responsive table-invoice report-table-width">
                            <table class="table table table-bordered table-striped"  id="table" >
                                <thead>
                                    <tr>
                                        <th> Case Number</th>
                                        <th> Client Name </th>
                                        <th> Client Number</th>
                                        <th> Case Type </th>
                                        <th> Due Date </th>
                                        <th> Status </th>
                                        <th> Assigned to</th>
                                        <!-- <th> Action</th> -->
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
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script type="text/javascript">
$(".filter ").on("click", function(e){
    e.preventDefault();
    getreportdata();
});
getreportdata();
function getreportdata() {
    var index_url = "{{route('firm.report.deadline_getData')}}";
    var srn = 0;
    from = $('.from').val();
    to = $('.to').val();
    srn++;
    table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        "ajax": {
                "url": index_url,
                "type": "GET",
                "data": {
                    "start_date": from,
                    "end_date": to,
                }
            },
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        columns: [
            {data: 'id', name: srn},
            {data: 'clientname', name: 'clientname'},
            {data: 'client_id', name: 'client_id'},
            {data: 'case_type', name: 'case_type'},
            {data: 'e_date', name: 'e_date'},
            {data: 'stat', name: 'stat'},
            {data: 'VP_Assistance', name: 'VP_Assistance'},
        ],
    });
    table.buttons().container().appendTo($('.report-icon-box'));
}

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
                    window.location.href = "{{ url('firm/report/deadline_report') }}";
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
</script>

@endpush 
