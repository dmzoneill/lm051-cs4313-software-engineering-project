@header@

<table style="width:623px;padding:5px;">
	<tr>
		<td style="color:#22229E;" colspan="2">%user% %account%<br /><hr /></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;"><img src='{reg-avatar}' id='avatar' alt='avatar' width='150' height='150'></img></td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><h2>{reg-username}</h2>{reg-email}<br />
		<div id="upload" ><span>%change% %picture%<span></div><span id="status" ></span></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;"></td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"></td>
	</tr>
	<tr>
		<td style="width:180px;color:#22229E;" colspan="2"><br /><br />%change% %password%<br /><hr /></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%old% %password%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class="textinput" type='password' id="passold" name="passold"  /> <span style="color:#cc0000;">{reg-pass1-error}</span> <span id="oldpassinfo"></span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%new% %password%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class="textinput" type='password' id="pass1" name="pass1" value="{reg-pass1}" /> <span style="color:#cc0000;">{reg-pass1-error}</span> <br/> <span id="pass1Info"></span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%retype% %password%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class="textinput" type='password' id="pass2" name="pass2" value="{reg-pass2}" /> <span style="color:#cc0000;">{reg-pass2-error}</span> <br/> <span id="pass2Info"></span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;"></td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='button' type='button' id="changepassword" value="%change% %password%" /><br /><br /> <span id='passwordConfirm'></span></td>
	</tr>
	<tr>
		<td style="width:180px;color:#22229E;" colspan="2"><br /><br />%address%<br /><hr /></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%first% %name%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class="textinput" type='text' name="fname" id="fname" value="{reg-fname}" /> <span style="color:#cc0000;" id='fnameError'>{reg-fname-error}</span></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%surname%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class="textinput" type='text' name="sname" id="sname" value="{reg-sname}" /> <span style="color:#cc0000;" id='snameError'>{reg-sname-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%house% %name% / %number%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class="textinput" type='text' id="hnumber" name="hnumber" value="{reg-hnumber}"  /> <span style="color:#cc0000;" id='hnumberError'>{reg-hnumber-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%street%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class="textinput" type='text' id="street" name="street" value="{reg-street}"  /> <span style="color:#cc0000;" id='streetError'>{reg-street-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%county%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class="textinput" type='text' id="county" name="county" value="{reg-county}" /> <span style="color:#cc0000;" id='countyError'>{reg-county-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%state% / %province%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class="textinput" type='text' id="province" name="province" value="{reg-province}" /> <span style="color:#cc0000;" id='provinceError'>{reg-province-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%country% </td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;">@countrySelect@ <span style="color:#cc0000;" id='countryError'>{reg-country-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%postcode% </td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class="textinput" type='text' id="postcode" name="postcode" value="{reg-postcode}" /> <span style="color:#cc0000;" id='postcodeError'>{reg-postcode-error}</span> </td>
	</tr>	
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;"></td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='button' type='button' id="changeAddress" value="%change% %address%" /> <span id='addressConfirm'></span></td>
	</tr>
</table>
<br />

<div id="dialog-message" title="%profile% %error%">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		%errorsexist%,
	</p>
	<p>
		%pleasecheck%.
	</p>
</div>

<script type="text/javascript" src="jscript/ajaxupload.3.5.js"></script>
<script language="javascript" type="text/javascript">

<!--

$(function()
{    
    var current = new String(window.location);
    var state = current.indexOf('profile.php');
    var dir = "";
    var page = "";
    if(state > -1)
    {
        page = "profile.php";
        dir = "images/avatars/";
    }
    else
    {
        page = "auction.php";
        dir = "images/auction/";
    }
   
    var btnUpload=$('#upload');
    var status=$('#status');
    new AjaxUpload(btnUpload, {
    	action: page,
    	name: 'uploadfile',
    	onSubmit: function(file, ext){
    		 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                        // extension is not allowed 
    			status.text('JPG, PNG or GIF');
    			return false;
    		}
    		status.text('Uploading...');
    	},
    	onComplete: function(file, response){
    		//On completion clear the status
    		status.text('');
    		//Add uploaded file to list
    		if(response==="error")
    		{
    			status.text('<img src="images/error.gif" width="20" height="20">');
    		} 
    		else
    		{
    			var randomnumber= Math.floor(Math.random()*1132);
    			//$('<li></li>').appendTo('#files').html('<img src="' + " alt="" /><br />'+response).addClass('success'); 
    			$("#avatar").attr("src", dir + response +'?refresh='+randomnumber);

    		}
    	}
    });
    
});


$(document).ready(function()
{
	var oldpass = $("#passold");
	var oldpassinfo = $("#oldpassinfo");	
	var pass1 = $("#pass1");
	var pass1Info = $("#pass1Info");
	var pass2 = $("#pass2");
	var pass2Info = $("#pass2Info");
	var passButton = $("#changepassword");

	var fname = $("#fname");
	var fnameError = $("#fnameError");
	var sname = $("#sname");
	var snameError = $("#snameError");	
	var dob = $("#datepicker");
	var dobError = $("#dobError");
	
	var hnumber = $("#hnumber");
	var hnumberError = $("#hnumberError");
	var street = $("#street");
	var streetError = $("#streetError");
	var county = $("#county");
	var countyError = $("#countyError");
	
	var province = $("#province");
	var provinceError = $("#provinceError");
	var country = $("#country");
	var countryError = $("#countryError");
	var postcode = $("#postcode");
	var postcodeError = $("#postcodeError");

	var changeAddressButton = $("#changeAddress");

	oldpass.blur(validateOld);
	pass1.blur(validatePass1);
	pass2.blur(validatePass2);

	fname.blur(validateFname);
	sname.blur(validateSname);
	hnumber.blur(validateHnumber);

	street.blur(validateStreet);
	county.blur(validateCounty);
	province.blur(validateProvince);
	country.blur(validateCountry);
	postcode.blur(validatePostcode);
	

	pass1.keyup(validatePass1);
	pass2.keyup(validatePass2);

	fname.keyup(validateFname);
	sname.keyup(validateSname);
	hnumber.keyup(validateHnumber);

	street.keyup(validateStreet);
	county.keyup(validateCounty);
	province.keyup(validateProvince);
	country.keyup(validateCountry);
	postcode.keyup(validatePostcode);

	
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


	changeAddressButton.click(function()
	{
		if(validatePostcode() && validateProvince() && validateCountry() && validateCounty() && validateStreet() && validateHnumber() && validateSname() && validateFname() )
		{
			$(changeAddressButton).attr("disabled", "disabled");
			$(changeAddressButton).val("%pleasewait% ..."); 
			$.post("profile.php", 
				{ 
					changeAddress: "true",
					postcode: postcode.val(), 
					province: province.val(), 
					country: country.val(),
					county: county.val(),
					street: street.val(),
					hnumber: hnumber.val(),
					sname: sname.val(),
					fname: fname.val()
				},
			   function(data)
			   {
					var confirmBox = $("#addressConfirm");
					if( data == "true" )
					{
						$(confirmBox).html("<img src='images/correct.png' width='25' height='25'>");								
					}
					else
					{
						$(confirmBox).html("<img src='images/error.gif' width='25' height='25'>");
					}
					$(changeAddressButton).removeAttr("disabled");
					$(changeAddressButton).val("%change% %address%"); 
			   });
		}
		else
		{			
			$("#dialog-message").dialog('open');
			return false;
		}
	});

	
	passButton.click(function()
	{
		if(validateOld() && validatePass1() && validatePass2())
		{
			$(passButton).attr("disabled", "disabled");
			$(passButton).val("%pleasewait% ..."); 
			$.post("profile.php", { oldpass: $("#passold").val(), pass1: $("#pass1").val(), pass2: $("#pass2").val() },
			   function(data)
			   {
					var confirmBox = $("#passwordConfirm");
					if( data == "true" )
					{
						$(confirmBox).html("<img src='images/correct.png' width='25' height='25'>");								
					}
					else
					{
						$(confirmBox).html("<img src='images/error.gif' width='25' height='25'>");
					}
					$(passButton).removeAttr("disabled");
					$(passButton).val("%change% %password%"); 
					$(oldpass).val("");
					$(oldpassinfo).text("");	
					$(pass1).val("");
					$(pass1Info).text("");	
					$(pass2).val("");
					$(pass2Info).text("");	
					oldpassinfo.html(""); 
			   });
		}
		else
		{		
			$("#dialog-message").dialog('open');	
			return false;
		}
	});


	function validatePostcode()
	{
		if(postcode.val().length < 4)
		{
			postcodeError.text("%tooshort%");
			postcode.addClass("error");
			return false;
		}
		else
		{
			postcodeError.text("");
			postcode.removeClass("error");
			return true;
		}
	}
	

	function validateProvince()
	{
		if(province.val().length < 4)
		{
			provinceError.text("%tooshort%");
			province.addClass("error");
			return false;
		}
		else
		{
			provinceError.text("");
			province.removeClass("error");
			return true;
		}
	}
	

	function validateCountry()
	{
		if(country.val() == 0)
		{
			countryError.text("%select% %country%");
			country.addClass("error");
			return false;
		}
		else
		{
			countryError.text("");
			country.removeClass("error");
			return true;
		}
	}


	function validateCounty()
	{
		if(county.val().length < 4)
		{
			countyError.text("%tooshort%");
			county.addClass("error");
			return false;
		}
		else
		{
			countyError.text("");
			county.removeClass("error");
			return true;
		}
	}


	function validateStreet()
	{
		if(street.val().length < 4)
		{
			streetError.text("%tooshort%");
			street.addClass("error");
			return false;
		}
		else
		{
			streetError.text("");
			street.removeClass("error");
			return true;
		}
	}


	function validateHnumber()
	{
		if(hnumber.val().length < 1)
		{
			hnumberError.text("%tooshort%");
			hnumber.addClass("error");
			return false;
		}
		else
		{
			hnumberError.text("");
			hnumber.removeClass("error");
			return true;
		}
	}
	

	function validateSname()
	{
		if(sname.val().length < 4)
		{
			snameError.text("%tooshort%");
			sname.addClass("error");
			return false;
		}
		else
		{
			snameError.text("");
			sname.removeClass("error");
			return true;
		}
	}


	function validateFname()
	{
		if(fname.val().length < 4)
		{
			fnameError.text("%tooshort%");
			fname.addClass("error");
			return false;
		}
		else
		{
			fnameError.text("");
			fname.removeClass("error");
			return true;
		}
	}	


	function validateOld()
	{
		var suc = true;
		$.get("profile.php", { ajax: "true", oldpass: $("#passold").val() }, function(data)
			{
				if(data!="true")
				{			
					oldpass.addClass("error");			       
					oldpassinfo.html("<img src='images/error.gif' width='14' height='14'>"); 
					suc = false;
					return;					    	   
				}
				else
				{
					oldpass.removeClass("error");
					oldpassinfo.html("<img src='images/correct.png' width='14' height='14'>"); 
					return;
				}
			}
		);

		return suc;
	}

	
	function validatePass1()
	{
		var a = $("#password1");
		var b = $("#password2");

		if(pass1.val().length <5)
		{
			pass1.addClass("error");
			pass1Info.text("%userlenght%");
			return false;
		}
		else
		{			
			pass1.removeClass("error");
			pass1Info.text("");
			validatePass2();
			return true;
		}
	}

	
	function validatePass2()
	{
		var a = $("#password1");
		var b = $("#password2");
		if( pass1.val() != pass2.val() )
		{
			pass2.addClass("error");
			pass2Info.text("%passwordmatch%");
			return false;
		}
		else
		{
			pass2.removeClass("error");
			pass2Info.text("");
			return true;
		}
	}
});

//-->

</script>

@footer@


