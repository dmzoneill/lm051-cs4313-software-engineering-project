@header@

<h2>{item_header_description}</h2>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">%status%</a></li>
		<li><a href="#tabs-2">%description%</a></li>
		<li><a href="#tabs-3">%seller%</a></li>
		<li><a href="#tabs-4">%postage%</a></li>
		<li><a href="#tabs-5">%bid% %history%</a></li>
	</ul>
	<div id="tabs-1"><br /><br />
		<table style="width:640px;" cellspacing="0" cellpadding="10">
			<tr>
				<td style="vertical-align:top;text-align:center; width:300px" rowspan="7"> 
					<div style="width:250px;height:200px;text-align:center;" id="slideshow"></div>
				<input type="button" class="button" value="{watching}" onclick="window.location='{watchinglink}'"/><br /> </td>
				<td style="padding-left:20px; width:100px;">%item% %condition%</td>
				<td>{condition}</td>
			</tr>
			<tr>
				<td style="padding-left:20px;">%timeleft%</td>
				<td>{timeleft}</td>	
			</tr>
			<tr>
				<td style="padding-left:20px;">%bid% %history%</td>
				<td><span class='pageBox' onclick='$("#tabs").tabs("select", 4);'>{bidcount} %bids%</span></td>
			</tr>
			<tr>
				<td style="padding-left:20px;">%starting% %bid%</td>
				<td>{startingbid}</td>
			</tr>
			<tr>	
				<td style="vertical-align:top;padding-left:20px;">%current% %bid%</td>
				<td>{currentBid}</td>
			</tr>
			<tr>	
				<td style="padding-left:20px;">%placebid%</td>
				<td><input type="text" class="textinput" id="bidamount" style="width:90px" value="{nextbid}"/> <br/> <span id='bidError' style='color:red'>{bidError}</span></td>
			</tr>
			<tr>	
				<td style="padding-left:20px;"></td>
				<td><input type="button" class="button" id="placebid" value="%placebid%"/> </td>
			</tr>
		</table><br /><br />
	</div>
	<div id="tabs-2">
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
	<div id="tabs-4"><br /><br /><br />
		{postage}<br /><br /><br />
	</div>
	<div id="tabs-5"><br /><br />
		<table style="width:640px;" cellspacing="0" cellpadding="10">
			#bids#
			<tr>
				<td style="vertical-align:top; width:70px"><img src='images/avatars/[avatar]' width='40' height='40' /></td><td style="vertical-align:middle;padding-left:20px ; width:60px"><span class='pageBox' onclick="window.location='memberlist.php?memberid=[bidderid]'">[bidder]</span></td><td style="vertical-align:middle;padding-left:20px"> [amount]</td><td style="vertical-align:middle;padding-left:20px"> [time]</td>				
			</tr>
			<tr>
				<td colspan="6" style="border-top:1px dashed #ccddcc"></td>
			</tr>
			#/bids#			
		</table>	<br /><br />
	</div>
</div>


<div id="dialog-message" title="%bid% %error%">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		%errorsexist%,
	</p>
	<p>
		%pleasecheck%.
	</p>
</div>

<script type="text/javascript" src="jscript/jquery.formatCurrency-1.3.0.min.js"></script>
<script type="text/javascript" src="jscript/jquery.formatCurrency.all.js"></script>

<script language="javascript" type="text/javascript">

$(document).ready(function()
{
	$(function() {
		$("#tabs").tabs();		
	});

	$(function() {
		$("#dialog-message").dialog({
			modal: true,
			autoOpen: false,
			buttons: {
				Ok: function() {
					$(this).dialog('close');
				}
			}
		});
	});
	
	$('#placebid').click(function() 
	{
		$.get("viewauction.php", { ajax: "true", checklogon: "true" }, function(data)
			{
				if(data!=1)
				{			
					flashLogin();
				    return false;					    	   
				}
				else
				{
					// regex money validator
					// http://lawrence.ecorp.net/inet/samples/regexp-validate2.php
					if(/^\$?[1-9][0-9]{0,2}(,[0-9]{3})*(\.[0-9]{2})?$/.test($("#bidamount").val()))
					{						
						$("#placebid").attr("disabled", "disabled");
						$("#placebid").val("%pleasewait%"); 
						window.location= 'auction.php?placebid=true&auctionid={auctionid}&bid=' + $("#bidamount").val();						
						return true;
					}
					else
					{	
						$("#dialog-message").dialog('open');
						return false;
					}
				}
		   }
		);
	});

	$('#bidamount').blur(function()
	{
		// regex money validator
		// http://lawrence.ecorp.net/inet/samples/regexp-validate2.php
		if(/^\$?[1-9][0-9]{0,2}(,[0-9]{3})*(\.[0-9]{2})?$/.test($("#bidamount").val()))
		{
			$('#bidamount').removeClass("error");		
		}
		else
		{	
			$('#bidamount').addClass("error");
		}
		
		$('#bidamount').formatCurrency();		
	});

	detectLanguage( "itemDescription" , "{langCode}" , "translateButton" );
	
	$('#slideshow').crossSlide({
	  sleep: 2,
	  fade: 1
	}, [
   		#images#{ src: 'images/auction/[img]' },#/images#
	]);	
		
});



</script>


@footer@

