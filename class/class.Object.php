<?php

$prewd_user = getcwd(); // get the current working directory
chdir(realpath(dirname(__FILE__))); // change working directory to the location of this file
	
require_once("../config/config.php");
require_once("class.Pinger.php");
require_once("class.Login.php");
	
chdir($prewd_user); // change back to previous working dir
  
class Object {
	var $data;
	var $data_gathered;
	var $locked_fields;  // These fields wont ever get updated (like id, for example)
	var $now;
		
	var $db_table;
	var $db_fields;
	
	var $keywords;
	
	
	function describe(){
		$q = "SHOW COLUMNS FROM " . $this->db_table;
		$results = mysql_query($q);
		while ($row = mysql_fetch_assoc($results)) {
			$this->db_fields[] = $row['Field'];	
		}
		mysql_free_result($results);
	}
	
	
	function __construct($db_table, $id = 0) { 
		$this->db_table = $db_table;
		$this->describe();
		$this->locked_fields = array("id");
		
		$this->now = date("Y-m-d H:i:s");
		//$this->data_type = new data_type();
   				
		if ($id == 0) {
			foreach($this->db_fields as $field){
				switch ($field) {
					case 'date_created':
					case 'date_modified':
					case 'post_date':
						$this->data[$field] = $this->now;
					break;
					case 'created_by':
						$login = new Login();
						$this->data[$field] = $login->get_person_id(); 
					break;
					case 'start_date':
						// Rounded down to the nearest 15 minutes
						$this->data[$field] = date("Y-m-d H:i:s", mktime(date("H", strtotime($this->now)), date('i', strtotime($this->now)) - (date('i', strtotime($this->now)) % 15), 0, date("n", strtotime($this->now)), date("j", strtotime($this->now)), date("Y", strtotime($this->now))));
					break;
					case 'end_date':
						// Rounded down to the nearest 15 minutes, 1 hour from now
						//$this->data[$row['Field']] = date("Y-m-d H:i:s", mktime(date("H", strtotime($this->now)) + 1, date('i', strtotime($this->now)) - (date('i', strtotime($this->now)) % 15), 0, date("n", strtotime($this->now)), date("j", strtotime($this->now)), date("Y", strtotime($this->now))));
						$this->data[$field] = '';
					break;
					case 'url':
					case 'url2':
						$this->data[$field] = 'http://';
					break;
					case 'priority':
						$q2 = "SELECT priority FROM ".$this->db_table." ORDER BY priority DESC LIMIT 1";
						$results2 = mysql_query($q2);
						if (mysql_num_rows($results2) > 0) {
							$row2 = mysql_fetch_array($results2);
							$this->data[$field] = ($row2["priority"] + 1);	
						} else {
							$this->data[$field] = 1;	
						}
					break;
					default:
						$this->data[$field] = '';
					break;
				}
			}
			
			$this->data_gathered = true;	
		} else {
			$q = "SELECT * FROM ".$this->db_table." WHERE id = " . $id;
			$results = mysql_query($q);
			$row = mysql_fetch_assoc($results);
			foreach($row as $field => $value){
				$this->data[$field] = $this->process_loaded_data($field, stripslashes($value));
			}	
		}
	}
	
	
	function process_loaded_data($field, $value) {
		switch($field){
			case 'image_path':
			case 'content':	
				return $value;
			break;
			case 'url':
				return ($value == '') ? 'http://' : $value;
			break;
			case 'title':
				return htmlspecialchars($value);
			break;
			default:
				return $value;
			break;
		}
	}
	
