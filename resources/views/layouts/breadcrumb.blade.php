<div class="section-header-breadcrumb">
	<div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
	<?php $segments = ''; ?>
	@foreach(Request::segments() as $segment)
	<?php 
	$segments .= '/'.$segment; 
	if($segment !='firm' && $segment !='admin' && $segments !='/admin/dashboard') {
	?>
	<div class="breadcrumb-item">
		<a href="{{ $segments }}">{{$segment}}</a>
	</div>
	<?php }
	if($segments == '/admin/firm') { ?>
		<div class="breadcrumb-item">
			<a href="{{ $segments }}">{{$segment}}</a>
		</div>
	 <?php 
	} ?>
	@endforeach
</div>