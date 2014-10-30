<?php

require_once("../config/config.php");
	
/* weblog_pinger.php

Weblog_Pinger PHP Class Library by Rogers Cadenhead
Version 1.6
Web: http://workbench.cadenhead.org/weblog-pinger

Copyright (C) 2009 Rogers Cadenhead

The Weblog_Pinger class can send a ping message over XML-RPC to
weblog notification services such as Weblogs.Com, Blo.gs,
and Technorati.

This class should be stored in a directory accessible to
the PHP scripts that will use it.

This software requires the XML-RPC for PHP class library by
Usefulinc: http://xmlrpc.usefulinc.com/php.html.

On a server equipped with MySQL, Weblog_Pinger can ensure not
to overload a ping notification service by hitting it too
often.

Example use:

require('weblog_pinger.php');
$pinger = new Weblog_Pinger();
echo $pinger->ping_ping_o_matic("Ekzemplo",
    "http://www.ekzemplo.com/");

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. */

// include the XML-RPC class library
require_once('xmlrpc/xmlrpc.inc');

class Pinger {
    // Weblogs.Com XML-RPC settings
    var $weblogs_com_server = "rpc.weblogs.com";
    var $weblogs_com_port = 80;
    var $weblogs_com_path = "/RPC2";
    var $weblogs_com_method = "weblogUpdates.ping";
    var $weblogs_com_extended_method = "weblogUpdates.extendedPing";
    // Blo.gs XML-RPC settings
    var $blo_gs_server = "ping.blo.gs";
    var $blo_gs_port = 80;
    var $blo_gs_path = "/";
    var $blo_gs_method = "weblogUpdates.ping";
    // Feedburner XML-RPC settings
    var $feedburner_server = "ping.feedburner.com";
    var $feedburner_port = 80;
    var $feedburner_path = "/RPC2";
    var $feedburner_method = "weblogUpdates.ping";
    // Ping-o-Matic XML-RPC settings
    var $ping_o_matic_server = "rpc.pingomatic.com";
    var $ping_o_matic_port = 80;
    var $ping_o_matic_path = "/RPC2";
    var $ping_o_matic_method = "weblogUpdates.ping";
    // Technorati XML-RPC settings
    var $technorati_server = "rpc.technorati.com";
    var $technorati_port = 80;
    var $technorati_path = "/rpc/ping";
    var $technorati_method = "weblogUpdates.ping";
    // Audio.Weblogs.Com XML-RPC settings
    var $audio_weblogs_com_server = "audiorpc.weblogs.com";
    var $audio_weblogs_com_port = 80;
    var $audio_weblogs_com_path = "/RPC2";
    var $audio_weblogs_com_method = "weblogUpdates.ping";
    // Simplaris Blogcast XML-RPC settings
    var $simplaris_blogcast_server = "blogcast.simplaris.com";
    var $simplaris_blogcast_port = 80;
    var $simplaris_blogcast_path = "/ping";
    var $simplaris_blogcast_method = "weblogUpdates.ping";
    // log settings
    var $log_file = "/var/log/ping.log";
    var $log_level = "short"; // full, short, or none;
    // database settings
    // the machine hosting the MySQL server
    var $database_server = "";
    // the MySQL database
    var $database_name = "";
    // the MySQL user with access to the database
    var $database_user = "";
    // the MySQL user password
    var $database_password = "";

    var $software_version = "1.6";
    var $debug = false;

    // report errors
    function report_error($message) {
        error_log("Weblog Pinger: " . $message);
    }

    /* Ping Weblogs.Com to indicate that a weblog has been updated. Returns true
    on success and false on failure. */
    function ping_weblogs_com($weblog_name, $weblog_url, $changes_url = "", $category = "") {
        return $this->ping($this->weblogs_com_server, $this->weblogs_com_port,
            $this->weblogs_com_path, $this->weblogs_com_method, $weblog_name,
            $weblog_url, $changes_url, $category);
    }

    /* Ping Blo.gs to indicate that a weblog has been updated. Returns true on success
    and false on failure. */
    function ping_blo_gs($weblog_name, $weblog_url, $changes_url = "", $category = "") {
        return $this->ping($this->blo_gs_server, $this->blo_gs_port,
            $this->blo_gs_path, $this->blo_gs_method, $weblog_name, $weblog_url,
            $changes_url, $category);
    }

    /* Ping Technorati to indicate that a weblog has been updated. Returns true on
    success and false on failure. */
    function ping_technorati($weblog_name, $weblog_url, $changes_url = "", $category = "") {
        return $this->ping($this->technorati_server, $this->technorati_port,
            $this->technorati_path, $this->technorati_method, $weblog_name, $weblog_url,
            $changes_url, $category);
    }

    /* Ping Pingomatic to indicate that a weblog has been updated. Returns true on success
    and false on failure. */
    function ping_ping_o_matic($weblog_name, $weblog_url, $changes_url = "", $category = "") {
        return $this->ping($this->ping_o_matic_server, $this->ping_o_matic_port,
            $this->ping_o_matic_path, $this->ping_o_matic_method, $weblog_name,
            $weblog_url, $changes_url, $category);
    }

