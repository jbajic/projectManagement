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


	$('table.table').on('click', 'input[type="checkbox"]', function(){
		
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
	$('table.no-style-table').on('click', 'a.addTask', function(){
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
					'<tr>' +
						'<td>' + taskName + '</td>' +
						'<td>' + taskDescription + '</td>' +
						'<td>' + taskEstimatedTime + ' h</td>' +
						'<td>' + 
							'<div class="checkbox checkbox-primary">' +
								'<input type="checkbox" name="completed' + data.taskId + '" value="' + data.taskId + '" />' +
								'<label for="checkbox' + data.taskId + '" ></label>' +
							'</div>' +
						'</td>' +
						'<td> <a role="button" class="" href="#"><i class="fa fa-trash center"></i></a></td>' +
					'</tr>';

					$(task).hide().prependTo('#collapse' + categoryId + ' table tbody').fadeIn(300);
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
	$('table.responsive-table').on('click', 'a.deleteTask', function(){
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
	$('table.no-style-table').on('click', 'a.deleteCategory', function(){
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
			switch( status )
			{
				case 1:
					
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
	$('table.no-style-table').on('click', 'a.addCategory', function(){
		var categoryId = $(this).parents('div.collcat').attr('data-param');
		console.log(categoryId);
		
		$.ajax({
			url: myUrl + 'project/addCategory',
			dataType: 'json',
			data: {
					'category': categoryId
				},
			type: 'POST'			
		}).done( function(data){
			var status = data.status;
			console.log('success');
			switch( status )
			{
				case 1:
					
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

});
