<?php

$prewd_auth = getcwd(); // get the current working directory
chdir(realpath(dirname(__FILE__))); // change working directory to the location of this file
	
require_once("../config/config.php");
require_once("class.Facebook.php");
		
chdir($prewd_auth); // change back to previous working dir
	
class Login {
	var $person_id;
	var $logged_in;
	var $person_cat_ids;
	var $allow_facebook;
	var $default_success_url;
	var $default_fail_url;
		
	function Login($allow_facebook = false) {
		$this->allow_facebook = $allow_facebook;
		$this->default_success_url = CMS_ROOT . 'index.php';
		$this->default_fail_url = CMS_ROOT . 'login.php';
			
		@session_start();		
			
		// Not logged in until proven otherwise...
		$this->person_id = 0;
		$this->logged_in = false;
			
		if ($person_id = $this->is_facebook_connected()) {
			// Logged in by Facebook
			$this->person_id = $person_id;
			$this->logged_in = true;
		} elseif (isset($_SESSION["person_id"])) {
			// Logged in by a session
			$this->person_id = $_SESSION["person_id"];
			$this->logged_in = true;
		} elseif (isset($_COOKIE["person_id"])) {
			// Logged in by a cookie
			$this->person_id = $_COOKIE["person_id"];
			$this->logged_in = true;
		}
			
		if ($this->logged_in) {
			// Get access categories
			$q = "SELECT cat_ids FROM people WHERE id = " . $this->person_id;
			$results = mysql_query($q);
			$row = mysql_fetch_array($results);
			$this->person_cat_ids = array_filter(explode('|', $row["cat_ids"]));	
		}
			
		return true;
	}
	
	function is_facebook_connected() {
		if (FACEBOOK && $this->allow_facebook) {
			$facebook = new Facebook(array(
				'appId'  => FB_APP_ID,
				'secret' => FB_APP_SECRET,
			));
			$facebook_id = $facebook->getUser();
							
			if ($facebook_id) {
				try {
					// Logged in by Facebook and authenticated.
					return $this->get_person_id_from_facebook_id($facebook_id);
				} catch (FacebookApiException $e) {
					//echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
				}	
			}
			return false;
		} else {
			return false;	
		}
	}
		
		function is_logged_in() {
			return $this->logged_in;
		}
		
		function get_person_id() {
			return $this->person_id;
		}
		
		function get_next_url() {
			$next_url = urlencode($_SERVER["PHP_SELF"]);
			if (!empty($_SERVER["QUERY_STRING"])) $next_url .= urlencode("?" . $_SERVER["QUERY_STRING"]);
			return $next_url;
		}
		
		function check_access($cat_id_or_array, $fail_url = '') {
			$fail_url = (empty($fail_url)) ? $this->default_fail_url : $fail_url;
			$next_url = $this->get_next_url();
			$has_access = false;
			
			if ($this->person_cat_ids == '') {
				// Person doesn't have access to any category.  Buh bye!
				header("Location: " . $fail_url . "?next=" . $next_url);
				exit();	
			} else {
				if (is_array($cat_id_or_array)) {
					// Check if person is of one of the categories
					foreach ($cat_id_or_array as $cat_id) {
						if (in_array($cat_id, $this->person_cat_ids)) $has_access = true;	
					}
				} else {
					// Check if person is this category	
					if (in_array($cat_id_or_array, $this->person_cat_ids)) $has_access = true;	
				}
				
				if ($has_access) {
					return $has_access;
				} else {
					header("Location: " . $fail_url . "?next=" . $next_url); 
					exit();	
				}
			}
		}
		
		function login_person($username, $password, $duration = 0, $success_url = '', $fail_url = '') {
			$success_url = (empty($success_url)) ? $this->default_success_url : $success_url;
			$fail_url    = (empty($fail_url))    ? $this->default_fail_url    : $fail_url;
			
			if (empty($username) || empty($password)) {
				header("Location: " . $fail_url . "?emptylogin=1");
				exit();
			} else {
				// Check the database
				$q = "SELECT id FROM people " .
					 "WHERE username = '" . mysql_real_escape_string($username) . "' " . 
					 "AND password = '" .  mysql_real_escape_string($password) . "' " . 
					 "AND disabled = 0";	
					 
				$results = mysql_query($q);
				if (mysql_num_rows($results) > 0) {
					$row = mysql_fetch_array($results);
					$person_id = $row["id"];
					
					$this->login_person_by_id($person_id, $duration, $success_url, $fail_url);
				} else {
					header("Location: " . $fail_url . "?notfound=1");
					exit();
				}
			}
		}
		
