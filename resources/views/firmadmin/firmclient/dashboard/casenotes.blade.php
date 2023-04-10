@extends('firmlayouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section client-listing-details new_task_notes">
<!--new-header open-->
  @include('firmadmin.firmclient.dashboard.client_header')
<!--new-header Close-->
 
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/clientcase') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
          </div>
          <div class="card-body">
            
            <div class="profile-new-client">
             <h2>Notes</h2>
             <!-- <a href="#" class="add-task-link" id="fire-modal-2">+ Add New</a> -->
             
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
        <input type="hidden" name="case_id" value="{{ $case->id }}" >  
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
        url:"{{ url('firm/case/add_case_notes') }}",
        data: {_token:_token, note:note, case_id:case_id},
        success:function(res)
        {       
          res = JSON.parse(res);
          if(res.status) {
            window.location.href = "{{ url('firm/case/case_notes') }}/{{ $case->id }}";
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
