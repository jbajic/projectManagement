$(document).ready( function(){

	$('.count').each(function () {
	    $(this).prop('Counter',0).animate({
	        Counter: $(this).text()
	    }, {
	        duration: 2000,
	        easing: 'swing',
	        step: function (now) {
	            $(this).text(Math.ceil(now));
	        }
	    });
	});


	$('div#Tasks-list').on('click', 'input[type="checkbox"]', function(){
		
		var taskId = $(this).val();
		if( $(this).is(':checked') )
		{
			checkTask(taskId, 1);
		}
		else
		{
			checkTask(taskId, 0);
		}		
	});

	//update task status
	function checkTask( taskId, checked )
	{
		$.ajax({
			url: myUrl + 'project/' + project + '/task/checkTask',
			dataType: 'json',
			data: {'id': taskId,
					'checked': checked},
			type: 'post'
		}).done( function(data){
			var responseStatus = data.status;
			switch( responseStatus )
			{
				case 0:
					var systemNotification = noty({
			          text: error_message,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
	       		 	});
					break;
				case 3:
					var systemNotification = noty({
			          text: validation_error,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
		       		 });
					break;
			}
			console.log('stauts: ' + data.status);
		}).fail( function(data){
			var systemNotification = noty({
	          text: connection_error_message,
	          layout: 'bottomRight',
	          type: 'error',
	          theme: 'defaultTheme',
	          timeout: 5000,
       		 });
		});
	}

	
	//add task
	var state_addTask = 0;
	$('div#Tasks-list').on('click', 'a.addTask', function(){
		
		var categoryId = $(this).parents('div.collcat').attr('data-param');
		
		if( state_addTask === 0 )
		{
			var create_options = '';
			for(var i = 0; i < options_length; ++i)
			{
				create_options += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
			}

			$('#collapse' + categoryId).collapse('show');
			$('#newTaskField' + categoryId).html(
			'<div class="row">' +
				'<div class="col-md-6">' +
					'<div class="form-group">' +
						'<label class="sr-only" for="taskName">Task name</label>' +
						'<input type="text" name="name" placeholder="Task" class="form-control" id="taskName" />' +
					'</div>' +
				'</div>' +
				'<div class="col-md-6">' + 
					'<div class="form-group">' +
						'<label class="sr-only" for="estimated_time">Estimated time</label>' +
						'<input type="number" name="estimated_time" id="estimated_time" placeholder="Estimated time in hours" class="form-control" min="1" />' + 
					'</div>' +
				'</div>' +
				'<div class="col-md-6">' + 
					'<div class="form-group">' +
						'<label class="sr-only" for="description">Description</label>' +
						'<textarea name="description" class="form-control" id="description" placeholder="Task description"></textarea>' +
					'</div>' +
				'</div>' +
				'<div class="col-md-6">' + 
					'<div class="form-group">' +
						'<label class="sr-only" for="members">Members</label>' +
						'<select id="members" class="chosen-select" multiple>' +
							create_options +
						'</select>' +
					'</div>' +
				'</div>' +
				'<div class="col-md-12">' + 
					'<div class="form-group">' +
						'<button type="button" class="btn btn-success btn-lg right addTaskToCategory" data-param="' + categoryId + '">Add Task</button>' +
					'</div>' +
				'</div>' +
			'</div>');
			state_addTask = 1;
			$(".chosen-select").chosen({
				no_results_text: "Oops, nothing found!",
			 	width: "95%"
			 });
		}
		else
		{
			$('#collapse' + categoryId).collapse('hide');
			$('#newTaskField' + categoryId).html('');
			state_addTask = 0;
		}
	});

	//store task
	$('div#Tasks-list').on('click', 'button.addTaskToCategory', function(){
		
		var categoryId = $(this).attr('data-param');
		var taskName = $('#taskName').val();
		var taskEstimatedTime = $('#estimated_time').val();
		var taskDescription = $('#description').val();
		var members = $('#members').val();
		
		$.ajax({
			url: myUrl + 'project/' + project + '/task',
			dataType: 'json',
			data: {
					'category': categoryId,
					'name': taskName,
					'body': taskDescription,
					'estimated_time': taskEstimatedTime,
					'members': members
				},
			type: 'POST'
		}).done( function(data){
			var status = data.status;
			console.log(status)
			switch( status )
			{
				case 1:
					var randpomVarName = removeLastTwoElementsFromString(members);

					var task = 
					'<tr data-param="' + data.taskId + '">' +
						'<td class="col-md-2">' + taskName + '</td>' +
						'<td class="col-md-3">' + taskDescription + '</td>' +
						'<td class="col-md-2">' + taskEstimatedTime + ' h</td>' +
						'<td class="col-md-2">' + randpomVarName + '</td>' +
						'<td class="col-md-1 tdstyle">' + 
							'<div class="checkbox checkbox-primary">' +
								'<input type="checkbox" name="completed' + data.taskId + '" value="' + data.taskId + '" />' +
								'<label for="checkbox' + data.taskId + '" ></label>' +
							'</div>' +
						'</td>' +
						'<td class="tdstyle col-md-1"> <a role="button" class="editTask"><i class="fa fa-edit"></i></a></td>' +
						'<td class="tdstyle col-md-1"> <a role="button" class="deleteTask"><i class="fa fa-trash"></i></a></td>' +
					'</tr>';

					var table = $('#collapse' + categoryId + ' table tbody');
					
					if( !table.exists() )
					{
						$('div#collapse' + categoryId).append(
							'<table class="table responsive-table" data-param="' + categoryId + '">' +
								'<thead>' +
									'<th data-sort-ignore="true" class="col-md-2">Task</th>' +
									'<th class="col-md-3">Description</th>' +
									'<th data-sort-ignore="true" class="col-md-2">Estimated time</th>' +
									'<th data-sort-ignore="true" class="col-md-2">Executors</th>' +
									'<th class="col-md-1"><i class="fa fa-check-square-o"></i></th>' +
									'<th class="col-md-1"><i class="fa fa-check-edit"></i></th>' +
									'<th class="col-md-1"><i class="fa fa-check-trash"></i></th>' +
								'</thead>' +
								'<tbody>' +
								'</tbody>' +
							'</table>');

						table = $('#collapse' + categoryId + ' table tbody');
					}
					
					$(task).hide().prependTo(table).fadeIn(300);
					$('#newTaskField' + categoryId).html('');
					state_addTask = 0;
					break;
				case 0:
					var systemNotification = noty({
			          text: error_message,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
	       		 	});
					break;
				case 3:
					var systemNotification = noty({
			          text: validation_error,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
		       		 });
					break;
			}
		}).fail( function(data){
			var systemNotification = noty({
	          text: connection_error_message,
	          layout: 'bottomRight',
	          type: 'error',
	          theme: 'defaultTheme',
	          timeout: 5000,
       		 });
		});
	});


	//destroy task
	$('#Tasks-list').on('click', 'a.deleteTask', function(){
		var task = $(this).parents('tr');
		var taskId = task.attr('data-param');
		var categoryId = $(this).parents('table').attr('data-param');
		
		$.ajax({
			url: myUrl + 'project/' + project + '/task/' + taskId,
			dataType: 'json',
			data: {
					'category': categoryId,
					'task': taskId,
				},
			type: 'DELETE'			
		}).done( function(data){
			var status = data.status;
			switch( status )
			{
				case 1:
					$(task).fadeOut( 400, function(){
						$(this).remove();
					});
					break;
				case 0:
					var systemNotification = noty({
			          text: error_message,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
	       		 	});
					break;
				case 3:
					var systemNotification = noty({
			          text: validation_error,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
		       		 });
					break;
			}
		}).fail( function(data){
			var systemNotification = noty({
	          text: connection_error_message,
	          layout: 'bottomRight',
	          type: 'error',
	          theme: 'defaultTheme',
	          timeout: 5000,
       		 });
		});
	});

	//edit task
	var state_editTask = 0;
	$('div#Tasks-list').on('click', 'a.editTask', function(){
		
		var categoryId = $(this).parents('table').attr('data-param');
		var task = $(this).parents('tr');
		var taskId = task.attr('data-param');

		if( state_editTask === 0 )
		{

			$.ajax({
				url: myUrl + 'project/' + project + '/task/' + taskId,
				type: 'GET'
			}).done( function(data){
				var status = data.status;
				var taskInfo = data.task;
				
				switch( status )
				{
					case 1:
						var taskMasters = taskInfo.users;
						var taskMasters_length = taskInfo.users.length;
						var edit_options = '';
						
						for(var i = 0; i < options_length; ++i)
						{
							var flag = 0;
							for(var j = 0; j < taskMasters_length; ++j)
							{
								if( options[i].id == taskMasters[j].id )
								{
									edit_options += '<option value="' + options[i].id + '" selected>' + options[i].name + '</option>';
									flag = 1;
									break;
								}
							}
							if( flag === 1 )
							{
								continue;
							}
							edit_options += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
						}
						
						$('#collapse' + categoryId).collapse('show');
						$('#newTaskField' + categoryId).html(
						'<div class="row">' +
						'<input type="hidden" name="task_id" value="' + taskId + '" id="taskId" />' +
							'<div class="col-md-6">' +
								'<div class="form-group">' +
									'<label class="sr-only" for="taskName">Task name</label>' +
									'<input type="text" name="name" placeholder="Task" class="form-control" value="' + taskInfo.name + '" id="taskName" />' +
								'</div>' +
							'</div>' +
							'<div class="col-md-6">' + 
								'<div class="form-group">' +
									'<label class="sr-only" for="estimated_time">Estimated time</label>' +
									'<input type="number" name="estimated_time" id="estimated_time" value="' + taskInfo.estimated_time + '" placeholder="Estimated time in hours" class="form-control" min="1" />' + 
								'</div>' +
							'</div>' +
							'<div class="col-md-6">' + 
								'<div class="form-group">' +
									'<label class="sr-only" for="description">Description</label>' +
									'<textarea name="description" class="form-control" id="description" placeholder="Task description">' + taskInfo.body + '</textarea>' +
								'</div>' +
							'</div>' +
							'<div class="col-md-6">' + 
								'<div class="form-group">' +
									'<label class="sr-only" for="members">Members</label>' +
									'<select id="members" class="chosen-select" multiple>' +
										edit_options +
									'</select>' +
								'</div>' +
							'</div>' +
							'<div class="col-md-12">' + 
								'<div class="form-group">' +
									'<button type="button" class="btn btn-success btn-lg updateTask" data-param="' + categoryId + '">Update Task</button>' +
								'</div>' +
							'</div>' +
						'</div>');
						$(".chosen-select").chosen({
										no_results_text: "Oops, nothing found!",
									 	width: "95%"
									 });
						state_editTask = 1;
						break;
					case 0:
						var systemNotification = noty({
				          text: error_message,
				          layout: 'bottomRight',
				          type: 'error',
				          theme: 'defaultTheme',
				          timeout: 5000,
		       		 	});
					break;
					case 2:
						var systemNotification = noty({
				          text: validation_error,
				          layout: 'bottomRight',
				          type: 'error',
				          theme: 'defaultTheme',
				          timeout: 5000,
			       		 });
					break;
				}
			}).fail( function(data){
				var systemNotification = noty({
		          text: connection_error_message,
		          layout: 'bottomRight',
		          type: 'error',
		          theme: 'defaultTheme',
		          timeout: 5000,
	       		 });
			});

			
		}
		else
		{
			$('#newTaskField' + categoryId).html('');
			state_editTask = 0;
		}

	});


	//update task
	$('div#Tasks-list').on('click', 'button.updateTask', function(){
		
		var categoryId = $(this).attr('data-param');
		var taskId = $('#taskId').val();
		var taskName = $('#taskName').val();
		var taskEstimatedTime = $('#estimated_time').val();
		var taskDescription = $('#description').val();
		var members = $('#members').val();
		console.log(members);
		$.ajax({
			url: myUrl + 'project/' + project + '/task/' + taskId,
			dataType: 'json',
			data: {
					'name': taskName,
					'body': taskDescription,
					'estimated_time': taskEstimatedTime,
					'members': members
				},
			type: 'PUT'
		}).done( function(data){
			var status = data.status;
			console.log('success');
			switch( status )
			{
				case 1:
					var randpomVarName = removeLastTwoElementsFromString(members);
					var task = 
					'<tr data-param="' + taskId + '">' +
						'<td class="col-md-2">' + taskName + '</td>' +
						'<td class="col-md-3">' + taskDescription + '</td>' +
						'<td class="col-md-2">' + taskEstimatedTime + ' h</td>' +
						'<td class="col-md-2">' + randpomVarName + ' </td>' +
						'<td class="col-md-1 tdstyle">' + 
							'<div class="checkbox checkbox-primary">' +
								'<input type="checkbox" name="completed' + taskId + '" value="' + taskId + '" />' +
								'<label for="checkbox' + taskId + '" ></label>' +
							'</div>' +
						'</td>' +
						'<td class="tdstyle col-md-1"> <a role="button" class="editTask"><i class="fa fa-edit"></i></a></td>' +
						'<td class="tdstyle col-md-1"> <a role="button" class="deleteTask"><i class="fa fa-trash"></i></a></td>' +
					'</tr>';
					
					$('tr[data-param="' + taskId + '"]').replaceWith(task);
					$('#newTaskField' + categoryId).html('');
					state_addTask = 0;
					break;
				case 0:
					var systemNotification = noty({
			          text: error_message,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
	       		 	});
					break;
				case 2:
					var systemNotification = noty({
			          text: validation_error,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
		       		 });
					break;
			}
		}).fail( function(data){
			var systemNotification = noty({
	          text: connection_error_message,
	          layout: 'bottomRight',
	          type: 'error',
	          theme: 'defaultTheme',
	          timeout: 5000,
       		 });
		});
	});


/*
	CATEGORY CONTROL
*/

	//destroy category
	$('div#Tasks-list').on('click', 'a.deleteCategory', function(){
		var categoryId = $(this).parents('div.collcat').attr('data-param');
		console.log(categoryId);
		
		$.ajax({
			url: myUrl + 'project/' + project + '/task/deleteCategory',
			dataType: 'json',
			data: {
					'category': categoryId
				},
			type: 'POST'			
		}).done( function(data){
			var status = data.status;
			console.log('success');
			console.log(data.status);
			switch( status )
			{
				case 1:
					$('#panelDis' + categoryId).fadeOut( 300, function(){
						$(this).remove();
					});
					$('#collapse' + categoryId).remove();
					break;
				case 0:
					var systemNotification = noty({
			          text: error_message,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
	       		 	});
					break;
				case 3:
					var systemNotification = noty({
			          text: validation_error,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
		       		 });
					break;
			}
		}).fail( function(data){
			var systemNotification = noty({
	          text: connection_error_message,
	          layout: 'bottomRight',
	          type: 'error',
	          theme: 'defaultTheme',
	          timeout: 5000,
       		 });
		});
	});

	//add category
	var state_category = 0;
	$('#addCategory').click( function(){
		if( state_category === 0 )
		{
			$('#Tasks-list').prepend(
			'<div class="row" id="form-category">' +
				'<div class="col-md-6">' +
					'<div class="form-group">' +
						'<label class="sr-only" for="category_name"> Category</label>' +
						'<input type="text" name="category_name" class="form-control" placeholder="Category" />' +
					'</div>' +
				'</div>' +
				'<div class="col-md-6">' +
					'<div class="form-group">' +
						'<label class="sr-only" for="category_deadline"> Deadline</label>' +
						'<input type="text" name="category_deadline" placeholder="Deadline" class="form-control datepicker" />' +
					'</div>' +
				'</div>' +
				'<div class="col-md-10">' +
					'<div class="form-group">' +
						'<label class="sr-only" for="category_body"> Description</label>' +
						'<textarea name="category_body" class="form-control" id="category_body" placeholder="Description"></textarea>' +
					'</div>' +
				'</div>' +
				'<div class="col-md-2">' +
					'<div class="form-group">' +
						'<button type="button" class="btn btn-success btn-lg" id="storeCategory">Add category</button>' +
					'</div>' +
				'</div>' +
			'</div>');
			startDatepicker();
			state_category = 1;
		}
		else
		{
			$('#form-category').remove();
			state_category = 0;
		}
	});

	//store category
	$('div#Tasks-list').on('click', 'button#storeCategory', function(){

		var category = $('input[name="category_name"]').val();
		var deadline = $('input[name="category_deadline"]').val();
		var body = $('#category_body').val();
		
		$.ajax({
			url: myUrl + '/project/' + project + '/task/addCategory',
			dataType: 'json',
			data: {
				'name': category,
				'deadline': deadline,
				'body': body,
				'project_id': project
			},
			type: 'post'
		}).done( function(data){
			var status = data.status;
			var id = data.id;

			switch( status )
			{
				case 1:
					$('#form-category').remove();
					$('#Tasks-list').prepend(
					'<div class="panel-heading collcat" role="tab" id="panelDis' + id + '" data-param="' + id + '">' +
						'<div class="row">' +
							'<div class="col-md-5">' +
								'<h4 class="panel-title">' +
									'<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse' + id + '" aria-expanded="false" aria-controls="collapse' + id + '" style="text-decoration: none;">' +
										'<span class="collcattxt">' + category + ' <i class="fa fa-sort-desc" style="vertical-align: top;"></i></span>' +
									'</a>' +
									'<span></span>' +
								'</h4>' +
							'</div>' +
							'<div class="col-md-4">' +
								 '<span class="collcattxt">Deadline: ' + deadline + '</span>' + 
							'</div>' +
							'<div class="col-md-1">' +
								 '<a role="button" class="headings-link editCategory" href="#"> <i class="fa fa-edit"></i></a>' + 
							'</div>' +
							'<div class="col-md-1">' +
								 '<a role="button" class="headings-link addTask" href="#"> <i class="fa fa-plus"></i></a>' + 
							'</div>' +
							'<div class="col-md-1">' + 
								'<a role="button" class="headings-link deleteCategory" href="#"> <i class="fa fa-trash"></i></a>' +
							'</div>' +
						'</div>' +
					'</div>' +
					'<div id="collapse' + id + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading' + id + '">' +
						'<p><span class="descriptive-text"> Description:</span> ' + body + '</p>' +
						'<p id="newTaskField' + id + '"></p>' +
					'</div>');
					break;
				case 0:
					var systemNotification = noty({
			          text: error_message,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
	       		 	});
					break;
				case 2:
					var systemNotification = noty({
			          text: validation_error,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
		       		 });
					break;
			}
		}).fail( function(data){
			var systemNotification = noty({
	          text: connection_error_message,
	          layout: 'bottomRight',
	          type: 'error',
	          theme: 'defaultTheme',
	          timeout: 5000,
       		 });
		});
	});

	// edit category
	var state_category = 0;
	$('div#Tasks-list').on('click', '.editCategory', function(){
		var categoryId = $(this).parents('div.collcat').attr('data-param');

		if( state_category === 0 )
		{
			$.ajax({
			url: myUrl + '/project/' + project + '/task/getCategory',
			dataType: 'json',
			data: {
				'id': categoryId
			},
			type: 'post'
			}).done( function(data){
				var status = data.status;
				var category = data.category;
				switch( status )
				{
					case 1:
						$('#Tasks-list').prepend(
							'<div class="row" id="form-category">' +
							'<input type="hidden" name="id" value="' + categoryId + '" id="categoryId" />' +
								'<div class="col-md-6">' +
									'<div class="form-group">' +
										'<label class="sr-only" for="category_name"> Category</label>' +
										'<input type="text" name="category_name" value="' + category.name + '" class="form-control" placeholder="Category" />' +
									'</div>' +
								'</div>' +
								'<div class="col-md-6">' +
									'<div class="form-group">' +
										'<label class="sr-only" for="category_deadline"> Deadline</label>' +
										'<input type="text" name="category_deadline" value="' + category.deadline + '" placeholder="Deadline" class="form-control datepicker" />' +
									'</div>' +
								'</div>' +
								'<div class="col-md-10">' +
									'<div class="form-group">' +
										'<label class="sr-only" for="category_body"> Description</label>' +
										'<textarea name="category_body" class="form-control" id="category_body" placeholder="Description">' + category.body + '</textarea>' +
									'</div>' +
								'</div>' +
								'<div class="col-md-2">' +
									'<div class="form-group">' +
										'<button type="button" class="btn btn-success btn-lg" id="updateCategory">Update category</button>' +
									'</div>' +
								'</div>' +
							'</div>');
						startDatepicker();
						state_category = 1;
						break;
					case 0:
						var systemNotification = noty({
				          text: error_message,
				          layout: 'bottomRight',
				          type: 'error',
				          theme: 'defaultTheme',
				          timeout: 5000,
		       		 	});
						break;
					case 2:
						var systemNotification = noty({
				          text: validation_error,
				          layout: 'bottomRight',
				          type: 'error',
				          theme: 'defaultTheme',
				          timeout: 5000,
			       		 });
						break;
				}

			}).fail( function(data){
				var systemNotification = noty({
		          text: connection_error_message,
		          layout: 'bottomRight',
		          type: 'error',
		          theme: 'defaultTheme',
		          timeout: 5000,
	       		 });
			});
		}
		else
		{
			$('#form-category').remove();
			state_category = 0;
		}	
	});

	// update category
	$('div#Tasks-list').on('click', 'button#updateCategory', function(){
		console.log('jare jare');
		var categoryId = $('#categoryId').val();
		var category = $('input[name="category_name"]').val();
		var deadline = $('input[name="category_deadline"]').val();
		var body = $('#category_body').val();
		console.log(categoryId);
		$.ajax({
			url: myUrl + '/project/' + project + '/task/updateCategory',
			dataType: 'json',
			data: {
				'id': categoryId,
				'name': category,
				'deadline': deadline,
				'body': body,
				'project_id': project
			},
			type: 'post'
		}).done( function(data){
			var status = data.status;
			var id = data.id;

			switch( status )
			{
				case 1:
					$('#form-category').remove();
					$('#panelDis' + categoryId).fadeOut( 300, function(){
						$(this).remove();
					});
					$('#collapse' + categoryId).remove();

					$('#Tasks-list').prepend(
					'<div class="panel-heading collcat" role="tab" id="panelDis' + categoryId + '" data-param="' + categoryId + '">' +
						'<div class="row">' +
							'<div class="col-md-5">' +
								'<h4 class="panel-title">' +
									'<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse' + categoryId + '" aria-expanded="false" aria-controls="collapse' + categoryId + '" style="text-decoration: none;">' +
										'<span class="collcattxt">' + category + ' <i class="fa fa-sort-desc" style="vertical-align: top;"></i></span>' +
									'</a>' +
									'<span></span>' +
								'</h4>' +
							'</div>' +
							'<div class="col-md-4">' +
								 '<span class="collcattxt">Deadline: ' + deadline + '</span>' + 
							'</div>' +
							'<div class="col-md-1">' +
								 '<a role="button" class="headings-link editCategory" href="#"> <i class="fa fa-edit"></i></a>' + 
							'</div>' +
							'<div class="col-md-1">' +
								 '<a role="button" class="headings-link addTask" href="#"> <i class="fa fa-plus"></i></a>' + 
							'</div>' +
							'<div class="col-md-1">' + 
								'<a role="button" class="headings-link deleteCategory" href="#"> <i class="fa fa-trash"></i></a>' +
							'</div>' +
						'</div>' +
					'</div>' +
					'<div categoryId="collapse' + categoryId + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading' + categoryId + '">' +
						'<p><span class="descriptive-text"> Description:</span> ' + body + '</p>' +
						'<p categoryId="newTaskField' + categoryId + '"></p>' +
					'</div>');
					break;
				case 0:
					var systemNotification = noty({
			          text: error_message,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
	       		 	});
					break;
				case 2:
					var systemNotification = noty({
			          text: validation_error,
			          layout: 'bottomRight',
			          type: 'error',
			          theme: 'defaultTheme',
			          timeout: 5000,
		       		 });
					break;
			}
		}).fail( function(data){
			var systemNotification = noty({
	          text: connection_error_message,
	          layout: 'bottomRight',
	          type: 'error',
	          theme: 'defaultTheme',
	          timeout: 5000,
       		 });
		});
	});

});
