@header@
<h2>%my% %bids%</h2>
<br />

<table style="width:718px;" cellspacing="0" cellpadding="6" id="mylist">	
	<tr>
		<td colspan="2">%viewas% : &nbsp;
			<select name="sortby">
				<option value="">%list%</option>
				<option value="">%gallery%</option>
			</select></td>
		<td style="text-align:right"><span id="menuBlock16" class='pictureBox' onclick="window.location='auction.php?mybids=all&sortby=bid_history&sortorder={order}'">%bids%</span></td>
		<td style="text-align:right"><span id="menuBlock17" class='pictureBox' onclick="window.location='auction.php?mybids=all&sortby=amount&sortorder={order}'">%my% %bid%</span></td>
		<td style="text-align:right"><span id="menuBlock18" class='pictureBox' onclick="alert('%denied%')">%current% %bid%</span></td>
		<td style="text-align:right"><span id="menuBlock19" class='pictureBox' onclick="window.location='auction.php?mybids=all&sortby=bidding_endtime&sortorder={order}'">%timeleft%</span><br /><br /></td>
	</tr>
	<tr>
		<td colspan="6" style="border-bottom:1px dashed #ccddcc"></td>
	</tr>	
	#auction#
	<tr class="auctionItemRow" id="a_auction_[iter]" style="width:700px">
		<td style="text-align:left; width:120px;vertical-align:middle"><br /><a class='imagePreview' href="images/auction/[img]"><img src='images/auction/[img]' width="[width]" height="[height]" /></a><br /><br /></td>
		<td style="text-align:left; width:130px;vertical-align:middle"><a href='viewauction.php?category=[categoryid]&subcategory=[subcategoryid]&viewid=[auctid]'>[itemShort]</a></td>
		<td style="text-align:right; width:60px;vertical-align:middle">[bids] %bids%</td>
		<td style="text-align:right; width:40px;vertical-align:middle" class="[highestbid]">[mybid] </td>
		<td style="text-align:right; width:70px;vertical-align:middle" class="[higherbid]">[currentbid]</td>
		<td style="text-align:right; width:125px;vertical-align:middle" id="timeleft[question]">[timeleft]</td>
	</tr>
	<tr>
		<td colspan="6" style="border-top:1px dashed #ccddcc"></td>
	</tr>
	#/auction#	
</table>

<table style="width:718px;" cellspacing="0" cellpadding="6">	
	<tr>
		<td style="text-align:left; height:40px; line-height:40px" colspan="2">
		    %picsize% : &nbsp;
			<span style="padding:1px 6px 1px 6px;" class='pictureBox' id="picsize8050" onclick="window.location='auction.php?mybids=all&pictureWidth=80&pictureHeight=50'">&nbsp;</span> &nbsp;
			<span style="padding:3px 8px 3px 8px;" class='pictureBox' id="picsize12080" onclick="window.location='auction.php?mybids=all&pictureWidth=120&pictureHeight=80'">&nbsp;</span> &nbsp;
			<span style="padding:6px 10px 6px 10px;" class='pictureBox' id="picsize160110" onclick="window.location='auction.php?mybids=all&pictureWidth=160&pictureHeight=110'">&nbsp;</span> &nbsp;
		</td>
		<td style="text-align:center; height:40px; line-height:40px" colspan="2">
			%page% &nbsp; 
			#pagination#
				<span id="pagebox1" class="pageBox" onclick="window.location='auction.php?mybids=all&page=[previous]'">&lt;</span> . .  |
				<span id="pagebox2" class="pageBox" onclick="window.location='auction.php?mybids=all&page=[current]'">[current]</span> &nbsp; |
				<span id="pagebox3" class="pageBox" onclick="window.location='auction.php?mybids=all&page=[next]'">[next]</span> . . . |
				<span id="pagebox4" class="pageBox" onclick="window.location='auction.php?mybids=all&page=[secondlast]'">[secondlast]</span> &nbsp; |
				<span id="pagebox5" class="pageBox" onclick="window.location='auction.php?mybids=all&page=[last]'">[last]</span>  . . |
				<span id="pagebox6" class="pageBox" onclick="window.location='auction.php?mybids=all&page=[next]'">&gt;</span> &nbsp;
			#/pagination#			
		</td>
		<td style="text-align:right; height:40px; line-height:40px" colspan="2">
		    %perpage% : &nbsp;
			<span class='pictureBox' style="padding:3px 6px 3px 6px;" id="items10" onclick="window.location='auction.php?mybids=all&itemsperpage=10'">10</span> &nbsp;  
			<span class='pictureBox' style="padding:6px 6px 6px 6px;" id="items15" onclick="window.location='auction.php?mybids=all&itemsperpage=15'">15</span> &nbsp;  
			<span class='pictureBox' style="padding:9px 6px 9px 6px;" id="items20" onclick="window.location='auction.php?mybids=all&itemsperpage=20'">20</span> &nbsp;  
			<span class='pictureBox' style="padding:12px 6px 12px 6px;" id="items25" onclick="window.location='auction.php?mybids=all&itemsperpage=25'">25</span> 
		</td>
	</tr>
</table>


<script language="javascript" type="text/javascript">

<!--

function highLightSortBy()
{
	var sortOrder = "{sortOrder}";
	var sortBy = "{sortBy}";

	if ( sortOrder == "ASC" )
	{
		sortOrder = "⇑";
	}
	else
	{
		sortOrder = "⇓";
	}

	if( sortBy == "bid_history" )
	{
		var by = $("#menuBlock16");
		by.css("background-color","#ffcccc");
	}

	if( sortBy == "price" )
	{
		var by = $("#menuBlock17");
		by.css("background-color","#ffcccc");
	}

	if( sortBy == "reserve" )
	{
		var by = $("#menuBlock18");
		by.css("background-color","#ffcccc");
	}

	if( sortBy == "bidding_endtime" )
	{
		var by = $("#menuBlock19");
		by.css("background-color","#ffcccc");
	}	

	by.text( by.text() + " " + sortOrder);
}

$(document).ready(function()
	{
		var itemsid = $("#items{itemsperpage}");
		itemsid.css("background-color","#ffcccc");	

		var picid = $("#picsize{picsize}");
		picid.css("background-color","#ffcccc");

		var pagecurrent = $("#pagebox2");
		pagecurrent.css("background-color","#ffcccc");

		highLightSortBy();


		for( var g =1; g < 26; g++)
		{
			var col = "timeleft" + g;

			if( $("#"+col).length )
			{
				$("#a_auction_" + g).css("background-color" , "#ffeeee");
			}
		}

		$(".imagePreview").fancybox({
			'titleShow'		: false,
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic'
		});
		
	}
);

//-->
</script>

@footer@

