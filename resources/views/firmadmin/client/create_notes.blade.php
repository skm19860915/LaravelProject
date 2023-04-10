@extends('firmlayouts.admin-master')

@section('title')
Create client notes
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Create Firm Client Notes</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.client')}}">Client</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">{{$client_name->first_name}} {{$client_name->middle_name}} {{$client_name->last_name}}</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Add client notes</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">
            <h4>Create Client Notes</h4>
          </div>
          <div class="card-body">
            <textarea name="notes" rows="4" cols="50"></textarea>

            <input type="hidden" name="client_id" value="{{$client_id}}">

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <button class="btn btn-primary" type="submit" name="create_firm_user">
                <span>Create Client Notes</span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>
@endsection
