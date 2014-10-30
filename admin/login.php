<?php

	require_once("../config/config.php");
	require_once("../class/class.Login.php");
	
	if (isset($_POST["login"])) {
		$login = new Login();
		$duration = (isset($_POST["remember_me"])) ? 3600 * 24 * 365 : 0;
		$login->login_person($_POST["username"], $_POST["password"], $duration);	
	}
	
	// let's start by assuming there's no error
	$message = "";
	$message_type = "";
	
	// then check to see which error was thrown:
	if (isset($_GET["notfound"])) {
		$message = "The username and password you entered is not correct.  Please check and try again.<br />\n";
		$message_type = "error";
	} elseif (isset($_GET["emptylogin"])) {
		$message = "One or more fields were missing required information.  Please enter your username and password below.<br />\n";
		$message_type = "warning";
	} elseif (isset($_GET["loggedout"])) {
		$message = "You have been successfully logged out.<br />\n";
		$message_type = "success";
	} else {
		$message = "Enter your username and password below.<br />\n";
		$message_type = "info";	
	}

?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<?php
	include("inc.admin-head.php");
?>
<link rel="stylesheet" href="/css/style.css" />
<link rel="icon" href="/img/favicon.ico">
<script src="/js/vendor/modernizr.js"></script>
<title>Login</title>
</head>
<body class="admin">
    <div align="center">
    	<div style="border-radius:3px; text-align:left; margin-top:45px; padding:30px; width:400px; background:#FFFFFF; box-shadow: 0px 0 0.3rem rgba(0,0,0,0.25);">
			<form name="form_login" method="post" action="<?php if (isset($_GET["target_url"])) { ?>?target_url=<?=urlencode($_GET["target_url"]);?><?php } ?>">
            	<img src="/img/logo.svg" width="199" height="60" style="display:block; margin:0 auto 30px; height:60px; width:199px;" />
<?php 
	if (!empty($message)) { 
?>
				<div class="<?php echo $message_type; ?>"><?php echo $message; ?></div>
<?php 
	}
?>
<label for="username">Username</label>
<input name="username" type="text" id="username" class="login_field">
<label for="password">Password</label>
<input name="password" type="password" id="password" class="login_field">
<input name="remember_me" type="checkbox" class="nostyle" id="remember_me" value="1" >
<label for="remember_me" class="inline" style="height:40px; line-height:30px;">Remember me</label>

<input type='submit' name='login' class="button teal margin_small expand" value="Login" id="login" />
</form>
    	</div>
	</div>
</body>
</html>