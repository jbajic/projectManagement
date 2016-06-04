@extends('templates.appNav')

@section('content')

	<h2>Add Project</h2>

		<div class="row">

			<div class="col-md-6 col-sm-6">	

							

			</div>
			
			<div class="col-md-6 col-sm-6">					
				

				
			</div>

			<div class="col-md-12 col-sm-12 center buttons">
				<button type="submit" class="btn primary"> Save</button>
				<a href="{{ route('dashboard.index') }}" class="btn cancel"> 
					Cancel</a>
			</div>

		</div>

@endsection