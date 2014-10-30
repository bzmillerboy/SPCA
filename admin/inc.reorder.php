<?php
	if (isset($_GET['reorder'])) {
		$ids = array_filter(explode('|', $_GET['sort_order']));
		$category = $_GET['reorder'];
			
		/* run the update query for each id */
		foreach($ids as $index=>$id) {
			if ($id != '') {
				$q = 'UPDATE ' . DATABASE . ' SET priority = ' . $index . ' WHERE id = '.$id;
				$results = mysql_query($q);
			}
		}
		
		if (isset($_GET["ajax"])) exit();
		
		$message = "Order updated.<br />\n";
		$message_type = "success";
	
	}
?>