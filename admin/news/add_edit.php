<?php

	define('DATABASE', 'news');
	define('OBJECT_SINGULAR', 'News Item');
	define('OBJECT_PLURAL', 'News Items');

	$hide_modules = array('featured', 'author_id', 'image_path', 'keywords', 'comments');

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

	include("../inc.add_author.php");

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
		$path = 'images/news';
		$width = '350';
		$height = '';
		$thumb = '';
		$return_width = "226";
		include("../inc.head_image_upload.php");
	}
	if (!in_array('author_id', $hide_modules)) include("../inc.head_add_author.php");
	if (!in_array('keywords', $hide_modules)) include("../inc.head_keywords.php");
	if (!in_array('comments', $hide_modules)) include("../inc.head_comments.php");
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
                    <label for="synopsis"><strong>Synopsis:</strong></label>
                    <div class="field_box_area">
                        <textarea name="synopsis" id="synopsis" style="height:142px;" class="form_element"><?php echo $o->get_synopsis(); ?></textarea>
                    </div>
                </div>

                <div class="field_box">
                    <label for="content"><strong>Content:</strong></label>
                    <div class="field_box_area">
                        <textarea name="content" id="content" style="height:120px;" class="form_element editor"><?php echo $o->content; ?></textarea>
                    </div>
                </div>




<?php
	if (!empty($id)) {
?>
				<input type="submit" name="update" value="Update" class="form_element button teal">&nbsp;&nbsp;&nbsp;&nbsp;
<?php
	} else {
?>
				<input type="submit" name="add_new" value="Add New" class="form_element button teal">&nbsp;&nbsp;&nbsp;&nbsp;
<?php
	}
?>
				<input type="button" value="Cancel" onclick="document.location.href='index.php';" class="form_element button rust">

            </td>
            <td id="right_column" valign="top">

<?php
	if (!in_array('author_id', $hide_modules)) {
?>
				<div class="field_box">
                    <label for="author_id"><strong>Author:</strong></label>
                    <div class="field_box_area" style="padding-right:10px;">
                        <select name="author_id" id="author_id" class="form_element">
                            <option value="0">No Author</option>
<?php
		$q = "SELECT users.id, users.first_name, users.last_name FROM users LEFT JOIN users_groups ON users.id = users_groups.user_id WHERE disabled = 0 AND group_id = 3 ORDER BY last_name ASC, first_name ASC";
		$results = mysql_query($q);
		while ($row = mysql_fetch_array($results)) {
?>
                            <option value="<?php echo $row["id"];?>"<?php if ($o->author_id == $row["id"]) echo(' selected'); ?>><?php echo  $row["first_name"] . " " . $row["last_name"]; ?></option>
<?php
		}
?>
                        </select>
                        <div class="caption">
                            <a href="" id="add-author">Add New Author</a>
                            <div id="add-author-area">
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="padding-right:5px;">
                                            <label for="author_first_name" class="text_only">First Name</label>
                                            <input type="text" id="author_first_name" class="form_element" />
                                        </td>
                                        <td style="padding-left:5px;">
                                            <label for="author_last_name" class="text_only">Last Name</label>
                                            <input type="text" id="author_last_name" class="form_element" />
                                        </td>
                                    </tr>
                                </table>
                                <input type="button" id="add-author-button" value="Add Author" style="margin-top:5px;" />
                            </div>
                        </div>
                    </div>
                </div>
<?php
	}
?>


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
	<label for="post_date"><strong>Post Date:</strong></label>
	<div class="field_box_area">
						<input name="post_date" type="text" id="post_date" class="form_element date_toggled" value="<?php echo $o->get_date("post_date", "n/j/Y"); ?>" />
                        <div class="caption">
                        	Setting a future date means the <?php echo OBJECT_SINGULAR; ?> will not post until that date.
<?php
	if (!in_array('featured', $hide_modules)) {
?>
                        	<br /><br /><input name="featured" type="checkbox" id="featured" value="1" <?php if ($o->featured == 1) echo(' checked="yes"'); ?> /> <label for="featured">Feature this <?php echo OBJECT_SINGULAR; ?> on the homepage.</label>
<?php
	}
?>
                        </div>
                    </div>
                </div>

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

<?php
	if (!in_array('comments', $hide_modules)) {
?>
                <div class="field_box">
                    <label><strong>Comments:</strong></label>
                    <div class="field_box_area">
                        <input name="comments" type="checkbox" id="comments" value="1" <?php if ($o->comments == 1) echo(' checked="yes"'); ?> /> <label for="comments">Allow comments on this item.</label>
					</div>
                    <div class="field_box_area" style="padding:0 10px 0 0;">
						<div id="comment_email_area">
							<label for="comment_email" class="text_only">E-mail new comments to:</label>
							<input type="text" name="comment_email" id="comment_email" class="form_element" value="<?php echo $o->comment_email; ?>" />
						</div>
                    </div>
                </div>
<?php
	}
?>
				<!--<div class="field_box">
                    <label for="url"><strong>News Link:</strong></label>
                    <div class="field_box_area">
                    	<label for="url" class="text_only">URL:</label>
                        <input name="url" type="text" id="url" class="form_element" value="<?php echo $o->url; ?>" style="margin-bottom: 12px;" />

                        <label for="link_text" class="text_only">Link Text:</label>
						<input name="link_text" type="text" id="link_text" class="form_element" value="<?php echo $o->link_text; ?>" />
                    </div>
                </div>
          -->

            </td>
		</tr>
	</table>
   	</form>

<?php
	include("../inc.admin-bottom.php");
?>
</body>
</html>