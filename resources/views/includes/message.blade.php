@if( Session::has('message') )
	<div class="alert alert-danger" role="alert">
		{{ Session::get('message') }}
	</div>
@endif