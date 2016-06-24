function startDatepicker() {
	$('.datepicker').datepicker({
		format: 'dd.mm.yyyy.',
		startDate: 0,
		todayHighlight: true,
		startView: 0,
		orientation: "bottom auto",
		setDate: new Date()
	});
}

jQuery.fn.exists = function() {
	return this.length > 0;
}

function removeLastTwoElementsFromString(members)
{
	var randpomVarName = '';
	
	if( members == null )
	{
		randpomVarName = 'Anyone';
	}
	else
	{
		if( members.length > 0 )
		{
			for( var i = 0; i < members.length; ++i )
			{
				for( var j = 0; j < options_length; ++j )
				{
					if( members[i] == options[j].id )
					{
						randpomVarName += options[j].name + ', ';
						break;
					}
				}
			}
			randpomVarName = randpomVarName.substring(0, randpomVarName.length - 2);
		}
		else
		{
			randpomVarName = 'Anyone';
		}		
	}
	return randpomVarName;
}