    /* Ping Simplaris Blogcast to indicate that a weblog has been updated. Returns true on success
    and false on failure. */
    function ping_simplaris_blogcast($weblog_name, $weblog_url, $ping_id, $changes_url = "", $category = "") {
        return $this->ping($this->simplaris_blogcast_server, $this->simplaris_blogcast_port,
            "{$this->simplaris_blogcast_path}/{$ping_id}/", $this->ping_o_matic_method, $weblog_name,
            $weblog_url, $changes_url, $category);
    }

    /* Ping most of the above services to indicate that a weblog has been updated.
    Returns true on success and false on failure. */
    function ping_all($weblog_name, $weblog_url, $changes_url = "", $category = "") {
        $error[0] = $this->ping_technorati($weblog_name, $weblog_url, $changes_url, $category);
        $error[1] = $this->ping_weblogs_com($weblog_name, $weblog_url, $changes_url, $category);
        $error[2] = $this->ping_ping_o_matic($weblog_name, $weblog_url, $changes_url, $category);
	    $all_ok = $error[0] & $error[1];
	    return array($all_ok, $error);
    }

    /* Ping Feedburner to indicate that a weblog has been updated. Returns true on success
    and false on failure. */
    function ping_feedburner($weblog_name, $weblog_url, $changes_url = "", $category = "") {
        return $this->ping($this->feedburner_server, $this->feedburner_port,
            $this->feedburner_path, $this->feedburner_method, $weblog_name,
            $weblog_url, $changes_url, $category);
    }

    /* Ping Audio.Weblogs.Com to indicate that a weblog with a podcast has been updated.
    Returns true on success and false on failure. */
    function ping_audio_weblogs_com($weblog_name, $weblog_url, $changes_url = "",
        $category = "") {

        return $this->ping($this->audio_weblogs_com_server, $this->audio_weblogs_com_port,
            $this->audio_weblogs_com_path, $this->audio_weblogs_com_method, $weblog_name,
            $weblog_url, $changes_url, $category);
    }

    /* Ping Weblogs.Com (extended version) to indicate that a weblog has been updated.
    Returns true on success and false on failure. */
    function ping_weblogs_com_extended($weblog_name, $weblog_url, $changes_url, $rss_url) {
        if ($this->debug) $this->report_error(
            "Sending extended ping to Weblogs.Com for "
            . "$weblog_name, $weblog_url, $changes_url, $rss_url");
        return $this->ping($this->weblogs_com_server, $this->weblogs_com_port,
            $this->weblogs_com_path, $this->weblogs_com_extended_method, $weblog_name,
            $weblog_url, $changes_url, $rss_url, true);
    }


    /* Multi-purpose ping for any XML-RPC server that supports the Weblogs.Com interface. */
    function ping($xml_rpc_server, $xml_rpc_port, $xml_rpc_path, $xml_rpc_method,
        $weblog_name, $weblog_url, $changes_url, $cat_or_rss, $extended = false) {

        // see how recently a ping was sent to the server for this url
        $db_response = $this->check_ping($xml_rpc_server, $weblog_url);
        $db_dex = 0;
        if ($db_response['timechecked'] > 0) {
        	$when = strtotime($db_response['timechecked']);
        	$db_dex = $db_response['dex'];
        	if (time() - $when < 300) {
        		// last ping less than 5 minutes ago, so don't send another ping
        		return true;
        	}
        }

        // build the parameters
        $name_param = new xmlrpcval($weblog_name, 'string');
        $url_param = new xmlrpcval($weblog_url, 'string');
        $changes_param = new xmlrpcval($changes_url, 'string');
        $cat_or_rss_param = new xmlrpcval($cat_or_rss, 'string');
        $method_name = "weblogUpdates.ping";
        if ($extended) $method_name = "weblogUpdates.extendedPing";

        if ($cat_or_rss != "") {
            $params = array($name_param, $url_param, $changes_param, $cat_or_rss_param);
            $call_text = "$method_name(\"$weblog_name\", \"$weblog_url\", \"$changes_url\", \"$cat_or_rss\")";
        } else {
            if ($changes_url != "") {
              $params = array($name_param, $url_param, $changes_param);
              $call_text = "$method_name(\"$weblog_name\", \"$weblog_url\", \"$changes_url\")";
          } else {
              $params = array($name_param, $url_param);
              $call_text = "$method_name(\"$weblog_name\", \"$weblog_url\")";
            }
        }

        // create the message
        $message = new xmlrpcmsg($xml_rpc_method, $params);
        $client = new xmlrpc_client($xml_rpc_path, $xml_rpc_server,
            $xml_rpc_port);
        $response = $client->send($message);
        // log the message
        $this->log_ping("Request: " . strftime("%D %T") . " " . $xml_rpc_server . $xml_rpc_path . " " . $call_text);
        $this->log_ping($message->serialize(), true);
        // record the ping in the database
        $this->update_ping($db_dex, $xml_rpc_server, $weblog_url);
        if ($response == 0) {
            $error_text = "Error: " . $xml_rpc_server . ": " . $client->errno . " "
                . $client->errstring;
            $this->report_error($error_text);
            $this->log_ping($error_text);
            return false;
        }
        if ($response->faultCode() != 0)  {
            $error_text = "Error: " . $xml_rpc_server . ": " . $response->faultCode()
                . " " . $response->faultString();
            $this->report_error($error_text);
            return false;
        }
        $response_value = $response->value();
        if ($this->debug) $this->report_error($response_value->serialize());
        $this->log_ping($response_value->serialize(), true);
        $fl_error = $response_value->structmem('flerror');
        $message = $response_value->structmem('message');

        // read the response
        if ($fl_error->scalarval() != false) {
            $error_text = "Error: " . $xml_rpc_server . ": " . $message->scalarval();
            $this->report_error($error_text);
            $this->log_ping($error_text);
            return false;
        }

        return true;
    }

