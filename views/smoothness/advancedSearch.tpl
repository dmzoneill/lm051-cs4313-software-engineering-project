@header@

<h2>%search%</h2>

<table style="width:700px" id="searchtable" cellpadding="2">
<tr>
	<td colspan="5" style="border-bottom:1px dashed #ccddcc"></td>
</tr>
<tr style="height:40px" id="searchOpt1">
	<td style="vertical-align:middle; width:50px; padding-left:10px">
	</td>
	<td style="vertical-align:middle; width:110px">
		<select id='column1' name='column[]'>
			<option value='1'>%description%</option>
			<option value='2'>%postage%</option>
			<option value='3'>%bidding% %end%</option>
			<option value='4'>%country% %from%</option>
			<option value='5'>%seller%</option>
			<option value='6'>%condition%</option>
			<option value='7'>%bid% %history%</option>
			<option value='8'>%starting% %bid%</option>
			<option value='9'>%reserve%</option>
			<option value='10'>%category%</option>
			<option value='11'>%sub% %category%</option>
			<option value='12'>%status%</option>
		</select>
	</td>
	<td style="vertical-align:middle; width:60px">
		<select id='condition1' name='condition[]'>
			<option value='1'>%like%</option>
			<option value='2'>&gt;</option>
			<option value='3'>&lt;</option>
			<option value='4'>=</option>
			<option value='5'>!=</option>
		</select>
	</td>
	<td style="vertical-align:middle" id='valueContainer1'>
		<input id='value1' type="text" class="textinput" name="value[]"/>
	</td>
	<td style="vertical-align:middle;text-align:right; width:120px; padding-right:10px">
		<span id="addButton1" class="pageBox" onclick="addOption()">%add%</span>  
	</td>
</tr>
</table>

<table style="border-top:1px dashed #ccddcc;text-align:right;width:700px" cellpadding="2" >
<tr>
	<td style="width:550px;"></td>
	<td style="width:50px;">
		<div id="loading" align="right" style="text-align:right"></div>
	</td>
	<td style="width:100px;text-align:right">
		<br />
		<input type="button" id="searchButton" class="button" value="%search%" />
	</td>
</tr>
</table>


<table style="width:718px;display:none" cellspacing="0" cellpadding="6" id="searchResultsHeader">	
	<tr>
		<td><h2>%search% %results%</h2></td>
	</tr>
</table>

<table style="width:718px;display:none" cellspacing="0" cellpadding="6" id="searchResults" class="tablesorter">	
		
</table>


<div id="dialog-message" title="%search% %error%">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		%errorsexist%,
	</p>
	<p>
		%pleasecheck%.
	</p>
</div>

<script type="text/javascript" src="jscript/jquery.formatCurrency-1.3.0.min.js"></script>
<script type="text/javascript" src="jscript/jquery.formatCurrency.all.js"></script>
<script type="text/javascript" src="jscript/jquery.tablesorter.min.js"></script> 

<script language="javascript" type="text/javascript">
<!--

var rowCount = 1;

function connectButtonHandlers( row )
{
	$("#removeButton" + row).mouseover(function( ) {
		$(this).animate(
		{
			backgroundColor: "#ffcccc"
		}, 500);
	});
	
	$("#removeButton" + row).mouseout(function( ) {
		$(this).animate(
		{
			backgroundColor: "#ffffff"
		}, 500);
	});	

	$("#addButton" + row).mouseover(function( ) {
		$(this).animate(
		{
			backgroundColor: "#ffcccc"
		}, 500);
	});
	
	$("#addButton" + row).mouseout(function( ) {
		$(this).animate(
		{
			backgroundColor: "#ffffff"
		}, 500);
	});			

	$("#value" + row).mouseover(function( ) {
		$(this).animate(
		{
			backgroundColor: "#ffcccc"
		}, 500);
	});
	
	$("#value" + row).mouseout(function( ) {
		$(this).animate(
		{
			backgroundColor: "#ffffff"
		}, 500);
	});
}





