@header@
@adminmenu@

<div id='selector'></div><br /><br />
<div id='content'></div><br /><br />
<div id='result'></div><br />


<div id="deleteconfirm" title="%delete% %user%?">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		%deleteuserwarning%
	</p>
	<p>
		%aresure%
	</p>
</div>


<div id="banconfirm" title="%ban% %user%?">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		%aresure%
	</p>
	<p>
		
	</p>
</div>


<script language="javascript" type="text/javascript">

<!--

$(document).ready(function()
{	

	$("#deleteconfirm").dialog({
		resizable: false,
		height:170,
		autoOpen: false,
		modal: true,
		buttons: {
			'%delete%': function() {
				$(this).dialog('close');
				$.get("admin.php", 
					{ 
						ajaxedit: "users",
						ajaxdelete: "true",
						ajaxeditdata:  $( "#users" ).val()
					},
					function(data)
				   	{
					   	var val = $( "#users" ).val();
					   	var name = $('#users option:selected').text();						  	
						userSelect = userSelect.replace( "<option value='" + val +"' >" + name + "</option>" , "" );
						$("#users option[value='"+val+"']").remove();
						$("#content").html( "" );
					   	$("#result").html( "<h4 style='color:red'>" + data + "</h4>" );
				   	}
			     );					
			},
			'%cancel%': function() {
				$(this).dialog('close');
			}
		}
	});


	$("#banconfirm").dialog({
		resizable: false,
		height:170,
		autoOpen: false,
		modal: true,
		buttons: {
			'%ban%': function() {
				$(this).dialog('close');
				$.get("admin.php", 
					{ 
						ajaxedit: "users",
						ajaxban: "true",
						ajaxeditdata:  $( "#users" ).val()
					},
					function(data)
				   	{
						$("#result").html( "<h4 style='color:red'>" + data + "</h4>" );
					   	$("#content").html( "" );
				   	}
			     );		
			},
			'%cancel%': function() {
				$(this).dialog('close');
			}
		}
	});




	var catmin = "@categorySelectMinimal@";	
	var subcat = "<select id='subcategory' name='subcategory' disabled='disabled'><option value='-1'>%select% %sub% %category%</option></select>";

	var userSelect = "<br /><h2>%select% %username%</h2>@userSelect@";
	var categoryopt = "<br /><br /><br /><table style='width:700px'><tr><td><h2>%add% %category%</h2><input type='text' class='textinput'/><h4>%to%</h4> " + catmin + " " + subcat + "</td></tr><tr><td><br /><br /><br /><br /><h2>%delete% %category%</h2> " + catmin + " " + subcat + " <input type='button' class='button' value='%delete% %category%'>  </td></tr><tr><td><br /><br /><br /><br /><h2>%change% %category% %name%</h2> " + catmin + " " + subcat + " <h4>%to%</h4><input type='text' class='textinput'/> <input type='button' class='button' value='%change% %name%'> </td></tr></table>";
	
	
	$( "#menuBlock12" ).click(function()
	{
		$("#result").empty();
		$("#content").empty();
		$("#selector").empty();
		$("#selector").append( categoryopt );	
		


		$( "#category" ).change(function()
		{
			$.get("auction.php", { categoryid: $("#category").val(), ajax: "true" },
		   function(data)
		   {
		   		var cats = data.split(',');  

		   		$('#subcategory').empty();
		   		$("#subcategory").append("<option value='-1'>%select% %sub% %category%</option>");

		   		for(var t=0; t < cats.length -1; t++)
		   		{
		   			var cat = cats[t].split('|');
		   			$("#subcategory").append("<option value='" + cat[0] +"'>" + cat[1] + "</option>");
		   		}
		   		$("#subcategory").removeAttr('disabled');
		   });
		});		
	});

	


	$( "#menuBlock13" ).click(function()
	{
		var tempUserSelect = userSelect.replace( "name='seller'" , "name='users'" );
		tempUserSelect = tempUserSelect.replace( "id='seller'" , "id='users'" );
		$("#result").empty();
		$("#content").empty();
		$("#selector").empty();
		$("#selector").append( tempUserSelect );	
		connect( 'users' );	
	});

	


	$( "#menuBlock14" ).click(function()
	{
		var tempUserSelect = userSelect.replace( "name='seller'" , "name='auctions'" );
		tempUserSelect = tempUserSelect.replace( "id='seller'" ,  "id='auctions'" );
		$("#result").empty();
		$("#content").empty();
		$("#selector").empty();
		$("#selector").append( tempUserSelect );
		connect( 'auctions' );
	});

	


	$( "#menuBlock15" ).click(function()
	{
		var tempUserSelect = userSelect.replace( "name='seller'" , "name='messages'" );
		tempUserSelect = tempUserSelect.replace( "id='seller'" ,  "id='messages'" );
		$("#result").empty();
		$("#content").empty();
		$("#selector").empty();
		$("#selector").append( tempUserSelect );
		connect( 'messages' );
	});

	


	$( "#menuBlock16" ).click(function()
	{
		var tempUserSelect = userSelect.replace( "name='seller'" , "name='feedback'" );
		tempUserSelect = tempUserSelect.replace( "id='seller'" ,  "id='feedback'" );
		$("#result").empty();
		$("#content").empty();
		$("#selector").empty();
		$("#selector").append( tempUserSelect );
		connect( 'feedback' );
	});

	


	$( "#menuBlock17" ).click(function()
	{
		var tempUserSelect = userSelect.replace( "name='seller'" , "name='bids'" );
		tempUserSelect = tempUserSelect.replace( "id='seller'" ,  "id='bids'" );
		$("#result").empty();
		$("#content").empty();
		$("#selector").empty();
		$("#selector").append( tempUserSelect );
		connect( 'bids' );
	});

	


	$( "#menuBlock18" ).click(function()
	{
		var tempUserSelect = userSelect.replace( "name='seller'" , "name='watching'" );
		tempUserSelect = tempUserSelect.replace( "id='seller'" ,  "id='watching'" );
		$("#result").empty();
		$("#content").empty();
		$("#selector").empty();
		$("#selector").append( tempUserSelect );
		connect( 'watching' );
	});



	
	$( "#menuBlock19" ).click(function()
	{
		var tempUserSelect = userSelect.replace( "name='seller'" , "name='pictureuser'" );
		tempUserSelect = tempUserSelect.replace( "id='seller'" ,  "id='pictureuser'" );
		$("#result").empty();
		$("#content").empty();
		$("#selector").empty();
		$("#selector").append( tempUserSelect );
		connect( 'pictureuser' );
	});	
	
});







