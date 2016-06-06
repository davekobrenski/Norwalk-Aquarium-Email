<?php
	date_default_timezone_set("America/New_York");
	require 'vendor/autoload.php';
	include('utility_fns.php');
	
	function logData($data, $dir) {
		$file = 'log.txt';
		$path = $dir . '/' . $file;
		if(is_file($path)) {
			//chmod($path, 0755);
			$data = "\n" . date("Y-m-d H:i:s") . " | " . $data . " ";
			file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
		}
	}
?>