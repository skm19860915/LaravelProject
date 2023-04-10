@extends('firmlayouts.admin-master')

@section('title')
Lead consult amount
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section report-seaction-box lead-report">
    <div class="section-header">
        <h1>Leads</h1>
       <div class="section-header-breadcrumb">
      <div class="report-icon-box"></div> 
    </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                  
                    <div class="card-body form-box-report">

                        <div class="calender-box-new">
                            <form action="#" id="daterange" class="needs-validation" novalidate="">
                              <label for="from">Select Start Date</label>
                              <input type="text" autocomplete="off" placeholder="Select Date Rang" value="<?php echo date('m/d/Y', strtotime("-1 month")) ?>" name="mydatestart" id="mydatestart" class="from">
                              <label for="to">Select end Date</label>     
                              <input type="text" autocomplete="off" placeholder="Select Date Rang" name="mydateend" value="<?php echo date('m/d/Y') ?>" id="mydateend" class="to">  
                               <button class="filter btn btn-primary" value="1" type="submit" name="Search"><img src="{{ url('/') }}/assets/images/icons/right-arrow-white.svg"></button>
 

                        </div>

                        <div class="table-responsive table-invoice">
                            <table class="table table table-bordered table-striped"  id="table" >
                                <thead>
                                    <tr>
                                        <th> Lead Number</th>
                                        <th> Lead Name </th>
                                        <th> Consult Count</th>
                                        <th> Event Date</th>
                                        <th> Lead Status</th>
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
<script src="{{ asset('assets/js/daterangepicker.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript">
    

    $("#daterange").on("submit", function(e){
        e.preventDefault();
        getleadreports();
    });
    getleadreports()
    function getleadreports() {
        var start_date = $('#mydatestart').val();
        var end_date = $('#mydateend').val();
        var index_url = "{{route('firm.report.leads_getData')}}";
        table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            "ajax": {
            "url": index_url,
                    "type": "POST",
                    "data": {
                    _token :' {{ csrf_token()}}',
                            "start_date": start_date,
                            "end_date": end_date,
                    }
            },
            dom: 'lBfrtip',
            buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            columns: [
            { data: 'leadid', name: 'leadid'},
            { data: 'leadname', name: 'leadname'},
            { data: 'coutner', name: 'coutner'},
            {data: 'eventDate', name: 'eventDate'},
            {data: 'Status', name: 'Status'}
            ],
        });
        table.buttons().container().appendTo($('.report-icon-box'));
    }
//================ Edit user ============//
</script>

@endpush
