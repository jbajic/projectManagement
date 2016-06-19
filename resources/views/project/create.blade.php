@extends('templates.appNav')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 col-md-offset-2">
			<h2 class="margin-down">Create project</h2>				
		</div>

		<form action="{{ route('project.store') }}" method="POST">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />

			<div class="col-md-4 col-sm-4 col-md-offset-2">	

				<div class="form-group{{ $errors->has('name') ? ' has-error': '' }}">
					<label for="name" class="">Project name</label>
					<input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Project name" />
					@if( $errors->has('name') )
						<span class="help-block"> {{ $errors->first('name') }}</span>
					@endif
				</div>

				<div class="form-group{{ $errors->has('manager') ? ' has-error': '' }}">
					<label for="project_manager" class="">Project manager</label>
					<select class="form-control" name="manager">
						<option value="{{ $user->id }}">{{ $user->name }}</option>
						@foreach( $users as $u )
							<option value="{{ $u->id }}">{{ $u->name }}</option>
						@endforeach
					</select>
				</div>

			</div>
			
			<div class="col-md-4 col-sm-4">					
				
				<div class="form-group{{ $errors->has('client') ? ' has-error': '' }}">
					<label for="client" class="">Client</label>
					<input type="text" class="form-control" name="client" value="{{ old('client') }}" placeholder="Client" />
					@if( $errors->has('client') )
						<span class="help-block"> {{ $errors->first('client') }}</span>
					@endif
				</div>

				<div class="form-group{{ $errors->has('deadline') ? ' has-error': '' }}">
					<label for="deadline" class="">Project deadline</label>
					<input type="text" class="form-control datepicker" name="deadline" value="{{ old('deadline') }}" />
					@if( $errors->has('deadline') )
						<span class="help-block"> {{ $errors->first('deadline') }}</span>
					@endif
				</div>
				
			</div>

			<div class="col-md-8 col-sm-8 col-md-offset-2">

				<div class="form-group{{ $errors->has('body') ? ' has-error': '' }}">
					<label for="body"> Project description</label>
					<textarea placeholder="Something about project" class="form-control" rows="5" name="body">{{ old('body') }}</textarea>
					@if( $errors->has('body') )
						<span class="help-block"> {{ $errors->first('body') }}</span>
					@endif
				</div>

			</div>

			<div class="col-md-4 col-sm-4 col-md-offset-2 ">

				<div class="form-group">
				<label for="search_friends"> Friends</label>
				<!-- <input type="text" name="search_friends" class="form-control" placeholder="Find friends..." /> -->
						<ul id="developers" class="developers groups-border">
							@foreach($users as $u)
								<li class="elem" id="{{ $u->id }}">
									<h5 class="">{{ $u->name }}</h5>
									<img src="/img/avatars/{{ $u->avatar }}"  alt="Users avatar" class="avatar-icon avatar-icon-ms" />
									<div>
										<a role="button" class="left"> <i class="fa fa-star"></i> </a>
										<a role="button" class="right"> <i class="fa fa-plus"></i> </a>
									</div>
								</li>
							@endforeach
						</ul>
				</div>

			</div>

			<div class="col-md-4 col-sm-4">
				<label> Team</label>
				<ul id="teamMembers" class="developers groups-border">
					<li class="elem manager" id="{{ $user->id }}">
						<h5 class="">{{ $user->name }}</h5>
						<img src="/img/avatars/{{ $user->avatar }}"  alt="Users avatar" class="avatar-icon avatar-icon-ms" />
						<div>
							<a role="button" class="left"> <i class="fa fa-star"></i> </a>
							<a role="button" class="right"> <i class="fa fa-plus"></i> </a>
						</div>
					</li>
					<input type="hidden" name="members" id="members" value="" />
				</ul>
			</div>

			<div class="col-md-5 col-sm-8 col-md-offset-2">

			</div>

			<div class="col-md-8 col-sm-8 col-md-offset-2">
				<div class="center buttons">
					<button type="submit" class="btn btn-primary"> Save</button>
					<a href="{{ route('dashboard.index') }}" role="button" class="btn btn-danger cancel"> Cancel</a>
				</div>
			</div>

		</form>
	</div>
</div>	
<script>
	var managerId = '{{ $user->id }}';
</script>
@endsection