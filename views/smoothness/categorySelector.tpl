
<table style="width:400px">
<tr>
<td style="width:200px;vertical-align:top;">
%category% 
</td>
<td style="vertical-align:top;">
<select id="category" name="category" onchange="updateSubCats();">
<option value="-1">%select% %category%</option>
#categorySelect#
<option value="[catid]">[category]</option>
#/categorySelect#
</select>  <span style="color:#cc0000;" id="categoryInfo">{auction-category}</span>
<br /><br />
</td>
</tr>

<tr>
<td style="vertical-align:top;">
%sub% %category% </td>
<td><select id="subcategory" name="subcategory" disabled="disabled">
<option value="-1">%select% %sub% %category%</option>
</select> <span style="color:#cc0000;" id="subcategoryInfo">{auction-subcategory}</span>
</td>
</tr>
</table>


<script language="javascript" type="text/javascript">

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

</script>