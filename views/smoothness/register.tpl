@header@

<h2>%register%</h2>

<form action='register.php?submit=true' method='post' id="registerForm">
<table style="width:623px;padding:5px;">
	<tr>
		<td style="color:#22229E;" colspan="2">%user% %account%<br /><hr /></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%alias%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='text' id="username" name="username" value="{reg-username}" /> <span style="color:#cc0000;">{reg-username-error}</span> <br/> <span id="usernameInfo">%displayedonwebsite%</span></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%email%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='text' id="email" name="email" value="{reg-email}" /> <span style="color:#cc0000;">{reg-email-error}</span> <br/> <span id="emailInfo"></span></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%password%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='password' id="pass1" name="pass1" value="{reg-pass1}" /> <span style="color:#cc0000;">{reg-pass1-error}</span> <br/> <span id="pass1Info"></span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%retype% %password%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='password' id="pass2" name="pass2" value="{reg-pass2}" /> <span style="color:#cc0000;">{reg-pass2-error}</span> <br/> <span id="pass2Info"></span> </td>
	</tr>
	<tr>
		<td style="color:#22229E;" colspan="2"><br /><br />%postage%<br /><hr /></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%first% %name%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='text' name="fname" id="fname" value="{reg-fname}" /> <span style="color:#cc0000;" id='fnameError'>{reg-fname-error}</span></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%surname%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='text' name="sname" id="sname" value="{reg-sname}" /> <span style="color:#cc0000;" id='snameError'>{reg-sname-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%dob%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type="text" id="datepicker" name="dob" value="{reg-dob}" /> <span style="color:#cc0000;" id='dobError'>{reg-dob-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%house% %name% / %number%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='text' id="hnumber" name="hnumber" value="{reg-hnumber}"  /> <span style="color:#cc0000;" id='hnumberError'>{reg-hnumber-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%street%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='text' id="street" name="street" value="{reg-street}"  /> <span style="color:#cc0000;" id='streetError'>{reg-street-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%county%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='text' id="county" name="county" value="{reg-county}" /> <span style="color:#cc0000;" id='countyError'>{reg-county-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%state% / %province%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='text' id="province" name="province" value="{reg-province}" /> <span style="color:#cc0000;" id='provinceError'>{reg-province-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%country% </td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;">@countrySelect@ <span style="color:#cc0000;" id='countryError'>{reg-country-error}</span> </td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%postcode% </td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='textinput' type='text' id="postcode" name="postcode" value="{reg-postcode}" /> <span style="color:#cc0000;" id='postcodeError'>{reg-postcode-error}</span> </td>
	</tr>
	<tr>
		<td style="color:#22229E;" colspan="2"><br /><br />%optional%<br /><hr /></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;padding-left:20px;padding-top:10px;">%picture%</td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input type='file' id="file" name="file" /></td>
	</tr>
	<tr>
		<td style="color:#22229E;" colspan="2"><br /><br />%complete% %registration%<br /><hr /></td>
	</tr>
	<tr>
		<td style="width:180px;vertical-align:top;"></td>
		<td style="vertical-align:top;padding-left:20px;padding-top:10px;"><input class='button' id="submitButton" type="button" value="%submit% %registration%" /></td>
	</tr>
</table>
</form>
<br />

<div id="dialog-message" title="%registration% %error%">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
		%errorsexist%,
	</p>
	<p>
		%pleasecheck%.
	</p>
</div>

<script language="javascript" type="text/javascript">

<!--

$(document).ready(function()
{
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

	var submitbutton = $("#submitButton");
	var name = $("#username");
	var nameInfo = $("#usernameInfo");
	var email = $("#email");
	var emailInfo = $("#emailInfo");
	var pass1 = $("#pass1");
	var pass1Info = $("#pass1Info");
	var pass2 = $("#pass2");
	var pass2Info = $("#pass2Info");

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
	
	name.blur(validateName);
	email.blur(validateEmail);
	pass1.blur(validatePass1);
	pass2.blur(validatePass2);

	fname.blur(validateFname);
	sname.blur(validateSname);
	dob.blur(validateDob);
	hnumber.blur(validateHnumber);

	street.blur(validateStreet);
	county.blur(validateCounty);
	province.blur(validateProvince);
	country.blur(validateCountry);
	postcode.blur(validatePostcode);
	

	name.keyup(validateName);
	email.keyup(validateEmail);
	pass1.keyup(validatePass1);
	pass2.keyup(validatePass2);

	fname.keyup(validateFname);
	sname.keyup(validateSname);
	dob.keyup(validateDob);
	hnumber.keyup(validateHnumber);

	street.keyup(validateStreet);
	county.keyup(validateCounty);
	province.keyup(validateProvince);
	country.keyup(validateCountry);
	postcode.keyup(validatePostcode);

	submitbutton.click(function()
	{
		if(validateName() && validateEmail() && validatePass1() && validatePass2() && validateFname() && validateSname()  && validateDob()  && validateHnumber()  && validateStreet()  && validateCounty()  && validateProvince()  && validateCountry() && validatePostcode())
		{
			$(submitbutton).attr("disabled", "disabled");
			$(submitbutton).val("%formsubmitmsg%"); 
			$("#registerForm").submit();
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


	function validateDob()
	{
		if(dob.val().length < 4)
		{
			dobError.text("%tooshort%");
			dob.addClass("error");
			return false;
		}
		else
		{
			dobError.text("");
			dob.removeClass("error");
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
	

	function validateEmail()
	{

		var a = $("#email").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+.[a-z]{2,4}$/;

		if( filter.test(a) )
		{
			var suc = true;
			$.get("register.php", { ajax: "true", email: $("#email").val() }, function(data)
				{
					if(data!="true")
					{						       
						emailInfo.html("%emailused% <a href='login.php'>%login%</a>"); 
					    suc = false;
					    return;					    	   
			    	}
				}
			);
			if(suc==true)
			{
				email.removeClass("error");
				emailInfo.text("");
				emailInfo.html("");
				return true;
			}
			return false;
		}
		else
		{
			email.addClass("error");
			emailInfo.text("%typevalidemail%");
			return false;
		}
	}

	
	function validateName()
	{
		if(name.val().length < 6)
		{
			name.addClass("error");
			nameInfo.html(""); 
			nameInfo.text("%userlenght%");
			return false;
		}
		else if(name.val().length > 5)
		{
			var suc = true;
			$.get("register.php", { ajax: "true", username: $("#username").val() }, function(data)
				{
					if(data!="true")
					{			
						name.addClass("error");			       
						nameInfo.html("%userused% <a href='login.php'>%login%</a>"); 
					    suc = false;
					    return;					    	   
					}
					else
					{
						name.removeClass("error");
						nameInfo.html(""); 
						nameInfo.text("");
						return;
					}
			   }
			);
			   return suc;			
		}
		else
		{
			name.removeClass("error");
			nameInfo.html(""); 
			nameInfo.text("");
			return true;
		}
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


