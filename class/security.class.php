<?php
if(!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } 

//-- Mtavari klasis interfeisi (GTXOVT ARAPERI SHECVALOT!)
interface iSecurity {
	public function error($error_str, $width);
	public function checkParam($type, $param);
	public function filter_text($text_data);
}
//
//-- Mtavari klasis dasacyisi
//
class Security implements iSecurity {

	public static function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException('$dirPath must be a directory');
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}

	//
	// Metodi #1 - Shecdoma
	//
	public function error($error_str, $width, $bg_img="error.gif", $bg_position="center left", $redirect_second=600) {
	echo "\n<div id=\"error\" style=\"
	width:$width;
	background-image:url(".SITE.TPL_DIR.TPL_NAME."/images/$bg_img);
	background-position:$bg_position;
	\">";
	echo $error_str;
	echo "</div>";
	echo "\n";
	
	echo "<script type=\"text/javascript\"><!-- \n";
	echo "setTimeout(\"document.location.href='".SITE."'\", {$redirect_second}000);";
	echo "\n --></script>";
	}
	
	//
	// Metodi #2 - Parametris shemocmeba
	//
	public function checkParam($type, $param) {
		if($type == "id") { // ubralod ID-is shemocmeba
			$bool = (preg_match("/^[0-9]+$/", $param)) && ($param != NULL) && ($param != false) ? true : false;	
			return $bool;

		} elseif($type == "mGET") { // module GET-is shemocmeba
			$bool = (preg_match("/^[0-9a-zA-Z]+$/", $param)) && ($param != NULL) ? true : false;
			return $bool;
			
		} elseif($type == "user") { // user GET
			$bool = (preg_match("/^[0-9a-zA-Z\._-]{3,20}+$/", $param)) && ($param != NULL) ? true : false;
			return $bool;

		} elseif($type == "action") { // CP action GET
			$bool = (preg_match("/^[a-zA-Z_-]+$/", $param)) && ($param != NULL) ? true : false;
			return $bool;
		
		} else {
			return false;
		}
	}
	//
	// Metodi #3 - Textis gapiltvra
	//
	function filter_text($text_data) {
		$text_data = trim($text_data);
		$text_data = str_replace("<div", "<span", $text_data);
		$text_data = str_replace("div>", "span>", $text_data);

		$text_data = str_replace("\"", "&#34;", $text_data);
		$text_data = str_replace("'", "&#39;", $text_data);
		$text_data = str_replace("<", "&lt;", $text_data);
		$text_data = str_replace(">", "&gt;", $text_data);
		$text_data = str_replace("-", "&#45;", $text_data);
		//$text_data = str_replace(";", "&#59;", $text_data);
		$text_data = str_replace("=", "&#61;", $text_data);

		$text_data = nl2br($text_data);
		return $text_data;
	}
	function return_htmltags($text_data) {
		//$text_data = trim($text_data);
		$text_data = str_replace("&#34;", "'", $text_data);
		$text_data = str_replace("&#39;", "'", $text_data);
		$text_data = str_replace("&lt;", "<", $text_data);
		$text_data = str_replace("&gt;", ">", $text_data);
		$text_data = str_replace("&#45;", "-", $text_data);
		//$text_data = str_replace("&#59;", ";", $text_data);
		$text_data = str_replace("&#61;", "=", $text_data);
		$text_data = strip_tags($text_data, '<b><strong><p><br><ul><li><span><iframe><embed><img><table><tr><td><a>');
		//$text_data = nl2br($text_data);
		return $text_data;
	}

	function return_autocomplete($text_data){
		//$text_data = trim($text_data);
		$text_data = str_replace("&#34;", "'", $text_data);
		$text_data = str_replace("&#39;", "'", $text_data);
		$text_data = str_replace("&lt;", "<", $text_data);
		$text_data = str_replace("&gt;", ">", $text_data);
		$text_data = str_replace("&#45;", "-", $text_data);
		//$text_data = str_replace("&#59;", ";", $text_data);
		$text_data = str_replace("&#61;", "=", $text_data);
		return $text_data;
	}

	//
	// Metodi #4 - Captcha
	//
	function getCaptcha($input_text = false) {
		$captcha = "<img src=\"".SITE.TLS_DIR."captcha/captcha.php\" alt=\"Security Code\" width=\"96\" height=\"20\" align=\"left\" />\n ";
		if($input_text === true) {
			$captcha .= "<input type=\"text\" name=\"captcha\" style=\"width:48px; margin-left:4px; text-indent:5px;\" class=\"input_text\" maxlength=\"4\" onkeyup=\"this.value=this.value.toUpperCase();\" />";		
		}
		return $captcha;
	}
	//
	// Metodi #5 - Check Captcha
	//
	function checkCaptcha() {
		if(@$_POST['captcha'] == @$_SESSION['captcha']) {
			return true;
		} else {
			return false;
		}
	}
} //-- Mtavari klasis dasasruli

?>