@header@
@messagesmenu@
<h1>%inbox%</h1>
<table style="width:718px;padding:10px;border:0px;">
	<tr>
		<td style="width:70px"></td>
		<td style="width:100px"><span class='pageBox'>%from%</span></td>
		<td style="width:450px"><span class='pageBox'>%subject%</span></td>
		<td style="width:50px"><span class='pageBox'>%status%</span></td>
		<td><br /><br /></td>
	</tr>	
	<tr>
		<td colspan="5" style="border-bottom:1px dashed #ccddcc"></td>
	</tr>
		
#inboxMessages#
	<tr id='messageHeader[id]'>
		<td style="width:70px"><br /><img src="images/avatars/[avatar]"/ style="margin-right:10px;" width='50' height='50'><br /><br /></td>
		<td style="width:100px;vertical-align:middle"><a href='memberlist.php?memberid=[fromid]'>[from]</a></td>
		<td style="text-align:left;width:450px;vertical-align:middle"><a href="javascript:displayMessage('[id]');">[title]</a></td>
		<td style="text-align:center;width:50px;vertical-align:middle">[status]</td>
		<td style="text-align:center;vertical-align:middle"><input type='button' class='button' onclick="window.location='profile.php?messages=true&compose=true&replyto=[fromid]'" value="%reply%"/></td>
	</tr>		
	<tr>
		<td colspan="5" style="border-bottom:1px dashed #ccddcc">
			<div id='messageBody[id]' style="display:none;">
				<br />
					<div style="padding:20px;border: 1px dashed #cccccc;"><br />
						[body]
					</div>
				<br />
			</div>
		</td>
	</tr>
#/inboxMessages#

</table>


<script type="text/javascript" src="jscript/jquery.textarearesizer.compressed.js"></script>
<script type="text/javascript">
	/* jQuery textarea resizer plugin usage */
	$(document).ready(function() 
	{
		$('textarea.resizable:not(.processed)').TextAreaResizer();

	});
</script>

@footer@


