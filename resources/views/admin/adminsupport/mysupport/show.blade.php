@extends('layouts.admin-master')

@section('title')
View Ticket Detail
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Show Ticket Detailt</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('admin.supportdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('admin.mysupport')}}">Mysupport</a>
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
            <h4>Show Ticket Detail</h4>
          </div>
          <div class="card-body">

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ticket ID
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $ticket->id }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $ticket->role_name }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"> User Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $ticket->username }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ticket Message
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $ticket->message }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ticket Priority
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $ticket->priority }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ticket Status
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $ticket->status }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Ticket Created At
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $ticket->created_at }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Supporter name
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo $retVal = ($ticket->supportername == "") ? "NA" : $ticket->supportername; ?>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
  </div>
  
</section>
@endsection

@push('footer_script')
@endpush 