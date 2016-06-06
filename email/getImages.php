<?php
header('Content-Type: application/json');
ini_set('default_charset', 'utf-8');

$relFolder = "attachments";
$attachmentsDir = __DIR__ . "/$relFolder";
$baseUrl = "http://" . $_SERVER["HTTP_HOST"]  . dirname($_SERVER["SCRIPT_NAME"]) ."/$relFolder/";
$imageList = array();
$images = glob($attachmentsDir .'/{*.jpg}', GLOB_BRACE);

$i=0;
foreach($images as $file) {
	$name = basename($file);
	$id = preg_replace('/\D/', '', basename($file, ".jpg"));
	$imageList[$i]["id"] = $id;
	$imageList[$i]["name"] = $name;
	$imageList[$i]["url"] = $baseUrl . $name;
	$i++;
}

echo json_encode($imageList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
?>