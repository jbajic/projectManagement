@extends('templates.appNav')

@section('content')

<div class="row">

	<div class="col-md-3 col-md-offset-2">
		<div class="testingDiv">
			<img src="{{ asset( 'img/avatars/'.$user->avatar ) }}" alt="avatar" class="avatar-icon right avatar-icon-l" id="mainAvatar" />
		</div>
		<form action="{{ route('profile.changeAvatar') }}" method="POST" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="form-group display-inline-block right" id="inputFileAvatar">

				<div class="hidden-group">
					<button class="btn btn-success left" type="submit">Save</button>
					<button class="btn btn-danger right" type="button" id="cancelChangeAvatar">Cancel</button>
				</div>

				<div class="showed-group">
					<label for="avatar" class="sr-only">Change avatar</label>
					<input type="file" name="avatar" id="avatar" class="filestyle form-control margin-top" data-buttonText="Change avatar" 
						data-buttonName="btn-primary" data-iconName="fa fa-image" />
				</div>

			</div>
		</form>
	</div>

	<div class="col-md-5 col-md-offset-1">
		<form action="{{ route('profile.update', array('id' => $user->id)) }}" method="POST" class="form-horizontal"> 

			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<input type="hidden" name="_method" value="PATCH" />

			<div class="form-group">

				<div class="col-lg-1">
					<i class="fa fa-user fa-2x"></i>
				</div>
				
				<div class="col-lg-5">
					<label for="first_name" class="sr-only">First name</label>
					<input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" placeholder="First name" />
				</div>

				<div class="col-lg-5">
					<label for="first_name" class="sr-only">Last name</label>
					<input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" placeholder="Last name" />
				</div>
			</div>

			@if( $errors->has('first_name') )
				<span class="help-block">{{ $errors->first('first_name') }}</span>
			@endif

			@if( $errors->has('last_name') )
				<span class="help-block">{{ $errors->first('last_name') }}</span>
			@endif

			<div class="form-group">
				<div class="col-lg-1">
					<i class="fa fa-envelope-o fa-2x"></i>
				</div>
				<div class="col-lg-10">
					<label class="sr-only" for="email">Email</label>
					<input type="email" class="form-control" name="email" value="{{ $user->email }}" placeholder="Email" />
				</div>
			</div>

			@if( $errors->has('email') )
				<span class="help-block">{{ $errors->first('email') }}</span>
			@endif

			<div class="form-group">

				<div class="col-lg-1">
					<i class="fa fa-home fa-2x"></i>
				</div>
				
				<div class="col-lg-4">
					<label for="address" class="sr-only">Address</label>
					<input type="text" class="form-control" name="address" value="{{ $user->address }}" placeholder="Address" />
				</div>

				<div class="col-lg-3">
					<label for="city" class="sr-only">City</label>
					<input type="text" class="form-control" name="city" value="{{ $user->city }}" placeholder="City" />
				</div>

				<div class="col-lg-3">
					<label for="country" class="sr-only">Country</label>
					<select class="form-control" name="country_id">
						@foreach( $countries as $country )
							@if( $country->id === $user->country_id )
								<option value="{{ $country->id }}" selected>{{ $country->name }}</option>
							@endif
						@endforeach
					</select>
				</div>
			</div>

			@if( $errors->has('address') )
				<span class="help-block">{{ $errors->first('address') }}</span>
			@endif

			@if( $errors->has('city') )
				<span class="help-block">{{ $errors->first('city') }}</span>
			@endif

			@if( $errors->has('country_id') )
				<span class="help-block">{{ $errors->first('country_id') }}</span>
			@endif
		
			<div class="form-group">
				<div class="col-lg-11">
					<label for="body" class="sr-only">Description</label>
					<textarea type="text" name="body" class="form-control" placeholder="Tell us something about yourself..." rows="5" />{{ $user->body }}</textarea>
				</div>
			</div>
			@if( $errors->has('body') )
				<span class="help-block">{{ $errors->first('body') }}</span>
			@endif

			<div class="form-group">
				<div class="col-lg-11">
					<button type="submit" class="btn btn-primary right">Update</button>
				</div>
			</div>
		</form>	
	</div>

</div>	
	
@endsection