@extends('layouts.admin-master')

@section('title')
Firm Use Report
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section report-seaction-box expiration-date-report">
  <div class="section-header">
    <h1>Financial Report</h1>
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
                <select class="form-control" name="reporttype" id="reporttype">
                    <option value="0">Select Report Type</option>
                    <option value="1">Firms using CMS + VA</option>
                    <option value="2">Firms using VA Only</option>
                    <option value="3">Firms using CMS + Translation services</option>
                    <option value="4">Firms using CMS + VA + Translations</option>
                    <option value="5">Firms only using Translations</option>
                </select>               
            </div>
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped" id="table" >
                <thead>
                  <tr>
                    <th>Firm Id</th>
                    <th>Firm Name</th>
                    <th>Firm Admin Email</th>
                    
                    <th>Payment Date</th>
                    
                    <th>Amount</th>
                    <th>Service </th>
                    
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

    $("#reporttype").on("change", function () {
        var reporttype = $(this).val();
            DataTableShow(reporttype);
        });
        $(document).ready(function(){
            DataTableShow(1);
        })
        function DataTableShow(reporttype) {
            var index_url = "{{route('admin.report.financialgetdate')}}";
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                dom: 'lBfrti',
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
                "ajax": {
                    "url": index_url,
                    "type": "POST",
                    "data": {
                        _token: ' {{ csrf_token()}}',
                        "reporttype": reporttype,
                    }
                },

                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'amount', name: 'amount'},
                    { data: 'paymenttype', name: 'paymenttype'}
                ],
            });
            table.buttons().container().appendTo($('.report-icon-box'));
        }

//================ Edit user ============//

</script>

@endpush 