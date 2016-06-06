<?php

function replace_non_unicode_chars($comment_string = '') { 
	$cslen = strlen($comment_string); 
	$valid_str = ""; 
	$upper_decimal = 127; 
	$replacement_character = "?"; 
	for($ci = 0; $ci < $cslen; $ci++) { 
		if (ord(substr($comment_string, $ci, 1)) > $upper_decimal ) { 
			$valid_str .= $replacement_character; 
		} else { 
			$valid_str .= substr($comment_string, $ci, 1); 
		} 
	} 
return $valid_str; 
}

function visualizeArray($arr, $return=false, $class=null) {
	if(is_array($arr)) {
		$returnMe = '<pre><code class="'.$class.'">';
		$returnMe .= print_r($arr,true);
		$returnMe .= '</code></pre>';
	} else {
		$returnMe = '<pre><code class="'.$class.'">'.$arr.'</code></pre>';
	}
	
	if($return) return $returnMe;
	else echo $returnMe;
}

function arr($arr, $return=false, $class=null) {
	if($return) {
		$tmp = visualizeArray($arr, true, $class); 
		return $tmp;
	} else {
		visualizeArray($arr, false, $class);
	}
}

function betterToken($length=null) {
	$token = md5(uniqid(rand(), true));
	if($length && is_numeric($length)) {
		return substr($token, 0, $length);
	} else {
		return $token;
	}
}

?>