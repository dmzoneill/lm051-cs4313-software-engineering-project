@header@

<table style="width:718px" cellpadding="10">

<tr>
	<td colspan="4">
		<h1>%welcome% %to% charity bay</h1>
	</td>
</tr>

<tr>
	<td style="text-align:right;vertical-align:middle;width:100px">
		<h3>%welcome% </h3>
	</td>
	<td style="text-align:left;vertical-align:middle;width:100px">
		<input type='button' class='button' value='%login%' onclick="window.location='login.php'"/>
	</td>
	<td style="text-align:right;vertical-align:middle;width:140px">
		<h3> %new% %to% charity bay?</h3>
	</td>
	<td style="text-align:left;vertical-align:middle">
		<input type='button' class='button' value='%register%' onclick="window.location='register.php'"/>
	</td>
</tr>
</table>

<table style="width:718px" cellpadding="10">
<tr>
	<td colspan="4">
		<h2>%latestauctions%</h2>
	</td>
</tr>

#newauctions#
<tr>
	<td>
		<div align="center" style="padding:left:30px" class='auctionBox' onclick="window.location='viewauction.php?category=[catid-odd]&subcategory=[subcatid-odd]&viewid=[viewid-odd]'">
			<img src='images/auction/[img-odd]' width='150' height='150'/><br/><br/>	
			[item-desc-odd]
		</div>
	</td>
	<td>
		<div align="center" class='auctionBox' onclick="window.location='viewauction.php?category=[catid-even]&subcategory=[subcatid-even]&viewid=[viewid-even]'">
			<img src='images/auction/[img-even]' width='150' height='150'/><br/><br/>	
			[item-desc-even]
		</div>
	</td>
</tr>
#/newauctions#
</table>


@footer@


