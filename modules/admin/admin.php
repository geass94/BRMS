<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<?php
global $user_id, $username, $lang, $url, $cGET;

if(isset($cGET)){
	if($cGET == 'profile')
		include_once("profile.php");
	elseif($cGET == 'waiters')
		include_once("waiters.php");
	else
		include_once("profile.php");
}else{
	include_once("profile.php");
}

?>