function deleteUser( user )
{
	$("#deleteconfirm").dialog('open');	
}








function banUser( user )
{
	$("#banconfirm").dialog('open');
}








function unbanUser( user )
{
	$.get("admin.php", 
		{ 
			ajaxedit: "users",
			ajaxeditdata:  $( "#users" ).val()
		},
		function(data)
	   	{
			$("#result").html( "<h4 style='color:red'>" + data + "</h4>" );
		   	$("#content").html( "" );
	   	}
    );	
}











function updateAuction( auctionButton )
{
	var id = $(auctionButton).attr('id');
	id = id.replace("change","");
	var date = $("#date" + id).val();
	var time = $("#time" + id).val();
	var auction = $("#auction" + id).val();

	$.get("admin.php", 
		{ 
			ajaxedit: "auctions",
			ajaxeditdata:  auction + "|" +  date + "|" +  time
		},
		function(data)
	   	{
			$("#result").html( "<h4 style='color:red'>" + data + "</h4>" );
		   	$("#content").html( "" );
	   	}
    );
}








function deleteAuction( auctionButton )
{
	var id = $(auctionButton).attr('id');
	id = id.replace("delete","");
	var auction = $("#auction" + id).val();

	$.get("admin.php", 
		{ 
			ajaxedit: "auctions",
			ajaxdelete: "true",
			ajaxeditdata:  auction
		},
		function(data)
	   	{
			$("#result").html( "<h4 style='color:red'>" + data + "</h4>" );
		   	$("#content").html( "" );
	   	}
    );
}