	function process_storing_data($field, $value) {
		switch($field){
			case 'image_path':
			case 'content':	
				return $value;
			break; 
			default:
				return $value;
			break;
		}
	}
	
	
	// MAGIC METHODS
	public function __call($field, $arguments) { 
		if (isset($this->data[$field]))
			return $this->data[$field];
					
		$parsed = explode("_", $field);
		$p_type = array_shift($parsed);
		$parsed = implode("_", $parsed);
		if (isset($this->data[$parsed]))
			return $this->data[$parsed];	  
					
		$trace = debug_backtrace();
		//error_log('Undefined property via __call() in ' . $trace[1]['class'] . ' CLASS.  ATTEMPTED TO CALL FUNCTION: ' . $field . ' FROM FILE: ' . $trace[1]['file'] . ' ON LINE: ' . $trace[1]['line']);		
		return '';
	}	
			
	
	public function __set($field, $value) {
		$this->data[$field] = $value;
	}
		
	
	public function set_checkboxes() {
		foreach ($this->db_fields as $field){		
			$type = explode("(", $field['Type']);
			if ($type[0] == "tinyint"){
				if (!in_array($field, $this->locked_fields) && in_array($field, $this->db_fields))
					$this->data[$field['Field']] = 0;
			}
		}
	}
		
	
	public function set_data($posted_data){
		$this->set_checkboxes();
		
		if (in_array('image_paths', $this->db_fields)) {
			// In case all images were cleared, we need to start empty
			$this->data['image_paths'] = '';
		}
		
		if (in_array('featured', $this->db_fields)) {
			$this->data['featured'] = 0;
		}
		
		if (in_array('comments', $this->db_fields)) {
			$this->data['comments'] = 0;
		}
		
		foreach($posted_data as $name => $value){
			switch($name) {
				case "title":
					$this->data['permalink'] = $this->process_permalink($value, $this->db_table);
				break;
				case "first_name":
					$this->data['permalink'] = $this->process_permalink($this->data['first_name'] . ' ' . $this->data["middle_name"] . ' ' . $this->data["last_name"], $this->db_table);
				break;
				case "keyword_string":
					$this->data['keyword_string'] = $value;
					$this->data['keyword_ids'] = $this->write_keywords();
				break;
				case "post_date":
				case "start_date":
				case "end_date":
					if ($value != '') $value = date("Y-m-d H:i:s", strtotime($value));	
				break;	
				case "url":
				case "url2":
					if ($value == 'http://') $value = '';
				break;
				case 'image_paths':
					$image_entered = false; 
					$image_string = '';
					foreach ($value as $image_path) {
						$image_string .= '|' . $image_path;
						$image_entered = true;	
					}
					if ($image_entered) $image_string .= '|';
					$value = $image_string;
				break;
			}

			$this->data[$name] = stripslashes($value);
		}	
	}
		
		
	public function __get($field) {
		if (array_key_exists($field, $this->data)) {
			return $this->data[$field];
		}	
		return '';
	}
		
		
	/**  As of PHP 5.1.0  */
	public function __isset($field) {
		return isset($this->data[$field]);
	}
		
	
	/**  As of PHP 5.1.0  */
	public function __unset($field) {
		unset($this->data[$field]);
	}
	

	function add_new() {
	
		$this->data['date_created'] = $this->now;
		$this->data['date_modified'] = $this->now;	
				
		$q = "INSERT INTO ". $this->db_table . " SET id = id";
				
		foreach ($this->data as $field => $value) {
			if (!in_array($field, $this->locked_fields) && in_array($field, $this->db_fields))
				$q .= ', ' . $field . ' = "' . mysql_real_escape_string($this->process_storing_data($field, $this->data[$field])) . '"';
		}
			
		$success = mysql_query($q);
						
		if ($success) { 
			$this->id = mysql_insert_id();
			$this->data['id'] = $this->id;
			
			if ($this->db_table == 'news' && PINGOMATIC) {
				$pinger = new Pinger();
				$ping_success = $pinger->ping_ping_o_matic($this->data["title"], HTTP_DOMAIN . 'news/' . $this->data["permalink"], '', '');
				$ping_success = $pinger->ping_weblogs_com($this->data["title"], HTTP_DOMAIN . 'news/' . $this->data["permalink"], '', '');
			}
		}
		
		
		return $success;
	}

	function update() {
	
		$this->data['date_modified'] = $this->now;
		
		$q = "UPDATE " . $this->db_table . " SET id = id ";
		
		foreach ($this->data as $field => $value) {
			if (!in_array($field, $this->locked_fields) && in_array($field, $this->db_fields))
				$q .= ', ' . $field . '= "'.mysql_real_escape_string($this->process_storing_data($field, $this->data[$field])) . '" ';
		}
		$q .= " WHERE id = $this->id";
		
		$success = mysql_query($q);
		
		return $success;
	}


	function delete() {
		$q = "DELETE FROM " . $this->db_table . " WHERE id = " . $this->data['id'] . " LIMIT 1"; 
		$success = mysql_query($q);
	
		return $success;
	}
		
	function get_date($field, $format = 'F j, Y') {
		return ($this->data[$field] == '') ? '' : date($format, strtotime($this->data[$field]));
	}
	
