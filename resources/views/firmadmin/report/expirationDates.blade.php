@extends('firmlayouts.admin-master')

@section('title')
Case Expiration report
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section report-seaction-box expiration-date-report">
  <div class="section-header">
    <h1>Case Expiration Report</h1>
    <div class="section-header-breadcrumb">
      <div class="report-icon-box"></div> 
    </div>
  </div>
  <div class="section-body">
     <div class="row">
      <div class="col-md-12">
        <div class="card">

          <div class="card-body">
            <div class="table-responsive table-invoice expiration-table-width">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th> Case Number</th>
                   <th> Client Name </th>
                   <th> Client Number</th>
                   <th> Case Type </th>
                   <th> Event Date</th>
                   <th> Status </th>
                  <th> Assigned to</th>
                  <!-- <th></th> -->
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

var index_url = "{{route('firm.report.expirationDates_getData')}}";
var srn = 0;
// $( window ).load(function() {
    srn++;
    var table = $('#table').DataTable({
      processing: true,      
      serverSide: true,
      ajax: index_url,
      dom: 'lBfrtip',
      buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
      columns: [
        { data: 'id', name: srn},
        { data: 'clientname', name: 'clientname'},
        { data: 'client_id', name: 'client_id'},
        { data: 'caseType', name: 'caseType'},
        { data: 'stat', name: 'stat'},
        { data: 'stat', name: 'stat'},
        { data: 'VP_Assistance', name: 'VP_Assistance'},
        // { data: null,  render:function(data){ return '<a target="_new" href="/firm/case/show/'+data.id+'" class="action_btn"><img src="{{ url('/') }}/assets/images/icon/pencil(1)@2x.png"></a>'}},
            
        ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
    table.buttons().container().appendTo($('.report-icon-box'));
 // });

//================ Edit user ============//

</script>

@endpush 
