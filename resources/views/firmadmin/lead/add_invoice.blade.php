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
           </div>      
          <div class="card-body">
            <div class="profile-new-client">
              <div class="profle-text-section">
                <form action="{{ url('firm/lead/create_lead_invoice') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
                  <div class="row form-group">
                    <div class="col-sm-6 col-md-6">
                      <div class="row">
                        <label class="col-form-label col-md-4 col-sm-4">Name <span style="color: red"> *</span>
                        </label> 
                        <div class="col-sm-6 col-md-6">
                          <input type="text" placeholder="Name" name="name" class="form-control" value="{{$lead->name.' '.$lead->last_name}}" required="required"> 
                          <div class="invalid-feedback">Name is required!</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-sm-6 col-md-6">
                      <div class="row">
                        <label class="col-form-label col-md-4 col-sm-4">Description
                        </label> 
                        <div class="col-sm-6 col-md-6">
                          <input type="text" placeholder="Description" name="description" class="form-control" value="Consultation" > 
                          <div class="invalid-feedback">Description is required!</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-sm-6 col-md-6">
                      <div class="row">
                        <label class="col-form-label col-md-4 col-sm-4">Total Amount <span style="color: red"> *</span>
                        </label> 
                        <div class="col-sm-6 col-md-6">
                          <span class="curruncy_symbol">$</span>
                          <input type="text" placeholder="Total Amount" name="total_amount" class="form-control case_cost" value="" required="required"> 
                          <div class="invalid-feedback">Total Amount is required!</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-sm-6 col-md-6">
                      <div class="row">
                        <label class="col-form-label col-md-4 col-sm-4">Due Date <span style="color: red"> *</span>
                        </label> 
                        <div class="col-sm-6 col-md-6">
                          <input type="text" placeholder="mm/dd/yyyy" name="due_date" class="form-control datepicker" value="" required="required"> 
                          <div class="invalid-feedback">Due Date is required!</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-sm-6 col-md-6">
                      <div class="row">
                        <label class="col-form-label col-md-4 col-sm-4">Destination Account <span style="color: red"> *</span>
                        </label> 
                        <div class="col-sm-6 col-md-6">
                          <select placeholder="Destination Account" name="destination_account" class="form-control" required="required">
                            <option value="">Select</option> 
                            <option value="Operating/Business Account">Operating/Business Account</option> 
                            <option value="Trust Account">Trust Account</option> 
                          </select>
                          <div class="invalid-feedback">Destination Account is required!</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row form-group">
                    <div class="col-sm-6 col-md-6">
                      <div class="row">
                        <label class="col-form-label col-md-4 col-sm-4">Comments
                        </label> 
                        <div class="col-sm-6 col-md-6">
                          <textarea placeholder="Comments" name="comments" class="form-control"></textarea>
                          <div class="invalid-feedback">Comments is required!</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row form-group"> 
                    <div class="col-sm-12 col-md-12">
                      <input type="hidden" name="lead_id" value="{{$lead->id}}">
                      @csrf
                      <button class="btn btn-primary" value="1" type="submit" name="create_firm_lead">
                      <span>Create Invoice</span>
                      </button>
                    </div>
                  </div>
                </form>
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
<script type="text/javascript">
$(document).ready(function(){
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
});
</script>
@endpush