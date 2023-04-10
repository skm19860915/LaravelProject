@extends('firmlayouts.admin-master')

@section('title')
Manage Client Files
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link  href="{{  asset('assets/css/daterangepicker.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Manage Client Files</h1>
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
        <a href="#">Client file</a>
      </div>
    </div>
  </div>
  <div class="section-body">
       
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Client Files</h4>
            <div class="card-header-action">
              <a href="" class="btn btn-primary trigger--fire-modal-2" id="fire-modal-2">Add <i class="fas fa-plus"></i></a>
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th> ID </th>
                   <th> Petitioner </th>
                   <th> Beneficiary </th>
                   <th> create date </th>
                   <th> Action </th>
                  </tr>
                </thead>
                @if ($client_files->isEmpty())

                @else
                @foreach ($client_files as $file)
                <tr>
                  <td>
                    {{$file->id}}
                  </td>
                  <td>
                   {{$file->petitioner}}
                  </td>
                  <td>
                    {{$file->beneficiary}}
                  </td>
                  <td>{{$file->created_at}}</td>
                  <td>
                    <a href="#" data-file='<?php echo json_encode($file); ?>' class="btn-primary btn view_file">
                      <i class="fa fa-eye"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
                @endif
              </table>
            </div>
          </div>
        </div>
      </div>
     </div>
  </div>
</section>

 <div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/lead/create_lead_note') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">  
      <div class="col-md-6">
        <label>Petitioner</label>
        <input type="text" name="petitioner" class="form-control" value="">
      </div>
      <div class="col-md-6">
        <label>Beneficiary</label>
        <input type="text" name="beneficiary" class="form-control" value="">
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-6">
        <label>Case Number</label>
        <select required="required" name="case_number" class="form-control">
        @if ($cases->isEmpty())

        @else
        @foreach ($cases as $case)
        <option value="{{ $case->id }}">Case No. {{ $case->id }}</option>
        @endforeach
        @endif
        </select>
      </div>
      <div class="col-md-6">
        <label>Case Type</label>
        <select required="required" name="case_type" class="form-control">
          <option value="1">Monthly</option> 
          <option value="2">Self Managed</option>
        </select>
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-6">
        <label>Case Venue</label>
        <input type="text" name="case_venue" class="form-control" value="">
      </div>
      <div class="col-md-6">
        <label>Sponsor Type</label>
        <input type="text" name="sponsor_type" class="form-control" value="">
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-6">
        <label>Open Date</label>
        <input type="text" name="open_date" class="form-control datepicker" value="">
      </div>
      <div class="col-md-6">
        <label>Staff Assigned</label>
        <select required="required" name="staff_assigned" class="form-control">
        @if ($users->isEmpty())

        @else
        @foreach ($users as $user)
        <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
        @endif
        </select>
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-6">
        <label>Attorney of record</label>
        <select required="required" name="attorney_of_record" class="form-control">
          <option value="1">Yes</option> 
          <option value="0">No</option>
        </select>
      </div>
      <div class="col-md-6">
        <label>VA Assigned</label>
        <select required="required" name="VA_Assigned" class="form-control">
          <option value="1">Yes</option> 
          <option value="0">No</option>
        </select>
      </div>
    </div>
    <br>
    <div class="row">  
      <div class="col-md-12 text-right">
        <input type="hidden" name="client_id" value="{{ $id }}" >  
        @csrf
        <input type="submit" name="save" value="Create Client File" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    <br>
    </form>
  </div>
<input type="hidden" id="fileid" value=""/>
@endsection

@push('footer_script')
<script src="{{  asset('assets/js/daterangepicker.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  setTimeout(function(){
      $('.datepicker').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        singleDatePicker: true,
        timePicker: false,
        timePicker24Hour: false,
      });
  },1000);
  $("#fire-modal-2").fireModal({title: 'Client Files', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var fileid = $('#fileid').val();
      var aurl = "{{ url('firm/client/create_client_file') }}";
      if(fileid != '') {
        var aurl = "{{ url('firm/client/update_client_file') }}";         
      }
      var client_id = $('input[name="client_id"]').val();
      var petitioner = $('input[name="petitioner"]').val();
      var beneficiary = $('input[name="beneficiary"]').val();
      var case_number = $('select[name="case_number"]').val();
      var case_type = $('select[name="case_type"]').val();
      var case_venue = $('input[name="case_venue"]').val();
      var sponsor_type = $('input[name="sponsor_type"]').val();
      var open_date = $('input[name="open_date"]').val();
      var staff_assigned = $('select[name="staff_assigned"]').val();
      var attorney_of_record = $('select[name="attorney_of_record"]').val();
      var VA_Assigned = $('select[name="VA_Assigned"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:aurl,
        data: {
          petitioner:petitioner,
          beneficiary:beneficiary, 
          case_number:case_number,
          case_type:case_type,
          case_venue:case_venue,
          sponsor_type:sponsor_type,
          open_date:open_date,
          staff_assigned:staff_assigned,
          attorney_of_record:attorney_of_record,
          VA_Assigned:VA_Assigned,
          client_id:client_id, 
          _token:_token,
          fileid:fileid
        },
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = window.location.href;
          }
          else {
            alert('Mendatory fields are required!')
          }
          console.log(res);
        }
      });
  });

  $('.view_file').on('click', function(e){
    e.preventDefault();
    $('#fire-modal-2').trigger('click');
    var v = $(this).data('file');
    $('#fire-modal-1 .modal-body form input[name="petitioner"]').val(v.petitioner);
    $('#fire-modal-1 .modal-body form input[name="beneficiary"]').val(v.beneficiary);
    $('#fire-modal-1 .modal-body form input[name="case_venue"]').val(v.case_venue);
    $('#fire-modal-1 .modal-body form input[name="sponsor_type"]').val(v.sponsor_type);
    $('#fire-modal-1 .modal-body form input[name="open_date"]').val(v.open_date);
    $('#fire-modal-1 .modal-body form select[name="case_type"]').val(v.case_type);
    $('#fire-modal-1 .modal-body form select[name="case_number"]').val(v.case_number);
    $('#fire-modal-1 .modal-body form select[name="staff_assigned"]').val(v.staff_assigned);
    $('#fire-modal-1 .modal-body form select[name="attorney_of_record"]').val(v.attorney_of_record);
    $('#fire-modal-1 .modal-body form select[name="VA_Assigned"]').val(v.VA_Assigned);
    $('#fileid').val(v.id);
    $('.saveclientinfo_form').val('Update Client File');
  });

  $('#fire-modal-2').on('click', function(){
    $('#fileid').val('');
    $('#fire-modal-1 .modal-body form input[name="petitioner"]').val('');
    $('#fire-modal-1 .modal-body form input[name="beneficiary"]').val('');
    $('#fire-modal-1 .modal-body form input[name="case_venue"]').val('');
    $('#fire-modal-1 .modal-body form input[name="sponsor_type"]').val('');
    $('#fire-modal-1 .modal-body form input[name="open_date"]').val('');
    $('#fire-modal-1 .modal-body form select[name="case_type"]').val('');
    $('#fire-modal-1 .modal-body form select[name="case_number"]').val('');
    $('#fire-modal-1 .modal-body form select[name="staff_assigned"]').val('');
    $('#fire-modal-1 .modal-body form select[name="attorney_of_record"]').val('');
    $('#fire-modal-1 .modal-body form select[name="VA_Assigned"]').val('');
    $('.saveclientinfo_form').val('Create Client File');
  });
});


//================ Edit user ============//

</script>

@endpush 
