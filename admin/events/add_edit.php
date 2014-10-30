<?php

	define('DATABASE', 'events');
	define('OBJECT_SINGULAR', 'Event');
	define('OBJECT_PLURAL', 'Events');

	$hide_modules = array('featured', 'end_date', 'image_path', 'keywords');

	require_once("../../config/config.php");
	require_once("../../class/class.Object.php");
	require_once("../../class/class.Login.php");

	$login = new Login();
	$login->check_access(1);

	$id = (isset($_GET["id"])) ? $_GET["id"] : "";

	$o = new Object(DATABASE, $id);

	// let's start by assuming there's no error
	$message = "";
	$message_type = "";

	// Validation
	if (isset($_POST["add_new"]) || isset($_POST["update"])) {
		$o->set_data($_POST);

		$valid = true;
		$errormsg = "";
		if ($o->title == "") {
			$message .= "You must enter a title.<br />\n";
			$message_type = "warning";
			$valid = false;
		}
		if ($o->start_date == "") {
			$message .= "You must choose a start date.<br />\n";
			$message_type = "warning";
			$valid = false;
		}
	}

	//check to see if user added an item
	if (isset($_POST["add_new"]) && $valid) {

		$o->add_new();

		header("Location: index.php?insertsuccess=1");
		exit();
	}

	//check to see if user updated an item
	if (isset($_POST["update"]) && !empty($id) && $valid) {

		$o->update();

		header("Location: index.php?updatesuccess=1");
		exit();
	}

?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<?php
	include("../inc.admin-head.php");
?>
<title><?php echo OBJECT_SINGULAR; ?></title>

<?php
	if (!in_array('image_path', $hide_modules)) {
		$field = 'image_path';
		$path = 'images/events';
		$width = '350';
		$height = '';
		$thumb = '';
		$return_width = "226";
		include("../inc.head_image_upload.php");
	}
	if (!in_array('keywords', $hide_modules)) include("../inc.head_keywords.php");
	include("../inc.head_datepicker.php");
	include("../inc.head_tinymce.php");
	include("../inc.head_validation.php");
?>

</head>
<body class="admin">
<?php
	include("../inc.admin-top.php");

	if (!empty($message)) {
?>
	<div class="<?php echo $message_type;?>"><?php echo $message;?></div>
<?php
	}
?>
	<p class="back"><a href="index.php">&laquo; Back to All <?php echo OBJECT_PLURAL; ?></a></p>
	<h1><?php echo OBJECT_SINGULAR; ?></h1>
<?php

	// Create form action
	$action = '';
	$q_mark = false;
	if (!empty($id)) {
		$action .= "?id=" . $id;
		$q_mark = true;
	}
?>
    <form id="form_add_edit" name="form_add_edit" method="post" action="<?php echo $action; ?>" class="form">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="structure">
    	<tr>
        	<td id="center_column" valign="top">

   				<div class="field_box">
                    <label for="title"><strong>Title:</strong></label>
                    <div class="field_box_area">
                        <input name="title" type="text" id="title" class="form_element required" value="<?php echo $o->title; ?>" />
                    </div>
                </div>


                <div class="field_box">
                    <label for="content"><strong>Content:</strong></label>
                    <div class="field_box_area">
                        <textarea name="content" id="content" style="height:120px;" class="form_element editor"><?php echo $o->content; ?></textarea>
                    </div>
                </div>


                <div class="field_box">
                    <label for="synopsis"><strong>Summary:</strong></label>
                    <div class="field_box_area">
                        <textarea name="synopsis" id="synopsis" style="height:40px;" class="form_element"><?php echo $o->get_synopsis(); ?></textarea>
                        <div class="caption">Provide a short summary of the content. <a href="javascript:get_synopsis();">Grab the first paragraph.</a></div>
                    </div>
                </div>

                <div class="field_box">
                    <label for="eventbrite_code"><strong>Eventbrite Event ID:</strong></label>
                    <div class="field_box_area">
                        <input type="text" name="eventbrite_code" id="eventbrite_code" class="form_element" value="<?php echo $o->eventbrite_code; ?>">
                    </div>
                </div>


