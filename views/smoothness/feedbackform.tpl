@header@

<h2>%leavefeedback%</h2>

<table cellpadding="10">
<tr>
	<td style="width:120px"></td>
	<td><h3>{item}</h3></td>
	<td></td>	
</tr>
<tr>
	<td>%product%</td>
	<td>
		<select name="feedval[]">
			<option value="1">%terrible%</option>
			<option value="2">%bad%</option>
			<option value="3" selected="selected">%acceptable%</option>
			<option value="4">%good%</option>
			<option value="5">%excellent%</option>
		</select>
	</td>
	<td></td>	
</tr>
<tr>
	<td>%promptness%</td>
	<td>
		<select name="feedval[]">
			<option value="1">%terrible%</option>
			<option value="2">%bad%</option>
			<option value="3" selected="selected">%acceptable%</option>
			<option value="4">%good%</option>
			<option value="5">%excellent%</option>
		</select>
	</td>
	<td></td>	
</tr>
<tr>
	<td>%communication%</td>
	<td>
		<select name="feedval[]">
			<option value="1">%terrible%</option>
			<option value="2">%bad%</option>
			<option value="3" selected="selected">%acceptable%</option>
			<option value="4">%good%</option>
			<option value="5">%excellent%</option>
		</select>
	</td>
	<td></td>	
</tr>
<tr>
	<td>%overall%</td>
	<td>
		<select name="feedval[]">
			<option value="1">%terrible%</option>
			<option value="2">%bad%</option>
			<option value="3" selected="selected">%acceptable%</option>
			<option value="4">%good%</option>
			<option value="5">%excellent%</option>
		</select>
	</td>
	<td></td>	
</tr>
<tr>
	<td>%message%</td>
	<td><textarea  class="resizable" name="message" id="message" style="width:400px;height:150px"></textarea></td>
	<td></td>	
</tr>
<tr>
	<td></td>
	<td><input class='button' id='feedbackButton' type='button' value='%leavefeedback%' /></td>
	<td></td>	
</tr>
</table>

<script type="text/javascript" src="jscript/jquery.textarearesizer.compressed.js"></script>
<script type="text/javascript">
	/* jQuery textarea resizer plugin usage */
	$(document).ready(function() 
	{
		$('textarea.resizable:not(.processed)').TextAreaResizer();

		$('#feedbackButton').click(function() 
		{
			var selects = new Array();
			$("*[name=feedval\\[\\]]").each(function() {selects.push($(this).val());});
			
			$.post( "auction.php?feedback=true&auctionid={auctionid}", 
		   		{
		   			'myfeedback' : "true",
		   			'feedval[]' : selects,
		   			'message' : $("#message").val()
		   		}, 
		    	function(data)
		    	{
					window.location = 'auction.php?mybids=all';
		    	}
			);	 
		});

	});
</script>

@footer@

