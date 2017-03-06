$(document).ready(function(){

	$('#inputFileAvatar div.hidden-group').hide();

	var fileInput = $('input[type="file"]');
	var inputFileAvatar = $('#inputFileAvatar');

	$(inputFileAvatar).on('change', fileInput, function( event ){
		
		if( $(fileInput).value != "" )
		{
		  	var reader = new FileReader();
		   	reader.onload = function(){
		      	var output = document.getElementById('mainAvatar');
		      	output.src = reader.result;
			};
			reader.readAsDataURL(event.target.files[0]);
			
			$('#inputFileAvatar div.showed-group, #inputFileAvatar div.hidden-group').toggle();
		}
	});

	$(inputFileAvatar).on('click', '#cancelChangeAvatar', function(){

		$('#inputFileAvatar div.showed-group, #inputFileAvatar div.hidden-group').toggle();
	});

	$('#friendsButton').on('click', 'button.addFriend', function(){
		var clickedElement = $(this);
		
		$.ajax({
			method: 'POST',
			url: myUrl + 'profile/addFriend',
			data: {
					'id': userId
				},
			dataType: 'json'
    		}).done( function(data){
    			
                var status = data.status;
                
                switch( status )
                {
                	case 1:
                		
                		clickedElement.removeClass('btn-primary addFriend').addClass('btn-danger rescindInvitation');
                		clickedElement.children('span').html('Rescind invitation');
                		// clickedElement.parent().removeClass('addFriend').addClass('removeFriend');
                		break;
                	case 2:
                		swal(
						  'Warning!',
						  'You cannot add yourself as friend!',
						  'error'
						);
                		break;
                	case 3:
                		swal(
						  'Warning!',
						  'You already sent an invitation!',
						  'error'
						);
                		break;
                	case 4:
                		swal(
						  'Warning!',
						  'You are already friend!',
						  'error'
						);
                		break;
                	case 0:
                		swal(
						  'Warning!',
						  'User doesn\'t exists!',
						  'error'
						);
                		break;
                }

    		}).fail( function(data){
    			console.log('fail');
    		});
	});

	$('#friendsButton').on('click', 'button.rescindInvitation', function(){
		var clickedElement = $(this);
		
		$.ajax({
			method: 'POST',
			url: myUrl + 'profile/rescindInvitation',
			data: {
					'id': userId
				},
			dataType: 'json'
    		}).done( function(data){

                var status = data.status;

                switch( status )
                {
                	case 1:
                		
                		clickedElement.addClass('btn-primary addFriend').removeClass('btn-danger rescindInvitation');
                		clickedElement.children('span').html('Add friend');
                		// clickedElement.parent().addClass('addFriend').removeClass('removeFriend');
                		break;
                	case 2:
                		swal(
						  'Warning!',
						  'Invitation wasn\'t sent!',
						  'error'
						);
                		break;
                	case 0:
                		swal(
						  'Warning!',
						  'User doesn\'t exists!',
						  'error'
						);
                		break;
                }

    		}).fail( function(data){
    			console.log('fail');
    		});
	});

	$('#friendsButton').on('click', 'button.removeFriend', function(){
		var clickedElement = $(this);
		
		$.ajax({
			method: 'POST',
			url: myUrl + 'profile/removeFriend',
			data: {
					'id': userId
				},
			dataType: 'json'
    		}).done( function(data){

                var status = data.status;

                switch( status )
                {
                	case 1:
                		console.log('case1');
                		clickedElement.addClass('btn-primary addFriend').removeClass('btn-danger removeFriend');
                		clickedElement.children('span').html('Add friend');
                		// clickedElement.parent().addClass('addFriend').removeClass('removeFriend');
                		break;
                	case 2:
                		swal(
						  'Warning!',
						  'You are not friends!',
						  'error'
						);
                		break;
                	case 0:
                		swal(
						  'Warning!',
						  'User doesn\'t exists!',
						  'error'
						);
                		break;
                }

    		}).fail( function(data){
    			console.log('fail');
    		});
	});

	$('#friendsButton').on('click', 'button.acceptFriend', function(){
		var clickedElement = $(this);
		
		$.ajax({
			method: 'POST',
			url: myUrl + 'profile/acceptFriend',
			data: {
					'id': userId
				},
			dataType: 'json'
    		}).done( function(data){

                var status = data.status;

                switch( status )
                {
                	case 1:
                		console.log('case1');
                		clickedElement.addClass('btn-danger removeFriend').removeClass('btn-success acceptFriend');
                		clickedElement.children('span').html('Remove frend');
                		// clickedElement.parent().addClass('addFriend').removeClass('removeFriend');
                		break;
                	case 2:
                		swal(
						  'Warning!',
						  'There is no request pending!',
						  'error'
						);
                		break;
                	case 0:
                		swal(
						  'Warning!',
						  'User doesn\'t exists!',
						  'error'
						);
                		break;
                }

    		}).fail( function(data){
    			console.log('fail');
    		});
	});

	/*
	*	Accept and refuse for navigation
	*/
	$('.friendRequests').on('click', 'button.acceptFriend', function(){

		var clickedElement = $(this);
		var userId = clickedElement.attr('data-param');

		$.ajax({
			method: 'POST',
			url: myUrl + 'profile/acceptFriend',
			data: {
					'id': userId
				},
			dataType: 'json'
    		}).done( function(data){

                var status = data.status;

                switch( status )
                {
                	case 1:
                		$('li#request' + userId).fadeOut(300, function() {
                			$(this).remove();
                		})
                		break;
                	case 2:
                		swal(
						  'Warning!',
						  'There is no request pending!',
						  'error'
						);
                		break;
                	case 0:
                		swal(
						  'Warning!',
						  'User doesn\'t exists!',
						  'error'
						);
                		break;
                }

    		}).fail( function(data){
    			console.log('fail');
    		});
	});

	$('.friendRequests').on('click', 'button.refuseFriend', function(){

			var clickedElement = $(this);
			var userId = clickedElement.attr('data-param');
			console.log(userId);
			$.ajax({
				method: 'POST',
				url: myUrl + 'profile/refuseFriend',
				data: {
						'id': userId
					},
				dataType: 'json'
	    		}).done( function(data){

	                var status = data.status;

	                switch( status )
	                {
	                	case 1:
	                		$('li#request' + userId).fadeOut(300, function() {
	                			$(this).remove();
	                		})
	                		break;
	                	case 2:
	                		swal(
							  'Warning!',
							  'There is no request pending!',
							  'error'
							);
	                		break;
	                	case 0:
	                		swal(
							  'Warning!',
							  'User doesn\'t exists!',
							  'error'
							);
	                		break;
	                }

	    		}).fail( function(data){
	    			console.log('fail');
	    		});
	});


	
});