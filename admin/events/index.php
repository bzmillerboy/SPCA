<?php

	define('DATABASE', 'events');
	define('OBJECT_SINGULAR', 'Event');
	define('OBJECT_PLURAL', 'Events');
	
	$hide_modules = array();
	//$hide_modules = array('featured', 'end_date', 'image_path', 'keywords');
	
	require_once("../../config/config.php");
	require_once("../../class/class.Object.php");
	require_once("../../class/class.Login.php");
	require_once("../../class/class.Paginator.php");
	
	$login = new Login();
	$login->check_access(1);
	
	// let's start by assuming there's no error
	$message = "";
	$message_type = "";
	
	//check to see if user deleted item
	if (isset($_GET["delete"])) {
		$o = new Object(DATABASE, $_GET["delete"]);
		$o->delete();
		
		$message = OBJECT_SINGULAR . " successfully deleted.<br />\n";
		$message_type = "success";
	}
	
	//check to see if user disabled an item
	if (isset($_GET["disable"])) { 
		$o = new Object(DATABASE, $_GET["disable"]);
		$o->disabled = 1;
		$o->update();
		
		$message = OBJECT_SINGULAR . " disabled.<br />\n";
		$message_type = "success";
	}
	
	//check to see if user enabled an item
	if (isset($_GET["enable"])) { 
		$o = new Object(DATABASE, $_GET["enable"]);
		$o->disabled = 0;
		$o->update();
		
		$message = OBJECT_SINGULAR . " enabled.<br />\n";
		$message_type = "success";
	}
	
	//include("../inc.reorder.php");
	
	if (isset($_GET["insertsuccess"])) {
		$message = OBJECT_SINGULAR . " successfully added.<br />\n";
		$message_type = "success";
	} elseif (isset($_GET["updatesuccess"])) {
		$message = OBJECT_SINGULAR . " information updated.<br />\n";
		$message_type = "success";
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
<title><?php echo OBJECT_PLURAL; ?></title>

<script language="javascript" type="text/javascript" src="<?php echo CMS_ROOT; ?>js/cms-list.js"></script>
<?php
	//include("../inc.head_reorder.php");
?>
</head>
<body class="admin">
<?php
	include("../inc.admin-top.php");

	if (!empty($message)) { 
?>
	<div class="<?php echo $message_type; ?>"><?php echo $message; ?></div>
<?php 
	}
?>
	<h1><?php echo OBJECT_PLURAL; ?></h1>
<?php
	$q = "SELECT id FROM " . DATABASE . "_categories WHERE disabled = 0";
	$results = mysql_query($q);
	$categories = (mysql_num_rows($results)) ? true : false; 
	
	// Set Up Paging
	$today = date("Y-m-d 00:00:00");
	$where = "start_date >= '$today'";
	$q = "SELECT COUNT(id) AS total_items FROM " . DATABASE . " WHERE $where";
	$results = mysql_query($q);
	$row = mysql_fetch_array($results);
	$total_items = $row["total_items"];
	
	$pages = new Paginator('start_date');
	$pages->items_total = $total_items;
	$pages->items_per_page = 100;
	$pages->paginate();
	echo '<div id="page-listing">' . $pages->display_pages() . '</div>';
	
	$q = "SELECT id FROM " . DATABASE . " WHERE $where " . $pages->order . $pages->limit;
	$results = mysql_query($q);
?>
    <a href="add_edit.php" class="button teal"><i class="fi-plus"></i> Add <?php echo OBJECT_SINGULAR; ?></a>
    
    <div class="list-header">
        <span class="record-delete">Delete</span>
        <span class="record-disable"><a href="<?php echo $pages->get_href("disabled"); ?>">Disable</a><?php echo $pages->get_arrow("disabled"); ?></span>
<?php
	if ($categories) {
?>
        <span class="record-date"><a href="<?php echo $pages->get_href("cat_id"); ?>">Category</a><?php echo $pages->get_arrow("cat_id"); ?></span>
<?php
	}
?>        
        <span class="record-date"><a href="<?php echo $pages->get_href("start_date"); ?>">Date</a><?php echo $pages->get_arrow("start_date"); ?></span>
        <span class="record-title"><a href="<?php echo $pages->get_href("title"); ?>">Title</a><?php echo $pages->get_arrow("title"); ?></span>
	</div>
    <div id="sortable">
<?php
	if (@mysql_num_rows($results) > 0) {
		
		// process the query
		$i = 1;
		while ($row = mysql_fetch_array($results)) {
			$o = new Object(DATABASE, $row["id"]);
?>	
		<div class="record<?php if ($o->disabled == 1) echo(' disabled');?>" id="record-<?php echo $row["id"]; ?>" alt="<?= $row["id"]; ?>">
           	<span class="record-delete"><a href="?delete=<?php echo $row["id"]; ?>" class="delete"><img src="<?php echo CMS_ROOT; ?>images/delete.png" height="16" width="16" border="0" alt="Delete this <?php echo OBJECT_SINGULAR; ?>" /></a></span>
            <span class="record-disable">
<?php 
			if ($o->disabled == 1) {		
?>
				<a href="?enable=<?php echo $row["id"]; ?>" class="enable"><img src="<?php echo CMS_ROOT; ?>images/dot-filled.gif" height="11" width="11" alt="Disabled" border="0" style="margin-top:3px;" /></a>
<?php
			} else {
?>
				<a href="?disable=<?php echo $row["id"]; ?>" class="disable"><img src="<?php echo CMS_ROOT; ?>images/dot-unfilled.gif" height="11" width="11" alt="Live" border="0" style="margin-top:3px;" /></a>
<?php
			} 
?>
			</span>
<?php
	if ($categories) {
?>
        	<span class="record-date"><?php echo $o->get_category(); ?></span>
<?php
	}
?>   
            <span class="record-date"><?php echo $o->get_date("start_date", 'F j, Y'); ?></span>
            <span class="record-title"><a href="add_edit.php?id=<?php echo $row["id"]; ?>"><?php echo $o->title; ?></a></span>
            
        </div>
<?php
		$i++;
		}
?>
	</div>
<?php		
	} else {
?>	
		<div class="record">
        	<span class="record-title">No <?php echo OBJECT_PLURAL; ?> have been added.  <a href="add_edit.php">Add <?php echo OBJECT_SINGULAR; ?></a></span>
        </div>		
<?php		
	}

	include("../inc.admin-bottom.php");
?>
</body>
</html>