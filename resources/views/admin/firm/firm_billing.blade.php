@extends('layouts.admin-master')

@section('title')
Firm Detail
@endsection

@push('header_styles')
<style type="text/css">
  .overview-border-number {
    padding-right: 15px;
    text-align: center;
    margin-right: 11px;
  }
</style>
@endpush 

@section('content')
<section class="section client-listing-details">
  <div class="section-header">
    <h1><a href="{{ url('admin/firm') }}"><span>Firm /</span></a> Billing</h1>
  </div>
  <!--new-header open-->
  @include('admin.firm.firm_header')
  <!--new-header Close-->
  
  <div class="section-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="back-btn-new">
            <a href="{{ url('admin/firm') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
          </div>
          <div class="card-body">
            <br>
            <h3>Account Details</h3>
            <div class="row">
              <div class="col-md-5 col-sm-6">
                <?php if($firm->account_type == "CMS") { ?>
                  <div class="clent-info">
                    <span>Type</span>:
                    <span>
                      {{ $firm->account_type }} Subscription
                    </span>
                  </div>
                  <div class="clent-info">
                    <span>Billing Cycle</span>:
                    <span>
                      Monthly
                    </span>
                  </div>
                  <div class="clent-info">
                    <span>Number of Users</span>:
                    <span>
                      {{$data['total_user']}}
                    </span>
                  </div>
                <?php } else { ?>
                  <div class="clent-info">
                    <span>Type</span>:
                    <span>
                      {{ $firm->account_type }}
                    </span>
                  </div>
                <?php } ?>
                <div class="clent-info">
                  <span>Joined Date</span>:
                  <span>
                    {{ date('M d, Y', strtotime($firm->created_at)) }}
                  </span>
                </div>
              </div>
            </div>
            <br>
            <h3>Payment Details</h3>
            <div class="row">
              <div class="col-md-5 col-sm-6">
                <?php 
                if(empty($data['payment_info'])) {
                  echo '<div class="clent-info">
                          <span>No payment details found!</span>
                        </div>';
                } else { ?>
                <div class="clent-info">
                  <span>Credit Card</span>:
                  <span>
                    <?php
                    if(!empty($data['payment_info'])) {
                      echo '********'.$data['payment_info']->payment_method_details->card->last4;
                    }
                    ?>
                  </span>
                </div>
                <div class="clent-info">
                  <span>Expiration Date</span>:
                  <span>
                    <?php
                    if(!empty($data['payment_info'])) {
                      echo $data['payment_info']->payment_method_details->card->exp_month.'/'.$data['payment_info']->payment_method_details->card->exp_year;
                    }
                    ?>
                  </span>
                </div>
                <div class="clent-info">
                  <span>Name on Credit Card</span>:
                  <span>
                    {{ $firmadmin->name }}
                  </span>
                </div>
                <div class="clent-info">
                  <span>Billing Address</span>:
                  <span>
                    
                  </span>
                </div>
                <div class="clent-info">
                  <span>Contact Phone number</span>:
                  <span>
                    {{ $firmadmin->contact_number }}
                  </span>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
