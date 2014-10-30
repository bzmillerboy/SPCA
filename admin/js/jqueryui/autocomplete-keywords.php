<?php

// Connect to the database
require_once("../../../config/config.php");
require_once("../../../class/class.Login.php");
	
$login = new Login();
$login->check_access(1);	
 
$value = $_GET["term"]; 

$keywords = array();

$q = "SELECT name FROM keywords WHERE name REGEXP '^$value'";
$results = mysql_query($q);
while ($row = mysql_fetch_array($results)) {
	$keywords[] = $row["name"];  
	//$keywords[] = array("label" => $row["name"], "value" => $row["id"]);  
}
  
//echo JSON to page  
echo json_encode($keywords);
exit();

?>