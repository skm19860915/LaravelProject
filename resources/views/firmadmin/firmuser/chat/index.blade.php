@extends('firmlayouts.admin-master')

@section('title')
Dashboard
@endsection

@section('content')
<section class="section">
	<div class="section-header">
		<h1>Dashboard</h1>
        <div class="section-header-breadcrumb">
      
        </div>
	</div>

	<div class="section-body">
		
     <div class="row">
     	<div class="col-md-3">
     		<ul class="sidebar-menu">
	     		@if ($users->isEmpty())
								    <p>No users</p>
								@else
								    @foreach ($users as $user)
								        <li><a href="{{ route('firm.userchat.chat', [ 'ids' => auth()->user()->id  . '-' . $user->id ]) }}" class="list-group-item list-group-item-action">{{ $user->name }}</a></li>
								    @endforeach
								@endif
	     	</ul>
     	</div>
     	<div class="col-md-9">
     		<div class="card">
            <div class="card-body text-center">
                <p class="font-weight-bold">You don’t have a chat selected</p>
                <p>Choose a user to continue an existing chat or start a new one.</p>
            </div>
        </div>
     	</div>
     </div>
	</div>
</section>
@endsection
