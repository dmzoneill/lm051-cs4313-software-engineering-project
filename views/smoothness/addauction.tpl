@header@

<h2>%auction% %item%</h2>

<form action='auction.php?agreement=true&submit=true' method='post' id="auctionForm">
<table style="width:630px;">

<tr>
	<td style="vertical-align:top;color:#22229E;" colspan="3">%select% %category%</td>
</tr>
<tr>
	<td colspan="3"><hr /></td>
</tr>
<tr>
	<td style="padding-left:30px;vertical-align:top;" colspan="3"><br/>@categorySelector@ <br/><br/></td><td style="vertical-align:top;"></td>
</tr>
<tr>
	<td style="vertical-align:top;color:#22229E;" colspan="3">%item%</td>
</tr>
<tr>
	<td colspan="3"><hr /></td>
</tr>
<tr>
	<td style="padding-left:30px;vertical-align:top;width:200px"><br/>%item%</td><td colspan="2"><br/><input id="itemname" name="itemname" class='textinput' type="text" value="{itemname}"/> <span style="color:#cc0000;" id="itemnameInfo">{auction-itemname}</span><br /><br /><br/><br/></td>
</tr>
<tr>
	<td style="vertical-align:top;color:#22229E;" colspan="3">%description%</td>
</tr>
<tr>
	<td colspan="3"><hr /></td>
</tr>
<tr>
	<td colspan="3" style="padding-left:30px;"><br/><textarea id="description" name="description" style="height:150px" class="resizable" > {description} </textarea><br/><span style="color:#cc0000;" id="descriptionInfo">{auction-description}</span><br/></td>
</tr>

<tr>
	<td style="vertical-align:top;color:#22229E;" colspan="3">%reserve% &amp; %starting% %price% </td>
</tr>
<tr>
	<td colspan="3"><hr /></td>
</tr>
<tr>
	<td style="padding-left:30px;vertical-align:top;"><br/>%reserve%</td><td colspan="2"><br/><input class="textinput" name="reserve" id="currencyReserve" type="text" value="{reserve}" /><br/><span style="color:#cc0000;" id="descriptionInfo">{auction-reserve}</span></td>
</tr>
<tr>
	<td style="padding-left:30px;vertical-align:top;"><br/>%starting% %bid%</td><td colspan="2"><br/><input class="textinput" name="startingbid" id="currencyStart" type="text" value="{startingbid}" /><br/><span style="color:#cc0000;" id="descriptionInfo">{auction-startingbid}</span></td>
</tr>
<tr>
	<td style="padding-left:30px;vertical-align:top;"><br/>%postage%</td><td colspan="2"><br/><input class="textinput" name="postage" id="currencyPostage" type="text" value="{postage}" /><br/><span style="color:#cc0000;" id="descriptionInfo">{auction-postage}</span><br/><br/></td>
</tr>

<tr>
	<td style="vertical-align:top;color:#22229E;" colspan="3">%bidding% %end% </td>
</tr>
<tr>
	<td colspan="3"><hr /></td>
</tr>
<tr>
	<td style="padding-left:30px;vertical-align:top;"><br/>%end% %date%</td><td colspan="2"><br/><input class='textinput' name="datepick" type="text" id="datepick" value="{datepick}" /> <span style="color:#cc0000;" id="dateInfo">{auction-datepick}</span><br/></td>
</tr>
<tr>
	<td style="padding-left:30px;vertical-align:top;"><br/>%end% %time%</td><td colspan="2"><br/><input class='textinput' id="timestart" name="starttime" type="text" value="{starttime}" /><br/><span style="color:#cc0000;" id="timeInfo">{auction-starttime}</span><br/><br/></td>
</tr>
<tr>
	<td style="vertical-align:top;color:#22229E;" colspan="3">%pictures%</td>
</tr>
<tr>
	<td colspan="3"><hr /></td>
</tr>
<tr>
	<td style="padding-left:30px;vertical-align:top;">
		<div class="wrapper">
			<div id="button1" class="button">%upload%</div>
		</div><br/><br/><br/>
	</td>
	<td colspan='2'>
		<ol id="files"></ol>
		{auction-imagefiles}
	</td>
</tr>

<tr>
	<td style="vertical-align:top;color:#22229E;" colspan="3">%submit% %auction% </td>
</tr>
<tr>
	<td colspan="3"><hr /></td>
</tr>
<tr>
	<td style="padding-left:30px;vertical-align:top;"><input class='button' id="submitButton" type='button' value='%post% %auction%' /></td><td><br/><br/></td><td></td>
</tr>

</table>

<input type='hidden' value='{imagefiles}' id="imagefiles" name="imagefiles" />



</form>

<div id="dialog-message" title="%auction% %error%">
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
<script type="text/javascript" src="jscript/jquery.textarearesizer.compressed.js"></script>
<script type="text/javascript" src="jscript/ajaxupload.3.5.js"></script>
<script type="text/javascript">

