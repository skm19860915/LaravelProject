@extends('layouts.admin-master')

@section('title')
Team
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
#usertable tbody tr td:nth-child(1) {
  display: none;
}
</style>
@endpush  

@section('content') 
<section class="section">
  <div class="section-header">
    <h1><a href="{{ url('admin/dashboard') }}"><span>Dashboard /</span></a> Team</h1>
    <div class="section-header-breadcrumb">
      <a href="{{ url('admin/users/create') }}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> New User</a>
    </div>
  </div>
  <div class="section-body">
    
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Total <span>({{ $total }})</span></h4>
            <div class="card-header-action">
              
            </div>
          </div>
          <div class="card-body">
            <div style="float: left;" class="country-left-select">
                <select class="form-control" name="role" id="role">
                    <option value="">All</option>
                    <option value="1">TILA Admin  </option>
                    <option value="2">TILA VP</option> 
                    <!-- <option value="3">Support User</option>
                    <option value="4">Firm Admin</option> -->
                </select>               
            </div>
            <div class="table-responsive table-invoice admin-table-user">
          		<table class="table"  id="usertable" >
          			<thead>
          				<tr>
                   <th style="display: none;"> Id </th>
                   <th> Name </th>
                   <th> Email </th>
                   <th> Phone</th>
                   <th> Role </th>
                   <!-- <th> create date </th> -->
                   <th> Status </th>
                   <th> Action </th>
          				</tr>
          			</thead>
          		</table>
            </div>
          </div>
        </div>
      </div>
     </div>
    <!-- <users-component></users-component> -->
  </div>
</section>
@endsection

@push('footer_script')
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>

<script>

if( window.location.href == "{{ url('/') }}/firm/payment_method") 
 {
  var stripe = Stripe("{{ env('SRTIPE_PUBLIC_KEY') }}");

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
  base: {
    color: '#32325d',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

// Create an instance of the card Element.
var card = elements.create('card', {style: style});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

// Handle real-time validation errors from the card Element.
card.addEventListener('change', function(event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

// Handle form submission.
var form = document.getElementById('payment-formstripe');
form.addEventListener('submit', function(event) {
  event.preventDefault();

  stripe.createToken(card).then(function(result) {
    if (result.error) {
      // Inform the user if there was an error.
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server.
      stripeTokenHandler(result.token);
    }
  });
});

// Submit the form with the token ID.
function stripeTokenHandler(token) {
  // Insert the token ID into the form so it gets submitted to the server
  var form = document.getElementById('payment-formstripe');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
}
}
$(document).ready(function(){
  function getUserdata(r = '') {
    var index_url = "{{ url('admin/get_user_data') }}";
    var table = $('#usertable').DataTable({
      processing: true,
      serverSide: true,
      destroy: true,
        "ajax": {
            "url": index_url,
            "type": "GET",
            "data": {
                _token: ' {{ csrf_token()}}',
                "role": r,
            }
        },
      order: [ [0, 'desc'] ],
      columns: [
      { data: 'id', name: 'id'},
      { data: 'name', name: 'name'},
      { data: 'email', name: 'email'},
      { data: 'contact_number', name: 'contact_number'},
      { data: 'role_name', name: 'role_name'},
      // { data: 'created_at', name: 'created_at'},
      { data: 'stat', name: 'stat'},
      { data: true,
        render: function(data){
          var label = '<a href="" class="action_btn edituser" title="Edit User" data-toggle="tooltip"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" /></a>';
          
          label += '<a href="" class="action_btn viewuser" title="View User Details" data-toggle="tooltip"><img src="{{url('assets/images')}}/icon/Group 557.svg"></a>';
          label += '<a href="" class="action_btn deleteuser" onclick="return confirm(\'Are you sure?\')" title="Delete User" data-toggle="tooltip"><img src="{{url('assets/images')}}/icons/case-icon3.svg"></a>';
          return label;
        }, 
        orderable: "false"
      },
      ],
      rowCallback: function(row, data, start, end, display) {
        var uid = data['id'];
        $(row).attr('data-user_id', uid);
        var elink = '{{ url("admin/users") }}/'+uid+'/edit';
        $(row).find('.edituser').attr('href', elink);
        if(data.role_id == 2) { 
          var vlink = '{{ url("admin/users/show") }}/'+uid;
          $(row).find('.viewuser').attr('href', vlink);
        }
        else {
          $(row).find('.viewuser').remove();
        }
        var dlink = '{{ url("admin/users/delete") }}/'+uid;
        $(row).find('.deleteuser').attr('href', dlink);
      },
      'footerCallback': function(row, data, start, end, display) {
        // console.log(row); 
        // console.log(data); 
        // console.log(start);
        // console.log(end);
        // console.log(display);
        console.log(table.data(),'---------------------------');
        var api = this.api(), data;
        // $( api.column( 0 ).footer() ).html(table.data().length);
      }
    });
  }
  getUserdata();
  $('#role').on('change', function(){
    var r = $(this).val();
    getUserdata(r);
  });
});

</script>

@endpush 
