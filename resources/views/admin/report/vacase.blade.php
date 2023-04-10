@extends('layouts.admin-master')

@section('title')
Firm Use Report
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"/>
<link rel="stylesheet" href="{{ asset('assets/css/ajax-bootstrap-select.min.css') }}"/>
@endpush  

@section('content')
<section class="section report-seaction-box expiration-date-repor">
    <div class="section-header">
        <h1>VP Case Report</h1>
        <div class="section-header-breadcrumb">
          <div class="report-icon-box"></div> 
        </div>
    </div>


    <div class="section-body">

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <div style="float: left;" class="country-left-select">
                            <select class="selectpicker" name="firm_name" id="firm_name" style="display: inline-block;" data-live-search="true">
                                <option value="">All</option>
                                <?php 
                                if(!empty($users)) {
                                    foreach ($users as $key => $f) {
                                        echo '<option value="'.$f->id.'">'.$f->name.'</option>';
                                    }
                                }
                                ?>
                            </select>      
                            <select class="selectpicker" name="Case_Type" id="Case_Type" style="display: inline-block;" data-live-search="true">
                                <option value="">All</option>
                                <?php 
                                if(!empty($case_type)) {
                                    foreach ($case_type as $key => $f) {
                                        echo '<option value="'.$f->Case_Type.'">'.$f->Case_Type.'</option>';
                                    }
                                }
                                ?>
                            </select>            
                        </div>
                        <div class="table-responsive table-invoice vpcase-admin-table">
                            <table class="table table table-bordered table-striped" id="table" >
                                <thead>
                                    <tr>
                                        <th>Case ID</th>
                                        <th>Case Type</th>
                                        <th>Firm Name</th>
                                        <th>VP Name</th>
                                        <th>Create Date</th>
                                        <th>Court Dates</th>
                                        <th>Case Status</th>
                                        
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('footer_script')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/ajax-bootstrap-select.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        VaCaseReport();
        $('#firm_name').on('change', function(){
            VaCaseReport();
        });
        $('#Case_Type').on('change', function(){
            VaCaseReport();
        });
    });
    function VaCaseReport() {
        var f = $('#firm_name').val();
        var c = $('#Case_Type').val();
        var index_url = "{{route('admin.report.VaCaseGetDate')}}";
        table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "ajax": {
                "url": index_url,
                "type": "GET",
                "data": {
                    _token: ' {{ csrf_token()}}',
                    "firm": f,
                    "Case_Type": c
                }
            },
            dom: 'lBfrtip',
            buttons: [
        {
            extend:    'copyHtml5',
            text:      '<i class="fa fa-files-o"></i>',
            titleAttr: 'Copy data to clipboard'
        },
        {
            extend:    'csv',
            text:      '<i class="fa fa-files-o"></i>',
            titleAttr: 'Download csv file'
        },
        {
            extend:    'excel',
            text:      '<i class="fa fa-files-o"></i>',
            titleAttr: 'Download excel file'
        },
        {
            extend:    'pdf',
            text:      '<i class="fa fa-files-o"></i>',
            titleAttr: 'Download pdf file'
        },
        {
            extend:    'print',
            text:      '<i class="fa fa-files-o"></i>',
            titleAttr: 'Print data'
        }
      ],
            columns: [
            {data: 'cid', name: 'cid'},
            { data: 'case_type', name: 'case_type'},
            { data: 'fname', name: 'fname'},
            { data: 'vname', name: 'vname'},
            {data: 'crd', name: 'crd'},
            {data:'CourtDates', name: 'CourtDates'},
            { data: 'casestatus', name: 'casestatus'},
            ],
        });
        table.buttons().container().appendTo($('.report-icon-box'));
    }
//================ Edit user ============//

</script>

@endpush 