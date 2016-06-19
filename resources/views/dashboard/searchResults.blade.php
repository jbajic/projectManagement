@extends('templates.appNav')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-7 col-md-offset-2">
		@foreach( $users as $user )
			<div class="row">

				<div class="col-md-2">
					<img src="{{ asset('img/avatars/'.$user->avatar) }}" alt="Users avatar" class="avatar-icon avatar-icon-m right" />
				</div>

				<div class="col-md-6">
					<h2> {{ $user->name }} </h2>
					@if( $user->email )
						<h4> {{ $user->email }} </h4>
					@endif
					@if( $user->address )
						<h4> {{ $user->address }}, {{ $user->city }} </h4>
					@endif
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<div class="col-lg-11 display-search-results-button" id="friendsButton">
							@if( $user->status === 1 )
								<button type="button" class="btn btn-danger left removeFriend"><span>Remove friend</span></button>
							@elseif( $user->status  === 2)
								<button type="button" class="btn btn-success left acceptFriend"><span>Accept friend</span></button>
							@elseif( $user->status === 3 )
								<button type="button" class="btn btn-danger left rescindInvitation"><span>Rescind invitation</span></button>
							@elseif( $user->status === 0 )
								<button type="button" class="btn btn-primary left addFriend"><span>Add friend</span></button>
							@endif
						</div>
					</div>
				</div>

			</div>
		@endforeach
		</div>
</div>
@endsection