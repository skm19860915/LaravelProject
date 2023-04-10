@extends('firmlayouts.client-family')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details new_task_notes">
<!--new-header open-->
 @include('firmclientfamily.cases.include.case_header')
<!--new-header Close-->
 
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            
            <div class="profile-new-client">
             <h2>Notes</h2>
             <a href="#" class="add-task-link" id="fire-modal-2">+ Add New</a>
             
             <div class="notes-list-box">
              <div class="row">
                <?php 
              if(!empty($notes_list)) {
                foreach ($notes_list as $k => $v) { ?>
                  <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="notas-box-border">
                     <div class="notes-text-box">
                      <div class="nots-header-box">
                       <div class="user-img" style="background-image: url(&quot;{{ url('/') }}/assets/img/avatar/avatar-1.png&quot;);"></div>
                       <div class="user-texct">
                        <h3><?php echo $v->username; ?></h3>
                        <h4>On <?php echo date('M Y', strtotime($v->created_at)); ?></h4>
                       </div>
                      </div>
                      <p><?php echo $v->notes; ?></p>
                     </div>
                     <div class="nots-border-id">
                      #<?php echo $v->id; ?>
                     </div>
                    </div>
                   </div>
                <?php    
                }
              }
              ?>
               </div>
              </div>
                            
           </div>
            
          </div>
        </div>
      </div>
     </div>
  </div>
</section>
<div class="modalformpart" id="modal-form-part" style="display: none;">
    <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
    <div class="row">  
      <div class="col-md-12">
        <textarea name="note" class="form-control" placeholder="Write your note here..." style="height: 150px;"></textarea>
      </div>
    </div>
    <div class="row">  
      <div class="col-md-12 text-right">
        <input type="hidden" name="case_id" value="{{ $case->case_id }}" >  
        @csrf
        <input type="submit" name="save" value="Create Client Note" class="btn btn-primary saveclientinfo_form"/>
      </div>
    </div>
    </form>
  </div>
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $("#fire-modal-2").fireModal({title: 'Add A New Note', body: $("#modal-form-part"), center: true});

  $('.saveclientinfo_form').on('click', function(e){
      e.preventDefault();
      var case_id = $('input[name="case_id"]').val();
      var note = $('textarea[name="note"]').val();
      var _token = $('input[name="_token"]').val();
      $.ajax({
        type:"post",
        url:"{{ url('firm/clientfamilydashboard/add_family_notes') }}",
        data: {_token:_token, note:note, case_id:case_id},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/clientfamilydashboard/familynotes') }}/{{ $case->case_id }}";
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
