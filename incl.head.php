<?php
require_once('class/class.MailChimp.php');
if (isset($_POST["subscribe"])) {

		// Subscribe to newsletter
		$mailchimp = new MailChimp('903a794743e7d30570ce6703d9f95694-us6');

		$result = $mailchimp->call('lists/subscribe', array(
			'id' => 'e18f4c3cd3', // All Constituents List
			'email' => array('email' => $_POST["email"]),

			'merge_vars' => array(

				  'FNAME'=>$_POST["First_Name"],
				  'LNAME'=>$_POST["Last_Name"],
				  'SIGNUP'=>"Website Homepage",
				 ),

			'double_optin'      => true,
			'update_existing'   => true,
			'replace_interests' => true,
			'send_welcome'      => false
		));

		header('Location: /confirmation');
		exit();

	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">



  <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <link href="/css/styles.css" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Open Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic","Montserrat:400,700"]
      }
    });
  </script>
  <script src="/js/slippry.js"></script>
  <script src="/js/Chart.js"></script>
  <script src="/js/bootstrapValidator.min.js"></script>