@extends('templates.appNav')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-4 col-lg-4 mb col-md-offset-1">

			@if( $permission )
			<div class="dropdown">
				<button class="btn btn-default dropdown-toggle" type="button" id="projectName" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<h1>{{ $project->name }} <span class="caret"></span></h1>
				</button>

				<ul class="dropdown-menu" role="menu">
	    			@if( $project->completed == false )
	    				<li><a role="button" href="#">Finish project</a></li>
					@else
						<li><a role="button" href="#">Project in progress</a></li>
					@endif
					<form action="{{ route('project.destroy', array('id' => $project->id)) }}" method="POST">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<input type="hidden" name="_method" value="DELETE" />

						<li role="separator" class="divider"></li>
						<li><button type="submit" role="button" class="force-li-style" type="submit" >Delete project</button></li>
					</form>
				</ul>
			</div>
			@else
				<h1> {{ $project->name }}</h1>
			@endif
			<div class="well wll">
				@if($project->client)
					<p><span class="descriptive-text">Client:</span> {{ $project->client }} </p>
				@endif

				@if($project->deadline)
					<p><span class="descriptive-text"> Deadline:</span> {{ $project->deadline }}</p>
				@endif

				@if($project->body)
					<p><span class="descriptive-text">Description:</span> {{ $project->body }}</p>
				@endif
			</div>
		</div>


		<div class="col-xs-push-1 col-sm-push-1 col-md-push-1 col-lg-push-1">
			<div class="circle-numbers">

				<div class="col-md-2">
					<div class="circle-outer">
						<a href="#">
							<div class="circle">
								<h1 class="count">{{ $countTasks }}</h1>
							</div>
						</a>
					</div>
					<p class="text-center pt10">All tasks</p>			
				</div>

				<div class="col-md-2">
					<div class="circle-outer">
						<a href="#">
							<div class="circle circle1">
								<h1 class="count">{{ $activeTasks }}</h1>
							</div>
						</a>
					</div>
					<p class="text-center pt10">Active tasks</p>
				</div>

				<div class="col-md-2">
					<div class="circle-outer">
						<a href="#">
							<div class="circle circle2">
								<h1 class="count">{{ $solvedTasks }}</h1>
							</div>
						</a>
					</div>
					<p class="text-center pt10">Completed tasks</p>
				</div>

			</div>
		</div>
		<div>
			<button type="button" class="btn btn-primary btn-lg" id="addCategory"><i class="fa fa-plus fa-2x"></i></button>
		</div>
	</div>

	<hr class="crta crtica" />

	<div class="row">
		<div class="table-responsive col-lg-10 col-lg-offset-1 Tasks-list">
		@if( count( $project->tasks ) )
			@foreach( $project->tasks as $task )
					<div class="panel-heading collcat" role="tab" id="panelDis{{ $task->id }}" data-param="{{ $task->id }}">
						<div class="row">
							<div class="col-md-10">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $task->id }}" aria-expanded="false" aria-controls="collapse{{ $task->id }}" style="text-decoration: none;">
										<span class="collcattxt">{{ $task->name }} <i class="fa fa-sort-desc" style="vertical-align: top;"></i></span>
									</a>
									<span>{{ $task->tasksFin , " / " , $task->tasksAct + $task->tasksFin }}</span>
								</h4>
							</div>
							<div class="col-md-1">
								<a role="button" class="headings-link addTask" href="#"> <i class="fa fa-plus"></i></a>
							</div>
							<div class="col-md-1">
								<a role="button" class="headings-link deleteCategory" href="#"> <i class="fa fa-trash"></i></a>
							</div>
						</div>
					</div>

					<div id="collapse{{ $task->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $task->id }}">
						<p><span class="descriptive-text"> Description:</span> {{ $task->body }}</p>
						<p id="newTaskField{{ $task->id }}"></p>
						@if( count( $task->categoryTasks ) )
							<table class="table responsive-table" data-param="{{ $task->id }}">
								<thead>
									<th data-sort-ignore="true">Task</th>
									<th>Description</th>
									<th data-sort-ignore="true">Estimated time</th>
									<th><i class="fa fa-check-square-o"></i></th>
								</thead>
								<tbody>
								@foreach( $task->categoryTasks as $t )
									<tr data-param="{{ $t->id }}">
										<td> {{ $t->name }}</td>
										<td> {{ $t->body }}</td>
										<td> {{ $t->estimated_time }} h</td>
										<td>
											<div class="checkbox checkbox-primary">
											@if( $t->completed )
												<input type="checkbox" name="completed{{ $t->id }}" value="{{ $t->id }}" checked />
												<label for="checkbox{{ $t->id }}" ></label>
											@else
												<input type="checkbox" name="completed{{ $t->id }}" value="{{ $t->id }}" />
												<label for="checkbox{{ $t->id }}"></label>
											@endif
											</div>
										</td>
										<td class="tdstyle"> <a role="button" class="deleteTask"><i class="fa fa-trash"></i></a></td>
									</tr>
								@endforeach
								</tbody>
							</table>
						@endif
					</div>

			@endforeach
		@endif
		</div>
	</div>
</div>

<script>
	var project = '{{ $project->id }}';
</script>
@endsection
