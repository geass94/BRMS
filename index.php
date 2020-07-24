<?php
// $options = ['cost' => 12];
// echo password_hash("1234", PASSWORD_BCRYPT, $options);
require_once ("inc.php");
global $user_id, $username, $email, $birthday, $level, $role, $lang;
if(isset($_GET['mod'])) { $mGET = $_GET['mod']; } else { unset($mGET); $mGET = null; } // module GET
if(isset($_GET['cat'])) { $cGET = $_GET['cat']; } else { unset($cGET); $cGET = null; } // category GET
//shemogvaqvs head
	require_once(TPL_DIR."header.php");

if(!empty($user_id) && !user_approved()) {
	require_once (TPL_DIR."approve.php");
}
else{ // HERE STARTS ALL CONTENT IF USER HAS ACTIVATED ACCOUNT

	###content
		require_once (MOD_DIR."modules.php");
if(is_authed()){
	if(!isset($mGET)) { 
		$mGET = "startpage";
		getMod($mGET);

	} else { getMod($mGET); }
}
else{
	$mGET = "startpage";
	getMod($mGET);
}
	###content END
} // HERE ENDS ALL CONTENT IF USER HAS ACTIVATED ACCOUNT
	require_once (TPL_DIR."footer.php");
?>