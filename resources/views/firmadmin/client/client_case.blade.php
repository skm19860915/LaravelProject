@extends('firmlayouts.admin-master')

@section('title')
View client
@endsection

@section('content')
<section class="section client-listing-details">

<!--new-header open-->
  @include('firmadmin.client.client_header')
<!--new-header Close-->
  
   <div class="section-body cases-table">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/client') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
           <div class="card-body">
           
             <div class="profile-new-client">
               <h2>Cases</h2>
               <?php 
               $is_add = true;
               $custom_role = get_user_meta(Auth::User()->id, 'custom_role');
               if($firm->account_type == 'VP Services' && $custom_role == '' && Auth::User()->role_id == 5) {
                $is_add = false;
               }
               if($is_add) { ?>
               <a href="{{ url('firm/client/add_new_case/')}}/{{$client->id}}" class="add-task-link">+ Add New Case</a>
               <?php } ?>
               <div class="table-box-task">
                <div class="table-responsive table-invoice">
                   <table class="table table-striped">
                     <thead>
                       <tr>
                         <!-- <th>ID</th>                          -->
                         <th>Case Type</th>
                         <th>Case Category</th>
                         <th>Attorney of Record</th>
                         <th>Assigned VP</th>
                         <th>Opened Date</th>
                         <th>Status</th>
                         <th>Action</th>
                       </tr>
                     </thead>
                     <tbody>
                      <?php
                      if(!empty($case)) {
                        foreach ($case as $k => $v) {
                          $vpuser = 'N/A';
                          if(!empty($v->VP_Assistance)) {
                              $q = "SELECT u1.name from `admintask` as at, `users` as u1 where at.task_type = 'Assign_Case' and at.case_id = '".$v->id."' and u1.id = at.allot_user_id";
                              $cvp = DB::select(DB::raw($q));
                              if(!empty($cvp)) {
                                $vpuser = $cvp[0]->name;
                              }
                          }
                          echo '<tr>
                              <td>'.$v->case_category.'</td>
                             <td>'.$v->case_type.'</td>
                             <td>'.$v->name.'</td>
                             <td>'.$vpuser.'</td>
                             <td>'.date('m/d/Y',strtotime($v->created_at)).'</td>
                             <td><div class="'.GetCaseStatus($v->status).'">'.GetCaseStatus($v->status).'</div></td>
                             <td>
                              <a href="'.url("firm/case/show").'/'.$v->id.'" class="action_btn" data-toggle="tooltip" data-placement="top" title="View Case"><img src="'.url("assets/images/icon").'/Group 557.svg"></a>
                             </td>
                          </tr>';
                        }
                      }
                      ?>
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