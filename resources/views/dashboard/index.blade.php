@extends('templates.appNav')

@section('content')
<div class="container-fluid">
	<div class="row">

		@if( $projects->count() )
			<div class="col-md-7 col-md-offset-2">
			<h1>Active Projects:</h1>
			@foreach( $projects as $project )
			<div class="row">
				<div class="col-md-2">
					<img src="{{ asset('img/avatars/'.$project->manager->avatar) }}" alt="Managers avatar" class="avatar-icon avatar-icon-m right" />
				</div>

					<div class="col-md-10">
						<div class="progress">
						  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" 
						  	aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->percentage }}%; min-width: 3em">
						  	<a href="{{ route('project.show', array( 'id' => $project->id )) }}"><span> {{ $project->percentage.'% '.$project->name }}</span></a>
						    <span class="sr-only">{{ $project->percentage }}% Complete (success)</span>
						  </div>
						</div>
					</div>
			</div>
			@endforeach
			</div>
		@endif

		<div class="col-md-3">
			<a role="button" title="Add project" class="btn btn-success" href="{{ route('project.create') }}"><i class="fa fa-plus fa-2x center"></i></a>
		</div>
	</div>
</div>
@endsection