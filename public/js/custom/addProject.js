$(document).ready( function(){

	startDatepicker();

	dragula([document.getElementById('developers'), document.getElementById('teamMembers')], {
		moves: function( el, source, handle, sibling ){
			if( $(el).hasClass('manager') )
				return false;
			else return true;
		}
	})
		.on('drop', function( el, target, source ){
			var element = $(el);
			var droppedIn = $(target);

			if( droppedIn.attr('id') === 'teamMembers' && members.indexOf(element.attr('id')) === -1 )
			{
				members.push(element.attr('id'));
			}
			else if( droppedIn.attr('id') === 'developers' )
			{
				var index = members.indexOf(element.attr('id'));
				members.splice(index, 1);
			}
			$('#members').val(members);
			console.log('manager is: ' + managerId);
			console.log('members are: ' + members);
			
		});
      
      
	$('select[name="manager"]').change( function(){
		var selected = $('select[name="manager"] option:selected').val();
		
		if( selected != managerId )
		{
			if( members.indexOf(selected) === -1 )
			{
				var memberToManager = $('ul#developers li#' + selected);
				
				memberToManager.fadeOut( 500, function() {
					$(this).detach();

					$('ul#teamMembers li#' + managerId).removeClass('manager');

					memberToManager.addClass('manager').prependTo('#teamMembers').fadeIn( 500);
					
					members.push(managerId);
					managerId = selected;
					$('#members').val(members);
					console.log('manager is: ' + managerId);
					console.log('members are: ' + members);
				});
			}
			else
			{
				$('ul#teamMembers li#' + managerId).removeClass('manager');
				$('ul#teamMembers li#' + selected).addClass('manager');

				var index = members.indexOf(selected);
				members.splice(index, 1);

				members.push(managerId);
				managerId = selected;

				$('#members').val(members);
				console.log('manager is: ' + managerId);
				console.log('members are: ' + members);
			}
		}
	});

	
});