@extends('firmlayouts.admin-master')

@section('title')
View Lead
@endsection

@push('header_styles')
<style type="text/css">
  .curruncy_symbol {
    position: absolute;
    left: 30px;
    top: 12px;
  } 
  input.form-control.case_cost {
    padding-left: 25px !important;
  }
</style>
@endpush 

@section('content')
<section class="section client-listing-details">
  <div class="section-header">
    <h1><a href="{{route('firm.lead')}}"><span>Lead /</span></a> Detail</h1>
    <div class="section-header-breadcrumb">
      <a href="{{ url('firm/lead/edit') }}/{{$lead->id}}" class="btn btn-primary" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> Convert to client</a>      
    </div>
  </div>
  <div class="client-header-new">
   <div class="clent-profle-box">
    <div class="row">
     <div class="col-md-8">
      <div class="client-main-box-profile">
      <div class="client-left-img" style="background-image: url({{ url('/') }}/assets/img/avatar/avatar-1.png);"></div>
      <div class="client-right-text">
       <h3>
         <?php 
         echo $lead->name.' '.$lead->last_name;
         ?>
         <!-- <a href="#" class="action_btn customedit_btn" title="Edit Lead" data-toggle="tooltip" style="position: static;" data-id="{{$lead->id}}"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png" style="width: 13px;" /></a> -->
       </h3>
       <p>{{ $lead->email }}<br />{{ $lead->cell_phone }}<br />
        Create Date : {{ date('M d, Y', strtotime($lead->created_at)) }}</p>
      </div>  
      </div>    
     </div>
     <div class="col-md-4">
      <div class="client-right-profile">
       <div class="clent-info"><span>Lead ID</span>:<span>#{{ $lead->id }}</span></div>
       <div class="clent-info"><span>Deported</span>:
        <span>
          <label class="custom-switch mt-2" style="padding-left: 0;">
            <input type="checkbox" name="is_deported" class="custom-switch-input is_deported" value="1" <?php echo $retVal = ($lead->is_deported == 1) ? "checked" : ""; ?>>
            <span class="custom-switch-indicator" style="width: 48px;"></span>
            <span class="custom-switch-description"></span>
          </label>
        </span>
      </div>
       <div class="clent-info"><span>Detained</span>:
        <span>
          <label class="custom-switch mt-2" style="padding-left: 0;">
            <input type="checkbox" name="is_detained" class="custom-switch-input is_detained" value="1" <?php echo $retVal = ($lead->is_detained == 1) ? "checked" : ""; ?>>
            <span class="custom-switch-indicator" style="width: 48px;"></span>
            <span class="custom-switch-description"></span>
          </label>
        </span>
       </div>
      </div>
     </div>
    </div>
   </div>
   <div class="client-menu-box">
    <ul>
      <li><a class="{{ Request::route()->getName() == 'firm.lead.show' ? 'active-menu' : '' }}" href="{{ url('firm/lead/show') }}/{{ $lead->id }}">Overview</a></li>
      <li><a class="{{ Request::route()->getName() == 'firm.lead.billing' ? 'active-menu' : '' }}" href="{{ url('firm/lead/billing') }}/{{ $lead->id }}">Billing</a></li>
    </ul>
   </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card"> 
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/lead') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
             <h4></h4>
             <a href="#payment-page" class="btn btn-primary card-header-action rapid_pay_btn" data-id="{{$invoice->id}}">
                Send
               </a>
             <a href="#" class="btn btn-primary card-header-action printinvoice">
                Print
               </a>
               <a href="#payment-page" class="btn btn-primary card-header-action" onclick="convert_HTML_To_PDF();">
                Download
               </a>
           </div>      
          <div class="card-body">
            <div class="row">
              <div class="col-md-10 offset-md-1"> 
                <div class="payment-page" id="payment-page" style="background: #fff; padding: 50px 25px; height: 100%;">

                  <div class="row">
                    <div class="col-md-6">
                      <?php 
                      $theme_logo = get_user_meta($firm->uid, 'theme_logo');

                      if(!empty($theme_logo) && $theme_logo != '[]') { ?>
                        <img src="{{asset('storage/app')}}/{{$theme_logo}}" alt="logo" width="200">
                      <?php } else { ?>
                        <img src="{{ asset('assets/img/tila-logo.png') }}" alt="logo" width="200">
                      <?php } ?>
                      <div class="row">
                        <div class="col-md-12">
                          <label>Firm Name : {{$firm->firm_name}} </label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <label>Phone Number : {{$firm->contact_number}} </label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <label>Email : {{$firm->email}} </label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <br><br><br><br>
                      <div class="row">
                        <div class="col-md-12">
                          <label>Client Name : {{$invoice->client_name}}</label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <label>Invoice Number : {{$invoice->id}}</label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <label>Discription : {{$invoice->description}}</label>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                  <br>
                  <h3>Payment Details</h3>
                
                  <div class="payment-info">
                    <div class="clent-info"><span>Total</span>:<span>${{ number_format($invoice->amount, 2) }}</span></div>
                    <div class="clent-info"><span>Paid</span>:<span>${{ number_format($invoice->paid_amount, 2) }}</span></div>
                    <div class="clent-info"><span>Due</span>:<span>${{ number_format(($invoice->amount-$invoice->paid_amount), 2) }}</span></div>
                  </div>
                  
                  <div class="due-payment-box">
                   Due : &nbsp; ${{ number_format(($invoice->amount-$invoice->paid_amount), 2) }}
                  </div>
                
                  <div class="amount-box-auto1">
                    <h4>Payment History</h4> 
                    <div class="table-responsive" style="overflow: hidden;">
                      <table class="table table-striped table-hover table-md">
                        <tbody>
                          <tr>
                            <th>Invoice Number</th>
                            <th>Total Amount</th>
                            <th>Payment Received</th>
                            <th>Outstanding Amount</th>
                            <th>Status</th>
                            <th>Paid Date</th>
                          </tr>
                          <?php 
                          if(!empty($transaction)) {
                            $amt2 = $invoice->amount;
                            $OutstandingA = 0;
                            foreach ($transaction as $k => $v) {
                              $amt2 = $amt2-($v->amount/100);
                             ?>
                            <tr>
                              <td>
                                #{{$invoice->id}}
                              </td>
                              <td>
                                ${{ number_format($invoice->amount, 2) }}
                              </td>
                              <td>
                                ${{ number_format(($v->amount/100), 2) }}
                              </td>
                              <td>
                                ${{ number_format($amt2, 2) }}
                              </td>
                              <td>
                                <?php 
                                if($v->amount) {
                                    echo 'Paid';
                                }
                                else {
                                    echo 'Skipped';
                                }
                                ?>
                              </td>
                              <td>
                                {{ date('m/d/Y', strtotime($v->created_at)) }}
                              </td>
                            </tr>
                          <?php } } ?>
                        </tbody>
                      </table>
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
<!-- Modal -->
<div id="RapidPayModal" class="modal fade" role="dialog" style="position: fixed;">
  <div class="modal-dialog">
    <!-- Pay Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="float: right;
        position: absolute;
        right: 22px;
        top: 15px;
        ">&times;</button>
        <h4 class="modal-title">Rapid Pay</h4>
      </div>
      <div class="modal-body">
        <p>Select One</p>
        <label class="selectgroup-item">
          <input type="radio" name="n_type" value="email" class="selectgroup-input" data-on="{{$lead->email}}" checked> 
          <span class="selectgroup-button">Email</span>
        </label>
        <label class="selectgroup-item">
          <input type="radio" name="n_type" value="text" class="selectgroup-input" data-on="{{$lead->cell_phone}}">
          <span class="selectgroup-button">Text</span>
        </label>
        <input class="form-control contact_info" type="text" name="contact_info" placeholder="Email" value="{{$lead->email}}" required>
        <input type="hidden" class="invoice_id" name="invoice_id" value="">
        <div class="invalid-feedback c_info_err">This is required!</div>
      </div>
      <div class="modal-footer text-right">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary sendbtn">Send</button>
      </div>
    </div>
  </div>
