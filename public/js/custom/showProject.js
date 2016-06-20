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


	$('div.Tasks-list').on('click', 'input[type="checkbox"]', function(){
		
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
			url: myUrl + 'project/checkTask',
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

	
	//add task to category input
	var state_addTask = 0;
	$('.Tasks-list').on('click', 'a.addTask', function(){
		var categoryId = $(this).parents('div').attr('data-param');
		
		if( state_addTask === 0 )
		{
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
				'<div class="col-md-10">' + 
					'<div class="form-group">' +
						'<label class="sr-only" for="description">Description</label>' +
						'<textarea name="description" class="form-control" id="description" placeholder="Task description"></textarea>' +
					'</div>' +
				'</div>' +
				'<div class="col-md-2">' + 
					'<div class="form-group">' +
						'<button type="button" class="btn btn-success btn-lg addTaskToCategory" data-param="' + categoryId + '">Add Task</button>' +
					'</div>' +
				'</div>' +
			'</div>');
			state_addTask = 1;
		}
		else
		{
			$('#collapse' + categoryId).collapse('hide');
			$('#newTaskField' + categoryId).html('');
			state_addTask = 0;
		}
	});

	//addTask
	$('.collapse').on('click', 'button.addTaskToCategory', function(){
		
		var categoryId = $(this).attr('data-param');
		var taskName = $('#taskName').val();
		var taskEstimatedTime = $('#estimated_time').val();
		var taskDescription = $('#description').val();
		
		$.ajax({
			url: myUrl + 'project/addTask',
			dataType: 'json',
			data: {
					'category': categoryId,
					'name': taskName,
					'body': taskDescription,
					'estimated_time': taskEstimatedTime
				},
			type: 'POST'
		}).done( function(data){
			var status = data.status;
			switch( status )
			{
				case 1:
					var task = 
					'<tr data-param="' + data.taskId + '">' +
						'<td>' + taskName + '</td>' +
						'<td>' + taskDescription + '</td>' +
						'<td>' + taskEstimatedTime + ' h</td>' +
						'<td>' + 
							'<div class="checkbox checkbox-primary">' +
								'<input type="checkbox" name="completed' + data.taskId + '" value="' + data.taskId + '" />' +
								'<label for="checkbox' + data.taskId + '" ></label>' +
							'</div>' +
						'</td>' +
						'<td class="tdstyle"> <a role="button" class="deleteTask"><i class="fa fa-trash"></i></a></td>' +
					'</tr>';

					var table = $('#collapse' + categoryId + ' table tbody');
					console.log(table);
					if( !table.exists() )
					{
						$('div#collapse' + categoryId).append(
							'<table class="table responsive-table" data-param="' + categoryId + '">' +
								'<thead>' +
									'<th data-sort-ignore="true">Task</th>' +
									'<th>Description</th>' +
									'<th data-sort-ignore="true">Estimated time</th>' +
									'<th><i class="fa fa-check-square-o"></i></th>' +
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

	//delete task
	$('.Tasks-list').on('click', 'a.deleteTask', function(){
		var task = $(this).parents('tr');
		var taskId = task.attr('data-param');
		var categoryId = $(this).parents('table').attr('data-param');
		
		$.ajax({
			url: myUrl + 'project/deleteTask',
			dataType: 'json',
			data: {
					'category': categoryId,
					'task': taskId,
				},
			type: 'POST'			
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

	//delete category
	$('div.Tasks-list').on('click', 'a.deleteCategory', function(){
		var categoryId = $(this).parents('div.collcat').attr('data-param');
		console.log(categoryId);
		
		$.ajax({
			url: myUrl + 'project/deleteCategory',
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
	$('#addCategory').click( function(){
		$('.Tasks-list').prepend(
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
						'<button type="button" class="btn btn-success btn-lg" >Add category</button>' +
					'</div>' +
				'</div>' +
			'</div>');
		startDatepicker();
	});

	//create category
	$('div.Tasks-list').on('click', 'button', function(){

		var category = $('input[name="category_name"]').val();
		var deadline = $('input[name="category_deadline"]').val();
		var body = $('#category_body').val();
		
		$.ajax({
			url: myUrl + '/project/addCategory',
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
					$('.Tasks-list').prepend(
					'<div class="panel-heading collcat" role="tab" id="panelDis' + id + '" data-param="' + id + '">' +
					'<table class="no-style-table">' +
						'<tr>' +
							'<td>' +
								'<h4 class="panel-title">' +
									'<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse' + id + '" aria-expanded="false" aria-controls="collapse' + id + '" style="text-decoration: none;">' +
										'<span class="collcattxt">' + category + ' <i class="fa fa-sort-desc" style="vertical-align: top;"></i></span>' +
									'</a>' +
									'<span></span>' +
								'</h4>' +
							'</td>' +
							'<td> <a role="button" class="headings-link addTask" href="#"> <i class="fa fa-plus"></i></a></td>' +
							'<td> <a role="button" class="headings-link deleteCategory" href="#"> <i class="fa fa-trash"></i></a></td>' +
						'</tr>' +
					'</table>' +
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
	})

});
