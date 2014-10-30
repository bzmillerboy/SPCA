<?php 
$success=false;
$error='';

if(isset($_POST['submit'])) {
	
if(($_POST['validation']=='6'||strtolower($_POST['validation'])=='six')&&$_POST['name']!=''&&$_POST['email']!='') {

$name_field = $_POST['name'];
$email_field = $_POST['email'];
$message_field = stripslashes($_POST['message']);

$to = "anne@healthyrootsfoundation.org";
$subject = "Message from the HRF website";
$body = 
"From: $name_field
Email: $email_field

Message:
$message_field";
		
$header = "From: $name_field <$email_field>"; // This statement comes after the first ones since they use the info that was captured from the POST variables.

mail($to, $subject, $body, $header);

$success=true;

unset($_POST);

} else {
	
	$error='Something is wrong with the form. <br />Please try again.';
		
}
}

?>
<?php require("incl.head.php"); ?>
<title>Contact the Healthy Roots Foundation</title>
<?php require("incl.top.php"); ?>
<section class="stripe tight">
<div class="content">
	<hgroup>
		<h1>Contact Us</h1>
	</hgroup>
<?PHP if($success) { ?>	
	<div id="success" data-alert class="alert-box success">Thank you! Someone will be in contact with you shortly.<a href="#" class="close">&times;</a></div>
<?PHP } if($error!="") { ?>	
	<div id="error" data-alert class="alert-box warning"><?=$error?><a href="#" class="close">&times;</a></div>
<?PHP } ?>
    <div class="row collapse">
            <div class="column small-24 medium-11 large-push-1">
				<form action="/contact" method="post" data-abide>
					<div>
					<label>Name</label>
					<input type='text' name='name' value="<?=$_POST["name"]?>" required>
					</div>
                        <div>
                        <label>Email</label>
                        <input type='email' name='email' value="<?=$_POST["email"]?>" required>
                        </div>
                        <div>
                        <label>Message</label>
                        <textarea type='text' name='message' rows="4"><?=$_POST["message"]?></textarea>
                        </div>
                        <div>
                        <label>What is 4 plus two?</label>
                        <input type='number' name='validation' value="<?=$_POST["validation"]?>" required>
                        </div>
                        <input type='submit' name='submit' class="button teal margin_small" value="Send" />
                    </form>
            </div>
            <div class="column small-24 medium-10 medium-pull-1 large-9">
                    <h2 class="feature gray" style="margin-top:5rem;">
                    <i class="fi-mail"></i><br>
                    PO Box 9750<br>
                    Cincinnati, OH 45209
                    </h2>
                <h2 class="feature gray">
                        <i class="fi-telephone"></i><br>
                    513-549-1838
                </h2>
            </div>
        </div>
    </div>
</section>
<?php require("incl.bottom.php"); ?>
<!-- Page-Specific Scripts -->
<script>
$("#success").delay(5000).slideUp('fast');
</script>
</body>
</html>