    /* Ping any server that supports PubSubHubbub's REST interface. The first argument can be a single URL of an RSS feed
       that has been updated or an array of several feeds. The second argument is the URL of the hub. */
    function ping_rest($updated_urls, $ping_server = "http://pubsubhubbub.appspot.com/") {
        // log the ping
        $this->log_ping("Request: " . strftime("%D %T") . " " . $ping_server);

    	if (!isset($updated_urls)) {
        	return false;
        }
        if (!is_array($updated_urls)) {
            $updated_urls = array($updated_urls);
        }

        // set up the HTTP request parameters
        $params = "hub.mode=publish";
        foreach ($updated_urls as $updated_url) {
            $params .= "&hub.url=" . urlencode($updated_url);
        }
		// make the request
		$options = array(CURLOPT_URL => $ping_server,
             CURLOPT_POST => true,
             CURLOPT_POSTFIELDS => $params,
             CURLOPT_USERAGENT => "Weblog-Pinger/{$this->$software_version}");
		$ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        // receive the response
        $info = curl_getinfo($ch);
        curl_close($ch);
        if ($info['http_code'] != 204) {
            // log the error
            $this->log_ping($response);
            return false;
        }
        return true;
    }

    /* Save ping data to a log file */
    function log_ping($message, $xml_data = false) {
        if ($this->log_level == "none") {
            return;
        }
        if (($this->log_level == "short") & ($xml_data)) {
            return;
        }
        if (!is_writable($this->log_file)) {
            $this->report_error("File {$this->log_file} is not writable");
            return;
        }
        $fhandle = fopen($this->log_file, "a");
        fwrite($fhandle, $message . "\r\n");
        fclose($fhandle);
    }


    /* Configure the MySQL database */
    function configure_database($database_server, $database_user, $database_password, $database_name) {
    	$this->database_server = $database_server;
    	$this->database_user = $database_user;
    	$this->database_password = $database_password;
    	$this->database_name = $database_name;
    	// $this->report_error("$database_server, $database_user, $database_password, $database_name");
    }

    /* Connect to the MySQL database */
    function connect_to_database() {
    	// make sure the database has been configured
    	if ($this->database_name == "") {
    		return false;
    	}
        $db = mysql_connect($this->database_server, $this->database_user,
            $this->database_password);
        if (!$db) {
            $this->report_error("Could not connect to database.");
            return false;
        } else {
            mysql_select_db($this->database_name);
            return true;
        }
    }

    /* Process a MySQL query */
    function process_query($query) {
        if (!$this->connect_to_database()) return false;
        $result = mysql_query($query);
        if ($result === false) {
            $this->report_error(mysql_error());
            $this->report_error($query);
        }
        return $result;
    }

    /* Lock the database */
    function lock_table($read_only = false) {
        $query = "LOCK TABLES $this->database_name WRITE";
        if ($read_only) {
            $query = "LOCK TABLES $this->database_name READ";
        }
        $result = mysql_query($query);
    }

    /* Unlock the database */
    function unlock_table() {
        $query = "UNLOCK TABLES";
        $result = mysql_query($query);
    }

    /* Create the MySQL database */
    function create_database() {
    	$query = "CREATE TABLE pingcheck ("
    		. "dex MEDIUMINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, "
    		. "url TINYTEXT, "
    		. "server TINYTEXT, "
    		. "timechecked DATETIME"
    		. ");";
    	return $this->process_query($query);
    }

    /* Check the last time a server was pinged for a URL */
    function check_ping($server, $url) {
    	if (($server == "") | ($url == "")) {
    		return false;
    	}
    	$query = "SELECT dex, timechecked FROM pingcheck WHERE server = '$server' AND url = '$url'";
    	$result = $this->process_query($query);
    	if ($result === false) {
    		return false;
    	}
    	return mysql_fetch_array($result);
    }

    /* Record a ping in the database */
    function update_ping($dex, $server, $url) {
    	$when = strftime("%Y/%m/%d %H:%M:%S", time());
    	$query = "REPLACE INTO pingcheck VALUES($dex, '$url', '$server', '$when')";
    	return $this->process_query($query);
	}
}

?>