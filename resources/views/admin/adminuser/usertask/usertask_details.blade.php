@extends('layouts.admin-master')

@section('title')
Edit Firm
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{$firm->firm_name}}</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('admin.userdashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('admin.usertask')}}">User task</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Detail</a>
      </div>
    </div>
  </div>
  <div class="section-body">
  		
     <div class="row">
        <div class="col-md-9">
          <div class="card">
            
          </div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <div class="form-group1" >
                <form action="" method="get">
                  <label>Select Date</label>
                      <input type="text" name="date" value="" placeholder="Select Date" class="form-control datepicker" />
                    </form>
              </div>
            </div>
          </div>
        </div>
     </div>
     <div class="row">
      <div class="col-md-3">
        <div class="hero bg-dark text-white">
          <div class="hero-inner text-center">
            <h2>32</h2>
            <p class="lead">Case Complete</p>
            <i class="text-dark">0</i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="hero bg-dark text-white">
          <div class="hero-inner text-center">
            <h2>10</h2>
            <p class="lead">Case Pending</p>
            <i class="text-dark">0</i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="hero bg-dark text-white">
          <div class="hero-inner text-center">
            <h2>4</h2>
            <p class="lead">Alert</p>
            <i class="text-dark">0</i>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="hero bg-dark text-white">
          <div class="hero-inner text-center">
            <h2>Credit</h2>
            <p class="lead">$5,000</p>
            <i class="text-secondary">used $1,500</i>
          </div>
        </div>
      </div>
     </div>
     <br><br><br>
     <div class="row">
      <div class="col-md-6">
        <table class="table table-striped">
          <tr>
            <td><i class="fa fa-tasks"></i>&nbsp;&nbsp; My Tasks</td>
            <td>2 Today</td>
          </tr>
          <tr>
            <td><i class="fa fa-circle"></i>&nbsp;&nbsp; Form I-130 Need Information 
                  <div>
                    <i class="text-secondary">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Check list sent 4/12/19</i>
                  </div>
                  </td>
            <td>
              <a href="#" class="btn btn-primary">
                <i class="fa fa-eye"></i>
              </a>
            </td>
          </tr>
          <tr>
            <td><i class="fa fa-circle"></i>&nbsp;&nbsp; Waiting on Checklist
                  <div>
                    <i class="text-secondary">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Check list sent 4/12/19</i>
                  </div>
                  </td>
            <td>
              <a href="#" class="btn btn-primary">
                <i class="fa fa-eye"></i>
              </a>
            </td>
          </tr>
          <tr>
            <td><i class="fa fa-circle"></i>&nbsp;&nbsp; Review I-485 for Miguel Angel
                  <div>
                    <i class="text-secondary">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Check list sent 4/12/19</i>
                  </div>
                  </td>
            <td>
              <a href="#" class="btn btn-primary">
                <i class="fa fa-eye"></i>
              </a>
            </td>
          </tr>
        </table>
      </div>
     </div>
  </div>
</section>
@endsection