<?php
	if (!empty($id)) {
?>
				<input type="submit" class="button teal" name="update" value="Update" class="form_element">&nbsp;&nbsp;&nbsp;&nbsp;
<?php
	} else {
?>
				<input type="submit" class="button teal" name="add_new" value="Add New" class="form_element">&nbsp;&nbsp;&nbsp;&nbsp;
<?php
	}
?>
				<input type="button" class="button rust" value="Cancel" onclick="document.location.href='index.php';" class="form_element">


            </td>
            <td id="right_column" valign="top">

<?php
	$q = "SELECT id, title FROM " . DATABASE . "_categories WHERE disabled = 0 ORDER BY title ASC";
	$results = mysql_query($q);
	if (mysql_num_rows($results) > 0) {
?>
				<div class="field_box">
                    <label for="cat_id"><strong>Category:</strong></label>
                    <div class="field_box_area" style="padding-right:10px;">
                        <select name="cat_id" id="cat_id" class="form_element">

<?php
		while ($row = mysql_fetch_array($results)) {
?>
                            <option value="<?php echo $row["id"]; ?>"<?php if ($o->cat_id == $row["id"]) echo(' selected'); ?>><?php echo $row["title"]; ?></option>
<?php
		}
?>
                        </select>
                        <div class="caption">
                        	The category determines which section this <?php echo OBJECT_SINGULAR; ?> will show up under.
                        </div>
                    </div>
                </div>
<?php
	}
?>

                <div class="field_box">
                    <label for="post_date"><strong>Date:</strong></label>
                    <div class="field_box_area">
                    	<label for="start_date" class="text_only">Start Date</label>
                        <input name="start_date" type="text" id="start_date" class="form_element date_toggled" value="<?php echo $o->get_date("start_date", "n/j/Y"); ?>" />
<?php
	if (!in_array('time', $hide_modules)) {
?>
						<div style="margin-top: 12px;">
						<label for="end_date" class="text_only">Time</label>
						<input name="time" type="text" id="time" class="form_element" value="<?php echo $o->time; ?>" />
                        </div>
<?php
	}
?>
<?php
	if (!in_array('end_date', $hide_modules)) {
?>
						<div style="margin-top: 12px;">
						<label for="end_date" class="text_only">End Date</label>
						<input name="end_date" type="text" id="end_date" class="form_element date_toggled" value="<?php echo $o->get_date("end_date", "n/j/Y"); ?>" />
						<div class="caption">Leave blank if same day.</div>
                        </div>
<?php
	}
?>
<?php
	if (!in_array('featured', $hide_modules)) {
?>
                       	<div class="caption" style="margin-top: 12px;">
                        	<input name="featured" type="checkbox" id="featured" value="1" <?php if ($o->featured == 1) echo(' checked="yes"'); ?> /> <label for="featured">Feature this <?php echo OBJECT_SINGULAR; ?> on the homepage.</label>
						</div>
<?php
	}
?>					</div>
				</div>


                <div class="field_box">
                    <label for="cost"><strong>Cost:</strong></label>
                    <div class="field_box_area">
                        <input name="cost" type="text" id="cost" class="form_element required" value="<?php echo $o->cost; ?>" />
                    </div>
                </div>

                <div class="field_box">
                    <label><strong>Location:</strong></label>
                    <div class="field_box_area">
                    	<label for="location" class="text_only">Location Name</label>
                        <input name="location" type="text" id="location" class="form_element" value="<?php echo $o->location; ?>" style="margin-bottom:12px;" />
						<label for="address" class="text_only">Address</label>
                        <input name="address" type="text" id="address" class="form_element" value="<?php echo $o->address; ?>" style="margin-bottom:12px;" />

