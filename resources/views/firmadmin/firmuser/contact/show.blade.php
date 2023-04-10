@extends('firmlayouts.admin-master')

@section('title')
Contact
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Firm Contact</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.firmuserdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.firmcontacts')}}">Contact</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Show</a>
      </div>
    </div>
  </div>
  <div class="section-body">
    
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Firm Contact</h4>
          </div>
          <div class="card-body">

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->name }}
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Contact Number
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->cell_phone }}
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client DOB
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->dob }}
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Petitioner Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->full_legal_name }}
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Petitioner DOB
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->dob }}
              </div>
            </div> 


            



            



    
            

 


            




            
            
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4>Send Message</h4>
          </div>
          <div class="card-body">
            <form action="{{url('firm/firmcontacts/send_message')}}" method="post">
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Email
                </label> 
                <div class="col-sm-12 col-md-7">
                  <label class="custom-switch mt-2">
                    <input type="checkbox" name="is_email_send" class="custom-switch-input" checked value="1">
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description">I agree with terms and conditions</span>
                  </label>
                </div>
              </div> 
            
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client SMS
                </label> 
                <div class="col-sm-12 col-md-7">
                  
                    <textarea class="summernote-simple" name="message" placeholder="Write Message Here...."></textarea>
                    @csrf
                    <input type="hidden" name="client_id" value="{{ $client->id }}">
                    <input type="hidden" name="user_id" value="{{ $client->user_id }}">
                    <button type="submit" name="send_message" class="btn btn-primary">Send Message</button>
                  
                </div>
              </div> 
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection


@push('footer_script')

<script src="{{ asset('assets/js/summernote-bs4.js') }}"></script>

<script type="text/javascript">

</script>
@endpush