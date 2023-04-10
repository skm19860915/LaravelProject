@extends('firmlayouts.admin-master')

@section('title')
View client
@endsection

@section('content')
<section class="section client-listing-details">

<!--new-header open-->

 @include('firmadmin.client.client_header')
    
<!--new-header Close-->
  
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">       
          
          <div class="card-body">
          
          <div class="profile-new-client">
           <h2>Profile</h2>
           <div class="profle-text-section">
            <div class="row">
            
             <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="profle-text-user">
               <h4>General Information</h4>               
               <div class="info-text-general"><span>First Name</span> {{ $client->first_name }}</div>
               <div class="info-text-general"><span>Middle Name</span> {{ $client->middle_name }}</div>
               <div class="info-text-general"><span>Last Name</span> {{ $client->last_name }}</div>
               <div class="info-text-general"><span>Contact Email</span> {{ $client->mailing_address }}</div>
               <div class="info-text-general"><span>Phone Number</span> {{ $client->cell_phone }}</div>
               <div class="info-text-general"><span>Language</span> {{ $client->language }}</div>               
               <div class="info-text-general"><span>Date Of Birth</span> {{ $client->dob }}</div>
               <div class="info-text-general"><span>Previous Name</span> {{ $client->previous_name }}</div>
               <div class="info-text-general"><span>Alien Number</span> {{ $client->alien_number }}</div>
               <div class="info-text-general"><span>Social Security No.</span> {{ $client->Social_security_number }}</div>
               <div class="info-text-general"><span>Birth Country</span> <?php echo isset($client->birth_address->country) ? getCountryName($client->birth_address->country) : ''; ?></div>
               <div class="info-text-general"><span>Birth State</span> <?php echo isset($client->birth_address->state) ? getStateName($client->birth_address->state) : ''; ?></div>
               <div class="info-text-general"><span>Birth City</span> <?php echo isset($client->birth_address->city) ?  getCityName($client->birth_address->city) : ''; ?></div>
               <div class="info-text-general"><span>Gender</span>
                <?php 
                if($client->gender == 1) {
                  echo 'Male';
                }
                else if($client->gender == 2) {
                  echo 'Female';
                }
                else if($client->gender == 3) {
                  echo 'Transgender';
                }
                ?>
               </div>
              </div>
             </div>
             
             <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="profle-text-user">
               <h4>General Information</h4>               
               <div class="info-text-general"><span>Client Type</span> {{ $client->type }}</div>
               <div class="info-text-general"><span>Portal Access</span> <?php echo $retVal = ($client->is_portal_access == 1) ? "YES" : "NO" ; ?></div>
               <div class="info-text-general"><span>Detained</span> <?php echo $retVal = ($client->is_detained == 1) ? "YES" : "NO" ; ?></div>
               <div class="info-text-general"><span>Deported</span> <?php echo $retVal = ($client->is_deported == 1) ? "YES" : "NO" ; ?></div>
               <div class="info-text-general"><span>Outside Of Us ?</span> <?php echo $retVal = ($client->is_outside_us == 1) ? "YES" : "NO" ; ?></div>
               
               <div class="info-text-general"><span>Client Email</span> {{ $client->email }}</div>
               <div class="info-text-general"><span>Legal Name</span> {{ $client->full_legal_name }}</div>
               <?php if($client->client_aliases) { 
              $client_aliases1 = json_decode($client->client_aliases); ?>
              @foreach($client_aliases1 as $client_aliases)
               <div class="info-text-general"><span>Client Aliases</span> {{$client_aliases}}</div>
               
               @endforeach
              <?php } else { ?>
              <div class="info-text-general"><span>Client Aliases</span></div>
              <?php } ?>
              <div class="info-text-general"><span>Eye Color</span> {{ $client->eye_color }}</div>
              <div class="info-text-general"><span>Hair Color</span> {{ $client->hair_color }}</div>
              <div class="info-text-general"><span>Client height</span> {{ $client->height }}</div>
              <div class="info-text-general"><span>Client Weight</span> {{ $client->weight }}</div>
              <div class="info-text-general"><span>Client Race</span> {{ $client->race }}</div>
              <div class="info-text-general"><span>Client Ethnicity</span> {{ $client->ethnicity }}</div>
              </div>
             </div>
             
             <div class="col-md-4 col-sm-6 col-xs-12">
              <div class="profle-text-user address-user">
               <h4>Address</h4>       
               <?php $residence_address = json_decode($client->residence_address); ?>        
               <div class="info-text-general"><span>Street Name</span>
               <?php if(!empty($residence_address->address)) { echo $residence_address->address; } ?></div> 
               <div class="info-text-general"><span>City Name</span>
               <?php if(!empty($residence_address->city)) { echo getCityName($residence_address->city); } ?> </div> 
               <div class="info-text-general"><span>State</span>
               <?php if(!empty($residence_address->state)) { echo getStateName($residence_address->state); } ?> </div>
               <div class="info-text-general"><span>Country</span>
               <?php if(!empty($residence_address->country)) { echo getCountryName($residence_address->country); } ?> </div>              
              </div>
             </div>
             
            </div>
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