var t = 0;

	function remove( pic )
	{
		var old = $('#imagefiles').val();
		var imgs = old.split(',');

		var newdata = "";

		for(var y = 0; y < imgs.length; y++)
		{
			if(y!=pic)
			{
				newdata = imgs[y] + ",";
			}
		}

		$('#imagefiles').val(newdata);
		var name = "#image" + pic;
		$(name).remove();
	}


	function imagePop()
	{
		var old = $('#imagefiles').val();

		if(old.indexOf(',') < 0)
			return;
		
		var imgs = old.split(',');

		for(var y = 0; y < imgs.length -1; y++)
		{
			$("<li id='image" + t + "'></li>").appendTo('#files').html("<img class='uploadExtra' src='images/auction/temp/" + imgs[y] +"' width='150' height='100'> <img width='15' height='15' src='images/error.gif' onclick=\"remove('"+ t +"')\" />");
			t++;
		}
	}

	
	$(document).ready(function() 
	{
		$('textarea.resizable:not(.processed)').TextAreaResizer();

		$('#currencyReserve').blur(function()
		{
			$('#currencyReserve').formatCurrency();
		});

		$('#currencyStart').blur(function()
		{
			$('#currencyStart').formatCurrency();
		});

		$('#currencyPostage').blur(function()
		{
			$('#currencyPostage').formatCurrency();
		});

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


		$(function() 
		{
			$('#timestart').timeEntry({show24Hours: true, spinnerImage: ''});
		});
	

		$(function() 
    	{
    		$('#datepick').datepicker({   
			changeMonth: true,
			changeYear: true, 			
			maxDate: '+1m',
			minDate: '+1d',
			dateFormat: 'dd-mm-yy'
    		});
    	});

		imagePop();

	});



    var current = new String(window.location);
    var state = current.indexOf('profile.php');
    var dir = "";
    var page = "";
    if(state > -1)
    {
        page = "profile.php";
        dir = "images/avatars/";
    }
    else
    {
        page = "auction.php";
        dir = "images/auction/temp/";
    }

    var btnUpload=$('#upload');
    var status=$('#status');
    var button = $('#button1'), interval;
    new AjaxUpload(button, {
    	action: page, 
    	name: 'uploadfile',
    	onSubmit : function(file, ext){
    		// change button text, when user selects file			
    		button.text('%uploading%');
    						
    		// If you want to allow uploading only 1 file at time,
    		// you can disable upload button
    		this.disable();
    		
    		// Uploding -> Uploading. -> Uploading...
    		interval = window.setInterval(function(){
    			var text = button.text();
    			if (text.length < 13){
    				button.text(text + '.');					
    			} else {
    				button.text('%uploading%');				
    			}
    		}, 200);
    	},
    	onComplete: function(file, response){
    		button.text('Upload');
    					
    		window.clearInterval(interval);
    					
    		// enable upload button
    		this.enable();

    		if(response!="error")
    		{
    			// add file to the list
    			var old = $('#imagefiles').val();
    			$('#imagefiles').val(old + "," + response);
    			$("<li id='image" + t + "'></li>").appendTo('#files').html("<img class='uploadExtra' src='images/auction/temp/" + response +"' width='150' height='100'> <img width='15' height='15' src='images/error.gif' onclick=\"remove('"+ t +"')\" />");
    			t++;
    		}						
    	}
    });   

	
	var submitbutton = $("#submitButton");
	
	var category = $('#category');
	category.blur(validateCategory);

	var subcategory = $('#subcategory');
	subcategory.blur(validatesubCategory);

	var itemname = $('#itemname');
	itemname.blur(validateItemname);

	var desc = $('#description');
	desc.blur(validateDescription);
	

	submitbutton.click(function()
	{
		if(validateCategory() && validatesubCategory() && validateItemname() && validateDescription() && validateDate() )
		{
			$(submitbutton).attr("disabled", "disabled");
			$(submitbutton).val("%pleasewait%"); 
			$("#auctionForm").submit();
		}
		else
		{
			$("#dialog-message").dialog('open');
			return false;
		}
	});


	function validateDescription()
	{
		if($('#description').val().length < 200)
		{
			$('#descriptionInfo').text("%tooshort%");
			$('#description').addClass("error");
			return false;
		}
		else
		{
			$('#descriptionInfo').text("");
			$('#description').removeClass("error");
			return true;
		}
	}


	function validateItemname()
	{
		if($('#itemname').val().length < 20)
		{
			$('#itemnameInfo').text("%tooshort%");
			$('#itemname').addClass("error");
			return false;
		}
		else
		{
			$('#itemnameInfo').text("");
			$('#itemname').removeClass("error");
			return true;
		}
	}
	

	function validateCategory()
	{
		if($('#category').val() == "-1")
		{
			$('#categoryInfo').text("%select%");
			$('#category').addClass("error");
			return false;
		}
		else
		{
			$('#categoryInfo').text("");
			$('#category').removeClass("error");
			return true;
		}
	}


	function validatesubCategory()
	{
		if($('#subcategory').val() == "-1")
		{
			$('#subcategoryInfo').text("%select%");
			$('#subcategory').addClass("error");
			return false;
		}
		else
		{
			$('#subcategoryInfo').text("");
			$('#subcategory').removeClass("error");
			return true;
		}
	}


	function validateDate()
	{
		if($('#datepick').val().length == 0)
		{
			$('#dateInfo').text("%select%");
			$('#datepick').addClass("error");
			return false;
		}
		else
		{
			$('#dateInfo').text("");
			$('#datepick').removeClass("error");
			return true;
		}
	}

	
</script>

@footer@

