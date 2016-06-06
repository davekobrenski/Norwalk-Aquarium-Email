<?php
ini_set('default_charset', 'utf-8');
include('functions/all_fns.php');

if(isset($_POST["message"]) && isset($_POST["smtp"])) {
	$message = strip_tags(trim($_POST["message"]));
	$smtp = strip_tags(trim($_POST["smtp"]));
	$numImages = 0;
	if(isset($_POST["images"]) && is_numeric($_POST["images"])) {
		$numImages = $_POST["images"];
	}
	logData("$smtp | ".$numImages." attachment".($numImages==1 ? '' : 's')." | " . $message, __DIR__);
}

?>