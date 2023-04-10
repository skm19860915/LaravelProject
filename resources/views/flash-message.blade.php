@if ($message = Session::get('success'))
<div id="myalert" class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>{{ $message }}</strong>
</div>
@endif



@if ($message = Session::get('error'))
<div id="myalert" class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>{{ $message }}</strong>
</div>
@endif



@if ($message = Session::get('warning'))
<div id="myalert" class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>{{ $message }}</strong>
</div>
@endif



@if ($message = Session::get('info'))
<div id="myalert" class="alert alert-info alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<strong>{{ $message }}</strong>
</div>
@endif



@if ($errors->any())
<div id="myalert" class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert">×</button>	
	<!-- Please check the form below for errors -->
    {{ implode('', $errors->all(':message ')) }}
</div>
@endif