@header@

<form name="agreement" action="auction.php?agreement=true" method="post">
<textarea class="resizable" style="width:700px;height:200px;" class="resizable" readonly="readonly"> agree to auction contract</textarea><br />

<input class='button' type='button' value='%agree%' onclick="document.agreement.submit();"/>
</form>

<script type="text/javascript" src="jscript/jquery.textarearesizer.compressed.js"></script>

<script language="" type="text/javascript">

$(document).ready(function() 
{
		$('textarea.resizable:not(.processed)').TextAreaResizer();
});

</script>

@footer@

