@header@

<h2>%login%</h2>

<form action='login.php' method='post'>
<table>
<tr>
<td>%Username%</td>
<td><input type='text' name='username' value='{username}'></td>
</tr>
<tr>
<td>%Password%</td>
<td><input type='password' name='password'></td>
</tr>
<tr>
<td></td>
<td><input class='button' type='submit' value='%login%'></td>
</tr>
</table>
<input type='hidden' name='refer' value='{refer}' / >
</form>

<p>{loginfailed}</p>

@footer@

