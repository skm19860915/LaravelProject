@extends('layouts.admin-master')

@section('title')
Client Questionnaire Form
@endsection

@push('header_styles')
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>

@endpush 
@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('admin.adminuser.usertask.task_header')
<!--new-header Close-->
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('admin/usertask/createfamilyforms') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="back-btn-new">
            <a href="{{ url('admin/usertask/familyforms') }}/{{$tid}}/{{$id}}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
          <div class="card-header">            
            <h4>Member : {{$family->name}}</h4>
          </div>
          <div class="card-body">
            

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Form Type*
              </label> 
              <div class="col-sm-12 col-md-7 information-form-data">
                <select name="file_data" class="selectpicker with-ajax form-control" required="required" id="ajax-select" data-live-search="true">
                  
                </select>
                <div class="invalid-feedback">Form type is required!</div>
              </div>
            </div> 

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Assign To*
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php
                $cname = $client->first_name;
                if(!empty($client->middle_name)) {
                  $cname .= ' '.$client->middle_name;
                }
                if(!empty($client->last_name)) {
                  $cname .= ' '.$client->last_name;
                }
                ?>
                <input type="hidden" name="family_id" value="{{$id}}"/>
                <input type="text" value="{{$cname}}" class="form-control" readonly="readonly" /> 
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-2 col-lg-2">Case Number
              </label> 
              <div class="col-sm-12 col-md-7">
                <input name="case_id" type="text" class="form-control CaseNumber" readonly="readonly" value="{{$case->id}}">
              </div>
            </div>

            <div class="form-group row mb-4">
              <!--<label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label>--> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <input type="hidden" name="firm_id" value="{{$firm->id}}">
                <input type="hidden" name="task_id" value="{{$admintask->id}}">
                <button class="btn btn-primary" type="submit" name="create_firm_lead">
                <span>Submit</span>
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
@push('footer_script')
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
  <script type="text/javascript">
    
    $(document).ready(function(){
        var _token = $('input[name="_token"]').val();
        var options = {
        ajax          : {
            url     : '{{ url("admin/usertask/getFamilyForms") }}',
            type    : 'POST',
            dataType: 'json',
            data    : {
                        _token:_token
                    }
        },
        locale        : {
            emptyTitle: 'Select and Begin Typing'
        },
        log           : 3,
        preprocessData: function (data) {
            var i, l = data.length, array = [];
            if (l) {
                for (i = 0; i < l; i++) {
                    array.push($.extend(true, data[i], {
                        text : data[i].Name,
                        value: JSON. stringify(data[i].ID),
                        data : {
                            subtext: data[i].ID
                        }
                    }));
                }
            }
            // You must always return a valid array when processing data. The
            // data argument passed is a clone and cannot be modified directly.
            return array;
        }
    };

    $('.selectpicker').selectpicker().filter('.with-ajax').ajaxSelectPicker(options);
    var v = '{{$client->user_id}}';
    $.ajax({
     type:"get",
     url:"{{ url('firm/forms/client_Cases') }}/"+v,
     success:function(res)
     {       
      if(res)
      {
        $(".CaseNumber").empty();
        $(".CaseNumber").append('<option>Select One</option>');
        $.each(res,function(key,value){
          $(".CaseNumber").append('<option value="'+key+'">'+value+'</option>');
        });
      }
    }

  });
    });
  </script>
  <style type="text/css">
 .bootstrap-select .dropdown-menu li small {
    display: none;
  }   
  </style>
@endpush