function addOption()
{	
	if( rowCount > 11 )
		return;

	var itemSelect = "<select id='column" + (rowCount + 1) + "' name='column[]'><option value='1'>%description%</option><option value='2'>%postage%</option><option value='3'>%bidding% %end%</option><option value='4'>%country% %from%</option><option value='5'>%seller%</option><option value='6'>%condition%</option><option value='7'>%bid% %history%</option><option value='8'>%starting% %bid%</option><option value='9'>%reserve%</option><option value='10'>%category%</option><option value='11'>%sub% %category%</option><option value='12'>%status%</option></select>";
	
	var itemCondition = "<select id='condition" + (rowCount + 1) + "' name='condition[]'><option value='1'>%like%</option><option value='2'>&gt;</option><option value='3'>&lt;</option><option value='4'>=</option><option value='5'>!=</option></select>";
	
	var itemExpression = "<select id='descExp" + (rowCount + 1) + "' name='exp[]'><option value='1'>%and%</option><option value='2'>%or%</option></select>";
	
	var tableRow = "<tr><td colspan='5' style='border-bottom:1px dashed #ccddcc'></td></tr><tr style='height:40px' id='searchOpt" + (rowCount + 1) + "'><td style='vertical-align:middle; padding-left:10px'>" + itemExpression + "</td><td style='vertical-align:middle'>" + itemSelect + "</td><td style='vertical-align:middle'>" + itemCondition + "</td><td id='valueContainer" + (rowCount + 1) + "' style='vertical-align:middle'><input type='text' class='textinput' id='value" + (rowCount + 1) + "' name='value[]'/></td><td style='vertical-align:middle;text-align:right; padding-right:10px'><span id='addButton" + (rowCount + 1) + "' class='pageBox' onclick='addOption()'>%add%</span> <span id='removeButton" + (rowCount + 1) + "' class='pageBox' onclick='removeOption()'>%remove%</span></td></tr>";

		
	$('#searchtable tr:last').after( tableRow );
	$('#removeButton' + rowCount).hide();
	$('#addButton' + rowCount).hide();

	connectButtonHandlers( (rowCount + 1) );
	fieldOption( (rowCount + 1) );

	rowCount++;
}





function removeOption()
{	
	if( rowCount < 1 )
		return;
			
	$('#searchtable tr:last').remove();
	$('#searchtable tr:last').remove();
	$('#removeButton' + (rowCount - 1)).show();
	$('#addButton' + (rowCount - 1)).show();
	
	rowCount--;
}





function showResults ( data )
{
	if ( data == "error" )
	{
		$("#dialog-message").dialog('open');
		$("#loading").html("");
	}
	else
	{	
		$("#searchResults").empty();	
		
		var stuff = data.split("|");
		$("#searchResults").append("<thead id='resultsHead'>\n");
		$("#searchResults").append("<tbody id='resultsBody'>\n");
		
		for( var g =0; g < stuff.length - 1; g++ )
		{			
			var rowData = stuff[g].split(",");
	
			if( g == 0 )
			{			
				var row = "<tr class='auctionItemRow' id='a_auction_" + (g+1) + "'>\n";
				
				for( var h =0; h < rowData.length; h++ )
				{			
					row += "<th>" + rowData[h] + "</th>\n";
				}
				
				row += "</tr>\n";			
				$("#resultsHead").append(row);
			}
			else
			{
				var row = "<tr class='auctionItemRow' id='a_auction_" + (g+1) + "'>\n";
				
				for( var h =0; h < rowData.length; h++ )
				{			
					row += "<td style='vertical-align:middle'>" + rowData[h] + "</td>\n";
				}	
				
				row += "</tr>\n";	
				$("#resultsBody").append(row);
			}		
		}	

		fadeinAuctions();
		
		$("#searchResults").tablesorter();
		$("#loading").html("");
	} 
}