	function get_price($field = 'price') {
		return ($this->data[$field] == '') ? 'Free' : '$' . $this->data[$field];
	}
	
	function get_author($author_id = '') {
		if ($author_id == '') $author_id = $this->author_id;
		
		$q = "SELECT first_name, last_name FROM users WHERE id = $author_id";
		$results = mysql_query($q);
		$row = mysql_fetch_array($results);
		
		return $row["first_name"] . ' ' . $row["last_name"];
	}
	
	function get_category($cat_id = '') {
		if ($cat_id == '') $cat_id = $this->cat_id;
		
		$q = "SELECT title FROM " . $this->db_table . "_categories WHERE id = $cat_id";
		$results = mysql_query($q);
		$row = mysql_fetch_array($results);
		
		return $row["title"];
	}
	
	function get_place($place_id = '') {
		if ($place_id == '') $place_id = $this->place_id;
		
		$q = "SELECT title FROM places WHERE id = $place_id";
		$results = mysql_query($q);
		$row = mysql_fetch_array($results);
		
		return $row["title"];
	}
	
	function get_full_category($cat_id = '') {
		if ($cat_id == '') $cat_id = $this->cat_id;
		
		$title = '';
		
		$q = "SELECT parent_id, title FROM " . $this->db_table . "_categories WHERE id = $cat_id";
		$results = mysql_query($q);
		$row = mysql_fetch_array($results);
		
		$title = $row["title"];
		
		if ($row["parent_id"] > 0) {
			$q = "SELECT title FROM " . $this->db_table . "_categories WHERE id = " . $row["parent_id"];
			$results = mysql_query($q);
			$row = mysql_fetch_array($results);
			
			$title = $row["title"] . ': ' . $title;
		}
		
		return $title;	
	}
	
	function get_previous($field = 'permalink', $conditions = '') {	
		$q = "SELECT $field FROM " . $this->db_table . " WHERE disabled = 0 " . $conditions . " AND priority < " . $this->data["priority"] . " ORDER BY priority DESC LIMIT 1";
		$results = mysql_query($q);
		if (mysql_num_rows($results) > 0) {
			$row = mysql_fetch_array($results);
			return $row[$field];
		} else {
			// No results - get last item	
			$q = "SELECT $field FROM " . $this->db_table . " WHERE disabled = 0 " . $conditions . " ORDER BY priority DESC LIMIT 1";
			$results = mysql_query($q);
			$row = mysql_fetch_array($results);
			return $row[$field];
		}
	}
	
	function get_next($field = 'permalink', $conditions = '') {	
		$q = "SELECT $field FROM " . $this->db_table . " WHERE disabled = 0 " . $conditions . " AND priority > " . $this->data["priority"] . " ORDER BY priority ASC LIMIT 1";
		$results = mysql_query($q);
		if (mysql_num_rows($results) > 0) {
			$row = mysql_fetch_array($results);
			return $row[$field];
		} else {
			// No results - get first item	
			$q = "SELECT $field FROM " . $this->db_table . " WHERE disabled = 0 " . $conditions . " ORDER BY priority ASC LIMIT 1";
			$results = mysql_query($q);
			$row = mysql_fetch_array($results);
			return $row[$field];
		}
	}
	
	function get_keywords($link = '') { 
		$keyword_ids = explode('|', $this->data['keyword_ids']);
		$keyword_ids = array_filter($keyword_ids);
		
		$num_keywords = count($keyword_ids);
		$i = 1;	
		$keyword_string = "";
			
		if (is_array($keyword_ids)) {
			foreach ($keyword_ids as $key_id) {
				$q = "SELECT permalink, name FROM keywords WHERE id = '$key_id' LIMIT 1";
				$results = mysql_query($q);	
				$row = mysql_fetch_array($results);
					
				if ($link != '') {
					$keyword_string .= '<a href="/' . $link . '/';
					
					$keyword_string .= $row["permalink"] . '">';
				}
				$keyword_string .= $row["name"];
				if ($link != '') {
					$keyword_string .= '</a>';	
				}
				if ($i != $num_keywords) $keyword_string .= ", ";
				$i++;
			}
		}
		
		return $keyword_string; 
	}
	
	function get_keyword_array() { 
		$keyword_ids = explode('|', $this->data['keyword_ids']);
		$keyword_ids = array_filter($keyword_ids);
		
		return $keyword_ids; 
	}
	
