$(document).ready( function(){

	// checking if a string is empty, null or undefined
	function isEmpty(str) {
    	return (!str || 0 === str.length);
	}

	//checking if a string is blank, null or undefined
	function isBlank(str) {
    	return (!str || /^\s*$/.test(str));
	}

	//checking if a string is blank or contains only white-space
	String.prototype.isEmpty = function() {
	    return (this.length === 0 || !this.trim());
	};



    //ajax search users
    $('#userSearch').autocomplete({
        focus: function(event, ui){
            event.preventDefault();
        },
	    source: function( request, response ){
    		$.ajax({
    			method: 'POST',
    			url: myUrl + 'profile/search',
    			data: {
    					'string': request.term
    				},
    			dataType: 'json'
    		}).done( function(data){

                response( data.results );

    		}).fail( function(data){
    			console.log('fail');
    		});
        },
        appendTo: 'body',
        messages: {
            noResults: '',
            results: function() {}
        },
        response: function( event, ui ){
            
            if( !ui.content )
            {
                ui.content = [{label:"<span></span>",value:"No search results!"}];
            }
            
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        
         return $( "<li class='li-auto'></li>" ).data("item.autocomplete", item)
            .append( "<a href=" + myUrl + "profile/" + item.id + "><img src='" + myUrl + "/img/avatars/" + item.avatar + "' class='avatar-icon avatar-icon-s' /><span>"
                         + item.name + "</span></a>" )
            .appendTo( ul );   
    };

});
