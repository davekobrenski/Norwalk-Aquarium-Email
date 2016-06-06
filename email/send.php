<?php
header('Content-Type: application/json');
ini_set('default_charset', 'utf-8');
include('functions/all_fns.php');

/** CONFIGURATION */

//basic email configuration:

//specify the filename of the json file that holds the mail smtp settings
//this file should exist in the same directory as the send.php file.
//(the json file contains the settings needed to connect to the mail server)
$mailSettingsFile = "smtp.json"; //i.e., "smtp.json"

$logDebug = true; //true or false, to enable/disable logging. turn off for production

/**
* END CONFIGURATION 
* no need to edit below here
*/




$jsonOut = array();
$attachmentsDir = __DIR__ . "/attachments";

//get mail settings from json file - can override in POST if need be (for testing)
if(isset($_POST["smtp"]) && $_POST["smtp"] != '') {
	$mailSettingsFile = $_POST["smtp"];
}

$smtpFile = __DIR__ . "/$mailSettingsFile";
if(is_file($smtpFile)) {
	$settings = file_get_contents($smtpFile);
	$mailSettings = json_decode($settings, true);
	if(!is_array($mailSettings)) {
		$jsonOut["error"] = 1;
		$jsonOut["message"] = "Problem parsing JSON config file.";
		echo json_encode($jsonOut);
		exit;
	}
	$from_email = $mailSettings["fromEmail"]; // "dave@bbmdesigns.com"; //email address of sender here
	$from_name = $mailSettings["fromName"]; //"Maritime Aquarium at Norwalk"; //name of sender here
	$subject = $mailSettings["defaultSubject"]; //"Your fish looks great!";
} else {
	$jsonOut["error"] = 1;
	$jsonOut["message"] = "JSON mail settings not found.";
	echo json_encode($jsonOut);
	exit;
}

//map the images to IDs
$imageList = array();
$images = glob($attachmentsDir .'/{*.jpg}', GLOB_BRACE);
if(count($images) > 0) {
	foreach($images as $file) {
		$name = basename($file);
		$id = preg_replace('/\D/', '', basename($file, ".jpg"));
		$imageList["$id"] = $name;
	}
}

$user = "rlmg";
$password = "norwalk";

//we need user, pass, email and name
switch(true){
	case empty($_POST['user']):
	case empty($_POST['password']):
	case $_POST['user'] !== $user:
	case $_POST['password'] !== $password:
		$jsonOut["error"] = 1;
		$jsonOut["message"] = "Invalid user/pass";
		break;
	case empty($_POST['email']):
	case empty($_POST['name']):
		$jsonOut["error"] = 1;
		$jsonOut["message"] = "Email and name fields required";
		break;
}

if(isset($jsonOut["error"])) {
	if($jsonOut["error"] == 1) {
		echo json_encode($jsonOut);
		exit;
	}
}

$data = array();
foreach($_POST as $key=>$val) {
	if(is_array($val)) {
		$data["$key"] = $val;
	} else {
		$data["$key"] = trim(strip_tags(html_entity_decode($val)));
	}
}

//if(!isset($data["join-list"])) $data["join-list"] = 0;

unset($data["opts"]);
$imagesArray = null;
if(!empty($data["images"])) {
	$imagesArray = json_decode($data["images"], true);
}

if(!is_array($imagesArray)) $imagesArray = array(); //avoid php errors later

$jsonOut["data"] = $data;
$jsonOut["data"]["images"] = $imagesArray;

//get the html and text versions of the email
$html = file_get_contents("email.html");
$text = file_get_contents("email.txt");

//personalization
$html = str_replace("{email}", $data["email"], $html);
$html = str_replace("{fromemail}", $from_email, $html);
$text = str_replace("{email}", $data["email"], $text);
$text = str_replace("{fromemail}", $from_email, $text);

//echo json_encode($mailSettings, JSON_PRETTY_PRINT); exit;

