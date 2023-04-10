@extends('firmlayouts.admin-master')

@section('title')
Cases
@endsection
@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
  #table tbody tr td:nth-child(1) {
    display: none;
  }
</style>
@endpush 
@section('content')
<section class="section firmclient-case">
    <div class="section-header">
        <h1>Cases</h1>
        <div class="section-header-breadcrumb">
          
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
                    <div class="card-body">
                        <div class="table-responsive table-invoice">
                            <table class="table table table-bordered table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th style="display: none;">Case Number</th>
                                        <th>Case Type</th>
                                        <th>Firm Name</th>
                                        <th>Create Date</th>
                                        <th>Courte Date</th>
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
<script type="text/javascript">
$(document).ready( function () {
    var index_url = "{{url('firm/clientcase/getClientCaseData')}}";
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'case_type', name: 'case_type' },
        { data: 'firm_name', name: 'firm_name'},
        { data: 'case_created_at', name: 'case_created_at'},
        { data: 'CourtDates', name: 'CourtDates'},
        { data: 'case_status', name: 'case_status'},
        { data: null,
          render: function(data){
            var view_button = ' <a href="{{url('firm/clientcase/show')}}/'+data.case_id+'" class="action_btn"><img src="{{ url('/') }}/assets/images/icon/Group 557.svg"></a>';
            return view_button;
          }, orderable: "false"
        },
      ],
    });
});
</script>
@endpush 