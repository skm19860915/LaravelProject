@extends('layouts.admin-master')

@section('title')
Manage Firm
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section report-seaction-box expiration-date-repor">
  <div class="section-header">
    <h1>Manage Firm</h1>
    <div class="section-header-breadcrumb">
      <div class="report-icon-box"></div> 
    </div>

  </div>
  <div class="section-body">
        
     <div class="row">
      <div class="col-md-12">
        <div class="card">

          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                    <th>Firm Id</th>
                    <th>Firm Name</th>
                    <th>Account Type</th>
                    <th>Firm Admin Email</th>
                    <th>Admin Name</th>
                    <th>Case Type</th> 
                    <th>Status</th>
                    <th>Action</th>
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



var index_url = "{{route('admin.firm.getData')}}";
var srn = 0;

$(window).on('load', function() {
    srn++;
    var table = $('#table').DataTable({
      
      ajax: index_url,
      dom: 'llBfrtip'
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
      processing: true,
      serverSide: true,
      columns: [
        { data: 'id', name: 'id'},
        { data: 'firm_name', name: 'firm_name'},
        { data: 'account_type', name: 'account_type'},
        { data: 'email', name: 'email'},
        { data: 'firm_admin_name', name: 'firm_admin_name'},
        { data: 'stat', name: 'stat'},
        { data: 'stat', name: 'stat'},
        { data: null,
          render: function(data){

            if(data.status == 1){
              var delete_button = ' <a href="{{url('admin/firm/delete')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-trash"></i></a>';  
            }else{
              var delete_button = ' <a href="{{url('admin/firm/reactive')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-bolt"></i></a>';
            }
            var view_button = ' <a href="{{url('admin/firm/firm_details')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-eye"></i></a>';
              var time_button = ' <a href="{{url('admin/firm/timeline')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-clock"></i></a>';
              var edit_button = ' <a href="{{url('admin/firm/firm_edit')}}/'+data.id+'" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
              return delete_button + view_button + time_button + edit_button;

          }, orderable: "false"
        },
      ],
      /*rowCallback: function(row, data) {
          $(row).attr('data-user_id', data['id']);
      }*/
    });
    table.buttons().container().appendTo($('.report-icon-box'));
 });

//================ Edit user ============//

</script>

@endpush 