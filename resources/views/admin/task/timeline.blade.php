@extends('layouts.admin-master')

@section('title')
Timeline Task
@endsection

@push('header_styles')

@endpush  

@section('content')


<section class="section">
  <div class="section-header">
    <h1>Timeline Task</h1>
    <div class="section-header-breadcrumb">
      <!-- <div class="breadcrumb-item">
        <a href="{{route('admin.dashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('admin.task')}}">Task</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Timeline</a>
      </div> -->
    </div>

  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
          <div class="back-btn-new">
            <a href="{{ url('admin/task') }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
           </div>
            <div class="section-body">
            <h2 class="section-title"><?php if(isset($caselog[0]->created_at)){ echo $caselog[0]->created_at; } ?></h2>
            <div class="row">
              <div class="col-12">
                <div class="activities">
                  
                  <?php foreach ($caselog as $key => $value) { ?>
                    
                    <div class="activity">
                    <div class="activity-icon bg-primary text-white shadow-primary">
                      <i class="fas fa-comment-alt"></i>
                    </div>
                    <div class="activity-detail">
                      <div class="mb-2">
                        <b>{{$value->created_at}}</b>
                      </div>
                      <p>{{$value->message}}</p>
                    </div>
                  </div>

                  <?php } ?>
        
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
@endsection

@push('footer_script')



@endpush 
