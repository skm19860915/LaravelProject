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
          <div class="card-header">
            <div class="back-btn-new" style="padding-top: 0; padding-left: 0;">
              <a href="{{ url('firm/client') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
             </div>
           </div>
           <div class="card-body">
            <div class="form-overview-new">
            
             <div class="overview-numbers">
             
              <div class="overview-border-number">
               <h3>{{$data['total_case']}}</h3>
               <p>Total Cases</p>
              </div>
              
              <div class="overview-border-number">
               <h3>{{$data['total_task']}}</h3>
               <p>Total Tasks</p>
              </div>
              
              <!-- <div class="overview-border-number">
               <h3>{{$data['total_note']}}</h3>
               <p>Total Notes</p>
              </div> -->
              
              

              <div class="overview-border-number">
               <h3>{{$data['totla_forms']}}</h3>
               <p>Total Forms</p>
              </div>

              <div class="overview-border-number">
               <h3>{{$data['total_document']}}</h3>
               <p>Total Documents</p>
              </div>
              
              <?php if($firm->account_type == 'CMS') { ?>
              <div class="overview-border-number">
               <h3>{{$data['total_event']}}</h3>
               <p>Total Events</p>
              </div>

              <div class="overview-border-number width-new-billing">
               <h3>$<?php echo number_format($data['total_billing'], 2); ?></h3>
               <p>Life Time Value</p>
              </div>
              <?php } ?>
             </div>
             
             <div class="overview-table-box">
              <h3>Recent Tasks</h3>
              <div class="tabel-box-recent">
                <div class="table-responsive table-invoice">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <!-- <th style="display: none;">Task ID</th> -->
                          <th>Tasks</th>
                          <th>Due Date</th>
                          <th>Status</th>                          
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(!empty($task)) {
                            foreach ($task as $k => $v) { ?>
                           <tr>
                             <!-- <td><a href="#">#<?php echo $v->id; ?></a></td> -->
                             <td><?php echo $v->description; ?></td>
                             <td><?php echo $v->e_date; ?> <span class="text-gray"><?php echo $v->e_time; ?></span></td>
                             <td>
                              <?php 
                                if($v->status) {
                                  echo '<div class="Active">Completed</div>';
                                }
                                else {
                                  echo '<div class="Closed - Lost">Incompleted</div>';
                                }
                              ?>
                              
                            </td>
                           </tr>
                         <?php } } ?>
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