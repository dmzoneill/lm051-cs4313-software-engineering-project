@header@

<h2>%search% '{term}' %results%</h2>

<table style="width:718px;" cellspacing="0" cellpadding="6">	
	<tr>
		<td colspan="2">%viewas% : &nbsp;
			<select name="sortby">
				<option value="">%list%</option>
				<option value="">%gallery%</option>
			</select></td>
		<td style="text-align:right"><span id="menuBlock16" class='pictureBox' onclick="window.location='search.php?sortby=bid_history&sortorder={order}&term={term}'">%bids%</span></td>
		<td style="text-align:right"><span id="menuBlock17" class='pictureBox' onclick="window.location='search.php?sortby=price&sortorder={order}&term={term}'">%price%</span></td>
		<td style="text-align:right"><span id="menuBlock18" class='pictureBox' onclick="window.location='search.php?sortby=postage&sortorder={order}&term={term}'">%postage%</span></td>
		<td style="text-align:right"><span id="menuBlock19" class='pictureBox' onclick="window.location='search.php?sortby=bidding_endtime&sortorder={order}&term={term}'">%timeleft%</span><br /><br /></td>
	</tr>
	<tr>
		<td colspan="6" style="border-bottom:1px dashed #ccddcc"></td>
	</tr>	
	#search#
	<tr class="auctionItemRow" id="a_auction_[iter]" style="width:700px">
		<td style="text-align:left; width:120px;"><br /><a class='imagePreview' href="images/auction/[img]"><img src='images/auction/[img]' width="[width]" height="[height]" border="0"/></a><br /><br /></td>
		<td style="text-align:left; width:130px;"><br /><br /><a href='viewauction.php?category=[categoryid]&subcategory=[subcategoryid]&viewid=[auctid]'>[itemShort]</a></td>
		<td style="text-align:right; width:60px;"><br /><br />[bids] %bids%</td>
		<td style="text-align:right; width:40px;"><br /><br />[price] </td>
		<td style="text-align:right; width:70px;"><br /><br />[postage]</td>
		<td style="text-align:right; width:125px;"><br /><br />[timeleft]</td>
	</tr>
	<tr>
		<td colspan="6" style="border-top:1px dashed #ccddcc"></td>
	</tr>
	#/search#	
</table>

<table style="width:718px;" cellspacing="0" cellpadding="6">	
	<tr>
		<td style="text-align:left; height:40px; line-height:40px" colspan="2">
		    %picsize% : &nbsp;
			<span style="padding:1px 6px 1px 6px;" class='pictureBox' id="picsize8050" onclick="window.location='search.php?pictureWidth=80&pictureHeight=50&term={term}'">&nbsp;</span> &nbsp;
			<span style="padding:3px 8px 3px 8px;" class='pictureBox' id="picsize12080" onclick="window.location='search.php?pictureWidth=120&pictureHeight=80&term={term}'">&nbsp;</span> &nbsp;
			<span style="padding:6px 10px 6px 10px;" class='pictureBox' id="picsize160110" onclick="window.location='search.php?pictureWidth=160&pictureHeight=110&term={term}'">&nbsp;</span> &nbsp;
		</td>
		<td style="text-align:center; height:40px; line-height:40px" colspan="2">
			%page% &nbsp; 
			#pagination#
				<span id="pagebox1" class="pageBox" onclick="window.location='search.php?page=[previous]&term=[term]'">&lt;</span> . .  |
				<span id="pagebox2" class="pageBox" onclick="window.location='search.php?page=[current]&term=[term]'">[current]</span> &nbsp; |
				<span id="pagebox3" class="pageBox" onclick="window.location='search.php?page=[next]&term=[term]'">[next]</span> . . . |
				<span id="pagebox4" class="pageBox" onclick="window.location='search.php?page=[secondlast]&term=[term]'">[secondlast]</span> &nbsp; |
				<span id="pagebox5" class="pageBox" onclick="window.location='search.php?page=[last]&term=[term]'">[last]</span>  . . |
				<span id="pagebox6" class="pageBox" onclick="window.location='search.php?page=[next]&term=[term]'">&gt;</span> &nbsp;
			#/pagination#			
		</td>
		<td style="text-align:right; height:40px; line-height:40px" colspan="2">
		    %perpage% : &nbsp;
			<span class='pictureBox' style="padding:3px 6px 3px 6px;" id="items10" onclick="window.location='search.php?itemsperpage=10&term={term}'">10</span> &nbsp;  
			<span class='pictureBox' style="padding:6px 6px 6px 6px;" id="items15" onclick="window.location='search.php?itemsperpage=15&term={term}'">15</span> &nbsp;  
			<span class='pictureBox' style="padding:9px 6px 9px 6px;" id="items20" onclick="window.location='search.php?itemsperpage=20&term={term}'">20</span> &nbsp;  
			<span class='pictureBox' style="padding:12px 6px 12px 6px;" id="items25" onclick="window.location='search.php?itemsperpage=25&term={term}'">25</span> 
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

	if( sortBy == "postage" )
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