function fieldOption( row )
{	
	prepareAutoSuggest( "value" + row );
	
	$("#column" + row ).change( function (){
	
		if( $("#valueContainer" + row + ":has(select)").length > 0 )
		{
			var input = "<input type='text' class='textinput' id='value" + (row) + "' name='value[]'/>";
			$("#valueContainer" + row).empty();
			$("#valueContainer" + row).append( input );
		}
		
		var id = $(this).val();

		$("#value" + row).unbind();

		if ( id == 1 )
		{
			prepareAutoSuggest( "value" + row );
		}
		else if ( id == 2 || id == 8 || id == 9 )
		{			
			$("#value" + row).blur(function()
			{
				$("#value" + row).formatCurrency();
			});
		}
		else if ( id == 3 )
		{
			$("#value" + row).datepicker({   
				changeMonth: true,
				changeYear: true, 			
				maxDate: '+1m',
				minDate: '+0d',
				dateFormat: 'dd-mm-yy'
	    	});
		}
		else if ( id == 4 )
		{	
			var countrySelect = "@countrySelect@";
			countrySelect = countrySelect.replace( "name='country'" , "name='value[]'" );
			var rowC = "id='value" + row + "'";
			countrySelect = countrySelect.replace( "id='country'" , rowC );

			$("#valueContainer" + row ).empty();
			$("#valueContainer" + row ).append( countrySelect );		
		}
		else if ( id == 5 )
		{	
			var userSelect = "@userSelect@";
			userSelect = userSelect.replace( "name='seller'" , "name='value[]'" );
			var rowC = "id='value" + row + "'";
			userSelect = userSelect.replace( "id='seller'" , rowC );

			$("#valueContainer" + row ).empty();
			$("#valueContainer" + row ).append( userSelect );		
		}		
		else if ( id == 10 )
		{
			var categories = "@categorySelectMinimal@";
			categories = categories.replace( "name='category'" , "name='value[]'" );
			var rowC = "id='value" + row + "'";
			categories = categories.replace( "id='category'" , rowC );
			
			$("#valueContainer" + row ).empty();
			$("#valueContainer" + row ).append( categories );
		}
		else
		{
				
		}
	});	
}





function updateSubCats()
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
}






var rowResult = 1;

$(document).ready( function()
{	
	fieldOption( 1 );

	$(function() {
		$("#dialog-message").dialog({
			modal: true,
			autoOpen: false,
			buttons: {
				Ok: function() {
					$(this).dialog('close');
				}
			}
		});
	});
	

	$("#searchButton").click(function()
	{
		$("#loading").html("<br /><img src='images/ajax-loader.gif'>");
		$("#searchResultsHeader").show();
		$("#searchResults").show();				

		var selectedCols = new Array();
		var selectedCons = new Array();
		var selectedExps = new Array();
		selectedExps[0] = "";
		var selectedVals = new Array();

		$("select[name=column\\[\\]]").each(function() {selectedCols.push($(this).val());});
		$("select[name=condition\\[\\]]").each(function() {selectedCons.push($(this).val());});
		$("select[name=exp\\[\\]]").each(function() {selectedExps.push($(this).val());});
		$("*[name=value\\[\\]]").each(function() {selectedVals.push($(this).val());});
		

	    if(selectedCols.length == 0) 
		{ 
			selectedCols = "none"; 
	    }       

	    if(selectedCons.length == 0) 
		{ 
	    	selectedCons = "none"; 
	    }  

	    if(selectedExps.length == 0) 
		{ 
	    	selectedExps = "none"; 
	    }  

	    if(selectedVals.length == 0) 
		{ 
	    	selectedVals = "none"; 
	    } 

	    $.post( "search.php", 
	   		{
	   			'columns[]' : selectedCols,
	   			'condition[]' : selectedCons,
	   			'exp[]' : selectedExps,
	   			'vals[]' : selectedVals
	   		}, 
	    	function(data)
	    	{
				showResults(data);
	    	}
		);	   
	});
});

// -->
</script>		


@footer@

