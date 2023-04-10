@extends('firmlayouts.admin-master')

@section('title')
Manage Lead Notes
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <div class="breadcrumb-item">
      <a href="{{route('firm.lead')}}">Lead</a>
    </div>
    <div class="breadcrumb-item">
      <h1>Notes</h1>
    </div>
    <div class="section-header-breadcrumb">
      <!-- <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      
      <div class="breadcrumb-item">
        <a href="#">{{$lead->name}} {{$lead->last_name}}</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Notes</a>
      </div> -->
      <a href="" class="btn btn-primary trigger--fire-modal-2" id="fire-modal-2" style="width: auto; padding: 0 18px;"><i class="fas fa-plus"></i> Add new</a>
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
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th> ID </th>
                   <th> Notes </th>
                   <th> Created By </th>
                   <th> create date </th>
                  </tr>
                </thead>
                @if ($lead_notes->isEmpty())

                @else
                @foreach ($lead_notes as $note)
                <tr>
                  <td>
                    {{$note->id}}
                  </td>
                  <td class="font-weight-600">
                   {{$note->notes}}
                  </td>
                  <td>
                    {{$note->username}}
                  </td>
                  <td>{{$note->created_at}}</td>
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
      <div class="col-md-12">
        Note 
        <br>
      </div>
      <div class="col-md-12">
        <textarea name="note" class="form-control" style="height: 150px;" placeholder="Write here..."></textarea>
      </div>
    </div>
    <div class="row">  
      <div class="col-md-12 text-right">
        <input type="hidden" name="lead_id" value="{{ $id }}" >  
        @csrf
        <input type="submit" name="save" value="Create Lead Note" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>

@endsection

@push('footer_script')

<script type="text/javascript">
$(document).ready(function(){
  $("#fire-modal-2").fireModal({title: 'Add Note', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var lead_id = $('input[name="lead_id"]').val();
      var note = $('textarea[name="note"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/lead/create_lead_note') }}",
        data: {note:note, lead_id:lead_id, _token:_token},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/lead/notes') }}/{{$id}}";
          }
          else {
            alert('Mendatory fields are required!')
          }
          console.log(res);
        }
      });
    });
});


//================ Edit user ============//

</script>

@endpush 