<label for="city" class="text_only">City</label>
<input name="city" type="text" id="city" class="form_element" value="<?php echo $o->city; ?>" />

                                	<label for="state" class="text_only">State</label>
                        			<select name="state" id="state" class="form_element">
                                    	<option value="">--</option>
                                        <option value="AL"<?php if ($o->state == 'AL') echo(' selected'); ?>>AL</option>
                                        <option value="AK"<?php if ($o->state == 'AK') echo(' selected'); ?>>AK</option>
                                        <option value="AZ"<?php if ($o->state == 'AZ') echo(' selected'); ?>>AZ</option>
                                        <option value="AR"<?php if ($o->state == 'AR') echo(' selected'); ?>>AR</option>
                                        <option value="CA"<?php if ($o->state == 'CA') echo(' selected'); ?>>CA</option>
                                        <option value="CO"<?php if ($o->state == 'CO') echo(' selected'); ?>>CO</option>
                                        <option value="CT"<?php if ($o->state == 'CT') echo(' selected'); ?>>CT</option>
                                        <option value="DE"<?php if ($o->state == 'DE') echo(' selected'); ?>>DE</option>
                                        <option value="DC"<?php if ($o->state == 'DC') echo(' selected'); ?>>DC</option>
                                        <option value="FL"<?php if ($o->state == 'FL') echo(' selected'); ?>>FL</option>
                                        <option value="GA"<?php if ($o->state == 'GA') echo(' selected'); ?>>GA</option>
                                        <option value="HI"<?php if ($o->state == 'HI') echo(' selected'); ?>>HI</option>
                                        <option value="ID"<?php if ($o->state == 'ID') echo(' selected'); ?>>ID</option>
                                        <option value="IL"<?php if ($o->state == 'IL') echo(' selected'); ?>>IL</option>
                                        <option value="IN"<?php if ($o->state == 'IN') echo(' selected'); ?>>IN</option>
                                        <option value="IA"<?php if ($o->state == 'IA') echo(' selected'); ?>>IA</option>
                                        <option value="KS"<?php if ($o->state == 'KS') echo(' selected'); ?>>KS</option>
                                        <option value="KY"<?php if ($o->state == 'KY') echo(' selected'); ?>>KY</option>
                                        <option value="LA"<?php if ($o->state == 'LA') echo(' selected'); ?>>LA</option>
                                        <option value="ME"<?php if ($o->state == 'ME') echo(' selected'); ?>>ME</option>
                                        <option value="MD"<?php if ($o->state == 'MD') echo(' selected'); ?>>MD</option>
                                        <option value="MA"<?php if ($o->state == 'MA') echo(' selected'); ?>>MA</option>
                                        <option value="MI"<?php if ($o->state == 'MI') echo(' selected'); ?>>MI</option>
                                        <option value="MN"<?php if ($o->state == 'MN') echo(' selected'); ?>>MN</option>
                                        <option value="MS"<?php if ($o->state == 'MS') echo(' selected'); ?>>MS</option>
                                        <option value="MO"<?php if ($o->state == 'MO') echo(' selected'); ?>>MO</option>
                                        <option value="MT"<?php if ($o->state == 'MT') echo(' selected'); ?>>MT</option>
                                        <option value="NE"<?php if ($o->state == 'NE') echo(' selected'); ?>>NE</option>
                                        <option value="NV"<?php if ($o->state == 'NV') echo(' selected'); ?>>NV</option>
                                        <option value="NH"<?php if ($o->state == 'NH') echo(' selected'); ?>>NH</option>
                                        <option value="NJ"<?php if ($o->state == 'NJ') echo(' selected'); ?>>NJ</option>
                                        <option value="NM"<?php if ($o->state == 'NM') echo(' selected'); ?>>NM</option>
                                        <option value="NY"<?php if ($o->state == 'NY') echo(' selected'); ?>>NY</option>
                                        <option value="NC"<?php if ($o->state == 'NC') echo(' selected'); ?>>NC</option>
                                        <option value="ND"<?php if ($o->state == 'ND') echo(' selected'); ?>>ND</option>
                                        <option value="OH"<?php if ($o->state == 'OH') echo(' selected'); ?>>OH</option>
                                        <option value="OK"<?php if ($o->state == 'OK') echo(' selected'); ?>>OK</option>
                                        <option value="OR"<?php if ($o->state == 'OR') echo(' selected'); ?>>OR</option>
                                        <option value="PA"<?php if ($o->state == 'PA') echo(' selected'); ?>>PA</option>
                                        <option value="RI"<?php if ($o->state == 'RI') echo(' selected'); ?>>RI</option>
                                        <option value="SC"<?php if ($o->state == 'SC') echo(' selected'); ?>>SC</option>
                                        <option value="SD"<?php if ($o->state == 'SD') echo(' selected'); ?>>SD</option>
                                        <option value="TN"<?php if ($o->state == 'TN') echo(' selected'); ?>>TN</option>
                                        <option value="TX"<?php if ($o->state == 'TX') echo(' selected'); ?>>TX</option>
                                        <option value="UT"<?php if ($o->state == 'UT') echo(' selected'); ?>>UT</option>
                                        <option value="VT"<?php if ($o->state == 'VT') echo(' selected'); ?>>VT</option>
                                        <option value="VA"<?php if ($o->state == 'VA') echo(' selected'); ?>>VA</option>
                                        <option value="WA"<?php if ($o->state == 'WA') echo(' selected'); ?>>WA</option>
                                        <option value="WV"<?php if ($o->state == 'WV') echo(' selected'); ?>>WV</option>
                                        <option value="WI"<?php if ($o->state == 'WI') echo(' selected'); ?>>WI</option>
                                        <option value="WY"<?php if ($o->state == 'WY') echo(' selected'); ?>>WY</option>
                                    </select>

                                	<label for="zip" class="text_only">Zip</label>
                        			<input name="zip" type="text" id="zip" class="form_element" value="<?php echo $o->zip; ?>" />

                    </div>
				</div>

                <?php /*
                <div class="field_box">
                    <label for="contact_name"><strong>Contact Information:</strong></label>
                    <div class="field_box_area">

                        <label for="phone" class="text_only">Phone Number</label>
                        <input name="phone" type="text" id="phone" class="form_element" value="<?php echo $o->phone; ?>" style="margin-bottom:12px;" />

                        <label for="url" class="text_only">URL</label>
                        <input name="url" type="text" id="url" class="form_element" value="<?php echo $o->url; ?>" />

                    </div>
                </div>
				*/ ?>


