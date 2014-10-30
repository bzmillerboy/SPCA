<?php
	//check to see if user added an author
	if (isset($_GET["add_author"])) {
		$first_name = $_GET["first_name"];
		$last_name = $_GET["last_name"]; 			
		$permalink = $o->process_permalink($first_name . ' ' . $last_name, 'users');
		
		$q = "INSERT INTO users SET first_name = '$first_name', last_name = '$last_name', permalink = '$permalink'";
		mysql_query($q);
		
		$author_id = mysql_insert_id();
		
		$q = "INSERT INTO users_groups SET user_id = $author_id, group_id = 3";
		mysql_query($q);
		
		if (isset($_GET["ajax"])) {
			$data = '<option value="0">No Author</option>';

			$q = "SELECT users.id, users.first_name, users.last_name FROM users LEFT JOIN users_groups ON users.id = users_groups.user_id WHERE disabled = 0 AND group_id = 3 ORDER BY last_name ASC, first_name ASC";
			$results = mysql_query($q);
			while ($row = mysql_fetch_array($results)) {
				$data .= '<option value="' . $row["id"] . '"';
				if ($author_id == $row["id"]) $data .= ' selected';
				$data .= '>' . $row["first_name"] . " " . $row["last_name"] . '</option>';

			}
			echo($data);
			exit();
		}
	}
?>