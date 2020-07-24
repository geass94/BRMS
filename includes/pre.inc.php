<?php
if(!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); }

function is_bot(){
if(empty($_SERVER['HTTP_USER_AGENT']) ) {return false;}
else{

	$botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi",
	"looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
	"Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
	"crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
	"msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
	"Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
	"Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot",
	"Butterfly","Twitturls","Me.dium","Twiceler","spider","Spider","facebook");
	foreach($botlist as $bot){
		if(strpos($_SERVER['HTTP_USER_AGENT'],$bot)!==false)
		return true;	// Is  bot
	}
 
	return false;	// Not 
}
}

//////////////////////////////////functions end /////////////////////////////////////////

/*USER DATA GLOBAL VARIABLES*/
$user_id = $_SESSION['userid'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$secret = $_SESSION['secret'];
$approved = $_SESSION['approved'];
$role = $_SESSION['role'];
$key = '2f634a1d399dae474dd4ef6a23a762b9';
/*USER DATA GLOBAL VARIABLES*/

$now = date('Y-m-d');
$month = date('F');
$security_key = md5(date("Ymdhis"));
$spamprotect_key = md5(date("Ymdhi"));

$url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$url = "/".$url[3];

?>