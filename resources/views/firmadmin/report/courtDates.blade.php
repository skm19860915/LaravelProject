@extends('firmlayouts.admin-master')

@section('title')
Manage Task
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section report-seaction-box court-dates-report">
    <div class="section-header">
        <h1>Court Dates</h1>
        <div class="section-header-breadcrumb">
          <div class="report-icon-box"></div> 
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    

                    <div class="card-body form-box-report">
                       <div class="calender-box-new"> <label for="from">From</label> 
                        <input type="text" class="from" value="<?php echo date('m/d/Y', strtotime("-6 month")); ?>" name="from">
                        <label for="to">to</label>
                        <input type="text" class="to" value="<?php echo date('m/d/Y', strtotime("+6 month")); ?>" name="to"> 
                        <button class="filter btn btn-primary"  value="courtdate" ><img src="{{ url('/') }}/assets/images/icons/right-arrow-white.svg"></button>
                         </div>
                        <div class="table-responsive table-invoice court-table-width">
                            <table class="table table table-bordered table-striped"  id="table" >
                                <thead>
                                    <tr>
                                        <th>Case Number</th>
                                        <th>Client Name </th>
                                        <th>Client Number</th>
                                        <th>Case Type </th>
                                        <th>Status </th>
                                        <th>Assigned to</th>
                                        <th>Court Dates</th>
                                        <!-- <th> Action</th> -->
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
<script type="text/javascript">
$(".filter ").on("click", function(e){
    e.preventDefault();
    getreportdata();
});
getreportdata();
function getreportdata() {
    var index_url = "{{route('firm.report.courtDates_getData')}}";
    var srn = 0;
    from = $('.from').val();
    to = $('.to').val();
    srn++;
    table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        "ajax": {
                "url": index_url,
                "type": "GET",
                "data": {
                    "start_date": from,
                    "end_date": to,
                }
            },
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        columns: [
            {data: 'id', name: srn},
            {data: 'clientname', name: 'clientname'},
            {data: 'client_id', name: 'client_id'},
            {data: 'case_type', name: 'case_type'},
            {data: 'stat', name: 'stat'},
            {data: 'VP_Assistance', name: 'VP_Assistance'},
            {data: 'CourtDates', name: 'CourtDates'},    
        ],
    });
    table.buttons().container().appendTo($('.report-icon-box'));
}
</script>
@endpush 
