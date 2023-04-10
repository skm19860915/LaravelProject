@extends('layouts.admin-master')

@section('title')
Firm Timeline
@endsection

@push('header_styles')

@endpush  

@section('content')


<section class="section">
  <div class="section-header">
    <h1><a href="{{ url('admin/firm') }}"><span>Firm /</span></a> Firm Timeline</h1>
    <div class="section-header-breadcrumb">

    </div>
  </div>
  <div class="section-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="back-btn-new">
              <a href="{{ url('admin/firm') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20.2" height="13.772" viewBox="0 0 20.2 13.772"><g transform="translate(20.2 -129.506) rotate(90)"><g transform="translate(135.408)"><g transform="translate(0)"><path d="M238.913,0a.984.984,0,0,0-.984.984V18.921a.984.984,0,0,0,1.968,0V.984A.984.984,0,0,0,238.913,0Z" transform="translate(-237.929)"/></g></g><g transform="translate(129.506 12.723)"><g transform="translate(0)"><path d="M143.013,234.021a.984.984,0,0,0-1.39-.048l-5.231,4.883-5.231-4.882a.984.984,0,0,0-1.343,1.438l5.9,5.509a.983.983,0,0,0,1.342,0l5.9-5.509A.984.984,0,0,0,143.013,234.021Z" transform="translate(-129.506 -233.709)"/></g></g></g></svg> Back</a>
          </div>
            <div class="card-body">
            <h2 class="section-title"><?php if(isset($firmlog[0]->created_at)){ echo $firmlog[0]->created_at; } ?></h2>
            <div class="row">
              <div class="col-12">
                <div class="activities">
                  
                  <?php foreach ($firmlog as $key => $value) { ?>
                    
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
    </div>
</section>
@endsection

@push('footer_script')



@endpush 
