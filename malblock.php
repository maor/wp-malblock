<?php
/*
Plugin Name: WP-MALBLOCK
Plugin URI: http://github.com/maor/wp-malblock
Description: Protect WordPress Against Malicious HTTP Requests
Author: Maor (Henry) Hazan
Version: 0.2b
*/
global $user_ID; // We need the User ID to know if we have the rights to run the script.

if($user_ID) {
	if(!current_user_can('level_10')) { // Do the user have the rights to run the script?
		if (strlen($_SERVER['REQUEST_URI']) > 255 || // if the request URI is too long... it must mean something!
			strpos($_SERVER['REQUEST_URI'], "CONCAT") || // CONTACT requests out
			strpos($_SERVER['REQUEST_URI'], "eval(") || // Who in earth would use eval() in the request URI? Bots!
			strpos($_SERVER['REQUEST_URI'], "UNION SELECT") ||
			strpos($_SERVER['REQUEST_URI'], "base64")) { // The same thing.
				@header("HTTP/1.1 414 Request-URI Too Long"); // Sent a HTTP header Request too long
				@header("Status: 414 Request-URI Too Long"); // Status indicator
				@header("Connection: Close"); // Closing connection with the bot or attacker.
				@exit; // Goodbye.
		}
	}
}

?>
