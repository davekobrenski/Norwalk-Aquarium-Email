<?php
	include('functions/all_fns.php');
	
	$context = stream_context_create();
	//$host = "smtp.office365.com";
	$host = "mail.rlmg2.com"; 
	$port = 25;
	$fp = @stream_socket_client('tls://'.$host.':'.$port, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
	
	arr($fp);
	arr($errno . " " . $errstr);
?>