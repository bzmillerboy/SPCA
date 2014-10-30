<?php
	$q = "SELECT id FROM " . DATABASE . "_categories WHERE disabled = 0";
	$results = mysql_query($q);
	$categories = (mysql_num_rows($results)) ? true : false; 
	
	// Set Up Paging
	$where = '1 = 1';
	$q = "SELECT COUNT(id) AS total_items FROM " . DATABASE . " WHERE $where";
	$results = mysql_query($q);
	$row = mysql_fetch_array($results);
	$total_items = $row["total_items"];
		
	$pages = new Paginator('post_date', 'DESC');
	$pages->items_total = $total_items;
	$pages->items_per_page = 100;
	$pages->paginate();
	
	$q = "SELECT id FROM " . DATABASE . " WHERE $where " . $pages->order . $pages->limit;
	$results = mysql_query($q);

	if (@mysql_num_rows($results) > 0) {
		
		// process the query
		$i = 1;
		while ($row = mysql_fetch_array($results)) {
			$o = new Object(DATABASE, $row["id"]);
?>	
		<div class="record<?php if ($o->disabled == 1) echo(' disabled');?>" id="record-<?php echo $row["id"]; ?>" alt="<?php echo $row["id"]; ?>">
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
	if (!in_array('comments', $hide_modules)) {
?>              
            <span class="record-comments">
<?php
	if ($o->comments == 0) {
		echo('n/a');	
	} else {
		$q2 = "SELECT COUNT(*) AS num_comments FROM comments WHERE item_table = '" . DATABASE . "' AND item_id = " . $row["id"] . " AND status = 1 AND disabled = 0";
		$results2 = mysql_query($q2);
		$row2 = mysql_fetch_array($results2);
		if ($row2["num_comments"] > 0) echo('<a href="comments.php?id=' . $row["id"] . '" class="popup fancybox.iframe">');
		echo($row2["num_comments"]);
		if ($row2["num_comments"] > 0) echo('</a>');
		 
		$q2 = "SELECT COUNT(*) AS num_comments FROM comments WHERE item_table = '" . DATABASE . "' AND item_id = " . $row["id"] . " AND status = 4 AND disabled = 0";
		$results2 = mysql_query($q2);
		$row2 = mysql_fetch_array($results2);
		if ($row2["num_comments"] > 0) echo(' - <strong><a href="comments.php?id=' . $row["id"] . '&status=4" class="popup fancybox.iframe">(' . $row2["num_comments"] . ')</a></strong>');
	}
?>
            </span>
<?php
	}
	if ($categories) {
?>
        	<span class="record-date"><?php echo $o->get_category(); ?></span>
<?php
	}
?>            
            <span class="record-date"><?= $o->get_date("post_date"); ?></span>
            <!--span class="handle"></span-->   
            <span class="record-title"><a href="add_edit.php?id=<?php echo $row["id"]; ?>"><?php echo $o->title; ?></a></span>
            
        </div>
<?php
		$i++;
		}
	}
?>