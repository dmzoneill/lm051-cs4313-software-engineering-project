@header@

<h2>{item_header_description}</h2>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">%status%</a></li>
		<li><a href="#tabs-2">%description%</a></li>
		<li><a href="#tabs-3">%seller%</a></li>
		<li><a href="#tabs-4">%winner%</a></li>
		<li><a href="#tabs-5">%bid% %history%</a></li>
	</ul>
	<div id="tabs-1">
		<table style="width:640px;" cellspacing="0" cellpadding="10">
			<tr>
				<td style="vertical-align:top;text-align:center; width:300px" rowspan="5"> 
					<div style="width:250px;height:200px;text-align:center;" id="slideshow"></div>
				<input type="button" class="button" value="%watch% %item%"/><br /> </td>
				<td style="padding-left:20px; width:100px;">%item% %condition%</td>
				<td>{condition}</td>
			</tr>
			<tr>
				<td style="padding-left:20px;">%timeleft%</td>
				<td>%finished%</td>	
			</tr>
			<tr>
				<td style="padding-left:20px;">%bid% %history%</td>
				<td>{bidcount} %bids%</td>
			</tr>
			<tr>
				<td style="padding-left:20px;">%starting% %bid%</td>
				<td>{startingbid}</td>
			</tr>
			<tr>	
				<td style="vertical-align:top;padding-left:20px;">%postage%</td>
				<td>{postage}</td>
			</tr>
		</table>
	</div>
	<div id="tabs-2">
		<table style="width:640px;" cellspacing="0" cellpadding="10">
			<table style="width:640px;" cellspacing="0" cellpadding="10">
			<tr id='translateButton'>
				<td><br />
					<span class="button" onclick="translate( 'itemDescription' , 'itemDescription' , '' , '{lang}' , 'translateWorking' );"> %translate% </span> &nbsp; &nbsp; <span id='translateWorking'></span><br />
				</td>				 
			</tr>
			<tr>
				<td><br />
					<span id='itemDescription'>{description}</span>
				</td>				 
			</tr>
		</table>
	</div>
	<div id="tabs-3">
		<br /><br />
		<table style="width:350px;" cellspacing="0" cellpadding="10">
			<tr>
				<td rowspan="3"><img src='images/avatars/{avatar}' width='120' height='120' /></td>
				<td style="vertical-align:middle;padding:10px"> <br /> <span class='pageBox' onclick="window.location='memberlist.php?memberid={seller}'">{sellername}</span></td>					
			</tr>
			<tr>
				<td style="vertical-align:middle;padding:10px">
				 	50% %positive% %feedback% 	
				</td>
			</tr>
			<tr>
				<td style="vertical-align:middle;padding:10px">
					<span class='pageBox' onclick="window.location='profile.php?messages=true&compose=true&memberid={seller}&auction_id={auctionid}'">%askquestion%</span>
				</td>
			</tr>
		</table><br /><br />
	</div>
	<div id="tabs-4"><br /><br />
		<table><tr><td style="width:250px"><img src='{winneravatar}' width='200' height='200' /></td><td style="vertical-align:middle"><h2>{winnername}</h2></td></tr></table><br /><br />
	</div>
	<div id="tabs-5">
		<table style="width:640px;" cellspacing="0" cellpadding="10">
			#bids#
			<tr>
				<td style="vertical-align:top; width:70px"><img src='images/avatars/[avatar]' width='40' height='40' /></td><td style="vertical-align:middle;padding-left:20px ; width:60px"><span class='pageBox' onclick="window.location='memberlist.php?memberid=[bidderid]'">[bidder]</span></td><td style="vertical-align:middle;padding-left:20px"> [amount]</td><td style="vertical-align:middle;padding-left:20px"> [time]</td>				
			</tr>
			<tr>
				<td colspan="6" style="border-top:1px dashed #ccddcc"></td>
			</tr>
			#/bids#			
		</table>	
	</div>
</div>



<script language="javascript" type="text/javascript">

$(document).ready(function()
{
	$('#placebid').click(function() 
	{
		var suc = true;
		$.get("viewauction.php", { ajax: "true", checklogon: "true" }, function(data)
			{
				if(data!=1)
				{			
					flashLogin();
				    return;					    	   
				}
				else
				{
					window.location= 'viewauction.php?placebid=true&id={auctionid}&bid=' + $("#bidamount").val();
					return;
				}
		   }
		);
		return suc;	
	});

	detectLanguage( "itemDescription" , "{langCode}" , "translateButton" );

	$(function() {
		$("#tabs").tabs();		
	});
	
	$('#slideshow').crossSlide({
	  sleep: 2,
	  fade: 1
	}, [
   		#images#{ src: 'images/auction/[img]' },#/images#
	]);	
		
});



</script>


@footer@