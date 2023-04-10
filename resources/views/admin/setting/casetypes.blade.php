@extends('layouts.admin-master')

@section('title')
Manage Email Notifications
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
.main-content .section-body .card-body .dataTables_wrapper .table.email_index_table thead tr th:nth-child(3) {
    min-width: auto;
}  
#table input.form-control:read-only {
  background: transparent;
  border: 0;
  padding: 0 0 0 10px;
  height: auto;
  color: #000000;
}
.curruncy_symbol {
  position: absolute;
  left: 30px;
  top: 11px;
} 
.case_cost {
	padding-left: 23px !important;
}
body div#table_filter {
    display: block; 
}
</style>
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Pricing Defaults Per Case Type</h1>
    <div class="section-header-breadcrumb">
      
    </div>
  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          
          <div class="card-body">
            <div class="table-responsive table-invoice casetype-table-admin">
              <table class="table table table-bordered table-striped email_index_table"  id="table" >
                <thead>
                  <tr>
                    <th> Type</th>
                    <th> Category</th>
                    <th> Actual Cost</th>
                    <th> Updated Cost</th>
                    <th> Action</th>
                  </tr>
                  <tbody>
                    <?php 
                    foreach ($CaseTypes as $k => $case) { ?>
                    <tr>
                      <td>
                        {{$case->Case_Category}}
                      </td>
                      <td>
                        {{$case->Case_Type}}
                      </td>
                      <td>
                        ${{ $case->actual_cost }}
                      </td>
                      <td>
                      	${{ $case->VP_Pricing }}
                      </td>
                      <td>
                        <a href="#" class="action_btn edit_cost" title="Edit Cost" data-toggle="tooltip" data-id="{{$case->id}}" data-actual_cost="{{$case->actual_cost}}" data-updated_cost="{{$case->VP_Pricing}}" data-case_type="{{$case->Case_Category}}" data-case_category="{{$case->Case_Type}}"><img src="{{url('assets/images/icon')}}/pencil(1)@2x.png"/></a>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </thead>
              </table>
            </div>
          </div>

        </div>
      </div>
     </div>
  </div>
</section>
<!-- Add Note Modal -->
<div id="CaseCostModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
	  <div class="modal-content">
	    <div class="modal-header">
	      <button type="button" class="close" data-dismiss="modal" style="float: right;
	      position: absolute;
	      right: 22px;
	      top: 15px;
	      ">&times;</button>
	      <h4 class="modal-title">Update Pricing Default</h4>
	    </div>
	    <div class="modal-body">
	      <form action="{{ url('firm/client/add_notes') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
	        <div class="row">  
	          <div class="col-md-4">
	            <label>Case Type</label>
	          </div>
	          <div class="col-md-8">
	          	<input type="text" class="form-control casetype" value="" readonly>
	          </div>
	        </div>
	        <br>
	        <div class="row">  
	          <div class="col-md-4">
	            <label>Case Category</label>
	          </div>
	          <div class="col-md-8">
	          	<input type="text" class="form-control casecategory" value="" readonly>
	          </div>
	        </div>
	        <br>
	        <div class="row">  
	          <div class="col-md-4">
	            <label>Actual Cost</label>
	          </div>
	          <div class="col-md-8">
	          	<input type="text" class="form-control actualcost" value="" readonly>
	          </div>
	        </div>
	        <br>
	        <div class="row">  
	          <div class="col-md-4">
	            <label>Updated Cost</label>
	          </div>
	          <div class="col-md-8" style="position: relative;">
	          	<input type="text" class="form-control updatedcost case_cost" name="VP_Pricing" value="">
	          	<span class="curruncy_symbol">$</span>
	          </div>
	        </div>
	        <div class="row">  
	          <div class="col-md-12 text-right">
	            <input type="hidden" name="id" value="0" >  
	            @csrf
	          </div>
	        </div>
	      </form>
	    </div>
	    <div class="modal-footer text-right">
	      <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
	      <button type="button" class="btn btn-primary save_cost">Save</button>
	    </div>
	  </div>
	</div>
</div>
@csrf
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#table').DataTable();   
    
    $('.save_cost').on('click', function(e){
      e.preventDefault();
      var id = $('#CaseCostModal input[name="id"]').val();
      var cost = $('#CaseCostModal .case_cost').val();
      var _token = $('input[name="_token"').val();
      $.ajax({
        type:"post",
        url:"{{ url('admin/setting/update_case_cost') }}",
        data: {_token: _token, cost: cost, id: id},
        success:function(res)
        {
          res = JSON.parse(res);
          alert(res.msg);
          if (res.status) {
            window.location.href = "{{ url('admin/setting/casetypes') }}";
          }
        }
      });
    });
  });
  $(document).on('click', '.edit_cost', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var actual_cost = $(this).data('actual_cost');
    actual_cost = '$'+actual_cost;
    var updated_cost = $(this).data('updated_cost');
    var Case_Type = $(this).data('case_type');
    var Case_Category = $(this).data('case_category');
    $('#CaseCostModal input.casetype').val(Case_Type);
    $('#CaseCostModal input.casecategory').val(Case_Category);
    $('#CaseCostModal input.actualcost').val(actual_cost);
    $('#CaseCostModal input.updatedcost').val(updated_cost);
    $('#CaseCostModal input[name="id"]').val(id);
    $('#CaseCostModal').modal('show');
  }); 
</script>

@endpush 