if(!empty($data["email"]) && !empty($data["name"]) && !empty($from_email) && !empty($html)) {
	if(filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
		//proceed with sending
		$mail = new PHPMailer();
		
		$mail->IsSMTP();
		$mail->Host = $mailSettings["mailHost"];
		$mail->SMTPAuth = true;
		$mail->Username = $mailSettings["mailUser"];
		$mail->Password = $mailSettings["mailPass"];
		$mail->SMTPSecure = $mailSettings["mailEncrypt"];
		$mail->Port = $mailSettings["mailPort"];
		
		$mail->CharSet = 'utf-8';
		$mail->From = $from_email;
		$mail->FromName = $from_name;
		$mail->Subject = $subject;
		$mail->IsHTML(true);
		$mail->Body = html_entity_decode($html);
		$mail->AltBody = $text;
		$mail->WordWrap = 80;
		$mail->AddAddress($data["email"], $data["name"]);
		
		//not using this here, but just in case we need it
		if(is_array($imagesArray) && count($imagesArray) > 0 && is_array($imageList) && count($imageList) > 0) {
			foreach($imagesArray as $imgID) {
				if(array_key_exists($imgID, $imageList)) {
					$img = $imageList["$imgID"];
					$imgFile = $attachmentsDir . "/$img";
					if(is_file($imgFile)) {
						$mail->AddAttachment($imgFile, $img);
					}
				}	
			}
		}
		
		// for each uploaded file
		foreach($_FILES as $file) {
			if($file['error'] == 0 && ($file["type"] == "image/jpg" || $file["type"] == "image/jpeg")) { //&& $file["size"] <= 600000 if there's no error and its a jpg and its less than 600kb
				$source = $file['tmp_name']; // location of temporary file
				$filename = $file['name']; // filename from the client
				//just attach it
				$mail->AddAttachment($source, $filename);// add file as attachment
			}
		}
		
		//deal with any base64 encoded images, if any
		$accept = array("image/jpeg", "image/gif", "image/png");
		$file_base64 = $_POST["fileUpload"];
		if(!empty($file_base64)) {
			if(is_array(json_decode($file_base64, true))) {
				$jsonFiles = json_decode($file_base64, true);
			} else {
				$jsonFiles[] = $file_base64;
			}
				
			foreach($jsonFiles as $file) {
				list($dataType) = explode(';',$file);
				$imgType = substr($dataType, strpos($dataType, ":")+1);
				if(in_array($imgType, $accept)) {
					$ext = substr($imgType, strpos($imgType, "/")+1);
					if($ext == 'jpeg') $ext = 'jpg';
					$dataString = substr($file, strpos($file, ",")+1);
					$filename = 'attachment.' . $ext;
					if($dataString != '') {
						$mail->AddStringAttachment(base64_decode($dataString), $filename, 'base64', $imgType);
					}
				}
			}	
		}
		
		//send to visitor
		if($mail->Send()) {
			$jsonOut["success"] = 1;
			$jsonOut["message"] = 'Message has been sent successfully.';
			//send to admin, if user opted in
			/* if(filter_var($adminEmail, FILTER_VALIDATE_EMAIL) && $data["join-list"] == 1) {
				$mail->clearAddresses();
				$mail->clearAttachments();
				$mail->AddAddress($adminEmail, $adminName);
				$mail->Subject = $adminSubject;
				$mail->IsHTML(false);
				$mail->Body = $data["name"] . " " . $data["email"];
				$mail->Send();
			} */
		} else {
			$jsonOut["error"] = 1;
			$jsonOut["message"] = 'Mailer Error: ' . $mail->ErrorInfo;
		}	
	} else {
		$jsonOut["error"] = 1;
		$jsonOut["message"] = "Invalid email address.";
	}	
} else {
	$jsonOut["message"] = "Required fields not set.";
	$jsonOut["error"] = 1;
}

$jsonOut["time"] = number_format(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 2) . " seconds";
if($logDebug) logData("$mailSettingsFile | ".count($imagesArray)." attachment".(count($imagesArray)==1 ? '' : 's')." | " . $jsonOut["time"], __DIR__);

echo json_encode($jsonOut, JSON_PRETTY_PRINT);
	
?>