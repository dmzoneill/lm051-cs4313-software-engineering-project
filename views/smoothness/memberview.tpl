@header@

<table style="width:718px;padding:5px;">
<tr>
	<td rowspan="4" style="width:260px;padding:60px;text-align:center">
		<img src='images/avatars/{picture}' width='200' height='200'/>
	</td>
	<td><br /><br /><br />
		<h1>{theuser}</h1>
	</td>
</tr>
<tr>
	<td>
		{fname} {lname} <br/><br/>
	</td>
</tr>
<tr>
	<td>
		{locale} <br/><br/>
	</td>
</tr>
<tr>
	<td>
		{hnumber} <br/>
		{street} <br/>
		{county} <br/>
		{state} <br/>
		{country} <br/>
		{postcode} <br/><br/>
		
		
	</td>
</tr>
<tr>
	<td style="text-align:center"> <input type='button' class='button' value='%back%' onclick="history.back()"/> <input type='button' class='button' value='%send% %message%' onclick="window.location='profile.php?messages=true&compose=true&replyto={userid}'"/></td>
	<td></td>
</tr>
</table>

@footer@

