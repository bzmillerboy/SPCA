<?php

	require_once("../config/config.php");
	require_once("../class/class.Login.php");
	
	$login = new Login();
	$login->logout_person("login.php");

?>