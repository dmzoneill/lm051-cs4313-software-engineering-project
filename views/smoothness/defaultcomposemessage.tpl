@header@
@messagesmenu@
<h1>%compose% %message%</h1>
<form name="message" action="profile.php?messages=true&send=true" method="post">
<table style="width:640px;" cellspacing="0" cellpadding="10">
	<tr>
		<td>%to%</td><td>@userSelect@ &nbsp; <span id='userInfo' style='color:red'></span></td>
	</tr>
	<tr>
		<td>%subject%</td><td><input type='text' class='textinput' name='subject' id='subject' value='' style='width:400px'/> &nbsp; <span id='subjectInfo' style='color:red'></span></td>
	</tr>
	<tr>
		<td>%message%</td><td><textarea class="resizable" id='message' name='message' style="width:500px;height:200px;" class="resizable"></textarea><br  /><span id='messageInfo' style='color:red'></span></td>
	</tr>
	<tr>
		<td></td><td><input class='button' type='button' id='submitbutton' value='%send%'/></td>
	</tr>
</table>
</form>

<div id="dialog-message" title="%message% %error%">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		%errorsexist%,
	</p>
	<p>
		%pleasecheck%.
	</p>
</div>

<script type="text/javascript" src="jscript/jquery.textarearesizer.compressed.js"></script>

<script language="javascript" type="text/javascript">

$(document).ready(function()
{
	var userto = $("#seller");
	var usertoInfo = $("#userInfo");
	var message = $("#message");
	var subject =  $("#subject");
	var submitbutton =  $("#submitbutton");
	var messageInfo = $("#messageInfo");
	var subjectInfo =  $("#subjectInfo");

	$('textarea.resizable:not(.processed)').TextAreaResizer();
	
	$(function() 
	{
		$("#dialog-message").dialog(
			{
				modal: true,
				autoOpen: false,
				buttons: 
				{
					Ok: function() 
					{
						$(this).dialog('close');
					}
				}
			});
	});


	submitbutton.click(function()
	{
		if( validateReceiver() && validateSubject() && validateMessage() )
		{
			$(submitbutton).attr("disabled", "disabled");
			$(submitbutton).val("%pleasewait%"); 
			document.message.submit();
		}
		else
		{		
			$("#dialog-message").dialog('open');
			return false;
		}	
	});
			


	function validateMessage()
	{
		if(message.val().length < 20)
		{
			messageInfo.text("%tooshort%");
			message.addClass("error");
			return false;
		}
		else
		{
			messageInfo.text("");
			message.removeClass("error");
			return true;
		}
	}


	function validateSubject()
	{
		if(subject.val().length < 5)
		{
			subjectInfo.text("%tooshort%");
			subject.addClass("error");
			return false;
		}
		else
		{
			subjectInfo.text("");
			subject.removeClass("error");
			return true;
		}
	}

	function validateReceiver()
	{
		if(userto.val() == 0)
		{
			usertoInfo.text("%select%");
			userto.addClass("error");
			return false;
		}
		else
		{
			usertoInfo.text("");
			userto.removeClass("error");
			return true;
		}
	}
	
});




</script>
@footer@