google.load("language", "1");


// google api code
// highly modified by me!

function translate( inputField , outputField , fromLang , toLang , ajaxWorking ) 
{
	$("#" + ajaxWorking).html("<img src='images/ajax-loader.gif' width='20' height='20' />");
    var text = document.getElementById( inputField ).innerHTML; 
    google.language.translate( text , fromLang , toLang , function( result ) 
    {
        var translated = document.getElementById( outputField );

        if ( result.translation ) 
        {
            translated.innerHTML = result.translation;
            $("#" + ajaxWorking).hide();
        }
        else
        {
        	$("#" + ajaxWorking).html("<img src='images/error.gif' width='20' height='20' />");
        }
    });
}     

function detectLanguage( element, lang, disable ) 
{
	var text = document.getElementById(element).innerHTML;	

	google.language.detect(text, function(result) 
	{
	    if (!result.error) 
	    {
	    	var langCode = result.language;
	    	
	    	if ( langCode  == lang )
	    	{
	    		$("#" + disable).hide();
	    	}
  	
	    }
	    else
	    {
	    	$("#" + disable).hide();
	    }
	});
}
// end google api code


function changeLanguage( language )
{
	var current = new String(window.location);
	current = current.replace(/(\?lang=\d*)/g, "");
	current = current.replace(/(\&lang=\d*)/g, "");
	var contains = current.indexOf('?');
	if(contains > -1)        		
		window.location = current + '&lang=' + language;
	else
		window.location =current + '?lang=' + language; 	
}


function passwordChanged( password ) 
{
	var strength = document.getElementById( "strength" );
	var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\W).*$", "g");
	var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	var enoughRegex = new RegExp("(?=.{6,}).*", "g");
	
	if ( password.length == 0 ) 
    {
	    strength.innerHTML = 'Type Password';
	} 
	else if ( false == enoughRegex.test( password ) ) 
    {
	    strength.innerHTML = 'More Characters';
	} 
	else if ( strongRegex.test( password ) ) 
    {
	    strength.innerHTML = '<span style="color:green">Strong!</span>';
	} 
	else if ( mediumRegex.test( password ) ) 
    {
	    strength.innerHTML = '<span style="color:orange">Medium!</span>';
	} 
	else 
    { 
	    strength.innerHTML = '<span style="color:red">Weak!</span>';
	}
}




function displayMessage( rowid )
{
	var row = "#messageBody" + rowid;
	var msg = $(row);
	
	if (msg.css('display') == 'block')
	{
		msg.css("display","none");
	}
	else
	{	
		msg.css("display","block");
	}
}


function showmenu( ele )
{
	if( $("#"+ele).text().indexOf('⇑') != -1 ||  $("#"+ele).text().indexOf('⇓') != -1 )
		return;
	
	$("#"+ele).animate(
		{ 
			opacity: 1.0,
	  	  	backgroundColor: "#ffcccc"
		}, 
		500 , 
		function()
		{
			$("#"+ele).animate(
				{ 
					opacity: 1.0,
					backgroundColor: "#ffffff"
				}, 
				300				
			);
		}
	);	
}


function showauction( ele )
{
	$("#"+ele).animate(
	{
		opacity : 1.0
	}, 500);

}


function hideresults()
{			
	var results = $( "#results" );
	setTimeout(function () { results.hide(500); }, 200);
	
}

function select( txt )
{
	var searchinput = $( "#searchBox" );
	$(searchinput).val(txt);
	var results = $( "#results" );
	results.hide(500);
}

function search()
{
	var searchBox = $( "#searchBox" );			
	window.location = "search.php?term=" + searchBox.val();
}


function fadeinMenu()
{	
	var delay = 500;
	var randomsequence = Math.floor(Math.random()*4);
	
	if( randomsequence == 0)
	{
		for( var x = 0; x < 2; x++ )
		{
			for( var y = 1; y < 20; y++ )
			{
				var menuBlock = "menuBlock" + y;
				if ( $("#"+menuBlock).length )
				{
					window.setTimeout("showmenu('" + menuBlock + "')", delay);
					delay += 200;
				}
				else
				{
					continue;
				}
			}
		}	
	}
	else if( randomsequence == 1)
	{
		var nums = [];
		nums[0] = 0;
		nums[1] = 1;
		nums[2] = 2;
		nums[3] = 3;
		nums[4] = 4;
		nums[5] = 5;
		nums[6] = 6;
		nums[7] = 7;
		nums[8] = 8;
		nums[9] = 9;
		nums[10] = 10;
		nums[11] = 11;
		nums[12] = 12;
		nums[13] = 13;
		nums[14] = 14;
		nums[15] = 15;
		nums[16] = 16;
		nums[17] = 17;
		nums[18] = 18;
		nums[19] = 19;
		
		var random = Math.floor( Math.random() * nums.length );
		var left = nums.length;
		
		var x = 0;
		
		var delay = 500;
		
		while( x != left )
		{
			random = Math.floor( Math.random() * left );
			
			// do thing
			var menuBlock = "menuBlock" + random;
			if ( $("#"+menuBlock).length )
			{
				window.setTimeout("showmenu('" + menuBlock + "')", delay);
				delay += 200;
			}
			
			for( var b = random; b < left -1; b++ )
			{
				nums[b] = nums[b+1];
			}	
			
			left--;
		}	
	}
	else if( randomsequence == 2)
	{
		var delay = 500;
		
		for( var x = 0; x < 2; x++ )
		{		
			for( var y = 19; y > -1; y-- )
			{
				var menuBlock = "menuBlock" + y;
				if ( $("#"+menuBlock).length )
				{
					window.setTimeout("showmenu('" + menuBlock + "')", delay);
				}
				else
				{
					continue;
				}
			}			
			delay += 1000;
		}
	}
	else
	{
		for( var x = 0; x < 2; x++ )
		{
			for( var y = 19; y > -1; y-- )
			{
				var menuBlock = "menuBlock" + y;
				if ( $("#"+menuBlock).length )
				{
					window.setTimeout("showmenu('" + menuBlock + "')", delay);
					delay += 200;
				}
				else
				{
					continue;
				}
			}
		}	
	}
}


