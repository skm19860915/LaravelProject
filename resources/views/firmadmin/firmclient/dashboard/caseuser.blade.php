@extends('firmlayouts.admin-master')

@section('title')
Client Cases
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Users</h1>
        <div class="section-header-breadcrumb">
          <div class="breadcrumb-item">
            <a href="{{route('firm.clientdashboard')}}">Dashboard</a>
          </div>
          <div class="breadcrumb-item">
            <a href="{{route('firm.caseuser')}}">Case User</a>
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
                        <h4 class="theme_text">Users</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive table-invoice">
                            <table class="table table-striped">
                                <tbody><tr>
                                    <th>User Name</th>
                                    <th>Relationship</th>
                                </tr>
                                @if ($cases->isEmpty())

                                @else
                                @foreach ($cases as $case)
                                <tr>
                                    <td>{{$case->name}}
                                        <!-- <a href="{{url('firm/clientcase/show')}}/{{$case->case_id}}"></a> -->
                                    </td>
                                    <td class="font-weight-600">
                                        @if($case->role_id == 1)
                                            TILA Admin
                                        @elseif($case->role_id == 2)
                                            TILA VA
                                        @elseif($case->role_id == 3)
                                            TILA Support
                                        @elseif($case->role_id == 4)
                                            Firm Admin  
                                        @elseif($case->role_id == 5)
                                            Firm User
                                        @elseif($case->role_id == 6)
                                            Firm Client
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </div>
</section>
@endsection
