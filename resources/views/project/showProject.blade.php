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
						<li><a role="button" href="{{ route('project.edit', array('id' => $project->id)) }}" class="force-li-style">Edit project</a></li>
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
					<p><span class="descriptive-text">Manager:</span> {{ $project->manager->name }} </p>
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
		<div class="table-responsive col-lg-10 col-lg-offset-1" id="Tasks-list">
		@if( count( $project->tasks ) )
			@foreach( $project->tasks as $task )
					<div class="panel-heading collcat" role="tab" id="panelDis{{ $task->id }}" data-param="{{ $task->id }}">
						<div class="row">
							<div class="col-md-5">
								<h4 class="panel-title">
									<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $task->id }}" aria-expanded="false" aria-controls="collapse{{ $task->id }}" style="text-decoration: none;">
										<span class="collcattxt">{{ $task->name }} <i class="fa fa-sort-desc" style="vertical-align: top;"></i></span>
									</a>
									<span>{{ $task->tasksFin , " / " , $task->tasksAct + $task->tasksFin }}</span>
								</h4>
							</div>
							<div class="col-md-4">
								<span class="collcattxt">Deadline: {{ $task->deadline }}</span>
							</div>
							<div class="col-md-1">
								<a role="button" class="headings-link editCategory" href="#"> <i class="fa fa-edit"></i></a>
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
									<th data-sort-ignore="true" class="col-md-2">Task</th>
									<th class="col-md-3">Description</th>
									<th data-sort-ignore="true" class="col-md-2">Estimated time</th>
									<th data-sort-ignore="true" class="col-md-2"> Executors</th>
									<th><i class="fa fa-check-square-o" class="col-md-1"></i></th>
									<th><i class="fa fa-edit" class="col-md-1"></i></th>
									<th><i class="fa fa-trash" class="col-md-1"></i></th>
								</thead>
								<tbody>
								@foreach( $task->categoryTasks as $t )
									<tr data-param="{{ $t->id }}">
										<td class="col-md-2"> {{ $t->name }}</td>
										<td class="col-md-3"> {{ $t->body }}</td>
										<td class="col-md-2"> {{ $t->estimated_time }} h</td>
										<td class="col-md-2">
										@if( count($t->users) )
											@foreach( $t->users as $taskMaster )
												<span>{{ $taskMaster->name }}, </span>
											@endforeach
										@else
											<p>Anyone</p>
										@endif
										</td>
										<td class="col-md-1 tdstyle">
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
										<td class="tdstyle col-md-1"> <a role="button" class="editTask"><i class="fa fa-edit"></i></a></td>
										<td class="tdstyle col-md-1"> <a role="button" class="deleteTask"><i class="fa fa-trash"></i></a></td>
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
	var options = [];
	@foreach( $project->users as $member )
		options.push({
			id: '{{ $member->id }}',
			name: '{{ $member->name }}'
		});
	@endforeach
	var options_length = options.length;
</script>
@endsection
