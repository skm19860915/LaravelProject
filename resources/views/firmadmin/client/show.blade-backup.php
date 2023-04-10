@extends('firmlayouts.admin-master')

@section('title')
View client
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Show Firm Client</h1>
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
        <a href="#">Show</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
       
          <div class="card-header">
            <!-- <h4>Show Firm Client</h4> -->

            <a href="{{url('firm/client/family')}}/{{ $client->id }}"> 
              <button class="btn btn-primary">Add Family</button>
            </a>&nbsp;&nbsp;&nbsp;

            <a href="{{url('firm/client/view_family')}}/{{ $client->id }}"> 
              <button class="btn btn-primary">View Family</button>
            </a>&nbsp;&nbsp;&nbsp;

            <button class="btn btn-primary trigger--fire-modal-2" id="fire-modal-2">Add Notes</button>
            &nbsp;&nbsp;&nbsp;

            <a href="{{url('firm/client/view_notes')}}/{{ $client->id }}"> 
              <button class="btn btn-primary">View Notes</button>
            </a>&nbsp;&nbsp;&nbsp;
            <a href="{{url('firm/client/client_files')}}/{{ $client->id }}"> 
              <button class="btn btn-primary">Client Files</button>
            </a>&nbsp;&nbsp;&nbsp;
            <a href="{{url('firm/document_request')}}/{{ $client->id }}"> 
              <button class="btn btn-primary">Document Request</button>
            </a>&nbsp;&nbsp;&nbsp;
            <a href="{{ url('firm/forms') }}/{{ $client->id }}"><button class="btn btn-primary">Forms</button></a>
            <!-- &nbsp;&nbsp;&nbsp;
            <a href="{{url('firm/transition')}}/{{ $client->id }}"> 
              <button class="btn btn-primary">Translations</button>
            </a> -->
            &nbsp;&nbsp;&nbsp;
            <a href="{{url('firm/client/text_message')}}/{{ $client->user_id }}"> 
              <button class="btn btn-primary">Text Message</button>
            &nbsp;&nbsp;&nbsp;
            <a href="{{url('firm/client/client_document')}}/{{ $client->id }}"> 
              <button class="btn btn-primary">Client Document</button>
            </a>
          </div>
          <div class="card-body">
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client first Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->first_name }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Middle Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->middle_name }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Last Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->last_name }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Contact Email
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->email }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Cell Phone
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->cell_phone }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Language
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->language }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Type
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->type }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Portal Access
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo $retVal = ($client->is_portal_access == 1) ? "YES" : "NO" ; ?>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Detained
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo $retVal = ($client->is_detained == 1) ? "YES" : "NO" ; ?>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Deported 
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo $retVal = ($client->is_deported == 1) ? "YES" : "NO" ; ?>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Outside Of Us ?
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo $retVal = ($client->is_outside_us == 1) ? "YES" : "NO" ; ?>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Residence Address
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->residence_address }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Mailing Address
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->mailing_address }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Full Legal Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->full_legal_name }}
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Date Of Birth
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->dob }}
              </div>
            </div>

            <?php if($client->client_aliases) { 
              $client_aliases1 = json_decode($client->client_aliases); ?>
              @foreach($client_aliases1 as $client_aliases)
                <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Aliases
                </label> 
                <div class="col-sm-12 col-md-7">
                  {{$client_aliases}}
                </div>
              </div>
              @endforeach
              <?php } else { ?>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Aliases
                </label> 
                <div class="col-sm-12 col-md-7">
                  
                </div>
              </div>
            <?php } ?>
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Previous Name
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->previous_name }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Alien Number
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->alien_number }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Social Security Number
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->Social_security_number }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Birth Country
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php 
                echo isset($client->birth_address->country) ? getCountryName($client->birth_address->country) : ''; ?>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Birth State
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo isset($client->birth_address->state) ? getStateName($client->birth_address->state) : ''; ?>
              </div>
            </div>

            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Birth City
              </label> 
              <div class="col-sm-12 col-md-7">
                <?php echo isset($client->birth_address->city) ?  getCityName($client->birth_address->city) : ''; ?>
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Gender
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->gender }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Eye Color
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->eye_color }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Hair Color
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->hair_color }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client height
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->height }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client weight
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->weight }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client race
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->race }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client ethnicity
              </label> 
              <div class="col-sm-12 col-md-7">
                {{ $client->ethnicity }}
              </div>
            </div>


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Client Image
              </label> 
              <div class="col-sm-12 col-md-7">
                <img src="{{asset('storage/app')}}/{{$client->image_path}}" width="200px" height="200px">
              </div>
            </div> 
 
          </div>
      </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>


<div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">  
      <div class="col-md-12">
        Note 
        <br>
      </div>
      <div class="col-md-12">
        <textarea name="note" class="form-control" style="height: 150px;"></textarea>
      </div>
    </div>
    <div class="row">  
      <div class="col-md-12 text-right">
        <input type="hidden" name="client_id" value="{{ $client->id }}" >  
        @csrf
        <input type="submit" name="save" value="Create Client Note" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>
@endsection


@push('footer_script')

<script type="text/javascript">
$(document).ready(function(){
  $("#fire-modal-2").fireModal({title: 'Add Client Notes', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var client_id = $('input[name="client_id"]').val();
      var note = $('textarea[name="note"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/client/add_notes') }}",
        data: {_token:_token, note:note, client_id:client_id},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/client/view_notes') }}/{{ $client->id }}";
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
<style type="text/css">
  .card .card-header .btn {
    margin-top: 1px;
    padding: 2px 12px;
  }
</style>
@endpush 