	function write_keywords() {
		if (isset($this->data['keyword_string'])) {
			if ($this->data['keyword_string'] != '') {
				$keyword_ids = '';
				$keywords = array_filter(explode(',', $this->data['keyword_string']));
				$keyword_set = false;
				foreach ($keywords as $keyword) {
					$keyword = trim($keyword);
					
					if ($keyword != '') {
						$keyword_set = true;
						
						// See if the keyword exists already
						$q = "SELECT id FROM keywords WHERE name = '$keyword' LIMIT 1";
						$results = mysql_query($q);
						if (mysql_num_rows($results) < 1) {
							// Add New Keyword
							$q = "INSERT INTO keywords SET name = '$keyword'";
							mysql_query($q);
							$keyword_id = mysql_insert_id();
							$processed_keyword = $this->process_permalink($keyword);
							$q = "UPDATE keywords SET permalink = '$processed_keyword' WHERE id = '$keyword_id'";
							mysql_query($q);
						}
						else {
							// Fetch the keyword
							$row = mysql_fetch_array($results);
							$keyword_id = $row["id"];
						}
						
						$keyword_ids .= '|'	. $keyword_id;
					}
				}
				if ($keyword_set) {
					$keyword_ids .= '|';
				
					return $keyword_ids;
				}
			}
		}
		return '';
	}
	
	function process_permalink($title = '', $db_table = '') {
		$plink = str_replace("&", "and", $title); // Replace & with and
		$plink = preg_replace('/\s\s+/', ' ', $plink); // Remove Excess Whitespace
		$plink = str_replace(" ", "-", $plink); // Replace spaces with dashes
		$plink = preg_replace("/[^a-zA-Z0-9s-]/", "", $plink); // Remove any non-alphanumeric characters
		$plink = preg_replace('/--+/', '-', $plink); // Get rid of double spaces (after removing non alphanumerics)
		$plink = strtolower($plink); // Make lowercase
		
		switch($db_table) {
			case 'news':
			case 'blogs':
			case 'articles':
				$plink = date("Y", strtotime($this->data['post_date'])) . '/' . date("m", strtotime($this->data['post_date'])) . '/' . date("d", strtotime($this->data['post_date'])) . '/' . $plink;
			break;
			case 'events':
				$plink = date("Y", strtotime($this->data['start_date'])) . '/' . date("m", strtotime($this->data['start_date'])) . '/' . date("d", strtotime($this->data['start_date'])) . '/' . $plink;
			break;
			case 'pages':
				$plink = ($this->data["is_index"] == 1) ? substr($this->gen_permalink_path('', $this->data['parent_id']), 0, -1) : $this->gen_permalink_path('', $this->data['parent_id']) . $plink;
			break;
			default:
			
			break;	
		}
		
		if ($db_table != '') $plink = $this->check_permalink($plink);
				
		return $plink;
	}
	
	function check_permalink($plink = "") {
		$q = "SELECT id FROM " . $this->db_table . " WHERE permalink = '$plink' AND id != '" . $this->data['id'] . "'";
		$results = mysql_query($q);
		if (mysql_num_rows($results) > 0) { // If the query returned a match
			// Check if the last character is a number
			$words = explode("-", $plink);
			$words = array_reverse($words);
			$num_check = $words[0];
			if (is_numeric($num_check)) { // If there is a number at the end...
				$plink = substr($plink, 0, -strlen($num_check));
				$num_check++; // Add 1 to the number
				$plink .= $num_check; 
			} else { // If theres not a number, we'll add 2 to the end
				$plink .= "-2";
			} // Now check the incremented permalink to see if THAT one is in there...
			$plink = $this->check_permalink($plink);
		}
		return $plink;
	}
	
	function gen_permalink_path($path, $page_id) {
		// Get parent_id
		$q = "SELECT parent_id FROM pages WHERE id = $page_id LIMIT 1";
		$results = mysql_query($q);
		$row = mysql_fetch_array($results);
		$parent_id = $row["parent_id"];
	
		if ($page_id == '')
			return;
		if ($parent_id == 0) {
			// Root - END
			return $path;
		} else {
			// Add on a level
			$q = "SELECT title FROM pages WHERE id = $page_id LIMIT 1";
			$results = mysql_query($q);
			$row = mysql_fetch_array($results);
			$page_permalink = $this->process_permalink($row["title"]);
			$path = $page_permalink . '/' . $path;
			
			return $this->gen_permalink_path($path, $parent_id);
		}
	}
	
