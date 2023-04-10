@extends('firmlayouts.admin-master')

@section('title')
Edit Notifications
@endsection

@section('content')
<section class="section">
  <div class="section-header edit-message-sms">
    <?php $etxt = 'Email'; if($record->category == 'SMS') { $etxt = 'Sms'; } ?>
    <h1><a href="#"><span>{{$etxt}} Notifications /</span></a> Edit</h1>
    <div class="section-header-breadcrumb">
     
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/setting/update_message') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          
          <div class="card-body">
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2"> Category
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" readonly="" value="{{$record->category}}"  class="form-control"> 
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2"> Title
              </label> 
              <div class="col-sm-12 col-md-7">
                <input type="text" readonly="" value="{{$record->title}}"  class="form-control"> 
              </div>
            </div>


           	<div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Message
              </label> 
              <div class="col-sm-12 col-md-7">               
                <textarea name="message" class="summernote-simple form-control" required="">{{$record->message}}</textarea> 
                <div class="invalid-feedback"> Message is required !</div>
              </div>

            </div>


            <div class="form-group row mb-4" style="display: none;">
              <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2"> Status
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="status">
                  <option <?php if($record->status == 1){echo "selected"; }?> value="1">Active</option>
                  <option <?php if($record->status == 0){echo "selected"; }?> value="0">Inactive</option>
                </select> 
              </div>
            </div>

            <input type="hidden" name="category" value="{{$record->category}}"> 
            <input type="hidden" name="setting_id" value="{{$record->id}}"> 

            <div class="form-group row mb-4">
             <!-- <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> -->
              <div class="col-sm-12 col-md-7">
                @csrf
                <button class="btn btn-primary" type="submit" name="create_firm_user">
                  <span>Update</span>
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