		function login_person_by_id($person_id, $duration = 0, $success_url = '', $fail_url = '') {
			$success_url = (empty($success_url)) ? $this->default_success_url : $success_url;
			$fail_url    = (empty($fail_url))    ? $this->default_fail_url    : $fail_url;
			
			if ($duration == 0) {
				// Register the person in the session
				$_SESSION["person_id"] = $person_id;
			} else {
				// Register the person in a cookie	
				setcookie("person_id", $person_id, time() + $duration, '/');
			}
					
			$this->person_id = $person_id;
			$this->logged_in = true;
					
			// Get access categories
			$q = "SELECT cat_ids FROM people WHERE id = " . $person_id;
			$results = mysql_query($q);
			$row = mysql_fetch_array($results);
			$this->person_cat_ids = array_filter(explode('|', $row["cat_ids"]));	
			
			// Record login in the database
			$q = "UPDATE people SET " . 
				"last_logged_date = NOW(), " .
				"last_logged_ip = '" . $_SERVER['REMOTE_ADDR'] . "' " . 
				"WHERE id = " . $person_id;
			$success = mysql_query($q);
					
			if (isset($_GET["next"])) {				
				header("Location: " . $_GET["next"]);
				exit();
			} else {
				header("Location: " . $success_url);
				exit();
			}
		}
		
		function logout_person($logout_url = "") {
			$logout_url = (empty($logout_url)) ? $this->default_fail_url : $logout_url;
			
			// Check to see if person is logged in with Facebook
			if ($person_id = $this->is_facebook_connected()) {
				$facebook = new Facebook(array(
					'appId'  => FB_APP_ID,
					'secret' => FB_APP_SECRET,
				));
				$facebook_logout_url = $facebook->getLogoutUrl(array(
					'next' => substr('' . HTTP_DOMAIN, 0, -1) . $logout_url . '?loggedout=1/',
				));
				$facebook->destroySession();	
				header('Location: ' . $facebook_logout_url);
				exit();
			}
			
			// Check to see if person is logged in with sessions
			if (isset($_SESSION["person_id"])) {
				$_SESSION = array();
				session_destroy();
			} 
			
			// Check to see if person is logged in with cookies
			if (isset($_COOKIE["person_id"])) {
				setcookie("person_id", "", 1, "/");
			}
				
			header("Location: " . $logout_url . "?loggedout=1");
			exit();
		}
		
		function get_person_id_from_facebook_id($facebook_id) {
			$q = "SELECT id FROM people WHERE facebook_id = " . $facebook_id;
			$results = mysql_query($q);
			$row = mysql_fetch_array($results);
			return $row["id"];	
		}
		
		function get_first_name($unknown_person_text = "Visitor") {
			if ($this->person_id > 0) {
				$q = "SELECT first_name FROM people WHERE id = " . $this->person_id;
				$results = mysql_query($q);
				if (mysql_num_rows($results) > 0) {
					$row = mysql_fetch_array($results);
					$name = ucfirst($row["first_name"]);
				} else {
					$name = $unknown_person_text;
				}
			} else {
				$name = $unknown_person_text;
			}
			return $name;
		}
		
		function get_last_name($unknown_person_text = "") {
			if ($this->person_id > 0) {
				$q = "SELECT last_name FROM people WHERE id = " . $this->person_id;
				$results = mysql_query($q);
				if (mysql_num_rows($results) > 0) {
					$row = mysql_fetch_array($results);
					$name = ucfirst($row["last_name"]);
				} else {
					$name = $unknown_person_text;
				}
			} else {
				$name = $unknown_person_text;
			}
			return $name;
		}
		
		function get_username($unknown_person_text = "Unknown") {
			if ($this->person_id > 0) {
				$q = "SELECT username FROM people WHERE id = " . $this->person_id;
				$results = mysql_query($q);
				if (mysql_num_rows($results) > 0) {
					$row = mysql_fetch_array($results);
					$username = $row["username"];
				} else {
					$username = $unknown_person_text;
				}
			} else {
				$username = $unknown_person_text;
			}
			return $username;
		} 
	}
		