	function get_image_width($image_path, $max_width = 300, $max_height = 300) {
		list($width, $height) = getimagesize(HTTP_DOMAIN . substr($image_path, 1));
		$new_width = $width;
		$new_height = $height;
		if ($width > $max_width) {
			$new_width = $max_width;
			$new_height = round(($height * $new_width) / $width);
		} 
		if ($new_height > $max_height) {
			$new_height = $max_height;
			$new_width = round(($width * $new_height) / $height);
		}
		
		return $new_width;
	}
	
	function get_related_items($num_items = 3, $relationship_threshold = 1, $conditions = '') { //returns an array of ids
		
		// assume we won't have any related items
		$related_items = array(); 
		
		//first, get an array of the current item's keywords
		
		$this_items_keywords = array_filter(explode('|', $this->data['keyword_ids']));
		
		//next, we have to loop through each other items' keywords and compute the intersection of the arrays
		$q = "SELECT id, keyword_ids FROM " . $this->db_table . " WHERE disabled = 0 AND id != " . $this->data["id"] . " " . $conditions . " ORDER BY post_date DESC";    
		$results = mysql_query($q);
		while ($row = mysql_fetch_array($results)) {
			
			// we want to get the other items' keywords
			$other_items_keywords = array_filter(explode('|', $row["keyword_ids"]));
			
			//do the comparison
			$intersect = array_intersect($this_items_keywords, $other_items_keywords);
			
			// this will give us a new array ($intersect) - the more values in the array, the higher threshold of relationship
			$relationship_likelyhood = count($intersect);
			
			// if the relationship likelyhood >= the threshold we set in the constructor...
			if ($relationship_likelyhood >= $relationship_threshold) {
				//add this product id to the related_products array
				$related_items[$row["id"]] = $relationship_likelyhood;
			}
			
		}
		
		// then, lets sort the array by the likelyhood before returning it
		arsort($related_items);  //related_products is passed by reference
		reset($related_items);
		
		//remember, the formala is: related products[PRODUCT_ID] => LIKELYHOOD_OF_RELATIONSHIP		
		return array_slice($related_items, 0, $num_items, true); 
	
	}
	
	function add_view() {
		$this_hour = date("Y-m-d");
		
		$q = "SELECT views FROM " . $this->db_table . "_views WHERE view_date = '$this_hour' AND " . $this->db_table . "_id = " . $this->data["id"];
		$results = mysql_query($q);
		
		if (mysql_num_rows($results) > 0) {
			$q = "UPDATE " . $this->db_table . "_views SET views = views + 1 WHERE " . $this->db_table . "_id = " . $this->data["id"] . " AND view_date = '$this_hour'";
		} else {
			$q = "INSERT INTO " . $this->db_table . "_views SET views = 1, " . $this->db_table . "_id = " . $this->data["id"] . ", view_date = '$this_hour'";
		}

		$success = mysql_query($q);
		
		return $success;
	}
	
	function get_views($start_date = '') {
		$q = "SELECT SUM(views) AS total_views FROM " . $this->db_table . "_views WHERE " . $this->db_table . "_id = " . $this->data["id"];
		if ($start_date != '') $q .= " AND view_date >= '" . date("Y-m-d H:i:s", strtotime($start_date)) . "'";
		
		$results = mysql_query($q);
		$row = mysql_fetch_array($results);
		
		return ($row["total_views"] == '') ? 0 : $row["total_views"];
	}
	
	function get_most_viewed($limit = 4, $start_date = '') {
		$q = "SELECT " . $this->db_table . "_id, SUM(views) AS total_views FROM " . $this->db_table . "_views WHERE " . $this->db_table . "_id != " . $this->data["id"];
		if ($start_date != '') $q .= " AND view_date >= '" . date("Y-m-d H:i:s", strtotime($start_date)) . "'";	
		$q .= " GROUP BY " . $this->db_table . "_id ORDER BY total_views DESC LIMIT $limit";
		
		$results = mysql_query($q);
		$items = array();
		while ($row = mysql_fetch_array($results)) {
			$items[] = $row[$this->db_table . "_id"];
		}
		
		return $items;
	}
}

?>