function fadeinAuctions()
{	
	var delay = 500;
	
	for(var y = 1; y < 250; y++)
	{
		var auctionBlock = "a_auction_" + y;
		if ( $("#"+auctionBlock).length )
		{
			window.setTimeout("showauction('" + auctionBlock + "')", delay);
			delay += 200;
		}
		else
		{
			continue;
		}
	}	
}


function prepareAutoSuggest( theInputField )
{
	var searchBox = $( "#" + theInputField );
	var results = $( "#results" );
	searchBox.blur(hideresults);
	var top = 0;
	
	$('#datepicker').datepicker({   
		changeMonth: true,
		changeYear: true, 			
		yearRange: '1910:2000',
		dateFormat: 'dd-mm-yy'
	});

	
	if ($.browser.mozilla) 
	{
	    top = searchBox.offset().top + searchBox.outerHeight() + 5;
	}
	else
	{
		top = searchBox.offset().top + searchBox.outerHeight() + 15;
	}
	
	results.css({
		left: (searchBox.offset().left + "px"),
		top: (top + "px"),
		width: ((searchBox.outerWidth() * 2) + "px")
	});
 
	searchBox.attr( "autocomplete", "off" );
	var xhr = null;
	var resultsTimer = null;

	var getResults = function( query )
	{
		xhr = $.ajax({
			type: "get",
			url: "search.php",
			data: {
				ajaxsearch: query
			},
			dataType: "html",
			success: function( response )
			{
				results.html( response );
				if (response.length)
				{
					results.show(500); 
				} 
				else 
				{
					results.hide(500); 
				}
			}
		});
	}; 

	searchBox.keyup(
		function( event )
		{
			var c = event.which ? event.which : event.keyCode;
			
			if( theInputField == "searchBox" )
			{
				if( c == 13  && searchBox.val().length > 0) 
		        {        	
		            $(this).blur();
		            $('#quickSearchButton').focus().click();
		            event.preventDefault();
		            return false;
		        }
			}
			else
			{
				if( c == 13  && searchBox.val().length > 0) 
		        {        	
		            $(this).blur();
		            $('#searchButton').focus().click();
		            event.preventDefault();
		            return false;
		        }
			}
	        
			
			clearTimeout( resultsTimer );
			if (xhr)
			{
				xhr.abort();
			} 
			var query = searchBox.val();
			if(query.length == 0)
			{
				results.hide(500);
				return;
			}
			resultsTimer = setTimeout(
				function(){
					getResults( query );
				},
				150
			);
		}
	);		
}


function flashButton( ele )
{
	$("#"+ele).animate(
		{ 
			fontSize: "10pt",
	  	  	backgroundColor: "#ffcccc"
		}, 
		200 , 
		function()
		{
			$("#"+ele).animate(
				{ 
					fontSize: "8pt",
					backgroundColor: "#ffffff"
				}, 
				200				
			);
		}
	);		
}


function flashLogin()
{
	for( var y = 1; y < 20; y++ )
	{
		var menuBlock = "menuBlock" + y;
		var delay = 1;
		if ( $("#"+menuBlock).length )
		{			
			
		}
		else
		{			
			menuBlock = "menuBlock" + (y-1);
			window.scrollTo(0,$("#top").offset().top);
			for(var g = 0; g < 10; g++)
			{
				window.setTimeout("flashButton('" + menuBlock + "')", delay);
				delay += 400;
			}
			break;
		}
	}	
}


$(document).ready(function()
{
	
	$(".menuBlock, .pageBox, .pictureBox, .textinput").mouseover(function( ) {
		$(this).animate(
		{
			backgroundColor: "#ffcccc"
		}, 500);
	});
	
	$(".menuBlock, .pageBox, .pictureBox, .textinput").mouseout(function( ) {
		$(this).animate(
		{
			backgroundColor: "#ffffff"
		}, 500);
	});	
	
	
	$(".button").mouseover(function( ) {
		$(this).animate(
		{
			backgroundColor: "#ffffff"
		}, 500);
	});
	
	$(".button").mouseout(function( ) {
		$(this).animate(
		{
			backgroundColor: "#f2f2f2"
		}, 500);
	});	
	
	
	$(".button").mousedown(function( ) {
		$(this).animate(
		{
			backgroundColor: "#ddddff"
		}, 5);
	});
	
	$(".button").mouseup(function( ) {
		$(this).animate(
		{
			backgroundColor: "#f2f2f2"
		}, 50);
	});	
		
	// fadein in menus
	fadeinMenu();
		
	// fancy opacity auctions fadein
	fadeinAuctions();
	
	//setup search auto suggest
	prepareAutoSuggest( "searchBox" );
				
});