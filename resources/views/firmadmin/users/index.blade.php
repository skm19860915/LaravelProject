@extends('firmlayouts.admin-master')

@section('title')
Manage Users
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#table tbody tr td:nth-child(1) {
    display: none;
}    
</style>
@endpush  


@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage Users</h1>
    <div class="section-header-breadcrumb">
      <?php if($data->role_id == 4) { ?>
      <a href="{{ url('firm/users/create') }}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> Add New</a>
      <?php } ?>
    </div>
  </div>
  <div class="section-body"> 
       
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th style="display: none;">Id</th>
                   <th>Name </th>
                   <th>Email</th>
                   <th>Role</th>
                   <th>Status</th>
                   <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
     </div>
  </div>
</section>
<?php if(!empty($user_deleted)) { ?>
<!-- Modal -->
<div class="modal fade" id="Addmembersmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">User Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><?php echo $user_deleted['userdata']->name; ?> deleted successfully</p>
        <p>Create Date : <?php echo date('Y-m-d', strtotime($user_deleted['userdata']->created_at)); ?></p>
        <p>End Date : <?php echo $user_deleted['end_date']; ?></p>
        <p>Refunded Amount : $<?php echo $user_deleted['amt']; ?></p>
      </div>
      <div class="modal-footer">
        @csrf
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<!-- Modal -->
<div id="PayForTranslation" class="modal fade" role="dialog" style="position: fixed;">
  <div class="modal-dialog">

    <!-- Pay Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Payment details</h4>
      </div>
      <div class="modal-body">
        <form action="{{ url('firm/payForUser') }}" method="post" id="payment-form" enctype="multipart/form-data"> 
          <div class="payment-form-card" id="card-element">
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
                  <input type="hidden" name="id"  value="">
                  <input name="amount" value="{{$firm->usercost}}" type="hidden" />
                  <input value="Upgrade" type="submit" class="submit">
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

var index_url = "{{route('firm.users.getData')}}";
$(window).on('load', function() {
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'name', name: 'name'},
        { data: 'email', name: 'email'},
        // { data: 'role_name', name: 'role_name'},
        { data: null, 
          render: function(data){
            if(data.role_id == 5) {
              <?php if($firm->account_type == 'CMS') { ?>
                return data.role_name;
              <?php }
              else { ?>
                if(data.custom_role == '') {
                  return 'Staff';
                }
                else {
                  return 'Attorney';
                }
              <?php } ?>
            }
            else {
              if('{{$firm->email}}' == data.email) {
                return 'Firm Owner';
              }
              else {
                return data.role_name;
              }
            }
          }, orderable: "false"
        },
        { data: null, 
          render: function(data){
            if(data.status) {
              return 'Active';
            }
            else {
              return 'Inactive';
            }
          }, orderable: "false"
        },
        { data: null,
          render: function(data){

            var text = "'This action will delete "+data.name+" from your account. Do you wish to proceed?'";

            var delete_button = ' <a href="{{url('firm/users/delete')}}/'+data.id+'" class="action_btn" onclick="return window.confirm('+text+');" title="Delete User" data-toggle="tooltip"><img src="{{url('assets/images/icons')}}/case-icon3.svg" /></a>';
            if('<?php echo $data->email; ?>' == data.email || '<?php echo $firm->email; ?>' == data.email) {
                delete_button = '';
            }

            var edit_button = ' <a href="{{url('firm/users/edit')}}/'+data.id+'" class="action_btn" title="Edit User" data-toggle="tooltip"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>';

            <?php if($firm->account_type == 'VP Services') { ?>
              if('<?php echo $firm->email; ?>' != data.email) {
                //edit_button = '';
              }
            <?php } ?>
            var pay_button = '';
            if(!data.status) {
              <?php if($firm->account_type == 'CMS') { ?>
                pay_button = '<a href="#" class="action_btn payfortranlation" data-toggle="tooltip" data-placement="top" title="Pay For User" data-id="'+data.id+'" data-cost="{{$firm->usercost}}"><img src="{{ url('/') }}/assets/images/icon/ticket@2x.png"></a>';
              <?php } ?>
            }
            <?php if($data->role_id == 4) { ?>
            return edit_button + delete_button + pay_button;
            <?php } else { ?>
              return '';
            <?php } ?>
          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
 });
Stripe.setPublishableKey("{{ env('SRTIPE_PUBLIC_KEY') }}");
$(document).ready(function(){
  <?php if(!empty($user_deleted)) { ?>
    $('#Addmembersmodal').modal('show');
  <?php } ?>
  $('.paywith_existing').on('click', function() {
    console.log('1');
    $('input[name="card_source"]').prop('checked', false);
    $(this).closest('.row').find('input[type="checkbox"]').prop('checked', true);
  });
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
  
$(document).on('click', '.payfortranlation', function(e){
  e.preventDefault();
  var id = $(this).data('id');
  var cost = $(this).data('cost');
  $('input[name="id"]').val(id);
  $('.provided_cost').text('User Monthly Cost : $'+cost);
  $("#PayForTranslation").modal('show');
});
//================ Edit user ============//

</script>

@endpush 