<?php
	if (!in_array('image_path', $hide_modules)) {
?>
                <div class="field_box">
<?php
		$img_field = 'image_path';
		$img_path = $o->image_path;
?>
                    <label for="<?php echo $img_field; ?>"><strong>Thumbnail Image:</strong></label>
                    <div class="field_box_area" style="padding-right: 10px;">
                        <div id="<?php echo $img_field; ?>_upload_div" style="<?php echo ($img_path == '') ? 'display:block;' : 'display:none;'; ?>">
                        	<div id="<?php echo $img_field; ?>_upload" style="padding-right: 4px;">
                            	Upload a Photo
                            </div>
                        </div>
    					<div id="<?php echo $img_field; ?>_preview" style="text-align:center; margin-bottom:8px;">
<?php
		if ($img_path != '') {
?>
							<img id="<?php echo $img_field; ?>_thumb" src="<?php echo $img_path; ?>" width="220" border="0" />
<?php
		}
?>
						</div>
        				<div id="<?php echo $img_field; ?>_remove_div" class="remove-photo" style="<?php echo ($img_path == '') ? 'display:none;' : 'display:block;'; ?>">
            				<a href="#" id="<?php echo $img_field; ?>_remove">Remove Photo</a>
                        </div>
                        <div class="caption">Image should be 300 x 200 pixels.</div>
                        <input name="<?php echo $img_field; ?>" type="hidden" id="<?php echo $img_field; ?>" class="form_element" value="<?php echo $img_path; ?>" />
                    </div>
                </div>
<?php
	}
?>


<?php
	if (!in_array('keywords', $hide_modules)) {
?>
                <div class="field_box">
                    <label for="keyword_string"><strong>Tags:</strong></label>
                    <div class="field_box_area">
                        <input name="keyword_string" type="text" id="keyword_string" class="form_element" value="<?php echo $o->get_keywords(); ?>" />
                        <div class="caption">Separate tags with commas.</div>
                    </div>
                </div>
<?php
	}
?>


            </td>
		</tr>
	</table>
   	</form>
<?php
	include("../inc.admin-bottom.php");
?>
</body>
</html>