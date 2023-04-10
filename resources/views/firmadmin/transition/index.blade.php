@extends('firmlayouts.admin-master')

@section('title')
Translation
@endsection

@push('header_styles')
<link  href="{{  asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endpush  

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Translation</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{url('firm/transition')}}/{{$id}}">Translation</a>
      </div>
    </div>
  </div>
  <div class="section-body">
       
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Translations </h4>
            <div class="card-header-action">
              <a href="{{ url('firm/transition/create') }}/{{$id}}" class="btn btn-primary">Add <i class="fas fa-plus"></i></a>
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th> TID </th>
                   <th> Language </th>
                   <th> Can TILA Contact Client Directly? </th>
                   <!-- <th> client_id </th> -->
                   <th> Documents to be translated </th>
                   <th> create date </th>
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

var index_url = "{{url('firm/transition/getDataTransition')}}/{{$id}}";
var srn = 0;
$(window).on('load', function() {
    srn++;
    $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: index_url,
      "order": [[ 0, "desc" ]],
      columns: [
        { data: 'id', name: 'id'},
        { data: 'language', name: 'language'},
        { data: 'can_tila_contact', name: 'can_tila_contact'},
        // { data: 'client_id', name: 'client_id'},
        { data: null,
          render: function(data){

            var f = data.document;
            var url = "{{asset('storage/app')}}/"+f; 
            f = f.replace('client_doc/', '');
            var view = '<a href="'+url+'"  target="_blank">'+f+'</a>';
            return view;
          }, orderable: "false"
        },
        // { data: 'document', name: 'document'},
        { data: 'created_at', name: 'created_at'},
      ],
      initComplete: function(row, data) {
          //$(row).attr('data-user_id', data['id']);
          
      }
    });
});

</script>

@endpush 
