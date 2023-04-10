@extends('firmlayouts.admin-master')

@section('title')
Create Case
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Create Firm Case</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.case')}}">Case</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Add Task</a>
      </div>
    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
        <form action="{{ url('firm/case/add_task') }}" method="post" class="needs-validation" enctype="multipart/form-data" novalidate="">
          <div class="card-header">
            <h4>Create Firm Case</h4>
          </div>
          <div class="card-body">


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Set Priority* 
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="" name="priority">
                  <option value="1">Urgent</option>
                  <option value="2">High</option>
                  <option value="3">Medium</option>
                  <option value="4">Low</option>
                </select> 
                <div class="invalid-feedback">Please select Priority Type!</div>
              </div>
            </div> 
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Task*
              </label> 
              <div class="col-sm-12 col-md-7">
                
                <input type="text" name="task" value="" required="required" class="form-control">
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">My Task*
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="required" name="mytask">
                  <option value="">Select</option>
                  <option value="yes">YES</option>
                  <option value="no">NO</option>
                </select> 
                <div class="invalid-feedback">Please select My task</div>
              </div>
            </div> 


            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Assign to Client*
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" required="required" name="client_task" onchange="Assign_Client(this);">
                  <option value="">Select</option>
                  <option value="yes">YES</option>
                  <option value="no">NO</option>
                </select> 
                <div class="invalid-feedback">Please select task to client</div>
              </div>
            </div> 
          
            <div class="form-group row mb-4 assigned_to" style="display: none;">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Who is task being assigned to 
              </label> 
              <div class="col-sm-12 col-md-7">
                <select class="form-control" name="assigned_to">
                  <option value="">Select</option>
                  <option value="Petitioner">Petitioner</option>
                  <option value="Beneficiary">Beneficiary</option>
                  <option value="Both">Both</option>
                </select> 
                <div class="invalid-feedback">Please select My task</div>
              </div>
            </div> 
            
            <div class="form-group row mb-4">
              <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
              </label> 
              <div class="col-sm-12 col-md-7">
                @csrf
                <input type="hidden" name="case_id" class="form-control" value="{{$id}}" /> 
                <button class="btn btn-primary" type="submit" name="">
                  <span>Create task</span>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
        </div>
      </div>
      <!-- <adduser-component></adduser-component> -->
  </div>
</section>
@endsection
<script type="text/javascript">
function Assign_Client(e) {
  var v = e.value;
  var x = document.getElementsByClassName("assigned_to")[0];
  if(v == 'yes') {
    x.style.display = "flex";
  }
  else {
    x.style.display = "none";
  }
}  
</script>
