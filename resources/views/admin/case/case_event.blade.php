@extends('layouts.admin-master')

@section('title')
View client
@endsection

@section('content')
<section class="section client-listing-details task-new-header">

<!--new-header open-->
  @include('admin.case.case_header')
<!--new-header Close-->
  
   <div class="section-body events-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
           <div class="card-body">
           
             <div class="profile-new-client">
               <h2>Events</h2>
               <!-- <a href="{{ url('firm/case/create_event/')}}/{{$case->id}}" class="add-task-link">Create Event</a> -->
               <div class="table-box-task">
                <div class="table-responsive table-invoice">
                   <table class="table table-striped">
                     <thead>
                       <tr>
                         <!-- <th>ID</th> -->
                         <th>Title</th>
                         <th>Event Type</th>
                         <th>Start Date</th>
                         <th>End Date</th>
                         <th>Status</th>
                       </tr>
                     </thead>
                     <tbody>
                      <?php
                      if(!empty($event)) {
                        foreach ($event as $k => $v) {
                          echo '<tr>
                             <td>'.$v->event_title.'</td>
                             <td>'.$v->event_type.'</td>
                             <td>'.$v->s_date.' <span class="text-gray">'.$v->s_time.'</span></td>
                             <td>'.$v->e_date.' <span class="text-gray">'.$v->e_time.'</span></td>';
                            if($v->status) {
                              echo '<td><div class="Active">Completed</div></td>';
                            } 
                            else {
                              echo '<td><div class="Closed - Lost">Incompleted</div></td>';
                            }
                          echo '</tr>';
                        }
                      }
                      ?>
                      <!-- <div class="Closed - Lost">Open</div> -->
                     </tbody>
                   </table>
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
        Note 
        <br>
      </div>
      <div class="col-md-12">
        <textarea name="note" class="form-control" style="height: 150px;"></textarea>
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
            window.location.href = "{{ url('firm/client/view_notes') }}/{{ $case->id }}";
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