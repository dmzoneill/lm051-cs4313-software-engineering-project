
$(document).ready(function()
{

	var submitbutton = $("#submitButton");
	var name = $("#username");
	var nameInfo = $("#usernameInfo");
	var email = $("#email");
	var emailInfo = $("#emailInfo");
	var pass1 = $("#pass1");
	var pass1Info = $("#pass1Info");
	var pass2 = $("#pass2");
	var pass2Info = $("#pass2Info");
	
	name.blur(validateName);
	email.blur(validateEmail);
	pass1.blur(validatePass1);
	pass2.blur(validatePass2);

	name.keyup(validateName);
	pass1.keyup(validatePass1);
	pass2.keyup(validatePass2);

	submitbutton.click(function()
	{
		if(validateName() && validateEmail() && validatePass1() && validatePass2())
		{
			$(submitbutton).attr("disabled", "disabled");
			$(submitbutton).val("%formsubmitmsg%"); 
			$("#registerForm").submit();
		}
		else
		{
			return false;
		}
	});
	

	function validateEmail()
	{

		var a = $("#email").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;

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
