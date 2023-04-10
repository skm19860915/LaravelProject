@extends('firmlayouts.admin-master')

@section('title')
Information Form
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Information Form</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item">
            <a href="{{route('firm.clientdashboard')}}">Dashboard</a>
          </div>
          <div class="breadcrumb-item">
            <a href="{{route('firm.information_update')}}">Information Update</a>
          </div>
        </div>
    </div>
    

    <div class="section-body">
        @if(session()->has('info'))
        <div class="alert alert-primary alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">
                    <span>×</span>
                </button>
                {{ session()->get('info') }}
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="theme_text">Information Form</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive table-invoice">
                            
                            <table class="table table-striped">
                                <tbody><tr>
                                    <th>Form Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                @if ($client_information_forms->isEmpty())

                                @else
                                @foreach ($client_information_forms as $form)
                                <tr>
                                    <td style="text-transform: capitalize;">
                                      <?php echo str_replace('_', ' ', $form->file_type); ?>
                                    </td>
                                    <td class="font-weight-600">
                                        @if($form->status1 == 0)
                                            Incomplete
                                        @elseif($form->status1 == 1)
                                            Complete
                                        @endif
                                    </td>
                                    <td>
                                        @if($form->status1 == 1)
                                            <a href="JavaScript:Void(0);" class="btn btn-primary document_file" data-file="{{asset('storage/app')}}/{{$form->file}}" data-id="{{$form->info_id}}" onclick="load_document(this, '{{$form->information}}')">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @endif
                                        @if($form->status1 == 0)
                                        <a href="JavaScript:Void(0);" class="btn btn-primary document_file" data-file="{{asset('storage/app')}}/{{$form->file}}" data-id="{{$form->info_id}}" onclick="load_document(this, null)">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        @csrf
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </div>
</section>
<div id="PDFTron_container" style="display: none;"></div>
@endsection

@push('footer_script')
    <style type="text/css">
      #PDFTron_container {
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 9999999999;
      }
    </style>
<!-- <link rel="stylesheet" href="{{ asset('assets/pspdf/pwa-example/src/index.css')}}" />
<script src="{{ asset('assets/pspdf/pwa-example/vendor/pspdfkit.js')}}"></script>
<script src="{{ asset('assets/pspdf/pwa-example/src/app.js')}}"></script> -->
<script src="{{ asset('assets/WebViewer/lib/webviewer.min.js')}}"></script>
<script src="{{ asset('assets/WebViewer/samples/old-browser-checker.js')}}"></script>
<script src="{{ asset('assets/WebViewer/samples/forms/form-fields/form-fields.js')}}?v=<?php echo rand(); ?>"></script>
<script type="text/javascript">
function load_document(e, pdf_data) {
    window.PDFDATA = pdf_data;
    window.pdfUrl = e.getAttribute('data-file');
    window.id = e.getAttribute('data-id');
    window.token = document.querySelector('input[name="_token"]').value;
    document.getElementById('PDFTron_container').style.display = 'block';
    WebViewer.getInstance().loadDocument(pdfUrl);   
}
</script>
@endpush 