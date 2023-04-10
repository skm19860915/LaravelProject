@extends('firmlayouts.admin-master')

@section('title')
Show client notes
@endsection

@section('content')

<section class="section">
  <div class="section-header">
    <h1>Manage Client Notes</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item">
        <a href="{{route('firm.admindashboard')}}">Dashboard</a>
      </div>
      <div class="breadcrumb-item">
        <a href="{{route('firm.client')}}">client</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">{{$client_name->first_name}} {{$client_name->middle_name}} {{$client_name->last_name}}</a>
      </div>
      <div class="breadcrumb-item">
        <a href="#">Nots Show</a>
      </div>
    </div>
  </div>
  <div class="section-body">
       
     <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Client Notes</h4>
            <!-- <div class="card-header-action">
              <a href="" class="btn btn-primary trigger--fire-modal-2" id="fire-modal-2">Add <i class="fas fa-plus"></i></a>
            </div> -->
          </div>

          <div class="card-body">
            <div class="table-responsive table-invoice">
              <table class="table table table-bordered table-striped"  id="table" >
                <thead>
                  <tr>
                   <th> ID </th>
                   <th> Notes </th>
                   <th> Created By </th>
                   <th> create date </th>
                  </tr>
                </thead>
                @if ($notes_list->isEmpty())

                @else
                @foreach ($notes_list as $note)
                <tr>
                  <td>
                    {{$note->id}}
                  </td>
                  <td class="font-weight-600">
                   {{$note->notes}}
                  </td>
                  <td>
                    {{$note->username}}
                  </td>
                  <td>{{$note->created_at}}</td>
                </tr>
                @endforeach
                @endif
              </table>
            </div>
          </div>
        </div>
      </div>
     </div>
  </div>
</section>

@endsection
