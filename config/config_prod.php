<?php

	error_reporting(0);
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);

	ini_set('date.timezone', 'America/New_York');

	$cms_root       = "/admin/";
	$domain         = "spcacincinnati.org";
	$www_domain     = "www.spcacincinnati.org/";
	$http_domain    = "http://www.spcacincinnati.org/";

	$company_name   = "Cincinnati SPCA";

	$db_hostname    = "65.98.62.74"; // Host name
	$db_username    = "brian2"; // Mysql username
	$db_password    = "miller"; // Mysql password
	$db_database    = "cspca_main"; // Database name

	$ga_email	    = "";
	$ga_password    = "";
	$ga_profile_id  = "";
	$ga_property_id = ""; //UA-XXXXXXXX-X

	$facebook		= false;
	$fb_app_id      = "";
	$fb_app_secret  = "";

	$akismet_key    = "";

	$pingomatic     = false;

	$paypal_username = "";
	$paypal_password = "";
	$paypal_signiture = "";

	define("CMS_ROOT", $cms_root);
	define("DOMAIN", $domain);
	define("WWW_DOMAIN", $www_domain);
	define("HTTP_DOMAIN", $http_domain);

	define("COMPANY_NAME", $company_name);

	define("DB_HOSTNAME", $db_hostname);
	define("DB_DATABASE", $db_database);
	define("DB_USERNAME", $db_username);
	define("DB_PASSWORD", $db_password);

	define('GA_EMAIL', $ga_email);
	define('GA_PASSWORD', $ga_password);
	define('GA_PROFILE_ID', $ga_profile_id);
	define('GA_PROPERTY_ID', $ga_property_id);

	define('FACEBOOK', $facebook);
	define('FB_APP_ID', $fb_app_id);
	define('FB_APP_SECRET', $fb_app_secret);

	define('AKISMET_KEY', $akismet_key);

	define('PINGOMATIC', $pingomatic);

	define("PAYPAL_USERNAME", $paypal_username);
	define("PAYPAL_PASSWORD", $paypal_password);
	define("PAYPAL_SIGNATURE", $paypal_signiture);

	$login = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die("Unable to connect to the database.  Try again later.");
	//trigger_error(mysql_error(),E_USER_ERROR);
	mysql_select_db(DB_DATABASE);

?>