</div>
@endsection
@push('footer_script')
<script src="{{ asset('assets/js/jspdf.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.rapid_pay_btn').on('click', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('.invoice_id').val(id);
    $('#RapidPayModal').modal('show');
  });
  $('input[name="n_type"]').on('click', function(){
    $('.c_info_err').hide();
    var on = $(this).data('on');
    $('.contact_info').val(on);
    var v = $(this).val();
    if(v == 'email') {
      //$('.contact_info').mask('+100000000000');
      $('.contact_info').attr('placeholder', 'Email');
    }
    else {
      //$('.contact_info').mask('+100000000000');
      $('.contact_info').attr('placeholder', 'Cell Phone');
    }
  });
  $('.sendbtn').on('click', function(){
    var r = $('.contact_info').val();
    if(r == '') {
      $('.c_info_err').show();
      return false;
    }
    else {
      $('.c_info_err').hide();
    }

    var id = $('.invoice_id').val();
    var contact_info = $('.contact_info').val();
    var n_type = $('input[name="n_type"]:checked').val();
    var csrf1 = $('input[name="_token"]').val();

    if(n_type == 'text') {
      var filter = /^\+(?:[0-9] ?){6,14}[0-9]$/;
      if (filter.test(contact_info)) { }
      else {
        alert('Phone number is not valid!');
        return false;
      }
    }

    setTimeout(function(){
      var pdf = new jsPDF('p', 'pt', 'a4');
      pdf.addHTML($('#payment-page')[0], 0, 0, function () {
        var pdfdata = btoa(pdf.output());
        $.ajax({
          type:"post",
          url:"{{url('send_invoice')}}",
          data: {pdfdata:pdfdata, id: id, contact_info: contact_info, n_type: n_type, _token: csrf1},
          success:function(res)
          {       
            alert('Invoice send successfully!');
            location.reload();
          }
        });
      });
    }, 10);
  });
  $('.nav-link').on('click', function(){
    var h = $(this).attr('href');
    window.location.href = h;
  });
  $('.datepicker').daterangepicker({
      timePicker: false,
      singleDatePicker: true,
      endDate: moment().startOf('hour').add(32, 'hour'),
      locale: {
        format: 'MM/DD/YYYY'
      },
      minDate: new Date()
  });
  $('.printinvoice').on('click', function(e){
    e.preventDefault();
    var divContents = $(".payment-page").html();
    var printWindow = window.open('', '', 'height=400,width=800');
    printWindow.document.write('<html><head><title>Invoice Details</title>');
    printWindow.document.write('</head><body><div class="payment-page" id="payment-page" style="background: #fff; padding: 50px 25px;">');
    printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"><link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}">');
    printWindow.document.write(divContents);
    printWindow.document.write('</div></body></html>');
    printWindow.document.close();
    printWindow.print();
  });
  $('.send_invoice').on('click', function(e){
    setTimeout(function(){
      // e.preventDefault();
      var pdf = new jsPDF('p', 'pt', 'a4');
      pdf.addHTML($('#payment-page')[0], 0, 0, function () {
         
        var pdfdata = btoa(pdf.output());
        var _token = $('input[name="_token"]').val();
        $.ajax({
          type:"post",
          url:"{{url('sendinvoice')}}/{{$invoice->id}}",
          data: {_token:_token, pdfdata:pdfdata},
          success:function(res)
          {       
            alert('Invoice send successfully!');
            console.log(res);
          }
        });
      });
    }, 10);
  });
});
/*
 * Convert HTML content to PDF
 */
function convert_HTML_To_PDF() {
  setTimeout(function(){
    var pdf = new jsPDF('p', 'pt', 'a4');
    pdf.addHTML($('#payment-page')[0], 0, 0, function () {
       pdf.save('invoice.pdf');
    });
  }, 10);
}
</script>
@endpush