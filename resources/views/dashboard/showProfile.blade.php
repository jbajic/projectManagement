@extends('templates.appNav')

@section('content')

<div class="row">

	<div class="col-md-3 col-md-offset-2">
		<div>
			<img src="{{ asset( 'img/avatars/'.$user->avatar ) }}" alt="avatar" class="avatar-icon right avatar-icon-l" id="mainAvatar" />
		</div>
	</div>

	<div class="col-md-5 col-md-offset-1">
		<form class="form-horizontal"> 

			<div class="form-group">

				<div class="col-lg-1">
					<i class="fa fa-user fa-2x"></i>
				</div>
				
				<div class="col-lg-5">
					@if( !empty($user->first_name) && !empty($user->last_name) )
						<span class="">{{ $user->first_name . ' ' . $user->last_name }}<span/>
					@endif
				</div>
			</div>

			<div class="form-group">
				<div class="col-lg-1">
					<i class="fa fa-envelope-o fa-2x"></i>
				</div>
				<div class="col-lg-10">
					
					<span> {{ $user->email }}</span>
				</div>
			</div>

			<div class="form-group">

				<div class="col-lg-1">
					<i class="fa fa-home fa-2x"></i>
				</div>
				
				<div class="col-lg-4">
					@if( !empty($user->address) && !empty($user->city) && !empty($user->countryName) )
						<span>{{ $user->address . ' ' . $user->city . ' ' . $user->countryName }}</span>
					@endif
				</div>
			</div>
		
			<div class="form-group">
				<div class="col-lg-11">
					@if( !empty($user->body) )
						<h3> Something about me</h3>
						<p class="preformatted">{{ $user->body }}</p>
					@endif
				</div>
			</div>

			<div class="form-group">
				<div class="col-lg-11" id="friendsButton">
					@if( $status === 1 )
						<button type="button" class="btn btn-danger left removeFriend"><span>Remove friend</span></button>
					@elseif( $status  === 2)
						<button type="button" class="btn btn-success left acceptFriend"><span>Accept friend</span></button>
					@elseif( $status === 3 )
						<button type="button" class="btn btn-danger left rescindInvitation"><span>Rescind invitation</span></button>
					@else
						<button type="button" class="btn btn-primary left addFriend"><span>Add friend</span></button>
					@endif
				</div>
			</div>
		</form>	
	</div>
	
</div>
<script>
	var userId = {{ $user->id }};
</script>

@endsection