function connect( id )
{
	$( "#" + id ).change(function()
	{
		if( $( "#" + id ).val() == "0" )
			return;
		
		$.get("admin.php", 
		{ 
			ajaxfetch: id,
			ajaxfetchdata: $( "#" + id ).val()
		},
		function(data)
	   	{
			$("#content").empty();
			$("#result").empty();






			
			
			if ( id == "users" )
			{				
				var user = data.split('|');
				var output = "<br /><br /><table cellpadding='10' style='width:718px'><tr><td style='width:220px'><img src='images/avatars/" + user[13] + "' width='150' height='150'></td>";
				output += "<td>";
				output += "<h2>" + user[2] + "</h2>";
				output += user[5] + " " + user[6] + "<br />";
				output += user[14] + "<br /><br /><br /><br />";
				if( user[1] == "6" )
				{
					output += " <input type='button' class='button' onclick=\"unbanUser()\" value='%unban% %user%'> ";
				}
				else
				{
					output += " <input type='button' class='button' onclick=\"banUser()\" value='%ban% %user%'> ";
				}				
				output += " <input type='button' class='button' onclick=\"deleteUser()\" value='%delete% %user%'> ";
				output += "</td></tr></table>";
				data = output;
			}	

			var iter = 0;




			


			
			if ( id == "auctions" )
			{				
				var auctions = data.split('#');
				iter = auctions.length -1;

				var output = "<br /><br /><table cellpadding='10' style='width:718px'>";
				
				for ( var h=0; h < auctions.length -1; h++)
				{	
					var row = auctions[h].split('|');

					var date = row[2].split(' ');
					
					output += "<tr style='border-bottom:1px dashed #000000;'>";
					output += "<td style='vertical-align:top'><img src='images/auction/" + row[3] + "' width='70' height='70'/></a></td>";
					output += "<td style='vertical-align:top'><a href='viewauction.php?viewid=" + row[0] + "'>" + row[1] + "</a><br/><br/><br/>%end% %date% &nbsp;&nbsp; ";
					output += "<input style='width:80px' name='date" + h + "' id='date" + h + "' class='textinput' type='text' value='" + date[0] + "'/> &nbsp;&nbsp; %time% &nbsp;&nbsp;";
					output += "<input style='width:60px' name='time" + h + "' id='time" + h + "' class='textinput' type='text' value='" + date[1] + "'/> &nbsp;&nbsp; <span class='pageBox' id='change" + h + "'>%change%</span></td>";
					output += "<td style='vertical-align:top'><br />";
					output += "<span class='pageBox' id='delete" + h + "'>%delete%</span><input type='hidden' id='auction" + h + "' value='" + row[0] + "'/></td>";
					output += "</tr>";
				}				
				output += "</table>";
				
				data = output;
			}	 








			
			
			if ( id == "messages" )
			{				
				var msgs = data.split('#');
				iter = msgs.length -1;

				var output = "<br /><br /><table cellpadding='10' style='width:718px'>";
				
				for ( var h=0; h < msgs.length -1; h++)
				{	
					var row = msgs[h].split('|');
					
					output += "<tr><td style='vertical-align:top'><h4>" + row[2] + "</h4></td></tr>";
					output += "<tr><td style='vertical-align:top'>" + row[3] + "</td></tr>";
					output += "<tr><td style='vertical-align:top'><input type='button' class='button' onclick=\"deleteMsg()\" value='%delete%'><input type='hidden' id='msg" + h + "' value='" + row[0] + "'/></td></tr>";
				}				
				output += "</table>";
				
				data = output;
			}	 








			


			if ( id == "feedback" )
			{				
				var msgs = data.split('#');
				iter = msgs.length -1;

				var output = "<br /><br /><table cellpadding='10' style='width:718px'>";
				
				for ( var h=0; h < msgs.length -1; h++)
				{	
					var row = msgs[h].split('|');					

					output += "<tr><td style='vertical-align:top'>" + row[5] + "</td></tr>";
					output += "<tr><td style='vertical-align:top'><input type='button' class='button' onclick=\"deleteFeedback()\" value='%delete%'><input type='hidden' id='msg" + h + "' value='" + row[0] + "'/></td></tr>";
				}				
				output += "</table>";
				
				data = output;
			}








			



			if ( id == "bids" )
			{				
				var msgs = data.split('#');
				iter = msgs.length -1;

				var output = "<br /><br /><table cellpadding='10' style='width:718px'>";
				
				for ( var h=0; h < msgs.length -1; h++)
				{	
					var row = msgs[h].split('|');					

					output += "<tr><td style='vertical-align:top'>" + row[5] + "</td></tr>";
					output += "<tr><td style='vertical-align:top'><input type='button' class='button' onclick=\"deleteFeedback()\" value='%delete%'><input type='hidden' id='msg" + h + "' value='" + row[0] + "'/></td></tr>";
				}				
				output += "</table>";
				
				data = output;
			}







			



			if ( id == "watching" )
			{				
				var msgs = data.split('#');
				iter = msgs.length -1;

				var output = "<br /><br /><table cellpadding='10' style='width:718px'>";
				
				for ( var h=0; h < msgs.length -1; h++)
				{	
					var row = msgs[h].split('|');					

					output += "<tr><td style='vertical-align:top'>" + row[5] + "</td></tr>";
					output += "<tr><td style='vertical-align:top'><input type='button' class='button' onclick=\"deleteFeedback()\" value='%delete%'><input type='hidden' id='msg" + h + "' value='" + row[0] + "'/></td></tr>";
				}				
				output += "</table>";
				
				data = output;
			}







			




			if ( id == "pictures" )
			{				
				var msgs = data.split('#');
				iter = msgs.length -1;

				var output = "<br /><br /><table cellpadding='10' style='width:718px'>";
				
				for ( var h=0; h < msgs.length -1; h++)
				{	
					var row = msgs[h].split('|');					

					output += "<tr><td style='vertical-align:top'>" + row[5] + "</td></tr>";
					output += "<tr><td style='vertical-align:top'><input type='button' class='button' onclick=\"deleteFeedback()\" value='%delete%'><input type='hidden' id='msg" + h + "' value='" + row[0] + "'/></td></tr>";
				}				
				output += "</table>";
				
				data = output;
			}
			





			

			

			$("#content").append( data );










			

			if( id == "auctions" )
			{
				for ( var h=0; h < auctions.length -1; h++)
				{
					$("#date" + h).datepicker({   
						changeMonth: true,
						changeYear: true, 			
						maxDate: '+6m',
						minDate: '-6m',
						dateFormat: 'dd-mm-yy'
			    	});
					$("#time" + h).timeEntry({show24Hours: true, spinnerImage: ''});

					$("#change" + h).mouseover(function( ) {
						$(this).animate(
						{
							backgroundColor: "#ffcccc"
						}, 500);
					});
					
					$("#change" + h).mouseout(function( ) {
						$(this).animate(
						{
							backgroundColor: "#ffffff"
						}, 500);
					});

					$("#change" + h).click(function( ) {
						updateAuction( this );
					});


					$("#delete" + h).mouseover(function( ) {
						$(this).animate(
						{
							backgroundColor: "#ffcccc"
						}, 500);
					});
					
					$("#delete" + h).mouseout(function( ) {
						$(this).animate(
						{
							backgroundColor: "#ffffff"
						}, 500);
					});

					$("#delete" + h).click(function( ) {
						deleteAuction( this );
					});
				}
			}		









				
	   	});
	});	
}


//-->

</script>
@footer@