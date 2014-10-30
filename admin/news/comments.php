<?php

	define('DATABASE', 'comments');
	define('OBJECT_SINGULAR', 'Comment');
	define('OBJECT_PLURAL', 'Comment');
	define('TABLE', 'news');
	
	require_once("../../config/config.php");
	require_once("../../class/class.Object.php");
	require_once("../../class/class.Login.php");
	require_once("../../class/class.Paginator.php");
	
	$login = new Login();
	$login->check_access(1);
	
	$item_id = (isset($_GET["id"])) ? $_GET["id"] : "";
	$status = (isset($_GET["status"])) ? $_GET["status"] : 1;
			
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
	
	//check to see if user approved item
	if (isset($_GET["approve"])) {
		$o = new Object(DATABASE, $_GET["approve"]);
		$o->status = 1;
		$o->update();
		
		if (isset($_GET["ajax"])) exit();
		
		$message = OBJECT_SINGULAR . " approved.<br />\n";
		$message_type = "success";
	}
	
	//check to see if user rejected item
	if (isset($_GET["reject"])) {
		$o = new Object(DATABASE, $_GET["reject"]);
		$o->status = 2;
		$o->update();
		
		if (isset($_GET["ajax"])) exit();
		
		$message = OBJECT_SINGULAR . " rejected.<br />\n";
		$message_type = "success";
	}
	
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
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]--><head>
<?php
	include("../inc.admin-head.php");
?>
<title>Comments</title>

<script language="javascript" type="text/javascript" src="<?php echo CMS_ROOT; ?>js/cms-list.js"></script>
</head>

<style type="text/css">
	body {
		background: #FFF;	
	}
</style>
<script type="text/javascript">
	$(function() {
		$('a.button-neg').each(function() {
			$(this).click(function(e) {
				e.preventDefault();	
				//if (confirm("Are you sure you want to PERMANENTLY DELETE this Item?  This action is not undoable.")) {
					var parent = $(this).closest('div');
					
					$.ajax({
						type: "GET",
						data: {
							reject: parent.attr('alt'),
							ajax: '1'
						},
						beforeSend: function() {
							parent.animate({'background-color': '#CC0000'}, 300);	
						}
					}).done(function(html) {
						parent.slideUp(300, function() {
							parent.remove();
						});
					});
				//}
			});
		});
		
		
		$('a.button-pos').each(function() {
			$(this).click(function(e) {
				e.preventDefault();	
				//if (confirm("Are you sure you want to PERMANENTLY DELETE this Item?  This action is not undoable.")) {
					var parent = $(this).closest('div');
					
					$.ajax({
						type: "GET",
						data: {
							approve: parent.attr('alt'),
							ajax: '1'
						},
						beforeSend: function() {
							parent.animate({'background-color': '#339933'}, 300);	
						}
					}).done(function(html) {
						parent.slideUp(300, function() {
							parent.remove();
						});
					});
				//}
			});
		});
		
	});
	
</script>
<body>
<?php
	if (!empty($message)) { 
?>
	<div class="<?php echo $message_type;?>"><?php echo $message;?></div>
<?php 
	}
?>
	<h1><?php if ($status == 4) echo('New '); ?>Comments</h1>
<?php
	$item = new Object(TABLE, $item_id);
?>    
    <h3><?php echo $item->title;?></h3>
<?php
	// Set Up Paging
	
	// Status:
	// 1 - Approved
	// 2 - Disapproved
	// 3 - Spam
	// 4 - New
	
	$where = "status = $status AND item_table = '" . TABLE . "' AND item_id = " . $item_id;	
	$q = "SELECT COUNT(id) AS total_items FROM " . DATABASE . " WHERE $where";
	$results = mysql_query($q);
	$row = mysql_fetch_array($results);
	$total_items = $row["total_items"];
		
	$pages = new Paginator('post_date', 'DESC');
	$pages->items_total = $total_items;
	$pages->items_per_page = 30;
	$pages->paginate();
	
	echo '<div id="page-listing">' . $pages->display_pages() . '</div>';
	
	$q = "SELECT id FROM " . DATABASE . " WHERE $where " . $pages->order . $pages->limit;
	$results = mysql_query($q);
?>
    <div class="list-header">
<?php
	if ($status == 1) {
?>    	
        <span class="record-delete">Delete</span>
        <span class="record-disable"><a href="<?php echo $pages->get_href("disabled"); ?>">Disable</a><?php echo $pages->get_arrow("disabled"); ?></span>     
<?php
	} elseif ($status == 4) {
?>	
		<span class="record-button">Approve</span>
        <span class="record-button">Reject</span>  
<?php		
	}
?>
		<span class="record-date"><a href="<?php echo $pages->get_href("post_date"); ?>">Date</a><?php echo $pages->get_arrow("post_date");?></span>
        <span class="record-title"><a href="<?php echo $pages->get_href("comments"); ?>">Comment</a><?php $pages->get_arrow("comments"); ?></span>
	</div>
<?php
	if (mysql_num_rows($results) > 0) {
		
		// process the query
		$i = 1;
		while ($row = mysql_fetch_array($results)) {
			$o = new Object(DATABASE, $row["id"]);
?>	
		<div class="record<?php if ($o->disabled == 1) echo(' disabled');?>" id="record-<?php echo $row["id"]; ?>" alt="<?php echo $row["id"]; ?>">
<?php
	if ($status == 1) {
?>         
           	<span class="record-delete"><a href="?delete=<?php echo $row["id"]; ?>" class="delete"><img src="<?php echo CMS_ROOT; ?>images/delete.png" height="16" width="16" border="0" alt="Delete this Blog Post" /></a></span>
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
	} elseif ($status == 4) {
?>
			<span class="record-button"><a href="?approve=<?php echo $row["id"]; ?>" class="button-pos">Approve</a></span>
       		<span class="record-button"><a href="?reject=<?php echo $row["id"]; ?>" class="button-neg">Reject</a></span>  
<?php
	}
?>
            <span class="record-date"><?php echo $o->get_date("post_date"); ?></span>
            <span class="record-title"><?php echo $o->comments; ?></span>
            
        </div>
<?php
		$i++;
		}
	} else {
?>	
		<div class="record">
        	<span class="record-title">No <?php echo OBJECT_PLURAL; ?> have been added.</span>
        </div>		
<?php		
	}
?>
</body>
</html>